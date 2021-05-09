<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(
			array(
				'Model_banner',
				'Model_pagina_dinamica',
				'Model_sede',
				'Model_servicio'
			)
		);
		$this->load->helper(
			array(
				'string_helper',
				'text',
				'form',
				'otros_helper'
			)
		);
		$this->load->library(
			array(
				'form_validation',
				'recaptcha'
			)
		);
	}

	public function index()
	{

		// $datos['recaptcha'] = $this->recaptcha->create_box();
		// $config = getConfig();

		// var_dump($config['metadata']['titulo-web']);
		// exit();
		$siteLang = $this->session->userdata('site_lang');
		$idioma = $siteLang == 'euskera' ? 'EUS' : 'CAS';
		$idioma_next = $siteLang == 'euskera' ? 'CAS' : 'EUS';
		$allInputs['idioma'] = $idioma;
		$allInputs['segmento'] = 'mensaje';
		$datos['pag_din'] = $this->Model_pagina_dinamica->m_get_pagina_dinamica($allInputs);
		$datos['sedes'] = $this->Model_sede->m_cargar_sedes_pagina($allInputs);

		foreach ($datos['sedes'] as $key => $row) {
			$row['idioma'] = $allInputs['idioma'];
			$servicios = $this->Model_servicio->m_cargar_sede_servicio($row);
			$datos['sedes'][$key]['servicios'] = $servicios;
		}

		$datos['info_mensaje'] = $this->lang->line('info_cambio');
		$datos['info_btn'] = $this->lang->line('info_aqui');
		$datos['info_url'] = base_url('main/switchLang/'.$idioma_next);

		$datos['vista'] = 'inicio_view';
		$this->load->view('home',$datos);
	}
	public function switchLang($language = "")
    {
        if($language == 'EUS'){
			$this->session->set_userdata('site_lang', 'euskera');
		}else{
			$this->session->set_userdata('site_lang', 'spanish');
		}
        redirect('/');
    }
	/**
	 * Muestra el contenido de una Ficha
	 * @param  string
	 * @return [type]
	 */
	public function ver_ficha($url) {
        $partes = explode("-", $url);
        $id = $partes[count($partes) - 1];



		// DATOS DEL BODY

		// $datos['scripts'] = '
		// 	<script src="' . base_url() . 'js/fancybox/jquery.fancybox.pack.js"></script>
		// 	<script type="text/javascript">
		// 		$(document).ready(function() {
		// 			$(".fancybox").fancybox({

		// 	    	openEffect	: \'elastic\',
		// 	    	closeEffect	: \'elastic\',
		// 			nextEffect	: \'fade\',
		// 	    	prevEffect	: \'fade\',
		// 			openSpeed : \'slow\',
		// 			closeSpeed : \'slow\',
		// 			padding: 5
		// 			});
		// 		});
		// 	</script>
		// ';
		// if($datos['anuncio'] = $this->Ficha_model->m_cargar_ficha($id)){
		// 	$datos['fotos'] = $this->Ficha_model->m_cargar_fotos_ficha($id);
		// }

		$datos['ficha'] = $this->Model_evento->m_cargar_ficha($id);
		$datos['dir_imagen'] = base_url() . 'uploads/fichas/';

		$datos['vista'] = 'ficha_view';
		$this->load->view('home',$datos);

    }

	public function registro()
	{
		#CREDENCIALES PARA LA VALIDACIÓN EN GOOGLE
		//  recaptcha comentado temporalmente
		$credential = array(
			'secret' => KEY_RECAPTCHA,
			'response' => $this->input->post('g-recaptcha-response')
		);

		#VALIDAMOS EN GOOGLE
		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
		curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($verify);
		#OBTENEMOS EL STATUS DE GOOGLE
		$status= json_decode($response, true);
		// $status['success'] = TRUE;
		if($status['success']){
			$this->form_validation->set_rules('nombre', 'Nombre', 'required');
			$this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'required|numeric');
			$this->form_validation->set_rules('postal', 'Código Postal', 'required|numeric|max_length[5]|min_length[5]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('terminos', 'Términos y Condiciones', 'required');

			if ($this->form_validation->run() == FALSE) {

				$datos['msg'] = validation_errors();
				$datos['vista'] = 'nok_view';
			} else {
				// EXTRACCION DE DATOS
				extract($this->input->post());

				$allInputs = array(
					'nombres' => strtoupper_total($nombre),
					'apellidos' => strtoupper_total($apellidos),
					'telefono' => $telefono,
					'email' => strtolower_total($email),
					'codigo_postal' => $postal,
					'fecha_registro' => date('Y-m-d H:i:s'),
					'ip' => $this->input->ip_address(),
					'estado_pa' => 2
				);
				// VALIDACIONES
				$data = array(
					'columna' => 'email',
					'valor' => $allInputs['email']
				);
				$p_email = $this->Model_participante->m_get_por_columna($data);

				$data = array(
					'columna' => 'telefono',
					'valor' => $allInputs['telefono']
				);
				$p_telefono = $this->Model_participante->m_get_por_columna($data);

				if($p_email || $p_telefono){
					$datos['msg'] = 'Participante ya está registrado. Inténtelo nuevamente';
					$datos['vista'] = 'nok_view';
					$this->load->view('home',$datos);
					return;
				}

				// REGISTRO EN BASE DE DATOS
				$id = $this->Model_participante->m_registro($allInputs);
				if($id){
					// ENVIO DE CORREO PARA VALIDACION
					$allInputs['idparticipante'] = $id;

					if($this->correo_confirmacion($allInputs)){
						log_message('custom', "Envio de correo exitoso: " . $allInputs['email']);
					}else{
						log_message('custom',"Ocurrió un error al enviar correo: " . $allInputs['email'] . " de ". $id);
					}



					$datos['vista'] = 'ok_view';

				}else{
					$datos['msg'] = 'Error al guardar en la base de datos. Inténtelo nuevamente';
					$datos['vista'] = 'nok_view';
				}
			}

		}else {
			$datos['msg'] = 'El Captcha no es válido. Inténtelo nuevamente';
			$datos['vista'] = 'nok_view';
		}

		$this->load->view('home',$datos);


	}

	public function reenviar_correo()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		if($this->correo_confirmacion($allInputs)){
			log_message('custom', "ReEnvio de correo exitoso: " . $allInputs['email']);
			$arrData['message'] = 'Se reenvió el correo exitosamente';
			$arrData['flag'] = 1;
		}else{
			log_message('custom',"Ocurrió un error al reenviar correo: " . $allInputs['email'] . " de ". $allInputs['idparticipante']);
			$arrData['message'] = 'Ocurrió un error al reenviar correo';
			$arrData['flag'] = 0;
		}


		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function confirmar_subscripcion($id_enc) {
        if ($id_enc != '') {

                // VERIFICAR DATOS DE PARTICIPANTE
                $datos_participante = $this->Model_participante->m_verificar_participante_inactivo($id_enc);
                // var_dump($datos_usuario); exit();
                if(!$datos_participante){
                  	$datos['msg'] = 'No se encontraron datos';
					$datos['vista'] = 'nok_view';
                   return;
				}
				// cargar evento activo
				$evento = $this->Model_evento->m_cargar_sorteo_activo();

				// activar registro de participante
                if($this->Model_participante->m_activar_participante($datos_participante)){

					// registrar afiliado
					$data = array(
						'idevento' => $evento['idevento'],
						'idparticipante' => $datos_participante['idparticipante'],
						'created_at' => date('Y-m-d H:i:s')
					);
					$this->Model_evento->m_registrar_afiliado($data);

                    $datos['msg'] = 'Registro exitoso';
					$datos['vista'] = 'ok_validacion';
                }
                else{

                    $datos['msg'] = 'No se pudo actualizar datos';
					$datos['vista'] = 'nok_view';
				}

        }
        else{

           	$datos['msg'] = 'Ruta no válida';
			$datos['vista'] = 'nok_view';
		}
		$this->load->view('home',$datos);
    }

	private function correo_confirmacion($datos)
	{
		$para = $datos['email'];
		$asunto = "EsGratuito.es - Confirma tu correo electrónico";
		$url = base_url() . 'confirmar_subscripcion/' . md5($datos['idparticipante']);

		$mensaje = "<p>¡Hola! Solo necesitamos comprobar que " . $datos['email'] . " es tu dirección de correo electrónico.<br>";
		$mensaje .= "Utiliza el botón inferior para confirmar:</p>";
		$mensaje .= '<a class="btn btn-info" href="' . $url . '" style="min-width: 234px;
				border: 13px solid #081d3a;
				border-radius: 4px;
				background-color: #081d3a;
				font-size: 20px;
				color: #ffffff;
				display: inline-block;
				text-align: center;
				vertical-align: top;
				font-weight: 900;
				text-decoration: none!important;" target="_blank">Confirmar dirección de correo</a>';
		if(enviar_mail($para, $asunto, $mensaje)){
			return true;
		}
		return false;
	}
}
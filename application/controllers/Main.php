<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(
			array(
				'model_banner',
				'model_pagina_dinamica',
				'model_sede',
				'model_servicio'
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
		$siteLang = $this->session->userdata('site_lang');
		$idioma = $siteLang == 'euskera' ? 'EUS' : 'CAS';
		$idioma_next = $siteLang == 'euskera' ? 'CAS' : 'EUS';
		$allInputs['idioma'] = $idioma;
		$allInputs['segmento'] = 'mensaje';
		$datos['pag_din'] = $this->model_pagina_dinamica->m_get_pagina_dinamica($allInputs);
		$datos['sedes'] = $this->model_sede->m_cargar_sedes_pagina($allInputs);




		$data = array(
			'zona' => 'cabecera',
			'idsede' => 0,
			'idioma' => $idioma
		);
		$listaBanners = $this->model_banner->m_get_banners_zona($data);
		$banners = array();
		$activo = true;
		foreach ($listaBanners as $key => $row) {
			array_push(
				$banners,
				array(
					'idbanner' => $row['idbanner'],
					'titulo' => $row['titulo'],
					'imagen' => $row['imagen'],
					'activo' => $activo? 'active' : '',
				)
			);
			$activo = false;
		}

		$datos['banners'] = $banners;
		$listaMenu = array();
		$order   = array("\r\n", "\n", "\r");
		$replace = '<br />';

		foreach ($datos['sedes'] as $key => $row) {
			$row['idioma'] = $allInputs['idioma'];
			$servicios = $this->model_servicio->m_cargar_sede_servicios($row);
			$datos['sedes'][$key]['servicios'] = $servicios;

			// Procesa primero \r\n así no es convertido dos veces.
			$datos['sedes'][$key]['horario'] = str_replace($order, $replace, $row['horario']);

			array_push(
				$listaMenu,
				array(
					'descripcion' 	=> $row['descripcion_se'],
					'link'			=> base_url('centro/'.$row['segmento_amigable'])
				)
			);
		}

		$datos['listaMenu'] = $listaMenu;
		$datos['info_mensaje'] = $this->lang->line('info_cambio');
		$datos['info_btn'] = $this->lang->line('info_aqui');
		$datos['info_url'] = base_url('main/switchLang/'.$idioma_next);

		$datos['vista'] = 'inicio_view';
		$this->load->view('home',$datos);
	}

	public function sede($sede)
	{
		$siteLang = $this->session->userdata('site_lang');
		$idioma = $siteLang == 'euskera' ? 'EUS' : 'CAS';

		$data = array(
			'segmento' => $sede,
			'idioma' => $idioma
		);
		$rowSede = $this->model_sede->m_cargar_sede_por_segmento($data);

		$data = array(
			'idsede' => $rowSede['idsede'],
			'idioma' => $idioma
		);
		$rowSede['servicios'] = $this->model_servicio->m_cargar_sede_servicios($data);

		// menu
		$listaMenu = array(
			array(
				'descripcion' 	=> $rowSede['descripcion_se'],
				'link'			=> base_url('centro/'.$sede)
			),

			array(
				'descripcion' 	=> strtoupper($this->lang->line('contacto')),
				'link'			=> base_url('contacto/'.$sede)
			),
		);
		$datos['listaMenu'] = $listaMenu;

		// banner
		$data = array(
			'zona' => 'cabecera',
			'idsede' => $rowSede['idsede'],
			'idioma' => $idioma
		);
		$listaBanners = $this->model_banner->m_get_banners_zona($data);
		$banners = array();
		$activo = true;
		foreach ($listaBanners as $key => $row) {
			array_push(
				$banners,
				array(
					'idbanner' => $row['idbanner'],
					'titulo' => $row['titulo'],
					'imagen' => $row['imagen'],
					'activo' => $activo? 'active' : '',
				)
			);
			$activo = false;
		}

		$datos['sede'] = $rowSede;
		$datos['banners'] = $banners;

		// BANNER LATERAL
		$data = array(
			'zona' => 'lateral',
			'idsede' => $rowSede['idsede'],
		);
		$listaBanners = $this->model_banner->m_get_banners_zona($data);
		$bannersLaterales = array();
		$activo = true;
		foreach ($listaBanners as $key => $row) {
			array_push(
				$bannersLaterales,
				array(
					'idbanner' => $row['idbanner'],
					'titulo' => $row['titulo'],
					'imagen' => $row['imagen'],
					'url' => $row['url'],
					'activo' => $activo? 'active' : '',
				)
			);
			$activo = false;
		}
		$datos['banners_laterales'] = $bannersLaterales;

		// vista
		$datos['vista'] = 'sede_view';
		$this->load->view('home',$datos);
	}
	public function switchLang($language = "")
    {
        if($language == 'EUS'){
			$this->session->set_userdata('site_lang', 'euskera');
		}else{
			$this->session->set_userdata('site_lang', 'spanish');
		}
        // redirect('/');
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }
	/**
	 * Muestra el contenido de una Ficha
	 * @param  string
	 * @return [type]
	 */
	public function ver_servicio($url) {
		$partes = explode("-", $url);
        $id = $partes[count($partes) - 1];


        $siteLang = $this->session->userdata('site_lang');
		$idioma = $siteLang == 'euskera' ? 'EUS' : 'CAS';

		$data = array(
			'idsedeservicio' => $id,
			'idioma' => $idioma
		);

		if($datos['servicio'] = $this->model_servicio->m_cargar_servicio_sede($data)){
			$datos['fotos'] = json_decode($datos['servicio']['imagenes'], TRUE);
		}

		$sede = $datos['servicio']['segmento_amigable'];
		if( $idioma == 'EUS' ){
			$datos['servicio']['btnWhatsapp'] = base_url() . 'assets/images/btn-whatsapp-eus.png';
		}else{
			$datos['servicio']['btnWhatsapp'] = base_url() . 'assets/images/btn-whatsapp-cas.png';
		}

		// menu
		$listaMenu = array(
			array(
				'descripcion' 	=> $datos['servicio']['descripcion_se'],
				'link'			=> base_url('centro/'.$sede)
			),
			array(
				'descripcion' 	=> strtoupper($this->lang->line('contacto')),
				'link'			=> base_url('contacto/'.$sede)
			),
		);
		$datos['listaMenu'] = $listaMenu;

		// BANNER CABECERA
		$data = array(
			'zona' => 'cabecera',
			'idsede' => $datos['servicio']['idsede'],
			'idioma' => $idioma
		);
		$listaBanners = $this->model_banner->m_get_banners_zona($data);
		$banners = array();
		$activo = true;
		foreach ($listaBanners as $key => $row) {
			array_push(
				$banners,
				array(
					'idbanner' => $row['idbanner'],
					'titulo' => $row['titulo'],
					'imagen' => $row['imagen'],
					'activo' => $activo? 'active' : '',
				)
			);
			$activo = false;
		}
		$datos['banners'] = $banners;



		$datos['sede_url'] = $sede;

		// DATOS DEL BODY

		$datos['scripts'] = '
			<script src="' . base_url() . 'assets/js/fancybox/jquery.fancybox.pack.js"></script>
			<script type="text/javascript">
				$(document).ready(function() {
					$(".fancybox").fancybox({

						openEffect	: \'elastic\',
						closeEffect	: \'elastic\',
						nextEffect	: \'fade\',
						prevEffect	: \'fade\',
						openSpeed : \'slow\',
						closeSpeed : \'slow\',
						padding: 5
					});
				});

				function envio() {
					var parametros = {
						nombre: $("#nombre").val(),
						email: $("#email").val(),
						telefono: $("#telefono").val(),
						mensaje: $("#mensaje").val(),
						servicio: $("#servicio").val(),
						sede: $("#sede").val(),
						terminos: $("#terminos").prop("checked")
					};
					$.ajax({
						data:parametros,
						url:"' . base_url() . 'main/envio_solicitud",
						type: "post",
						beforeSend: () => {
							$("#resultado").html("Enviando, espere por favor");
						},
						success: rpta => {
							if(rpta.flag == 1){
								$("#resultado").html("");
								$("#error").html("");
								alert(rpta.msg);
							}else{
								$("#resultado").html("");
								$("#error").html(rpta.msg);
							}
						}
					});
				}
			</script>
		';

		$datos['idioma'] = $idioma;
		$datos['vista'] = 'servicio_view';
		$this->load->view('home',$datos);

    }

	public function envio_solicitud()
	{

		$arrData['flag'] = 0;

		$this->form_validation->set_rules('nombre', 'Nombre', 'required');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'required|numeric');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'required');
		$this->form_validation->set_rules('terminos', 'Protección de datos', 'callback_accept_terms');

		if ($this->form_validation->run() == FALSE) {

			// echo validation_errors();
			$arrData['msg'] = validation_errors();
		}else{
			extract($this->input->post());
			// envio de correo
			$this->load->library('email');

			$asunto = "Mensaje de " . $servicio . " - " . $sede;
			$datos['contenido'] = "";
			$datos['contenido'] .= "Se ha recibido un mensaje de:<br/>";
			$datos['contenido'] .= "<strong>Nombre:</strong> " . $nombre . "<br/>";
			$datos['contenido'] .= "<strong>Email:</strong> " . $email . "<br/>";
			$datos['contenido'] .= "<strong>Teléfono:</strong> " . $telefono . "<br/>";
			$datos['contenido'] .= "<strong>Mensaje:</strong> " . $mensaje . "<br/>";

			$mensaje = $this->load->view('plantilla_email', $datos, true);


			$this->email->from(EMAIL_FROM, NOMBRE_WEB);
			$this->email->to(EMAIL_WEB);
			$this->email->subject($asunto);
			$this->email->message($mensaje);

			if($this->email->send()){

				$arrData['flag'] = 1;
				$arrData['msg'] = 'Mensaje enviado correctamente';
			}else{

				$arrData['msg'] = 'Ocurrió un error al enviar mensaje. Inténtelo nuevamente';
			}


		}

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));


		// exit();
	}

	function accept_terms($str)
	{
        if ( $str === 'true' ) {
			return TRUE;
		} else {
			$error = 'Por favor acepte la protección de datos.';
			$this->form_validation->set_message('accept_terms', $error);
			return FALSE;
		}
	}

	public function contacto($sede)
	{
		$siteLang = $this->session->userdata('site_lang');
		$idioma = $siteLang == 'euskera' ? 'EUS' : 'CAS';

		$data = array(
			'segmento' => $sede,
			'idioma' => $idioma
		);
		$rowSede = $this->model_sede->m_cargar_sede_por_segmento($data);

		$data = array(
			'idsede' => $rowSede['idsede'],
			'idioma' => $idioma
		);
		// $rowSede['servicios'] = $this->model_servicio->m_cargar_sede_servicios($data);

		// menu
		$listaMenu = array(
			array(
				'descripcion' 	=> $rowSede['descripcion_se'],
				'link'			=> base_url('centro/'.$sede)
			)
		);
		$datos['listaMenu'] = $listaMenu;

		// banner
		$data = array(
			'zona' => 'cabecera',
			'idsede' => $rowSede['idsede'],
			'idioma' => $idioma
		);
		$listaBanners = $this->model_banner->m_get_banners_zona($data);
		$banners = array();
		$activo = true;
		foreach ($listaBanners as $key => $row) {
			array_push(
				$banners,
				array(
					'idbanner' => $row['idbanner'],
					'titulo' => $row['titulo'],
					'imagen' => $row['imagen'],
					'activo' => $activo? 'active' : '',
				)
			);
			$activo = false;
		}

		$datos['sede'] = $rowSede;
		$datos['banners'] = $banners;

		$datos['idioma'] = $idioma;

		$datos['scripts'] = '

			<script type="text/javascript">
				$("form").submit( event => {
					event.preventDefault();

					var parametros = {
						nombre: $("#nombre").val(),
						email: $("#email").val(),
						asunto: $("#asunto").val(),
						mensaje: $("#mensaje").val(),
						sede: $("#sede").val(),
						terminos: $("#terminos").prop("checked")
					};
					$.ajax({
						data: parametros,
						url: "' . base_url() .'main/envio_contacto",
						type: "post",
						beforeSend: () => {
							$("#resultado").html("Enviando, espere por favor");
						},
						success: rpta => {
							if (rpta.flag == 1) {
								$("#resultado").html("");
								$("#errores").html("");
								alert(rpta.msg);
							} else {
								$("#resultado").html("");
								$("#errores").html(rpta.msg);
							}
						}
					});
				});
			</script>
		';


		// vista
		$datos['vista'] = 'contacto_view';
		$this->load->view('home',$datos);
	}

	public function envio_contacto()
	{
		$arrData['flag'] = 0;

		$this->form_validation->set_rules('nombre', 'Nombre', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'required');
		$this->form_validation->set_rules('asunto', 'Asunto', 'required');
		// $this->form_validation->set_rules('terminos', 'Política de privacidad', 'callback_accept_terms');

		if ($this->form_validation->run() == FALSE) {

			// echo validation_errors();
			$arrData['msg'] = validation_errors();
		}else{
			extract($this->input->post());

			// envio de correo
			$this->load->library('email');

			$asunto = $asunto . " de " . $sede;
			$datos['contenido'] = "";
			$datos['contenido'] .= "Se ha recibido un mensaje de:<br/>";
			$datos['contenido'] .= "<strong>Nombre:</strong> " . $nombre . "<br/>";
			$datos['contenido'] .= "<strong>Email:</strong> " . $email . "<br/>";
			$datos['contenido'] .= "<strong>Mensaje:</strong> " . $mensaje . "<br/>";

			$mensaje = $this->load->view('plantilla_email', $datos, true);


			$this->email->from(EMAIL_FROM, NOMBRE_WEB);
			$this->email->to(EMAIL_WEB);
			$this->email->subject($asunto);
			$this->email->message($mensaje);

			if($this->email->send()){

				$arrData['flag'] = 1;
				$arrData['msg'] = 'Mensaje enviado correctamente';
			}else{

				$arrData['msg'] = 'Ocurrió un error al enviar mensaje. Inténtelo nuevamente';
			}

		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
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

	public function test_email()
	{
		// $para = 'rguevarac@hotmail.es';
		// $asunto = "Email de prueba";

		// $mensaje = "<p>Test</p>";

		// if(enviar_mail($para, $asunto, $mensaje)){
		// 	var_dump('Enviado correctamente');
		// 	return true;
		// }
		// var_dump('Ocurrio un error');
		// return false;

		$this->load->library('email');

		$this->email->from('noreply@osakin.net');
        $this->email->to('rguevarac@hotmail.es');
        $this->email->subject('asunto');
        $this->email->message('mensaje con prioridad 1');

        if($this->email->send()){
			echo "enviado ";
			echo $this->email->print_debugger();
		}
		else echo "No enviado ";
		echo $this->email->print_debugger();
	}

}
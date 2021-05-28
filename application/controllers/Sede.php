<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sede extends CI_Controller {
	public function __construct(){
        parent::__construct();

        $this->sessionCM = @$this->session->userdata('sess_cm_'.substr(base_url(),-7,6));
		$this->load->helper(
			array(
				'security',
				'otros',
				'fechas',
				'imagen_helper'
			)
		);
        $this->load->model(array('model_sede','model_servicio'));
    }
	public function listarSedes()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		if( $allInputs['idioma'] === 'es' ){
			$allInputs['idioma'] = 'CAS';
		}else{
			$allInputs['idioma'] = 'EUS';
		}
		$lista = $this->model_sede->m_cargar_sedes_pagina($allInputs);
		$arrListado = array();

		if(empty($lista)){
			$arrData['flag'] = 0;
			$arrData['datos'] = $arrListado;
			$arrData['message'] = 'No hay datos';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
			return;
		}

		$arrData['datos'] = $lista;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function registrarSede()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// AQUI ESTARAN LAS VALIDACIONES
    	if(empty($allInputs['descripcion_se'])){
    		$arrData['message'] = 'El nombre es obligatorio.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}
		if(!empty($allInputs['myCroppedImage'])){
    		$allInputs['nombre_foto'] = $allInputs['descripcion_se'].date('YmdHis').'.png';
    		$subir = subir_imagen_Base64($allInputs['myCroppedImage'], 'uploads/sedes/', $allInputs['nombre_foto']);

    	}

    	// INICIA EL REGISTRO
		if( $allInputs['idioma'] === 'es' ){
			$idioma = 'CAS';
		}else{
			$idioma = 'EUS';
		}

		$data = array(
			'descripcion_se' => strtoupper_total($allInputs['descripcion_se']),
			'telefono' => empty($allInputs['telefono'])? null : $allInputs['telefono'],
			'email' => empty($allInputs['email'])? null : $allInputs['email'],
			'imagen_se' => empty($allInputs['nombre_foto'])? null : $allInputs['nombre_foto']

		);



		if($idsede = $this->model_sede->m_registrar($data)){

			//REGISTRO DE PAGINA
			$data = array(
				'idsede' => $idsede,
				'direccion' => $allInputs['direccion'],
				'direccion2' => empty($allInputs['direccion2'])? null : $allInputs['direccion2'],
				'titulo' => strtoupper_total($allInputs['titulo']),
				'descripcion' => $allInputs['descripcion'],
				'horario' => empty($allInputs['horario'])? null : $allInputs['horario'],
				'idioma' => $idioma
			);

			$this->model_sede->m_registrar_sede_pagina($data);

			$arrData['message'] = 'Se registraron los datos correctamente';
			$arrData['datos'] = $idsede;
    		$arrData['flag'] = 1;
		}else{
			$arrData['message'] = 'Ocurrió un error. Inténtelo nuevamente';
			$arrData['datos'] = null;
    		$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function editarSede(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		$data = array(
			'descripcion_se' => strtoupper_total($allInputs['descripcion_se']),
			'telefono' => empty($allInputs['telefono'])? null : $allInputs['telefono'],
			'email' => empty($allInputs['email'])? null : $allInputs['email'],
		);
		if(!empty($allInputs['myCroppedImage'])){
    		$allInputs['nombre_foto'] = $allInputs['descripcion_se'].date('YmdHis').'.png';
    		$subir = subir_imagen_Base64($allInputs['myCroppedImage'], 'uploads/sedes/', $allInputs['nombre_foto']);

			$data['imagen_se'] = $allInputs['nombre_foto'];
    	}


		if($this->model_sede->m_editar($data,$allInputs['idsede'])){
			$arrData['message'] = 'Se editaron los datos correctamente ';
    		$arrData['flag'] = 1;
		}

		// Sede Pagina
		if( empty($allInputs['idsedepagina']) ){ // se registra
			if( $allInputs['idioma'] === 'es' ){
				$idioma = 'CAS';
			}else{
				$idioma = 'EUS';
			}
			$data = array(
				'idsede' => $allInputs['idsede'],
				'horario' => empty($allInputs['horario'])? null : $allInputs['horario'],
				'direccion' => $allInputs['direccion'],
				'direccion2' => empty($allInputs['direccion2'])? null : $allInputs['direccion2'],
				'titulo' => strtoupper_total($allInputs['titulo']),
				'descripcion' => $allInputs['descripcion'],
				'idioma' => $idioma
			);
			$this->model_sede->m_registrar_sede_pagina($data);

		}else{
			$data = array(
				'horario' => empty($allInputs['horario'])? null : $allInputs['horario'],
				'direccion' => $allInputs['direccion'],
				'direccion2' => empty($allInputs['direccion2'])? null : $allInputs['direccion2'],
				'titulo' => strtoupper_total($allInputs['titulo']),
				'descripcion' => $allInputs['descripcion']
			);
			$this->model_sede->m_editar_sede_pagina($data,$allInputs['idsedepagina']);
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function listarServiciosSede()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		if( $allInputs['idioma'] === 'es' ){
			$allInputs['idioma'] = 'CAS';
		}else{
			$allInputs['idioma'] = 'EUS';
		}
		$lista = $this->model_servicio->m_cargar_sede_servicios($allInputs);

		$arrData['datos'] = $lista;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function editarServicioSede(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		$data = array(
			'nombre_serv' => strtoupper_total($allInputs['servicio']),
			'titulo' => strtoupper_total($allInputs['titulo']),
			'descripcion' => $allInputs['descripcion']
		);


		if($this->model_servicio->m_editar_servicio_sede($data,$allInputs['idsedeservicioidioma'])){
			$arrData['message'] = 'Se editaron los datos correctamente ';
    		$arrData['flag'] = 1;
		}


		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}
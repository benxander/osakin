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
        $this->load->model(array('model_sede'));
    }
	public function listarSedes()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

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
		$data = array(
			'descripcion_se' => strtoupper_total($allInputs['descripcion_se']),
			// 'direccion' => $allInputs['direccion'],
			// 'titulo' => strtoupper_total($allInputs['titulo']),
			// 'descripcion' => $allInputs['descripcion'],
			// 'horario' => empty($allInputs['horario'])? null : $allInputs['horario'],
			'telefono' => empty($allInputs['telefono'])? null : $allInputs['telefono'],
			'email' => empty($allInputs['email'])? null : $allInputs['email'],
			'imagen_se' => empty($allInputs['nombre_foto'])? null : $allInputs['nombre_foto']

		);



		if($idcentromedico = $this->model_sede->m_registrar($data)){
			$arrData['message'] = 'Se registró el nuevo centro médico correctamente';
			$arrData['datos'] = $idcentromedico;
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

		if(!empty($allInputs['myCroppedImage'])){
    		$allInputs['nombre_foto'] = $allInputs['descripcion_se'].date('YmdHis').'.png';
    		$subir = subir_imagen_Base64($allInputs['myCroppedImage'], 'uploads/sedes/', $allInputs['nombre_foto']);

    	}

		$data = array(
			'descripcion_se' => strtoupper_total($allInputs['descripcion_se']),
			// 'direccion' => $allInputs['direccion'],
			// 'titulo' => strtoupper_total($allInputs['titulo']),
			// 'descripcion' => $allInputs['descripcion'],
			'telefono' => empty($allInputs['telefono'])? null : $allInputs['telefono'],
			'email' => empty($allInputs['email'])? null : $allInputs['email'],
			'imagen_se' => empty($allInputs['nombre_foto'])? null : $allInputs['nombre_foto']
			// 'horario' => empty($allInputs['horario'])? null : $allInputs['horario']
		);

		if($this->model_sede->m_editar($data,$allInputs['idsede'])){
			$arrData['message'] = 'Se editaron los datos correctamente ';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

}
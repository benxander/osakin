<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio extends CI_Controller {
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
        $this->load->model(array('model_servicio'));
    }
	public function listarServicios()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$lista = $this->model_servicio->m_cargar_servicios();
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
	public function registrarServicio()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// AQUI ESTARAN LAS VALIDACIONES
    	if(empty($allInputs['nombre'])){
    		$arrData['message'] = 'El nombre es obligatorio.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}


    	// INICIA EL REGISTRO


		$data = array(
			'nombre' => strtoupper_total($allInputs['nombre']),
		);



		if($idsede = $this->model_servicio->m_registrar($data)){

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

	public function editarServicio(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		$data = array(
			'nombre' => strtoupper_total($allInputs['nombre']),
		);

		if($this->model_servicio->m_editar($data,$allInputs['idservicio'])){
			$arrData['message'] = 'Se editaron los datos correctamente ';
    		$arrData['flag'] = 1;
		}


		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CentroMedico extends CI_Controller {
	public function __construct(){
        parent::__construct();

        $this->sessionCM = @$this->session->userdata('sess_cm_'.substr(base_url(),-7,6));
		$this->load->helper(array('security','otros','fechas'));
        $this->load->model(array('model_centro_medico'));
    }
	public function listarCentrosMedicos()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$lista = $this->model_centro_medico->m_cargar_centros_medicos();
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
	public function registrarCentroMedico()
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
			'direccion' => $allInputs['direccion'],
			'titulo' => strtoupper_total($allInputs['titulo']),
			'descripcion' => $allInputs['descripcion'],
			'telefono' => empty($allInputs['telefono'])? null : $allInputs['telefono'],
			'email' => empty($allInputs['email'])? null : $allInputs['email'],
			'horario' => empty($allInputs['horario'])? null : $allInputs['horario'],
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		);

		if($idcentromedico = $this->model_centro_medico->m_registrar($data)){
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

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Participante extends CI_Controller {
	public function __construct(){
        parent::__construct();

        $this->sessionCM = @$this->session->userdata('sess_cmp_'.substr(base_url(),-7,6));
		$this->load->helper(array('security','otros','fechas'));
        $this->load->model(array('model_participante'));
    }

	public function listar_participantes()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];

		$lista = $this->model_participante->m_cargar_participantes($paramPaginate);
		$arrListado = array();

		if(empty($lista)){
			$arrData['flag'] = 0;
			$arrData['datos'] = $arrListado;
    		$arrData['paginate']['totalRows'] = 0;
			$arrData['message'] = 'No hay ningun participante activo';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
		}

		$totalRows = $this->model_participante->m_count_participantes($paramPaginate);

		foreach ($lista as $row) {
			$objEstado = array();
			if($row['estado_pa'] == 1){ //habilitado (verde)
				$objEstado['claseIcon'] = 'fa fa-check';
				$objEstado['claseLabel'] = 'label-success';
				$objEstado['labelText'] = 'HABILITADO';
			}
			else if($row['estado_pa'] == 2){ //PENDIENTE DE CORREO (amarillo)
				$objEstado['claseIcon'] = 'fa fa-spinner fa-spin';
				$objEstado['claseLabel'] = 'label-warning';
				$objEstado['labelText'] = 'PENDIENTE';
			}else if($row['estado_pa'] == 0){ //anulado (rojo)
				$objEstado['claseIcon'] = 'fa fa-ban';
				$objEstado['claseLabel'] = 'label-danger';
				$objEstado['labelText'] = 'ANULADO';
			}else if($row['estado_pa'] == 3){ //CAMBIO DE CLAVE (amarillo)
				$objEstado['claseIcon'] = 'fa fa-spinner fa-spin';
				$objEstado['claseLabel'] = 'label-warning';
				$objEstado['labelText'] = 'PENDIENTE POR CLAVE';
			}

			array_push($arrListado,
				array(
					'idparticipante' => $row['idparticipante'],
					'nombres' 	=> $row['nombres'],
					'apellidos' 	=> $row['apellidos'],
					'telefono' 	=> $row['telefono'],
					'email' => $row['email'],
					'codigo_postal' 	=> $row['codigo_postal'],
					'fecha' 	=> formatoFechaReporte4($row['fecha_registro']),
					'ip' 	=> $row['ip'],
					'estado_pa' 	=> $row['estado_pa'],
					'estado_obj' => $objEstado,
				)
			);
		}

    	$arrData['datos'] = $arrListado;
    	$arrData['paginate']['totalRows'] = $totalRows;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));

	}

	public function listar_afiliado_sorteo()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];
		$paramDatos = $allInputs['data'];

		$lista = $this->model_participante->m_cargar_afiliado_sorteo($paramPaginate,$paramDatos);
		$arrListado = array();

		if(empty($lista)){
			$arrData['flag'] = 0;
			$arrData['datos'] = $arrListado;
    		$arrData['paginate']['totalRows'] = 0;
			$arrData['message'] = 'No hay ningun afiliado activo';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
		}

		$totalRows = $this->model_participante->m_count_afiliado_sorteo($paramPaginate,$paramDatos);

		foreach ($lista as $row) {
			array_push($arrListado,
				array(
					'idparticipante' => $row['idparticipante'],
					'nombres' 	=> $row['nombres'],
					'apellidos' 	=> $row['apellidos'],
					'telefono' 	=> $row['telefono'],
					'email' => $row['email'],
					'codigo_postal' 	=> $row['codigo_postal'],
					'ip' 	=> $row['ip'],
				)
			);
		}

    	$arrData['datos'] = $arrListado;
    	$arrData['paginate']['totalRows'] = $totalRows;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

}
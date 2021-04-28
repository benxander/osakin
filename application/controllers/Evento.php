<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evento extends CI_Controller {
	public function __construct(){
        parent::__construct();

        $this->sessionCM = @$this->session->userdata('sess_cm_'.substr(base_url(),-7,6));
		$this->load->helper(array('security','otros','fechas'));
        $this->load->model(array('model_evento','model_participante'));
    }

	public function listar_eventos()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];

		$lista = $this->model_evento->m_cargar_eventos($paramPaginate);
		$arrListado = array();

		if(empty($lista)){
			$arrData['flag'] = 0;
			$arrData['datos'] = $arrListado;
    		$arrData['paginate']['totalRows'] = 0;
			$arrData['message'] = 'No hay ningun evento activo';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
		}

		$totalRows = $this->model_evento->m_count_eventos($paramPaginate);

		foreach ($lista as $row) {
			$objEstado = array();
			if($row['estado'] == 1){ //habilitado (verde)
				$objEstado['claseIcon'] = 'fa fa-check';
				$objEstado['claseLabel'] = 'label-success';
				$objEstado['labelText'] = 'ACTIVO';
			}
			else if($row['estado'] == 2){ //
				$objEstado['claseIcon'] = '';
				$objEstado['claseLabel'] = 'label-default';
				$objEstado['labelText'] = 'DESACTIVADO';
			}else if($row['estado'] == 0){ //anulado (rojo)
				$objEstado['claseIcon'] = 'fa fa-ban';
				$objEstado['claseLabel'] = 'label-danger';
				$objEstado['labelText'] = 'ANULADO';
			}
			$partFecha = explode('-',$row['fecha']);
			array_push($arrListado,
				array(
					'idevento' => $row['idevento'],
					'titulo' 	=> $row['titulo'],
					'fecha_cf' 	=> darFormatoDMY($row['fecha']),
					'year' 	=> $partFecha[0],
					'month' 	=> $partFecha[1],
					'day' 	=> $partFecha[2],
					'estado' => $row['estado'],
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

	public function listar_premios_sorteo()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$lista = $this->model_evento->m_cargar_premios_sorteo($allInputs);
		$arrListado = array();

		if(empty($lista)){
			$arrData['flag'] = 0;
			$arrData['datos'] = array();
			$arrData['message'] = 'No hay ningun evento activo';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
		}

		// foreach ($lista as $row) {

		// 	array_push($arrListado,
		// 		array(
		// 			'idficha' => $row['idficha'],
		// 			'titulo' 	=> $row['titulo'],
		// 			'fecha_cf' 	=> darFormatoDMY($row['fecha']),
		// 			'year' 	=> $partFecha[0],
		// 			'month' 	=> $partFecha[1],
		// 			'day' 	=> $partFecha[2],
		// 			'estado_obj' => $objEstado,
		// 		)
		// 	);
		// }

    	$arrData['datos'] = $lista;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));

	}


	public function registrar()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// AQUI ESTARAN LAS VALIDACIONES
    	if(empty($allInputs['titulo'])){
    		$arrData['message'] = 'El titulo es obligatorio.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}

    	// INICIA EL REGISTRO
		$data = array(
			'titulo' => strtoupper_total($allInputs['titulo']) ,
			'fecha' => date('Y-m-d',strtotime($allInputs['fecha'])),
			'created_at' => date('Y-m-d H:i:s')
		);

		if($idevento = $this->model_evento->m_registrar($data)){
			$arrData['message'] = 'Se registró el nuevo sorteo correctamente';
			$arrData['datos'] = $idevento;
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
	public function editar(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		$data = array(
			'titulo' => $allInputs['titulo'],
			'fecha' => date('Y-m-d',strtotime($allInputs['fecha']))
		);

		if($this->model_evento->m_editar($data,$allInputs['idevento'])){
			$arrData['message'] = 'Se editaron los datos correctamente ';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function ejecutar_sorteo()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$arrGanadores = array();
		$arrResultado = array();
		$lista_Part = $this->model_participante->m_cargar_afiliado_sorteo(FALSE,$allInputs);
		$lista_Premios = $this->model_evento->m_cargar_premios_sorteo($allInputs);
		$cant_premios = count($lista_Premios);

		if(count($lista_Part) == 0){
			$arrData['datos'] = $arrResultado;
			$arrData['message'] = 'No hay participantes en este sorteo';
			$arrData['flag'] = 0;
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
			return;
		}

		if($cant_premios == 0){
			$arrData['datos'] = $arrResultado;
			$arrData['message'] = 'No hay premios configurados';
			$arrData['flag'] = 0;
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
			return;
		}

		$claves_aleatorias = array_rand($lista_Part, $cant_premios);


		foreach ($claves_aleatorias as $index) {
			array_push($arrGanadores,$lista_Part[$index]);
		}

		foreach ($arrGanadores as $key => $row) {
			array_push(
				$arrResultado,
				array(
					'idficha' => $lista_Premios[$key]['idficha'],
					'titulo' => $lista_Premios[$key]['titulo'],
					'url_imagen' => base_url() . 'uploads/fichas/' . $lista_Premios[$key]['imagen'],
					'orden' => $lista_Premios[$key]['orden'],
					'idparticipante' => $row['idparticipante'],
					'nombres' => $row['nombres'],
					'apellidos' => $row['apellidos']
				)
			);
			// actualizar tabla de premios
			$data = array(
				'idparticipante' => $row['idparticipante'],
				'updated_at' => date('Y-m-d H:i:s')
			);
			$this->model_evento->m_actualizar_premio($data,$lista_Premios[$key]['idficha']);

		}

		// finalizar evento
		$data = array(
			'estado' => 2,
			'updated_at' => date('Y-m-d H:i:s')
		);
		if($this->model_evento->m_editar($data,$allInputs['idevento'])){
			$arrData['datos'] = $arrResultado;
			$arrData['message'] = 'Se realizó el sorteo con éxito';
			$arrData['flag'] = 1;
		}else{
			$arrData['datos'] = array();
			$arrData['message'] = 'Ocurrió un error inténtalo nuevamente';
			$arrData['flag'] = 0;
		}

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));

	}
}
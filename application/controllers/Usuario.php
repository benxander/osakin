<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->sessionCM = @$this->session->userdata('sess_cm_'.substr(base_url(),-7,6));
		$this->load->helper(array('security','otros','fechas'));
        $this->load->model(array('model_usuario'));
    }
	/**
	 * Método para listar los usuarios.
	 * Se usa en la vista principal de Mantenimiento de Usuarios.
	 *
	 * @since 1.0.0 26-12-2020
	 * @author Ing. Ruben Guevara <rguevarac@hotmail.es>
	 * @return void
	 */
	public function listar_usuarios()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];

		$lista = $this->model_usuario->m_cargar_usuarios($paramPaginate);
		$arrListado = array();

		if(empty($lista)){
			$arrData['flag'] = 0;
			$arrData['datos'] = $arrListado;
    		$arrData['paginate']['totalRows'] = 0;
			$arrData['message'] = 'No hay ninguna usuario activo';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
		}

		$totalRows = $this->model_usuario->m_count_usuarios($paramPaginate);

		foreach ($lista as $row) {
			$objEstado = array();
			if($row['estado_us'] == 1){ //habilitado (verde)
				$objEstado['claseIcon'] = 'fa fa-check';
				$objEstado['claseLabel'] = 'label-success';
				$objEstado['labelText'] = 'HABILITADO';
			}
			else if($row['estado_us'] == 2){ //deshabilitado (gris)
				$objEstado['claseIcon'] = '';
				$objEstado['claseLabel'] = 'label-default';
				$objEstado['labelText'] = 'DESHABILITADO';
			}else if($row['estado_us'] == 0){ //anulado (rojo)
				$objEstado['claseIcon'] = 'fa fa-ban';
				$objEstado['claseLabel'] = 'label-danger';
				$objEstado['labelText'] = 'ANULADO';
			}else if($row['estado_us'] == 3){ //CAMBIO DE CLAVE (amarillo)
				$objEstado['claseIcon'] = 'fa fa-spinner fa-spin';
				$objEstado['claseLabel'] = 'label-warning';
				$objEstado['labelText'] = 'PENDIENTE POR CLAVE';
			}

			array_push($arrListado,
				array(
					'idusuario' => $row['idusuario'],
					'idgrupo' 	=> $row['idgrupo'],
					'username' 	=> $row['username'],
					'idgrupo' 	=> $row['idgrupo'],
					'grupo' 	=> array(
						'idgrupo' => $row['idgrupo'],
						'descripcion' => $row['descripcion_gr'],
					),
					'descripcion_gr' => $row['descripcion_gr'],
					'nombre_foto' 	=> $row['nombre_foto'],
					'ultimo_inicio_sesion' 	=> formatoFechaReporte4($row['ultimo_inicio_sesion']),
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
    public function lista_usuario_autocomplete(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$lista = $this->model_usuario->m_cargar_usuario_autocomplete($allInputs);
		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado,
				array(
					'idusuario' => $row['idusuario'],
					'username' => $row['username']
				)
			);
		}

    	$arrData['datos'] = $arrListado;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	// MANTENIMIENTO
	public function registrar_usuario()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// AQUI ESTARAN LAS VALIDACIONES
    	if(empty($allInputs['pass'])){
    		$arrData['message'] = 'faltan los datos de la contraseña.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}
    	$usuario = $this->model_usuario->m_cargar_usuario_search($allInputs);
    	if($usuario){
    		$arrData['message'] = 'el username ya existe. Por favor ingrese otro nombre de usuario.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}


    	// INICIA EL REGISTRO
		$data = array(
			'username' => $allInputs['username'],
			'idconfiguracion' => 1,
			'idgrupo' => $allInputs['grupo']['id'],
			'pass' => do_hash($allInputs['pass'],'md5'),
			'createdat' => date('Y-m-d H:i:s'),
			'updatedat' => date('Y-m-d H:i:s')
		);

		if($idusuario = $this->model_usuario->m_registrar($data)){
			$arrData['message'] = 'Se registraron los datos del usuario correctamente';
			$arrData['datos'] = $idusuario;
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
	public function editar_usuario(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		$data = array(
			'username' => $allInputs['username'],
			'idgrupo' => $allInputs['grupo']['id'],
			'updatedAt' => date('Y-m-d H:i:s')
		);

		if($this->model_usuario->m_editar($data,$allInputs['idusuario'])){
			$arrData['message'] = 'Se editaron los datos del usuario correctamente ';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function anular_usuario(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al anular los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// var_dump($allInputs); exit();
		if($allInputs['idusuario'] == $this->sessionCM['idusuario']){
			$arrData['message'] = 'No puede eliminar usuario de sesión actual';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
			return;
		}
		if($this->model_usuario->m_anular($allInputs)){
			$arrData['message'] = 'Se anularon los datos correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function mostrar_usuario_id(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$lista = $this->model_usuario->m_cargar_usuario_id($allInputs);
		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado,
				array(
					'idusuario' => $row['idusuario'],
					'username' => $row['username'],
					'idgrupo' => $row['idgrupo']
				)
			);
		}

    	$arrData['datos'] = $arrListado;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function cambiar_clave(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al cambiar la clave, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// var_dump($allInputs); exit();
    	$passOK = $this->model_usuario->m_verificar_clave($allInputs);
    	if(!$passOK){
    		$arrData['message'] = 'La contraseña actual no es correcta.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}
    	if($allInputs['pass'] != $allInputs['pass2']){
    		$arrData['message'] = 'Las nuevas contraseñas no son iguales.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}

		if($this->model_usuario->m_cambiar_clave($allInputs)){
			$arrData['message'] = 'Se actualizaron los datos correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function actualizar_intro_no_mostrar()
	{
		$arrData['message'] = 'Error al procesar información, inténtelo nuevamente';
    	$arrData['flag'] = 0;
		if($this->model_usuario->m_actualizar_intro_no_mostrar()){
			$arrData['message'] = '';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function listar_grupo_cbo()
	{

		$lista = $this->model_usuario->m_cargar_grupo();
		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado,
				array(
					'id' => $row['idgrupo'],
					'descripcion' => $row['descripcion_gr']
				)
			);
		}

    	$arrData['datos'] = $arrListado;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function listar_usuarios_disp_cbo()
	{

		$lista = $this->model_usuario->m_cargar_usuario_empresa_disp();
		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado,
				array(
					'id' => $row['idusuario'],
					'descripcion' => $row['username']
				)
			);
		}

    	$arrData['datos'] = $arrListado;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}
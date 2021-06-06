<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends CI_Controller {
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
        $this->load->model(array('model_banner'));
    }
	public function listarBanners()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		if( $allInputs['idioma'] === 'es' ){
			$allInputs['idioma'] = 'CAS';
		}else{
			$allInputs['idioma'] = 'EUS';
		}
		$lista = $this->model_banner->m_cargar_banners($allInputs);
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

	public function registrarBanner()
	{
		$errors = array(
		    '0' => 'There is no error, the file uploaded with success',
		    '1' => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		    '2' => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		    '3' => 'The uploaded file was only partially uploaded',
		    '4' => 'No file was uploaded',
		    '6' => 'Missing a temporary folder',
		    '7' => 'Failed to write file to disk.',
		    '8' => 'A PHP extension stopped the file upload.',
		);

		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		$objectZona = get_object_vars(json_decode($this->input->post('objZona')));
		$objectSede = get_object_vars(json_decode($this->input->post('objSede')));
		$idioma = $this->input->post('idioma');

		$allInputs = array(
			'idioma' => $idioma == 'es'? 'CAS' : 'EUS',
			'titulo' => strtoupper_total($this->input->post('titulo')),
			'url' => empty($this->input->post('url'))? null : $this->input->post('url'),
			'zona' =>$objectZona['id'],
			'idsede' => $objectSede['id']
		);
		// Los banners laterales no tienen idioma
		if( $allInputs['zona'] == 'lateral' ){
			$allInputs['idioma'] = null;
		}

		// VALIDACIONES
    	if(empty($allInputs['titulo'])){
    		$arrData['message'] = 'El titulo es obligatorio.';
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}
		if( empty($_FILES) ){
			$arrData['message'] = 'No ha seleccionado una imagen';
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
		}

		// subida de imagen
		$file_name = $_FILES['imagenBanner']['name'];
		$file_size =$_FILES['imagenBanner']['size'];
		$file_tmp =$_FILES['imagenBanner']['tmp_name'];
		$file_type=$_FILES['imagenBanner']['type'];
		$file_error=$_FILES['imagenBanner']['error'];
		if(!$file_tmp){
			$arrData['message'] = 'Temporal no existe';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
			return;
		}
		if($file_error > 0){
			$arrData['message'] = $errors[$file_error];
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
			return;
		}
		$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
		$extensions_archivo = array("jpg","jpeg","png");
		$file_name = md5($file_tmp). '.' . $file_ext;
		if(in_array($file_ext, $extensions_archivo)){

		}else{
			$arrData['message'] = 'No es el formato correcto';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
			return;
		}


		if( subir_fichero('uploads/banner','imagenBanner',$file_name) ){
			$allInputs['imagen'] = $file_name;

		}else{
			$arrData['message'] = 'Ocurrió un error al subir el archivo.';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
			return;
		}

    	// INICIA EL REGISTRO

		if($idsede = $this->model_banner->m_registrar($allInputs)){

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

	public function editarBanner(){
		$errors = array(
		    '0' => 'There is no error, the file uploaded with success',
		    '1' => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		    '2' => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		    '3' => 'The uploaded file was only partially uploaded',
		    '4' => 'No file was uploaded',
		    '6' => 'Missing a temporary folder',
		    '7' => 'Failed to write file to disk.',
		    '8' => 'A PHP extension stopped the file upload.',
		);

		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		$objectZona = get_object_vars(json_decode($this->input->post('objZona')));
		$objectSede = get_object_vars(json_decode($this->input->post('objSede')));
		$idioma = $this->input->post('idioma');

		$allInputs = array(
			'idbanner' => $this->input->post('idbanner'),
			'idioma' => $idioma == 'es'? 'CAS' : 'EUS',
			'titulo' => strtoupper_total($this->input->post('titulo')),
			'url' => empty($this->input->post('url'))? null : $this->input->post('url'),
			'zona' =>$objectZona['id'],
			'idsede' => $objectSede['id'],
			'imagen' => $this->input->post('imagen')
		);
		// Los banners laterales no tienen idioma
		if( $allInputs['zona'] == 'lateral' ){
			$allInputs['idioma'] = null;
		}

		// VALIDACIONES
    	if(empty($allInputs['titulo'])){
    		$arrData['message'] = 'El titulo es obligatorio.';
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}

		if( !empty($_FILES) ){ // hay subida de imagen
			$file_name = $_FILES['imagenBanner']['name'];
			$file_size =$_FILES['imagenBanner']['size'];
			$file_tmp =$_FILES['imagenBanner']['tmp_name'];
			$file_type=$_FILES['imagenBanner']['type'];
			$file_error=$_FILES['imagenBanner']['error'];
			if(!$file_tmp){
				$arrData['message'] = 'Temporal no existe';
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($arrData));
				return;
			}
			if($file_error > 0){
				$arrData['message'] = $errors[$file_error];
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($arrData));
				return;
			}
			$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			$extensions_archivo = array("jpg","jpeg","png");
			$file_name = md5($file_tmp). '.' . $file_ext;
			if(in_array($file_ext, $extensions_archivo)){

			}else{
				$arrData['message'] = 'No es el formato correcto';
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($arrData));
				return;
			}


			if( subir_fichero('uploads/banner','imagenBanner',$file_name) ){
				$allInputs['imagen'] = $file_name;

			}else{
				$arrData['message'] = 'Ocurrió un error al subir el archivo.';
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($arrData));
				return;
			}
		}

		// registro
		$data = array(
			'titulo' => $allInputs['titulo'],
			'zona' => $allInputs['zona'],
			'idsede' => $allInputs['idsede'],
			'idioma' => $allInputs['idioma'],
			'imagen' => $allInputs['imagen'],
			'url' => $allInputs['url'],
		);
		if($this->model_banner->m_editar($data,$allInputs['idbanner'])){
			$arrData['message'] = 'Se editaron los datos correctamente ';
    		$arrData['flag'] = 1;
		}


		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function anularBanner(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al eliminar, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		$data = array(
			'estado_ba' => 0,
		);

		if($this->model_banner->m_editar($data,$allInputs['idbanner'])){
			$arrData['message'] = 'Se eliminó el banner correctamente ';
    		$arrData['flag'] = 1;
		}


		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

}
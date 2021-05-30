<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// 955 081 056 29/05/2021 call lost
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
		// $allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		$servicio = $this->input->post('servicio');
		$codigo_youtube = $this->input->post('codigo_youtube');
		$codigo_vimeo = $this->input->post('codigo_vimeo');
		$titulo = $this->input->post('titulo');
		$descripcion = $this->input->post('descripcion');
		$icono = $this->input->post('icono');
		$idsedeservicioidioma = $this->input->post('idsedeservicioidioma');
		$idsedeservicio = $this->input->post('id');
		// var_dump($allInputs);
		// exit();
		if( empty($_FILES) ){
    		// $icono = 'noimage.jpg';
    	}else{
			$extension = pathinfo($_FILES['iconoServ']['name'], PATHINFO_EXTENSION);
    		$nameFile = md5($_FILES['iconoServ']['name']).'.'.$extension;
			if( subir_fichero('uploads/servicios/iconos','iconoServ',$nameFile) ){
				$icono = $nameFile;

			}else{
				var_dump('ocurrio un error');
				exit();
			}
		}
		// Edicion de Sede_servicio
		$data_serv = array(
			'icono' => $icono,
			'codigo_youtube' => empty($codigo_youtube) || $codigo_youtube == 'null' ? null : $codigo_youtube,
			'codigo_vimeo' => empty($codigo_vimeo) || $codigo_vimeo == 'null' ? null : $codigo_vimeo,
		);
		$this->model_servicio->m_editar_sede_servicio($data_serv,$idsedeservicio);

		// Edicion de sede_Servicio_idioma
		$data = array(
			'nombre_serv' => strtoupper_total($servicio),
			'titulo' => strtoupper_total($titulo),
			'descripcion' => $descripcion
		);


		if($this->model_servicio->m_editar_sede_servicio_idioma($data,$idsedeservicioidioma)){
			$arrData['message'] = 'Se editaron los datos correctamente ';
    		$arrData['flag'] = 1;
		}


		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function cargarGaleriaSedeServicio()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$rowGaleria = $this->model_servicio->m_cargar_galeria_sede_servicio($allInputs);

		if( empty($rowGaleria) ){
			$arrData['datos'] = null;
			$arrData['message'] = 'No hay imagenes';
			$arrData['flag'] = 0;

		}else{
			$arrData['datos'] = json_decode($rowGaleria['imagenes'], TRUE);
			$arrData['message'] = 'Ok';
			$arrData['flag'] = 1;
		}

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function SubirArchivo()
	{
		$arrData['message'] = 'Error al subir archivo';
    	$arrData['flag'] = 0;
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
    	// var_dump($_FILES['file']); exit();
		if( empty($_FILES) ){
    		$arrData['datos'] = null;
			$arrData['message'] = 'No se han cargado imagenes';
			$arrData['flag'] = 0;

			$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
			return;
    	}

		if( isset($_FILES['file'])){
			$file_name = $_FILES['file']['name'];
		    $file_size =$_FILES['file']['size'];
		    $file_tmp =$_FILES['file']['tmp_name'];
		    $file_type=$_FILES['file']['type'];
		    $file_error=$_FILES['file']['error'];
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
		    $carpeta = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'servicios' . DIRECTORY_SEPARATOR . 'thumbs';
		    $file_name = md5($file_tmp). '.' . $file_ext;
		    if(in_array($file_ext,$extensions_archivo)){
		    	move_uploaded_file($file_tmp, $carpeta . DIRECTORY_SEPARATOR . $file_name);
		    	// $arrData = $this->registrar_clientes_excel($carpeta . DIRECTORY_SEPARATOR . $file_name );
		    }else{
		    	$arrData['message'] = 'No es el formato correcto';
	    		$this->output
				    ->set_content_type('application/json')
				    ->set_output(json_encode($arrData));
				return;
		    }
		}

		$arrData['message'] = 'Fotos cargadas';
		$arrData['flag'] = 1;

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}
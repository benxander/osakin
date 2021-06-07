<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Configuracion extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security','imagen_helper'));
		$this->load->model(array('model_config'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		// date_default_timezone_set("America/Lima");
	}

	public function getEmpresaAdmin()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrConfig = $this->model_config->m_cargar_empresa_admin();
		$arrConfig['logo_admin'] = 'logo_admin.jpg';
		$arrData['flag'] = 0;
    	$arrData['message'] = 'No hay empresa admin';

		if( $arrConfig ){
			$arrData['flag'] = 1;
    		$arrData['message'] = 'Se cargó la empresa admin';
    		$arrData['datos'] = $arrConfig;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function listarSitioWeb()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrConfig = $this->model_config->m_cargar_configuraciones();
		// $arrConfig['logo_admin'] = 'logo_admin.jpg';
		$arrData['flag'] = 0;
    	$arrData['message'] = 'No hay datos';

		if( $arrConfig ){
			$arrData['flag'] = 1;
    		$arrData['message'] = 'Se cargaron los datos';
    		$arrData['datos'] = $arrConfig;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function editarSitioWeb()
	{
		// $allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$allInputs = array(
			'id' => $this->input->post('id'),
			'valor' => $this->input->post('valor'),
			'tipo' => $this->input->post('tipo'),
			'elemento' => $this->input->post('elemento'),
		);

		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

		if( $allInputs['tipo'] == 'imagen' ){
			$imagen_para_eliminar = $allInputs['valor'];
			$carpeta = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'uploads';
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
			if( !empty($_FILES) ){ // hay subida de imagen
				$file_name = $_FILES['imagenWeb']['name'];
				$file_size =$_FILES['imagenWeb']['size'];
				$file_tmp =$_FILES['imagenWeb']['tmp_name'];
				$file_type=$_FILES['imagenWeb']['type'];
				$file_error=$_FILES['imagenWeb']['error'];
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

				if( subir_fichero($carpeta,'imagenWeb',$file_name) ){
					$allInputs['valor'] = $file_name;

					unlink($carpeta.DIRECTORY_SEPARATOR.$imagen_para_eliminar);

				}else{
					$arrData['message'] = 'Ocurrió un error al subir el archivo.';
					$this->output
						->set_content_type('application/json')
						->set_output(json_encode($arrData));
					return;
				}
			}
		}
		$data = array(
			 'valor' => $allInputs['valor'],
		);
		if($this->model_config->m_editar($data,$allInputs['id'])){
			$arrData['message'] = 'Se editaron los datos correctamente ';
    		$arrData['flag'] = 1;
		}

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

}
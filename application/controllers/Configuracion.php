<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Configuracion extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security'));
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

}
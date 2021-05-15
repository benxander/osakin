<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paginas_dinamicas extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model(array('Model_pagina_dinamica'));
	}

	public function mostrar($segmento_amigable) {
		$allInputs['segmento'] = $segmento_amigable;
		$allInputs['idioma'] = 'CAS';

        $data = $this->Model_pagina_dinamica->m_get_pagina_dinamica($allInputs);
        if(empty($data))
			$datos['contenido'] = '<h1>ESTA PAGINA ESTA EN CONSTRUCCION</h1>Disculpe la molestia.<br>Gracias';
		else $datos = $data;
        $this->load->view('pagina_dinamica',$datos);

    }

    public function listarPaginasDinamicas()
    {
        $allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$lista = $this->Model_pagina_dinamica->m_cargar_paginas_dinamicas();
		$arrListado = array();

		if(empty($lista)){
			$arrData['flag'] = 0;
			$arrData['datos'] = $arrListado;
			$arrData['message'] = 'No hay datos';
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($arrData));
		}

        $arrData['datos'] = $lista;
    	$arrData['message'] = 'Ok';
    	$arrData['flag'] = 1;

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));

    }
}
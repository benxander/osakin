<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paginas_dinamicas extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model(array('Model_pagina_dinamica'));
	}
	
	public function mostrar($segmento_amigable) {
        $data = $this->Model_pagina_dinamica->m_get_pagina_dinamica($segmento_amigable);
        if(empty($data))
			$datos['contenido'] = '<h1>ESTA PAGINA ESTA EN CONSTRUCCION</h1>Disculpe la molestia.<br>Gracias';
		else $datos = $data;
        $this->load->view('pagina_dinamica',$datos);
        
    }
}
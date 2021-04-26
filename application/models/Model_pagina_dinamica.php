<?php
class Model_pagina_dinamica extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function m_get_pagina_dinamica($segmento)
	{
		$this->db->select("
			idpaginadinamica,
			nombre,
			segmento_amigable,
			titulo,
			contenido,
			imagen,
			posicion_imagen,
			url_imagen,
			destino_url_imagen
		", FALSE);
		$this->db->from('pagina_dinamica pd');
		$this->db->where('segmento_amigable', $segmento);
		$this->db->limit('1');
		return $this->db->get()->row_array();
	}
}
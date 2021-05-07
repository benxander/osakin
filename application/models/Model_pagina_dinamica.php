<?php
class Model_pagina_dinamica extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function m_get_pagina_dinamica($datos)
	{
		$this->db->select("
			pd.idpaginadinamica,
			pd.nombre,
			pd.segmento_amigable,
			pd.titulo,
			pd.contenido,
			pd.imagen,
			pd.posicion_imagen,
			pd.url_imagen,
			pd.destino_url_imagen
		", FALSE);
		$this->db->from('pagina_dinamica pd');
		$this->db->join('idioma idi', 'pd.ididioma = idi.ididioma');
		$this->db->where('segmento_amigable', $datos['segmento']);
		$this->db->where('idi.abreviatura', $datos['idioma']);
		$this->db->limit('1');
		return $this->db->get()->row_array();
	}
	public function m_cargar_paginas_dinamicas()
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
		$this->db->order_by('idpaginadinamica', 'ASC');
		return $this->db->get()->result_array();
	}
}
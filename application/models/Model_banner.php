<?php
class Model_banner extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_get_banners_zona($zona) {
		$this->db->select("
			idbanner,
			titulo,
			CONCAT('uploads/banner/', imagen) AS imagen,
			url,
			destino_url,
			zona,
			estado_ba
		",FALSE);
    	$this->db->from('banner');
    	$this->db->where('estado_ba', 1);
    	$this->db->where('zona', $zona);
    	// $this->db->order_by('rand()');
    	return $this->db->get()->result_array();
    }
	
	public function m_get_promociones() {
		$this->db->select("
			idpromocion,
			CONCAT('uploads/promocion/', imagen) AS imagen,
			titulo,
			precio,
			precio_anterior,
			fecha_inicio,
			fecha_fin,
			estado_pr
		",FALSE);
    	$this->db->from('promocion');
    	$this->db->where('estado_pr', 1);
    	$this->db->order_by('idpromocion','DESC');
    	return $this->db->get()->result_array();
	}
}
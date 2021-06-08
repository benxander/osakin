<?php
class Model_banner extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_banners($datos) {
		$this->db->select("
			ba.idbanner,
			ba.titulo,
			ba.imagen,
			ba.url,
			ba.destino_url,
			ba.zona,
			ba.estado_ba,
			ba.idsede,
			se.descripcion_se
		",FALSE);
    	$this->db->from('banner ba');
		$this->db->join('sede se', 'ba.idsede = se.idsede','left');

		if( !empty($datos['idioma']) ){
    		$this->db->where('( idioma = '. $this->db->escape($datos['idioma']) . ' OR idioma IS NULL)');
		}
    		$this->db->where('estado_ba', 1);
    	return $this->db->get()->result_array();
    }
	public function m_get_banners_zona($datos) {
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
    	$this->db->where('zona', $datos['zona']);
		$this->db->where('idsede', $datos['idsede']);
		if( !empty($datos['idioma']) ){
    		$this->db->where('idioma', $datos['idioma']);
		}
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

	// crud
	public function m_registrar($data)
	{
		$this->db->insert('banner', $data);
		return $this->db->insert_id();
	}
	public function m_editar($data,$id){
		$this->db->where('idbanner',$id);
		return $this->db->update('banner', $data);
	}
}
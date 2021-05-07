<?php
class Model_sede extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function m_cargar_sedes()
	{
		$this->db->select("
			se.idsede,
			se.descripcion_se,
			se.telefono,
			se.email,
			se.imagen_se
		", FALSE);
		$this->db->from('sede se');
		$this->db->where('se.estado_se', 1);
		$this->db->order_by('idsede', 'ASC');
		return $this->db->get()->result_array();
	}
	public function m_cargar_sedes_pagina($datos)
	{
		$this->db->select("
			se.idsede,
			se.descripcion_se,
			se.telefono,
			se.email,
			se.imagen_se,
			sp.idsedepagina,
			sp.titulo,
			sp.descripcion,
			sp.horario,
			sp.direccion
		", FALSE);
		$this->db->from('sede se');
		$this->db->join('sede_pagina sp', 'se.idsede = sp.idsede');
		$this->db->where('se.estado_se', 1);
		$this->db->where('sp.idioma', $datos['idioma']);
		$this->db->where('sp.estado_sp', 1);
		$this->db->order_by('descripcion_se', 'ASC');
		return $this->db->get()->result_array();
	}

	public function m_registrar($data)
	{
		$this->db->insert('sede', $data);
		return $this->db->insert_id();
	}
	public function m_editar($data,$id){
		$this->db->where('idsede',$id);
		return $this->db->update('sede', $data);
	}

}
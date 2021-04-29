<?php
class Model_centro_medico extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function m_cargar_centros_medicos()
	{
		$this->db->select("
			cm.idcentromedico,
			cm.nombre,
			cm.titulo,
			cm.descripcion,
			cm.direccion,
			cm.telefono,
			cm.email,
			cm.horario,
			cm.imagen,
			cm.created_at,
			cm.updated_at,
			cm.estado
		", FALSE);
		$this->db->from('centro_medico cm');
		$this->db->where('estado', 1);
		$this->db->order_by('idcentromedico', 'ASC');
		return $this->db->get()->result_array();
	}

	public function m_registrar($data)
	{
		$this->db->insert('centro_medico', $data);
		return $this->db->insert_id();
	}
	public function m_editar($data,$id){
		$this->db->where('idcentromedico',$id);
		return $this->db->update('centro_medico', $data);
	}

}
<?php
class Model_servicio extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function m_cargar_servicios()
	{
		$this->db->select("
			ser.idservicio,
			ser.nombre,
			ser.icono,
			ser.estado_ser
		", FALSE);
		$this->db->from('servicio ser');
		$this->db->where('se.estado_ser', 1);
		$this->db->order_by('idservicio', 'ASC');
		return $this->db->get()->result_array();
	}
	public function m_cargar_sede_servicio($datos)
	{
		$this->db->select("
			ser.idservicio,
			si.nombre_serv AS servicio,
			ser.icono
		", FALSE);
		$this->db->from('servicio ser');
		$this->db->join('sede_servicio ss', 'ser.idservicio = ss.idservicio');
		$this->db->join('servicio_idioma si', 'ser.idservicio = si.idservicio');
		$this->db->where('ss.idsede', $datos['idsede']);
		$this->db->where('si.idioma', $datos['idioma']);
		$this->db->where('ser.estado_ser', 1);
		$this->db->where('ss.estado_ss', 1);
		$this->db->where('si.estado_si', 1);
		$this->db->order_by('idservicio', 'ASC');
		return $this->db->get()->result_array();
	}

	public function m_registrar($data)
	{
		$this->db->insert('servicio', $data);
		return $this->db->insert_id();
	}
	public function m_editar($data,$id){
		$this->db->where('idservicio',$id);
		return $this->db->update('servicio', $data);
	}

}
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
			ser.estado_ser
		", FALSE);
		$this->db->from('servicio ser');
		$this->db->where('se.estado_ser', 1);
		$this->db->order_by('idservicio', 'ASC');
		return $this->db->get()->result_array();
	}
	public function m_cargar_sede_servicios($datos)
	{
		$this->db->select("
			ss.idsedeservicio as id,
			ser.idservicio,
			ssi.nombre_serv AS servicio,
			ss.icono
		", FALSE);
		$this->db->from('servicio ser');
		$this->db->join('sede_servicio ss', 'ser.idservicio = ss.idservicio');
		$this->db->join('sede_servicio_idioma ssi', 'ss.idsedeservicio = ssi.idsedeservicio');
		$this->db->where('ss.idsede', $datos['idsede']);
		$this->db->where('ssi.idioma', $datos['idioma']);
		$this->db->where('ser.estado_ser', 1);
		$this->db->where('ss.estado_ss', 1);
		$this->db->order_by('idservicio', 'ASC');
		return $this->db->get()->result_array();
	}

	public function m_cargar_servicio_sede($data)
	{
		$this->db->select("
			se.idsede,
			se.descripcion_se,
			se.segmento_amigable,
			ss.idsedeservicio as id,
			ssi.nombre_serv AS servicio,
			ss.icono,
			ss.imagenes,
			ss.estado_ss,
			ssi.titulo,
			ssi.descripcion
		", FALSE);
		$this->db->from('sede_servicio ss');
		$this->db->join('sede_servicio_idioma ssi', 'ss.idsedeservicio = ssi.idsedeservicio');
		$this->db->join('sede se', 'ss.idsede = se.idsede');
		$this->db->where('ss.idsedeservicio', $data['idsedeservicio']);
		$this->db->where('ssi.idioma', $data['idioma']);
		$this->db->limit('1');
		return $this->db->get()->row_array();
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
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
		$this->db->where('ser.estado_ser', 1);
		$this->db->order_by('idservicio', 'ASC');
		return $this->db->get()->result_array();
	}
	public function m_cargar_servicios_agregados($datos)
	{
		$this->db->select("
			ser.idservicio,
			ser.nombre AS servicio,
			ser.estado_ser,
			ss.idsedeservicio AS id
		", FALSE);
		$this->db->from('servicio ser');
		$this->db->join('sede_servicio ss', 'ser.idservicio = ss.idservicio');
		$this->db->where('ser.estado_ser', 1);
		$this->db->where('ss.estado_ss', 1);
		$this->db->where('ss.idsede', $datos['idsede']);
		$this->db->order_by('idservicio', 'ASC');
		return $this->db->get()->result_array();
	}
	public function m_cargar_servicios_no_agregados($datos)
	{
		$this->db->select("
			ser.idservicio,
			ser.nombre AS servicio,
			ser.estado_ser
		", FALSE);
		$this->db->from('servicio ser');
		$this->db->join('sede_servicio ss', 'ser.idservicio = ss.idservicio AND ss.estado_ss = 1 AND ss.idsede = ' . $datos['idsede'],'left');
		$this->db->where('ser.estado_ser', 1);
		$this->db->where('ss.idsedeservicio IS NULL');
		$this->db->order_by('idservicio', 'ASC');
		return $this->db->get()->result_array();
	}
	public function m_cargar_sede_servicios($datos)
	{
		$this->db->select("
			ss.idsedeservicio as id,
			ser.idservicio,
			ser.nombre AS servicio,
			ssi.idsedeservicioidioma,
			ssi.nombre_serv,
			ssi.titulo,
			ssi.descripcion,
			ss.icono,
			ss.telefono_contacto,
			ss.codigo_youtube,
			ss.codigo_vimeo
		", FALSE);
		$this->db->from('servicio ser');
		$this->db->join('sede_servicio ss', 'ser.idservicio = ss.idservicio');
		$this->db->join('sede_servicio_idioma ssi', 'ss.idsedeservicio = ssi.idsedeservicio AND ssi.idioma = ' . $this->db->escape($datos['idioma']),'left');
		$this->db->where('ss.idsede', $datos['idsede']);
		// $this->db->where('ssi.idioma', $datos['idioma']);
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
			ss.telefono_contacto,
			ss.imagenes,
			ss.codigo_youtube,
			ss.codigo_vimeo,
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

	// servicio
	public function m_registrar($data)
	{
		$this->db->insert('servicio', $data);
		return $this->db->insert_id();
	}
	public function m_editar($data,$id){
		$this->db->where('idservicio',$id);
		return $this->db->update('servicio', $data);
	}

	// sede servicio
	public function m_registrar_sede_servicio($data)
	{
		$this->db->insert('sede_servicio', $data);
		return $this->db->insert_id();
	}

	public function m_editar_sede_servicio($data,$id){
		$this->db->where('idsedeservicio',$id);
		return $this->db->update('sede_servicio', $data);
	}

	// sede servicio idioma
	public function m_registrar_sede_servicio_idioma($data)
	{
		$this->db->insert('sede_servicio_idioma', $data);
		return $this->db->insert_id();
	}

	public function m_editar_sede_servicio_idioma($data,$id){
		$this->db->where('idsedeservicioidioma',$id);
		return $this->db->update('sede_servicio_idioma', $data);
	}

	// galeria
	public function m_cargar_galeria_sede_servicio($data)
	{
		$this->db->select("
			ss.idsedeservicio,
			ss.icono,
			ss.imagenes,
			ss.estado_ss
		", FALSE);
		$this->db->from('sede_servicio ss');
		// $this->db->join('sede se', 'ss.idsede = se.idsede');
		$this->db->where('ss.idsedeservicio', $data['idsedeservicio']);
		$this->db->limit('1');
		return $this->db->get()->row_array();
	}

}
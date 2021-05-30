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
			se.icono,
			se.segmento_amigable,
			sp.idsedepagina,
			sp.titulo,
			sp.descripcion,
			sp.horario,
			sp.direccion,
			sp.direccion2
		", FALSE);
		$this->db->from('sede se');
		$this->db->join('sede_pagina sp', 'se.idsede = sp.idsede AND sp.estado_sp = 1 AND sp.idioma = ' . $this->db->escape($datos['idioma']),'left');
		$this->db->where('se.estado_se', 1);

		$this->db->order_by('descripcion_se', 'DESC');
		return $this->db->get()->result_array();
	}

	public function m_cargar_sede_por_segmento($datos)
	{
		$this->db->select("
			se.idsede,
			se.descripcion_se,
			se.telefono,
			se.email,
			se.imagen_se,
			se.icono,
			se.segmento_amigable,
			sp.idsedepagina,
			sp.titulo,
			sp.descripcion,
			sp.horario,
			sp.direccion,
			sp.direccion2,
			sp.ubicacion
		", FALSE);
		$this->db->from('sede se');
		$this->db->join('sede_pagina sp', 'se.idsede = sp.idsede AND sp.estado_sp = 1 AND sp.idioma = ' . $this->db->escape($datos['idioma']),'left');
		$this->db->where('se.segmento_amigable', $datos['segmento']);

		$this->db->limit('1');
		return $this->db->get()->row_array();
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

	public function m_registrar_sede_pagina($data)
	{
		$this->db->insert('sede_pagina', $data);
		return $this->db->insert_id();
	}
	public function m_editar_sede_pagina($data,$id){
		$this->db->where('idsedepagina',$id);
		return $this->db->update('sede_pagina', $data);
	}

}
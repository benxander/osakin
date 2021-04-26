<?php
class Model_evento extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function m_cargar_eventos($paramPaginate=FALSE)
	{
		$this->db->select("
			ev.idevento,
			ev.titulo,
			ev.fecha,
			ev.estado
		", FALSE);
		$this->db->from('evento ev');
		if( isset($paramPaginate['search'] ) && $paramPaginate['search'] ){
			foreach ($paramPaginate['searchColumn'] as $key => $value) {
				if(! empty($value)){
					$this->db->like($key ,strtoupper_total($value) ,FALSE);
				}
			}
		}

		if( $paramPaginate['sortName'] ){
			$this->db->order_by($paramPaginate['sortName'], $paramPaginate['sort']);
		}
		if( $paramPaginate['firstRow'] || $paramPaginate['pageSize'] ){
			$this->db->limit($paramPaginate['pageSize'],$paramPaginate['firstRow'] );
		}
		return $this->db->get()->result_array();
	}

	public function m_count_eventos($paramPaginate=FALSE)
	{
		$this->db->select('count(*) AS contador');
		$this->db->from('evento ev');
		if( isset($paramPaginate['search'] ) && $paramPaginate['search'] ){
			foreach ($paramPaginate['searchColumn'] as $key => $value) {
				if(! empty($value)){
					$this->db->like($key ,strtoupper_total($value) ,FALSE);
				}
			}
		}
		$fData = $this->db->get()->row_array();
		return $fData['contador'];
	}

	public function m_cargar_evento()
	{
		$this->db->select("
			ev.idevento,
			ev.titulo AS evento,
			ev.fecha,
			fi.idficha,
			fi.titulo,
			fi.descripcion,
			fi.imagen,
		", FALSE);
		$this->db->from('evento ev');
		$this->db->join('ficha fi', 'ev.idevento = fi.idevento');
		$this->db->where('ev.estado', 1);
		$this->db->where('estado_fi', 1);
		$this->db->order_by('fi.orden', 'ASC');
		return $this->db->get()->result_array();
	}
	public function m_cargar_ganadores_evento()
	{
		$this->db->select("
			ev.idevento,
			ev.titulo AS evento,
			ev.fecha,
			fi.idficha,
			fi.titulo,
			fi.imagen,
			fi.orden,
			fi.idparticipante,
			pa.nombres,
			pa.apellidos
		", FALSE);
		$this->db->from('evento ev');
		$this->db->join('ficha fi', 'ev.idevento = fi.idevento');
		$this->db->join('participante pa', 'fi.idparticipante = pa.idparticipante');
		$this->db->where('ev.estado', 2);
		$this->db->where('estado_fi', 1);
		$this->db->order_by('fi.orden', 'ASC');
		return $this->db->get()->result_array();
	}

	public function m_cargar_ficha($id)
	{
		$this->db->select("
			idficha,
			titulo,
			descripcion,
			imagen,
			estado_fi
		", FALSE);
		$this->db->from('ficha fi');
		$this->db->where('fi.idficha', $id);
		$this->db->limit('1');
		return $this->db->get()->row_array();
	}

	public function m_cargar_premios_sorteo($datos)
	{
		$this->db->select("
			idficha,
			titulo,
			descripcion,
			imagen,
			orden
		", FALSE);
		$this->db->from('ficha fi');
		$this->db->where('fi.idevento', $datos['idevento']);
		$this->db->order_by('orden', 'ASC');
		return $this->db->get()->result_array();
	}

	public function m_cargar_sorteo_activo()
	{
		$this->db->select("
			ev.idevento,
			ev.titulo,
			ev.fecha,
			ev.estado
		", FALSE);
		$this->db->from('evento ev');
		$this->db->where('estado', 1);
		$this->db->limit(1);
		$this->db->order_by('idevento', 'DESC');
		return $this->db->get()->row_array();

	}

	public function m_registrar($data)
	{
		$this->db->insert('evento', $data);
		return $this->db->insert_id();
	}
	public function m_registrar_afiliado($data)
	{
		$this->db->insert('evento_participante', $data);
		return $this->db->insert_id();
	}
	public function m_editar($data,$id){
		$this->db->where('idevento',$id);
		return $this->db->update('evento', $data);
	}
	public function m_actualizar_premio($data,$id){
		$this->db->where('idficha',$id);
		return $this->db->update('ficha', $data);
	}
}
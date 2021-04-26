<?php
class Model_participante extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_participantes($paramPaginate=FALSE)
	{
		$this->db->select("
			idparticipante,
			nombres,
			apellidos,
			telefono,
			email,
			codigo_postal,
			fecha_registro,
			ip,
			estado_pa
		", FALSE);
		$this->db->from('participante pa');
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

	public function m_count_participantes($paramPaginate=FALSE)
	{
		$this->db->select('count(*) AS contador');
		$this->db->from('participante pa');
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
	public function m_cargar_afiliado_sorteo($paramPaginate=FALSE,$paramDatos)
	{
		$this->db->select("
			pa.idparticipante,
			pa.nombres,
			pa.apellidos,
			pa.telefono,
			pa.email,
			pa.codigo_postal,
			pa.fecha_registro,
			pa.ip,
			pa.estado_pa
		", FALSE);
		$this->db->from('participante pa');
		$this->db->join('evento_participante ep', 'pa.idparticipante = ep.idparticipante');
		$this->db->where('idevento', $paramDatos['idevento']);
		if($paramPaginate){
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
		}
		return $this->db->get()->result_array();
	}

	public function m_count_afiliado_sorteo($paramPaginate=FALSE,$paramDatos)
	{
		$this->db->select('count(*) AS contador');
		$this->db->from('participante pa');
		$this->db->join('evento_participante ep', 'pa.idparticipante = ep.idparticipante');
		$this->db->where('idevento', $paramDatos['idevento']);
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
	public function m_registro($datos)
	{
		$this->db->insert('participante', $datos);
		return $this->db->insert_id();
	}

	public function m_get_por_columna($data)
	{
		$this->db->select("
			idparticipante,
			nombres,
			apellidos,
			telefono,
			email,
			fecha_registro,
			estado_pa
		", FALSE);
		$this->db->from('participante pa');
		$this->db->where($data['columna'], $data['valor']);
		$this->db->limit('1');

		return $this->db->get()->row_array();
	}

	public function m_verificar_participante_inactivo($id_enc)
	{
		$this->db->select("
			idparticipante,
			nombres,
			apellidos,
			telefono,
			email,
			fecha_registro,
			estado_pa
		", FALSE);
		$this->db->from('participante pa');
		$this->db->where('estado_pa', 2);
		$this->db->where("MD5(pa.idparticipante) = '" . $id_enc . "'");
		$this->db->limit('1');
		return $this->db->get()->row_array();

	}

	public function m_activar_participante($datos)
	{
		$data = array(
            'estado_pa'    => 1
            // 'fecha_activacion' => date('Y-m-d H:i:s')
        );
        $this->db->where('idparticipante',$datos['idparticipante']);
        return $this->db->update('participante', $data);
	}
}
<?php
class Model_acceso extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
 	// ACCESO AL SISTEMA
	public function m_logging_user($data){
		$this->db->select('
			COUNT(*) AS logged,
			us.idusuario,
			us.estado_us,
			us.username,
			us.idgrupo,
			us.nombre_foto
		',FALSE);
		$this->db->from('usuario us');
		$this->db->where('us.username', $data['usuario']);
		// $this->db->where('us.pass', $data['clave'] );
		$this->db->where('us.pass', do_hash($data['clave'] , 'md5'));
		$this->db->where('us.estado_us <>', '0');
		$this->db->group_by('us.idusuario');
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	public function m_actualizar_fecha_ultima_sesion($datos)
	{
		$data = array(
			'ultimo_inicio_sesion' => date('Y-m-d H:i:s'),
			'updatedAt' => date('Y-m-d H:i:s')
		);
		$this->db->where('idusuario',$datos['idusuario']);
		return $this->db->update('usuario', $data);
	}
}
?>
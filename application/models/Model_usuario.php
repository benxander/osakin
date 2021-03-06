<?php
class Model_usuario extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function m_cargar_usuarios($paramPaginate=FALSE){
		$this->db->select("
			us.idusuario,
			us.idconfiguracion,
			us.username,
			us.pass,
			us.estado_us,
			us.mostrar_info_contacto,
			us.mostrar_info_cobro,
			us.mostrar_intro,
			us.nombre_foto,
			us.ultimo_inicio_sesion,
			gr.idgrupo,
			gr.descripcion_gr
		", FALSE);
		$this->db->from('usuario us');
		$this->db->join('grupo gr', 'us.idgrupo = gr.idgrupo');
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
	public function m_count_usuarios($paramPaginate=FALSE){
		$this->db->select('count(*) AS contador');
		$this->db->from('usuario us');
		$this->db->join('grupo gr', 'us.idgrupo = gr.idgrupo');
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

	public function m_cargar_usuario_disponible(){
		$this->db->select('u.idusuario, u.username, u.idgrupo');
		$this->db->from('usuario u');
		$this->db->join('profesionales p', 'u.idusuario = p.idusuario AND u.estado_us = 1 AND p.idusuario = NULL','left');
		return $this->db->get()->result_array();
	}
	public function m_cargar_usuario_search($datos){
		$this->db->select('u.idusuario,u.username',FALSE);
		$this->db->from('usuario u');
		$this->db->where("username LIKE '". $datos['username'] . "'");
		return $this->db->get()->result_array();
	}
	public function m_cargar_usuario_autocomplete($datos){
		$this->db->select('u.idusuario,u.username',FALSE);
		$this->db->from('usuario u');
		$this->db->join('profesional p', 'u.idusuario = p.idusuario AND u.estado_us = 1','left');
		$this->db->where("username LIKE '%". $datos['search'] . "%' AND p.idusuario IS NULL");

		$this->db->limit(10);
		return $this->db->get()->result_array();
	}
	public function m_cargar_usuario_id($datos){
		$this->db->select('idusuario,username,idgrupo',FALSE);
		$this->db->from('usuario');
		$this->db->where('idusuario',$datos['idusuario']);
		$this->db->where('estado_us',1);
		return $this->db->get()->result_array();
	}
	public function m_verificar_usuario_check_pago()
	{
		$this->db->select('mostrar_info_cobro');
		$this->db->from('usuario');
		$this->db->where('idusuario',$this->sessionCM['idusuario']);
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	public function m_registrar($data)
	{
		$this->db->insert('usuario', $data);
		return $this->db->insert_id();
	}
	public function m_editar($data,$id){
		$this->db->where('idusuario',$id);
		return $this->db->update('usuario', $data);
	}

	public function m_anular($datos)
	{
		$data = array(
			'estado_us' => 0,
			'updatedAt' => date('Y-m-d H:i:s')
		);
		$this->db->where('idusuario',$datos['idusuario']);
		return $this->db->update('usuario', $data);
	}
	public function m_cambiar_clave($datos){
		$data = array(
			'pass' => do_hash($datos['pass'],'md5')
		);
		$this->db->where('idusuario',$datos['idusuario']);
		return $this->db->update('usuario', $data);
	}
	public function m_verificar_clave($datos){
		$this->db->select('idusuario,username',FALSE);
		$this->db->from('usuario');
		$this->db->where("idusuario",$datos['idusuario']);
		$this->db->where("pass",do_hash($datos['passAnt'],'md5'));
		return $this->db->get()->result_array();
	}
	public function m_actualizar_intro_no_mostrar()
	{
		$data = array(
			'mostrar_intro' => 2
		);
		$this->db->where('idusuario',$this->sessionCM['idusuario']);
		return $this->db->update('usuario', $data);
	}

	public function m_cargar_grupo()
	{
		$this->db->select("
			idgrupo,
			descripcion_gr
		", FALSE);
		$this->db->from('grupo gr');
		$this->db->order_by('gr.idgrupo', 'ASC');
		return $this->db->get()->result_array();
	}

	public function m_cargar_usuario_empresa_disp()
	{
		$this->db->select("
			us.idusuario,
			us.username
		", FALSE);
		$this->db->from('usuario us');
		$this->db->join('empresa emp', 'us.idusuario = emp.idusuario','left');
		$this->db->where('us.idgrupo', '2');
		$this->db->where('emp.idempresa IS NULL');
		$this->db->order_by('us.idusuario', 'ASC');
		return $this->db->get()->result_array();
	}
}
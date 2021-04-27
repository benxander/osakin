<?php
class Model_config extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function m_cargar_empresa_admin()
	{
		$this->db->select("
			idconfiguracion,
			empresa,
			pagina_web,
			logo_imagen
		", FALSE);
		$this->db->from('w_configuracion wc');
		$this->db->limit('1');
		return $this->db->get()->row_array();
	}

	public function m_cargar_configuracion_por_usuario($datos)
	{
		$this->db->select("
			us.idusuario,
			us.estado_us,
			us.username,
			us.idgrupo,
			us.nombre_foto

		", FALSE);
		$this->db->from('usuario us');

		$this->db->where('us.idusuario', $datos['idusuario']);
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	public function m_cargar_configuraciones() {
		$this->db->select("
			id,
			elemento,
			descripcion,
			valor,
			tipo
		", FALSE);
		$this->db->from('configuracion co');
		return $this->db->get()->result_array();

	}

	//======================================================================================================
	// OBTENER TODAS LAS IMAGENES
	//======================================================================================================
	public function get_imagen($variable) {
		$data = array();
		$sql = "SELECT * FROM imagenes WHERE nombre = ?";
        $res = $this->db->query($sql,$variable);

		if ($res->num_rows() > 0) {
            $rows = $res->result_array();
            return $rows[0];
        } else {
            return 0;
        }
	}
	public function m_obtener_imagenes() {
		$this->db->select('nombre, imagen, titulo');
		$this->db->from('imagenes');
		return $this->db->get()->result_array();
	}
	//======================================================================================================
	// OBTENER DATOS DEL PIE DE PAGINA
	//======================================================================================================
	public function get_footer() {
		$this->db->select('nombre, valor');
		$this->db->from('piepagina');
		$this->db->where('activo', 1);
		return $this->db->get()->result_array();
	}

	//======================================================================================================
	// OBTENER DATOS DEL PIE DE PAGINA
	//======================================================================================================
	public function get_redes($variable) {
		$data = array();
		$sql = "SELECT * FROM redes_sociales WHERE red_social = ?";
        $res = $this->db->query($sql,$variable);

		if ($res->num_rows() > 0) {
            $rows = $res->result_array();
            return $rows[0];
        } else {
            return 0;
        }
	}
}
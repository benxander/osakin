<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getConfig()
{
	$CI = & get_instance();
    $CI->load->model('Model_config');

    $lista = $CI->Model_config->m_cargar_configuraciones();
    $arrPrincipal = array();
    foreach ($lista as $row) {
		$arrPrincipal[$row['tipo']][$row['elemento']] = $row['valor'];
	}
	return $arrPrincipal;
}
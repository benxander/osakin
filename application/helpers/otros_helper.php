<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function enviar_mail($to, $asunto = 'Email de prueba', $message = 'Esto es una prueba') {

	$CI = & get_instance();

	$CI->load->library('email');

	$salida = $CI->load->view('plantilla_email',array(),TRUE);

	$CI->email->from(EMAIL_FROM, SITIO_WEB);
	$CI->email->to($to);
	$CI->email->subject($asunto);
	$CI->email->message(str_replace("{contenido}", $message, $salida));

	return $CI->email->send();
}

// para verificar si un string esta compuesto de solo numeros sin comas ni puntos
function soloNumeros($laCadena) {
    $carsValidos = "0123456789";
    for ($i=0; $i<strlen($laCadena); $i++) {
      if (strpos($carsValidos, substr($laCadena,$i,1))===false) {
         return false;
      }
    }
    return true;
}

function strtoupper_total($string){
  return strtr(strtoupper($string),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
}
function strtolower_total($string){
  return strtr(strtolower($string),"ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ","àèìòùáéíóúçñäëïöü");
}
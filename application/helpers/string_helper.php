<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/** Helper que reemplaza al strip_tags() de PHP y ademas elimina caracteres de control
 * @author Ing. Ruben Guevara <rguevarac@hotmail.es>
 */
if ( ! function_exists('rip_tags')){
    function rip_tags($string){
      $conv = array(
        '&aacute;' => 'á',
        '&eacute;' => 'é',
        '&iacute;' => 'í',
        '&oacute;' => 'ó',
        '&uacute;' => 'ú',
        '&Aacute;' => 'Á',
        '&Eacute;' => 'É',
        '&Iacute;' => 'Í',
        '&Oacute;' => 'Ó',
        '&Uacute;' => 'Ú',
        '&Ntilde;' => 'Ñ',
        '&ntilde;' => 'ñ',
        '&iquest;' => '¿',
        '&quot;' => '"',
        '&nbsp;' => ' '
        );
      $string = strtr($string, $conv);
      // ----- remove HTML TAGs -----
      $string = preg_replace ('/<[^>]*>/', ' ', $string);

      // ----- remove control characters -----
      $string = str_replace("\r", '', $string);    // --- replace with empty space
      $string = str_replace("\n", ' ', $string);   // --- replace with space
      $string = str_replace("\t", ' ', $string);   // --- replace with space

      // ----- remove multiple spaces -----
      $string = trim(preg_replace('/ {2,}/', ' ', $string));

      return $string;
	}
}
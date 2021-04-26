<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
	public function __construct(){
        parent::__construct();

		$this->load->helper(array('otros','fechas'));
		$this->load->model(array('model_participante'));
		$this->load->library('excel');
	}

	public function afiliados_por_sorteo_excel()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		// TRATAMIENTO DE DATOS //
			$paramDatos = $allInputs['resultado'];
			$nombre_reporte = 'afiliados_sorteo';
			$titulo = 'Lista de afiliados - '. $paramDatos['titulo'];
			$nombre_hoja = date('Y-m-d');


			$lista = $this->model_participante->m_cargar_afiliado_sorteo(FALSE,$paramDatos);

			if(empty($lista)){
				$arrData['flag'] = 0;
				$arrData['message'] = 'No hay datos';
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($arrData));
				return;
			}


			$arrListadoProd = array();
			$i = 1;

			foreach ($lista as $row) {
				array_push($arrListadoProd,
					array(
						$i++,
						$row['idparticipante'],
						$row['nombres'],
						$row['apellidos'],
						$row['telefono'],
						$row['email'],
						$row['codigo_postal'],
						darFormatoDMY($row['fecha_registro']),
						darFormatoHora($row['fecha_registro']),
						$row['ip']
					)
				);
			}

		// SETEO DE VARIABLES
			$dataColumnsTP = array(
				array( 'col' => '#',           		'ancho' => 7 , 	'align' => 'L' ),
				array( 'col' => 'COD AFILIADO',		'ancho' => 12, 	'align' => 'C' ),
				array( 'col' => 'NOMBRES',			'ancho' => 30, 	'align' => 'L' ),
				array( 'col' => "APELLIDOS",		'ancho' => 40, 	'align' => 'L' ),
				array( 'col' => 'TELEFONO', 		'ancho' => 15, 	'align' => 'C' ),
				array( 'col' => 'EMAIL',			'ancho' => 40, 	'align' => 'L' ),
				array( 'col' => 'COD. POSTAL',		'ancho' => 12, 	'align' => 'C' ),
				array( 'col' => 'FECHA REGISTRO',	'ancho' => 12, 	'align' => 'C' ),
				array( 'col' => 'HORA REGISTRO',	'ancho' => 12, 	'align' => 'C' ),
				array( 'col' => 'IP',			  	'ancho' => 15, 	'align' => 'C' )
			);
		
			$cantColumns = count($dataColumnsTP);
			$arrColumns = array();
			$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
			$a = 'B'; // INICIO DE COLUMNA
			for ($x=0; $x < $cantColumns; $x++) {
				$arrColumns[] = $a++;
			}
			$endColum = end($arrColumns);
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle($nombre_hoja);
			$this->excel->getActiveSheet()->setShowGridlines(true);

		// ESTILOS
			$styleArrayTitle = array(
				'font'=>  array(
					'bold'  => false,
					'size'  => 18,
					'name'  => 'calibri',
					'color' => array('rgb' => 'FFFFFF')
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array( 'rgb' => '3A3838' )
				),
			);
			$styleArraySubTitle = array(
				'font'=>  array(
					'bold'  => false,
					'size'  => 12,
					'name'  => 'Microsoft Sans Serif',
					'color' => array('rgb' => 'FFFFFF')
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array( 'rgb' => '3A3838' )
				),
			);
			$styleArrayHeader = array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
				'font'=>  array(
					'bold'  => false,
					'size'  => 10,
					'name'  => 'calibri',
					'color' => array('rgb' => 'FFFFFF')
				),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array( 'rgb' => '5B9BD5' )
				),
			);
		// TITULO
			$this->excel->getActiveSheet()->getCell($arrColumns[0].'1')->setValue($titulo);
			$this->excel->getActiveSheet()->getStyle($arrColumns[0].'1')->applyFromArray($styleArrayTitle);
			$this->excel->getActiveSheet()->mergeCells($arrColumns[0].'1:'. $endColum .'1');
		
			$this->excel->getActiveSheet()->getCell('B3')->setValue('FECHA DE SORTEO : ');
			$this->excel->getActiveSheet()->getCell('D3')->setValue($paramDatos['fecha_cf']);
				
			$currentCellEncabezado = 6; // donde inicia el encabezado del listado
			$fila_mes = $currentCellEncabezado - 1;
			$fila = $currentCellEncabezado + 1;
			$pieListado = $fila + count($arrListadoProd);

		// ENCABEZADO DE LA LISTA
			$i=0;
			foreach ($dataColumnsTP as $key => $value) {
				$this->excel->getActiveSheet()->getColumnDimension($arrColumns[$i])->setWidth($value['ancho']);
				$this->excel->getActiveSheet()->getCell($arrColumns[$i].$currentCellEncabezado)->setValue($value['col']);
				if( $value['align'] == 'C' ){
					$this->excel->getActiveSheet()->getStyle($arrColumns[$i].$fila .':'.$arrColumns[$i].$pieListado)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}

				$i++;
			}
			$c1 = $i;
			$this->excel->getActiveSheet()->getStyle($arrColumns[0].$currentCellEncabezado.':'.$endColum.$currentCellEncabezado)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle($arrColumns[0].($currentCellEncabezado).':'.$endColum.($currentCellEncabezado))->applyFromArray($styleArrayHeader);
			$this->excel->getActiveSheet()->getRowDimension($currentCellEncabezado)->setRowHeight(30);
			$this->excel->getActiveSheet()->setAutoFilter($arrColumns[0].$currentCellEncabezado.':'.$endColum.$currentCellEncabezado);

		// LISTA
			$this->excel->getActiveSheet()->fromArray($arrListadoProd, null, $arrColumns[0].$fila);
			$this->excel->getActiveSheet()->freezePane($arrColumns[0].$fila);

		$objWriter = new PHPExcel_Writer_Excel2007($this->excel);
		$time = date('YmdHis_His');
		$objWriter->save('uploads/reportes/'. $nombre_reporte . '_'.$time.'.xlsx');

		$arrData = array(
			'urlTempEXCEL'=> '../uploads/reportes/'. $nombre_reporte . '_'.$time.'.xlsx',
			'flag'=> 1
		);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($arrData));
	}
}
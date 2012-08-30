<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="export_fine_'.date("Y-m-d").'.xls"');
header('Cache-Control: max-age=0');

$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Mouf Fine")
							 ->setLastModifiedBy("Mouf Fine")
							 ->setTitle("Mouf Export Translation")
							 ->setSubject("Mouf Export Translation")
							 ->setDescription("Fine Translation export")
							 ->setKeywords("mouf fine export translation")
							 ->setCategory("Mouf export");

$objSheet = $objPHPExcel->setActiveSheetIndex(0);
							 
$letter = 1;
foreach ($this->languages as $value) {
	$objSheet->setCellValue($this->letters[$letter]."1", $value);
	$letter ++;
}
$num = 2;
foreach ($this->msgs as $key => $elements) {
	$objSheet->setCellValue("A".$num, $key);
	$letter = 1;
	foreach ($elements as $value) {
		$objSheet->setCellValue($this->letters[$letter].$num, $value);
		$letter ++;
	}
	$num ++;
}

// Save Excel5 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save("php://output");

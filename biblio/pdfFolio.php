<?php

include('conexion.php');
require('librerias/fpdf/fpdf.php');
error_reporting(0);
session_start();



$pdf = new FPDF('L','mm',array(315,299));
$pdf->AliasNbPages();
$pdf->AddPage();

$id='';
if (isset($_GET['id'])) {
	$id=$_GET['id'];
}

$result = $conn->query("SELECT * FROM alumno  where id ='$id'");
$datos=  $result -> fetch_assoc();

$nomDir =substr($datos['cedula'], 0, 7);//solo sera de 7 caracteres

//ruta de la imagen a pasar a pdf
$ruta='archivos/CI_'.$nomDir.'/'.$datos['archivo'];

$pdf->Image($ruta,0,0,315, 299);










$pdf->Output('I','folio.pdf', true);





?>


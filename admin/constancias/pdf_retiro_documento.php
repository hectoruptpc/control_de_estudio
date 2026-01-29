<?php
ob_start(); error_reporting(0); ini_set('display_errors',0);
require_once('../../funciones/functions.php'); require_once('../fpdf/fpdf.php');
if (!isset($_GET['id'])) die('ID no proporcionado.');
$id=intval($_GET['id']); $q="SELECT * FROM users WHERE id=? LIMIT 1"; $s=$db->prepare($q); $s->bind_param('i',$id); $s->execute(); $est=$s->get_result()->fetch_assoc(); if(!$est) die('Estudiante no encontrado.');
function txt($t){return iconv('UTF-8','ISO-8859-1//TRANSLIT',$t);} 
$pdf=new FPDF('P','mm','A4'); $pdf->AddPage(); $pdf->SetMargins(25,10,25);
if(file_exists('../../images/uptpc.png')) $pdf->Image('../../images/uptpc.png',20,10,18);
$pdf->SetFont('Arial','B',12); $pdf->Cell(0,8,txt('RETIRO DE DOCUMENTO'),0,1,'C'); $pdf->Ln(8);
$pdf->SetFont('Arial','',11);
$pdf->MultiCell(0,6,txt('Se deja constancia que ' . strtoupper($est['nombre']) . ' (C.I. ' . $est['idusuario'] . ') ha retirado documento(s) oficiales pertenecientes a su expediente académico.'),0,'J');
$pdf->Ln(12); $pdf->MultiCell(0,6,txt('Documento(s): ....................................... Fecha: __/__/____'),0,'J');
$pdf->Ln(30); $pdf->SetFont('Arial','B',10); $pdf->Cell(0,4,txt('Departamento de Control de Estudios'),0,1,'C');
ob_end_clean(); $pdf->Output('I','Retiro_Documento_' . $est['idusuario'] . '.pdf'); exit();

<?php

include('conexion.php');
require('librerias/fpdf/fpdf.php');




$pdf = new FPDF('L','mm',array(335,325));
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->Image('img/image3.png',15,15,35, 35);
$pdf->Image('img/logo.png',155,15,35, 35);
$pdf->Image('img/image2.png',290,15,35, 35);


$pdf->Ln(35);
////////////////////////////////
//fecha actual de la imprecion

$fechaA = '';
$fecha1 = '';
$fecha2 = '';

if (isset($_GET['fecha'])) {
    
    $fechaA =trim($_GET['fecha']);
}

if (isset($_GET['fecha1'])) {
    
    $fecha1 =trim($_GET['fecha1']);
}

if (isset($_GET['fecha2'])) {
    
    $fecha2 =trim($_GET['fecha2']);
}
//////////////////////////////
//carreras y cantidad de estudiantes

$carrT='';
$carrCode='';
$contador='';
$acto='';
$actoV='';

$titulo='';
$tituloV='';

if (isset($_GET['carrT'])) {
    
    $carrT =trim($_GET['carrT']);
}

if (isset($_GET['contador'])) {
    
    $contador =trim($_GET['contador']);
}

if (isset($_GET['carrCode'])) {
    
    $carrCode =trim($_GET['carrCode']);
}

if (isset($_GET['acto'])) {
    
    $acto =trim($_GET['acto']);
}


if (isset($_GET['actoV'])) {
    
    $actoV =trim($_GET['actoV']);
}


if (isset($_GET['titulo'])) {
    
    $titulo =trim($_GET['titulo']);
}


if (isset($_GET['tituloV'])) {
    
    $tituloV =trim($_GET['tituloV']);
}

//modificar oren de la fecha
$fecha1Array = explode('-', $fecha1);
$fecha2Array = explode('-', $fecha2);

$fecha1Ver = $fecha1Array[2].'/'.$fecha1Array[1].'/'.$fecha1Array[0];
$fecha2Ver = $fecha2Array[2].'/'.$fecha2Array[1].'/'.$fecha2Array[0];


/////////////////////
//titulos


$pdf->Ln(20);
$pdf->SetFont('Arial','B',25);
$pdf->Cell(1);
$pdf->MultiCell(0,10,utf8_decode('Listado de Tipo de Acto') ,0, 'C');
$pdf->SetFont('Arial','B',8);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',25);
$pdf->Cell(1);
$pdf->MultiCell(0,10,utf8_decode('Reporte desde el '.$fecha1Ver.' hasta el '.$fecha2Ver ) ,0, 'C');
$pdf->Ln(5);


$pdf->SetFont('Arial','B',15);
$pdf->Cell(31);
$pdf->MultiCell(0,10,utf8_decode('Carrera: ') ,0, 'L');

$pdf->SetFont('Arial','I',15);
$pdf->Ln(-10);
$pdf->Cell(54);
$pdf->MultiCell(0,10,utf8_decode($carrT) ,0, 'L');



$pdf->SetFont('Arial','B',15);
$pdf->Ln(0);
$pdf->Cell(31);
$pdf->MultiCell(0,10,utf8_decode('Tipo de acto: ') ,0, 'L');

$pdf->SetFont('Arial','I',15);
$pdf->Ln(-10);
$pdf->Cell(66);
$pdf->MultiCell(0,10,utf8_decode($acto) ,0, 'L');

$pdf->SetFont('Arial','B',15);
$pdf->Ln(0);
$pdf->Cell(31);
$pdf->MultiCell(0,10,utf8_decode('Nivel de grado: ') ,0, 'L');

$pdf->SetFont('Arial','I',15);
$pdf->Ln(-10);
$pdf->Cell(71);
$pdf->MultiCell(0,10,utf8_decode($titulo) ,0, 'L');



$pdf->SetFont('Arial','B',15);
$pdf->Ln(0);
$pdf->Cell(31);
$pdf->MultiCell(0,10,utf8_decode('Cantidad de graduados: ') ,0, 'L');

$pdf->SetFont('Arial','I',15);
$pdf->Ln(-10);
$pdf->Cell(96);
$pdf->MultiCell(0,10,utf8_decode($contador) ,0, 'L');





$pdf->Ln(10);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(5);

$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(79,98,142);  
$pdf->SetDrawColor(0,0,0);


$pdf->Ln(-10);
$pdf->Cell(31);
$pdf->SetFillColor(79,98,142);
$pdf->SetDrawColor(0,0,0);
$pdf->MultiCell(30,10,utf8_decode('Graduado') ,'RLTB', 'C',true);


$pdf->Ln(-10);
$pdf->Cell(61);
$pdf->SetFillColor(79,98,142);
$pdf->SetDrawColor(0,0,0);
$pdf->MultiCell(30,10,utf8_decode('Cédula') ,'TBL', 'C',true);



$pdf->Ln(-10);
$pdf->Cell(91);
$pdf->SetFillColor(79,98,142);
$pdf->SetDrawColor(0,0,0);
$pdf->MultiCell(30,10,utf8_decode('Carrera') ,'RTBL', 'C',true);



$pdf->Ln(-10);
$pdf->Cell(121);
$pdf->SetFillColor(79,98,142);
$pdf->SetDrawColor(0,0,0);
$pdf->MultiCell(40,10,utf8_decode('Tipo de acto') ,'RTBL', 'C',true);



$pdf->Ln(-10);
$pdf->Cell(161);
$pdf->SetFillColor(79,98,142);
$pdf->SetDrawColor(0,0,0);
$pdf->MultiCell(40,10,utf8_decode('Nivel de grado') ,'RTBL', 'C',true);


$pdf->Ln(-10);
$pdf->Cell(201);
$pdf->SetFillColor(79,98,142);
$pdf->SetDrawColor(0,0,0);
$pdf->MultiCell(80,10,utf8_decode('Fecha del grado') ,'RTBL', 'C',true);


/*$pdf->Ln(-10);
$pdf->Cell(241);
$pdf->SetFillColor(79,98,142);
$pdf->SetDrawColor(0,0,0);
$pdf->MultiCell(40,10,utf8_decode('Fecha del registro') ,'RTBL', 'C',true);
*/

$pdf->Ln(-10);
$pdf->Cell(70);
$pdf->MultiCell(15,10,' ',0);



//////////////////////////////////////

 $result = $conn->query("SELECT *,
            (SELECT nom FROM carrera where code like carrera) as nomCarr,
            CASE 
            WHEN acto = 1 THEN 'Ceremonia'
            WHEN acto = 2 THEN 'Secretaria'
            ELSE acto
            END AS Actos,
            CASE 
            WHEN titulo = 1 THEN 'TSU'
            WHEN titulo = 2 THEN (SELECT CASE 
            WHEN c.titulo = 1 THEN 'Ingeneria'
            WHEN c.titulo = 2 THEN 'Licenciatura'
            ELSE c.titulo
            END 
            FROM carrera c  where c.code like carrera)
            ELSE titulo
            END AS titulos
            FROM alumno 
            where fechaGrado BETWEEN '$fecha1' AND '$fecha2' 
            and(carrera like '%$carrCode%'
            and acto like '%$actoV%') 
            ORDER BY nomCarr, fechaGrado, nom asc");




  if($tituloV == 1 || $tituloV == ''){

            
     $result = $conn->query("SELECT *,
            (SELECT nom FROM carrera where code like carrera) as nomCarr,
            CASE 
            WHEN acto = 1 THEN 'Ceremonia'
            WHEN acto = 2 THEN 'Secretaria'
            ELSE acto
            END AS Actos,
            CASE 
            WHEN titulo = 1 THEN 'TSU'
            WHEN titulo = 2 THEN (SELECT CASE 
            WHEN c.titulo = 1 THEN 'Ingeneria'
            WHEN c.titulo = 2 THEN 'Licenciatura'
            ELSE c.titulo
            END 
            FROM carrera c  where c.code like carrera)
            ELSE titulo
            END AS titulos
            FROM alumno 
            where fechaGrado BETWEEN '$fecha1' AND '$fecha2' 
            and(
            carrera like '%$carrCode%'
            and acto like '%$actoV%'
            AND  titulo like '%$tituloV%'
            )
            ORDER BY nomCarr, fechaGrado, nom asc");





            }else if($tituloV == 2 || $tituloV == 3){

                $tituloP=intval($tituloV)-1;//titulo del profesional
              $result = $conn->query("SELECT *,
            (SELECT nom FROM carrera where code like carrera) as nomCarr,
            CASE 
            WHEN acto = 1 THEN 'Ceremonia'
            WHEN acto = 2 THEN 'Secretaria'
            ELSE acto
            END AS Actos,
            CASE 
            WHEN titulo = 1 THEN 'TSU'
            WHEN titulo = 2 THEN (SELECT CASE 
            WHEN c.titulo = 1 THEN 'Ingeneria'
            WHEN c.titulo = 2 THEN 'Licenciatura'
            ELSE c.titulo
            END 
            FROM carrera c  where c.code like carrera)
            ELSE titulo
            END AS titulos
            FROM alumno 
            where fechaGrado BETWEEN '$fecha1' AND '$fecha2' 
            and(
            carrera like '%$carrCode%'
            and acto like '%$actoV%'
            and titulo like '2'
            AND carrera in (SELECT c.code
            FROM carrera c  where c.titulo like '%$tituloP%')
            )
            ORDER BY nomCarr, fechaGrado, nom asc");
            }
              
                


//////////////////////////////////////////
$pdf->SetFont('Arial','I',10);
$suma=0;
$total=0;
if(mysqli_num_rows($result)>0){
    
    while ($mostrar = $result->fetch_assoc()) {




// Calcular altura del primer multicell
$nom = utf8_decode($mostrar['nom'].' '.$mostrar['apll']);
$cedula = utf8_decode($mostrar['cedula']);
$carr= utf8_decode($mostrar['nomCarr']);
$fechaG= utf8_decode($mostrar['fechaGrado']);
$tomo= utf8_decode(trim($mostrar['tomo']));
$folio= utf8_decode($mostrar['folio']);
$fecha= utf8_decode($mostrar['fecha']);
$actos = utf8_decode($mostrar['Actos']);

$titulos = utf8_decode($mostrar['titulos']);

$ancho1 = $pdf->GetStringWidth($nom);
$ancho2 = $pdf->GetStringWidth($carr);

$numLineas1 = ceil($ancho1  / 30);
$numLineas2 = ceil($ancho2  / 30);

$altura1 = $numLineas1 * 10;
$altura2 = $numLineas2 * 10;

// Obtener la mayor altura entre los dos multicell
$alturaMaxima = max($altura1, $altura2);

//altura para las columnas que causan el desmadre
$celdaNom=10;//celda de los nombre de los estudiantes
$celdaCarr=10;//celda de los nombre de las carreras
if ($altura1 > $altura2) {

    $celdaNom=10;
    $celdaCarr=$alturaMaxima;
}

if ($altura2 > $altura1) {

    $celdaNom=$alturaMaxima;
    $celdaCarr=10;
}


////////////////////////////////
//dibujar las celdas
$pdf->Cell(31);

$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,$celdaNom,utf8_decode($nom),'LBRT', 'C', true);

$pdf->Ln(-$alturaMaxima);
$pdf->Cell(61);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,$alturaMaxima,utf8_decode($cedula) ,'LBRT', 'C',true);




$pdf->Ln(-$alturaMaxima);
$pdf->Cell(91);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(30,$celdaCarr,$carr,'LBRT', 'C',true);
 

$pdf->Ln(-$alturaMaxima);
$pdf->Cell(121);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(40,$alturaMaxima,utf8_decode($actos) ,'RTBL', 'C',true);


$pdf->Ln(-$alturaMaxima);
$pdf->Cell(161);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(40,$alturaMaxima,utf8_decode($titulos) ,'LBRT', 'C',true);




$pdf->Ln(-$alturaMaxima);
$pdf->Cell(201);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(80,$alturaMaxima,utf8_decode($fechaG) ,'LBRT', 'C',true);


/*$pdf->Ln(-$alturaMaxima);
$pdf->Cell(241);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(40,$alturaMaxima,utf8_decode($fecha) ,'LBRT', 'C',true);
*/






$pdf->Ln(-10);
$pdf->Cell(275);
$pdf->MultiCell(60,10,' ');


}

}else{

$pdf->Cell(31);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(250,10,utf8_decode('Disculpe | no hay resultados'),'LBRT', 'C', true);

}

$pdf->Ln(-10);
$pdf->Cell(130);
$pdf->MultiCell(60,10,' ');





$pdf->Output('I','reporte_Estudiantes.pdf', true);








?>


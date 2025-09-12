<?php
//archo para el registro de estudiantes
// enviar la base de datos
include("conexion.php");
session_start();


//variables con los valores de los inputs evitando la inyección SQL
$nom= mysqli_real_escape_string($conn, trim($_POST['nom']));
$apll= mysqli_real_escape_string($conn, trim($_POST['apll']));
$cedula= mysqli_real_escape_string($conn, trim($_POST['cedula']));
$nFolio= mysqli_real_escape_string($conn, trim($_POST['nFolio']));
$fechaG= mysqli_real_escape_string($conn, trim($_POST['fechaG']));
$carr= mysqli_real_escape_string($conn, trim($_POST['carr']));

$acto= mysqli_real_escape_string($conn, trim($_POST['acto']));
$titulo= mysqli_real_escape_string($conn, trim($_POST['titulo']));

$tomo= mysqli_real_escape_string($conn, trim($_POST['tomo']));


$file= trim($_FILES['file']['name']);

//validar el registro al sistema
   $validar = $conn->query("SELECT * FROM alumno  
   WHERE  cedula = '$cedula'  ");

   $validarFolio = $conn->query("SELECT * FROM alumno  
   WHERE  folio = '$nFolio'
   and tomo='$tomo'");

    if (mysqli_num_rows($validar) > 0) {
    	echo "1";//indicar que el estudiante ya se encuatra registrado en el sistema
     
     }else if (mysqli_num_rows($validarFolio) > 0) {
      echo "2";//indicar que el folio ya esta registrado a otro nombre
     } else{

/*===========================================*/
/*======= CREAR CARPETA O FICHER ============*/
/*===========================================*/
             
                //el sigiente codigo es para crear un nuevo fuchero done el user guardara sus archivos//
                    
                $nomDir =substr($cedula, 0, 7);//solo sera de 7 caracteres
                $my_dir = "archivos/CI_".$nomDir;//hacer cambios
                    if (!is_dir($my_dir)) {
                        
                        mkdir($my_dir);
                    
                    }else{

                        echo "<script>alert('Algo salió mal, $nomDir');</script>";

                    }

             

    if (is_uploaded_file($_FILES['file']['tmp_name'])) {//comprobar si hay una archivo 
     //SE MUEVEN LOS ARCHIVOS AL DIRECTORIO PREDETERMINADO  
        if(move_uploaded_file($_FILES['file']['tmp_name'],$my_dir."/".$_FILES['file']['name'])){ }

    }

 //enviar los datos a la BD
      $envio = $conn->query("INSERT INTO alumno 
      	(nom,
        apll,
        cedula,
        folio,
        fechaGrado,
        archivo,
        carrera,
        acto,
        titulo,
        tomo,
        fecha) 
      	VALUES 
      	('$nom',
        '$apll',
        '$cedula',
        '$nFolio',
        '$fechaG',
        '$file',
        '$carr',
        '$acto',
        '$titulo',
        '$tomo',
		      now()) ");

      //se nos enviaran los siguientes valores a un archivo js 'registroSecc.js'
      
      if ($envio) {
        
        echo "5";//alerta de éxito
      
      }else{

        echo "ERROR SQL";//alerta que nos indicara un error SQL
      }





    }




?>
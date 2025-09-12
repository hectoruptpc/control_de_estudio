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
$id= mysqli_real_escape_string($conn, trim($_POST['id']));

$result = $conn->query("SELECT * FROM alumno WHERE id = '$id' ");
$mostrar = $result -> fetch_assoc();

$file= trim($mostrar['archivo']);

//validar el registro al sistema
   $validar = $conn->query("SELECT * FROM alumno  
   WHERE  cedula = '$cedula'
   and id != '$id'  ");

   $validarFolio = $conn->query("SELECT * FROM alumno  
   WHERE  folio = '$nFolio'
   and tomo='$tomo'
   and id != '$id'");

    if (mysqli_num_rows($validar) > 0) {
    	echo "1";//indicar que el estudiante ya se encuatra registrado en el sistema
     
     }else if (mysqli_num_rows($validarFolio) > 0) {
      echo "2";//indicar que el folio ya esta registrado a otro nombre
     } else{

/*===========================================*/
/*======= CREAR CARPETA O FICHER ============*/
/*===========================================*/
             
                //nombre del fichero//
                    
                $nomDir =substr($cedula, 0, 7);//solo sera de 7 caracteres
                $my_dir = "archivos/CI_".$nomDir;//hacer cambios
                if (!is_dir($my_dir)) {
                        
                        mkdir($my_dir);
                    
                    }

             

    if (is_uploaded_file($_FILES['file']['tmp_name'])) {//comprobar si hay una archivo 
     //SE MUEVEN LOS ARCHIVOS AL DIRECTORIO PREDETERMINADO  
        if(move_uploaded_file($_FILES['file']['tmp_name'],$my_dir."/".$file)){ }

    }

  //se actualizan los datos
       $enviar = $conn->query("UPDATE alumno 
                                    SET 
                                    nom= '$nom',
                                    apll= '$apll',
                                    cedula= '$cedula',
                                    folio= '$nFolio',
                                    fechaGrado= '$fechaG',
                                    archivo= '$file',
                                    carrera= '$carr',
                                    acto='$acto',
                                    titulo='$titulo',
                                    tomo= '$tomo'
                                    WHERE id = '$id'");

 

      //se nos enviaran los siguientes valores a un archivo js 'registroSecc.js'
      
      if ($enviar) {
        
        echo "5";//alerta de éxito
      
      }else{

        echo "ERROR SQL";//alerta que nos indicara un error SQL
      }





    }




?>
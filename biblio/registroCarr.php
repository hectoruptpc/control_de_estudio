<?php
//archo para el registro de estudiantes
// enviar la base de datos
include("conexion.php");
session_start();


//variables con los valores de los inputs evitando la inyección SQL
$nom= mysqli_real_escape_string($conn, trim($_POST['nom']));
$codigo= mysqli_real_escape_string($conn, trim($_POST['codigo']));
$titulo= mysqli_real_escape_string($conn, trim($_POST['titulo']));



//validar el registro al sistema
   $validar = $conn->query("SELECT * FROM carrera  
   WHERE  code = '$codigo'  ");

    if (mysqli_num_rows($validar) > 0) {
    	echo "1";
     
     }else{


             
 //enviar los datos a la BD
      $envio = $conn->query("INSERT INTO carrera 
      	(nom,
        code,
        titulo,
        fecha) 
      	VALUES 
      	('$nom',
        '$codigo',
        '$titulo',
		      now()) ");

      //se nos enviaran los siguientes valores a un archivo js 'registroSecc.js'
      
      if ($envio) {
        
        echo "5";//alerta de éxito
      
      }else{

        echo "ERROR SQL";//alerta que nos indicara un error SQL
      }





    }




?>
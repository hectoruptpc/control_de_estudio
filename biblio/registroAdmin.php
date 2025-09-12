<?php

include("conexion.php");
session_start();


//variables con los valores de los inputs evitando la inyección SQL
$email= mysqli_real_escape_string($conn, trim($_POST['email']));
$clave= mysqli_real_escape_string($conn, md5($_POST['clave']));
$tipo= mysqli_real_escape_string($conn, $_POST['tipo']);


//validar el registro al sistema
    $validar = $conn->query("SELECT * FROM user  
   WHERE  nom = '$email' ");

    $validarUser = $validar ->num_rows; 



if ($validarUser > 0) {

    echo "1";//datos repetidos

}else{

//enviar los datos a la BD
      $envio = $conn->query("INSERT INTO user (nom, pas, tipo, fecha) VALUES ('$email', '$clave', '$tipo', now())");


//se nos enviara un los siguientes valores a un archivo js 'validarRgMt.js'
      if ($envio) {
        
        echo "5";//alerta de éxito
      }else{

        echo "ERROR SQL";//alerta que nos indicara un error SQL
      }




  
   }






?>
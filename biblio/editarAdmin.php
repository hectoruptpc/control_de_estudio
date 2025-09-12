<?php
//archo para editar  administradores
// enviar  a la base de datos
include("conexion.php");
session_start();


//variables con los valores de los inputs evitando la inyección SQL
$email= mysqli_real_escape_string($conn, trim($_POST['email']));
$clave= mysqli_real_escape_string($conn, md5($_POST['clave']));
$id= mysqli_real_escape_string($conn, trim($_POST['id']));


//validar el registro al sistema
   $validar = $conn->query("SELECT * FROM user
   WHERE  nom = '$email'
   and id !='$id'  ");

    if (mysqli_num_rows($validar) > 0) {
    	echo "1";
    }else{

//se actualizan los datos
       $enviar = $conn->query("UPDATE user
                                    SET 
                                    nom='$email',
                                    pas='$clave'
                                    WHERE id = '$id'");

      //se nos enviaran los siguientes valores a un archivo js 'registroSecc.js'
      
      if ($enviar) {
        
        echo "5";//alerta de éxito
      
      }else{

        echo "ERROR SQL";//alerta que nos indicara un error SQL
      }





    }




?>
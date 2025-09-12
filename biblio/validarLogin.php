<?php

include("conexion.php");
session_start();


 if (isset($_SESSION['tipoUser'])) {
 		//indentificar si existe una sección activa
        //echo "<script> window.location = 'index.php'; </script>";

        
      }

//condiciones para entrar en el sistema 


//variables con los valores de los inputs evitando la inyección SQL
$nom= mysqli_real_escape_string($conn, trim($_POST['email']));
$pass= mysqli_real_escape_string($conn, md5($_POST['pass']));



//validar el ingreso al sistema
    $validar = $conn->query("SELECT * FROM user  
   WHERE  nom = '$nom' 
   AND  pas = '$pass' ");

    $validarUser = $validar ->num_rows; 

    $row = $validar -> fetch_assoc();



if ($validarUser > 0) {//validar el ingreso al sistema

    $_SESSION['tipoUser'] = $row['tipo'];
    $_SESSION['idAdmin'] = $row['id'];
    $_SESSION['nomAdmin'] = $nom;
    $_SESSION['passAdmin'] = trim($_POST['pass']);
    //echo "<script> window.location = 'index.php'; </script>";

    echo "5";

}else{//si ninguno de los datos ingresados por el login coinciden se muestra una alerta
       
     echo "1";
  
   }



?>

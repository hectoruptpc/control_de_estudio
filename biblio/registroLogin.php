<?php

include("conexion.php");
session_start();

if (isset($_POST['btn'])) {


//variables con los valores de los inputs evitando la inyección SQL
$email= mysqli_real_escape_string($conn, trim($_POST['email']));
$clave= mysqli_real_escape_string($conn, md5($_POST['clave']));
$tipo= mysqli_real_escape_string($conn, $_POST['tipo']);


//validar el registro al sistema
    $validar = $conn->query("SELECT * FROM user  
   WHERE  nom = '$email' 
   AND  pas = '$clave' ");

    $validarUser = $validar ->num_rows; 



if ($validarUser > 0) {

    echo "1";//datos repetidos

}else{

//enviar los datos a la BD
      $envio = $conn->query("INSERT INTO user (nom, pas, tipo, fecha) VALUES ('$email', '$clave', '$tipo', now())");


//se nos enviara un los siguientes valores a un archivo js 'validarRgMt.js'
      if ($envio) {
        
        echo "<script> alert('exito 5')</script>";//alerta de éxito
      }else{

        echo "ERROR SQL";//alerta que nos indicara un error SQL
      }




  
   }


//recargamos la pagina para evitar que aya acomulación de datos repetidos
echo "<script> window.location='registroLogin.php'; </script>";



}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Registro</title>
	 <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	 <link rel="stylesheet" href="iconos/icon.css">
	 <link rel="stylesheet" type="text/css" href="css/login.css">

</head>

<!--Este es el login del sistema-->

<!--las clases que le ponemos al "body" son pertenecientes de bootstrap que ya tienen estilos 
	predeterminados para saber bien para que sirve cada una solo tienes que investigar su funcionalidad.

	si investigas la clases "bg" te saldrá que sirve para poner colores de fondo a los elementos en este caso "bg-info" nos da un color para el "body" para combiar lo solo tienes investigar otros "bg" de bootstrap para poner otro color de fondo que sea de tu preferencia.

-->

<body class=" bg bg-info bg-secondary bg-gradient d-flex justify-content-center align-items-center vh-100">


	<div class="bgf  p-5 rounded-5 text-secondary shadow" style="width: 25rem" >

<!--inicio del contenido del login -->
		
		<form id="form" method="POST" action="registroLogin.php">

				<div class="d-flex justify-content-center">

        			<!--img src="img/logo.jpg" alt="login" style="height: 7rem"/-->
     	
     	 		</div>

      			<div class="text-center fs-1 fw-bold">Registro</div><!--estos son los cambios -->

			<!--este input es para ingresar el usuario -->
      			<div class="inputLg input-group mt-4">

      				<input type="hidden" name="tipo" value="1">


        			<input id="email" name="email" class="form-control bg-light" type="email" placeholder="" required />
        			<div class="label" for="nom">Correo Electronico:</div>
      			</div>

			<!--este input es para ingresar la clave/contraseña -->
       			<div class="inputLg input-group mt-1">
        			<input id="clave" name="clave" class="form-control bg-light" type="password" placeholder="" required/>


        			<button for="clave" id="boton" type="button"><i id="ojo" class="icon icon-eye-blocked"></i></button>
        			<div class="label" for="clave">Clave:</div>
      			</div>

<!--fin del contenido del login -->

			<!--aquí diseñaremos el botón  -->

      			<div class="contenedorBtn">

      				<button name="btn" id="btn" class="btn btn-info text-white w-100 mt-4 fw-semibold shadow-sm" type="submit">
      					Enviar
      				</button>
        
      			</div>


		</form>
		
	</div>

<script src="js/sweetAlert.js"></script>
<script src="js/verClave.js"></script>
<!--
<script src="js/validarLogin.js"></script>
-->

</body>
</html>
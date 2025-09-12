<?php
/*=============================================*/
/*=== ARCHIVO PARA ELIMINAR administradores ===*/
/*=============================================*/

include("conexion.php");
session_start();
 


$id='';
if (isset($_GET['id'])) {
    
    $id=$_GET['id'];
    
}



//se elimina de la BD
$envio = $conn->query("DELETE FROM user WHERE id = '$id'");



if ($envio) {//alerta de eliminación exitosa

		echo "5";
	
}else{//nos indicara que hay un erro en el SQL
    
	echo "algo salio mal con el SQL";
}

    

?>





<?php
/*========================================================*/
/*===== ARCHIVO PARA ELIMINAR hORARIO DEL ESTUDIANTE =====*/
/*========================================================*/

include("conexion.php");
session_start();
 

$cedulaAlum='';
if (isset($_GET['cedula'])) {
    
   $cedulaAlum=$_GET['cedula'];
    
}

$nomDir =trim(substr($cedulaAlum, 0, 7));

//RUTA DE ELIMINACION DE LOS FICHEROS Y ARCHIVOS
$ruta='archivos/CI_'.$nomDir;

//se elimina de la BD
$envio = $conn->query("DELETE FROM  alumno WHERE cedula = '$cedulaAlum'");



if ($envio) {//alerta de eliminación exitosa

		
	if (is_dir($ruta)) {
	


$files = glob($ruta.'/*');//sera igual a todos los elemotos del archivo//

foreach ($files as $file) {


	if (is_file($file)) {
		
		unlink($file);

		

	}

	if(is_dir($file)){

		rmdir($file);
		

	}


}





if (rmdir($ruta)) {

		echo "5";//alerta que se elimino todo
}



}







}else{//nos indicara que hay un erro en el SQL
    
	echo "algo salio mal con el SQL";
}

    

?>





<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('../../funciones/functions.php');
$query=$db->query("SELECT * FROM ciudad WHERE id_estado=$_GET[estado_id]");
$states = array();
while($r=$query->fetch_object()){ $states[]=$r; }
if(count($states)>0){
print "<option value=''>-- SELECCIONE --</option>";
foreach ($states as $s) {
	print "<option value='$s->id'>$s->name</option>";
}
}else{
print "<option value=''>-- NO HAY DATOS --</option>";
}
?>
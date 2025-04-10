<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Listar Billetera";
include('../../funciones/functions.php');

function lb(){
  global $db, $usua, $id_usua, $operador;

//$arreglo = 0;
if (!isLoggedIn()) {
echo   "Ha sido sacado por el sistema";
}
else {
  $query = "SELECT *
    , SUM(CASE WHEN status = 1 THEN monto ELSE 0 END) OVER(ORDER BY id) AS historial
FROM billetera
WHERE id_usuario = '$id_usua'
AND monto != 0
ORDER BY id DESC";

// SE DEBERIA UTILIZAR ESTE query

$query = "SELECT billetera.id,
billetera.id_usuario,
billetera.monto,
billetera.descripcion,
billetera.id_descripcion,
billetera.fecha,
billetera.status,
SUM(CASE WHEN status = 1 THEN billetera.monto ELSE 0 END) OVER(ORDER BY billetera.id) AS historial,
(CASE
 WHEN billetera.descripcion = 'PEDIDO' THEN pedidos.operador
 WHEN billetera.descripcion = 'MENSUALIDAD' THEN pagos.concepto
 WHEN billetera.descripcion = 'INGRESO' THEN 'Ingreso'
 WHEN billetera.descripcion = 'DEVOLUCION' THEN 'Devolucion'
 WHEN billetera.descripcion = 'FACTOR DE RECONVERSION MONETARIA' THEN 'Reconversion'
 ELSE 0 END) AS detalle
FROM billetera
LEFT JOIN pedidos ON (pedidos.id = billetera.id_descripcion)
LEFT JOIN pagos ON (pagos.id = billetera.id_descripcion)
WHERE billetera.id_usuario = '$id_usua'
AND billetera.monto != 0
ORDER BY billetera.id
DESC";


  $result = mysqli_query($db, $query);
  $row =  mysqli_num_rows($result);

  if ($row == 0){
    die();
  } else {
    $c = $db->query($query);
    while($data = $c->fetch_array(MYSQLI_ASSOC))
     {
       if ($data['status']==='0') {
         $data['status'] = 'ESPERANDO';
       } else if ($data['status']==='1') {
         $data['status'] = 'APROBADO';
       }
       else if ($data['status']==='2') {
         $data['status'] = 'RECHAZADO';
       }


   $arreglo ["data"][] = $data;
 }

 echo json_encode($arreglo);
  }
  mysqli_free_result($result);
  mysqli_close($db);

}
}



lb();


?>

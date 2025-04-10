<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');


function contar_en_esperaAA(){
      global $db, $username, $usua, $contar_pedido,
      $pendiente_pedido, $mes_de_pago_actual, $ganancia_bantecom, $esperando;

      $query = "SELECT SUM(CASE WHEN confirmacion = 'Esperando_Operador' THEN 1 ELSE 0 END) AS 'esperando' FROM `recargar`";
      $result = mysqli_query($db, $query);
      $rows =  mysqli_fetch_assoc($result);
      $esperando = $rows['esperando'];

      if ($esperando>0) {
        $esperando = $esperando;
      } else {
      $esperando = "";
      }

      $esperando = $esperando ;

    }


	function contar_pedidosAA(){
        global $db, $username, $usua, $contar_pedido,
        $pendiente_pedido, $mes_de_pago_actual, $ganancia_bantecom;
        if (isAdmin())
        {
//echo 'ADMIN';
$query = "SELECT sum(monto) AS 'total',
SUM(CASE WHEN status_pedido = 'ESPERANDO' THEN 1 ELSE 0 END) AS 'esperando',
SUM(CASE WHEN status_pedido = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado',
SUM(CASE WHEN status_pedido = 'ENTREGADO' THEN 1 ELSE 0 END) AS 'entregado',
SUM(CASE WHEN status_pedido = 'ENTREGADO' AND DATE_FORMAT(pedidos.fecha_pedido, '%Y%m') = DATE_FORMAT(NOW(), '%Y%m') THEN 1 ELSE 0 END) AS 'entregadomes',
SUM(CASE WHEN status_pedido = 'ENTREGADO' AND DATE_FORMAT(pedidos.fecha_pedido, '%Y%m') = DATE_FORMAT(NOW(), '%Y%m') THEN monto ELSE 0 END) AS 'entregadomesmonto'
FROM pedidos";
		$result = mysqli_query($db, $query);
    $rows =  mysqli_fetch_assoc($result);
$esperando = $rows['esperando'];
$aprobado = $rows['aprobado'];
$entregado = $rows['entregado'];
$entregadomes = $rows['entregadomes'];
$entregadomesmonto = $rows['entregadomesmonto'];

$contar_pedido = $esperando + $aprobado + $entregado .' en Total <br>';

$porentregar = $esperando +
$aprobado;

// if ($porentregar > 0) {
$contar_pedido .= $esperando .' Esperando Aprobacion <br>';
$contar_pedido .= $aprobado .'  Esperando entrega <br>';
$contar_pedido .= $entregado .' General Entregado <br>';
$contar_pedido .= "<b>En el Mes " . $mes_de_pago_actual ." </b><br>" ;
$contar_pedido .= $entregadomes .' Entregado en el Mes <br>';
$contar_pedido .= number_format($entregadomesmonto, 2, ',', '.') .' Bs. Entregado en el Mes <br>';
$ganancia_bantecom = $entregadomesmonto*4.9/100;
$contar_pedido .='Ganancia BANTECOM '.number_format($ganancia_bantecom, 2, ',', '.').' Bs';


if ($porentregar>0) {
  $pendiente_pedido = $porentregar;
} else {
$pendiente_pedido = 0;
}

$pendiente_pedido = $pendiente_pedido;

	// } else {
  //   $contar_pedido .= $entregado .' Ya Entregados <br>';
  //   $pendiente_pedido = "";
	// }
        } else {
//echo 'USUARIO';

$sql="SELECT sum(monto) AS 'total',
    SUM(CASE WHEN status_pedido = 'ESPERANDO' THEN 1 ELSE 0 END) AS 'esperando',
    SUM(CASE WHEN status_pedido = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado',
    SUM(CASE WHEN status_pedido = 'RECHAZADO' THEN 1 ELSE 0 END) AS 'rechazado',
    SUM(CASE WHEN status_pedido = 'ENTREGADO' THEN 1 ELSE 0 END) AS 'entregado'
    FROM pedidos
    WHERE usuario = '$usua'";
$resultsql = mysqli_query($db, $sql);

while ($rowsql = mysqli_fetch_assoc($resultsql))
  {
    if ($rowsql['total']<1){
      echo 'No hay Pedidos <br>';
        } else {
     //echo "Cantidad en Bs. Pagados a la fecha ".$rowsql['total']." Bs.<br>";
     echo "Aprobados ".$rowsql['aprobado']."<br>";
     echo "Esperando ".$rowsql['esperando']."<br>";
     echo "Entregado ".$rowsql['entregado']."<br>";
     echo "Rechazados ".$rowsql['rechazado']."<br>";
     //echo 'SUS PEDIDOS!';

    }}

$query = "SELECT * FROM pedidos  WHERE usuario = '$usua'";
		$resultA = mysqli_query($db, $query);
    $rows =  mysqli_num_rows($resultA);
    $row =  mysqli_fetch_assoc($resultA);
    $arra =  mysqli_fetch_array($resultA);
echo 'Usted posee '.$rows .' en Total <br>';
//echo 'SUS PEDIDOS!';
        }
}

contar_en_esperaAA();
contar_pedidosAA();

 ?>


<span id="pedidosA" class="badge badge-light"><?php
@$suma_resumen = intval($pendiente_pedido) + intval($esperando);
if ($suma_resumen == 0) {
// code...
$suma_resumen = '';
}
echo $suma_resumen; ?></span>

<span id="pedidosB" class="badge badge-warning"><?php
if ($pendiente_pedido == 0) {
// code...
$pendiente_pedido = '';
}
echo $pendiente_pedido; ?></span>

<span id="pedidosC" class="badge badge-warning"><?php
if ($esperando == 0) {
// code...
$esperando = '';
}
echo $esperando; ?></span>

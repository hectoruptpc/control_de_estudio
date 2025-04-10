<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');


$titulopag = "Comentarios";
include('../funciones/functions.php');



function usua_comentarios(){
  global $db, $limit_end;
  //$limit_end = 1;
  //$init = "";

if (isset($_GET['p']))
$ini=$_GET['p'];
else
$ini=1;

$init = ($ini-1) * $limit_end;

  $count_query="SELECT COUNT(*) FROM comentario WHERE comentario.visible = 1";

  $query = "SELECT *, comentario.id AS idrow, users.nombre AS 'nombre'
  FROM comentario
  INNER JOIN users ON (comentario.user=users.idusuario)
  WHERE comentario.visible = 1
  ORDER BY fecha DESC
  LIMIT $init, $limit_end";

  //$result = mysqli_query($db,$query);
$result_count = mysqli_query($db, $count_query);


    $num = $db->query($count_query);
    $x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);
echo '<div class="d-none d-sm-none d-md-block">';
    pag($ini, $limit_end, $total);
echo "</div>";
echo '<div class="d-block d-sm-block d-md-none">';
pag_test($ini, $limit_end, $total);
echo "</div>";
    $admin_comentarios = '<div class="table-responsive">';
    $admin_comentarios .= '<table id="tabla1" class="table table-bordered table-hover stacktable">
      <thead>
       <tr>

       <th>Fecha del Comentario</th>
       <th>Nombre</th>
        <th>Comentario </th>

       </tr>
       </thead>
       <tbody>';

       $c = $db->query($query);
       while($row = $c->fetch_array(MYSQLI_ASSOC))
        {
        $date = date_create($row['fecha']);
        $fecha = date_format($date, 'd-m-Y');
        $fecha_comentario = $fecha;
        $comentario = $row['comentario'];
        //$id = $row['idrow'];
        $nombre = $row['nombre'];
        $user = $row['user'];



    $admin_comentarios .= '<tr>';
    $admin_comentarios .= '<td>'.$fecha_comentario.'</td>
                          <td>'.$nombre.'</td>
                          <td>'. strtoupper ($comentario) .'</td>
                          </tr>';
        }
        $admin_comentarios .= '</tbody></table></div>';




echo $admin_comentarios;
echo '<div class="d-none d-sm-none d-md-block">';
    pag($ini, $limit_end, $total);
echo "</div>";
echo '<div class="d-block d-sm-block d-md-none">';
pag_test($ini, $limit_end, $total);
echo "</div>";
}


?>
<?php include("includes/head.php"); ?>
<div class="container">

<?php
$a = 'comentario';
mostrar_alert($a);
 ?>
  <div class="row">
      <div class="col-lg-10 col-sm-12">Su comentario es importante para nosotros, por ello le invitamos a dejarnos un comentario de caracter publico sobre su experiencia en el uso de esta plataforma, puede enviarnos su comentario publico aqui:</div>
      <div class="col-lg-2" col-sm-12><?php enviar_comentario(); ?></div>
  </div>

<hr>


<?php
usua_comentarios();
?>




</div>

<?php include("includes/footer.php"); ?>

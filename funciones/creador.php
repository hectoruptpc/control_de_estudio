<?php
// LISTA PEDIDOS
function lista_pedidos_creacion(){
    global $db, $username, $usua, $mes, $limit_end, $res;
  
    $url = basename($_SERVER ["PHP_SELF"]);
    
    if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower($_REQUEST['busqueda']);
  } else {
    $busqueda = "";
  }
  
  if (isset($_GET['pos']))
  $ini=$_GET['pos'];
  else
  $ini=1;
  
  $init = ($ini-1) * $limit_end;
  
  if (isAdmin()) {
      //SI ES ADMINISTRADOOR
  
        if (empty($busqueda)) {
        $busqueda = "";
  
        $count="SELECT COUNT(*) FROM pedidos INNER JOIN users ON pedidos.usuario=users.idusuario WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO')";
        
        $query = "SELECT pedidos.*, users.nombre, users.email, users.username FROM pedidos INNER JOIN users  ON pedidos.usuario=users.idusuario WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO') ORDER BY id DESC LIMIT $init, $limit_end";
  
      $result = mysqli_query($db, $query);
      $row =  mysqli_num_rows($result);
      $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Pedidos Pendientes.';
  
      } else {
        $count="SELECT COUNT(*) FROM pedidos
        INNER JOIN users ON pedidos.usuario=users.idusuario
        WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO') AND (usuario LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%' OR ci_nro_cuenta LIKE '%$busqueda%' OR banco_emisor LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR status_pedido LIKE '%$busqueda%')";
  
        $query = "SELECT pedidos.*, users.nombre, users.email, users.username FROM pedidos INNER JOIN users  ON pedidos.usuario=users.idusuario WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO') AND (usuario LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%' OR ci_nro_cuenta LIKE '%$busqueda%' OR banco_emisor LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR status_pedido LIKE '%$busqueda%') ORDER BY id DESC LIMIT $init, $limit_end";
  
          $result = mysqli_query($db, $query);
          $row =  mysqli_num_rows($result);
          $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No resultados con su criterio de busqueda';
      }
  } else {
    // ES USUARIO
    $count="SELECT COUNT(*) FROM pedidos WHERE usuario = '$usua'";
  
    $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' ORDER BY id DESC LIMIT $init, $limit_end";
    
    $result = mysqli_query($db, $query);
    $row =  mysqli_num_rows($result);
  
    $mensaje  = '<i class="fa fa-exclamation-triangle"></i> En este momento no hay datos que Mostrar del usuario ' .ucwords(strtolower($_SESSION['user']['nombre']));
  
  }
  
      if (!$row){
       
              echo '<div class="alert alert-danger" role="alert" >';
              echo '<h3>';
              echo $mensaje; 
              echo '</h3>';
              echo '</div>';
                  
              } else
              {
                  $num = $db->query($count);
                  $x = $num->fetch_array();
                  $total = ceil($x[0]/$limit_end);
  
                  if (isAdmin()){
                 // ES ADMIN
  
                 $suma="SELECT sum(monto) AS 'total', SUM(CASE WHEN status_pedido = 'ESPERANDO' THEN 1 ELSE 0 END) AS 'esperando_aprobar', SUM(CASE WHEN status_pedido = 'ESPERANDO' THEN monto ELSE 0 END) AS 'esperando_aprobar_monto', SUM(CASE WHEN status_pedido = 'APROBADO' THEN 1 ELSE 0 END) AS 'esperando_entregar', SUM(CASE WHEN status_pedido = 'APROBADO' THEN monto ELSE 0 END) AS 'esperando_entregar_monto' FROM pedidos WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO') ";
                 $result_suma = mysqli_query($db, $suma);
                 
                 while ($row_suma = mysqli_fetch_assoc($result_suma)) 
                 {
                   echo 'Cantidad Total: '.$row_suma['total'] . '<br>';
                   echo 'Esperando Aprobacion: '.$row_suma['esperando_aprobar'] . ' Total: '.$row_suma['esperando_aprobar_monto'] . ' BsS<br>';
                   echo 'Cantidad Esperando Entrega: '.$row_suma['esperando_entregar'] . ' Total: '.$row_suma['esperando_entregar_monto'] . ' BsS<br>';
                 }
                  echo '<div class="table-responsive">';
          
                  echo '<table id="tabla1" class="table table-bordered table-hover table-sm ">
                  <thead>
                   <tr>
                   <th>id</th>
                   <th>Id de Usuario</th>
                   <th>Nombre</th>
                   <th>Email</th>
                    <th>Fecha de Transf</th>
                    <th>Monto</th>
                    <th>Nro Transf / Cedula</th>
                    <th>Emisor / Receptor</th>
                    <th>Accion</th>
                   </tr>
                   </thead>
                   <tbody>';
  
                  
          
                   $c = $db->query($query);
                   while($row = $c->fetch_array(MYSQLI_ASSOC))
                    {
  
                  $date = date_create($row['fecha_transf']);
                  $fecha = date_format($date, 'd-m-Y');
                  $fecha_pedido = $fecha;
  
                  $rowUser = $row['usuario'];
                  $rowid = $row['id'];
                  $rowNombre = $row['nombre'];
                  $rowEmail = $row['email'];
                  $status_pedido = $row['status_pedido'];
                 
                  analisis_pedidos_por_cliente($rowUser);
                  $resumen = $res;
  
  if ($status_pedido == "ESPERANDO") {
      
    
  
                  $aprobar = '<form autocomplete="off" class="was-validated" method="post" action= "pedidos.php?id='.$rowid.'&user='.$rowUser.'"><button type="submit" class="btn btn-success  btn-block" name="aprobar_pago_pedido_btn">Aprobar <i class="fa fa-check-circle"></i></button> </form>';
  
                   //$rechazar = '<form autocomplete="off" class="was-validated" method="post" action= "rechazar.php?id='.$rowid.'&user='.$rowUser.'&asunto=pedido"><button type="submit" class="btn btn-danger btn-sm  btn-block" name="rechazar_pago_pedido_btn">Rechazar  <i class="fa fa-times-circle"></i></button> </form>';
  
                   //$aprobar = '<a href= "pedidos.php?id='.$rowid.'&user='.$rowUser.'" type="submit" class="btn btn-success btn-sm  btn-block" name="aprobar_pago_pedido_btn">Aprobar  <i class="fa fa-check-circle"></i></a>';
  
                   $rechazar = '<a href= "rechazar.php?id='.$rowid.'&user='.$rowUser.'&asunto=pedido" type="submit" class="btn btn-danger btn-sm  btn-block" name="rechazar_pago_pedido_btn">Rechazar  <i class="fa fa-times-circle"></i></a>';
  
                  
  
                   $link = '<div class="btn-group-vertical" role="group" >'. $aprobar . $rechazar . $resumen. '</div>';
                    
  }
  else if ($status_pedido == "APROBADO") {
  
      $enviar = '<form autocomplete="off" class="was-validated" method="post" action= "preparar_entrega_pedido.php?id='.$rowid.'&user='.$rowUser.'"><button type="submit" class="btn btn-warning" name="enviar_pedido_btn">Enviar Pedido  <i class="fa fa-paper-plane"></i></button> </form>';

      
  
      $link = '<div class="btn-group-vertical" role="group" >'. $enviar . $resumen. '</div>';


      
  }
                   
           echo '<tr>';
           echo '<td>'.$row['id'] .'</td>
           <td>'.$rowUser .'</td>
           <td>'. $rowNombre .'</td>
           <td>'. $rowEmail .'</td>
                 <td>'.$fecha_pedido .'</td>
                 <td>'.$row['monto'].' BsS</td>
                 <td>'.$row['nro_transf'] .' / '.$row['ci_nro_cuenta'] .'</td>
                 <td>'.$row['banco_emisor'] .' / '.$row['banco_destino'] .'</td>
                 <td>'.$link .'</td>
                    </tr>';
                   }
                    echo '</tbody></table>';
                  }
                else {
  
      //SI ES USUARIO
  
          echo '<table id="tabla1" class="table table-bordered table-hover stacktable">
          <thead>
           <tr>
            <th>Fecha de su Pedido</th>
            <th>Monto</th>
            <th>Banco Emisor / Nro. de Transferencia</th>
            <th width ="25%">Status de Su Pedido</th>
           </tr>
            </thead>
           <tbody>';
  
           $c = $db->query($query);
           while($row = $c->fetch_array(MYSQLI_ASSOC))
            {
           
            $date = date_create($row['fecha_pedido']);
            $fecha = date_format($date, 'd-m-Y');
            $fecha_pedido = $fecha;
            $abab = $row['status_pedido'];
            //$motivo = strip_tags($row['motivo_rechazo']);
            $motivo = $row['motivo_rechazo'];
            if ($abab == "ESPERANDO"){
              $stdp = '<div class="w-70 mx-auto alert alert-warning" role="alert" data-toggle="popover" title="EN ESPERA" data-content="Su pago aun no ha sido conformado.">
        EN ESPERA  <i class="fa fa-clock"></i>
      </div>';
            } 
          else  if ($abab == "APROBADO"){
        $stdp = '<div class="w-70 mx-auto alert alert-success" role="alert" data-toggle="popover" title="CONFORMADO" data-content="Este mensaje es indicativo de que su pago ya fue aprobado, queda a la espera de la entrega de su pedido.">
         CONFORMADO <i class="fa fa-clock"></i>
      </div>';
            }
            else if ($abab == "RECHAZADO") {
  
              $stdp = '<div class="text-center w-70 mx-auto alert alert-danger" role="alert" data-html="true" data-toggle="popover" title="RECHAZADO" data-content="Su pago fue rechazado, por el siguiente motivo:<br> '.$motivo.'.">
               RECHAZADO  <i class="fa fa-exclamation-triangle"></i>
             </div>';
        
             }
            else {
              $id = $row['id'];
              $stdp = '<form autocomplete="off" class="was-validated" method="post" action= "ver_pedido.php">
              <input type="hidden" name="id_pedido" value="'.$id.'">
              <button data-html="true" data-toggle="popover" title="SECCION DE DESCARGAS" data-content="Aca podra acceder a sus Tarjetas UN1CAS." type="submit" class="btn btn-success" name="ver_pedido_btn">DESCARGAR  <i class="fa fa-file-download"></i></button> </form>';
          // $stdp = 'DESCARGAR <i class="fa fa-paperclip"></i>';
            }
   echo '<tr>';
   echo '<td>'.$fecha_pedido .'</td>
             <td>'.$row['monto'].' BsS</td>
             <td>'.$row['banco_emisor'] .' / '.$row['nro_transf'] .'</td>
             <td class="text-center">'.$stdp .'</td>
            </tr>';
           } 
            echo '</tbody></table>';
          }
  
      
  /* numeración de registros [importante]*/
  // echo "<div class='pagination'>";
  echo '<nav aria-label="Page navigation example">';
  echo '<ul class="pagination">';
  /****************************************/
  if(($ini - 1) == 0)
  {
  echo "<li class='page-item'><a class='page-link' href='#'>&laquo;</a></li>";
  }
  else
  {
  echo "<li class='page-item'><a class='page-link' href='$url?pos=".($ini-1)."'><b>&laquo;</b></a></li>";
  }
  /****************************************/
  for($k=1; $k <= $total; $k++)
  {
  if($ini == $k)
  {
    echo "<li class='page-item'><a class='page-link' href='#'><b>".$k."</b></a></li>";
  }
  else
  {
    echo "<li class='page-item'><a class='page-link' href='$url?pos=$k'>".$k."</a></li>";
  }
  }
  /****************************************/
  if($ini == $total)
  {
  echo "<li class='page-item'><a class='page-link' href='#'>&raquo;</a></li>";
  }
  else
  {
  echo "<li class='page-item'><a class='page-link' href='$url?pos=".($ini+1)."'><b>&raquo;</b></a></li>";
  }
  /*******************END*******************/
  echo "</ul>";
  // echo "</div>";
  echo '</nav>';
  }
  }

function ver_mensajeria(){

  global $db, $limit_end;

  $url = basename($_SERVER ["PHP_SELF"]);

if (isset($_GET['msn']))
  $ini=$_GET['msn'];
else
  $ini=1;
      $init = ($ini-1) * $limit_end;

      // SI ES ADMIN
      
      if (isAdmin()) {

          $count_mensajeria="SELECT COUNT(*) FROM mensajes";
          $query_mensajeria = "SELECT * FROM mensajes ORDER BY id DESC LIMIT $init, $limit_end";

        $result_mensajeria = mysqli_query($db, $query_mensajeria);
          $row_mensajeria =  mysqli_num_rows($result_mensajeria);
          
          $mensaje  = 'No hay mensajes que Mostrar';
         
      } else {

          $count_mensajeria="SELECT COUNT(*) FROM mensajes";
          $query_mensajeria = "SELECT * FROM mensajes ORDER BY id DESC LIMIT $init, $limit_end";
        $result_mensajeria = mysqli_query($db, $query_mensajeria);
          $row_mensajeria =  mysqli_num_rows($result_mensajeria);
          
          $mensaje  = 'No hay mensajes que Mostrar';

         
      }

/* querys */


if (!$row_mensajeria){

echo '<div class="alert alert-danger" role="alert" >';
echo '<h3>';
echo $mensaje; 
//unset($_SESSION['successmes']);
echo '</h3>';
echo '</div>';
  
} else {
  $num = $db->query($count_mensajeria);
  $x = $num->fetch_array();
      $total = ceil($x[0]/$limit_end);
      
      if (isAdmin()){
  
         
echo '<div class="table-responsive">';
  echo '<table id="tabla1" class="table table-bordered table-hover stacktable">
  <thead>
   <tr>
   <th>ID</th> 
   <th>Fecha de Mensaje</th>
    <th>Asunto </th>
    <th>Contenido</th>
    <th>Accion</th>
   </tr>
   </thead>
   <tbody>';

   $c = $db->query($query_mensajeria);
   while($row_mensajeria = $c->fetch_array(MYSQLI_ASSOC))
    {
    $date = date_create($row_mensajeria['fecha_mensaje']);
    $fecha = date_format($date, 'd-m-Y');
    $fecha_mensaje = $fecha;
    $asunto = $row_mensajeria['asunto'];
$rowid = $row_mensajeria['id'];
    $contenido = $row_mensajeria['contenido'];
    $rowid = $row_mensajeria['id'];
    $admin = $row_mensajeria['admin'];

$boton_editar = '<a class="btn btn-outline-dark btn-sm" href="editar_mensajeria.php?id='.$rowid.'" data-toggle="popover" title=EDITAR CONTENIDO" data-content="Editar este contenido.">
    Editar
    </a>';

$accion = '<div class="btn-group" >'. $boton_editar. '</div>';
   
    $consultar_nombre = "SELECT nombre FROM users WHERE id = '$admin'";
    $resultado_consultar_nombre=mysqli_query($db,$consultar_nombre);
    $rcn = mysqli_fetch_assoc($resultado_consultar_nombre);


echo '<tr>';
echo '<td>'.$rowid.'</td>
     <td>'.$fecha_mensaje.'</td>
     <td>'.$asunto.'</td>
     <td>'.$contenido .'</td>
     <td>'.$accion.'</td>
    </tr>';
    } 
    echo '</tbody></table>';


      }
      else
      // SI ES USER NO ES ADMIN
      {
          
     
     
      echo '<div class="accordion" id="accordionExample">';
      
           $c = $db->query($query_mensajeria);
           while($row_mensajeria = $c->fetch_array(MYSQLI_ASSOC))
            {
            $date = date_create($row_mensajeria['fecha_mensaje']);
            $fecha = date_format($date, 'd-m-Y');
            $fecha_mensaje = $fecha;
            $asunto = $row_mensajeria['asunto'];
            $contenido = $row_mensajeria['contenido'];
            $rowid = $row_mensajeria['id'];
            $admin = $row_mensajeria['admin'];
      
     $a = '

    <div class="card">
      <div class="card-header" id="headingOne'.$rowid.'">
        <h5 class="row mb-0">
          <button  title="EDITAR CONTENIDO" class="btn btn-link collapsed col-12" type="button" data-toggle="collapse" data-target="#collapseOne'.$rowid.'" aria-expanded="true" aria-controls="collapseOne'.$rowid.'">

          <div class="row no-gutters">
  <div class="d-flex justify-content-start col-sm-8">'.$asunto.'</div>
  <div class="d-flex justify-content-end col-sm-4"><span class="justify-content-end badge badge-pill badge-info">Mensaje General</span></div>
</div>
          
             
          </button>
        </h5>
      </div>
  
      <div id="collapseOne'.$rowid.'" class="collapse" aria-labelledby="headingOne'.$rowid.'" data-parent="#accordionExample">
        <div class="card-body">
        '.$contenido.'
        </div>
      </div>
    </div>
   
  ';
  echo $a;
            }
            echo '</div>';

      }



     /* numeración de registros [importante]*/
// echo "<div class='pagination'>";
echo '<nav aria-label="Page navigation example">';
echo '<ul class="pagination">';
/****************************************/
if(($ini - 1) == 0)
{
echo "<li class='page-item'><a class='page-link' href='#'>&laquo;</a></li>";
}
else
{
echo "<li class='page-item'><a class='page-link' href='$url?msn=".($ini-1)."'><b>&laquo;</b></a></li>";
}
/****************************************/
for($k=1; $k <= $total; $k++)
{
if($ini == $k)
{
echo "<li class='page-item'><a class='page-link' href='#'><b>".$k."</b></a></li>";
}
else
{
echo "<li class='page-item'><a class='page-link' href='$url?msn=$k'>".$k."</a></li>";
}
}
/****************************************/
if($ini == $total)
{
echo "<li class='page-item'><a class='page-link' href='#'>&raquo;</a></li>";
}
else
{
echo "<li class='page-item'><a class='page-link' href='$url?msn=".($ini+1)."'><b>&raquo;</b></a></li>";
}
/*******************END*******************/
echo "</ul>";
// echo "</div>";
echo '</nav>';
}
  
}








?>
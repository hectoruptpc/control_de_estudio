<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Test 2";
    include('../funciones/functions.php');
    include('../funciones/creador.php');

?>
<!DOCTYPE html>
<html lang="es">


  <?php include("includes/head.php"); ?>

    <!-- Page Content -->
    <div class="container">



      <p>Este es una web de creacion 2.</p>

      <?php
      $id_usuario = 1;
      $sentencia = $db->prepare("SELECT nombre, idusuario FROM users WHERE id = ?");
      $sentencia->bind_param("i", $id_usuario );
      $sentencia->execute();
      $sentencia->bind_result($nombre, $usua);
      $sentencia->fetch();
      echo $nombre . ' ' . $usua;
      $sentencia->close();


      //$login = $db->prepare("SELECT * FROM users WHERE (username='?' OR email='?') AND password='?' LIMIT 1");
      //$login->bind_param("sss",$username, $username, $password);
      //$login->execute();

echo "<hr>";
$status = 0;
$n = 0;
      $stmt = $db->prepare("SELECT * FROM users WHERE status = ?");
      $stmt->bind_param("i", $status);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows === 0) exit('Ha ocurrido un error inesperado, intente mas tarde.');
      while($row = $result->fetch_assoc()) {
      $id[] = $row['id'];
      $name[] = $row['nombre'];
      $idusuario[] = $row['idusuario'];



      var_dump($id[$n]);
      var_dump($name[$n]);
      $n ++;
      }
      $nr = $result->num_rows;
      echo $nr;

        ?>




        <form class="was-validated" method="post" action="crear_password.php?id=<?php echo $idUser; ?>&control=<?php echo $control; ?>" autocomplete="off" name="crear_password">
      <!-- notification message -->
      <?php if (isset($_SESSION['msg_crear'])) : ?>
          <div class="alert alert-danger" role="alert"" >
            <h3>
              <?php
                echo $_SESSION['msg_crear'];
                unset($_SESSION['msg_crear']);
              ?>
            </h3>
          </div>
      <?php endif ?>

      <?php echo display_error();
      echo "<br>";
      ?>

      <?php if ((count($errors) == 0) || (count($error) >0)) :
      echo display_error2(); ?>

      <div class="form-group">
        <label for="password_1">Password o Contraseña</label>
        <input pattern="[a-zA-Z0-9.+_-]{6,10}" title="Debe utilizar combiaciones de Letras, Numeros y Puede utilizar los caracteres especiales: . + _ - Puede usar un minimo de 6 caracteres y un maximo de 10"
      type="password" class="form-control" id="password_1" placeholder="Password" name="password_1" required>
        <div class="invalid-feedback">Ingrese su Password o Contraseña. Por su seguridad Recomendamos que Utilice una contraseña conformada por combiaciones de Letras Pueden ser Mayusculas o Minusculas y Numeros. Su contraseña debe tener minimo 6 caracteres y un maximo de 10 caracteres. Puede utilizar los caracteres especiales: . + _ - </div>
      </div>

      <div class="form-group" >
        <label for="password_2">Repita Password o Contraseña:</label>
        <input name="password_2" onkeyup="comprobar_password();" pattern="[a-zA-Z0-9.+_-]{6,10}" title="Recomendamos que Utilice una contraseña conformada por combiaciones de Letras y Numeros y use un minimo de 6 caracteres y un maximo de 10"
      type="password" class="form-control" id="password_2" placeholder="Repita Password"  required>
      <div class="invalid-feedback">Repita su Password o Contraseña.</div>
      </div>

      <input type="hidden" name="idusuario" value="<?php echo $idUser; ?>">
      <input type="hidden" name="email" value="<?php echo $rowEmail;  ?>">
      <input type="hidden" name="control" value="<?php echo $control;  ?>">
      <input type="hidden" name="nombre" value="<?php echo $rowNombre;  ?>">
      <input type="hidden" name="username" value="<?php echo $username;  ?>">

      <button type="submit" class="btn btn-danger btn-sm" name="crear_password_btn" disabled="disabled"><i class="fa fa-key"></i> Crear Password</button>
      <div id="mensaje2"></div>
      <?php endif ?>
      </form>

<script type="text/javascript" >
function comprobar() {
var p1 = document.formulario.p1.value;
var p2 = document.formulario.p2.value;

if (p1 != p2) {
document.getElementById("mensaje").innerHTML = "Las pass no coinciden";
} else {
document.getElementById("mensaje").innerHTML = "";
}
}

function comprobar_password() {
var p1 = document.crear_password.password_1.value;
var p2 = document.crear_password.password_2.value;

if (p1 != p2) {
document.getElementById("mensaje2").innerHTML = '<div class="alert alert-warning" role="alert">Ambas contraseñas deben ser iguales</div>';
$('.btn').prop('disabled', true);
} else {
document.getElementById("mensaje2").innerHTML = "";
$('.btn').prop('disabled', false);
}
}
</script>






<form name="formulario">
<input type="password" name="p1"/> <div id="mensaje"></div>
<input type="password" name="p2" onkeyup="comprobar();"/>
</form>


    </div>
    <?php include("includes/footer.php"); ?>

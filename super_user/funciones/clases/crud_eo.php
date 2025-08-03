<?php
include('../../../../funciones/functions.php');

class crud{

  // MANIPULACION DE USUARIOS

  public function obtenDatosUsuarios($id){
  $obj= new conectar();
  $conexion=$obj->conexion();

  $sql="SELECT
      id,
      idusuario,
      nombre,
      email,
      tlf,
      cel,
      direccion,
      ciudad,
      estado,
      municipio,
      parroquia,
      status,
      motivo_bloqueo,
      monto_a_favor,
      disp_a_favor
      from users
      where
      id='$id'";
  $result=mysqli_query($conexion,$sql);
  $ver=mysqli_fetch_assoc($result);
  $datos=array(
    'id' => $ver['id'],
    'idusuario' => $ver['idusuario'],
    'nombre' => $ver['nombre'],
    'email' => $ver['email'],
    'tlf' => $ver['tlf'],
    'cel' => $ver['cel'],
    'direccion' => $ver['direccion'],
    'ciudad' => $ver['ciudad'],
    'estado' => $ver['estado'],
    'municipio' => $ver['municipio'],
    'parroquia' => $ver['parroquia'],
    'status' => $ver['status'],
    'motivo_bloqueo' => $ver['motivo_bloqueo'],
    'monto_a_favor' => $ver['monto_a_favor'],
    'disp_a_favor' => $ver['disp_a_favor']
    );
  return $datos;
}


public function actualizarUsuario($datos){
  $obj= new conectar();
  $conexion=$obj->conexion();
  $nombre = utf8_decode($datos[2]);
  $direccion = utf8_decode($datos[6]);
  $ciudad = utf8_decode($datos[7]);
  $estado = utf8_decode($datos[8]);
  $municipio = utf8_decode($datos[9]);
  $parroquia = utf8_decode($datos[10]);
  $status = utf8_decode($datos[11]);
  $motivo_bloqueo = utf8_decode($datos[12]);
  $monto_a_favor = utf8_decode($datos[13]);
  $disp_a_favor = utf8_decode($datos[14]);

  $sql="UPDATE `users`
        SET
          `users`.`idusuario` = '$datos[1]',
          `users`.`nombre` = '$nombre',
          `users`.`email` = '$datos[3]',
          `users`.`tlf` = '$datos[4]',
          `users`.`cel` = '$datos[5]',
          `users`.`direccion` = '$direccion',
          `users`.`ciudad` = '$ciudad',
          `users`.`estado` = '$estado',
          `users`.`municipio` = '$municipio',
          `users`.`parroquia` = '$parroquia',
          `users`.`status` = '$status',
          `users`.`motivo_bloqueo` = '$motivo_bloqueo',
          `users`.`monto_a_favor` = '$monto_a_favor',
          `users`.`disp_a_favor` = '$disp_a_favor'
        WHERE
          `users`.`id` = '$datos[0]'";

          //$asunto = "Actualizacion de Usuario";
          //$cuerpo = '<p>Hola '.$nombre.' <br><br> Por alguna razon hemos tenido que modificar tu perfil dentro de la plataforma, normalmente se debe a que al momento de ingresar tus datos en el formulario de solicitud de afiliacion algunos datos como tu correo lo escribistes con errores, o colocastes datos incompletos y los mismos ya fueron corregidos, te invitamos a utilizar tus credenciales:</p><p style="text-align: justify;"><strong>CREDENCIALES DE ACCESO:</strong></p><p style="text-align: center;"><br> <span style="background-color: #70FF70; color: #000000; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;">Correo Registrado: <strong>'.$email.'</strong><br>Su Usuario es: <strong>'.$idusuario.'</strong></span></p><p>&nbsp;</p><hr /><p>Ahora puedes acceder y crear tu contrase&ntilde;a desde el modulo <a href="https://virtual.jesuministrosymas.com.ve/u/recuperar_password.php" target="_blank"> OLVIDO CONTRASE&Ntilde;A:</a></p><p style="text-align: center;"><br> <span style="background-color: #DE0000; color: #fff; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;"><strong><a href="https://virtual.jesuministrosymas.com.ve/u/recuperar_password.php" target="_blank">RECUPERA TU CLAVE DE ACCESO AQUI</a></strong></span></p>';

          //enviarEmail($email, $nombre, $asunto, $cuerpo);


  return mysqli_query($conexion,$sql);

}



  // MANIPULACION DE RECARGAS EN ESPERA

    public function obtenDatos($id){
    $obj= new conectar();
    $conexion=$obj->conexion();

    $sql="SELECT
        id,
        user,
        operador,
        tipo,
        nro,
        monto,
        fecha,
        status,
        relacion,
        confirmacion,
        sin_plan
        from recargar
        where
        id='$id'";
    $result=mysqli_query($conexion,$sql);
    $ver=mysqli_fetch_row($result);
    $datos=array(
      'id' => $ver[0],
      'user' => $ver[1],
      'operador' => $ver[2],
      'tipo' => $ver[3],
      'nro' => $ver[4],
      'monto' => $ver[5],
      'fecha' => $ver[6],
      'status' => $ver[7],
      'relacion' => $ver[8],
      'confirmacion' => $ver[9],
      'sin_plan' => $ver[10]
      );
    return $datos;
  }

  public function actualizar($datos){
    $obj= new conectar();
    $conexion=$obj->conexion();

// SELECCIONAR DATOS DE NOMBRE Y Correo
            $sql_mail="SELECT
                id, email, nombre
                FROM users
                WHERE
                idusuario='$datos[1]'";
            $result_mail=mysqli_query($conexion,$sql_mail);
            $mail=mysqli_fetch_assoc($result_mail);
            //return $email;
            $id_usua =$mail['id'];
            $email = $mail['email'];
            $nombre = $mail['nombre'];
            $monto = number_format($datos[5],2,',','.') .' Bs';

// SI ES DEVOLUCION
            if ($datos[9] == 'DEVOLUCION') {

              if ($datos[10] == 1) {
                // code...
                $datos[5] = $datos[5] + $datos[5]*0.30;
              }



              $sql="UPDATE `recargar`
                    SET
                      `recargar`.`user` = '$datos[1]',
                      `recargar`.`operador` = '$datos[2]',
                      `recargar`.`tipo` = '$datos[3]',
                      `recargar`.`nro` = '$datos[4]',
                      `recargar`.`monto` = '$datos[5]',
                      `recargar`.`fecha` = '$datos[6]',
                      `recargar`.`status` = 4,
                      `recargar`.`relacion` = '$datos[8]',
                      `recargar`.`confirmacion` = '$datos[9]',
                      `recargar`.`sin_plan` = '$datos[10]'
                    WHERE
                      `recargar`.`id` = '$datos[0]'";

              // code...
              $asunto = "Operadora $datos[2] ha efectuado una Devolucion";
              $cuerpo = "Hola $nombre <br>Una recarga que estaba pendiente ha sido <b>Rechazada</b> por la operadora <b>$datos[2]</b>.<br> No se preocupe el dinero ha regresado a tu Billetera Virtual.<br><br><b>DETALLE:</b>
              Operador = $datos[2] <br>
              Numero = $datos[4] <br>
              Monto = $monto <br>
              Status Actual = Devuelto. Este status indica que el numero que ha intentado recargar la operadora no puede recargarlo, generalmente este error ocurre cuando: <br>- Un numero no es Prepagado.<br>- Esta suspendido.<br>- Posee algun problema dentro de la plataforma.<br> Si es ese el caso el usuario debe ponerse en contacto con <b>$datos[2]</b> para solucionar el inconveniente con su linea <b>$datos[4]</b> <br>";

              $descripcion = 'DEVOLUCION';


              $sqlBilletera = "INSERT INTO billetera (id, id_usuario, monto, descripcion, id_descripcion, fecha, status) VALUES (null, '$id_usua','$datos[5]','$descripcion','$datos[8]',NOW(),1)";
              mysqli_query($conexion, $sqlBilletera);

            } else {

              $sql="UPDATE `recargar`
                    SET
                      `recargar`.`user` = '$datos[1]',
                      `recargar`.`operador` = '$datos[2]',
                      `recargar`.`tipo` = '$datos[3]',
                      `recargar`.`nro` = '$datos[4]',
                      `recargar`.`monto` = '$datos[5]',
                      `recargar`.`fecha` = '$datos[6]',
                      `recargar`.`status` = '$datos[7]',
                      `recargar`.`relacion` = '$datos[8]',
                      `recargar`.`confirmacion` = '$datos[9]',
                      `recargar`.`sin_plan` = '$datos[10]'
                    WHERE
                      `recargar`.`id` = '$datos[0]'";

              // SI EL NUMERO DE CONFIRMACION ES OTRO
            $asunto = "Recarga Procesada por La Operadora";
            $cuerpo = "Hola $nombre <br>Una recarga que estaba pendiente se ha procesado con exito. <br>
            Operador = $datos[2] <br>
            Numero = $datos[4] <br>
            Monto = $monto <br>
            Confirmacion = $datos[9] <br>";

    }

    enviarEmail($email, $nombre, $asunto, $cuerpo);

    return mysqli_query($conexion,$sql);
  }

}


 ?>

 <?php
 //formulario de registro y editar administrador
include("conexion.php");
error_reporting(0);
session_start();

$id='';
$titulo='';
$urlForm='';
$boton='';
$clave='';

if (isset($_GET['id'])) {
    $id=trim($_GET['id']);
    $titulo='Editar Usuario';
    $urlForm='editarAdmin.php';
    $boton='Editar';
    $clave='Nueva clave';
}else{

 $titulo='Registro de Usuario';
 $urlForm='registroAdmin.php';
 $boton='Registrar';
 $clave='Clave';
}

$result = $conn->query("SELECT * FROM user WHERE id = '$id' ");
$mostrar = $result -> fetch_assoc();


?>


<!--formulario para el registro de los administradores-->
    <main>  
        <section class="registro"> 

            <div class="form">

            <h2><?php echo $titulo; ?></h2>  
            <form url="<?php echo $urlForm; ?>" id="form" action="index.php?formAdmin=1" method="POST"> 
                <!--id del alministrador-->
                <input type="hidden" name="id" id="id" value="<?php echo $mostrar['id']; ?>"> 

                <!--tipo de administrador--> 
                <input type="hidden" name="tipo" value="2">
                
                <div class="campo inputLg input-group mt-1">  
                    <label for="nom">Correo electrónico:</label>  
                    <input class="input" value="<?php echo $mostrar['nom']; ?>" type="email" id="email" name="email" required>  
                </div>  
                <div class="campo inputLg input-group mt-1">  
                    <label for="pass"><?php echo $clave; ?>:</label>  
                    <input class="input" type="password" id="pass" name="clave" required> 
                    <button for="pass" id="boton" type="button"><i id="ojo" class="icon icon-eye-blocked"></i></button> 
                </div>  
               

                 <div class="campo btn">  
                    <span id="noVilid" class="noValid">¡Los datos ingresados están repetidos!</span>
                    <button id="btn" name="btn" type="submit"><?php echo $boton; ?></button>  
                </div> 

  
            </form> 

            </div>  
        </section>  
    </main>  
<script src="js/sweetAlert.js"></script>
<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/registroAdmin.js"></script>
<script src="js/verClave.js"></script>
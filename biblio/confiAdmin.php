 <?php
 //formulario de configuración de usuario administrador
include("conexion.php");
error_reporting(0);
session_start();


 $titulo='Configuración de Administrador';
 $urlForm='editarUser.php';
 $boton='Configurar';
 $clave='Clave';




?>


<!--formulario para configurar usuario administrador-->
    <main>  
        <section class="registro"> 

            <div class="form">

            <h2><?php echo $titulo; ?></h2>  
            <form url="<?php echo $urlForm; ?>" id="form" action="index.php?formAdmin=1" method="POST"> 
                <!--esto es solo para indicar en el javaScript que para configurar el usuario-->
                 <input type="hidden" name="conf" id="conf" >
                <!--id del alministrador-->

                <input type="hidden" name="id" id="id" value="<?php echo $_SESSION['idAdmin']; ?>"> 

                <!--tipo de administrador--> 
                <input type="hidden" name="tipo" value="<?php $_SESSION['tipoUser'] ?>">
                
                <div class="campo inputLg input-group mt-1">  
                    <label for="nom">Correo electrónico:</label>  
                    <input class="input" value="<?php echo $_SESSION['nomAdmin']; ?>" type="email" id="email" name="email" required>  
                </div>  
                <div class="campo inputLg input-group mt-1">  
                    <label for="pass"><?php echo $clave; ?>:</label>  
                    <input class="input" value="<?php echo $_SESSION['passAdmin']; ?>" type="password" id="pass" name="clave" required> 
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
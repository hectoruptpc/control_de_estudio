 <?php
 //formulario de registro y editar de carrera
include("conexion.php");
error_reporting(0);
session_start();

$id='';
$titulo='';
$urlForm='';
$boton='';

if (isset($_GET['id'])) {
    $id=trim($_GET['id']);
    $titulo='Editar Carrera';
    $urlForm='editarCarr.php';
    $boton='Editar';
}else{

 $titulo='Registro de Carrera';
 $urlForm='registroCarr.php';
 $boton='Registrar';
}

$result = $conn->query("SELECT * FROM carrera WHERE id = '$id' ");
$mostrar = $result -> fetch_assoc();


?>


<!--formulario para el registro de las carreras-->
    <main>  
        <section class="registro"> 

            <div class="form">

            <h2><?php echo $titulo; ?></h2>  
            <form url="<?php echo $urlForm; ?>" id="form" action="#" method="POST"> 

                <input type="hidden" name="id" id="id" value="<?php echo $mostrar['id']; ?>"> 
                
                <div class="campo">  
                    <label for="nom">Nombre de la carrera:</label>  
                    <input value="<?php echo $mostrar['nom']; ?>" type="text" id="nom" name="nom" required>  
                </div>  
                <div class="campo">  
                    <label for="codigo">Código de la carrera:</label>  
                    <input value="<?php echo $mostrar['code']; ?>" type="number" id="codigo" name="codigo" required>  
                </div>


                <div class="campo"> 
                    <label for="titulo">Nivel de grado:</label>
                    <select class="select-css" id="titulo" name="titulo" required title="Tipo de titulo">
                    <option value="">Selecione nivel de grado</option>
                    <!--el contenido de este elemento lo obtenemos de 'tipoTitulo.php'-->
                    <?php

                      $titulos =  array('','Ingeneria','Licenciatura');
                     for ($t=1; $t <= count($titulos)-1; $t++) { ?>
                        <option
                         <?php if ($mostrar['titulo'] == $t) { ?>
                                selected
                        <?php } ?>
                          value="<?php echo $t; ?>"><?php echo $titulos[$t]; ?></option>
                    <?php } ?>
                        
                    </select> 
                      
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
<script src="js/registroCarr.js"></script>
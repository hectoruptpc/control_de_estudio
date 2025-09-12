 <?php
 //formulario de registro y editar graduados
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
    $titulo='Editar Graduado';
    $urlForm='editarGraduado.php';
    $boton='Editar';
    $clave='Nueva clave';
}else{

 $titulo='Registro de Graduado';
 $urlForm='registroGraduado.php';
 $boton='Registrar';
 $clave='Clave';
}

$result = $conn->query("SELECT * FROM alumno WHERE id = '$id' ");
$mostrar = $result -> fetch_assoc();


?>
<link rel="stylesheet" href="css/registro2.css">

<div class="container2">
    <main>  
        <section class="registro"> 

        <center>
             <div class="containerF">
            <header><h2><?php echo $titulo; ?></h2></header>
      <!--indicadore de paginas que contiene el formulario-->
      <div class="progress-bar2">
        <div class="step">
          <p class="pasos">Paso 1</p>
          <div class="bullet">
            <span>1</span>
            <span class="icon icon-checkmark"></span>
          </div>
          <div class="check fas fa-check"></div>
        </div>
        <div class="step">
          <p class="pasos">Paso 2</p>
          <div class="bullet">
            <span>2</span>
            <span class="icon icon-checkmark"></span>
          </div>
          <div class="check fas fa-check"></div>
        </div>
        <div class="step">
          <p class="pasos">Paso 3</p>
          <div class="bullet">
            <span>3</span>
            <span class="icon icon-checkmark"></span>
          </div>
          <div class="check fas fa-check"></div>
        </div>
        <div class="step">
          <p class="pasos">Fin</p>
          <div class="bullet">
            <span>4</span>
            <span class="icon icon-checkmark"></span>
          </div>
          <div class="check fas fa-check"></div>
        </div>
      </div>

             <div class="form-outer">
            <!--formulario con sus paginas-->

            <form url="<?php echo $urlForm; ?>" id="form" action="#" method="POST">

            <!--id del alministrador-->
                <input type="hidden" name="id" id="id" value="<?php echo $mostrar['id']; ?>"> 

                <div class="page slide-page">

                <div class="field">  
                    <label class="label" for="nom">Nombre del estudiante:</label>  
                    <input class="formInput" value="<?php echo $mostrar['nom']; ?>" type="text" id="nom" name="nom" required title="Nombre del estudiante">  
                </div>  
                <div class="field">  
                    <label class="label" for="apll">Apellido del estudiante:</label>  
                    <input class="formInput" value="<?php echo $mostrar['apll']; ?>" type="text" id="apll" name="apll" required title="Apellido del estudiante">  
                </div>

                <div class="field">

                <button type="button" class="firstNext next">Siguiente <i class="icon icon-arrow-right"></i></button>
              <input type="hidden" class="prev">
            </div>

            </div>

            <div class="page">

                <div class="field">  
                    <label class="label" for="tomo">Tomo:</label>  
                    <input class="formInput" class="formInput" value="<?php echo $mostrar['tomo']; ?>" type="number" id="tomo" name="tomo" required title="Tomo">  
                </div> 
              
                <div class="field">  
                    <label class="label" for="nFolio">Numero de folio (pagina) del estudiante:</label>  
                    <input class="formInput" class="formInput" value="<?php echo $mostrar['folio']; ?>" type="number" id="nFolio" name="nFolio" required title="Numero de folio">  
                </div> 

            <div class="field btns">
              <button type="button" class="prev-1 prev"><i class="icon icon-arrow-left"></i> Atr&#225;s</button>
              <button type="button" class="next-1 next">Siguiente <i class="icon icon-arrow-right"></i></button>
            </div>
                
            </div>

                 
                <div class="page">
                <div class="field">  
                    <label class="label" for="fechaG">Fecha de graduación:</label>  
                    <input class="formInput" value="<?php echo $mostrar['fechaGrado']; ?>" type="date" id="fechaG" name="fechaG" required title="Fecha de graduación">  
                </div> 

                <div class="field">  
                    <label class="label" for="cedula">Cédula del estudiante:</label>  
                    <input class="formInput" value="<?php echo $mostrar['cedula']; ?>" type="number" id="cedula" name="cedula" required title="Cédula del estudiante">  
                </div> 

             <div class="field btns">
              <button type="button" class="prev-2 prev"><i class="icon icon-arrow-left"></i> Atr&#225;s</button>
              <button type="button" class="next-2 next">Siguiente <i class="icon icon-arrow-right"></i></button>
            </div>

            </div>




            <div class="page">
                <div class="field"> 
                    <label class="label" for="carr">Carrera del estudiante:</label>

                    <?php $result = $conn->query("SELECT * FROM carrera  ORDER BY nom asc"); ?> 

                    <select class="select-css formInput" id="carr" name="carr" required title="Carrera del estudiante">
                    <option value="">Selecione carrera</option>
                    <?php while ($row = mysqli_fetch_array($result)) { ?>
                        <option
                         <?php if ($mostrar['carrera']== $row['code']) { ?>
                                selected
                        <?php } ?>


                          value="<?php echo $row['code']; ?>"><?php echo $row['nom']; ?></option>
                    <?php } ?>
                        
                    </select> 
                      
                </div> 


                 <div class="field"> 
                    <label class="label" for="acto">Tipo de acto</label>

                    <select class="select-css formInput" id="acto" name="acto" required title="Tipo de acto">
                    <option value="">Selecione Tipo de acto</option>
                    <?php

                      $actos =  array('','Ceremonia','Secretaria');
                     for ($a=1; $a <= count($actos)-1; $a++) { ?>
                        <option
                         <?php if ($mostrar['acto'] == $a) { ?>
                                selected
                        <?php } ?>
                          value="<?php echo $a; ?>"><?php echo $actos[$a]; ?></option>
                    <?php } ?>
                        
                    </select> 
                      
                </div> 




                    <div class="field"> 
                    <label class="label" for="carr">Nivel de grado:</label>

                    <select class="select-css formInput" id="titulo" name="titulo" required title="Nivel de grado:">
                    <option value="">Selecione nivel de grado</option>
                    <?php if (!isset($_GET['id'])) { ?>
                    
                    <option disabled value="">En espera de datos...</option>

                    <?php } ?>
                    <!--el contenido de este elemento lo obtenemos de 'tipoTitulo.php'-->

                    <!--en caso de que se este editado aun estudiante se ejecutara la consulta aquí-->
                    <?php if (isset($_GET['id'])) { ?>
                      
                    <?php

                    $carrera_titulo=trim($mostrar['carrera']);

                    $consultaTitulo = $conn->query("SELECT *, 
                     CASE 
                     WHEN titulo = 1 THEN 'Ingeneria'
                     WHEN titulo = 2 THEN 'Licenciatura'
                     ELSE titulo
                     END AS titulos
                     FROM carrera
                        where code like '$carrera_titulo'");

                    $mostrarTitulo = $consultaTitulo -> fetch_assoc();

                    $text_titulo=$mostrarTitulo['titulos'];

                      $titulos =  array('','TSU',$text_titulo);
                     for ($t=1; $t <= count($titulos)-1; $t++) { ?>
                        <option
                         <?php if ($mostrar['titulo'] == $t) { ?>
                                selected
                        <?php } ?>
                          value="<?php echo $t; ?>"><?php echo $titulos[$t]; ?></option>
                    <?php } ?>

                    <?php } ?>
                        
                    </select> 
                      
                </div> 




                <div class="field">  

                    <?php if (isset($_GET['id'])) { ?>
                        <label class="label" for="file">Modificar Imagen escanciada del folio (opcional):</label> 
                    <?php } ?>

                    <?php if (!isset($_GET['id'])) { ?>
                       <label class="label" for="file">Archivo escanciada del folio (PDF):</label>  
                    <?php } ?>
                    



                    <input  type="file" id="file" name="file" accept=".pdf,application/pdf" 
                    <?php if (!isset($_GET['id'])) { ?>
                       required
                       class="formInput"
                    <?php } ?>  title="Archivo escanciada del folio (PDF)">  
                </div> 

                <span id="noVilid" class="noValid">¡Los datos ingresados están repetidos!</span>
                <div class="field btns"> 
                    <button  type="button" class="prev-3 prev"><i class="icon icon-arrow-left"></i> Atr&#225;s</button>

                    
                    <button class="formInput boton next-2 next" type="submit"><?php echo $boton; ?></button>  
                </div> 

            </div>
<!--fin de las paginas-->

                 

  
            </form> 

            </div> 

            </div>
            
        </center>
           
            
        </section>  
    </main>  <br> <br><br><br><br>

</div>
<script src="js/sweetAlert.js"></script>
<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/registroGraduado.js?time=<?php echo date('s'); ?>"></script>
<script src="js/validarRgs.js"></script>
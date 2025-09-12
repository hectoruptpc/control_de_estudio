<?php
/*=======================================================*/
/*===== archivo para las consultas e los horarios de los Alumnos =====*/
/*=======================================================*/
  include("conexion.php");
  session_start();

         

          $result = $conn->query("SELECT *,
            (SELECT nom FROM carrera where code like carrera) as nomCarr
           FROM alumno  ORDER BY nom asc");



                if (isset($_POST['mos'])) {

                


                
                $mos = trim($_POST['mos']);
                $text = trim($_POST['txt']);
               
               $result = $conn->query("SELECT *,
            (SELECT nom FROM carrera where code like carrera) as nomCarr, 
             CASE 
              WHEN acto = 1 THEN 'Ceremonia'
              WHEN acto = 2 THEN 'Secretaria'
              ELSE 'Datos no disponibles'
             END AS actos
            FROM alumno 
                        where nom like '%$text%'
                        or apll like '%$text%'
                        or carrera like '%$text%'
                        or folio like '%$text%'
                        or cedula like '%$text%'
                        or fechaGrado like '%$text%'
                        ORDER BY fechaGrado, nom asc
                          LIMIT $mos");
                    
               
                    

                  
                        
                

                }      

            




?>




<?php  if (mysqli_num_rows($result) > 0) { ?>

<?php  while ($row = mysqli_fetch_array($result)) { ?>


                <ul>  
                    <li>  
                        <h3>Pagina del folio: <?php echo $row['folio']; ?></h3>  
                        <p>Graduado: <?php echo $row['nom'].' '.$row['apll']; ?></p>  
                        <p>Cedula: <?php echo $row['cedula']; ?></p> 
                        <p>Carrera: <?php echo $row['nomCarr']; ?></p>

                   <?php
                    $carrera_titulo=trim($row['carrera']);

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

                        ?>






                        <p>Tipo de titulo: <?php echo $titulos[intval($row['titulo'])]; ?></p> 
                        <p>Graduación por: <?php echo $row['actos']; ?></p> 
                        <p>Fecha del grado: <?php echo $row['fechaGrado']; ?></p> 
                        <p>Tomo: <?php echo $row['tomo']; ?></p> 
                        <p>Folio: <?php echo $row['folio']; ?> 
                        <?php

                        $nomDir =substr($row['cedula'], 0, 7);//solo sera de 7 caracteres

                        //ruta de la imagen a pasar a pdf
                        $ruta='archivos/CI_'.$nomDir.'/'.$row['archivo'];

                          //ruta vieja
                         /*pdfFolio.php?id=<?php echo $row['id'].'&'.date('s'); ?>*/
                         ?>


                          <br>  <i class="icon icon-file-pdf pdf"></i> <a href="<?php echo $ruta; ?>" target="_blank" class="verFolio">Visualizar</a></p> <br> 
                        

                        <p>Acciones:  <a class="alumrow editar" 
         title="Editar elemento" href="index.php?formGraduado=1&id=<?php echo $row['id']  ?>" style="text-decoration: none;">
          <!--Editar-->
                                <button  class="pencil">
                
                                    <i class="icon icon-pencil2"></i>
                  
                                </button>
        </a>
                <a name="bin" 
        class="alumrow" 
        title="Eliminar elemento" href="eliminarFolio.php?cedula=<?php echo $row['cedula'];  ?>">
          <!--Eliminar-->
       
                              <button  class="denegar">
                
                                    <i class="icon icon-bin"></i>
                  
                                </button>

        </a>
        </p> 
                    </li>  
           




      
            

        

      </ul> 

    
<?php } ?>

<?php }else{ ?>

    <p style="text-align: center;" colspan ="7">No hay datos-Disponibles </p>



<?php } ?>

<script src="js/verFolio.js"></script>
<script src="js/eliminarRowBD.js"></script>
<script src="js/editarRow.js"></script>

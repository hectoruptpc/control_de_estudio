<?php
//archivo php para obtener el contenido del selector de los tipos
//de títulos des los graduados  
include("conexion.php");

if (isset($_POST['carrera'])) {

					$carrera_titulo=trim($_POST['carrera']);

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
                      echo '<option value="">Selecione Tipo de graduación</option>';
                     for ($t=1; $t <= count($titulos)-1; $t++) { ?>
                        <option
                         <?php if ($mostrar['titulo'] == $t) { ?>
                                selected
                        <?php } ?>
                          value="<?php echo $t; ?>"><?php echo $titulos[$t]; ?></option>
                    <?php } ?>


<?php } ?>

                    




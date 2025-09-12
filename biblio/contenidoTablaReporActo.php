<?php
/*======================================================*/
/*===== archivo para las consultas de los estudiantes =====*/
/*======================================================*/
  include("conexion.php");

  session_start();

         

          

                
               

                $result = $conn->query("SELECT *,
            (SELECT nom FROM carrera where code like carrera) as nomCarr,
            CASE 
            WHEN acto = 1 THEN 'Ceremonia'
            WHEN acto = 2 THEN 'Secretaria'
            ELSE acto
            END AS Actos,
            CASE 
            WHEN titulo = 1 THEN 'TSU'
            WHEN titulo = 2 THEN (SELECT CASE 
            WHEN c.titulo = 1 THEN 'Ingeneria'
            WHEN c.titulo = 2 THEN 'Licenciatura'
            ELSE c.titulo
            END 
            FROM carrera c  where c.code like carrera)
            ELSE titulo
            END AS titulos
           FROM alumno  ORDER BY nom asc");


                if (isset($_POST['mos'])) {

                


                
                $mos = trim($_POST['mos']);
                $text = trim($_POST['txt']);

                 $fecha1 = '';
                 $fecha2 = '';
                 $carr = '';
                 $acto = '';
                 $titulo = '';

                if (isset($_POST['fecha2'])) {
                    $fecha2 = trim($_POST['fecha2']);
                }
                
                if (isset($_POST['fecha1'])) {
                    $fecha1 = trim($_POST['fecha1']);
                }

                if (isset($_POST['carr'])) {
                    $carr = trim($_POST['carr']);
                }

                if (isset($_POST['acto'])) {
                    $acto = trim($_POST['acto']);
                }

                if (isset($_POST['titulo'])) {
                    $titulo = trim($_POST['titulo']);
                }
               
                
                
            if($titulo == 1 || $titulo == ''){

            

              

              $result = $conn->query("SELECT *,
            (SELECT nom FROM carrera where code like carrera) as nomCarr,
            CASE 
            WHEN acto = 1 THEN 'Ceremonia'
            WHEN acto = 2 THEN 'Secretaria'
            ELSE acto
            END AS Actos,
            CASE 
            WHEN titulo = 1 THEN 'TSU'
            WHEN titulo = 2 THEN (SELECT CASE 
            WHEN c.titulo = 1 THEN 'Ingeneria'
            WHEN c.titulo = 2 THEN 'Licenciatura'
            ELSE c.titulo
            END 
            FROM carrera c  where c.code like carrera)
            ELSE titulo
            END AS titulos
            FROM alumno 
            where fechaGrado BETWEEN '$fecha1' AND '$fecha2' 
            and(
            carrera like '%$carr%'
            and acto like '%$acto%'
            AND  titulo like '%$titulo%'
            )
            ORDER BY nomCarr, fecha, nom asc
            LIMIT $mos");





            }else if($titulo == 2 || $titulo == 3){

                $tituloP=intval($titulo)-1;//titulo del profesional
              $result = $conn->query("SELECT *,
            (SELECT nom FROM carrera where code like carrera) as nomCarr,
            CASE 
            WHEN acto = 1 THEN 'Ceremonia'
            WHEN acto = 2 THEN 'Secretaria'
            ELSE acto
            END AS Actos,
            CASE 
            WHEN titulo = 1 THEN 'TSU'
            WHEN titulo = 2 THEN (SELECT CASE 
            WHEN c.titulo = 1 THEN 'Ingeneria'
            WHEN c.titulo = 2 THEN 'Licenciatura'
            ELSE c.titulo
            END 
            FROM carrera c  where c.code like carrera)
            ELSE titulo
            END AS titulos
            FROM alumno 
            where fechaGrado BETWEEN '$fecha1' AND '$fecha2' 
            and(
            carrera like '%$carr%'
            and acto like '%$acto%'
            and titulo like '2'
            AND carrera in (SELECT c.code
            FROM carrera c  where c.titulo like '%$tituloP%')
            )
            ORDER BY nomCarr, fecha, nom asc
            LIMIT $mos");
            }
                

                }      

            




?>




<?php  if (mysqli_num_rows($result) > 0) { ?>

<?php  while ($row = mysqli_fetch_array($result)) {  ?>


     <tr>
        <td><?php echo $row['nom'].' '.$row['apll']; ?> </td>
        <td><?php echo $row['cedula'] ?></td>
        <td><?php echo $row['nomCarr'] ?></td>
        <td><?php echo $row['Actos'] ?></td>
        <td><?php  echo $row['titulos']; ?></td>
        <td><?php echo $row['fechaGrado'] ?></td>
        <!--td><?php echo $row['fecha'] ?></td-->

        <script type="text/javascript">
          document.getElementById('cant').innerText="<?php echo mysqli_num_rows($result); ?>";

        </script>
        

       </tr> 

    
<?php } ?>

<?php }else{ ?>

  <tr>
    <td style="text-align: center;" colspan ="7">No hay datos-Disponibles </td>
    <script type="text/javascript">
          document.getElementById('cant').innerText="<?php echo mysqli_num_rows($result); ?>";

        </script>
  </tr>


<?php } ?>


<script src="js/eliminarRowBD.js"></script>
<script src="js/editarRow.js"></script>

 <?php
//tabla de los estudiantes registrados
include("conexion.php");
error_reporting(0);
session_start();

?>
<!-- CONTENEDOR DE LOS ELEMENTOS  -->
<div class="container" >
  <h3 class="mt-5">Reporte de Graduados





  <a href="pdfAlum.php" target="_blank">
    <button class="pdf">
    

    <i class="icon icon-file-pdf"></i>

    </button>



  </a>
  </h3>
  <hr>
<br>


<div>
  
  <div  class="dataTables_wrapper no-footer">

    <div class="dataTables_length dataTables_filter" id="table_id_length">
        <label>Cantidad de estudiantes:&nbsp;&nbsp;
            <span id="cant"></span><!--numero de la cantidad de estudiantes-->
    </label>
</div>



    </div>
</div>

<!--bucar po secretaria o acto -->

<div>
  
  <div  class="dataTables_wrapper no-footer">

        <div class="dataTables_length " id="table_id_length">

        <label>Carrera 
            <select id="carr" name="carr" aria-controls="table_id" class="">
            <option value="">Todas</option>
            
            
<?php $result = $conn->query("SELECT * FROM carrera  ORDER BY nom asc"); ?>

 <?php  if (mysqli_num_rows($result) > 0) { ?>

  <?php  while ($row = mysqli_fetch_array($result)) {  ?>

        <option value="<?php echo $row['code']; ?>"><?php echo $row['nom']; ?></option>
    
  <?php } ?>
<?php } ?>

            </select>
    </label>
    </div>


    <div style="display: none;" class="dataTables_length " id="table_id_length">

        <label>Tipo acto
            <select id="acto" name="acto" aria-controls="table_id" class="acto">
            <option value="">Todos</option>
            <option value="1">Ceremonia</option>
            <option value="2">Secretaria</option>
            
            </select>
    </label>
    </div>


      <div class="dataTables_length dataTables_filter" id="table_id_length">

        <label>Nivel de grado
            <select id="titulo" name="titulo" aria-controls="table_id" class="titulo">
            <option value="">Todos</option>
            <option value="1">TSU</option>
            <option value="2">Ingeniera</option>
            <option value="3">Licenciatura</option>
            
            
            </select>
    </label>
    </div>

    </div>


 
</div>

<!--
el atributo url contiene la dirección el archivo de donde
se resabiara el contenido del tabla
-->

<div id="contenedor" class="dataTables_wrapper no-footer" url="contenidoTablaReporAlum.php">

<div class="dataTables_length dataTables_filter" id="table_id_length">
        <label>Mostrar Desde
            <input name="busqueda" id="fecha1" type="date"  aria-controls="table_id"> 
            &nbsp;Hasta&nbsp;
            <input name="busqueda" id="fecha2" type="date"  aria-controls="table_id">
    </label>
</div>

     <div class="dataTables_length " id="table_id_length">

        <label>Mostrar 
            <select id="mos" name="mos" aria-controls="table_id" class="">
            <option value="2">2</option>
            <option selected value="5">5</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            </select> Registros
    </label>
    </div>



    
</div>

<div class="contenerdor_tabla">



        <table class="tutorial-table" id="table_id">
       

     <thead>

             <tr>
                <th class="centrado" style="text-align: left;">Graduado</th>
                <th class="centrado" style="text-align: left;">Cédula</th>
                <th class="centrado" style="text-align: left;">Carrera</th>
                <th class="centrado" style="text-align: left;">Tomo</th>
                <th class="centrado" style="text-align: left;">Folio</th>
                <!--th class="centrado" style="text-align: left;">Tipo de acto</th-->
                <th class="centrado" style="text-align: left;">Nivel de grado</th>
                <th class="centrado" style="text-align: left;">Fecha del grado</th>
                <th class="centrado" style="text-align: left;">Fecha del registro</th>
            </tr>
            

        </thead>


       
<tbody id="contenido_tabla">

<!--aqui va el contenio de la tabla que viene de contenidoTablaCarr.php-->

</tbody>



  </table> 


</div> 

    

   
      
  </div>

</div><!--fin del contenido-->
<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/consultas.js?time=<?php echo date('s'); ?>"></script>
<script src="js/fechaPDF.js"></script>
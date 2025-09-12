 <?php
//tabla de los estudiantes registrados
include("conexion.php");
error_reporting(0);
session_start();

?>
<!-- CONTENEDOR DE LOS ELEMENTOS  -->
<div class="container" >
  <h3 class="mt-5">Listado de Carreras</h3>
  <hr>
<br>

<!--
el atributo url contiene la dirección el archivo de donde
se resabiara el contenido del tabla
-->

<div id="contenedor" class="dataTables_wrapper no-footer" url="contenidoTablaCarr.php">

<div id="table_id_filter" class="dataTables_filter">
        <label>Buscar:<input name="buscar" id="buscar" type="text"  aria-controls="table_id"></label>
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
                <th class="centrado">Carrera</th>
                <th class="centrado">Codigo</th>
                <th class="centrado">Nivel de grado</th>
              <?php if (isset($_SESSION['tipoUser'])) { ?>
              
              <th class="centrado">Acciones</th>    
            
              <?php } ?>    
                
                
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
<script src="js/consultas.js"></script>
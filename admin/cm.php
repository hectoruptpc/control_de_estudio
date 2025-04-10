<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Creador de Mensajes";
include('../funciones/functions.php');

?>
<?php include("includes/head.php"); ?>
<div class="container">

<h1>CREACION CON USO DE PRODUCTO CARTESIANO</h1>
<h2>Permutas</h2>


<div class="container mt-5">
        <h1>Editor de Permutaciones</h1>

        <!-- notification message -->
<?php if (isset($_SESSION['editar_permutas'])) : ?>
			<div class="alert alert-success" role="alert" >
				<h3>
					<?php
						echo $_SESSION['editar_permutas'];
						unset($_SESSION['editar_permutas']);
					?>
				</h3>
			</div>
<?php endif ?>


        <form id="permutacion-form" method="post" action="funciones/guardar_permutas.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>C1</th>
                        <th>C2</th>
                        <th>C3</th>
                        <th>C4</th>
                    </tr>
                </thead>
                <tbody id="tabla-creador">
                    <?php
                  // Obtener datos de la tabla "creador"
                    $sql = "SELECT * FROM creador";
                    $result = $db->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td><input type='text' class='form-control' name='c1[]' value='" . $row["c1"] . "'></td>";
                            echo "<td><input type='text' class='form-control' name='c2[]' value='" . $row["c2"] . "'></td>";
                            echo "<td><input type='text' class='form-control' name='c3[]' value='" . $row["c3"] . "'></td>";
                            echo "<td><input type='text' class='form-control' name='c4[]' value='" . $row["c4"] . "'></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No se encontraron datos.</td></tr>";
                    }
                   
                    ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>

        <div class="mt-4" id="resultado-permutaciones">
            <h2>Permutaciones:</h2>
            <div id="permutaciones">
                <!-- Aquí se mostrarán las permutaciones -->
            </div>
        </div>
    </div>

    <script>
 $(document).ready(function(){
  // Función para generar las permutaciones (se usará para ambos eventos)
  function generarPermutaciones() {
        $.ajax({
          url: "funciones/generar_permutaciones.php", 
          type: "POST",
          data: $("#permutacion-form").serialize(), 
          success: function(response) {
            $("#permutaciones").html(response);
          }
        });
      }

      // Llama a la función al cargar la página
      generarPermutaciones(); 


    });
    </script>







<?php include("includes/footer.php"); ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Editar Contenidos";
include('../funciones/functions.php');

$rowid = $_REQUEST['id'];

?>
<?php include("includes/head.php"); ?>
<div class="container">

<h2>Editar Contenidos</h2>

<!-- notification message -->
<?php if (isset($_SESSION['editar_mensajeria'])) : ?>
			<div class="alert alert-danger" role="alert" >
				<h3>
					<?php
						echo $_SESSION['editar_mensajeria'];
						unset($_SESSION['editar_mensajeria']);
					?>
				</h3>
			</div>
<?php endif ?>

<?php
editar_mensajeria();
?>


<script>
    $('#contenido').summernote({
   placeholder: 'Ingrese su contenido',
   tabsize: 2,
   height: 300
 });
 </script>

<?php echo $rowid;?>
</div>

</div>



<?php include("includes/footer.php"); ?>

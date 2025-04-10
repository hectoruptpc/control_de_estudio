<?php
include('../../../../funciones/functions.php');

	/* Connect To Database*/
	require_once ("../conexion.php");

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));

	$tables="agenda";
	$campos="*";
	$sWhere=" ($tables.first_name LIKE '%".$query."%'";
	$sWhere.=" OR $tables.numero LIKE '%".$query."%')";
	$sWhere.=" AND $tables.id_user = '$id_usua'";
	$sWhere.=" ORDER BY first_name ASC";


	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables WHERE $sWhere ");
	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	else {echo mysqli_error($con);}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$query = mysqli_query($con,"SELECT $campos FROM  $tables WHERE $sWhere  LIMIT $offset,$per_page");
	//loop through fetched data





	if ($numrows>0){

	?>
<div class="table-responsive">
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Nombre </th>
				<th>Numero </th>
				<th></th>
			</tr>
		</thead>
	<tbody>
<?php
$finales=0;
while($row = mysqli_fetch_array($query)){
	$first_name=$row['first_name'];
	$numero=$row['numero'];
	$id=$row['id'];
	$finales++;
?>
<tr class="<?php echo $text_class;?>">
<td ><?php echo $first_name;?></td>
<td ><?php echo $numero;?></td>
<td>
	<!--
<a href="#"  data-target="#editProductModal" class="edit" data-toggle="modal" data-code='<?php echo $prod_code;?>' data-name="<?php echo $prod_name?>" data-category="<?php echo $prod_ctry?>" data-stock="<?php echo $prod_qty?>" data-price="<?php echo $price;?>" data-id="<?php echo $product_id; ?>"><i class="material-icons" data-toggle="tooltip" title="Editar" >&#xE254;</i></a>
<a href="#deleteProductModal" class="delete" data-toggle="modal" data-id="<?php echo $product_id;?>"><i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i></a>
!-->
<a href="#" data-target="#editProductModal" class="btn btn-warning" data-toggle="modal" data-first_name='<?php echo $first_name; ?>' data-numero='<?php echo $numero ?>' data-id='<?php echo $id; ?>'> <i class="fas fa-user-edit" data-toggle="tooltip" title="Editar"></i> </a>
<a href="#deleteProductModal" class="btn btn-danger" data-toggle="modal" data-id="<?php echo $id;?>"><i class="fas fa-user-slash" data-toggle="tooltip" title="Eliminar"></i></a>
</td>
</tr>
<?php
}
?>
<tr>
	<td colspan='6'>
<?php
	$inicios=$offset+1;
	$finales+=$inicios -1;
	echo "Mostrando $inicios al $finales de $numrows registros";
	echo paginate( $page, $total_pages, $adjacents);
?>
	</td>
</tr>
</tbody>
</table>
</div>



<?php
}
else {
echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Lo sentimos no hay datos a su agenda.</div>';
}
}
?>

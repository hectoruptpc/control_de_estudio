<div id="addProductModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="add_product" id="add_product">
					<div class="modal-header">
						<h4 class="modal-title">Agregar Nuevo Numero</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">

						<div class="form-group">
							<label>Nombre</label>
							<input type="text" name="first_name"  id="first_name" class="form-control" required>

						</div>
						<div class="form-group">
							<label>Numero</label>
							<input type="text" name="numero" id="numero" class="form-control" required>
						</div>

						<input type="hidden" name="id_usua" value="<?php echo $id_usua; ?>">

					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
						<input type="submit" class="btn btn-success" value="Guardar datos">
					</div>
				</form>
			</div>
		</div>
	</div>

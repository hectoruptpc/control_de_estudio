<div id="editProductModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="edit_product" id="edit_product">
					<div class="modal-header">
						<h4 class="modal-title">Editar Contacto</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">

						<div class="form-group">
							<label>Nombre</label>
							<input type="text" name="edit_first_name"  id="edit_first_name" class="form-control" required>
							<input type="hidden" name="edit_id" id="edit_id" >
						</div>

						<div class="form-group">
							<label>Numero</label>
							<input type="text" name="edit_numero" id="edit_numero" class="form-control" required>
						</div>




					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
						<input type="submit" class="btn btn-info" value="Guardar datos">
					</div>
				</form>
			</div>
		</div>
	</div>

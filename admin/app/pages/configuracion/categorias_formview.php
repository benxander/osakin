<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<div class="row">
		    <div class="form-group col-md-10">
				<label for="razonsocial" class="control-label minotaur-label">Agregar Categoria </label>
				<input
				ng-model="mp.temporal.categoria"
				type="text"
				class="form-control"
				placeholder="Ingrese nombre de Categoria"
				>
			</div>
			<div class="col-md-1 btn btn-success mt-lg" ng-click="mp.agregarCat();">
				Agregar
			</div>
		</div>
		<div class="row">
			<div class="form-group col-xs-12 m-n">
				<label class="control-label">Categorias: (Doble clic para editar campo)</label>
				<div
					ui-grid="mp.gridOptions"
					ui-grid-resize-columns
					ui-grid-auto-resize
					ui-grid-edit
					class="grid table-responsive"
					style="overflow: hidden;"
					ng-style="getTableHeight();">
				</div>
            </div>
        </div>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Salir</button>
  <!-- <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formEmpresa.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button> -->
</div>
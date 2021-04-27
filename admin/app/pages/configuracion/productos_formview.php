<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<form name="formExcel" role="form" novalidate class="form-validation">
			<div class="row">
				<div class="form-group col-sm-12">
					<label class="control-label minotaur-label">Subir excel</label>
					<div class="pull-right"><a href="assets/documentos/formato_subida_productos.xlsx">Formato de subida <i class="fa fa-download"></i></a></div>
					<input  type="file" class="filestyle" nv-file-select="" uploader="uploader" filestyle button-text="Excel" icon-name="fa fa-inbox" accept=".xls, .xlsx" required>
					<button class="btn btn-success " ng-disabled="formExcel.$invalid" ng-click="mp.aceptar()">Aceptar</button>
				</div>
			</div>
		</form>

	</section>

	<section class="tile-body p-0">
		<div class="row">
			<div class="form-group col-xs-12 m-n">
				<label class="control-label">Productos de la Carta:</label>
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
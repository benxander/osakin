<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-2">
		<form name="formSitioWeb" role="form" novalidate class="form-validation">
			<div class="row">
				<div class="form-group col-xs-12">
					<label for="titulo" class="control-label minotaur-label">Valor <small class="text-red">(*)</small></label>
					<div><small>{{mp.fData.descripcion}}</small></div>
					<input
						ng-if="mp.fData.tipo != 'imagen' "
						ng-model="mp.fData.valor"
						type="text"
						name="valor"
						id="valor"
						class="form-control"
						placeholder="Registra valor"
						autocomplete="off"
						required
					>
					<img
						ng-if="mp.fData.tipo == 'imagen'"
						ng-src="{{mp.dirUploads + mp.fData.valor}}"
						alt="imagen"
					>
				</div>



		</form>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Cancelar</button>
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formSitioWeb.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
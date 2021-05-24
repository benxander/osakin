<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-2">
		<form name="formSede" role="form" novalidate class="form-validation">
			<div class="row">
				<div class="form-group col-xs-12">
					<label for="titulo" class="control-label minotaur-label">Titulo </label>
					<input	ng-model="mp.fData.titulo"
						type="text"
						name="titulo"
						id="titulo"
						class="form-control"
						placeholder="Registra titulo la sede"
						autocomplete="off"
					>
				</div>
				<div class="form-group col-xs-12">
					<label for="contenido" class="control-label minotaur-label">Contenido <small class="text-red">(*)</small></label>
					<text-angular ng-model="mp.fData.contenido" name="contenido"></text-angular>
					<div ng-messages="formSede.contenido.$error" ng-show="formSede.contenido.$dirty" role="alert"
						class="help-block text-red">
						<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
					</div>
				</div>


		</form>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Cancelar</button>
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formSede.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
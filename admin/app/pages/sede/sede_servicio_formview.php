<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<form name="formSedeServ" role="form" novalidate class="form-validation">
			<div class="row">
				<div class="form-group col-md-12">
					<label for="nombre" class="control-label minotaur-label">Nombre del Servicio <small class="text-red">(*)</small> </label>
					<input
					ng-model="mp.fData.servicio"
					type="text"
					name="servicio"
					id="servicio"
					class="form-control"
					placeholder="Registra nombre del servicio"
					autocomplete="off"
					required
					>
					<div ng-messages="formSede.titulo.$error" ng-show="formSede.servicio.$dirty" role="alert" class="help-block text-red">
					<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
					</div>
				</div>
				<div class="form-group col-md-12">
					<label for="nombre" class="control-label minotaur-label">Título <small class="text-red">(*)</small> </label>
					<input
					ng-model="mp.fData.titulo"
					type="text"
					name="titulo"
					id="titulo"
					class="form-control"
					placeholder="Registra titulo"
					autocomplete="off"
					required
					>
					<div ng-messages="formSede.titulo.$error" ng-show="formSede.titulo.$dirty" role="alert" class="help-block text-red">
					<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
					</div>
				</div>
				<div class="form-group col-md-12">
					<label class="control-label minotaur-label">Descripción <small class="text-red">(*)</small> </label>


					<text-angular ng-model="mp.fData.descripcion"></text-angular>
				</div>
			</div>
		</form>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Cancelar</button>
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formSedeServ.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
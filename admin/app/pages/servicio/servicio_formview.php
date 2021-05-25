<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<form name="formServicio" role="form" novalidate class="form-validation">
		    <div class="row">
				<div class="col-12">
					<div class="form-group col-md-12">
						<label for="nombre" class="control-label minotaur-label">Nombre <small class="text-red">(*)</small> </label>
						<input
							ng-model="mp.fData.nombre"
							type="text"
							name="nombre"
							id="nombre"
							class="form-control"
							placeholder="Registra nombre del servicio"
							autocomplete="off"
							required
						>
						<div ng-messages="formServicio.nombre.$error" ng-show="formServicio.nombre.$dirty" role="alert" class="help-block text-red">
						<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Cancelar</button>
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formServicio.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
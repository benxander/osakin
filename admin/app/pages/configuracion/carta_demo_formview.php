<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<form name="formCartaDemo" role="form" novalidate class="form-validation">
			<div class="row">
				<div class="form-group col-md-6">
					<label for="razonsocial" class="control-label minotaur-label">Nombre de Carta <small class="text-red">(*)</small> </label>
					<input
						ng-model="mp.fData.razon_social"
						type="text"
						name="razonsocial"
						id="razonsocial"
						class="form-control"
						placeholder="Registre Nombre de la Carta Demo"
						required
					>
					<div ng-messages="formCartaDemo.razonsocial.$error" ng-if="formCartaDemo.razonsocial.$dirty" role="alert" class="help-block text-red">
					<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="nombrenegocio" class="control-label minotaur-label">Nombre del negocio <small
							class="text-red">(*)</small> </label>
					<input
						ng-model="mp.fData.nombre_negocio"
						type="text"
						name="nombrenegocio"
						id="nombrenegocio"
						class="form-control"
						placeholder="Nombre subdominio, sin espacios"
						ng-pattern="/^[a-z0-9\-\_]*$/"
						required
					>
					<div ng-messages="formCartaDemo.nombrenegocio.$error" ng-if="formCartaDemo.nombrenegocio.$dirty" role="alert"
						class="help-block text-red">
						<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-6 mb-md ">
					<label class="control-label minotaur-label"> Modelo: <small class="text-danger">(*)</small> </label>
					<select class="form-control" ng-model="mp.fData.modeloObj"
						ng-options="item as item.descripcion for item in mp.fArr.listaModelos" required></select>
				</div>

				<div class="form-group col-md-6 mb-md ">
					<label class="control-label minotaur-label"> Color: <small class="text-danger">(*)</small> </label>
					<select class="form-control" ng-model="mp.fData.colorObj"
						ng-options="item as item.nombre for item in mp.fArr.listaColores" required></select>
				</div>
			</div>

		</form>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Salir</button>
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formCartaDemo.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
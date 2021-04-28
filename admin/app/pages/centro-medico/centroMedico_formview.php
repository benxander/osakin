<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<form name="formCentroMedico" role="form" novalidate class="form-validation">
		    <div class="row">
		    	<div class="form-group col-md-12">
					<div class="row">
			            <div class="form-group col-md-6">
			              <label for="nombre" class="control-label minotaur-label">Nombre <small class="text-red">(*)</small> </label>
			              <input
							ng-model="mp.fData.nombre"
							type="text"
							name="nombre"
							id="nombre"
							class="form-control"
							placeholder="Registra nombre del centro médico"
							required
						  >
			              <div ng-messages="formCentroMedico.nombre.$error" ng-if="formCentroMedico.nombre.$dirty" role="alert" class="help-block text-red">
			                <div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              </div>
			            </div>
			            <div class="form-group col-md-6">
			              <label for="direccion" class="control-label minotaur-label">DIRECCIÓN <small class="text-red">(*)</small> </label>
			              <input
							ng-model="mp.fData.direccion"
							type="text"
							name="direccion"
							id="direccion"
							class="form-control"
							placeholder="Registra dirección"
							required
						  >
			              <div ng-messages="formCentroMedico.direccion.$error" ng-if="formCentroMedico.direccion.$dirty" role="alert" class="help-block text-red">
			                <div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              </div>
			            </div>
			        </div>

					<div class="row">


						<div class="form-group col-md-6">
							<label for="horario" class="control-label minotaur-label">Horario <small
									class="text-red">(*)</small></label>
							<input
								ng-model="mp.fData.horario"
								type="text"
								name="horario"
								id="horario"
								class="form-control"
								placeholder="Registra horario del centro"
								required
							>
							<div ng-messages="formCentroMedico.horario.$error" ng-if="formCentroMedico.horario.$dirty" role="alert"
								class="help-block text-red">
								<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
							</div>
						</div>

						<div class="form-group col-md-3">
			              	<label for="telefono" class="control-label minotaur-label">Teléfono </label>
			              	<input
								ng-model="mp.fData.telefono"
								type="tel"
								name="telefono"
								id="telefono"
								class="form-control"
								placeholder="Registra telefono"
								ng-pattern="/([0-9]{9})$/"
							>
			              	<div ng-messages="formCentroMedico.telefono.$error" ng-if="formCentroMedico.telefono.$dirty" role="alert" class="help-block text-red">
			                	<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              	</div>
			            </div>
			            <div class="form-group col-md-3">
			              	<label for="email" class="control-label minotaur-label">Correo </label>
			              	<input
								ng-model="mp.fData.email"
								type="email"
								name="email"
								id="email"
								class="form-control"
								placeholder="Registra email"
							>
			              	<div ng-messages="formCentroMedico.email.$error" ng-if="formCentroMedico.email.$dirty" role="alert" class="help-block text-red">
			                	<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              	</div>
			            </div>

			        </div>

					<div class="row">
						<div class="form-group col-md-6">
							<label for="titulo" class="control-label minotaur-label">Título <small class="text-red">(*)</small> </label>
							<input
								ng-model="mp.fData.titulo"
								type="text"
								name="titulo"
								id="titulo"
								class="form-control"
								placeholder="Título del apartado"
								required
							>
							<div ng-messages="formCentroMedico.titulo.$error" ng-if="formCentroMedico.titulo.$dirty" role="alert"
								class="help-block text-red">
								<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
							</div>

							<label class="control-label minotaur-label mt-md">Descripción <small class="text-red">(*)</small> </label>
							<textarea ng-model="mp.fData.descripcion" name="descripcion" id="" cols="30" rows="10" class="form-control"></textarea>
						</div>

						<div class="form-group col-md-6">
							<label for="titulo" class="control-label minotaur-label">Imagen </label>
							<input type="file" name="" id="">
						</div>

					</div>

		    	</div>

		    </div>

		</form>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Cancelar</button>
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formCentroMedico.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
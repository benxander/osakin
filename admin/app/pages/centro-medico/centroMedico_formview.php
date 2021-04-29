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
							autocomplete="off"
							required
						  >
			              <div ng-messages="formCentroMedico.nombre.$error" ng-show="formCentroMedico.nombre.$dirty" role="alert" class="help-block text-red">
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
							autocomplete="off"
							required
						  >
			              <div ng-messages="formCentroMedico.direccion.$error" ng-show="formCentroMedico.direccion.$dirty" role="alert" class="help-block text-red">
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
								autocomplete="off"
								required
							>
							<div ng-messages="formCentroMedico.horario.$error" ng-show="formCentroMedico.horario.$dirty" role="alert"
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
								autocomplete="off"
							>
			              	<div ng-messages="formCentroMedico.telefono.$error" ng-show="formCentroMedico.telefono.$dirty" role="alert" class="help-block text-red">
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
								autocomplete="off"
							>
			              	<div ng-messages="formCentroMedico.email.$error" ng-show="formCentroMedico.email.$dirty" role="alert" class="help-block text-red">
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
								autocomplete="off"
								required
							>
							<div ng-messages="formCentroMedico.titulo.$error" ng-show="formCentroMedico.titulo.$dirty" role="alert"
								class="help-block text-red">
								<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
							</div>

							<label class="control-label minotaur-label mt-md">Descripción <small class="text-red">(*)</small> </label>
							<textarea ng-model="mp.fData.descripcion" name="descripcion" id="" cols="30" rows="10" class="form-control"></textarea>
						</div>

						<div class="form-group col-md-6">
							<label class="control-label minotaur-label">Imagen </label>

							<!-- <input  type="file" class="filestyle" nv-file-select="" uploader="uploader" filestyle button-text="Img" icon-name="fa fa-inbox" accept="image/*"> -->

							<!-- <div class="text-center" ng-if="!mp.fotoCrop">
								<img ng-src="assets/images/sin-imagen.png" style="width: 100%">
							</div> -->
							<div class="row" >
								<div class="col-md-12">
									<div class="p-10 bg-white b-a b-solid" style="height:250px">

									<img-crop
										image="mp.fData.myImage"
										result-image="mp.fData.myCroppedImage"
										result-image-size="160"
										area-type="{{mp.cropType}}"
									></img-crop>
									</div>
								</div>
								<div class="col-md-12" ng-show="false">
									<div class="p-15 bg-white b-a b-solid inline-block">
									<img ng-src="{{mp.fData.myCroppedImage}}" />
									</div>
								</div>
							</div>
							<input type="file"
								class="filestyle"
								id="fileInput"
								ng-click="mp.cargarImagen();"
							>
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
<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<form name="formSede" role="form" novalidate class="form-validation">
		    <div class="row">
		    	<div class="col-md-6">
					<div class="row">
			            <div class="form-group col-md-12">
			              <label for="nombre" class="control-label minotaur-label">Nombre <small class="text-red">(*)</small> </label>
			              <input
							ng-model="mp.fData.descripcion_se"
							type="text"
							name="nombre"
							id="nombre"
							class="form-control"
							placeholder="Registra nombre la sede"
							autocomplete="off"
							required
						  >
			              <div ng-messages="formSede.nombre.$error" ng-show="formSede.nombre.$dirty" role="alert" class="help-block text-red">
			                <div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              </div>
			            </div>



						<div class="form-group col-md-6">
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
			              	<div ng-messages="formSede.telefono.$error" ng-show="formSede.telefono.$dirty" role="alert" class="help-block text-red">
			                	<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              	</div>
			            </div>
			            <div class="form-group col-md-6">
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
			              	<div ng-messages="formSede.email.$error" ng-show="formSede.email.$dirty" role="alert" class="help-block text-red">
			                	<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              	</div>
			            </div>
					</div>

		    	</div>

				<!-- IMAGEN -->
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label minotaur-label">Imagen </label>

						<!-- <input  type="file" class="filestyle" nv-file-select="" uploader="uploader" filestyle button-text="Img" icon-name="fa fa-inbox" accept="image/*"> -->

						<div class="text-center" ng-if="!mp.fotoCrop && mp.fData.imagen_se">
							<img ng-src="../uploads/sedes/{{mp.fData.imagen_se}}">
						</div>
						<div class="row" ng-if="mp.fotoCrop || !mp.fData.imagen_se" >
							<div class="col-md-12">
								<div class="p-10 bg-white b-a b-solid" style="height:219px">

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

			<div class="row">
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
					<div ng-messages="formSede.direccion.$error" ng-show="formSede.direccion.$dirty" role="alert" class="help-block text-red">
					<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="direccion" class="control-label minotaur-label">DIRECCIÓN 2 </label>
					<input
					ng-model="mp.fData.direccion2"
					type="text"
					name="direccion2"
					id="direccion2"
					class="form-control"
					placeholder="Registra segunda dirección"
					autocomplete="off"

					>
					<div ng-messages="formSede.direccion.$error" ng-show="formSede.direccion.$dirty" role="alert" class="help-block text-red">
					<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
					</div>
				</div>

				<div class="form-group col-md-6">
					<label for="horario" class="control-label minotaur-label">Horario <small
							class="text-red">(*)</small></label>
					<!-- <input
						ng-model="mp.fData.horario"
						type="text"
						name="horario"
						id="horario"
						class="form-control"
						placeholder="Registra horario del centro"
						autocomplete="off"
						required
					> -->
					<textarea class="form-control" name="horario" id="horario" cols="30" rows="5" ng-model="mp.fData.horario"></textarea>
					<!-- <text-angular ng-model="mp.fData.horario"></text-angular> -->
					<div ng-messages="formSede.horario.$error" ng-show="formSede.horario.$dirty" role="alert"
						class="help-block text-red">
						<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
					</div>
				</div>
				<div class="form-group col-md-12">
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
					<div ng-messages="formSede.titulo.$error" ng-show="formSede.titulo.$dirty" role="alert"
						class="help-block text-red">
						<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
					</div>
				</div>
			</div>
			<div class="row">
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
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formSede.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
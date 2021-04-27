<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<form name="formEmpresa" role="form" novalidate class="form-validation">
		    <div class="row">
		    	<div class="form-group col-md-12">
					<div class="row">
			            <div class="form-group col-md-6">
			              <label for="razonsocial" class="control-label minotaur-label">Razón Social <small class="text-red">(*)</small> </label>
			              <input
							ng-model="mp.fData.razon_social"
							type="text"
							name="razonsocial"
							id="razonsocial"
							class="form-control"
							placeholder="Registre razón social"
							required
						  >
			              <div ng-messages="formEmpresa.razonsocial.$error" ng-if="formEmpresa.razonsocial.$dirty" role="alert" class="help-block text-red">
			                <div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              </div>
			            </div>
			            <div class="form-group col-md-6">
			              <label for="dni_cif" class="control-label minotaur-label">DNI / CIF <small class="text-red">(*)</small> </label>
			              <input
							ng-model="mp.fData.dni_cif"
							type="text"
							name="dni_cif"
							id="dni_cif"
							class="form-control"
							maxlength="9"
							minlength="9"
							ng-pattern="/^\d{9}$/"
							placeholder="Registre DNI o CIFl"
							required
						  >
			              <div ng-messages="formEmpresa.dni_cif.$error" ng-if="formEmpresa.dni_cif.$dirty" role="alert" class="help-block text-red">
			                <div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
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
							placeholder="Registre dirección"
							required
						  >
			              <div ng-messages="formEmpresa.direccion.$error" ng-if="formEmpresa.direccion.$dirty" role="alert" class="help-block text-red">
			                <div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              </div>
			            </div>

						<div class="form-group col-md-6">
							<label for="codigo_postal" class="control-label minotaur-label">Código Postal <small
									class="text-red">(*)</small></label>
							<input ng-model="mp.fData.codigo_postal" type="codigo_postal" name="codigo_postal" id="codigo_postal"
								class="form-control" placeholder="Registra Código Postal" required>
							<div ng-messages="formEmpresa.codigo_postal.$error" ng-if="formEmpresa.codigo_postal.$dirty" role="alert"
								class="help-block text-red">
								<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
							</div>
						</div>

			        </div>

					<div class="row">
						<div class="form-group col-md-6">
							<label for="nombrenegocio" class="control-label minotaur-label">Nombre del negocio <small
									class="text-red">(*)</small> </label>
							<input ng-model="mp.fData.nombre_negocio" type="text" name="nombrenegocio" id="nombrenegocio" class="form-control"
								placeholder="Nombre de negocio (nombre subdominio, sin espacios)" required>
							<div ng-messages="formEmpresa.nombrenegocio.$error" ng-if="formEmpresa.nombrenegocio.$dirty" role="alert"
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
			              	<div ng-messages="formEmpresa.telefono.$error" ng-if="formEmpresa.telefono.$dirty" role="alert" class="help-block text-red">
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
			              	<div ng-messages="formEmpresa.email.$error" ng-if="formEmpresa.email.$dirty" role="alert" class="help-block text-red">
			                	<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              	</div>
			            </div>
					</div>

					<div class="row">
			            <div class="form-group col-md-6">
			              	<label for="personacontacto" class="control-label minotaur-label">Persona de contacto</label>
			              	<input
								ng-model="mp.fData.contacto"
								type="text"
								name="personacontacto"
								id="personacontacto"
								class="form-control"
								placeholder="Registra persona de contacto"
							>
			              	<div ng-messages="formEmpresa.personacontacto.$error" ng-if="formEmpresa.personacontacto.$dirty" role="alert" class="help-block text-red">
			                	<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
			              	</div>
			            </div>

						<div class="form-group col-md-3 mb-md ">
							<label class="control-label minotaur-label"> Plan: <small class="text-danger">(*)</small> </label>
							<select class="form-control" ng-model="mp.fData.plan"
								ng-options="item as item.descripcion for item in mp.fArr.listaPlanes" required></select>
						</div>

						<div class="form-group col-md-3 mb-md ">
							<label class="control-label minotaur-label"> Opción de Pago: <small class="text-danger">(*)</small> </label>
							<select class="form-control" ng-model="mp.fData.tipo_pago"
								ng-options="item as item.descripcion for item in mp.fArr.listaTiposPago" required></select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-6 mb-md ">
							<label class="control-label minotaur-label"> Usuario: </label>
							<div class="input-group">
								<span class="input-group-btn ">
									<input type="text" class="form-control" style="width:40px;margin-right:4px;" ng-model="mp.fData.idusuario" placeholder="ID" readonly="true" />
								</span>
								<input
									ng-model="mp.fData.usuario"
									type="text"
									class="form-control"
									ng-enter="mp.verPopupListaUsuarios(mp.fData)"
									placeholder="Presione ENTER o Seleccionar"
									ng-change="mp.fData.idusuario=null"
									autocomplete="off"
								/>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" ng-click="mp.verPopupListaUsuarios(mp.fData)">Seleccionar</button>
								</span>
							</div>
						</div>
		    		</div>
		    	</div>

		    </div>

		</form>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Cancelar</button>
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formEmpresa.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
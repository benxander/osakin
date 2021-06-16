<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<form name="formSedeServ" role="form" novalidate class="form-validation">
			<div class="row">
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="nombre" class="control-label minotaur-label">Nombre del Servicio <small class="text-red">(*)</small> </label>
								<input
									ng-model="mp.fData.nombre_serv"
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
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="nombre" class="control-label minotaur-label">Teléfono de contacto <small class="text-red">(*)</small> </label>
								<input
									ng-model="mp.fData.telefono_contacto"
									type="text"
									name="telefono_contacto"
									id="telefono_contacto"
									class="form-control"
									placeholder="Solo números"
									autocomplete="off"
									ng-pattern="mp.regexTel"
									required
								>
								<div ng-messages="formSede.titulo.$error" ng-show="formSede.telefono_contacto.$dirty" role="alert" class="help-block text-red">
								<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="nombre" class="control-label minotaur-label">Código de Youtube </label>
								<input
									ng-model="mp.fData.codigo_youtube"
									type="text"
									name="codigo_youtube"
									id="codigo_youtube"
									class="form-control"
									placeholder="Ingresa código de Youtube"
									autocomplete="off"
								>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="nombre" class="control-label minotaur-label">Código de Vimeo </label>
								<input
									ng-model="mp.fData.codigo_vimeo"
									type="text"
									name="codigo_vimeo"
									id="codigo_vimeo"
									class="form-control"
									placeholder="Ingresa código de Vimeo"
									autocomplete="off"
								>
							</div>
						</div>
					</div>
					<div class="form-group" style="margin-top: 2px;">
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
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="nombre" class="control-label minotaur-label">Icono <small class="text-red">(*)</small> </label>
						<div class="text-center">
							<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
								<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; min-height:134px">
									<img class="" ng-if="mp.fData.icono" ng-src="{{mp.dirIconos + mp.fData.icono}}" />
								</div>
								<div>
									<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a>
									<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span>
										<span class="fileinput-exists">Cambiar</span>
										<input type="file" name="file" file-model="mp.fData.iconoServ" />
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12">
					<label class="control-label minotaur-label">Descripción <small class="text-red">(*)</small> </label>


					<!-- <text-angular ng-model="mp.fData.descripcion"></text-angular> -->
					<textarea ckeditor="editorOptions" ng-model="mp.fData.descripcion"></textarea>
				</div>
			</div>
		</form>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Cancelar</button>
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formSedeServ.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
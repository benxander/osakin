<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<form name="formBanner" role="form" novalidate class="form-validation">
		    <div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="form-group col-xs-12">
							<label for="titulo" class="control-label minotaur-label">Título <small class="text-red">(*)</small> </label>
							<input
								ng-model="mp.fData.titulo"
								type="text"
								name="titulo"
								id="titulo"
								class="form-control"
								placeholder="Registra titulo del banner"
								autocomplete="off"
								required
							>
							<div ng-messages="formBanner.titulo.$error" ng-show="formBanner.titulo.$dirty" role="alert" class="help-block text-red">
							<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
							</div>
						</div>

						<div class="form-group col-md-6">
							<label for="grupo" class="control-label minotaur-label">Zona <small class="text-red">(*)</small> </label>
							<select
								class="form-control"
								ng-model="mp.fData.zona"
								ng-options="item as item.descripcion for item in mp.fArr.listaZonas"
								required
							></select>

						</div>

						<div class="form-group col-md-6">
							<label for="grupo" class="control-label minotaur-label">Sede <small class="text-red">(*)</small> </label>
							<select
								class="form-control"
								ng-model="mp.fData.sede"
								ng-options="item as item.descripcion for item in mp.fArr.listaSedes"
								required
							></select>

						</div>

						<div class="form-group col-xs-12">
							<label for="url" class="control-label minotaur-label">Enlace </label>
							<input
								ng-model="mp.fData.url"
								type="text"
								name="url"
								id="url"
								class="form-control"
								placeholder="Registra url del banner"
								autocomplete="off"
								ng-disable="{{mp.fData.zona.id == 'lateral'}} "
							>

							<div ng-messages="formBanner.url.$error" ng-show="formBanner.url.$dirty" role="alert" class="help-block text-red">
							<div ng-messages-include="app/components/templates/messages_tmpl.html"></div>
							</div>
						</div>

					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="nombre" class="control-label minotaur-label">Imagen <small class="text-red">(*)</small> </label>
						<div class="text-center">
							<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
								<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; min-height:134px">
									<img class="" ng-if="mp.fData.imagen" ng-src="{{mp.dirBanner + mp.fData.imagen}}" />
								</div>
								<div>
									<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a>
									<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span>
										<span class="fileinput-exists">Cambiar</span>
										<input type="file" name="file" file-model="mp.fData.imagenBanner" />
									</span>
								</div>
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
  <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formBanner.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button>
</div>
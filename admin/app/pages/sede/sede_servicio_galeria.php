<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
    <form name="formGaleria" role="form" novalidate class="form-validation">
      <div class="row">
        <div class="col-sm-12">

					<div class="form-group">
						<label for="nombre" class="control-label minotaur-label">Agregar fotos a la galeria </label>
						<!-- <input type="file" class="file-upload" multiple> -->
            <!-- <input  type="file" class="filestyle" nv-file-select="" uploader="uploader" filestyle button-text="Fotos" icon-name="fa fa-inbox" accept="image/*" required multiple> -->
            <input type="file" nv-file-select="" uploader="uploader" multiple class="filestyle" filestyle button-text="Fotos" accept=".jpeg, .jpg, .png"/><br/>

					  <button class="btn btn-success " ng-disabled="formExcel.$invalid" ng-click="mp.aceptar()">Aceptar</button>
					</div>

				</div>
      </div>
    </form>
    <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-12" ng-repeat="item in mp.fData.galeria">
        <img ng-src="{{mp.dirThumbs + item.foto}}" class="full-width" alt="">
      </div>
    </div>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Cancelar</button>
  <!-- <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formSedeServ.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button> -->
</div>
<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
    <form name="formGaleria" role="form" novalidate class="form-validation">
      <div class="row">
        <div class="col-sm-12">
          <button class="btn btn-success" ng-click="mp.btnSubir()">Agregar fotos a la galeria</button>


          <div class="row" ng-show="mp.uploadBtn">
            <label class="col-md-12 control-label minotaur-label mt-md">Seleccione</label>
              <div class="form-group col-md-3">
                <input
                  type="file"
                  multiple
                  class="filestyle"
                  nv-file-select=""
                  uploader="uploader"
                  filestyle
                  button-text="Fotos"
                  icon-name="fa fa-inbox"
                  accept=".jpeg, .jpg, .png"
                />
              </div>

            <div class="form-group col-md-9" style="text-align: right;">
              <span >Tamaño maximo para Imagenes es de 10MB</span>

            </div>
            <div class="col-md-12" style="margin-bottom: 40px;background-color: #fbfbf2;padding: 35px 20px;">

              <p>Cantidad de archivos en cola: {{ uploader.queue.length }}</p>

              <table class="table">
                <thead>
                  <tr>
                      <th width="50%">Nombre</th>
                      <th ng-show="uploader.isHTML5">Tamaño</th>
                      <th ng-show="uploader.isHTML5">Progreso</th>
                      <th>Estatus</th>
                      <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="item in uploader.queue">
                    <td><strong>{{ item.file.name }}</strong></td>
                    <td ng-show="uploader.isHTML5" nowrap>{{ item.file.size/1024/1024|number:2 }} MB</td>
                    <td ng-show="uploader.isHTML5">
                        <div class="progress" style="margin-bottom: 0;">
                            <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                        <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                        <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                    </td>
                    <td nowrap>
                        <button type="button" class="btn btn-success btn-xs" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                            <span class="glyphicon glyphicon-upload"></span> Subir
                        </button>
                        <button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                            <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
                            <span class="glyphicon glyphicon-trash"></span> Eliminar
                        </button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div>
                  <div>
                      <div class="progress">
                          <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
                      </div>
                  </div>
                  <button type="button" class="btn btn-success btn-s" ng-click="mp.subirTodo();" ng-disabled="!uploader.getNotUploadedItems().length">
                      <span class="glyphicon glyphicon-upload"></span> Subir Todo
                  </button>
                  <button type="button" class="btn btn-warning btn-s" ng-click="uploader.cancelAll()" ng-disabled="!uploader.isUploading">
                      <span class="glyphicon glyphicon-ban-circle"></span> Cancelar Todo
                  </button>
                  <button type="button" class="btn btn-danger btn-s" ng-click="uploader.clearQueue()" ng-disabled="!uploader.queue.length">
                      <span class="glyphicon glyphicon-trash"></span> Eliminar Todo
                  </button>
              </div>

            </div>
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
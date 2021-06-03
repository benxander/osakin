<div class="modal-header">
  <h4 class="modal-title">{{mp.modalTitle}}</h4>
</div>
<div class="modal-body">
	<section class="tile-body p-0">
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<div>Elija un servicio para agregar a la sede</div>
				<div id="grid-servNoAgre" ui-grid="mp.gridServNoAgrOptions" ui-grid-auto-resize class="grid table-responsive clear"></div>
			</div>

			<div class="col-md-6 col-sm-12">
				<div>Servicios agregados a la sede</div>
				<div id="grid-servAgre" ui-grid="mp.gridServAgrOptions" ui-grid-auto-resize class="grid table-responsive clear"></div>
			</div>
		</div>
	</section>
</div>
<div class="modal-footer">
  <button class="btn btn-warning btn-ef btn-ef-4 btn-ef-4c" ng-click="mp.cancel()"><i class="fa fa-arrow-left"></i> Salir</button>
  <!-- <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ng-disabled="formSede.$invalid" ng-click="mp.aceptar()"><i class="fa fa-arrow-right"></i> Guardar</button> -->
</div>
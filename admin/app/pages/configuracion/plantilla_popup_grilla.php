<div class="modal-header modal-header-danger" ng-init="focusIndex = 0;">
	<h4 class="modal-title"> Selección de {{ mp.titulo }} </h4>
</div>
<div class="modal-body row">

	<div class="col-md-12" >
		<div ui-grid="mp.gridComboOptions" ui-grid-selection class="grid table-responsive">
			<div class="waterMarkEmptyData" ng-show="!mp.gridComboOptions.data.length">No hay Usuarios disponibles</div>
		</div>
    </div>
</div>
<div class="modal-footer">
</div>
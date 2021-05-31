(function () {
	'use strict';
	angular
		.module('minotaur')
		.controller('SitioWebController', SitioWebController)
		.service('SitioWebServices', SitioWebServices);

	/** @ngInject */
	function SitioWebController(
		$scope,
		$uibModal,
		pinesNotifications,
		SweetAlert,
		SitioWebServices
	) {
		var vm = this;

		vm.fArr = {};
		vm.dirUploads = angular.patchURLCI + "uploads/";


		vm.mySelectionGrid = [];
		vm.gridOptions = {
			enableFiltering: false,
			enableSorting: false,
			useExternalPagination: false,
			useExternalSorting: false,
			useExternalFiltering: false,
			enableRowSelection: true,
			enableRowHeaderSelection: false,
			enableFullRowSelection: true,
			multiSelect: false,
			appScopeProvider: vm
		}
		vm.gridOptions.columnDefs = [
			{ field: 'id', name: 'id', displayName: 'ID', width: 80, enableFiltering: false },
			{ field: 'tipo', name: 'tipo', displayName: 'TIPO', width: 250 },
			{ field: 'elemento', name: 'elemento', displayName: 'ELEMENTO' },
			{ field: 'valor', name: 'valor', displayName: 'VALOR' },

			{
				field: 'accion', name: 'accion', displayName: 'ACCION', width: 80, enableFiltering: false,
				cellTemplate: '<label class="btn text-primary" ng-click="grid.appScope.btnEditar(row);$event.stopPropagation();" tooltip-placement="left" uib-tooltip="EDITAR"> <i class="fa fa-edit"></i> </label>'
			},

		];
		vm.gridOptions.onRegisterApi = function (gridApi) {
			vm.gridApi = gridApi;
			gridApi.selection.on.rowSelectionChanged($scope, function (row) {
				vm.mySelectionGrid = gridApi.selection.getSelectedRows();
			});

		}

		vm.getPaginationServerSide = function () {
			// var datos = {
			// 	idioma: localStorage.getItem('language')
			// }
			SitioWebServices.sListarSitioWeb().then(function (rpta) {
				vm.gridOptions.data = rpta.datos;
				// vm.gridOptions.totalItems = rpta.paginate.totalRows;
				vm.mySelectionGrid = [];
			});
		}
		vm.getPaginationServerSide();
		// mantenimiento
		vm.btnEditar = row => {
			$uibModal.open({
				templateUrl: 'app/pages/sitio-web/sitioWeb_formview.php',
				controllerAs: 'mp',
				size: 'lg',
				backdropClass: 'splash splash-2 splash-info splash-ef-12',
				windowClass: 'splash splash-2 splash-ef-12',
				backdrop: 'static',
				keyboard: true,
				controller: function ($scope, $uibModalInstance, arrToModal) {
					var vm = this;
					vm.fData = angular.copy(row.entity);
					vm.modoEdicion = true;
					vm.getPaginationServerSide = arrToModal.getPaginationServerSide;
					vm.fArr = arrToModal.fArr;
					vm.dirUploads = arrToModal.dirUploads;
					console.log('dirUploads', vm.dirUploads);


					vm.modalTitle = 'Edición de Elemento de Sitio Web';
					// BOTONES
					vm.aceptar = function () {
						SitioWebServices.sEditarSitioWeb(vm.fData).then(function (rpta) {
							if (rpta.flag == 1) {
								$uibModalInstance.close(vm.fData);
								vm.getPaginationServerSide();
								var pTitle = 'OK!';
								var pType = 'success';
							} else if (rpta.flag == 0) {
								var pTitle = 'Advertencia!';
								var pType = 'warning';
							} else {
								alert('Ocurrió un error');
							}
							pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 3000 });
						});
					};
					vm.cancel = function () {
						$uibModalInstance.close();
					};
				},
				resolve: {
					arrToModal: function () {
						return {
							getPaginationServerSide: vm.getPaginationServerSide,
							fArr: vm.fArr,
							dirUploads: vm.dirUploads
						}
					}
				}
			});
		}



	}
	function SitioWebServices($http, $q, handle) {
		return ({
			sListarSitioWeb: sListarSitioWeb,
			sEditarSitioWeb: sEditarSitioWeb,

		});
		function sListarSitioWeb(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Configuracion/listarSitioWeb",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}

		function sEditarSitioWeb(datos) {
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Configuracion/editarSitioWeb",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}


	}
})();
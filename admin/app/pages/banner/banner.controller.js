(function () {
	'use strict';

	angular
		.module('minotaur')
		.controller('BannerController', BannerController)
		.service('BannerServices', BannerServices);

	/** @ngInject */
	function BannerController(
		$scope,
		$uibModal,
		SweetAlert,
		BannerServices,
		pinesNotifications
	) {

		var vm = this;
		
		vm.fArr = {};

		// Lista Zonas
		
		vm.fArr.listaZonas = [
			{
				id: 'cabecera',
				descripcion: 'CABECERA'
			},
			{
				id: 'lateral',
				descripcion: 'LATERAL'
			}

		];


		// GRILLA PRINCIPAL

		vm.mySelectionGrid = [];
		vm.gridOptions = {
			paginationPageSizes: [10, 50, 100, 500, 1000],
			paginationPageSize: 10,
			rowHeight: 32,
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
			{ field: 'idbanner', name: 'idbanner', displayName: 'ID', width: 80, enableFiltering: false },
			{ field: 'titulo', name: 'titulo', displayName: 'TITULO' },
			{ field: 'zona', name: 'zona', displayName: 'ZONA' },
			{
				field: 'accion', name: 'accion', displayName: 'ACCIONES', width: 120, enableFiltering: false, enableColumnMenu: false,
				cellTemplate:
					'<label class="btn text-primary" ng-click="grid.appScope.btnEditar(row);$event.stopPropagation();">' +
					'<i class="fa fa-edit" tooltip-placement="left" uib-tooltip="EDITAR"></i> </label>' +
					
					'<label class="btn text-red" ng-click="grid.appScope.btnAnular(row);$event.stopPropagation();">' +
					'<i class="fa fa-trash" tooltip-placement="left" uib-tooltip="ELIMINAR!"></i> </label>'
			},

		];
		vm.gridOptions.onRegisterApi = function (gridApi) {
			vm.gridApi = gridApi;
			gridApi.selection.on.rowSelectionChanged($scope, function (row) {
				vm.mySelectionGrid = gridApi.selection.getSelectedRows();
			});
			gridApi.selection.on.rowSelectionChangedBatch($scope, function (rows) {
				vm.mySelectionGrid = gridApi.selection.getSelectedRows();
			});

		}
		vm.getPaginationServerSide = () => {
			var datos = {
				idioma : localStorage.getItem('language')
			}
			BannerServices.sListarBanners(datos).then(rpta => {
				vm.gridOptions.data = rpta.datos;
				vm.mySelectionGrid = [];
			});
		}
		vm.getPaginationServerSide();
		/*---------- NUEVO BANNER--------*/
		vm.btnNuevo = () => {
			$uibModal.open({
				templateUrl: 'app/pages/banner/banner_formview.php',
				controllerAs: 'mp',
				size: 'lg',
				backdropClass: 'splash splash-2 splash-ef-12',
				windowClass: 'splash splash-2 splash-ef-12',
				backdrop: 'static',
				keyboard: false,
				controller: function ($scope, $uibModalInstance, arrToModal) {
					console.log('$scope', $scope);
					var vm = this;
					vm.fData = {};
					
					vm.getPaginationServerSide = arrToModal.getPaginationServerSide;
					vm.fArr = arrToModal.fArr;
          			vm.fData.zona = vm.fArr.listaZonas[0];

					vm.modalTitle = 'Registro de Banner';

					// BOTONES
					vm.aceptar = () => {
						vm.fData.idioma = localStorage.getItem('language');
						BannerServices.sRegistrarBanner(vm.fData).then(rpta => {
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
					vm.cancel = () => {
						$uibModalInstance.close();
					};
				},
				resolve: {
					arrToModal: () => {
						return {
							getPaginationServerSide: vm.getPaginationServerSide,
							verPopupListaUsuarios: vm.verPopupListaUsuarios,
							fArr: vm.fArr,
						}
					}
				}
			});
		}
		/*-------- BOTON DE EDICION ----*/
		vm.btnEditar = row => {//datos personales
			$uibModal.open({
				templateUrl: 'app/pages/banner/banner_formview.php',
				controllerAs: 'mp',
				size: 'lg',
				backdropClass: 'splash splash-2 splash-ef-12',
				windowClass: 'splash splash-2 splash-ef-12',
				backdrop: 'static',
				keyboard: false,
				controller: function ($scope, $uibModalInstance, arrToModal) {
					var vm = this;
					vm.fData = {};
					vm.fData = angular.copy(arrToModal.seleccion);

					vm.modoEdicion = true;
					vm.getPaginationServerSide = arrToModal.getPaginationServerSide;


					vm.modalTitle = 'Edición de Banner';

					vm.aceptar = () => {
						// console.log('edicion...', vm.fData);
						vm.fData.idioma = localStorage.getItem('language');
						BannerServices.sEditarBanner(vm.fData).then(rpta => {
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
					vm.cancel = () => {
						$uibModalInstance.close();
					};
				},
				resolve: {
					arrToModal: function () {
						return {
							getPaginationServerSide: vm.getPaginationServerSide,
							verPopupListaUsuarios: vm.verPopupListaUsuarios,

							seleccion: row.entity
						}
					}
				}
			});
		}
		vm.btnAnular = row => {
			SweetAlert.swal(
				{
					title: "Atención!!!",
					text: "¿Realmente desea eliminar este item?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#fa2d48",
					confirmButtonText: "Si, Eliminar!",
					cancelButtonText: "No, Cancelar!",
					closeOnConfirm: true,
					closeOnCancel: false
				},
				isConfirm => {
					if (isConfirm) {

						BannerServices.sAnularBanner(row.entity).then(rpta => {
							if (rpta.flag == 1) {
								pTitle = 'OK!';
								pType = 'success';

							} else if (rpta.flag == 0) {
								var pTitle = 'Error!';
								var pType = 'danger';
							} else {
								alert('Error inesperado.');
							}
							pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 1000 });
							vm.getPaginationServerSide();
						});
					} else {
						SweetAlert.swal('Cancelado', 'La operación ha sido cancelada', 'warning');
					}
				}
			);
		}


	}
	function BannerServices($http, $q, handle) {
		return ({
			sListarBanners: sListarBanners,
			sListarBannersCbo: sListarBannersCbo,
			sRegistrarBanner: sRegistrarBanner,
			sEditarBanner: sEditarBanner,
			sAnularBanner: sAnularBanner,
		});
		function sListarBanners(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Banner/listarBanners",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sListarBannersCbo(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Banner/listarBanner_cbo",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sRegistrarBanner(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Banner/registrarBanner",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sEditarBanner(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Banner/editarBanner",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sAnularBanner(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Banner/anularBanner",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
	}
})();

(function () {
	'use strict';

	angular
		.module('minotaur')
		.controller('ServicioController', ServicioController)
		.service('ServicioServices', ServicioServices);

	/** @ngInject */
	function ServicioController(
		$scope,
		$uibModal,
		SweetAlert,
		ServicioServices,
		pinesNotifications
	) {

		var vm = this;
		vm.selectedItem = {};
		vm.options = {};

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
			{ field: 'idservicio', name: 'idservicio', displayName: 'ID', width: 80, enableFiltering: false },
			{ field: 'nombre', name: 'nombre', displayName: 'SERVICIO' },

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
			ServicioServices.sListarServicios().then(rpta => {
				vm.gridOptions.data = rpta.datos;
				vm.mySelectionGrid = [];
			});
		}
		vm.getPaginationServerSide();
		/*---------- NUEVO SERVICIO--------*/
		vm.btnNuevo = () => {
			$uibModal.open({
				templateUrl: 'app/pages/servicio/servicio_formview.php',
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
					vm.modoEdicion = false;
					vm.getPaginationServerSide = arrToModal.getPaginationServerSide;
					vm.fotoCrop = false;
					vm.modalTitle = 'Registro de Servicio';

					// BOTONES
					vm.aceptar = () => {
						vm.fData.idioma = localStorage.getItem('language');
						ServicioServices.sRegistrarServicio(vm.fData).then(rpta => {
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
		/*-------- BOTONES DE EDICION ----*/
		vm.btnEditar = row => {//datos personales
			$uibModal.open({
				templateUrl: 'app/pages/servicio/servicio_formview.php',
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


					vm.modalTitle = 'Edición de Servicio';

					vm.aceptar = () => {
						// console.log('edicion...', vm.fData);
						vm.fData.idioma = localStorage.getItem('language');
						ServicioServices.sEditarServicio(vm.fData).then(rpta => {
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

						ServicioServices.sAnularServicio(row.entity).then(rpta => {
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
	function ServicioServices($http, $q, handle) {
		return ({
			sListarServicios: sListarServicios,
			sListarServiciosCbo: sListarServiciosCbo,
			sRegistrarServicio: sRegistrarServicio,
			sEditarServicio: sEditarServicio,
			sAnularServicio: sAnularServicio,
			sListarServiciosAgregados: sListarServiciosAgregados,
			sListarServiciosNoAgregados: sListarServiciosNoAgregados
		});
		function sListarServicios(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Servicio/listarServicios",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sListarServiciosCbo(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Servicio/listarServicio_cbo",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sRegistrarServicio(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Servicio/registrarServicio",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sEditarServicio(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Servicio/editarServicio",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sAnularServicio(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Servicio/anularServicio",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sListarServiciosAgregados(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Servicio/listarServiciosAgregados",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sListarServiciosNoAgregados(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Servicio/listarServiciosNoAgregados",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
	}
})();

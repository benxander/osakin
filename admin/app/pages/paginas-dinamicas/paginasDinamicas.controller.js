(function () {
	'use strict';
	angular
		.module('minotaur')
		.controller('PaginasDinamicasController', PaginasDinamicasController)
		.service('PaginasDnamicasServices', PaginasDnamicasServices);

	/** @ngInject */
	function PaginasDinamicasController(
		$scope,
		$uibModal,
		pinesNotifications,
		SweetAlert,
		PaginasDnamicasServices
	) {
		var vm = this;

		vm.fArr = {};

		// Lista grupos
		// PaginasDnamicasServices.sListarGrupoCbo().then(function (rpta) {
		// 	vm.fArr.listaGrupos = rpta.datos;
		// });

		// Grilla principal

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
			{ field: 'idpaginadinamica', name: 'idpaginadinamica', displayName: 'ID', width: 80, enableFiltering: false },
			{ field: 'nombre', name: 'nombre', displayName: 'NOMBRE' },
			{ field: 'segmento_amigable', name: 'segmento_amigable', displayName: 'SEGMENTO AMIGABLE', width: 250 },

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
			var datos = {
				idioma: localStorage.getItem('language')
			}
			PaginasDnamicasServices.sListarPaginasDinamicas(datos).then(function (rpta) {
				vm.gridOptions.data = rpta.datos;
				// vm.gridOptions.totalItems = rpta.paginate.totalRows;
				vm.mySelectionGrid = [];
			});
		}
		vm.getPaginationServerSide();
		// mantenimiento
		vm.btnEditar = function (row) {
			$uibModal.open({
				templateUrl: 'app/pages/paginas-dinamicas/paginas_formview.php',
				controllerAs: 'mp',
				size: 'lg',
				backdropClass: 'splash splash-2 splash-info splash-ef-12',
				windowClass: 'splash splash-2 splash-ef-12',
				backdrop: 'static',
				keyboard: true,
				scope: $scope,
				controller: function ($scope, $uibModalInstance, arrToModal) {
					var vm = this;
					vm.fData = angular.copy(row.entity);
					vm.modoEdicion = true;
					vm.getPaginationServerSide = arrToModal.getPaginationServerSide;
					vm.fArr = arrToModal.fArr;

					vm.modalTitle = 'Edición de Página dinámica';
					// BOTONES
					vm.aceptar = function () {
						PaginasDnamicasServices.sEditarPaginaDinamica(vm.fData).then(function (rpta) {
							if (rpta.flag == 1) {
								$uibModalInstance.close();
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
							fArr: vm.fArr
						}
					}
				}
			});
		}

		vm.btnAnular = function (row) {
			SweetAlert.swal(
				{
					title: "Confirmación?",
					text: "¿Realmente desea eliminar la página dinámica?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#038dcc",
					confirmButtonText: "Si, Generar!",
					cancelButtonText: "No, Cancelar!",
					closeOnConfirm: true,
					closeOnCancel: false
				},
				function (isConfirm) {
					if (isConfirm) {
						vm.anularPaginaDinamica(row.entity);
					} else {
						SweetAlert.swal("Cancelado", "La operación ha sido cancelada", "error");
					}
				});
		}
		vm.anularPaginaDinamica = function (row) {
			PaginasDnamicasServices.sAnularPaginaDinamica(row).then(function (rpta) {
				if (rpta.flag == 1) {
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
		}
	}
	function PaginasDnamicasServices($http, $q, handle) {
		return ({
			sListarPaginasDinamicas: sListarPaginasDinamicas,
			sRegistrarPaginaDinamica: sRegistrarPaginaDinamica,
			sEditarPaginaDinamica: sEditarPaginaDinamica,
			sAnularPaginaDinamica: sAnularPaginaDinamica,

		});
		function sListarPaginasDinamicas(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Paginas_dinamicas/listarPaginasDinamicas",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sRegistrarPaginaDinamica(datos) {
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Paginas_dinamicas/registrarPaginaDinamica",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sEditarPaginaDinamica(datos) {
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Paginas_dinamicas/editarPaginaDinamica",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sAnularPaginaDinamica(datos) {
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Paginas_dinamicas/anularPaginaDinamica",
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}

	}
})();
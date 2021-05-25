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
		$location,
		uiGridConstants,
		alertify,
		$timeout,
		$document,
		SweetAlert,
		ServicioServices,
		UsuarioServices,
		pinesNotifications
	) {

		var vm = this;
		var params = $location.search();
		vm.selectedItem = {};
		vm.options = {};


		// vm.remove = function(scope) {
		//   scope.remove();
		// };

		// vm.toggle = function(scope) {
		//   scope.toggle();
		// };

		// vm.expandAll = function() {
		//   vm.$broadcast('angular-ui-tree:expand-all');
		// };

		// GRILLA PRINCIPAL

		vm.mySelectionGrid = [];
		vm.gridOptions = {
			paginationPageSizes: [10, 50, 100, 500, 1000],
			paginationPageSize: 10,
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
				cellTemplate: '<label class="btn text-primary" ng-click="grid.appScope.btnEditar(row);$event.stopPropagation();" tooltip-placement="left" uib-tooltip="EDITAR"> <i class="fa fa-edit"></i> </label>' +
					'<label class="btn text-red" ng-click="grid.appScope.btnAnular(row);$event.stopPropagation();"> <i class="fa fa-trash" tooltip-placement="left" uib-tooltip="ELIMINAR!"></i> </label>'
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
		vm.getPaginationServerSide = function () {
			ServicioServices.sListarServicios().then(function (rpta) {
				vm.gridOptions.data = rpta.datos;
				vm.mySelectionGrid = [];
			});
		}
		vm.getPaginationServerSide();
		/*---------- NUEVA EMPRESA--------*/
		vm.btnNuevo = function () {
			$uibModal.open({
				templateUrl: 'app/pages/servicio/servicio_formview.php',
				controllerAs: 'mp',
				size: 'lg',
				backdropClass: 'splash splash-2 splash-info splash-ef-12',
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
					// SUBIDA DE IMAGENES MEDIANTE IMAGE CROP
					vm.cargarImagen = function () {
						vm.fData.myImage = '';
						vm.fData.myCroppedImage = '';
						vm.cropType = 'square';
						vm.fotoCrop = true;
						var handleFileSelect = function (evt) {
							var file = evt.currentTarget.files[0];
							var reader = new FileReader();
							reader.onload = function (evt) {
								/* eslint-disable */
								$scope.$apply(function () {
									vm.fData.myImage = evt.target.result;
								});
								/* eslint-enable */
							};
							reader.readAsDataURL(file);
						};
						$timeout(function () { // lo pongo dentro de un timeout sino no funciona
							angular.element($document[0].querySelector('#fileInput')).on('change', handleFileSelect);
						});
					}
					// BOTONES
					vm.aceptar = function () {
						vm.fData.idioma = localStorage.getItem('language');
						ServicioServices.sRegistrarServicio(vm.fData).then(function (rpta) {
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
							verPopupListaUsuarios: vm.verPopupListaUsuarios,
							fArr: vm.fArr,
						}
					}
				}
			});
		}
		/*-------- BOTONES DE EDICION ----*/
		vm.btnEditar = function (row) {//datos personales
			$uibModal.open({
				templateUrl: 'app/pages/servicio/servicio_formview.php',
				controllerAs: 'mp',
				size: 'lg',
				backdropClass: 'splash splash-2 splash-info splash-ef-12',
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
					// SUBIDA DE IMAGENES MEDIANTE IMAGE CROP
					vm.cargarImagen = function () {
						vm.fData.myImage = '';
						vm.fData.myCroppedImage = '';
						vm.cropType = 'square';
						vm.fotoCrop = true;
						var handleFileSelect = function (evt) {
							var file = evt.currentTarget.files[0];
							var reader = new FileReader();
							reader.onload = function (evt) {
								/* eslint-disable */
								$scope.$apply(function () {
									vm.fData.myImage = evt.target.result;
								});
								/* eslint-enable */
							};
							reader.readAsDataURL(file);
						};
						$timeout(function () { // lo pongo dentro de un timeout sino no funciona
							angular.element($document[0].querySelector('#fileInput')).on('change', handleFileSelect);
						});
					}
					vm.aceptar = function () {
						// console.log('edicion...', vm.fData);
						vm.fData.idioma = localStorage.getItem('language');
						ServicioServices.sEditarServicio(vm.fData).then(function (rpta) {
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
						$uibModalInstance.dismiss('cancel');
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
		vm.btnAnular = function (row) {
			alertify.confirm("¿Realmente desea realizar la acción?", function (ev) {
				ev.preventDefault();
				ServicioServices.sAnularServicio(row.entity).then(function (rpta) {
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
			}, function (ev) {
				ev.preventDefault();
			});
		}

		vm.verPopupListaUsuarios = function (data) {
			console.log('Usuarios');
			$uibModal.open({
				templateUrl: 'app/pages/configuracion/plantilla_popup_grilla.php',
				controllerAs: 'mp',
				size: 'md',
				controller: function ($scope, $uibModalInstance, arrToModal) {
					var vm = this;
					vm.titulo = 'Usuario.';

					vm.fData = arrToModal.fData;
					console.log('fdata', vm.fData);
					vm.mySelectionComboGrid = [];
					vm.gridComboOptions = {
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
					vm.gridComboOptions.columnDefs = [
						{ field: 'id', displayName: 'ID', maxWidth: 80 },
						{ field: 'descripcion', displayName: 'DESCRIPCIÓN' }

					];
					vm.gridComboOptions.onRegisterApi = function (gridApi) {
						vm.gridApi = gridApi;
						gridApi.selection.on.rowSelectionChanged($scope, function (row) {
							vm.mySelectionComboGrid = gridApi.selection.getSelectedRows();
							vm.fData.idusuario = vm.mySelectionComboGrid[0].id;
							vm.fData.usuario = vm.mySelectionComboGrid[0].descripcion;

							$uibModalInstance.close();
						});
					}

					UsuarioServices.sListarUsuarioDisp().then(function (rpta) {
						vm.gridComboOptions.data = rpta.datos;
						vm.mySelectionComboGrid = [];
					});
				},
				resolve: {
					arrToModal: function () {
						return {
							fData: data
						}
					}
				}
			});
		}
		if (params.param == 'nueva-empresa') {
			vm.btnNuevo();
		}
	}
	function ServicioServices($http, $q, handle) {
		return ({
			sListarServicios: sListarServicios,
			sListarServiciosCbo: sListarServiciosCbo,
			sRegistrarServicio: sRegistrarServicio,
			sEditarServicio: sEditarServicio,
			sAnularServicio: sAnularServicio,
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
	}
})();

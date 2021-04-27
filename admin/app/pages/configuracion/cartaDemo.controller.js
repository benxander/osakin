(function() {
	'use strict';

	angular
		.module('minotaur')
		.controller('CartaDemoController', CartaDemoController)
		.service('CartaDemoServices', CartaDemoServices);

	/** @ngInject */
	function CartaDemoController($scope, uiGridConstants,
		$uibModal,
		alertify,
		SweetAlert,
		pinesNotifications,
		pageLoading,
		FileUploader,
		CartaDemoServices,
		ProductoServices,
		AparienciaServices) {
		var vm = this;
		vm.fData = {};
		vm.fArr = {};
		// Lista de colores
		AparienciaServices.sCargarColores().then(function (rpta) {
			vm.fArr.listaColores = rpta.datos;

		});

		// Lista de modelos
		vm.fArr.listaModelos = [
			{ id: 1, descripcion: 'MODELO 1' },
			{ id: 2, descripcion: 'MODELO 2' },
		];

		// GRILLA PRINCIPAL
		var paginationOptions = {
			pageNumber: 1,
			firstRow: 0,
			pageSize: 10,
			sort: uiGridConstants.DESC,
			sortName: null,
			search: null
		};
		vm.mySelectionGrid = [];
		vm.gridOptions = {
			paginationPageSizes: [10, 50, 100, 500, 1000],
			paginationPageSize: 10,
			enableFiltering: true,
			enableSorting: true,
			useExternalPagination: true,
			useExternalSorting: true,
			useExternalFiltering: true,
			enableRowSelection: true,
			enableRowHeaderSelection: false,
			enableFullRowSelection: true,
			multiSelect: false,
			appScopeProvider: vm
		}
		vm.gridOptions.columnDefs = [
			{ field: 'idempresa', name: 'idempresa', displayName: 'ID', width: 80, enableFiltering: false, sort: { direction: uiGridConstants.DESC } },
			{ field: 'razon_social', name: 'razon_social', displayName: 'NOMBRE CARTA' },
			{ field: 'nombre_negocio', name: 'nombre_negocio', displayName: 'SEGMENTO DE URL' },
			{ field: 'modelo_carta', name: 'modelo_carta', displayName: 'MODELO', width: 120 },
			{ field: 'color', name: 'color', displayName: 'COLOR', width: 150, },

			{
				field: 'accion', name: 'accion', displayName: 'ACCIONES', width: 130, enableFiltering: false, enableColumnMenu: false,
				cellTemplate:
					'<label class="btn btn-primary" ng-click="grid.appScope.btnEditar(row);$event.stopPropagation();" tooltip-placement="left" uib-tooltip="EDITAR"> <i class="fa fa-edit"></i> </label>' +
					'<label class="btn btn-warning" ng-click="grid.appScope.btnCategorias(row);$event.stopPropagation();" tooltip-placement="left" uib-tooltip="CATEGORIAS"> <i class="fa fa-coffee"></i> </label>' +
					'<label class="btn btn-success" ng-click="grid.appScope.btnProductos(row);$event.stopPropagation();"> <i class="fa fa-coffee" tooltip-placement="left" uib-tooltip="PRODUCTOS!"></i></label>'

			},

		];

		vm.getPaginationServerSide = function () {
			vm.datosGrid = {
				paginate: paginationOptions
			};
			CartaDemoServices.sListarCartasDemo(vm.datosGrid).then(function (rpta) {
				vm.gridOptions.data = rpta.datos;
				vm.gridOptions.totalItems = rpta.paginate.totalRows;
				vm.mySelectionGrid = [];

			});
		}
		vm.getPaginationServerSide();

		vm.btnEditar = function (row) {//datos personales
			$uibModal.open({
				templateUrl: 'app/pages/configuracion/carta_demo_formview.php',
				controllerAs: 'mp',
				size: 'lg',
				backdropClass: 'splash splash-2 splash-info splash-ef-12',
				windowClass: 'splash splash-2 splash-ef-12',
				backdrop: 'static',
				keyboard: false,
				controller: function ($scope, $uibModalInstance, arrToModal) {
					var vm = this;
					vm.fData = {};

					vm.fData = row.entity;
					vm.modalTitle = 'Edición de Carta ' + vm.fData.razon_social;

					vm.getPaginationServerSide = arrToModal.getPaginationServerSide;
					vm.fArr = arrToModal.fArr;

					// modelos
					var objIndex = vm.fArr.listaModelos.filter(function (obj) {
						return obj.id == vm.fData.idmodelo;
					}).shift();
					if (objIndex) {
						vm.fData.modeloObj = objIndex;
					} else {
						vm.fData.modeloObj = vm.fArr.listaModelos[0];
					}

					// colores
					var objIndex = vm.fArr.listaColores.filter(function (obj) {
						return obj.idcolor == vm.fData.idcolor;
					}).shift();
					if (objIndex) {
						vm.fData.colorObj = objIndex;
					} else {
						vm.fData.colorObj = vm.fArr.listaColores[0];
					}

					vm.aceptar = function () {
						// console.log('edicion...', vm.fData);

						$uibModalInstance.close(vm.fData);

						CartaDemoServices.sEditarCartaDemo(vm.fData).then(function (rpta) {
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
					};
					vm.cancel = function () {
						$uibModalInstance.dismiss('cancel');
					};
				},
				resolve: {
					arrToModal: function () {
						return {
							getPaginationServerSide: vm.getPaginationServerSide,
							fArr: vm.fArr,
							seleccion: row.entity
						}
					}
				}
			});
		}

		vm.btnCategorias = function(row){
			$uibModal.open({
				templateUrl: 'app/pages/configuracion/categorias_formview.php',
				controllerAs: 'mp',
				size: 'lg',
				backdropClass: 'splash splash-2 splash-info splash-ef-12',
				windowClass: 'splash splash-2 splash-ef-12',
				backdrop: 'static',
				keyboard: false,
				controller: function ($scope, $uibModalInstance, pinesNotifications) {
					var vm = this;
					vm.temporal = {}
					vm.fData = row.entity;
					vm.modalTitle = 'Categorias del ' + vm.fData.razon_social;

					vm.gridOptions = {
						useExternalPagination: false,
						useExternalSorting: false,
						useExternalFiltering : false,
						enableGridMenu: false,
						enableSelectAll: false,
						enableFiltering: false,
						enableSorting: false,
						appScopeProvider: vm,
						data: [],
					};

					vm.gridOptions.columnDefs = [
						{ field: 'idcategoria', name: 'idcategoria', displayName: 'ID', width: 80, enableFiltering: false, enableCellEdit: false, sort: { direction: uiGridConstants.DESC }},
						{ field: 'descripcion_cat', name: 'descripcion_cat', displayName: 'CATEGORIA'},
						{
							field: 'accion', name: 'accion', displayName: 'ACCIONES', width: 120, enableFiltering: false, enableColumnMenu: false, enableCellEdit: false,
							cellTemplate:
							'<label class="btn text-red" ng-click="grid.appScope.btnAnular(row);$event.stopPropagation();"> <i class="fa fa-trash" tooltip-placement="left" uib-tooltip="ELIMINAR!"></i> </label>'
						},
					]
					vm.gridOptions.onRegisterApi = function (gridApi) {
						gridApi.edit.on.afterCellEdit($scope, function (rowEntity, colDef, newValue, oldValue) {
							rowEntity.column = colDef.field;
							rowEntity.anteriorValor = oldValue;
							CartaDemoServices.sEditarCategoriaDemo(rowEntity).then(function (rpta) {
								if (rpta.flag == 1) {
									pTitle = 'OK!';
									pType = 'success';
								} else if (rpta.flag == 0) {
									var pTitle = 'Advertencia!';
									var pType = 'warning';
								} else {
									alert('Error inesperado');
								}
								vm.getPaginationServerSide();
								pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 3000 });
							});
							$scope.$apply();
						});
					}

					vm.getPaginationServerSide = function() {

						CartaDemoServices.sListarCategoriasDemo(vm.fData).then(function (rpta) {
						  vm.gridOptions.data = rpta.datos;

						});
					  }
					  vm.getPaginationServerSide();



					vm.agregarCat = function(){
						vm.temporal.idempresa = vm.fData.idempresa;

						CartaDemoServices.sAgregarCategoriaDemo(vm.temporal).then(function (rpta) {
							if(rpta.flag == 1){
								vm.temporal = {}
								vm.getPaginationServerSide();
								var pTitle = 'OK!';
								var pType = 'success';
							  }else if( rpta.flag == 0 ){
								var pTitle = 'Advertencia!';
								var pType = 'warning';
							  }else{
								alert('Ocurrió un error');
							  }
							  pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 3000 });

						  });

					}

					vm.btnAnular = function(row){
						alertify.confirm("¿Realmente desea realizar la acción?", function (ev) {
						  ev.preventDefault();
						  CartaDemoServices.sAnularCategoria(row.entity).then(function (rpta) {
							if(rpta.flag == 1){
							  vm.getPaginationServerSide();
							  var pTitle = 'OK!';
							  var pType = 'success';
							}else if( rpta.flag == 0 ){
							  var pTitle = 'Advertencia!';
							  var pType = 'warning';
							}else{
							  alert('Ocurrió un error');
							}
							pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 3000 });
						  });
						}, function(ev) {
							ev.preventDefault();
						});
					}

					vm.cancel = function () {
						$uibModalInstance.dismiss('cancel');
					};

				}


			})
		}

		vm.btnProductos = function(row) {
			$uibModal.open({
				templateUrl: 'app/pages/configuracion/productos_formview.php',
				controllerAs: 'mp',
				size: 'lg',
				backdropClass: 'splash splash-2 splash-info splash-ef-12',
				windowClass: 'splash splash-2 splash-ef-12',
				backdrop: 'static',
				keyboard: false,
				controller: function ($scope, $uibModalInstance, pinesNotifications) {
					var vm = this;
					vm.fData = row.entity;
					vm.modalTitle = 'Productos del ' + vm.fData.razon_social;

					vm.gridOptions = {
						useExternalPagination: false,
						useExternalSorting: false,
						useExternalFiltering: false,
						enableGridMenu: false,
						enableSelectAll: false,
						enableFiltering: false,
						enableSorting: false,
						appScopeProvider: vm,
						data: [],
					};

					vm.gridOptions.columnDefs = [
						{ field: 'idproducto', name: 'idproducto', displayName: 'ID PRODUCTO', width: 80, enableCellEdit: false, sort: { direction: uiGridConstants.DESC } },
						{ field: 'descripcion_cat', name: 'descripcion_cat', displayName: 'CATEGORIA', enableFiltering: false, enableColumnMenu: false, enableCellEdit: false },
						{ field: 'descripcion_pr', name: 'descripcion_pr', displayName: 'NOMBRE DE PRODUCTO' },
						{ field: 'alergenos', name: 'alergenos', displayName: 'ALERGENOS', width: 100},
						{ field: 'precio', name: 'precio', displayName: 'PRECIO', width: 90, enableFiltering: false, enableColumnMenu: false, },

						{
							field: 'accion', name: 'accion', displayName: 'ACCIONES', width: 90, enableFiltering: false, enableColumnMenu: false, enableCellEdit: false,
							cellTemplate:
								'<label class="btn text-red" ng-click="grid.appScope.btnAnular(row);$event.stopPropagation();"> <i class="fa fa-trash" tooltip-placement="left" uib-tooltip="ELIMINAR!"></i> </label>'
						},
					]

					vm.gridOptions.onRegisterApi = function (gridApi) {
						gridApi.edit.on.afterCellEdit($scope, function (rowEntity, colDef, newValue, oldValue) {
							rowEntity.column = colDef.field;
							rowEntity.anteriorValor = oldValue;
							CartaDemoServices.sEditarProductoDemo(rowEntity).then(function (rpta) {
								if (rpta.flag == 1) {
									pTitle = 'OK!';
									pType = 'success';
								} else if (rpta.flag == 0) {
									var pTitle = 'Advertencia!';
									var pType = 'warning';
								} else {
									alert('Error inesperado');
								}
								vm.getPaginationServerSide();
								pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 3000 });
							});
							$scope.$apply();
						});
					}

					vm.getPaginationServerSide = function () {

						CartaDemoServices.sListarProductosDemo(vm.fData).then(function (rpta) {
							vm.gridOptions.data = rpta.datos;

						});
					}
					vm.getPaginationServerSide();

					var uploader = $scope.uploader = new FileUploader({
						url: angular.patchURLCI + 'producto/upload_excel'
					});

					vm.aceptar = function () {
						console.log('uploader.queue', uploader.queue);
						uploader.queue[0].upload();
						pageLoading.start('Procesando...');
						uploader.onSuccessItem = function (fileItem, response, status, headers) {
							console.info('onSuccessItem', fileItem, response, status, headers);
							pageLoading.stop();
							if (response.flag == 1) {
								var pTitle = 'OK!';
								var pType = 'success';
								// $uibModalInstance.close();
								vm.getPaginationServerSide();
							} else if (response.flag == 0) {
								var pTitle = 'Advertencia!';
								var pType = 'warning';
							} else {
								alert('Ocurrió un error');
							}
							pinesNotifications.notify({ title: pTitle, text: response.message, type: pType, delay: 3000 });
						};
						uploader.onErrorItem = function (fileItem, response, status, headers) {
							console.info('onErrorItem', fileItem, response, status, headers);
							pageLoading.stop();
							if (response.flag == 1) {
								var pTitle = 'OK!';
								var pType = 'success';
								// $uibModalInstance.close();
								vm.getPaginationServerSide();
							} else if (response.flag == 0) {
								var pTitle = 'Advertencia!';
								var pType = 'warning';
							} else {
								alert('Ocurrió un error');
							}
							pinesNotifications.notify({ title: pTitle, text: response.message, type: pType, delay: 3000 });
						};
					};

					vm.btnAnular = function (row) {
						SweetAlert.swal(
							{
								title: "Confirmación?",
								text: "¿Realmente desea eliminar el producto?",
								type: "warning",
								showCancelButton: true,
								confirmButtonColor: "#038dcc",
								// confirmButtonText: "Si, Generar!",
								// cancelButtonText: "No, Cancelar!",
								closeOnConfirm: true,
								closeOnCancel: false
							},
							function (isConfirm) {
								if (isConfirm) {
									vm.anularProducto(row.entity);
								} else {
									SweetAlert.swal("Cancelado", "La operación ha sido cancelada", "error");
								}
							});
					}
					vm.anularProducto = function (row) {
						ProductoServices.sAnularProducto(row).then(function (rpta) {
							if (rpta.flag == 1) {
								vm.getPaginationServerSide(true);
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

					vm.cancel = function () {
						$uibModalInstance.dismiss('cancel');
					};
				}
			});
		}

	}

	function CartaDemoServices($http, $q, handle) {
		return({
			sListarCartasDemo: sListarCartasDemo,
			sEditarCartaDemo: sEditarCartaDemo,
			sListarCategoriasDemo: sListarCategoriasDemo,
			sListarProductosDemo: sListarProductosDemo,
			sEditarProductoDemo: sEditarProductoDemo,
			sAgregarCategoriaDemo: sAgregarCategoriaDemo,
			sEditarCategoriaDemo: sEditarCategoriaDemo,
			sAnularCategoria: sAnularCategoria
		});

		function sListarCartasDemo(pDatos) {
			var datos = pDatos || {}
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Empresa/listar_cartas_demo",
				data: datos
			});
			return(request.then(handle.success, handle.error));
		}
		function sEditarCartaDemo(pDatos) {
			var datos = pDatos || {}
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Empresa/editar_carta_demo",
				data: datos
			});
			return(request.then(handle.success, handle.error));
		}
		function sListarCategoriasDemo(pDatos) {
			var datos = pDatos || {}
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Empresa/listar_categorias_demo",
				data: datos
			});
			return(request.then(handle.success, handle.error));
		}
		function sListarProductosDemo(pDatos) {
			var datos = pDatos || {}
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Producto/listar_productos_demo",
				data: datos
			});
			return(request.then(handle.success, handle.error));
		}
		function sEditarProductoDemo(pDatos) {
			var datos = pDatos || {}
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Producto/editar_producto_demo",
				data: datos
			});
			return(request.then(handle.success, handle.error));
		}
		function sAgregarCategoriaDemo(pDatos) {
			var datos = pDatos || {}
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Empresa/agregar_categoria_demo",
				data: datos
			});
			return(request.then(handle.success, handle.error));
		}
		function sEditarCategoriaDemo(pDatos) {
			var datos = pDatos || {}
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Categoria/editar_categoria",
				data: datos
			});
			return(request.then(handle.success, handle.error));
		}
		function sAnularCategoria(pDatos) {
			var datos = pDatos || {}
			var request = $http({
				method: "post",
				url: angular.patchURLCI + "Categoria/anular_categoria",
				data: datos
			});
			return(request.then(handle.success, handle.error));
		}

	}
  })();
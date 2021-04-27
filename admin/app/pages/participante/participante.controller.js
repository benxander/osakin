(function () {
	'use strict';

	angular
		.module('minotaur')
		.controller('ParticipanteController', ParticipanteController)
		.service('ParticipanteServices', ParticipanteServices);

	/** @ngInject */
	function ParticipanteController(
		$scope,
		uiGridConstants,
		blockUI,
		pinesNotifications,
		ParticipanteServices
	) {
		var vm = this;

		vm.fArr = {};
		vm.filtro = {};

		// Grilla principal
		var paginationOptions = {
			pageNumber: 1,
			firstRow: 0,
			pageSize: 50,
			sort: uiGridConstants.DESC,
			sortName: null,
			search: null
		}
		vm.mySelectionGrid = [];
		vm.gridOptions = {
			paginationPageSizes: [50, 100, 500, 1000],
			paginationPageSize: 50,
			enableFiltering: true,
			enableSorting: true,
			useExternalPagination: true,
			useExternalSorting: true,
			useExternalFiltering: true,
			enableRowSelection: true,
			enableRowHeaderSelection: true,
			enableFullRowSelection: true,
			multiSelect: false,
			appScopeProvider: vm
		}
		vm.gridOptions.columnDefs = [
			{ field: 'idparticipante', name: 'idparticipante', displayName: 'ID', width: 80, sort: { direction: uiGridConstants.DESC } },
			{ field: 'nombres', name: 'nombres', displayName: 'NOMBRES', minWidth: 120 },
			{ field: 'apellidos', name: 'apellidos', displayName: 'APELLIDOS', minWidth: 120 },
			{ field: 'telefono', name: 'telefono', displayName: 'TELEFONO', width: 100 },
			{ field: 'email', name: 'email', displayName: 'EMAIL', minWidth: 220 },
			{ field: 'fecha', name: 'fecha', displayName: 'FEC DE REGISTRO', width: 130 },
			{ field: 'ip', name: 'ip', displayName: 'IP', width: 120 },
			{ field: 'codigo_postal', name: 'codigo_postal', displayName: 'COD POSTAL', width: 120 },
			{
				field: 'estado_obj', type: 'object', name: 'estado_pa', displayName: 'ESTADO', width: 120, enableFiltering: false, enableSorting: false, enableColumnMenus: false, enableColumnMenu: false,
				cellTemplate: '<div class="ui-grid-cell-contents">' +
					'<label style="box-shadow: 1px 1px 0 black; display: block;font-size: 12px;" class="label {{ COL_FIELD.claseLabel }} "> <i class="{{ COL_FIELD.claseIcon }}"></i> {{ COL_FIELD.labelText }}' +
					'</label></div>'
			},


		];
		vm.gridOptions.onRegisterApi = function (gridApi) {
			vm.gridApi = gridApi;
			gridApi.selection.on.rowSelectionChanged($scope, function (row) {
				vm.mySelectionGrid = gridApi.selection.getSelectedRows();
			});
			gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
				paginationOptions.pageNumber = newPage;
				paginationOptions.pageSize = pageSize;
				paginationOptions.firstRow = (paginationOptions.pageNumber - 1) * paginationOptions.pageSize;
				vm.getPaginationServerSide(true);
			});
			vm.gridApi.core.on.filterChanged($scope, function (grid, searchColumns) {
				var grid = this.grid;
				paginationOptions.search = true;
				paginationOptions.searchColumn = {
					'idparticipante': grid.columns[1].filters[0].term,
					'nombres': grid.columns[2].filters[0].term,
					'apellidos': grid.columns[3].filters[0].term,
					'telefono': grid.columns[4].filters[0].term,
					'email': grid.columns[5].filters[0].term,
					'fecha_registro': grid.columns[6].filters[0].term,
					'ip': grid.columns[7].filters[0].term,
					'codigo_postal': grid.columns[8].filters[0].term
				};
				vm.getPaginationServerSide(true);
			});
		}

		paginationOptions.sortName = vm.gridOptions.columnDefs[0].name;

		vm.getPaginationServerSide = function (loader) {
			if(loader){
				blockUI.start('Cargando datos...');
			}
			vm.datosGrid = {
				paginate: paginationOptions,
				data: vm.filtro
			};
			ParticipanteServices.sListarParticipantes(vm.datosGrid).then(function (rpta) {
				if(loader){
					blockUI.stop();
				}
				vm.gridOptions.data = rpta.datos;
				vm.gridOptions.totalItems = rpta.paginate.totalRows;
				vm.mySelectionGrid = [];
			});
		}

		vm.getPaginationServerSide(true);

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

		vm.btnReenviar = function(){
			console.log('seleccion', vm.mySelectionGrid[0]);
			blockUI.start('Reenviando, por favor espere...');
			ParticipanteServices.sReenviarCorreo(vm.mySelectionGrid[0]).then(function (rpta) {
				blockUI.stop();
				if (rpta.flag == 1) {
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

	function ParticipanteServices($http, $q, handle) {
		return ({
			sListarParticipantes: sListarParticipantes,
			sReenviarCorreo: sReenviarCorreo

		});

		function sListarParticipantes(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: 'post',
				url: angular.patchURLCI + 'Participante/listar_participantes',
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}
		function sReenviarCorreo(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: 'post',
				url: angular.patchURLCI + 'Main/reenviar_correo',
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}

	}
})();
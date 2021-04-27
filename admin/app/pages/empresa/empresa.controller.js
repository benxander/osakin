(function() {
  'use strict';

  angular
    .module('minotaur')
    .controller('EmpresaController', EmpresaController)
    .service('EmpresaServices', EmpresaServices);

  /** @ngInject */
  function EmpresaController(
    $scope,
    $uibModal,
    $location,
    uiGridConstants,
    alertify,
    SweetAlert,
    EmpresaServices,
    UsuarioServices,
    pinesNotifications
  ) {

    var vm = this;
    var params = $location.search();
    vm.selectedItem = {};
    vm.options = {};
    vm.fDemo = {};
    vm.fArr = {}; // contiene todos los arrays generados por las funciones

    vm.fArr.listaPlanes = [
      { id: 0, descripcion: '--Seleccione plan--' },
      { id: 1, descripcion: 'CARTA DIGITAL 1' },
      { id: 2, descripcion: 'CARTA DIGITAL 2' },
      { id: 3, descripcion: 'CARTA DIGITAL 3' }
    ];

    vm.fArr.listaTiposPago = [
      { id: 0, descripcion: '--Seleccione tipo pago--' },
      { id: 1, descripcion: 'MENSUAL' },
      { id: 2, descripcion: 'SEMESTRAL' },
      { id: 3, descripcion: 'ANUAL' }
    ];



    vm.remove = function(scope) {
      scope.remove();
    };

    vm.toggle = function(scope) {
      scope.toggle();
    };

    vm.expandAll = function() {
      vm.$broadcast('angular-ui-tree:expand-all');
    };

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
        useExternalFiltering : true,
        enableRowSelection: true,
        enableRowHeaderSelection: false,
        enableFullRowSelection: true,
        multiSelect: false,
        appScopeProvider: vm
      }
      vm.gridOptions.columnDefs = [
        { field: 'idempresa', name: 'idempresa', displayName: 'ID', width: 80, enableFiltering: false, sort: { direction: uiGridConstants.DESC }},
        { field: 'nombre_negocio', name:'nombre_negocio', displayName: 'NOMBRE NEGOCIO' },
        { field: 'usuario', name:'username', displayName: 'USUARIO', width: 120 },
        { field: 'telefono', name:'telefono', displayName: 'TELÉFONO', width: 150, },
        { field: 'descripcion_pl', name:'descripcion_pl', displayName: 'PLAN', minWidth: 150, width:150 },
        { field: 'descripcion_tp', name: 'descripcion_tp', displayName: 'TIPO PAGO', minWidth: 120, width: 120},
        {
          field: 'accion', name: 'accion', displayName: 'ACCIONES', width: 120, enableFiltering: false, enableColumnMenu: false,
          cellTemplate:'<label class="btn text-primary" ng-click="grid.appScope.btnEditar(row);$event.stopPropagation();" tooltip-placement="left" uib-tooltip="EDITAR"> <i class="fa fa-edit"></i> </label>'+
          '<label class="btn text-red" ng-click="grid.appScope.btnAnular(row);$event.stopPropagation();"> <i class="fa fa-trash" tooltip-placement="left" uib-tooltip="ELIMINAR!"></i> </label>'
         },

      ];
      vm.gridOptions.onRegisterApi = function(gridApi) {
        vm.gridApi = gridApi;
        gridApi.selection.on.rowSelectionChanged($scope,function(row){
          vm.mySelectionGrid = gridApi.selection.getSelectedRows();
        });
        gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
          vm.mySelectionGrid = gridApi.selection.getSelectedRows();
        });
        gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
          paginationOptions.pageNumber = newPage;
          paginationOptions.pageSize = pageSize;
          paginationOptions.firstRow = (paginationOptions.pageNumber - 1) * paginationOptions.pageSize;
          vm.getPaginationServerSide();
        });
        vm.gridApi.core.on.filterChanged( $scope, function(grid, searchColumns) {
          var grid = this.grid;
          paginationOptions.search = true;
          paginationOptions.searchColumn = {
            'nombre_negocio' : grid.columns[2].filters[0].term,
            'razon_social' : grid.columns[3].filters[0].term,
            'telefono' : grid.columns[4].filters[0].term,
            'contacto' : grid.columns[5].filters[0].term,
          };
          vm.getPaginationServerSide();
        });
      }
      paginationOptions.sortName = vm.gridOptions.columnDefs[0].name;
      vm.getPaginationServerSide = function() {
        vm.datosGrid = {
          paginate : paginationOptions
        };
        EmpresaServices.sListarEmpresas(vm.datosGrid).then(function (rpta) {
          vm.gridOptions.data = rpta.datos;
          vm.gridOptions.totalItems = rpta.paginate.totalRows;
          vm.mySelectionGrid = [];
        });
      }
      vm.getPaginationServerSide();
      /*---------- NUEVA EMPRESA--------*/
      vm.btnNuevo = function () {
        $uibModal.open({
          templateUrl: 'app/pages/empresa/empresa_formview.php',
          controllerAs: 'mp',
          size: 'lg',
          backdropClass: 'splash splash-2 splash-info splash-ef-12',
          windowClass: 'splash splash-2 splash-ef-12',
          backdrop: 'static',
          keyboard: false,
          controller: function($scope, $uibModalInstance, arrToModal ){
            console.log('$scope', $scope);
            var vm = this;
            vm.fData = {};
            vm.modoEdicion = false;
            vm.getPaginationServerSide = arrToModal.getPaginationServerSide;
            vm.fArr = arrToModal.fArr;
            vm.verPopupListaUsuarios = arrToModal.verPopupListaUsuarios;
            vm.fData.plan = vm.fArr.listaPlanes[0];
            vm.fData.tipo_pago = vm.fArr.listaTiposPago[0];
            vm.modalTitle = 'Registro de Empresas';
            // BOTONES
            vm.aceptar = function () {
              EmpresaServices.sRegistrarEmpresa(vm.fData).then(function (rpta) {
                if(rpta.flag == 1){
                  $uibModalInstance.close(vm.fData);
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
            };
            vm.cancel = function () {
              $uibModalInstance.close();
            };
          },
          resolve: {
            arrToModal: function() {
              return {
                getPaginationServerSide : vm.getPaginationServerSide,
                verPopupListaUsuarios : vm.verPopupListaUsuarios,
                fArr: vm.fArr,
              }
            }
          }
        });
      }
      /*-------- BOTONES DE EDICION ----*/
      vm.btnEditar = function(row){//datos personales
        $uibModal.open({
          templateUrl: 'app/pages/empresa/empresa_formview.php',
          controllerAs: 'mp',
          size: 'lg',
          backdropClass: 'splash splash-2 splash-info splash-ef-12',
          windowClass: 'splash splash-2 splash-ef-12',
          backdrop: 'static',
          keyboard: false,
          controller: function($scope, $uibModalInstance, arrToModal ){
            var vm = this;
            vm.fData = {};
            vm.fData = arrToModal.seleccion;
            // console.log("row",vm.fData);
            vm.modoEdicion = true;
            vm.getPaginationServerSide = arrToModal.getPaginationServerSide;
            vm.fArr = arrToModal.fArr;
            vm.verPopupListaUsuarios = arrToModal.verPopupListaUsuarios;
            // planes
            var objIndex = vm.fArr.listaPlanes.filter(function (obj) {
              return obj.id == vm.fData.idplan;
            }).shift();
            if (objIndex) {
              vm.fData.plan = objIndex;
            } else {
              vm.fData.plan = vm.fArr.listaPlanes[0];
            }
            // tipo pago
            objIndex = vm.fArr.listaTiposPago.filter(function (obj) {
              return obj.id == vm.fData.idtipopago;
            }).shift();
            if (objIndex) {
              vm.fData.tipo_pago = objIndex;
            } else {
              vm.fData.tipo_pago = vm.fArr.listaTiposPago[0];
            }

            vm.modalTitle = 'Edición de Empresas';
            vm.aceptar = function () {
              // console.log('edicion...', vm.fData);
              $uibModalInstance.close(vm.fData);
              EmpresaServices.sEditarEmpresa(vm.fData).then(function (rpta) {
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
            };
            vm.cancel = function () {
              $uibModalInstance.dismiss('cancel');
            };
          },
          resolve: {
            arrToModal: function() {
              return {
                getPaginationServerSide : vm.getPaginationServerSide,
                verPopupListaUsuarios: vm.verPopupListaUsuarios,
                fArr: vm.fArr,
                seleccion : row.entity
              }
            }
          }
        });
      }
      vm.btnAnular = function(row){
        alertify.confirm("¿Realmente desea realizar la acción?", function (ev) {
          ev.preventDefault();
          EmpresaServices.sAnularEmpresa(row.entity).then(function (rpta) {
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

      vm.verPopupListaUsuarios = function(data){
        console.log('Usuarios');
        $uibModal.open({
          templateUrl: 'app/pages/configuracion/plantilla_popup_grilla.php',
          controllerAs: 'mp',
          size: 'md',
          controller: function($scope,$uibModalInstance, arrToModal){
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
          resolve:{
            arrToModal: function(){
              return {
                fData : data
              }
            }
          }
        });
      }
      if(params.param == 'nueva-empresa'){
        vm.btnNuevo();
      }
  }
  function EmpresaServices($http, $q, handle) {
    return({
        sListarEmpresas: sListarEmpresas,
        sListarEmpresaCbo: sListarEmpresaCbo,
        sRegistrarEmpresa: sRegistrarEmpresa,
        sEditarEmpresa: sEditarEmpresa,
        sAnularEmpresa: sAnularEmpresa,
    });
    function sListarEmpresas(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url :  angular.patchURLCI + "Empresa/listar_empresas",
            data : datos
      });
      return (request.then( handle.success,handle.error ));
    }
    function sListarEmpresaCbo(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url :  angular.patchURLCI + "Empresa/listar_empresa_cbo",
            data : datos
      });
      return (request.then( handle.success,handle.error ));
    }
    function sRegistrarEmpresa(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url : angular.patchURLCI + "Empresa/registrar_empresa",
            data : datos
      });
      return (request.then(handle.success,handle.error));
    }
    function sEditarEmpresa(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url : angular.patchURLCI + "Empresa/editar_empresa",
            data : datos
      });
      return (request.then(handle.success,handle.error));
    }
    function sAnularEmpresa(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url : angular.patchURLCI + "Empresa/anular_empresa",
            data : datos
      });
      return (request.then(handle.success,handle.error));
    }
  }
})();

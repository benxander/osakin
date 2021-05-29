(function() {
  'use strict';

  angular
    .module('minotaur')
    .controller('SedeController', SedeController)
    .service('SedeServices', SedeServices);

  /** @ngInject */
  function SedeController(
    $scope,
    $uibModal,
    $location,
    uiGridConstants,
    alertify,
    $timeout,
    $document,
    SweetAlert,
    SedeServices,
    UsuarioServices,
    pinesNotifications
  ) {

    var vm = this;
    var params = $location.search();
    vm.selectedItem = {};
    vm.options = {};
    vm.verServicios = false;
    vm.serv = {}
    vm.dirServicios = angular.patchURLCI + "uploads/servicios/";


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
      useExternalFiltering : false,
      enableRowSelection: false,
      enableRowHeaderSelection: false,
      enableFullRowSelection: false,
      multiSelect: false,
      appScopeProvider: vm
    }
    vm.gridOptions.columnDefs = [
      { field: 'idsede', name: 'idsede', displayName: 'ID', width: 80, enableFiltering: false},
      { field: 'descripcion_se', name:'descripcion_se', displayName: 'SEDE' },
      { field: 'telefono', name:'telefono', displayName: 'TELÉFONO', width: 150, },
      { field: 'email', name:'email', displayName: 'EMAIL', minWidth: 200, width:200 },

      {
        field: 'accion', name: 'accion', displayName: 'ACCIONES', width: 180, enableFiltering: false, enableColumnMenu: false,
        cellTemplate:
        '<label class="btn btn-primary" ng-click="grid.appScope.btnServicios(row)">Servicios</label>' +
        '<label class="btn text-primary" ng-click="grid.appScope.btnEditar(row);$event.stopPropagation();" tooltip-placement="left" uib-tooltip="EDITAR"> <i class="fa fa-edit"></i> </label>'+
        '<label class="btn text-red" ng-click="grid.appScope.btnAnular(row);$event.stopPropagation();"> <i class="fa fa-trash" tooltip-placement="left" uib-tooltip="ELIMINAR!"></i> </label>'
        },

    ];
    // vm.gridOptions.onRegisterApi = function(gridApi) {
    //   vm.gridApi = gridApi;
    //   gridApi.selection.on.rowSelectionChanged($scope,function(row){
    //     vm.mySelectionGrid = gridApi.selection.getSelectedRows();
    //   });
    //   gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
    //     vm.mySelectionGrid = gridApi.selection.getSelectedRows();
    //   });

    // }
    vm.getPaginationServerSide = function() {
      var paramDatos = {
        idioma: localStorage.getItem('language')
      }
      SedeServices.sListarSedes(paramDatos).then(function (rpta) {
        vm.gridOptions.data = rpta.datos;
        vm.mySelectionGrid = [];
      });
    }
    vm.getPaginationServerSide();
    /*---------- NUEVA EMPRESA--------*/
    vm.btnNuevo = function () {
      $uibModal.open({
        templateUrl: 'app/pages/sede/sede_formview.php',
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
          vm.fotoCrop = false;
          vm.modalTitle = 'Registro de Sede';
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
            SedeServices.sRegistrarSede(vm.fData).then(function (rpta) {
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
        templateUrl: 'app/pages/sede/sede_formview.php',
        controllerAs: 'mp',
        size: 'lg',
        backdropClass: 'splash splash-2 splash-info splash-ef-12',
        windowClass: 'splash splash-2 splash-ef-12',
        backdrop: 'static',
        keyboard: false,
        controller: function($scope, $uibModalInstance, arrToModal ){
          var vm = this;
          vm.fData = {};
          vm.fData = angular.copy(arrToModal.seleccion);

          vm.modoEdicion = true;
          vm.getPaginationServerSide = arrToModal.getPaginationServerSide;


          vm.modalTitle = 'Edición de Sede';
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
            SedeServices.sEditarSede(vm.fData).then(function (rpta) {
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
            $uibModalInstance.dismiss('cancel');
          };
        },
        resolve: {
          arrToModal: function() {
            return {
              getPaginationServerSide : vm.getPaginationServerSide,
              verPopupListaUsuarios: vm.verPopupListaUsuarios,

              seleccion : row.entity
            }
          }
        }
      });
    }
    vm.btnAnular = function(row){
      alertify.confirm("¿Realmente desea realizar la acción?", function (ev) {
        ev.preventDefault();
        SedeServices.sAnularSede(row.entity).then(function (rpta) {
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

    vm.btnServicios = (row) => {
      console.log('cambio a serv', row);
      vm.verServicios = true;
      vm.serv.fData = row.entity;

      vm.gridServOptions = {
        paginationPageSizes: [10, 50, 100, 500, 1000],
        paginationPageSize: 10,
        // enableFiltering: false,
        // enableSorting: false,
        // useExternalPagination: false,
        // useExternalSorting: false,
        // useExternalFiltering: false,
        // enableRowSelection: false,
        // enableRowHeaderSelection: false,
        // enableFullRowSelection: false,
        // multiSelect: false,
        appScopeProvider: vm
      }
      vm.gridServOptions.columnDefs = [
        { field: 'id', name: 'idsedeservicio', displayName: 'ID', width: 80, enableFiltering: false },
        { field: 'servicio', name: 'descripcion_se', displayName: 'SERVICIO', minWidth: 250 },
        { field: 'titulo', name: 'titulo', displayName: 'TITULO', minWidth: 250, },

        {
          field: 'accion', name: 'accion', displayName: 'ACCIONES', width: 180, enableFiltering: false, enableColumnMenu: false,
          cellTemplate:
            '<label class="btn text-primary" ng-click="grid.appScope.btnEditarServicio(row);$event.stopPropagation();" tooltip-placement="left" uib-tooltip="EDITAR"> <i class="fa fa-edit"></i> </label>' +
            '<label class="btn text-success" ng-click="grid.appScope.btnGaleriaServicio(row);$event.stopPropagation();" tooltip-placement="left" uib-tooltip="GALERIA"> <i class="fa fa-photo"></i> </label>' +
            '<label class="btn text-red" ng-click="grid.appScope.btnAnularServicio(row);$event.stopPropagation();"> <i class="fa fa-trash" tooltip-placement="left" uib-tooltip="ELIMINAR!"></i> </label>'
        },

      ];

      vm.getPaginationServServerSide = function () {
        console.log('sesion', $scope.fSessionCI);
        var paramDatos = {
          idioma: localStorage.getItem('language'),
          idsede: row.entity.idsede
        }
        SedeServices.sListarServiciosSedes(paramDatos).then(function (rpta) {
          vm.gridServOptions.data = rpta.datos;
          // vm.mySelectionGrid = [];
        });
      }
      vm.getPaginationServServerSide();

      vm.btnEditarServicio = function (row) {
        $uibModal.open({
          templateUrl: 'app/pages/sede/sede_servicio_formview.php',
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
            vm.dirIconos = arrToModal.dirServicios + '/iconos/';
            // console.log('ruta', vm.dirIconos);
            vm.modoEdicion = true;
            vm.getPaginationServServerSide = arrToModal.getPaginationServServerSide;


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
              // vm.fData.idioma = localStorage.getItem('language');
              var formData = new FormData();
              angular.forEach(vm.fData, function (index, val) {
                formData.append(val, index);
              });

              SedeServices.sEditarServicioSede(formData).then(function (rpta) {
                if (rpta.flag == 1) {
                  $uibModalInstance.close(vm.fData);
                  vm.getPaginationServServerSide();
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
                getPaginationServServerSide: vm.getPaginationServServerSide,
                seleccion: row.entity,
                dirServicios: vm.dirServicios
              }
            }
          }
        });
      }

      vm.btnGaleriaServicio = row => {
        $uibModal.open({
          templateUrl: 'app/pages/sede/sede_servicio_galeria.php',
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
            vm.dirServicios = arrToModal.dirServicios;
            // console.log('ruta', vm.dirIconos);
            vm.modoEdicion = true;
            vm.getPaginationServServerSide = arrToModal.getPaginationServServerSide;


            vm.modalTitle = 'Galeria de Servicio';

            vm.cancel = function () {
              $uibModalInstance.dismiss('cancel');
            };
          },
          resolve: {
            arrToModal: function () {
              return {
                getPaginationServServerSide: vm.getPaginationServServerSide,
                seleccion: row.entity,
                dirServicios: vm.dirServicios
              }
            }
          }
        });
      }
    }

      //  if(params.param == 'nueva-empresa'){
      //   vm.btnNuevo();
      // }
  }
  function SedeServices($http, $q, handle) {
    return({
      sListarSedes: sListarSedes,
      sListarSedeCbo: sListarSedeCbo,
      sRegistrarSede: sRegistrarSede,
      sEditarSede: sEditarSede,
      sAnularSede: sAnularSede,
      sListarServiciosSedes: sListarServiciosSedes,
      sEditarServicioSede: sEditarServicioSede
    });
    function sListarSedes(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url :  angular.patchURLCI + "Sede/listarSedes",
            data : datos
      });
      return (request.then( handle.success,handle.error ));
    }
    function sListarSedeCbo(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url :  angular.patchURLCI + "Sede/listarSede_cbo",
            data : datos
      });
      return (request.then( handle.success,handle.error ));
    }
    function sRegistrarSede(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url : angular.patchURLCI + "Sede/registrarSede",
            data : datos
      });
      return (request.then(handle.success,handle.error));
    }
    function sEditarSede(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url : angular.patchURLCI + "Sede/editarSede",
            data : datos
      });
      return (request.then(handle.success,handle.error));
    }
    function sAnularSede(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url : angular.patchURLCI + "Sede/anularSede",
            data : datos
      });
      return (request.then(handle.success,handle.error));
    }
    function sListarServiciosSedes(pDatos) {
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url : angular.patchURLCI + "Sede/listarServiciosSede",
            data : datos
      });
      return (request.then(handle.success,handle.error));
    }
    function sEditarServicioSede(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/editarServicioSede",
        data: datos,
        transformRequest: angular.identity,
        headers: { 'Content-Type': undefined }
      });
      return (request.then(handle.success, handle.error));
    }
  }
})();

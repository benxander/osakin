(function () {
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
    pageLoading,
    FileUploader,
    SweetAlert,
    SedeServices,
    ServicioServices,
    pinesNotifications
  ) {

    var vm = this;
    var params = $location.search();
    vm.selectedItem = {};
    vm.options = {};
    vm.verServicios = false;
    vm.serv = {}
    vm.dirServicios = angular.patchURLCI + "uploads/servicios/";

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
      enableRowSelection: false,
      enableRowHeaderSelection: false,
      enableFullRowSelection: false,
      multiSelect: false,
      appScopeProvider: vm
    }
    vm.gridOptions.columnDefs = [
      { field: 'idsede', name: 'idsede', displayName: 'ID', width: 80, enableFiltering: false },
      { field: 'descripcion_se', name: 'descripcion_se', displayName: 'SEDE' },
      { field: 'telefono', name: 'telefono', displayName: 'TELÉFONO', width: 150, },
      { field: 'email', name: 'email', displayName: 'EMAIL', minWidth: 200, width: 200 },
      {
        field: 'accion1', displayName: '', width: 175, enableFiltering: false, enableColumnMenu: false,
        cellTemplate:
          '<label class="btn btn-primary text-center" style="width: 170px;" ng-click="grid.appScope.btnServicios(row)">' +
          'Servicios {{row.entity.descripcion_se}}' +
          '</label>'
      },
      {
        field: 'accion', name: 'accion', displayName: 'ACCIONES', width: 90, enableFiltering: false, enableColumnMenu: false,
        cellTemplate:
          '<label class="btn text-primary" ng-click="grid.appScope.btnEditar(row);$event.stopPropagation();">' +
          '<i class="fa fa-edit" tooltip-placement="left" uib-tooltip="EDITAR"></i> </label>' +
          '<label class="btn text-red" ng-click="grid.appScope.btnAnular(row);$event.stopPropagation();">' +
          '<i class="fa fa-trash" tooltip-placement="left" uib-tooltip="ELIMINAR!"></i> </label>'
      },

    ];

    vm.getPaginationServerSide = function () {
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
        backdropClass: 'splash splash-2 splash-ef-12',
        windowClass: 'splash splash-2 splash-ef-12',
        backdrop: 'static',
        keyboard: false,
        controller: function ($scope, $uibModalInstance, arrToModal) {
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
    vm.btnEditar = function (row) {
      $uibModal.open({
        templateUrl: 'app/pages/sede/sede_formview.php',
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
            vm.fData.idioma = localStorage.getItem('language');
            SedeServices.sEditarSede(vm.fData).then(function (rpta) {
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
        SedeServices.sAnularSede(row.entity).then(function (rpta) {
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

    vm.btnServicios = (row) => {
      vm.verServicios = true;
      vm.serv.fData = row.entity;

      vm.gridServOptions = {
        paginationPageSizes: [10, 50, 100, 500, 1000],
        paginationPageSize: 10,
        rowHeight: 32,
        appScopeProvider: vm
      }
      vm.gridServOptions.columnDefs = [
        { field: 'id', name: 'idsedeservicio', displayName: 'ID', width: 80, enableFiltering: false },
        { field: 'servicio', name: 'servicio', displayName: 'SERVICIO', minWidth: 250 },
        { field: 'nombre_serv', name: 'nombre_serv', displayName: 'NOMBRE DE SERVICIO', minWidth: 250 },
        { field: 'titulo', name: 'titulo', displayName: 'TITULO', minWidth: 250, },
        { field: 'telefono_contacto', name: 'telefono_contacto', displayName: 'WHATSAPP', minWidth: 250, },

        {
          field: 'accion', name: 'accion', displayName: 'ACCIONES', width: 120, enableFiltering: false, enableColumnMenu: false,
          cellTemplate:
            '<label class="btn text-success" ng-click="grid.appScope.btnGaleriaServicio(row);$event.stopPropagation();">' +
            '<i class="fa fa-photo" tooltip-placement="left" uib-tooltip="GALERIA"></i> </label>' +

            '<label class="btn text-primary" ng-click="grid.appScope.btnEditarServicio(row);$event.stopPropagation();">' +
            '<i class="fa fa-edit" tooltip-placement="left" uib-tooltip="EDITAR"></i> </label>' +

            '<label class="btn text-red" ng-click="grid.appScope.btnAnularServicio(row);$event.stopPropagation();">' +
            '<i class="fa fa-trash" tooltip-placement="left" uib-tooltip="ELIMINAR!"></i> </label>'
        },

      ];

      vm.getPaginationServServerSide = () => {
        var paramDatos = {
          idioma: localStorage.getItem('language'),
          idsede: row.entity.idsede
        }
        SedeServices.sListarServiciosSedes(paramDatos).then(rpta => {
          vm.gridServOptions.data = rpta.datos;
          // vm.mySelectionGrid = [];
        });
      }
      vm.getPaginationServServerSide();

      vm.btnAgregarServicio = () => {
        $uibModal.open({
          templateUrl: 'app/pages/sede/agrega_sede_servicio_formview.php',
          controllerAs: 'mp',
          size: 'lg',
          backdropClass: 'splash splash-2 splash-ef-12',
          windowClass: 'splash splash-2 splash-ef-12',
          backdrop: 'static',
          keyboard: false,
          controller: function ($scope, $uibModalInstance, arrToModal) {
            var vm = this;

            vm.fData = {};
            vm.getPaginationServServerSide = arrToModal.getPaginationServServerSide;
            vm.fData = arrToModal.fData;
            vm.btnEliminar = arrToModal.btnAnularServicio;

            vm.modalTitle = 'Agregar Servicios a ' + vm.fData.descripcion_se;

            // GRID DE SERVICIOS NO AGREGADOS
            vm.gridServNoAgrOptions = {
              paginationPageSizes: [10, 50, 100, 500, 1000],
              paginationPageSize: 10,
              rowHeight: 32,
              appScopeProvider: vm
            }
            vm.gridServNoAgrOptions.columnDefs = [
              { field: 'idservicio', name: 'idservicio', displayName: 'ID', width: 60, enableFiltering: false, visible: true },
              { field: 'servicio', name: 'servicio', displayName: 'SERVICIO', minWidth: 250 },

              {
                field: 'accion', name: 'accion', displayName: 'ACCION', width: 80, enableFiltering: false, enableColumnMenu: false,
                cellTemplate:
                '<div class="" style="text-align:center;">' +
                '<button type="button" class="btn btn-sm btn-primary m-xs" ng-click="grid.appScope.btnAgregar(row)" title="AGREGAR">' +
                '<i class="fa fa-arrow-right"></i></button>' +
                '</div>'
              },

            ];
            vm.getServiciosNoAgrServerSide = () => {
              var paramDatos = {
                idsede: vm.fData.idsede
              }
              ServicioServices.sListarServiciosNoAgregados(paramDatos).then(rpta => {
                vm.gridServNoAgrOptions.data = rpta.datos;
              });
            }
            vm.getServiciosNoAgrServerSide();

            // GRID DE SERVICIOS AGREGADOS
            vm.gridServAgrOptions = {
              paginationPageSizes: [10, 50, 100, 500, 1000],
              paginationPageSize: 10,
              rowHeight: 32,
              appScopeProvider: vm
            }
            vm.gridServAgrOptions.columnDefs = [
              { field: 'idservicio', name: 'idservicio', displayName: 'ID', width: 60, enableFiltering: false, visible: true },
              { field: 'servicio', name: 'servicio', displayName: 'SERVICIO', minWidth: 250 },

              {
                field: 'accion', name: 'accion', displayName: 'ACCION', width: 80, enableFiltering: false, enableColumnMenu: false,
                cellTemplate:
                '<div class="" style="text-align:center;">' +
                '<button type="button" class="btn btn-sm btn-danger m-xs" ng-click="grid.appScope.btnEliminar(row, grid.appScope.callback)" title="ELIMINAR">' +
                '<i class="fa fa-trash"></i></button>' +
                '</div>'
              },

            ];
            vm.getServiciosServerSide = () => {
              var paramDatos = {
                idsede: vm.fData.idsede
              }
              ServicioServices.sListarServiciosAgregados(paramDatos).then(rpta => {
                vm.gridServAgrOptions.data = rpta.datos;
              });
            }
            vm.getServiciosServerSide();

            vm.callback = () => {
              vm.getServiciosNoAgrServerSide();
              vm.getServiciosServerSide();
            }

            vm.btnAgregar = row => {
              var paramDatos = {
                idsede: vm.fData.idsede,
                idservicio: row.entity.idservicio,
              }
              SedeServices.sAgregarServicioSede(paramDatos).then(rpta => {
                if (rpta.flag == 1) {
                  vm.callback();
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
              $uibModalInstance.close();
            };

          },
          resolve: {
            arrToModal: function () {
              return {
                getPaginationServServerSide: vm.getPaginationServServerSide,
                fData: vm.serv.fData,
                btnAnularServicio: vm.btnAnularServicio
              }
            }
          }
        });
      }
      vm.btnEditarServicio = row => {
        $uibModal.open({
          templateUrl: 'app/pages/sede/sede_servicio_formview.php',
          controllerAs: 'mp',
          size: 'lg',
          backdropClass: 'splash splash-2 splash-ef-12',
          windowClass: 'splash splash-2 splash-ef-12',
          backdrop: 'static',
          keyboard: false,
          scope:$scope,
          controller: function ($scope, $uibModalInstance, arrToModal) {
            var vm = this;

            vm.fData = {};
            vm.fData = angular.copy(arrToModal.seleccion);
            vm.dirIconos = arrToModal.dirServicios + '/iconos/';
            vm.modoEdicion = true;
            vm.getPaginationServServerSide = arrToModal.getPaginationServServerSide;

            vm.modalTitle = 'Edición de Servicio ' + row.entity.servicio;

            vm.regexTel = /^[6789]\d{8}$/;

            vm.aceptar = function () {
              vm.fData.idioma = localStorage.getItem('language');
              var formData = new FormData();
              angular.forEach(vm.fData, function (index, val) {
                formData.append(val, index);
              });

              SedeServices.sEditarServicioSede(formData).then(function (rpta) {
                if (rpta.flag == 1) {
                  $uibModalInstance.close();
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
              $uibModalInstance.close();
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
            vm.dirThumbs = arrToModal.dirServicios + 'thumbs/';
            vm.uploadBtn = false;
            vm.getPaginationServServerSide = arrToModal.getPaginationServServerSide;

            var uploader = $scope.uploader = new FileUploader({
              url: angular.patchURLCI + 'sede/SubirArchivo'
            });

            vm.modalTitle = 'Galeria de Servicio';
            var paramDatos = {
              idsedeservicio: vm.fData.id
            }
            vm.cargarGaleria = () => {
              SedeServices.sCargarGaleriaSedeServicio(paramDatos).then(function (rpta) {
                vm.fData.galeria = rpta.datos;
              });
            }
            vm.cargarGaleria();

            vm.btnSubir = function () {
              vm.uploadBtn = true;
            }

            vm.subirTodo = function () {
              uploader.uploadAll();
            }

            vm.btnAnularArchivo = function (row, index) {
              SweetAlert.swal({
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
                function (isConfirm) {
                  if (isConfirm) {
                    vm.fData.galeria.splice(index, 1);
                    var data = {
                      fotoParaEliminar: row.foto,
                      sedeServicio: vm.fData
                    }
                    SedeServices.sEliminarArchivo(data).then(rpta => {
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
                      vm.cargarGaleria();
                    });
                  } else {
                    SweetAlert.swal('Cancelado', 'La operación ha sido cancelada', 'warning');
                  }
                });
            }

            // CALLBACKS

            uploader.onWhenAddingFileFailed = function (item /*{File|FileLikeObject}*/, filter, options) {
            };
            uploader.onAfterAddingAll = function (addedFileItems) {
              console.info('onAfterAddingAll', addedFileItems);
            };
            uploader.onBeforeUploadItem = function (item) {
              item.formData.push({
                idsedeservicio: vm.fData.id,
                galeria: JSON.stringify(vm.fData.galeria)
              });
            };

            uploader.onProgressAll = function (progress) {
              console.info('onProgressAll', progress);
            };
            uploader.onResumen = function (progress) {
              console.info('onProgressAll', progress);
            };
            uploader.onSuccessItem = function (fileItem, response, status, headers) {
              if (response.flag == 1) {
                var pTitle = 'OK';
                var pType = 'success';
              } else if (response.flag == 0) {
                var pTitle = 'Advertencia';
                var pType = 'warning';
              } else {
                alert('Ocurrió un error');
              }
              pinesNotifications.notify({ title: pTitle, text: response.message, type: pType, delay: 3000 });
            };
            uploader.onErrorItem = function (fileItem, response, status, headers) {
              if (response.flag == 1) {
                var pTitle = 'OK';
                var pType = 'success';
              } else if (response.flag == 0) {
                var pTitle = 'Advertencia';
                var pType = 'warning';
              } else {
                alert('Ocurrió un error');
              }
              pinesNotifications.notify({ title: pTitle, text: response.message, type: pType, delay: 3000 });
            };
            uploader.onCompleteAll = () => {
              vm.uploadBtn = false;
              uploader.clearQueue();
              vm.cargarGaleria();
            };

            vm.cancel = () => {
              $uibModalInstance.close();
            };
          },
          resolve: {
            arrToModal: () => {
              return {
                getPaginationServServerSide: vm.getPaginationServServerSide,
                seleccion: row.entity,
                dirServicios: vm.dirServicios
              }
            }
          }
        });
      }

      vm.btnAnularServicio = (row, callback) => {
        var callback = callback || null;
        SweetAlert.swal({
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
        function (isConfirm) {
          if (isConfirm) {

            SedeServices.sEliminarServicioSede(row.entity).then(rpta => {
              if (rpta.flag == 1) {
                pTitle = 'OK!';
                pType = 'success';

              } else if (rpta.flag == 0) {
                var pTitle = 'Error!';
                var pType = 'danger';
              } else {
                alert('Error inesperado.');
              }
              pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 5000 });
              vm.getPaginationServServerSide();
              if(callback){
                callback();
              }
            });
          } else {
            SweetAlert.swal('Cancelado', 'La operación ha sido cancelada', 'warning');
          }
        });
      }
    }

    //  if(params.param == 'nueva-empresa'){
    //   vm.btnNuevo();
    // }
  }
  function SedeServices($http, $q, handle) {
    return ({
      sListarSedes: sListarSedes,
      sListarSedeCbo: sListarSedeCbo,
      sRegistrarSede: sRegistrarSede,
      sEditarSede: sEditarSede,
      sAnularSede: sAnularSede,
      sListarServiciosSedes: sListarServiciosSedes,
      sEditarServicioSede: sEditarServicioSede,
      sAgregarServicioSede: sAgregarServicioSede,
      sEliminarServicioSede: sEliminarServicioSede,
      sCargarGaleriaSedeServicio: sCargarGaleriaSedeServicio,
      sEliminarArchivo: sEliminarArchivo
    });
    function sListarSedes(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/listarSedes",
        data: datos
      });
      return (request.then(handle.success, handle.error));
    }
    function sListarSedeCbo(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/listarSede_cbo",
        data: datos
      });
      return (request.then(handle.success, handle.error));
    }
    function sRegistrarSede(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/registrarSede",
        data: datos
      });
      return (request.then(handle.success, handle.error));
    }
    function sEditarSede(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/editarSede",
        data: datos
      });
      return (request.then(handle.success, handle.error));
    }
    function sAnularSede(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/anularSede",
        data: datos
      });
      return (request.then(handle.success, handle.error));
    }
    function sListarServiciosSedes(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/listarServiciosSede",
        data: datos
      });
      return (request.then(handle.success, handle.error));
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
    function sEliminarServicioSede(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/eliminarServicioSede",
        data: datos,
      });
      return (request.then(handle.success, handle.error));
    }
    function sAgregarServicioSede(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/agregarServicioSede",
        data: datos,
      });
      return (request.then(handle.success, handle.error));
    }
    function sCargarGaleriaSedeServicio(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/cargarGaleriaSedeServicio",
        data: datos
      });
      return (request.then(handle.success, handle.error));
    }
    function sEliminarArchivo(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method: "post",
        url: angular.patchURLCI + "Sede/eliminarArchivo",
        data: datos
      });
      return (request.then(handle.success, handle.error));
    }
  }
})();

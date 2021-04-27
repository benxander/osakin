(function() {
  'use strict';

  angular
    .module('minotaur')
    // .factory('CalendarData', CalendarData)
    .factory('ModalReporteFactory', ModalReporteFactory);

  /** @ngInject */

  function ModalReporteFactory($uibModal,$http,$q,blockUI,pinesNotifications,rootServices){
    var interfazReporte = {
      getPopupReporte: function(arrParams){ //console.log(arrParams.datos.salida,' as');
        if( arrParams.datos.salida == 'pdf' || angular.isUndefined(arrParams.datos.salida) ){
          $uibModal.open({
            templateUrl: 'app/pages/reportes/popup_reporte.php',
            controllerAs: 'mr',
            size: 'lg',
            controller: function ($scope,$uibModalInstance,arrParams) {
              $scope.titleModalReporte = arrParams.titulo;
              
              $scope.cancel = function () {
                $uibModalInstance.dismiss('cancel');
              }
              
              var deferred = $q.defer();
              $http.post(arrParams.url, arrParams.datos).then(
                function(res) {
                    $('#frameReporte').attr("src", res.data.urlTempPDF);
                    deferred.resolve(res.data);
                },
                function(err) {
                    deferred.resolve(err);
                }
              );
            },
            resolve: {
              arrParams: function() {
                return arrParams;
              }
            }
          });
        }else if( arrParams.datos.salida == 'excel' ){
          blockUI.start('Preparando reporte');
          $http.post(arrParams.url, arrParams.datos)
            .then(function(rpta) {
              blockUI.stop();
              var data = rpta.data;
              if(data.flag == 1){
                console.log('Excel');
                // window.open = arrParams.urlTempEXCEL;
                window.location = data.urlTempEXCEL;
              }else if(data.flag == 0){
                var pTitle = 'Advertencia!';
                var pType = 'warning';
                pinesNotifications.notify({ title: pTitle, text: data.message, type: pType, delay: 3000 });
              }else{
                console.log('Error');
              }
          });
        }
      }
    }
    return interfazReporte;
  }
})();

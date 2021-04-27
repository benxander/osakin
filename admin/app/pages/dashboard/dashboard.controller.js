(function() {
  'use strict';

  angular
    .module('minotaur')
    .controller('DashboardController', DashboardController);

  /** @ngInject */
  function DashboardController($scope,$timeout,) {
    var vm = this;
    vm.fData = {};
    vm.listaEmpresasPendientes = [];
    // vm.listarProxCitas = function() {
    //   CitasServices.sListarProximasCitas().then(function(rpta) {
    //     if( rpta.flag == 1 ){
    //       vm.listaProxCitas = rpta.datos;
    //     }else{
    //       vm.listaProxCitas = [];
    //     }
    //   });
    // }

    vm.listaEmpresasPendientes = [
      {
        empresa: 'PizitaPepito',
        fecha: '15/07/2020',
        telefono: '999 999 999',
        correo: 'pizzita@pizzapepito.com'
      }
    ]
    
    $timeout(function() {
      if( $scope.fSessionCI.idusuario ){
        console.log('Usuario logueado');
        // vm.listarProxCitas();
        // vm.listarInformeGeneral();
      }
    },1000);

  }
})();

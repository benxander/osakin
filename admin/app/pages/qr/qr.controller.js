(function () {
	'use strict';

	angular
		.module('minotaur')
		.controller('QrController', QrController)
		.service('QrServices', QrServices);

	/** @ngInject */
	function QrController(
		$scope,
		$uibModal,
		$timeout,
		ModalReporteFactory
	) {
		var vm = this;
		$timeout(function () {
			vm.codigoQR = angular.patchURLCI + '/inicio/qr_code';
			vm.direccionCarta = angular.patchURLCI + 'c/' + $scope.fSessionCI.nombre_negocio;

		}, 1000);

		vm.btnImprimir = function(){
            var arrParams = {
              titulo: 'CODIGO QR',
              datos:{
                salida    : 'pdf',
                titulo    : 'CODIGO QR',
			  },
			  url: angular.patchURLCI+'CentralReportes/imprimir_qr'
            }
            ModalReporteFactory.getPopupReporte(arrParams);
          }
	}

	function QrServices($http, $q, handle) {
		return ({
			sMostrarQr: sMostrarQr
		});

		function sMostrarQr(pDatos) {
			var datos = pDatos || {};
			var request = $http({
				method: 'post',
				url: angular.patchURLCI + 'Inicio/qr',
				data: datos
			});
			return (request.then(handle.success, handle.error));
		}

	}
})();
(function() {
  'use strict';

  angular
    .module('minotaur')
    .controller('MainController', MainController)
    .service('rootServices', rootServices)
    .directive('hcChart', function () {
      return {
          restrict: 'E',
          template: '<div></div>',
          scope: {
              options: '='
          },
          link: function (scope, element) {
            Highcharts.chart(element[0], scope.options);
          }
      };
    })
    // Directive for pie charts, pass in title and data only
    .directive('hcPieChart', function () {
        return {
            restrict: 'E',
            template: '<div></div>',
            scope: {
                title: '@',
                data: '='
            },
            link: function (scope, element) {
                Highcharts.chart(element[0], {
                    chart: {
                        type: 'pie',
                        events: {
                          load: function () {

                          }
                        }
                    },
                    title: {
                        text: scope.title
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        data: scope.data
                    }]
                });
            }
        };
    })
    .directive('fileModel', ['$parse', function ($parse) {
      return {
        restrict: 'A',
        link: function (scope, element, attrs) {
          var model = $parse(attrs.fileModel);
          var modelSetter = model.assign;
          element.bind('change', function () {
            scope.$apply(function () {
              modelSetter(scope, element[0].files[0]);
            });
          });
        }
      };
    }])
    // .directive('ckEditor', function () {
    //   return {
    //     require: '?ngModel',
    //     link: function (scope, elm, attr, ngModel) {
    //       var ck = CKEDITOR.replace(elm[0]);

    //       if (!ngModel) return;

    //       ck.on('pasteState', function () {
    //         scope.$apply(function () {
    //           ngModel.$setViewValue(ck.getData());
    //         });
    //       });

    //       ngModel.$render = function (value) {
    //         ck.setData(ngModel.$viewValue);
    //       };
    //     }
    //   };
    // })
    .factory("pageLoading", function(){
      var pageLoading = {
        start: function(text){
            var page = angular.element('#page-loading');
            var pageText = angular.element('.page-loading-text');
            page.addClass('visible');
            pageText.text(text);
        },
        stop: function(){
            var page = angular.element('#page-loading');
            var pageText = angular.element('.page-loading-text');
            page.removeClass('visible');
            pageText.text('');
        }
      }
      return pageLoading;
    })
    .factory("handle", function(alertify,$state){
      var handle = {
        error: function (error) {
                      return function () {
                        return {success: false, message: Notification.warning({message: error})};
                      };
        },
        success: function (response) {
            //console.log('response.data',response.data);
            if(response.data.flag == 'session_expired'){
              alertify.okBtn("CLICK AQUI")
                      .cancelBtn("Cerrar")
                      .confirm(response.data.message,
                        function (ev) {
                          //var dir = window.location.href.split('app')[0];
                          //window.location.href = dir + 'app/pages/login';
                          var url = $state.href('pages.login');
                          window.open(url,'_self');
                          $('.alertify').remove();
                        }
                      );
            }
            if(response.data.flag == 'pay_expired'){
              alertify.okBtn("CLICK AQUI")
                      .cancelBtn("Cerrar")
                      .confirm(response.data.message,
                        function (ev) {
                          var url = $state.href('pages.login');
                          window.open(url,'_self');
                          $('.alertify').remove();
                          // var dir = window.location.href.split('app')[0];
                          // window.location.href = dir + 'app/pages/login';
                        }
                      );
            }
            return( response.data );
        }
      }
      return handle;
    })
    .factory('pinesNotifications', ['$window', function ($window) {
      'use strict';
      return {
        notify: function (args) {
          args.styling = 'fontawesome';
          args.mouse_reset = false;
          var notification = new $window.PNotify(args);
          notification.notify = notification.update;
          return notification;
        },
      };
    }]);

  /** @ngInject */
  function MainController(
    $translate,
    $scope,
    $timeout,
    $state,
    rootServices,
    $uibModal,
    UsuarioServices,
    $location,
    pinesNotifications
  ) {
    var vm = this;

    vm.idioma = localStorage.getItem('language');
    if (!vm.idioma) {
      vm.idioma = 'es';
      localStorage.setItem('language', vm.idioma);
    }

    $scope.fSessionCI = {};
    $scope.isLoggedIn = false;
    $scope.metodos = {};
    $scope.fArr = {};
    $scope.fArr.boolAutoStart = false;
    $scope.iniciarTour = function() {
      $scope.fArr.boolAutoStart = true;
    }
    $scope.fArr.arrSteps = [];
    $scope.fArr.valores = [];
    $scope.editorOptions = {
      language: 'es',
      extraPlugins: 'font,colorbutton,colordialog',


    };
    var arrSteps = [
        {
          cod_permiso: null,
          element: 'minotaur-nav .nav-logo',
          intro: '<h2 class="header">El logo de su marca</h2> <p>Visualizaras tu logo desde aqu??.</p>',
          position: 'right'
        },
        {
          cod_permiso: null,
          element: 'minotaur-header button.navigation-toggle',
          intro: '<h2 class="header">Desglosar el men??</h2> <p>Oculta o muestra el men?? para mas comodidad de trabajo.</p>'
        },
        {
          cod_permiso: null,
          element: 'minotaur-header .main-search',
          intro: '<h2 class="header">Buscador</h2> <p>Busque a sus elementos rapidamente. <br /> Desde cualquier opci??n siempre tendr?? este cuadro visible.</p>'
        },
        {
          cod_permiso: null,
          element: 'minotaur-header #profile',
          intro: '<h2 class="header">Tu perfil</h2> <p>Aqu?? va tu foto de perfil y tu configuraci??n de la cuenta.</p>'
        },
        {
          cod_permiso: 1,
          element: 'minotaur-nav .nav li.dashboard',
          intro: '<h2 class="header">Dashboard</h2> <p> Accesos directos. </p>',
          position: 'right'
        },
        {
          cod_permiso: 2,
          element: 'minotaur-nav .nav li.empresa',
          intro: '<h2 class="header">Clientes Corporativos</h2> <p> Restaurantes que adquieren el sistema. </p>',
          position: 'right'
        },
        {
          cod_permiso: 3,
          element: 'minotaur-nav .nav li.carta',
          intro: '<h2 class="header">Carta</h2> <p> Gestiona tu propia Carta digital. </p>',
          position: 'right'
        },
        {
          cod_permiso: 4,
          element: 'minotaur-nav .nav li.seccion',
          intro: '<h2 class="header">Secciones</h2> <p> Gestiona tu propia base de datos de secciones que tendra tu carta digital. </p>',
          position: 'right'
        },
        {
          cod_permiso: 5,
          element: 'minotaur-nav .nav li.plato',
          intro: '<h2 class="header">Platos</h2> <p> Gestiona tu propia base de datos de platos que incluiras en tus carta digital. </p>',
          position: 'right'
        },
        {
          cod_permiso: 6,
          element: 'minotaur-nav .nav li.alergeno',
          intro: '<h2 class="header">Al??rgenos</h2> <p> Revisa los Al??rgenos alimentarios principales. </p>',
          position: 'right'
        },
        {
          cod_permiso: 7,
          element: 'minotaur-nav .nav li.usuario',
          intro: '<h2 class="header">Usuarios</h2> <p> Gestiona los usuarios del sistema </p>',
          position: 'right'
        },
        {
          cod_permiso: 7,
          element: 'minotaur-nav .nav li.sys-configuracion',
          intro: '<h2 class="header">Configuracion Web</h2> <p> Configura la web </p>',
          position: 'right'
        },
        {
          cod_permiso: null,
          element: 'minotaur-nav .contacto',
          intro: '<h2 class="header">??Cont??ctanos!</h2> <p> ??Necesitas ayuda? Te brindamos soporte los 365 d??as del a??o, por nuestros canales de contacto. </p>',
          position: 'right'
        },
        {
          cod_permiso: null,
          element: 'minotaur-nav .disfruta_de_la_plataforma',
          intro: '<h2 class="header">??Disfruta!</h2> <p> <b>??FIN!</b> <br/> Esta plataforma estar?? actualiz??ndose constantemente con nuevas funcionalidades para t??. :) </p>',
          position: 'right'
        }
    ];

    $scope.getInfoEmpresa = function () {
      console.log('getInfoEmpresa');
      rootServices.sGetEmpresaAdministradora().then(function (response) {
        if (response.flag == 1) {
          $scope.fEmpresa = response.datos;
        }
      });
    }
    $scope.CargaMenu = function() {
      var opciones = ['opDashboard', 'opEmpresas', 'opCartas', 'opAlergenos', 'opSeguridad', 'opConfiguracion', 'opSecciones', 'opPlatos'];
      $scope.fArr.arrSteps = [];

      $scope.fArr.listaIdiomas = [
        {id: 'CAS', descripcion: 'CASTELLANO'},
        {id: 'EUS', descripcion: 'EUSKERA'}
      ];

      $scope.fArr.idioma = $scope.fArr.listaIdiomas[0];

      if($scope.fSessionCI.idgrupo == 1){
        $scope.fArr.valores = [true,true,true,true,true,true,false,false];
      }
      if($scope.fSessionCI.idgrupo == 2){
        $scope.fArr.valores = [true,false,false,false,false,false,true,true];
      }
      if($scope.fSessionCI.idgrupo == 3){
        $scope.fArr.valores = [true,false,true,true,false,true,false,false];
      }
      var cont = 0;
      angular.forEach(arrSteps,function(val,index) {
        if(val.cod_permiso){
          if( $scope.fArr.valores[cont] === true ){
            $scope.fArr.arrSteps.push(val);
          }
          cont++;
        }else{
          $scope.fArr.arrSteps.push(val);
        }
      });
      var result = [];
      angular.forEach($scope.fArr.arrSteps,function(val,index) {
        if(val){
          result.push(val);
        }
      });
      vm.introOptions.steps = result;
    }

    $scope.getValidateSession = function () {
      rootServices.sGetSessionCI().then(function (response) {
        //console.log(response);
        if(response.flag == 1){

          $scope.fSessionCI = response.datos;
          $scope.fSessionCI.idioma = localStorage.getItem('language');

          $scope.fArr.boolAutoStart = ($scope.fSessionCI.mostrar_intro == 1) ? true : false;
          $scope.logIn();
          $scope.CargaMenu();
          if( $location.path() == '/app/pages/login' ){
            $scope.goToUrl('/');
          }
          if( $scope.fArr.boolAutoStart ){
            $timeout(function() {
              $scope.iniciarTour();
            },5000);
          }
        }else{
          $scope.fSessionCI = {};
          $scope.logOut();
          $scope.goToUrl('/app/pages/login');
        }
      });
    }
    $scope.getValidateSession();

    vm.introOptions = {
      overlayOpacity: 0.3,
      showBullets: true,
      showStepNumbers: false,
      nextLabel: 'Siguiente <i class="fa fa-chevron-right"></i>',
      prevLabel: '<i class="fa fa-chevron-left"></i> Atr??s',
      skipLabel: '<i class="fa fa-close"></i>',
      doneLabel: '<i class="fa fa-close"></i>',
      steps: $scope.fArr.arrSteps
    };

    vm.onInitLanguaje = () => {
      $translate.use(vm.idioma);
      vm.currentLanguage = vm.idioma;
    }
    vm.onInitLanguaje();

    // solo debe usarse con el boton de idiomas
    vm.changeLanguage = function (langKey) {
      $translate.use(langKey);
      vm.currentLanguage = langKey;
      localStorage.setItem('language', langKey);
      $scope.fSessionCI.idioma = langKey;
      $state.go($state.current.name, $state.params, { reload: true });
    };

    vm.onChangeStep = function(obj,scope) {
      if($(obj).hasClass('contacto')){
        // actualizar valor de intro
        UsuarioServices.sActualizarIntroNoMostrar();
      }
    }
    vm.onChangeExit = function() {
      // actualizar valor de intro
      UsuarioServices.sActualizarIntroNoMostrar();
    }

    // vm.changeLanguage('es');


    $scope.logOut = function() {
      $scope.isLoggedIn = false;
      $scope.captchaValido = false;
    }
    $scope.logIn = function() {
      $scope.isLoggedIn = true;
    };

    $scope.btnLogoutToSystem = function () {
      rootServices.sLogoutSessionCI().then(function () {
        $scope.fSessionCI = {};
        $scope.listaUnidadesNegocio = {};
        $scope.listaModulos = {};
        $scope.logOut();
        $scope.goToUrl('/app/pages/login');
        // $scope.fArr.arrSteps = [];
        // $scope.fArr.valores = [];
      });
    };
    $scope.btnChangePassword = function() {
      var modalInstance = $uibModal.open({
        templateUrl: 'password.html',
        controllerAs: 'ps',
        size: 'sm',
        scope: $scope,
        backdropClass: 'splash',
        windowClass: 'splash',
        controller: function($scope, $uibModalInstance){
          var vm = this;
          vm.fData = {};
          vm.modalTitle = 'Cambio de Clave';
          vm.fData.idusuario = $scope.fSessionCI.idusuario;
          //console.log("sesion: ",$scope.fSessionCI.idusuario);
          // BOTONES
          vm.aceptar = function () {
            UsuarioServices.sCambiarClave(vm.fData).then(function (rpta) {
              if(rpta.flag == 1){
                //data.usuario = vm.fData.username;
                $uibModalInstance.close();
                var pTitle = 'OK!';
                var pType = 'success';
              }else if( rpta.flag == 0 ){
                var pTitle = 'Advertencia!';
                var pType = 'warning';
              }else{
                alert('Ocurri?? un error');
              }
              pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 3000 });
            });

          };
          vm.cancel = function () {
            $uibModalInstance.dismiss('cancel');
          };
        }
      });
    };
    $scope.buscarPaciente = function (paciente) {
      var paramDatos = {
        search: paciente
      }
      // PacienteServices.sListarPacientePorNombre(paramDatos).then(function (rpta) {
      //   if( rpta.flag == 1 ){
      //     $scope.paciente = rpta.datos;
      //     $state.go('pacienteficha');
      //     if( !(angular.isUndefined($scope.metodos.btnVerFicha)) ){
      //       $scope.metodos.btnVerFicha($scope.paciente);
      //       $scope.fArr.fBusqueda = rpta.datos.paciente;
      //     }
      //   }else{
      //     pinesNotifications.notify({ title: 'Advertencia', text: 'No se encontr?? al paciente.', type: 'warning', delay: 3000 });
      //   }

      // });
    };

    $scope.goToUrl = function ( path , searchParam) {
      $location.path( path );
      if(searchParam){
        $location.search({param: searchParam});
      }
    };


  }
  function rootServices($http, $q, handle) {
    return({
      sLogoutSessionCI: sLogoutSessionCI,
      sGetSessionCI: sGetSessionCI,
      sGetEmpresaAdministradora: sGetEmpresaAdministradora
    });
    function sLogoutSessionCI(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method : "post",
        url :  angular.patchURLCI + "Acceso/logoutSessionCI",
        data : datos
      });
      return (request.then( handle.success,handle.error ));
    }
    function sGetSessionCI(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method : "post",
        url :  angular.patchURLCI + "Acceso/getSessionCI",
        data : datos
      });
      return (request.then( handle.success,handle.error ));
    }
    function sGetEmpresaAdministradora(pDatos) {
      var datos = pDatos || {};
      var request = $http({
        method : "post",
        url: angular.patchURLCI + "Configuracion/getEmpresaAdmin",
        data : datos
      });
      return (request.then( handle.success,handle.error ));
    }
  }
})();

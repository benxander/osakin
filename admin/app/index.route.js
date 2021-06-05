(function() {
  'use strict';

  angular
    .module('minotaur')
    .config(routerConfig);

  /** @ngInject */
  function routerConfig($stateProvider, $urlRouterProvider) {
    $stateProvider
      //dashboard
      .state('dashboard', {
        url: '/app/dashboard',
        templateUrl: 'app/pages/dashboard/dashboard.html',
        controller: 'DashboardController',
        controllerAs: 'ds'
      })
      //app core pages (errors, login,signup)
      .state('pages', {
        url: '/app/pages',
        template: '<div ui-view></div>'
      })
      //login
      .state('pages.login', {
        url: '/login',
        templateUrl: 'app/pages/pages-login/pages-login.html',
        controller: 'LoginController',
        controllerAs: 'ctrl',
        parent: 'pages',
        specialClass: 'core'
      })

      //Sede
      .state('sede', {
        url: '/app/sede',
        templateUrl: 'app/pages/sede/sede.html',
        controller: 'SedeController as vm'
      })

      //Banners
      .state('banner', {
        url: '/app/banner',
        templateUrl: 'app/pages/banner/banner.html',
        controller: 'BannerController as vm'
      })

      // Servicio
      .state('servicio', {
        url: '/app/servicio',
        templateUrl: 'app/pages/servicio/servicio.html',
        controller: 'ServicioController as vm'
      })

      //usuario
      .state('usuario', {
        url: '/app/usuario',
        templateUrl: 'app/pages/usuario/usuario.html',
        controller: 'UsuarioController as vm'
      })

      // paginas dinamicas
      .state('paginas-dinamicas', {
        url: '/app/paginas-dinamicas',
        templateUrl: 'app/pages/paginas-dinamicas/paginas-dinamicas.html',
        controller: 'PaginasDinamicasController as vm'
      })

      // redes sociales
      .state('sitio-web', {
        url: '/app/sitio-web',
        templateUrl: 'app/pages/sitio-web/sitio-web.html',
        controller: 'SitioWebController as vm'
      })

      // configuracion
      // .state('sys-configuracion', {
      //   url: '/app/sys-configuracion',
      //   templateUrl: 'app/pages/configuracion/sys-configuracion.html',
      //   controller: 'ConfiguracionController as vm'
      // });


    $urlRouterProvider.otherwise('/app/dashboard');


  }

})();

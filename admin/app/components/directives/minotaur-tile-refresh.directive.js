(function() {
  'use strict';

  angular
    .module('minotaur')
    .directive('minotaurTileRefresh', minotaurTileRefresh);

  /** @ngInject */
  function minotaurTileRefresh($timeout, cfpLoadingBar) {
    var directive = {
      restrict: 'E',
      // template: '<i class="fa fa-refresh"></i>Refresh',
      template: '<button class="btn btn-transparent"><i class="fa fa-refresh"></i></button>',
      link: function (scope, element) {
        var tile = element.parents('.tile');
        element.on('click', function(){
          tile.addClass('loading');
          cfpLoadingBar.start();

          $timeout(function(){
            tile.removeClass('loading');
            cfpLoadingBar.complete();
          },3000)
        });
      }
    };

    return directive;

  }

})();

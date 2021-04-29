(function() {
  'use strict';

  angular
    .module('minotaur')
    .directive('filestyle', filestyle);

  /** @ngInject */
  function filestyle() {
    var directive = {
      restrict:'AC',
      scope: true,
      link: function (scope, element, attrs) {
        var options = {
          'input': attrs.input === 'false' ? false : true,
          'icon': attrs.icon === 'false' ? false : true,
          'buttonBefore': attrs.buttonBefore === 'true' ? true : false,
          'disabled': attrs.disabled === 'true' ? true : false,
          'size': attrs.size,
          'buttonText': attrs.buttonText || '',
          'buttonName': attrs.buttonName,
          'iconName': attrs.iconName || 'fa fa-file-image-o',
          'badge': attrs.badge === 'false' ? false : true,
          'placeholder': attrs.placeholder || 'Selecciona una imagen'
        };
        angular.element(element).filestyle(options);
      }
    };

    return directive;

  }

})();

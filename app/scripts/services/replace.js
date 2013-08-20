angular.module('PageReplace').
    factory('Replace', function($resource, $window){
  return $resource($window.ajaxurl, {}, {
    save: {method:'POST', params: {action: 'pagereplace-replace', pagereplace_nonce: $window.pagereplace_vars.nonce}}
  });
});

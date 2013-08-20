angular.module('PageReplace').
    factory('Process', function($resource, $window){
  return $resource($window.ajaxurl, {action: 'pagereplace-process', pagereplace_nonce: $window.pagereplace_vars.nonce}, {
    query: {method:'JSON', query: {}, cache: true}
  });
});

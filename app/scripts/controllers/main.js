'use strict';

angular.module('PageReplace')
  .controller('MainCtrl', function ($scope, Process, Replace) {
    $scope.message = "";
    $scope.msgclass = "updated";
    $scope.postContents = "";
    $scope.find = "";
    $scope.replace = "";
    $scope.type = "page";
    $scope.loader = false;
    $scope.results = [];
    $scope.showTable = false;
    $scope.showReplace = false;
    $scope.replaceLoader = false;

    $scope.search = function () {
      $scope.loader = true;
      $scope.showTable = false;
      $scope.showReplace = false;

      Process.query(
        {
          q: $scope.postContents,
          type: $scope.type,
        }, 
        function (data) {
          $scope.results = data;
          $scope.loader = false;
          $scope.showTable = true;
          $scope.showReplace = true;
      });
    };

    $scope.findReplace = function (id) {
      $scope.replaceLoader = true;
      console.log(id);

      Replace.save(
        {
          id: id,
          find: $scope.find,
          replace: $scope.replace,
          type: $scope.type,
        }, 
        function (data) {
          if (data.result == true) {
            $scope.msgclass = "updated";
            $scope.message = "Successfully replaced text in " + data.title;
            $scope.replaceLoader = false;
            return;
          }
          $scope.msgclass = "alternate";
          $scope.message = "Error: " + data.reason;
          $scope.replaceLoader = false;
      });
    };


  });

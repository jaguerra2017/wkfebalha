/*
 * File for handling factories for HeadQuarters controllers
 * */

(function () {
  'use strict';

  /* Declare app level module which depends on views, and components */
  angular.module('BncBackend.reserveFactory', []);


  /* Factory for handling HeadQuarters functions */
  function reserveFact($http) {
    var factory = {};
    toastr.options.timeOut = 1000;

    factory.loadInitialsData = function($scope, searchParametersCollection, successCallBackFn, errorCallBackFn){
      $http({
        method: "post",
        url: Routing.generate('reserve_initials_data'),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        data:$.param(searchParametersCollection)
      }).then(function successCallback(response) {

        if(successCallBackFn != undefined && typeof successCallBackFn == 'function'){
          successCallBackFn(response);
        }

      }, function errorCallback(response) {
        if(errorCallBackFn != undefined && typeof errorCallBackFn == 'function'){
          errorCallBackFn(response);
        }
      });
    }

    factory.loadSeats = function($scope,searchParametersCollection, successCallBackFn, errorCallBackFn){
      $http({
        method: "post",
        url: Routing.generate('reserve_seats_data'),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        data:$.param(searchParametersCollection)
      }).then(function successCallback(response) {

        if(successCallBackFn != undefined && typeof successCallBackFn == 'function'){
          successCallBackFn(response);
        }

      }, function errorCallback(response) {
        if(errorCallBackFn != undefined && typeof errorCallBackFn == 'function'){
          errorCallBackFn(response);
        }
      });
    }

    factory.enableSeatsToSale = function($scope, data, successCallBackFn){
      $http({
        method: "post",
        url: Routing.generate('enable_seats_to_sale'),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        data:$.param(data)
      }).then(function successCallback(response) {
        $scope.toggleDataLoader();
        if(successCallBackFn != undefined && typeof successCallBackFn == 'function'){
          //toastr.options.timeOut = 3000;
          toastr.success(response.data.message,"¡Hecho!");
          successCallBackFn(response);
        }

      }, function errorCallback(response) {
        $scope.model.processingData = true;
        $scope.toggleDataLoader();
        toastr.options.timeOut = 5000;
        if(response.data && response.data.message){
          toastr.error(response.data.message,"¡Error!");
        }
        else{
          toastr.error("Esta operación no ha podido ejecutarse.","¡Error!");
        }
      });
    }

    factory.saveBookingData = function($scope, data, successCallBackFn){
      $http({
        method: "post",
        url: Routing.generate('save_booking_data'),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        data:$.param(data)
      }).then(function successCallback(response) {
        $scope.toggleDataLoader();
        if(successCallBackFn != undefined && typeof successCallBackFn == 'function'){
          //toastr.options.timeOut = 3000;
          toastr.success(response.data.message,"¡Hecho!");
          successCallBackFn(response);
        }

      }, function errorCallback(response) {
        $scope.model.processingData = true;
        $scope.toggleDataLoader();
        toastr.options.timeOut = 5000;
        if(response.data && response.data.message){
          toastr.error(response.data.message,"¡Error!");
        }
        else{
          toastr.error("Esta operación no ha podido ejecutarse.","¡Error!");
        }
      });

    }

    return factory;
  }


  /* Declare factories functions for this module */
  angular.module('BncBackend.headquartersFactory').factory('reserveFact',reserveFact);


})();
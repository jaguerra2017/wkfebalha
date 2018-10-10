/*
 * File for handling factories for HeadQuarters controllers
 * */

(function () {
  'use strict';

  /* Declare app level module which depends on views, and components */
  angular.module('BncFrontend.programFactory', []);


  /* Factory for handling HeadQuarters functions */
  function programFact($http) {
    var factory = {};
    // toastr.options.timeOut = 1000;

    factory.loadInitialsData = function($scope, searchParametersCollection, successCallBackFn, errorCallBackFn){
      $http({
        method: "post",
        url: Routing.generate('frontend_program_initials_data'),
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
        url: Routing.generate('frontend_program_seats_data'),
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
    return factory;
  }


  /* Declare factories functions for this module */
  angular.module('BncFrontend.programFactory').factory('programFact',programFact);


})();
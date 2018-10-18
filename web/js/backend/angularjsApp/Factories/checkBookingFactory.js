/*
 * File for handling factories for Bookings controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.checkBookingFactory', []);


    /* Factory for handling Bookings functions */
    function checkBookingFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope, successCallBackFn, errorCallBackFn){
            $http({
                method: "post",
                url: Routing.generate('check_booking_view_initials_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
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

        factory.getBookingsData = function($scope,searchParametersCollection, successCallBackFn, errorCallBackFn){
            $http({
                method: "post",
                url: Routing.generate('bookings_data'),
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
    angular.module('BncBackend.checkBookingFactory').factory('checkBookingFact',checkBookingFact);


})();
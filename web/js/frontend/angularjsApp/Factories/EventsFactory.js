/*
 * File for handling factories for Events controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend.eventsFactory', []);


    /* Factory for handling Events functions */
    function eventsFact($http) {
        var factory = {};

        factory.loadInitialsData = function($scope, successCallBackFn, errorCallBackFn){
            $http({
                method: "post",
                url: Routing.generate('events_view_initials_data'),
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

        factory.getEventsData = function(searchParametersCollection, successCallBackFn, errorCallBackFn){
            $http({
                method: "post",
                url: Routing.generate('dh_events_collection'),
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
    angular.module('BncFrontend.eventsFactory').factory('eventsFact',eventsFact);


})();
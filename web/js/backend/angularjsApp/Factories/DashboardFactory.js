/*
 * File for handling factories for Dashboard controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.dashboardFactory', []);


    /* Factory for handling Dashboard functions */
    function dashboardFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('dashboard_view_initials_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(function successCallback(response) {
                if(successFnCallBack != undefined && typeof successFnCallBack == 'function'){
                    successFnCallBack(response);
                }

            }, function errorCallback(response) {
                if(errorFnCallBack != undefined && typeof errorFnCallBack == 'function'){
                    errorFnCallBack(response);
                }
            });
        }

        return factory;
    }

    
    /* Declare factories functions for this module */
    angular.module('BncBackend.dashboardFactory').factory('dashboardFact',dashboardFact);


})();
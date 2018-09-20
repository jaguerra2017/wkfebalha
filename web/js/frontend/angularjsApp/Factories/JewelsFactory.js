/*
 * File for handling factories for Jewels controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend.jewelsFactory', []);


    /* Factory for handling Jewels functions */
    function jewelsFact($http) {
        var factory = {};
        //toastr.options.timeOut = 1000;

        factory.getJewelsData = function($searchParamsCollection, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('dh_jewels_collection'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param($searchParamsCollection)
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
    angular.module('BncFrontend.jewelsFactory').factory('jewelsFact',jewelsFact);


})();
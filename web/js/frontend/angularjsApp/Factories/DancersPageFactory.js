/*
 * File for handling factories for Pages controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend.dancersPageFactory', []);


    /* Factory for handling Pages functions */
    function dancersPageFact($http) {
        var factory = {};
        //toastr.options.timeOut = 1000;

        factory.getPageData = function($searchParamsCollection, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('dh_page_dancers_collection'),
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
    angular.module('BncFrontend.dancersPageFactory').factory('dancersPageFact',dancersPageFact);


})();
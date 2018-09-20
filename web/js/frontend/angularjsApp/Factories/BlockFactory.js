/*
 * File for handling factories for Blocks
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend.blockFactory', []);


    /* Factory for handling Blocks functions */
    function blockFact($http) {
        var factory = {};


        factory.getBlocksData = function($scope,searchParametersCollection, fnSuccessCallBack, fnErrorCallBack){
            $http({
                method: "post",
                url: Routing.generate('dh_blocks_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(searchParametersCollection)
            }).then(function successCallback(response) {

                if(fnSuccessCallBack != undefined && typeof fnSuccessCallBack == 'function'){
                    fnSuccessCallBack(response);
                }
                else{
                    return response;
                }

            }, function errorCallback(response) {

                if(fnErrorCallBack != undefined && typeof fnErrorCallBack == 'function'){
                    fnErrorCallBack(response);
                }
                else{

                }
            });
        }

        factory.getBlockElementsData = function($scope,searchParametersCollection, fnSuccessCallBack, fnErrorCallBack){
            $http({
                method: "post",
                url: Routing.generate('dh_blocks_elements_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(searchParametersCollection)
            }).then(function successCallback(response) {

                if(fnSuccessCallBack != undefined && typeof fnSuccessCallBack == 'function'){
                    fnSuccessCallBack(response);
                }
                else{
                    return response;
                }

            }, function errorCallback(response) {

                if(fnErrorCallBack != undefined && typeof fnErrorCallBack == 'function'){
                    fnErrorCallBack(response);
                }
                else{

                }
            });
        }

        return factory;
    }

    
    /* Declare factories functions for this module */
    angular.module('BncFrontend.blockFactory').factory('blockFact',blockFact);


})();
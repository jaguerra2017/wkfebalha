/*
 * File for handling factories for Blocks
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.blockFactory', []);


    /* Factory for handling Blocks functions */
    function blockFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.getBlocksData = function($scope,searchParametersCollection, fnSuccessCallBack, fnErrorCallBack){
            $http({
                method: "post",
                url: Routing.generate('blocks_data'),
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
                    toastr.options.timeOut = 5000;
                    toastr.error("Ha ocurrido un error, por favor intente nuevamente en unos minutos." +
                        " Si al intentar nuevamente persiste esta notificación de ERROR, asegúrese de que no sea debido " +
                        "a la conexión o falla en servidores. De lo contrario contacte a los DESARROLLADORES.");
                }
            });
        }

        factory.getBlockElementsData = function($scope,searchParametersCollection, fnSuccessCallBack, fnErrorCallBack){
            $http({
                method: "post",
                url: Routing.generate('blocks_elements_data'),
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
                    toastr.options.timeOut = 5000;
                    toastr.error("Ha ocurrido un error, por favor intente nuevamente en unos minutos." +
                        " Si al intentar nuevamente persiste esta notificación de ERROR, asegúrese de que no sea debido " +
                        "a la conexión o falla en servidores. De lo contrario contacte a los DESARROLLADORES.");
                }
            });
        }

        factory.saveBlockData = function($scope, data, action, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('blocks_'+action),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(data)
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

        factory.changeBlockPriority = function($scope, data, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('blocks_change_priority'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(data)
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

        factory.deleteBlock = function($scope, data, successFnCallBack, errorFnCallBack){

            $http({
                method: "post",
                url: Routing.generate('blocks_delete'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(data)
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
    angular.module('BncBackend.blockFactory').factory('blockFact',blockFact);


})();
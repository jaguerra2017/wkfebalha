/*
 * File for handling factories for Composition controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.compositionFactory', []);


    /* Factory for handling Composition functions */
    function compositionFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope, successCallBackFn, errorCallBackFn){
            $http({
                method: "post",
                url: Routing.generate('composition_view_initials_data'),
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

        factory.getCompositionData = function($scope,searchParametersCollection, successCallBackFn, errorCallBackFn){
            $http({
                method: "post",
                url: Routing.generate('composition_data'),
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

        factory.saveCompositionData = function($scope, data, option, action){
            $http({
                method: "post",
                url: Routing.generate('composition_'+action),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(data)
            }).then(function successCallback(response) {
                $scope.model.processingData = false;
                $scope.toggleDataLoader();
                if(response.data.success == 0){
                    toastr.options.timeOut = 5000;
                    toastr.error(response.data.message,"Error");
                }
                else{
                    $scope.clearErrorsCompositionForm();
                    if(option == 'clear'){
                        $scope.clearCompositionForm();
                    }
                    else if(option == 'close'){
                        $scope.clearCompositionForm();
                        $scope.hideCompositionForm();
                    }
                    else if(option == 'stay'){
                        $scope.model.createAction = false;
                        $scope.model.selectedComposition.id = response.data.compositionId;
                    }
                    //toastr.options.timeOut = 3000;
                    toastr.success(response.data.message,"¡Hecho!");
                }

                $scope.goToTop();

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

        factory.deleteComposition = function($scope, data){

            $http({
                method: "post",
                url: Routing.generate('composition_delete'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(data)
            }).then(function successCallback(response)
            {
                if(response.data.success == 0){
                    toastr.options.timeOut = 5000;
                    toastr.error(response.data.message,"Error");
                }
                else{
                    toastr.success(response.data.message,"¡Hecho!");
                }

                $scope.getComposition();

            }, function errorCallback(response) {
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
    angular.module('BncBackend.compositionFactory').factory('compositionFact',compositionFact);


})();
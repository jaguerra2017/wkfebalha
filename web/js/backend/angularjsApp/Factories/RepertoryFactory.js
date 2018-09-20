/*
 * File for handling factories for Repertory controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.repertoryFactory', []);


    /* Factory for handling Repertory functions */
    function repertoryFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope, successCallBackFn, errorCallBackFn){
            $http({
                method: "post",
                url: Routing.generate('repertory_view_initials_data'),
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

        factory.getRepertoryData = function($scope,searchParametersCollection, successCallBackFn, errorCallBackFn){
            $http({
                method: "post",
                url: Routing.generate('repertory_data'),
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

        factory.saveRepertoryData = function($scope, data, option, action){
            $http({
                method: "post",
                url: Routing.generate('repertory_'+action),
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
                    $scope.clearErrorsRepertoryForm();
                    if(option == 'clear'){
                        $scope.clearRepertoryForm();
                    }
                    else if(option == 'close'){
                        $scope.clearRepertoryForm();
                        $scope.hideRepertoryForm();
                    }
                    else if(option == 'stay'){
                        $scope.model.createAction = false;
                        $scope.model.selectedRepertory.id = response.data.repertoryId;
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

        factory.deleteRepertory = function($scope, data){

            $http({
                method: "post",
                url: Routing.generate('repertory_delete'),
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

                $scope.getRepertory();

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
    angular.module('BncBackend.repertoryFactory').factory('repertoryFact',repertoryFact);


})();
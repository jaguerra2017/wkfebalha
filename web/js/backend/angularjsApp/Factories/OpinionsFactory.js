/*
 * File for handling factories for Opinions controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.opinionsFactory', []);


    /* Factory for handling Opinions functions */
    function opinionsFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('opinions_view_initials_data'),
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

        factory.getOpinionsData = function($scope,searchParametersCollection, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('opinions_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(searchParametersCollection)
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

        factory.saveOpinionsData = function($scope, data, action, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('opinions_'+action),
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

        factory.deleteOpinions = function($scope, data, successFnCallBack, errorFnCallBack){

            $http({
                method: "post",
                url: Routing.generate('opinions_delete'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(data)
            }).then(function successCallback(response)
            {
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
    angular.module('BncBackend.opinionsFactory').factory('opinionsFact',opinionsFact);


})();
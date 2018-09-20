/*
 * File for handling factories for Comments controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend.commentsFactory', []);


    /* Factory for handling Comments functions */
    function commentsFact($http) {
        var factory = {};

        factory.loadInitialsData = function($scope, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('dh_comments_view_initials_data'),
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

        factory.getCommentsData = function($scope,searchParametersCollection, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('dh_comments_data'),
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

        factory.saveCommentsData = function($scope, data, action, successFnCallBack, errorFnCallBack){
            $http({
                method: "post",
                url: Routing.generate('dh_comments_'+action),
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
    angular.module('BncFrontend.commentsFactory').factory('commentsFact',commentsFact);


})();
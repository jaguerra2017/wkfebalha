/*
 * File for handling factories for Users controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.usersFactory', []);


    /* Factory for handling Users functions */
    function usersFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope,searchParametersCollection){

        }

        factory.getUsersData = function($scope,searchParametersCollection){

        }

        factory.getUserData = function($scope, data, fnCallBack){
            $http({
                method: "post",
                url: Routing.generate('users_user_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(data)
            }).then(function successCallback(response) {
                $scope.toggleDataLoader();
                if(response.data.success == 0){
                    toastr.options.timeOut = 5000;
                    toastr.error(response.data.message,"Error");
                }
                else{
                    if(fnCallBack != undefined){
                        fnCallBack(response.data.userData);
                    }
                }

            }, function errorCallback(response) {
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

        factory.saveUserData = function($scope, data, option, action, fnCallBack){
            $http({
                method: "post",
                url: Routing.generate('users_user_'+action),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(data)
            }).then(function successCallback(response) {
                if(response.data.success == 0){
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 5000;
                    toastr.error(response.data.message,"Error");
                }
                else{ console.log(typeof fnCallBack);
                    if(fnCallBack != undefined && (typeof fnCallBack == 'function')){
                        fnCallBack();
                    }
                    else{
                        $scope.toggleDataLoader();
                        //$scope.clearErrorsUsersForm();
                        if(option == 'clear'){
                            //$scope.clearUsersForm();
                        }
                        else if(option == 'close'){
                            //$scope.clearUsersForm();
                            //$scope.hideUsersForm();
                        }
                        //toastr.options.timeOut = 3000;
                        toastr.success(response.data.message,"¡Hecho!");
                    }
                }

                $scope.goToTop();

            }, function errorCallback(response) {
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

        factory.deleteUsers = function($scope, data){

        }

        return factory;
    }

    
    /* Declare factories functions for this module */
    angular.module('BncBackend.usersFactory').factory('usersFact',usersFact);


})();
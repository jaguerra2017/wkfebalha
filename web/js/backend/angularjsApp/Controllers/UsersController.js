/*
 * File for handling controllers for Backend Users Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.usersController', ['BncBackend.usersFactory']);


    /* Controller for handling Users functions */
    function usersCtrller($scope, usersFact){

        /*
         * Global variables
         *
         * */
        var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");



        /* post-save profile actions*/
        $scope.saveProfilePostActions = function(){
            $scope.clearErrorsProfileForm();
            $scope.clearProfileForm();
            $scope.model.processingData = false;
            $scope.toggleDataLoader();
            toastr.options.timeOut = 5000;
            toastr.success('Datos guardados. ¡Le RECOMENDAMOS que refresque la página para que los cambios tomen efecto!',"¡Hecho!");
        }

        /*assign user profile*/
        $scope.assignUserProfile = function(user){
            $scope.selecteduser = user;
            $scope.model.userAvatar.url = user.avatar_url;
        }

        /*clear errors form*/
        $scope.clearErrorsProfileForm = function(){
            $scope.model.userFullNameHasError = false;
            $scope.model.userPasswordHasError = false;
            $scope.model.userRePasswordHasError = false;
        }

        /*clear form*/
        $scope.clearProfileForm = function(){
            $scope.selecteduser.password = null;
            $scope.selecteduser.repassword = null;
            $scope.model.passwordInputType = "password";
        }

        /*change input password type*/
        $scope.changeInputPsswordType = function(){
            if($scope.model.passwordInputType == "password"){
                $scope.model.passwordInputType = "text";
            }
            else{
                $scope.model.passwordInputType = "password";
            }
        }

        /*hide profile modal*/
        $scope.hideUsersForm = function(){
            $scope.clearErrorsProfileForm();
        }

        /*save profile*/
        $scope.saveProfile = function(){
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsProfileForm();

                if($scope.selecteduser.full_name == null ||
                    !alfaNumericRegExpr.test($scope.selecteduser.full_name) ||
                    ($scope.selecteduser.password != null && $scope.selecteduser.repassword != $scope.selecteduser.password) ||
                    ($scope.selecteduser.repassword != null && $scope.selecteduser.repassword != $scope.selecteduser.password))
                {
                    canProceed = false;

                    if($scope.selecteduser.full_name == null ||
                        !alfaNumericRegExpr.test($scope.selecteduser.full_name)){
                        $scope.model.userFullNameHasError = true;
                    }

                    if($scope.selecteduser.password != null && $scope.selecteduser.repassword != $scope.selecteduser.password){
                        $scope.model.userPasswordHasError = true;
                        $scope.model.userRePasswordHasError = true;
                    }

                    if($scope.selecteduser.repassword != null && $scope.selecteduser.repassword != $scope.selecteduser.password){
                        $scope.model.userRePasswordHasError = true;
                        $scope.model.userPasswordHasError = true;
                    }
                }

                if(canProceed){
                    if($scope.model.userAvatar.url != $scope.selecteduser.url){
                        $scope.selecteduser.avatar_id = $scope.model.userAvatar.id;
                    }
                    var userData = $scope.selecteduser;
                    usersFact.saveUserData($scope, userData, 'close', 'edit', function(){
                        $scope.saveProfilePostActions();
                    });
                }
                else{
                    $scope.toggleDataLoader();
                    $scope.model.processingData = false;
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }
            }
        }

        /* toggle data-loading message */
        $scope.toggleDataLoader = function()
        {
            $scope.model.loadingData = !$scope.model.loadingData;
        }


        $scope.initVisualization = function (){
            /*list view variables*/
            $scope.model.loadingData = false;
            /*form view variables*/
            $scope.model.processingData = false;
            $scope.model.passwordInputType = "password";
        }
        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            $scope.model.showUsersForm = true;
            $scope.model.formActiveView = 'general-info';
            $scope.model.userAvatar = {};
            $scope.selecteduser = null;
            if($scope.selecteduser == null){
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    'searchByUserId': true,
                    'userId': null
                };
                usersFact.getUserData($scope, searchParametersCollection, function(user){
                    $scope.assignUserProfile(user);
                    $scope.initVisualization();
                });
            }
            else{
                $scope.model.userAvatar.url = $scope.selecteduser.url;
            }
        }
        init();

    }


    /* Declaring controllers functions for this module */
    angular.module('BncBackend.usersController').controller('usersCtrller',usersCtrller);
})();
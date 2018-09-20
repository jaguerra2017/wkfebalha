/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.profileDirective', ['BncBackend.usersFactory']);

    /* Declaring directive functions for this module */
    angular.module('BncBackend.profileDirective').directive('profile', [function()
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            scope : {
                usebuttonlinktag : "@",
                linktext: "@",
                selecteduser: "="
            },
            controller: function($scope, $element, usersFact) {

                /*
                 * Global variables
                 *
                 * */
                var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");



                /* post-save profile actions*/
                $scope.saveProfilePostActions = function(){
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    $scope.hideProfileModal();
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
                    $scope.selecteduser.full_name = null;
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
                $scope.hideProfileModal = function(){
                    $scope.clearErrorsProfileForm();
                    $('#profile-modal').modal('hide');
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

                /*show profile modal*/
                $scope.showProfileModal = function(){

                    $('#profile-modal').modal('show');
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
                    $scope.model.userAvatar = {};
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
            },
            template:
                '<div>' +
                    '<a data-ng-if="usebuttonlinktag == 0" class=" " ' +
                    'data-ng-click="showProfileModal()">' +
                        '<i class="icon-user"></i>' +
                        ' [[linktext]]' +
                    '</a>' +

                    '<div id="profile-modal" class="modal fade" tabindex="-1" data-width="600" data-backdrop="static" data-keyboard="false">'+
                        '<div class="modal-body min-height-400">'+
                            '<form class="form horizontal-form" style="position: relative;min-height: 150px;">'+
                                '<div class="form-body">'+
                                    '<div class="row" style="border-bottom: 1px solid #eee;padding-bottom: 20px;margin-bottom: 20px;">' +
                                        '<div class="col-xs-12 col-md-5">'+
                                            '<div class="profile-image-container">' +
                                                '<!-- Directive mediaSelector(media-selector) --> ' +
                                                '<media-selector selectedimage="model.userAvatar"></media-selector>' +
                                                '<img data-ng-src="[[model.userAvatar.url]]">' +
                                            '</div>'+
                                        '</div>' +
                                        '<div class="col-xs-12 col-md-7">' +
                                            '<div class="form-group">' +
                                                '<label class="control-label width-100-percent">Usuario</label>'+
                                                '<span>[[selecteduser.user_name]]</span>'+
                                            '</div>'+
                                            '<div class="form-group">' +
                                                '<label class="control-label width-100-percent">Rol</label>'+
                                                '<span>[[selecteduser.role_name]]</span>'+
                                            '</div>'+
                                            '<div class="form-group [[model.userFullNameHasError?\'has-error\':\'\']]">' +
                                                '<label class="control-label width-100-percent">Nombre y apellidos</label>'+
                                                '<input class="form-control" type="text" data-ng-model="selecteduser.full_name">' +
                                                '<span class="help-block">' +
                                                    '<p data-ng-if="model.userFullNameHasError">'+
                                                        'Valor incorrecto o en blanco.'+
                                                    '</p>'+
                                                '</span>'+
                                            '</div>'+
                                        '</div>' +
                                    '</div>' +
                                    '<div class="row">' +
                                    '<div class="col-xs-12">' +
                                        '<div class="form-group [[model.userPasswordHasError?\'has-error\':\'\']]">' +
                                            '<label class="control-label" style="width: 90%;">Contraseña</label>' +
                                            '<btn type="button" class="btn btn-link btn-icon-only" ' +
                                            'data-ng-click="changeInputPsswordType()">' +
                                                '<i class="fa [[model.passwordInputType == \'password\' ? \'fa-eye\' : \'fa-eye-slash\']]"></i>' +
                                            '</btn>'+
                                            '<input class="form-control" type="[[model.passwordInputType]]" data-ng-model="selecteduser.password">' +
                                            '<span class="help-block">' +
                                                '<p data-ng-if="model.userPasswordHasError">'+
                                                'Valor incorrecto o en blanco.'+
                                                '</p>'+
                                            '</span>'+
                                        '</div>'+
                                        '<div class="form-group [[model.userRePasswordHasError?\'has-error\':\'\']]">' +
                                            '<label class="control-label width-100-percent">Repita la contraseña</label>'+
                                            '<input class="form-control" type="[[model.passwordInputType]]" data-ng-model="selecteduser.repassword">' +
                                            '<span class="help-block">' +
                                                '<p data-ng-if="!model.userRePasswordHasError">'+
                                                'En caso de cambiar la contraseña los valores deben de coincidir.'+
                                                '</p>'+
                                                '<p data-ng-if="model.userRePasswordHasError">'+
                                                'Valor incorrecto o en blanco.'+
                                                '</p>'+
                                            '</span>'+
                                        '</div>'+
                                    '</div>'+
                                    '</div>' +

                                    '<div data-ng-show="model.loadingData">'+
                                        '<div class="data-loader">' +
                                            '<div class="sk-data-loader-spinner sk-spinner-three-bounce">' +
                                                '<div class="sk-bounce1"></div>' +
                                                '<div class="sk-bounce2"></div>' +
                                                '<div class="sk-bounce3"></div>' +
                                            '</div>' +
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="form-actions" style="background-color:white;">'+
                                    '<div class="row">'+
                                        '<div class="col-xs-12 col-md-offset-4 col-md-8">'+
                                            '<button class="btn default btn-footer" type="button" ' +
                                            'data-ng-click="hideProfileModal()">'+
                                            'Cancelar </button>'+
                                            '<button class="btn blue btn-blue btn-footer" type="submit" ' +
                                            'data-ng-click="saveProfile()">'+
                                            'Guardar y cerrar </button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</form>'+
                        '</div>'+
                    '</div>'+
                '</div>'
        }

        return directiveDefinitionObject;
    }]);
})();
/*
 * File for handling factories for Publications controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.publicationsFactory', []);


    /* Factory for handling Publications functions */
    function publicationsFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope){
            $http({
                method: "post",
                url: Routing.generate('publications_view_initials_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(function successCallback(response) {

                $scope.model.publicationsCollection = response.data.initialsData.publicationsDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showPublicationsForm = response.data.initialsData.showPublicationsForm;

                $scope.initVisualization();

                if(showPublicationsForm == true){
                    $scope.createPublications();
                }

            }, function errorCallback(response) {
                toastr.options.timeOut = 16000;
                if(response.data && response.data.message){
                    toastr.error(response.data.message,"Error");
                }
                else{
                    toastr.options.timeOut = 5000;
                    toastr.error("Ha ocurrido un error, por favor intente nuevamente en unos minutos." +
                        " Si al intentar nuevamente persiste esta notificación de ERROR, asegúrese de que no sea debido " +
                        "a la conexión o falla en servidores. De lo contrario contacte a los DESARROLLADORES.");
                }
            });
        }

        factory.getPublicationsData = function($scope,searchParametersCollection, fnCallBack){
            $http({
                method: "post",
                url: Routing.generate('publications_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(searchParametersCollection)
            }).then(function successCallback(response) {
                if(fnCallBack != undefined && typeof fnCallBack == 'function'){
                    fnCallBack(response);
                }
                else{
                    $scope.model.publicationsCollection = response.data.publicationsDataCollection;
                    $scope.updatePaginationValues();
                    $scope.toggleDataLoader();
                }
            }, function errorCallback(response) {

                $scope.updatePaginationValues();
                $scope.toggleDataLoader();
                toastr.options.timeOut = 16000;
                if(response.data && response.data.message){
                    toastr.error(response.data.message,"Error");
                }
                else{
                    toastr.options.timeOut = 5000;
                    toastr.error("Ha ocurrido un error, por favor intente nuevamente en unos minutos." +
                        " Si al intentar nuevamente persiste esta notificación de ERROR, asegúrese de que no sea debido " +
                        "a la conexión o falla en servidores. De lo contrario contacte a los DESARROLLADORES.");
                }
            });
        }

        factory.savePublicationsData = function($scope, data, option, action){
            $http({
                method: "post",
                url: Routing.generate('publications_'+action),
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
                    $scope.clearErrorsPublicationsForm();
                    if(option == 'clear'){
                        $scope.clearPublicationsForm();
                    }
                    else if(option == 'close'){
                        $scope.clearPublicationsForm();
                        $scope.hidePublicationsForm();
                    }
                    else if(option == 'stay'){
                        $scope.model.createAction = false;
                        $scope.model.selectedPublication.id = response.data.publicationId;
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

        factory.deletePublications = function($scope, data){

            $http({
                method: "post",
                url: Routing.generate('publications_delete'),
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

                $scope.getPublications();

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
    angular.module('BncBackend.publicationsFactory').factory('publicationsFact',publicationsFact);


})();
/*
 * File for handling factories for Taxonomy controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.taxonomyFactory', []);


    /* Factory for handling Taxonomies functions */
    function taxonomyFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope,searchParametersCollection){
            $http({
                method: "post",
                url: Routing.generate('taxonomies_view_initials_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(function successCallback(response) {

                $scope.taxonomyModel.taxonomiesCollection = response.data.initialsData.taxonomiesDataCollection;
                $scope.taxonomyModel.taxonomyTypesCollection = response.data.initialsData.taxonomyTypesDataCollection;
                var showTaxonomyForm = response.data.initialsData.showTaxonomyForm;


                $scope.initVisualization('disable');
                if(showTaxonomyForm == true){
                    $scope.createTaxonomy();
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

        factory.getTaxonomiesData = function($scope,searchParametersCollection, fnCallBack){
            $http({
                method: "post",
                url: Routing.generate('taxonomies_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(searchParametersCollection)
            }).then(function successCallback(response) {
                $scope.toggleDataLoader();
                if(fnCallBack != undefined && typeof fnCallBack == 'function'){
                    fnCallBack(response);
                }
                else{
                    $scope.taxonomyModel.taxonomiesCollection = response.data.taxonomiesDataCollection;
                    $scope.updatePaginationValues();
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

        factory.SaveTaxonomyData = function($scope, data, option, action){
            $http({
                method: "post",
                url: Routing.generate('taxonomies_'+action),
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
                    $scope.clearErrorsTaxonomyForm();
                    if(option == 'clear'){
                        $scope.clearTaxonomyForm();
                    }
                    else if(option == 'close'){
                        $scope.clearTaxonomyForm();
                        $scope.hideTaxonomyForm();
                    }
                    //toastr.options.timeOut = 3000;
                    toastr.success(response.data.message,"¡Hecho!");
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

        factory.DeleteTaxonomies = function($scope, data){

            $http({
                method: "post",
                url: Routing.generate('taxonomies_delete'),
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

                $scope.getTaxonomies();

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
    angular.module('BncBackend.taxonomyFactory').factory('taxonomyFact',taxonomyFact);


})();
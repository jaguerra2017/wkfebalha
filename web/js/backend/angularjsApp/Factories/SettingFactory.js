/*
 * File for handling factories for Setting controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.settingFactory', []);


    /* Factory for handling Settings functions */
    function settingFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope,searchParametersCollection){
            $http({
                method: "post",
                url: Routing.generate('settings_view_initials_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(searchParametersCollection)
            }).then(function successCallback(response) {

                $scope.model.availabilitySettingsObject = response.data.initialsData.settingsData;
                $scope.initVisualization();

            }, function errorCallback(response) {
                toastr.options.timeOut = 16000;
                console.log(response);
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

        factory.getSettingsData = function($scope,searchParametersCollection){
            $http({
                method: "post",
                url: Routing.generate('settings_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(searchParametersCollection)
            }).then(function successCallback(response) {

                switch(searchParametersCollection.section){
                    case 'availability':
                        $scope.model.availabilitySettingsObject = response.data.settingsData;
                    break;
                    case 'appearance':
                        $scope.model.appearanceSettingsObject = response.data.settingsData;
                        $scope.model.welcomeImage.url = $scope.model.appearanceSettingsObject.configurations[0].welcome_image_url;
                    break;
                    case 'notification':
                        $scope.model.notificationSettingsObject = response.data.settingsData;
                    break;
                    case 'media':
                        $scope.model.mediaSettingsObject = response.data.settingsData;
                    break;
                }

                $scope.toggleDataLoader();
                $scope.afterLoadSectionData(searchParametersCollection.section);

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

        factory.getAppearanceMenusData = function($scope){
            $http({
                method: "post",
                url: Routing.generate('settings_appearance_menu_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(function successCallback(response) {
                $scope.toggleDataLoader();
                $scope.model.appearanceMenusCollection = response.data.appearanceMenusData;
                $scope.model.appearanceMenuItemTypesCollection = response.data.appearanceMenuItemTypesData;

            }, function errorCallback(response) {
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

        factory.getAppearanceMenuItemsData = function($scope, data){
            $http({
                method: "post",
                url: Routing.generate('settings_appearance_menu_items_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(data)
            }).then(function successCallback(response) {
                $scope.toggleDataLoader();
                $scope.model.appearanceMenuItemsCollection = response.data.appearanceMenuItemsData;

            }, function errorCallback(response) {
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

        factory.SaveSettingsData = function($scope, data){
            $http({
                method: "post",
                url: Routing.generate('settings_save_data'),
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
                    switch (data.section){
                        case 'availability':
                            $scope.clearErrorsAvailabilityForm();
                        break;
                        case 'appearance':
                            $scope.clearErrorsAppearanceThemeForm();
                        break;
                        case 'notification':
                            break;
                        case 'media':
                        break;
                    }
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

        factory.SaveAppearanceMenuData = function($scope, data, option, action){
            $http({
                method: "post",
                url: Routing.generate('settings_appearance_menu_'+action),
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
                    $scope.clearErrorsAppearanceMenuForm();
                    $scope.clearAppearanceMenuForm();
                    if(option == 'close'){
                        $scope.hideAppearanceMenuForm();
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

        factory.SaveAppearanceMenuItemData = function($scope, data, option, action){
            $http({
                method: "post",
                url: Routing.generate('settings_appearance_menu_item_'+action),
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
                    $scope.clearErrorsAppearanceMenuItemForm();
                    $scope.clearAppearanceMenuItemForm();
                    if(option == 'close'){
                        $scope.hideAppearanceMenuItemForm();
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

        factory.DeleteAppearanceMenus = function($scope, data){

            $http({
                method: "post",
                url: Routing.generate('settings_appearance_menu_delete'),
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

                $scope.getAppearanceMenus();

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

        factory.DeleteAppearanceMenuItems = function($scope, data){

            $http({
                method: "post",
                url: Routing.generate('settings_appearance_menu_item_delete'),
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

                $scope.getAppearanceMenuItems();

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
    angular.module('BncBackend.settingFactory').factory('settingFact',settingFact);


})();
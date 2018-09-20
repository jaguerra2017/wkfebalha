/*
 * File for handling factories for Media controllers
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.mediaFactory', []);


    /* Factory for handling Media functions */
    function mediaFact($http) {
        var factory = {};
        toastr.options.timeOut = 1000;

        factory.loadInitialsData = function($scope){
            $http({
                method: "post",
                url: Routing.generate('media_view_initials_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(function successCallback(response) {

                $scope.model.imagesCollection = response.data.initialsData.imagesCollection;
                $scope.model.mediaUploadRestrictionsCollection = response.data.initialsData.uploadRestrictionsCollection;
                var showMediaForm = response.data.initialsData.showMediaForm;


                $scope.initVisualization();
                if(showMediaForm == true){
                    $scope.createMedia();
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

        factory.getMediaData = function($scope, searchParametersCollection, fnCallBack){
            $http({
                method: "post",
                url: Routing.generate('media_data'),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data:$.param(searchParametersCollection)
            }).then(function successCallback(response) {
                if(searchParametersCollection.mediaType == 'image'){
                    $scope.model.imagesCollection = response.data.mediaDataCollection;
                }
                else if(searchParametersCollection.mediaType == 'video'){
                    $scope.model.videosCollection = response.data.mediaDataCollection;
                }
                else if(searchParametersCollection.mediaType == 'gallery'){
                    $scope.model.galleriesCollection = response.data.mediaDataCollection;
                    $scope.model.galleryTypesCollection = response.data.galleryTypesCollection;
                    /*if($scope.model.galleryTypesCollection.length > 0){
                        $scope.model.selectedGalleryType = $scope.model.galleryTypesCollection[0];
                    }*/
                }
                $scope.updatePaginationValues(searchParametersCollection.mediaType);
                $scope.toggleDataLoader();

                if(fnCallBack != undefined && typeof fnCallBack == 'function'){
                    fnCallBack();
                }

            }, function errorCallback(response) {
                $scope.updatePaginationValues(searchParametersCollection.mediaType);
                $scope.toggleDataLoader();
                toastr.options.timeOut = 5000;
                if(response.data && response.data.message){
                    toastr.error(response.data.message,"Error");
                }
                else{
                    toastr.error("Ha ocurrido un error, por favor intente nuevamente en unos minutos." +
                        " Si al intentar nuevamente persiste esta notificación de ERROR, asegúrese de que no sea debido " +
                        "a la conexión o falla en servidores. De lo contrario contacte a los DESARROLLADORES.");
                }
            });
        }

        factory.saveMediaImageData = function($scope, Upload, selectedImages, selectedImagesInfoAssociated, action){
            if(action == 'create'){
                Upload.upload({
                    method: "post",
                    url:  Routing.generate('media_image_create'),
                    data: {media_images_uploaded: selectedImages, media_images_info_associated:selectedImagesInfoAssociated}
                })
                .then(function(response) {
                       if(response.data.success == 0){
                           toastr.options.timeOut = 5000;
                           toastr.error(response.data.message,"Error");
                       }
                        else{
                           toastr.options.timeOut = 3000;
                           toastr.success(response.data.message,"¡Hecho!");
                       }
                        $scope.hideMediaForm();
                        $scope.toggleDataLoader();
                }, function(response) {
                        toastr.options.timeOut = 5000;
                        toastr.error("Ha ocurrido un error, por favor intente nuevamente en unos minutos." +
                            " Si al intentar nuevamente persiste esta notificación de ERROR, asegúrese de que no sea debido " +
                            "a la conexión o falla en servidores. De lo contrario contacte a los DESARROLLADORES.")
                        $scope.hideMediaForm();
                        $scope.toggleDataLoader();
                    }, function(evt) {
                    // progress notify
                    //console.log('progress: ' + parseInt(100.0 * evt.loaded / evt.total) + '% file :'+ evt.config.data.file.name);
                })
                .catch(function(error){
                        toastr.options.timeOut = 5000;
                        toastr.error("Ha ocurrido un error, por favor intente nuevamente en unos minutos." +
                            " Si al intentar nuevamente persiste esta notificación de ERROR, asegúrese de que no sea debido " +
                            "a la conexión o falla en servidores. De lo contrario contacte a los DESARROLLADORES.")
                        $scope.hideMediaForm();
                        $scope.toggleDataLoader();
                    });
            }
            else{
                var data = selectedImagesInfoAssociated;
                $http({
                    method: "post",
                    url: Routing.generate('media_image_edit'),
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
                        $scope.clearErrorsMediaForm();
                        $scope.clearMediaForm();
                        $scope.hideMediaForm();
                        //toastr.options.timeOut = 3000;
                        toastr.success(response.data.message,"¡Hecho!");
                    }

                }, function errorCallback(response) {
                    $scope.model.processingData = false;
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
        }

        factory.deleteMediaImage = function($scope, data){

            $http({
                method: "post",
                url: Routing.generate('media_image_delete'),
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

                $scope.getMediaData();

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

        factory.saveMediaVideoData = function($scope, data, option, action){
            $http({
                method: "post",
                url: Routing.generate('media_video_'+action),
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
                    $scope.clearErrorsMediaForm();
                    $scope.clearMediaForm();
                    if(option != undefined && option == 'close'){
                        $scope.hideMediaForm();
                    }
                    //toastr.options.timeOut = 3000;
                    toastr.success(response.data.message,"¡Hecho!");
                }

            }, function errorCallback(response) {
                $scope.model.processingData = false;
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

        factory.deleteMediaVideo = function($scope, data){

            $http({
                method: "post",
                url: Routing.generate('media_video_delete'),
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

                $scope.getMediaData();

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

        factory.saveMediaGalleryData = function($scope, data, option, action){
            $http({
                method: "post",
                url: Routing.generate('media_gallery_'+action),
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
                    $scope.clearErrorsMediaForm();
                    $scope.clearMediaForm();
                    if(option != undefined && option == 'close'){
                        $scope.hideMediaForm();
                    }
                    //toastr.options.timeOut = 3000;
                    toastr.success(response.data.message,"¡Hecho!");
                }

            }, function errorCallback(response) {
                $scope.model.processingData = false;
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

        factory.deleteMediaGallery = function($scope, data){

            $http({
                method: "post",
                url: Routing.generate('media_gallery_delete'),
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

                $scope.getMediaData();

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
    angular.module('BncBackend.mediaFactory').factory('mediaFact',mediaFact);


})();
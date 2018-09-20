/*
 * File for handling controllers for Backend Media Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.mediaController', ['BncBackend.mediaFactory','ngFileUpload']);


    /* Controller for handling Media functions */
    function mediaCtrller($scope, $timeout, mediaFact, Upload){

        /*
         * Global variables
         *
         * */
        var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");


        /*
         * Operations Functions
         *
         * */
        /* clear errors of the form */
        $scope.clearErrorsMediaForm = function(){
            if($scope.model.activeView == 'image'){
                $scope.model.imageNameHasError = false;
                $scope.model.imageAlternativeTextHasError = false;
            }
            else if($scope.model.activeView == 'video'){
                $scope.model.videoNameHasError = false;
                $scope.model.videoUrlHasError = false;
            }
            else if($scope.model.activeView == 'gallery'){
                $scope.model.galleryNameHasError = false;
                $scope.model.galleryTypeHasError = false;
            }
        }

        /* clear media form */
        $scope.clearMediaForm = function(){
            if($scope.model.activeView == 'image'){
                $scope.model.imageName = null;
                $scope.model.imageDescription = null;
                $scope.model.imageAlternativeText = null;
            }
            else if($scope.model.activeView == 'video'){
                $scope.model.selectedVideo = {};
                $scope.model.selectedVideo.name_es = null;
                $scope.model.selectedVideo.description_es = null;
                $scope.model.selectedVideo.url = null;
                $scope.model.selectedVideo.origin = 'youtube';
                $scope.model.selectedVideo.http_protocol = 'http';
            }
            else if($scope.model.activeView == 'gallery'){
                $scope.model.selectedGallery = {};
                $scope.model.selectedGallery.name_es = null;
                $scope.model.selectedGallery.description_es = null;
                $scope.model.selectedGallery.gallery_type_id = null;
                $scope.model.selectedGallery.childrens = [];
            }
        }

        /* create media element */
        $scope.createMedia = function(){
            if($scope.model.activeView == 'image'){
                $scope.model.createImageAction = true;
                $scope.model.selectedImage = null;
            }
            else if($scope.model.activeView == 'video'){
                $scope.model.createVideoAction = true;
                $scope.model.selectedVideo = null;
            }
            else if($scope.model.activeView == 'gallery'){
                $scope.model.createGalleryAction = true;
                $scope.model.selectedGallery = null;
            }
            $scope.clearMediaForm();
            $scope.showMediaForm();
        }

        /*change video url protocol*/
        $scope.changeVideoUrlProtocol = function(protocol){
            $scope.model.selectedVideo.http_protocol = protocol;
        }

        /*delete Gallery element*/
        $scope.deleteGalleryChildren = function(children_id){
            var tempChildrens = [];
            for(var i=0; i<$scope.model.selectedGallery.childrens.length; i++){
                if(children_id != $scope.model.selectedGallery.childrens[i].id){
                    tempChildrens.push($scope.model.selectedGallery.childrens[i]);
                }
            }
            $scope.model.selectedGallery.childrens = tempChildrens;
        }

        /*delete Gallery*/
        $scope.deleteMediaGallery = function(gallery_id){
            swal({
                    title: "Confirme ",
                    text: "Si confirma no será capaz de recuperar estos datos.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#F3565D",
                    cancelButtonColor: "#E5E5E5 !important",
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm)
                    {
                        $scope.model.createGalleryAction = false;
                        var mediaGalleriesIdCollection = [];
                        if(typeof gallery_id == 'string'){
                            if($scope.model.galleriesCollection.length > 0){
                                for(var i=0; i<$scope.model.galleriesCollection.length; i++){
                                    if($scope.model.galleriesCollection[i].selected != undefined &&
                                        $scope.model.galleriesCollection[i].selected == true)
                                    {
                                        mediaGalleriesIdCollection.push($scope.model.galleriesCollection[i].id);
                                    }
                                }
                            }
                        }
                        else{
                            mediaGalleriesIdCollection.push(gallery_id);
                        }
                        var data = {
                            mediaGalleriesId: mediaGalleriesIdCollection
                        };
                        mediaFact.deleteMediaGallery($scope, data);
                    }
                });
        }

        /* delete media */
        $scope.deleteMediaImage = function(media_image_id)
        {
            swal({
                    title: "Confirme ",
                    text: "Si confirma no será capaz de recuperar estos datos.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#F3565D",
                    cancelButtonColor: "#E5E5E5 !important",
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm)
                    {
                        $scope.model.createImageAction = false;
                        var mediaImageIdCollection = [];
                        if(typeof media_image_id == 'string'){
                            if($scope.model.imagesCollection.length > 0){
                                for(var i=0; i<$scope.model.imagesCollection.length; i++){
                                    if($scope.model.imagesCollection[i].selected != undefined &&
                                        $scope.model.imagesCollection[i].selected == true)
                                    {
                                        mediaImageIdCollection.push($scope.model.imagesCollection[i].id);
                                    }
                                }
                            }
                        }
                        else{
                            mediaImageIdCollection.push(media_image_id);
                        }
                        var data = {
                            mediaImagesId: mediaImageIdCollection
                        };
                        mediaFact.deleteMediaImage($scope, data);
                    }
                });

        }

        /* delete media video*/
        $scope.deleteMediaVideo = function(media_video_id)
        {
            swal({
                    title: "Confirme ",
                    text: "Si confirma no será capaz de recuperar estos datos.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#F3565D",
                    cancelButtonColor: "#E5E5E5 !important",
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm)
                    {
                        $scope.model.createVideoAction = false;
                        var mediaVideoIdCollection = [];
                        if(typeof media_video_id == 'string'){
                            if($scope.model.videosCollection.length > 0){
                                for(var i=0; i<$scope.model.videosCollection.length; i++){
                                    if($scope.model.videosCollection[i].selected != undefined &&
                                        $scope.model.videosCollection[i].selected == true)
                                    {
                                        mediaVideoIdCollection.push($scope.model.imagesCollection[i].id);
                                    }
                                }
                            }
                        }
                        else{
                            mediaVideoIdCollection.push(media_video_id);
                        }
                        var data = {
                            mediaVideosId: mediaVideoIdCollection
                        };
                        mediaFact.deleteMediaVideo($scope, data);
                    }
                });

        }

        /* edit media element*/
        $scope.editMedia = function(media){
            $scope.clearMediaForm();
            if($scope.model.activeView == 'image'){
                $scope.model.createImageAction = false;
                $scope.model.selectedImage = media;
            }
            else if($scope.model.activeView == 'video'){
                $scope.model.createVideoAction = false;
                $scope.model.selectedVideo = media;
            }
            else if($scope.model.activeView == 'gallery'){
                $scope.model.createGalleryAction = false;
                $scope.model.selectedGallery = media;
            }
            $scope.showMediaForm();
        }

        /* get the Media Data Collection */
        $scope.getMediaData = function(view)
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            searchParametersCollection.mediaType = $scope.model.activeView;
            if(view != undefined){
                searchParametersCollection.mediaType = view;
            }
            mediaFact.getMediaData($scope,searchParametersCollection);
        }

        /*hide modal of Gallery childrens selection*/
        $scope.hideGalleryChildrensSelectionModal = function(){
            if($scope.model.selectedGalleryType.tree_slug == 'gallery-type-image' && $scope.model.imagesCollection.length > 0){
                for(var i=0; i<$scope.model.imagesCollection.length; i++){
                    $scope.model.imagesCollection[i].selected = false;
                }
            }
            else if($scope.model.selectedGalleryType.tree_slug == 'gallery-type-video' && $scope.model.videosCollection.length > 0){
                for(var i=0; i<$scope.model.videosCollection.length; i++){
                    $scope.model.videosCollection[i].selected = false;
                }
            }
            $('#images-videos-gallery-selection-modal').modal('hide');
        }

        /* hide Media form */
        $scope.hideMediaForm = function(){
            $scope.model.processingData = false;
            if($scope.model.activeView == 'image'){
                $scope.model.createImageAction = null;
            }
            else if($scope.model.activeView == 'video'){
                $scope.model.createImageVideo = null;
            }
            $('#images-gallery-crud-modal').modal('hide');
            $scope.getMediaData();
        }

        /*hide video play modal*/
        $scope.hideVideoPlayModal = function(){
            $scope.model.selectedVideo = null;
            $('#video-play-modal').modal('hide');
        }

        /**/
        $scope.loadMediaSectionData = function(media_section){
            var proceed = false;
            switch (media_section){
                case 'image':
                    $scope.model.sectionTitle = 'Media Imágenes';
                    $scope.model.sectionSubTitle = 'Biblioteca de imágenes...';
                    $scope.model.activeView = 'image';
                    if(!$scope.model.mediaImagesLoaded){
                        proceed = true;
                        $scope.model.mediaImagesLoaded = true;
                    }
                    break;
                case 'video':
                    $scope.model.sectionTitle = 'Media Videos';
                    $scope.model.sectionSubTitle = 'Biblioteca de referencias a videos de Youtube...';
                    $scope.model.activeView = 'video';
                    if(!$scope.model.mediaVideosLoaded){
                        proceed = true;
                        $scope.model.mediaVideosLoaded = true;
                    }
                    break;
                case 'gallery':
                    $scope.model.sectionTitle = 'Media Galerías';
                    $scope.model.sectionSubTitle = 'Biblioteca de galerías de imágenes y videos..';
                    $scope.model.activeView = 'gallery';
                    if(!$scope.model.mediaGalleriesLoaded){
                        proceed = true;
                        $scope.model.mediaGalleriesLoaded = true;
                    }
                    break;
            }
            if(proceed){
                $scope.getMediaData();
            }
        }

        /*update selected images */
        $scope.updateSelectedImages = function(selectedImages){
            $scope.toggleDataLoader();
            if(selectedImages != null){
                $scope.model.selectedImages = selectedImages;
                if($scope.model.selectedImages.length > 0){
                    for(var i=0; i<$scope.model.selectedImages.length; i++){
                        var selectedImage = $scope.model.selectedImages[i];
                        /*var sizeKb = Math.ceil(selectedImage.size / 1000);
                        $scope.model.selectedImages[i]['sizeKb'] = sizeKb;
                        $scope.model.selectedImages[i]['sizeMb'] = Math.ceil(Math.round((sizeKb / 1024)));*/
                        $scope.model.selectedImages[i]['dimensions'] = selectedImage['$ngfWidth']+'x'+selectedImage['$ngfHeight'];
                        $scope.model.selectedImages[i]['extension'] = selectedImage['type'].split('/')[1];
                    }
                }
            }
            $scope.toggleDataLoader();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function(element)
        {
            if(element == 'image'){
                $scope.model.mediaImageCurrentPage = 1;
                $scope.model.mediaImagePagesCollection = [];
                $scope.model.mediaImagePagesCollection.push(1);
                $scope.model.mediaImageCurrentResultStart = 0;
                $scope.model.mediaImageResultLimit = 0;
            }
            if(element == 'video'){
                $scope.model.mediaVideoCurrentPage = 1;
                $scope.model.mediaVideoPagesCollection = [];
                $scope.model.mediaVideoPagesCollection.push(1);
                $scope.model.mediaVideoCurrentResultStart = 0;
                $scope.model.mediaVideoResultLimit = 0;
            }
            if(element == 'gallery'){
                $scope.model.mediaGalleryCurrentPage = 1;
                $scope.model.mediaGalleryPagesCollection = [];
                $scope.model.mediaGalleryPagesCollection.push(1);
                $scope.model.mediaGalleryCurrentResultStart = 0;
                $scope.model.mediaGalleryResultLimit = 0;
            }

            $scope.updatePaginationValues(element);
        }

        /*save media data*/
        $scope.saveMediaData = function(option){
            if(!$scope.model.processingData){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsMediaForm();
                var mediaData = {};
                if($scope.model.activeView == 'image'){
                    if($scope.model.selectedImage.name_es == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedImage.name_es) ||
                    $scope.model.selectedImage.alternative_text_es == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedImage.alternative_text_es)){
                        canProceed = false;

                        if($scope.model.selectedImage.name_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedImage.name_es)){
                            $scope.model.imageNameHasError = true;
                        }

                        if($scope.model.selectedImage.alternative_text_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedImage.alternative_text_es)){
                            $scope.model.imageAlternativeTextHasError = true;
                        }
                    }
                    else{
                        var mediaImageData = {
                            mediaData: $scope.model.selectedImage
                        };
                        mediaFact.saveMediaImageData($scope, null, null, mediaImageData, 'edit');
                    }
                }
                else if($scope.model.activeView == 'video'){
                    if($scope.model.selectedVideo.name_es == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedVideo.name_es) ||
                    $scope.model.selectedVideo.url == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedVideo.url)){
                        canProceed = false;

                        if($scope.model.selectedVideo.name_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedVideo.name_es)){
                            $scope.model.videoNameHasError = true;
                        }

                        if($scope.model.selectedVideo.url == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedVideo.url)){
                            $scope.model.videoUrlHasError = true;
                        }
                    }
                    else{
                        var mediaVideoData = {
                            mediaData: $scope.model.selectedVideo
                        };
                        var action = $scope.model.createVideoAction == true ? 'create': 'edit';
                        mediaFact.saveMediaVideoData($scope, mediaVideoData, option, action);
                    }
                }
                else if($scope.model.activeView == 'gallery'){
                    if($scope.model.selectedGallery.name_es == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedGallery.name_es) ||
                    $scope.model.selectedGalleryType == null){
                        canProceed = false;

                        if($scope.model.selectedGallery.name_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedGallery.name_es)){
                            $scope.model.galleryNameHasError = true;
                        }

                        if($scope.model.selectedGalleryType == null){
                            $scope.model.galleryTypeHasError = true;
                        }
                    }
                    else{
                        $scope.model.selectedGallery.gallery_type_id = $scope.model.selectedGalleryType.id;
                        var mediaGalleryData = {
                            mediaData: $scope.model.selectedGallery
                        };
                        var action = $scope.model.createGalleryAction == true ? 'create': 'edit';
                        mediaFact.saveMediaGalleryData($scope, mediaGalleryData, option, action);
                    }
                }

                if(canProceed){

                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    var message = 'El formulario tiene valores incorrectos o en blanco.'
                    toastr.error(message,"¡Error!");
                }
            }
        }

        /*save media image data*/
        $scope.saveMediaImageData = function(){
            if(!$scope.model.processingData){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                var selectedImagesInfoAssociated = [];
                if($scope.model.createImageAction){
                    if($scope.model.selectedImages == null || $scope.model.selectedImages.length < 1){
                        canProceed = false;
                    }
                    else if($scope.model.selectedImages.length > 0){
                        for(var i=0; i<$scope.model.selectedImages.length; i++){
                            var infoAssociated = {
                                dimensions: $scope.model.selectedImages[i]['dimensions']
                            };
                            selectedImagesInfoAssociated.push(infoAssociated);
                        }
                    }
                }
                else{

                }

                if(canProceed){
                    var action = $scope.model.createImageAction ? 'create' : 'edit';
                    mediaFact.saveMediaImageData($scope, Upload, $scope.model.selectedImages, selectedImagesInfoAssociated, action);
                }
                else{
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    var message = 'Debe de seleccionar al menos una imagen.'
                    if(!$scope.model.createImageAction){
                        message = 'El formulario tiene valores incorrectos o en blanco.'
                    }
                    toastr.error(message,"¡Error!");
                }
            }
        }

        /*save selected Gallery elements */
        $scope.saveSelectedGalleryChildrens = function(){
            var proceed = false;

            if($scope.model.selectedGalleryType.tree_slug == 'gallery-type-image' && $scope.model.imagesCollection.length > 0){
                for(var i=0; i<$scope.model.imagesCollection.length; i++){
                    if($scope.model.imagesCollection[i].selected == true){
                        proceed = true;
                       $scope.model.selectedGallery.childrens.push($scope.model.imagesCollection[i]);
                    }
                }
            }
            else if($scope.model.selectedGalleryType.tree_slug == 'gallery-type-video' && $scope.model.videosCollection.length > 0){
                for(var i=0; i<$scope.model.videosCollection.length; i++){
                    if($scope.model.videosCollection[i].selected == true){
                        proceed = true;
                        $scope.model.selectedGallery.childrens.push($scope.model.videosCollection[i]);
                    }
                }
            }

            if(proceed){
                $scope.hideGalleryChildrensSelectionModal();
            }
            else{
                toastr.options.timeOut = 3000;
                var message = 'Debe de seleccionar al menos un elemento.'
                toastr.error(message,"¡Error!");
            }
        }

        /* search Media data through Search Input Field */
        $scope.searchMediaData = function($event, view)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue))
                || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getMediaData(view);
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getMediaData(view);
            }
        }

        /* see details of Media image */
        $scope.seeMediaDetails = function(media){
            if($scope.model.activeView == 'image'){
                $scope.model.createImageAction = null;
                $scope.model.selectedImage = media;
            }
            else if($scope.model.activeView == 'gallery'){
                $scope.model.createGalleryAction = null;
            }
            $scope.showMediaForm();
        }

        /*select media image*/
        $scope.selectMedia = function(event,element){
            if(element.selected != undefined){
                element.selected = !element.selected;
            }
            else{
                element.selected = true;
            }
        }

        /* show the form to Create/Edit Media elements */
        $scope.showMediaForm = function()
        {
            $scope.clearErrorsMediaForm();
            if($scope.model.activeView == 'image'){
                $scope.model.mediaFormTitle = ($scope.model.createImageAction == true ? 'Agregar imágenes a la biblioteca' : 'Datos de la imágen');
                if($scope.model.createImageAction == true){
                    $scope.model.selectedImages = [];
                }
            }
            else if($scope.model.activeView == 'video'){
                $scope.model.mediaFormTitle = ($scope.model.createVideoAction == true ? 'Agregar video' : 'Datos del video');
                if($scope.model.createVideoAction){
                    $scope.clearMediaForm();
                    $scope.model.selectedVideo.origin = 'youtube';
                    $scope.model.selectedVideo.http_protocol = 'http';
                }
                else{

                }
            }
            else if($scope.model.activeView == 'gallery'){
                $scope.model.mediaFormTitle = ($scope.model.createGalleryAction == true ? 'Crear nueva galería' : 'Datos de la galería');
                if($scope.model.createGalleryAction){

                }
                else{
                    if($scope.model.galleryTypesCollection.length > 0){
                        for(var i=0; i<$scope.model.galleryTypesCollection.length; i++){
                            if($scope.model.galleryTypesCollection[i].id == $scope.model.selectedGallery.gallery_type_id){
                                $scope.model.selectedGalleryType = $scope.model.galleryTypesCollection[i];
                            }
                        }
                    }
                }
            }
            $scope.model.showMediaForm = true;
            $('#images-gallery-crud-modal').modal('show');
        }

        /*show modal of Gallery childrens selection*/
        $scope.showGalleryChildrensSelectionModal = function(){
            if($scope.model.selectedGalleryType.tree_slug == 'gallery-type-image' && $scope.model.imagesCollection.length > 0){
                for(var i=0; i<$scope.model.imagesCollection.length; i++){
                    $scope.model.imagesCollection[i].selected = false;
                }
            }
            else if($scope.model.selectedGalleryType.tree_slug == 'gallery-type-video'){
                if($scope.model.videosCollection.length > 0){

                }
                else{
                    $scope.toggleDataLoader();
                    var searchParametersCollection = {};
                    searchParametersCollection.mediaType = 'video';
                    mediaFact.getMediaData($scope,searchParametersCollection);
                }
            }

            $('#images-videos-gallery-selection-modal').modal('show');
        }

        /*show video play modal*/
        $scope.showVideoPlayModal = function(video){

            if(video != undefined){
                $scope.model.selectedVideo = video;

                $timeout(function(){
                    $('.media-video-play').each(function(){
                        var iframeParent = $(this).parent();
                        var iframeVideoId = iframeParent.attr("data-video-id");
                        if(iframeVideoId == $scope.model.selectedVideo.id){
                            $(this).attr('id',$scope.model.selectedVideo.id);
                            $(this).attr('src',$scope.model.selectedVideo.youtube_url);
                            $(this).attr('title',$scope.model.selectedVideo.name_es);
                        }
                    });
                }, 1000);

                $('#video-play-modal').modal('show');
            }
        }

        /* toggle data-loading message */
        $scope.toggleDataLoader = function()
        {
            $scope.model.loadingData = !$scope.model.loadingData;
        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(element){

            if(element == 'image'){
                $scope.model.mediaImageCurrentResultStart = 0;
                $scope.model.mediaImageCurrentResultLimit = 0;
                $scope.model.mediaImageCurrentPage = ($scope.model.mediaImageCurrentPage*1);
                $scope.model.mediaImageCurrentPageSize = ($scope.model.mediaImageCurrentPageSize*1);

                if($scope.model.imagesCollection.length > 0){
                    $scope.model.mediaImageCurrentResultStart = ($scope.model.mediaImageCurrentPage - 1) * $scope.model.mediaImageCurrentPageSize + 1;
                    $scope.model.mediaImageCurrentResultLimit = ($scope.model.mediaImageCurrentPageSize * $scope.model.mediaImageCurrentPage);
                    if($scope.model.imagesCollection.length < ($scope.model.mediaImageCurrentPageSize * $scope.model.mediaImageCurrentPage)){

                        $scope.model.mediaImageCurrentResultLimit = $scope.model.imagesCollection.length;
                    }
                    var totalPages = Math.ceil($scope.model.imagesCollection.length / $scope.model.mediaImageCurrentPageSize);
                    $scope.model.mediaImagePagesCollection = [];
                    if(totalPages > 0){
                        for(var i=1; i<=totalPages; i++){
                            $scope.model.mediaImagePagesCollection.push(i);
                        }
                    }
                    else{
                        $scope.model.mediaImagePagesCollection.push(1);
                    }
                }
            }
            if(element == 'video'){
                $scope.model.mediaVideoCurrentResultStart = 0;
                $scope.model.mediaVideoCurrentResultLimit = 0;
                $scope.model.mediaVideoCurrentPage = ($scope.model.mediaVideoCurrentPage*1);
                $scope.model.mediaVideoCurrentPageSize = ($scope.model.mediaVideoCurrentPageSize*1);

                if($scope.model.videosCollection.length > 0){
                    $scope.model.mediaVideoCurrentResultStart = ($scope.model.mediaVideoCurrentPage - 1) * $scope.model.mediaVideoCurrentPageSize + 1;
                    $scope.model.mediaVideoCurrentResultLimit = ($scope.model.mediaVideoCurrentPageSize * $scope.model.mediaVideoCurrentPage);
                    if($scope.model.videosCollection.length < ($scope.model.mediaVideoCurrentPageSize * $scope.model.mediaVideoCurrentPage)){

                        $scope.model.mediaVideoCurrentResultLimit = $scope.model.videosCollection.length;
                    }
                    var totalPages = Math.ceil($scope.model.videosCollection.length / $scope.model.mediaVideoCurrentPageSize);
                    $scope.model.mediaVideoPagesCollection = [];
                    if(totalPages > 0){
                        for(var i=1; i<=totalPages; i++){
                            $scope.model.mediaVideoPagesCollection.push(i);
                        }
                    }
                    else{
                        $scope.model.mediaVideoPagesCollection.push(1);
                    }
                }
            }
            if(element == 'gallery'){
                $scope.model.mediaGalleryCurrentResultStart = 0;
                $scope.model.mediaGalleryCurrentResultLimit = 0;
                $scope.model.mediaGalleryCurrentPage = ($scope.model.mediaGalleryCurrentPage*1);
                $scope.model.mediaGalleryCurrentPageSize = ($scope.model.mediaGalleryCurrentPageSize*1);

                if($scope.model.galleriesCollection.length > 0){
                    $scope.model.mediaGalleryCurrentResultStart = ($scope.model.mediaGalleryCurrentPage - 1) * $scope.model.mediaGalleryCurrentPageSize + 1;
                    $scope.model.mediaGalleryCurrentResultLimit = ($scope.model.mediaGalleryCurrentPageSize * $scope.model.mediaGalleryCurrentPage);
                    if($scope.model.galleriesCollection.length < ($scope.model.mediaGalleryCurrentPageSize * $scope.model.mediaGalleryCurrentPage)){

                        $scope.model.mediaGalleryCurrentResultLimit = $scope.model.galleriesCollection.length;
                    }
                    var totalPages = Math.ceil($scope.model.galleriesCollection.length / $scope.model.mediaGalleryCurrentPageSize);
                    $scope.model.mediaGalleryPagesCollection = [];
                    if(totalPages > 0){
                        for(var i=1; i<=totalPages; i++){
                            $scope.model.mediaGalleryPagesCollection.push(i);
                        }
                    }
                    else{
                        $scope.model.mediaGalleryPagesCollection.push(1);
                    }
                }
            }
        }

        /*update slected Gallery Type*/
        $scope.updateSelectedGalleryType = function(galleryType){
            if($scope.model.selectedGalleryType != null ){
                $scope.model.selectedGallery.childrens = [];
            }
            $scope.model.selectedGalleryType = galleryType;
            $scope.showGalleryChildrensSelectionModal();
        }

        


        /*
        * Initialization Functions
        * 
        * */
        $scope.initVisualization = function (){
            /*list view variables*/
            $scope.model.createImageAction = null;
            $scope.model.createVideoAction = null;
            $scope.model.createGalleryAction = null;
            $scope.model.loadingData = false;
            $scope.model.mediaImagesLoaded = true;
            $scope.model.mediaVideosLoaded = false;
            $scope.model.mediaGalleriesLoaded = false;
            $scope.model.sectionTitle = 'Media Imágenes';
            $scope.model.sectionSubTitle = 'Biblioteca de imágenes...';
            $scope.model.activeView = 'image';
            /*form view variables*/
            $scope.model.showMediaForm = false;
            $scope.model.processingData = false;
            $scope.updatePaginationValues('image');
            $scope.clearErrorsMediaForm();
        }
        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.model.imagesCollection = [];
            $scope.model.mediaUploadRestrictionsCollection = [];
            $scope.model.selectedImages = [];
            $scope.model.selectedImage = null;
            $scope.model.videosCollection = [];
            $scope.model.selectedVideo = null;
            $scope.model.galleriesCollection = [];
            $scope.model.selectedGallery = null;
            $scope.model.galleryTypesCollection = [];
            $scope.model.selectedGalleryType = null;
            /*images pagination*/
            $scope.model.mediaImageEntriesSizesCollection = [];
            $scope.model.mediaImageEntriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.mediaImageCurrentPageSize = 20;
            $scope.model.mediaImageCurrentPage = 1;
            $scope.model.mediaImagePagesCollection = [];
            $scope.model.mediaImagePagesCollection.push(1);
            $scope.model.mediaImageCurrentResultStart = 0;
            $scope.model.mediaImageCurrentResultLimit = 0;
            /*videos pagination*/
            $scope.model.mediaVideoEntriesSizesCollection = [];
            $scope.model.mediaVideoEntriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.mediaVideoCurrentPageSize = 20;
            $scope.model.mediaVideoCurrentPage = 1;
            $scope.model.mediaVideoPagesCollection = [];
            $scope.model.mediaVideoPagesCollection.push(1);
            $scope.model.mediaVideoCurrentResultStart = 0;
            $scope.model.mediaVideoCurrentResultLimit = 0;
            /*galleries pagination*/
            $scope.model.mediaGalleryEntriesSizesCollection = [];
            $scope.model.mediaGalleryEntriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.mediaGalleryCurrentPageSize = 20;
            $scope.model.mediaGalleryCurrentPage = 1;
            $scope.model.mediaGalleryPagesCollection = [];
            $scope.model.mediaGalleryPagesCollection.push(1);
            $scope.model.mediaGalleryCurrentResultStart = 0;
            $scope.model.mediaGalleryCurrentResultLimit = 0;

            mediaFact.loadInitialsData($scope);
        }
        init();
    }


    /* Declaring controllers functions for this module */
    angular.module('BncBackend.mediaController').controller('mediaCtrller',mediaCtrller);
})();
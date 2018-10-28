/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.mediaSelectorDirective', ['BncBackend.mediaFactory']);

    /* Declaring directive functions for this module */
    angular.module('BncBackend.mediaSelectorDirective').directive('mediaSelector', [function()
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            scope : {
                selectedimage : "="
            },
            controller: function($scope, $element, mediaFact) {

                /*
                 * Global variables
                 *
                 * */
                var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");


                /*
                 * Operations Functions
                 *
                 * */
                /* get the Media Data Collection */
                $scope.getMediaData = function()
                {
                    $scope.toggleDataLoader();
                    var searchParametersCollection = {};
                    if($scope.model.generalSearchValue != null){
                        if(alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                            searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                        }
                    }
                    searchParametersCollection.mediaType = 'image';
                    mediaFact.getMediaData($scope,searchParametersCollection);
                }

                /*hide media selector*/
                $scope.hideMediaSelectorModal = function(){
                    if($scope.model.imagesCollection.length > 0){
                        for(var i=0; i<$scope.model.imagesCollection.length; i++){
                            $scope.model.imagesCollection[i].selected = false;
                        }
                    }

                    $('#media-images-selector-modal-'+$scope.model.randomNumb).modal('hide');
                }

                /* reset the page size to default value 1 */
                $scope.resetPaginationPages = function(element)
                {
                    $scope.model.mediaImageCurrentPage = 1;
                    $scope.model.mediaImagePagesCollection = [];
                    $scope.model.mediaImagePagesCollection.push(1);
                    $scope.model.mediaImageCurrentResultStart = 0;
                    $scope.model.mediaImageResultLimit = 0;
                    $scope.updatePaginationValues();
                }

                /*save the selected image*/
                $scope.saveSelectedImage = function(){
                    var proceed = false;
                    if($scope.model.imagesCollection.length > 0){
                        for(var i=0; i<$scope.model.imagesCollection.length; i++){
                            if($scope.model.imagesCollection[i].selected == true){
                                $scope.selectedimage = $scope.model.imagesCollection[i];
                                proceed = true;
                                break;
                            }
                        }
                    }
                    if(proceed){
                        $('#media-images-selector-modal-'+$scope.model.randomNumb).modal('hide');
                    }
                    else{
                        /*toastr.options.timeOut = 3000;
                        toastr.error("Debe de seleccionar una imagen.","¡Error!");*/
                        $scope.selectedimage = {};
                        $('#media-images-selector-modal-'+$scope.model.randomNumb).modal('hide');
                    }

                }

                /* search Media data through Search Input Field */
                $scope.searchMediaData = function($event)
                {
                    /*when ENTER key are press OR input value are empty */
                    if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue))
                        || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                        $scope.getMediaData();
                    }/*when ESCAPE key are press*/
                    else if($event.keyCode == 27){
                        $scope.model.generalSearchValue = null;
                        $scope.getMediaData();
                    }
                }

                /*select media image*/
                $scope.selectMediaImage = function(event,image){
                    if(image.selected != undefined){
                        image.selected = !image.selected;
                    }
                    else{
                        image.selected = true;
                    }
                }

                /*show modal with media images collection*/
                $scope.showMediaImageSelector = function(){
                    if($scope.model.imagesCollection.length > 0){
                        for(var i=0; i<$scope.model.imagesCollection.length; i++){
                            if($scope.selectedimage.url != undefined &&
                            $scope.selectedimage.url == $scope.model.imagesCollection[i].url){
                                $scope.model.imagesCollection[i].selected = true;
                            }
                            else{
                                $scope.model.imagesCollection[i].selected = false;
                            }
                        }
                    }
                    $('#media-images-selector-modal-'+$scope.model.randomNumb).modal('show');
                }

                /* update values of the pagination options */
                $scope.updatePaginationValues = function(element){

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
                    $scope.updatePaginationValues();
                }
                function init(){
                    /*generals variables*/
                    $scope.model = {};
                    $scope.success = false;
                    $scope.error = false;
                    /*list view variables*/
                    $scope.model.generalSearchValue = null;
                    $scope.model.imagesCollection = [];
                    /*images pagination*/
                    $scope.model.mediaImageEntriesSizesCollection = [];
                    $scope.model.mediaImageEntriesSizesCollection = [5,10,20,50,100,150,200];
                    $scope.model.mediaImageCurrentPageSize = 20;
                    $scope.model.mediaImageCurrentPage = 1;
                    $scope.model.mediaImagePagesCollection = [];
                    $scope.model.mediaImagePagesCollection.push(1);
                    $scope.model.mediaImageCurrentResultStart = 0;
                    $scope.model.mediaImageCurrentResultLimit = 0;
                    $scope.model.randomNumb = Math.floor(Math.random() * 1000000000);

                    $scope.getMediaData();
                }
                init();
            },
            template:
                '<div class="button-container">' +
                    '<button class="btn btn-circle blue btn-icon-only tooltips" ' +
                    'type="button" data-container="body" data-placement="top" ' +
                    'data-original-title="Seleccionar imagen" ' +
                    'data-ng-click="showMediaImageSelector()">' +
                        '<i class="icon-picture"></i>' +
                    '</button>' +

                        '<div id="media-images-selector-modal-[[model.randomNumb]]" class="modal fade" tabindex="-1" data-width="1200" data-backdrop="static" data-keyboard="false">'+
                            '<div class="modal-header">'+
                                '<button type="button" class="close" data-ng-click="hideMediaSelectorModal()"></button>'+
                                '<h4 class="modal-title">Seleccione la imagen deseada</h4>'+
                            '</div>'+
                            '<div class="modal-body min-height-400">'+
                                '<form class="form horizontal-form" style="position: relative;min-height: 150px;">'+
                                    '<div class="form-body">'+
                                        '<!-- Search bar -->'+
                                        '<div class="inputs">'+
                                            '<div class="portlet-input input-small input-inline" style="width: 100% !important;border-bottom: 1px solid #eee;margin-bottom: 20px;padding-bottom: 20px;">'+
                                                '<div class="input-icon right">'+
                                                    '<i class="icon-magnifier"></i>'+
                                                    '<input type="text" class="form-control form-control-solid" placeholder="Buscar..." data-ng-model="model.generalSearchValue" data-ng-keyup="searchMediaData($event)" style="color:#2a3239;">'+
                                                '</div>' +
                                            '</div>'+
                                        '</div>'+
                                        '<!-- Pagination-->'+
                                        '<div class="row">'+
                                            '<div class="col-xs-12 col-md-6 paginator">'+
                                                '<span> Mostrar </span>'+
                                                '<div class="form-group inline m-b-xs" style="float:left;">'+
                                                    '<select class="form-control input-xsmall" ' +
                                                    'data-ng-model="model.mediaImageCurrentPageSize" ' +
                                                    'data-ng-options="mediaImageEntrySize for mediaImageEntrySize in model.mediaImageEntriesSizesCollection" ' +
                                                    'data-ng-change="resetPaginationPages()">'+
                                                    '</select>'+
                                                '</div>'+
                                                '<span> entradas </span>'+
                                                '<span style="border-left: 1px solid #eee;padding-left: 10px;">' +
                                                    '<strong>[[model.mediaImageCurrentResultStart]]</strong> ' +
                                                '</span>'+
                                                '<span> - </span>'+
                                                '<span><strong>[[model.mediaImageCurrentResultLimit]]</strong></span>'+
                                                '<span> de <strong>[[model.imagesCollection.length]]</strong> entradas </span>'+
                                                '<span style="border-left: 1px solid #eee;padding-left: 10px;"> Página </span>'+
                                                '<div class="form-group inline m-b-xs" style="float:left;">' +
                                                    '<select class="form-control input-s-xs" ' +
                                                    'data-ng-model="model.mediaImageCurrentPage" ' +
                                                    'data-ng-options="page for page in model.mediaImagePagesCollection" ' +
                                                    'data-ng-change="updatePaginationValues()">' +
                                                    '</select>' +
                                                '</div>'+
                                                '<span> de <strong>[[model.mediaImagePagesCollection.length]]</strong></span>'+
                                            '</div>'+
                                            '<div class="col-xs-12 col-md-6">'+
                                                '<a class="btn btn-circle btn-icon-only btn-default pull-right reload" style="margin-left:10px;" ' +
                                                'data-ng-click="getMediaData()">' +
                                                    '<i class="icon-refresh"></i>' +
                                                '</a>'+
                                            '</div>'+
                                        '</div>'+
                                        '<!-- Media Images List-->'+
                                        '<div class="col-xs-12" style="margin-top: 20px;">'+
                                            '<div class="alert alert-warning alert-dismissable" data-auto-close="2" >'+
                                                '<span style="font-size: 12px;color:#777;">'+
                                                    'Solo debe de marcar una sola imagen. Si selecciona más de una, se tomará la primera seleccionada. <br>' +
                                                    'Si lo que desea es retirar la imagen seleccionada, solo desmarque la seleccionada.'+
                                                '</span>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div style="position: relative;">'+
                                            '<div data-ng-show="model.imagesCollection.length > 0" class="row" style="margin-top:30px;">'+
                                                '<div data-ng-repeat="image in model.imagesCollection | limitTo : (model.mediaImageCurrentResultLimit - (model.mediaImageCurrentResultStart - 1)) : (model.mediaImageCurrentResultStart - 1)" ' +
                                                'class="col-xs-6 col-md-2">'+
                                                    '<div class="media-image-standard-thumbnail-container cursor-pointer" ' +
                                                    'data-ng-click="selectMediaImage($event,image)">'+
                                                        '<img data-ng-src="[[image.web_filtered_standard_thumbnail_url]]">'+
                                                        '<div style="background-color: white; position: absolute;top: 0 !important;margin-top: 5px;margin-left: 5px;width: 22px; height: 22px">' +
                                                            '<div class="icheckbox_square-blue hover [[image.selected ? \'checked\' : \'\']]"data-ng-click="selectMediaImage($event,image)"style="">' +
                                                            '</div>' +
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '<div data-ng-show="model.imagesCollection.length == 0">'+
                                            '<!-- No data to show -->' +
                                            '<div style="display:block;text-align: center; color:#93a2a9; padding: 20px;">' +
                                                'Sin datos para mostrar...' +
                                            '</div>'+
                                        '</div>'+
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
                                '</div>'+
                                '<div class="form-actions" style="background-color:white;">'+
                                    '<div class="row">'+
                                        '<div class="col-xs-12 col-md-offset-4 col-md-8">'+
                                            '<button class="btn default btn-footer" type="button" data-ng-click="hideMediaSelectorModal()">'+
                                            'Cancelar </button>'+
                                            '<button class="btn blue btn-blue btn-footer" type="submit" ' +
                                            'data-ng-click="saveSelectedImage()">'+
                                            'Seleccionar </button>'+
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
/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.blockSelectorDirective', ['BncBackend.blockFactory']);

    /* Declaring directive functions for this module */
    angular.module('BncBackend.blockSelectorDirective').directive('blockSelector', [function()
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            scope : {
                elementid : '=',
                elementtype : '@'
            },
            controller: function($scope, $element, blockFact) {

                /*
                 * Global variables
                 *
                 * */
                var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");
                var first_load = false;

                /*
                 * Operations Functions
                 *
                 * */
                /* clear errors of the form */
                $scope.clearErrorsBlockForm = function(){
                    $scope.model.titleHasError = false;
                    $scope.model.blockElementsHasError = false;
                }

                /* clear form values */
                $scope.clearBlockForm = function(){
                    $scope.model.selectedBlock = {};

                }

                /*create block*/
                $scope.createBlock = function(block_type){
                    $scope.model.createAction = true;
                    $scope.clearBlockForm();
                    $scope.model.selectedBlock.generic_post_id = $scope.elementid;
                    $scope.model.selectedBlock.block_type_id = block_type.id;
                    $scope.model.selectedBlock.block_type_name = block_type.name_es;
                    $scope.model.selectedBlock.block_type_tree_slug = block_type.tree_slug;
                    $scope.showBlockSelectorModal();
                }

                /* delete block*/
                $scope.deleteBlock = function(block_id)
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
                                $scope.model.createAction = false;
                                var blockIdCollection = [];
                                if(typeof block_id == 'string'){
                                    if($scope.model.blocksCollection.length > 0){
                                        for(var i=0; i<$scope.model.blocksCollection.length; i++){
                                            if($scope.model.blocksCollection[i].selected != undefined &&
                                                $scope.model.blocksCollection[i].selected == true)
                                            {
                                                blockIdCollection.push($scope.model.blocksCollection[i].id);
                                            }
                                        }
                                    }
                                }
                                else{
                                    blockIdCollection.push(block_id);
                                }
                                var data = {
                                    blocksId: blockIdCollection
                                };
                                blockFact.deleteBlock($scope, data, function(response){
                                    if(response.data.success == 0){
                                        toastr.options.timeOut = 5000;
                                        toastr.error(response.data.message,"Error");
                                    }
                                    else{
                                        toastr.success(response.data.message,"¡Hecho!");
                                    }

                                    $scope.getBlocksData();
                                },
                                function(response){
                                    toastr.options.timeOut = 5000;
                                    if(response.data && response.data.message){
                                        toastr.error(response.data.message,"¡Error!");
                                    }
                                    else{
                                        toastr.error("Esta operación no ha podido ejecutarse.","¡Error!");
                                    }
                                });
                            }
                        });

                }

                /*edit block*/
                $scope.editBlock = function(block){
                    $scope.model.createAction = false;
                    $scope.clearBlockForm();
                    /*$scope.model.selectedBlock.generic_post_id = $scope.elementid;
                    $scope.model.selectedBlock.block_type_id = block_type.id;
                    $scope.model.selectedBlock.block_type_name = block_type.name_es;
                    $scope.model.selectedBlock.block_type_tree_slug = block_type.tree_slug;*/
                    $scope.model.selectedBlock = block;
                    console.log(block);
                    $scope.showBlockSelectorModal();
                }

                /*change block priority*/
                $scope.changeBlockPriority = function(block, operation){
                    $scope.toggleDataLoader();
                    var data = {
                        currentPriority : block.priority,
                        desiredPriority : (block.priority + operation),
                        blockId : block.id
                    }

                    blockFact.changeBlockPriority($scope, data, function(response){
                        $scope.toggleDataLoader();
                        $scope.getBlocksData();
                    },
                    function(){
                        $scope.toggleDataLoader();
                        $scope.getBlocksData();
                    });
                }

                /* get the BLocks Data Collection */
                $scope.getBlocksData = function()
                {
                    $scope.toggleDataLoader();
                    var searchParametersCollection = {
                        genericPostId: $scope.elementid,
                        firstLoad: first_load
                    };

                    blockFact.getBlocksData($scope, searchParametersCollection,
                        function(response){
                            $scope.model.blocksCollection = response.data.blocksDataCollection;
                            if(first_load == true){
                                $scope.model.blockTypesCollection = response.data.blockTypesDataCollection;
                                first_load = false;
                            }
                            $scope.toggleDataLoader();
                        },
                        function(response){
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

                /*get block elements*/
                $scope.getBlockElements = function(){
                    $scope.toggleDataLoader();
                    var searchParametersCollection = {
                        generalSearchValue: $scope.model.blockElementsGeneralSearchValue,
                        blockElementType: $scope.model.selectedBlock.block_type_tree_slug
                    };

                    if($scope.model.selectedTaxonomiesCollection.length > 0){
                        var taxonomieIdsCollection = [];
                        for(var i=0; i<$scope.model.selectedTaxonomiesCollection.length; i++){
                            taxonomieIdsCollection.push($scope.model.selectedTaxonomiesCollection[i].id);
                        }
                        searchParametersCollection.searchByTaxonomies = true;
                        searchParametersCollection.taxonomieIdsCollection = taxonomieIdsCollection;
                    }

                    blockFact.getBlockElementsData($scope, searchParametersCollection,
                        function(response){
                            $scope.model.blockElementsCollection = response.data.blockElementsDataCollection;
                            if($scope.model.createAction == false &&
                            $scope.model.selectedBlock.elements != undefined &&
                            $scope.model.selectedBlock.elements.length > 0){
                                for(var i=0; i<$scope.model.selectedBlock.elements.length; i++){
                                    for(var j=0; j<$scope.model.blockElementsCollection.length; j++){
                                        if($scope.model.selectedBlock.elements[i].id == $scope.model.blockElementsCollection[j].id){
                                            $scope.model.blockElementsCollection[j].selected = true;
                                            break;
                                        }
                                    }
                                }
                            }
                            $scope.updatePaginationValues('block-element');
                            $scope.toggleDataLoader();
                        },
                        function(response){
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

                /* function on scope for go ahead to top */
                $scope.goToTop = function()
                {
                    var pageHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
                    $('html, body').animate({scrollTop: pageHeading.height()}, 1000);
                }

                /*hide block selector*/
                $scope.hideBlockSelectorModal = function(){
                    $scope.model.createAction = null;
                    $scope.clearErrorsBlockForm();
                    $scope.clearBlockForm();
                    $('#block-selector-modal').modal('hide');
                    $scope.goToTop();
                    $scope.getBlocksData();
                }

                /* reset the page size to default value 1 */
                $scope.resetPaginationPages = function(element)
                {
                   if(element == 'block' || element == 'both'){
                       $scope.model.blockCurrentPage = 1;
                       $scope.model.blockPagesCollection = [];
                       $scope.model.blockPagesCollection.push(1);
                       $scope.model.blockCurrentResultStart = 0;
                       $scope.model.blockResultLimit = 0;

                   }
                    if(element == 'block-element' || element == 'both'){
                        $scope.model.blockElementsCurrentPage = 1;
                        $scope.model.blockElementsPagesCollection = [];
                        $scope.model.blockElementsPagesCollection.push(1);
                        $scope.model.blockElementsCurrentResultStart = 0;
                        $scope.model.blockElementsResultLimit = 0;

                    }
                    $scope.updatePaginationValues(element);

                }

                /*save the selected blocks*/
                $scope.saveBlockData = function(){

                    if($scope.model.processingData == false){
                        $scope.model.processingData = true;
                        $scope.toggleDataLoader();
                        var canProceed = true;
                        $scope.clearErrorsBlockForm();

                        if($scope.model.blockElementsCollection.length > 0){
                            var tempSelectedBlockElements = [];
                            for(var i=0; i<$scope.model.blockElementsCollection.length; i++){
                                if($scope.model.blockElementsCollection[i].selected == true){
                                    tempSelectedBlockElements.push($scope.model.blockElementsCollection[i]);
                                }
                            }
                            $scope.model.selectedBlock.elements = tempSelectedBlockElements;
                        }

                        if($scope.model.selectedBlock.title_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedBlock.title_es) ||
                        $scope.model.selectedBlock.elements == undefined ||
                        ($scope.model.selectedBlock.elements != undefined &&
                        $scope.model.selectedBlock.elements.length == 0)){
                            canProceed = false;

                            if($scope.model.selectedBlock.title_es == null ||
                            !alfaNumericRegExpr.test($scope.model.selectedBlock.title_es)){
                                $scope.model.titleHasError = true;
                            }

                            if($scope.model.selectedBlock.elements == undefined ||
                            ($scope.model.selectedBlock.elements != undefined &&
                            $scope.model.selectedBlock.elements.length == 0)){
                                $scope.model.blockElementsHasError = true
                            }
                        }

                        if(canProceed){
                            var tempElementsCollection = $scope.model.selectedBlock.elements;
                            $scope.model.selectedBlock.elements = [];
                            for(var i=0; i<tempElementsCollection.length; i++){
                                $scope.model.selectedBlock.elements.push(tempElementsCollection[i].id);
                            }

                            var blockData = {blockData: $scope.model.selectedBlock};
                            var action = $scope.model.createAction == true ? 'create' : 'edit';

                            blockFact.saveBlockData($scope, blockData, action,
                            function(response){
                                $scope.model.processingData = false;
                                $scope.toggleDataLoader();
                                if(response.data.success == 0){
                                    toastr.options.timeOut = 5000;
                                    toastr.error(response.data.message,"Error");
                                }
                                else{
                                    $scope.clearErrorsBlockForm();
                                    $scope.clearBlockForm();
                                    $scope.hideBlockSelectorModal();

                                    toastr.success(response.data.message,"¡Hecho!");
                                }
                            },
                            function(response){
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
                        else{
                            $scope.model.processingData = false;
                            $scope.toggleDataLoader();
                            toastr.options.timeOut = 3000;
                            toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                        }
                    }
                }

                /*selecting/deselecting block */
                $scope.selectBlockElement = function(event, blockElement){
                    if(blockElement != undefined && blockElement.selected != undefined){
                        blockElement.selected = !blockElement.selected;
                    }
                    else{
                        blockElement.selected = true;
                    }
                }

                /*selecting/deselecting block */
                $scope.selectTaxonomy= function(event,block){
                    if(block != undefined && block.selected != undefined){
                        block.selected = !block.selected;
                    }
                    else{
                        block.selected = true;
                    }
                }

                /*show modal with block blocks collection*/
                $scope.showBlockSelectorModal = function(){
                    $scope.clearErrorsBlockForm();
                    if($scope.model.createAction == true){

                    }
                    else{

                    }

                    $('#block-selector-modal').modal('show');
                    $scope.getBlockElements();
                }

                /* update values of the pagination options */
                $scope.updatePaginationValues = function(element){

                    if(element != undefined && (element == 'block' || element == 'both')){
                        $scope.model.blockCurrentResultStart = 0;
                        $scope.model.blockCurrentResultLimit = 0;
                        $scope.model.blockCurrentPage = ($scope.model.blockCurrentPage*1);
                        $scope.model.blockCurrentPageSize = ($scope.model.blockCurrentPageSize*1);

                        if($scope.model.blocksCollection.length > 0){
                            $scope.model.blockCurrentResultStart = ($scope.model.blockCurrentPage - 1) * $scope.model.blockCurrentPageSize + 1;
                            $scope.model.blockCurrentResultLimit = ($scope.model.blockCurrentPageSize * $scope.model.blockCurrentPage);
                            if($scope.model.blocksCollection.length < ($scope.model.blockCurrentPageSize * $scope.model.blockCurrentPage)){

                                $scope.model.blockCurrentResultLimit = $scope.model.blocksCollection.length;
                            }
                            var totalPages = Math.ceil($scope.model.blocksCollection.length / $scope.model.blockCurrentPageSize);
                            $scope.model.blockPagesCollection = [];
                            if(totalPages > 0){
                                for(var i=1; i<=totalPages; i++){
                                    $scope.model.blockPagesCollection.push(i);
                                }
                            }
                            else{
                                $scope.model.blockPagesCollection.push(1);
                            }
                        }
                    }

                    if(element != undefined && (element == 'block-element' || element == 'both')){
                        $scope.model.blockElementsCurrentResultStart = 0;
                        $scope.model.blockElementsCurrentResultLimit = 0;
                        $scope.model.blockElementsCurrentPage = ($scope.model.blockElementsCurrentPage*1);
                        $scope.model.blockElementsCurrentPageSize = ($scope.model.blockElementsCurrentPageSize*1);

                        if($scope.model.blockElementsCollection.length > 0){
                            $scope.model.blockElementsCurrentResultStart = ($scope.model.blockElementsCurrentPage - 1) * $scope.model.blockElementsCurrentPageSize + 1;
                            $scope.model.blockElementsCurrentResultLimit = ($scope.model.blockElementsCurrentPageSize * $scope.model.blockElementsCurrentPage);
                            if($scope.model.blockElementsCollection.length < ($scope.model.blockElementsCurrentPageSize * $scope.model.blockElementsCurrentPage)){

                                $scope.model.blockElementsCurrentResultLimit = $scope.model.blockElementsCollection.length;
                            }
                            var totalPages = Math.ceil($scope.model.blockElementsCollection.length / $scope.model.blockElementsCurrentPageSize);
                            $scope.model.blockElementsPagesCollection = [];
                            if(totalPages > 0){
                                for(var i=1; i<=totalPages; i++){
                                    $scope.model.blockElementsPagesCollection.push(i);
                                }
                            }
                            else{
                                $scope.model.blockElementsPagesCollection.push(1);
                            }
                        }
                    }
                }

                /* toggle data-loading message */
                $scope.toggleDataLoader = function()
                {
                    $scope.model.loadingData = !$scope.model.loadingData;
                }

                /* update the styles of the I-checks components(radio or checkbox) */
                $scope.updateICheckStyles = function(event, icheckType, suffix, identifierClass){

                    var eventType = null;
                    /*ensuring the event comes from the view action*/
                    if(typeof event == 'object'){
                        if(event == null){eventType = 'click';}
                        else{eventType = event.type;}
                    }
                    else{eventType = event;}

                    /*if event is 'mouseover'*/
                    if(eventType == 'mouseover'){
                        if(identifierClass != null){
                            $('.'+identifierClass).find('.i'+icheckType+'_square-blue').addClass('hover');
                        }
                        else{
                            $('.'+suffix).addClass('hover');
                        }

                    }
                    else if(eventType == 'mouseleave'){
                        if(identifierClass != null){
                            $('.'+identifierClass).find('.i'+icheckType+'_square-blue').removeClass('hover');
                        }
                        else{
                            $('.'+suffix).removeClass('hover');
                        }
                    }
                    else{/* event is 'click'*/
                        $('.'+identifierClass).find('.i'+icheckType+'_square-blue').addClass('checked');
                        $('.'+suffix+'-icheck').each(function(){
                            if(icheckType == 'radio'){
                                if(!$(this).hasClass(identifierClass) && $(this).find('.i'+icheckType+'_square-blue').hasClass('checked')){
                                    $(this).find('.i'+icheckType+'_square-blue').removeClass('checked');
                                }
                            }
                        });


                        /*if(suffix == 'block-type'){
                            $('#block-types-modal-selector').modal('hide');
                        }*/
                    }

                }

                /*watching changes on Filtered Taxonomies Collection*/
                $scope.$watch('model.selectedTaxonomiesCollection', function(newValue, oldValue) {
                    if(newValue != undefined && newValue != null){
                        $scope.getBlockElements();
                    }
                });




                function init(){
                    /*generals variables*/
                    $scope.model = {};
                    $scope.success = false;
                    $scope.error = false;
                    /*list view variables*/
                    $scope.model.generalSearchValue = null;
                    $scope.model.blocksCollection = [];
                    $scope.model.blockTypesCollection = [];
                    $scope.model.loadingData = false;
                    $scope.model.processingData = false;
                    /*form variables*/
                    $scope.model.selectedBlock = {};
                    $scope.model.createAction = null;
                    $scope.model.blockElementsCollection = [];
                    $scope.model.blockElementsGeneralSearchValue = null;
                    $scope.model.selectedTaxonomiesCollection = [];
                    /*blocks pagination*/
                    $scope.model.blockEntriesSizesCollection = [];
                    $scope.model.blockEntriesSizesCollection = [5,10,20,50,100,150,200];
                    $scope.model.blockCurrentPageSize = 20;
                    $scope.model.blockCurrentPage = 1;
                    $scope.model.blockPagesCollection = [];
                    $scope.model.blockPagesCollection.push(1);
                    $scope.model.blockCurrentResultStart = 0;
                    $scope.model.blockCurrentResultLimit = 0;
                    /*block elements pagination*/
                    $scope.model.blockElementsEntriesSizesCollection = [];
                    $scope.model.blockElementsEntriesSizesCollection = [5,10,20,50,100,150,200];
                    $scope.model.blockElementsCurrentPageSize = 20;
                    $scope.model.blockElementsCurrentPage = 1;
                    $scope.model.blockElementsPagesCollection = [];
                    $scope.model.blockElementsPagesCollection.push(1);
                    $scope.model.blockElementsCurrentResultStart = 0;
                    $scope.model.blockElementsCurrentResultLimit = 0;

                    first_load = true;
                    $scope.getBlocksData();
                }
                init();
            },
            template:
                '<div class="row">' +

                    '<div class="col-xs-12">' +
                        '<div class="form-group margin-top-20">' +
                            '<div class="input-group" style="width:180px;float:left;">' +
                                '<div class="input-group-btn">' +
                                    '<a class="btn toolbar-btn-dropdown-text btn-sm btn-default dropdown-toggle" ' +
                                    'style="text-align: left; font-size: 14px;" ' +
                                    'data-toggle="dropdown" data-hover="dropdown" data-close-others="true" ' +
                                    '>' +
                                        '<i class="fa fa-plus"></i> ' +
                                        ' Agregar bloque' +
                                    '</a>' +
                                    '<div class="dropdown-menu hold-on-click dropdown-checkboxes " ' +
                                    'style="min-width: 275px;top: 25px;margin-left: 0px;"> ' +
                                        '<label data-ng-if="model.blockTypesCollection.length == 0">' +
                                            'Cargando Tipos de Bloques...' +
                                            '<i class="fa fa-spinner fa-spin"></i>' +
                                        '</label>' +
                                        '<label data-ng-if="model.blockTypesCollection.length > 0" ' +
                                        'data-ng-repeat="blockType in model.blockTypesCollection">' +
                                            '<a class="btn" style="width: 100%;text-align: left;" ' +
                                            'data-ng-click="createBlock(blockType)"> ' +
                                                '[[blockType.name_es]]' +
                                            '</a>' +
                                        '</label>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +

                            '<a class="btn btn-circle btn-icon-only btn-default pull-right reload" style="" ' +
                            'data-ng-click="getBlocksData()">' +
                                '<i class="icon-refresh"></i>' +
                            '</a>'+

                        '</div>' +
                    '</div>' +

                    '<div class="col-xs-12" style="position:relative;">' +
                        '<div data-ng-show="model.blocksCollection.length > 0" class="panel-group accordion scrollable" ' +
                        'id="blocks_accordion" style="margin-top:35px;">' +
                            '<div data-ng-repeat="block in model.blocksCollection" class="panel panel-default">' +
                                '<div class="panel-heading">' +
                                    '<h4 class="panel-title">' +
                                        '<button data-ng-if="!$first" class="btn btn-link pull-left" style="padding-top: 15px !important;margin-right:5px;" ' +
                                        'data-ng-click="changeBlockPriority(block, -1)">' +
                                            '<i class="fa fa-sort-up" style="font-size:24px"></i>' +
                                        '</button>'+
                                        '<button data-ng-if="!$last" class="btn btn-link pull-left" style="margin-right:10px;" ' +
                                        'data-ng-click="changeBlockPriority(block, 1)">' +
                                            '<i class="fa fa-sort-down" style="font-size:24px"></i>' +
                                        '</button>'+

                                        '<a class="accordion-toggle" data-toggle="collapse" data-parent="#blocks_accordion" href="#[[$index]]" ' +
                                        'style="padding: 10px 65px !important;">' +
                                            '[[block.title_es]]' +
                                        '</a>' +

                                        '<button data-ng-if="block.canDelete" ' +
                                        'class="btn btn-link pull-right" data-ng-click="deleteBlock(block.id)" ' +
                                        'style="position:relative;top:-30px;right:10px">' +
                                            '<i class="icon-trash btn-red"></i>' +
                                        '</button>'+
                                        '<button data-ng-if="block.canEdit" class="btn btn-link pull-right" ' +
                                        'data-ng-click="editBlock(block)" ' +
                                        'style="position:relative;top:-30px;right:20px">' +
                                            '<i class="icon-pencil btn-blue"></i>' +
                                        '</button>'+
                                    '</h4>' +
                                '</div>' +
                                '<div id="[[$index]]" class="panel-collapse [[$first ? \'in\' : \'collapse\']]">' +
                                    '<div class="panel-body">' +
                                        '<span class="help-block" style="margin-bottom:30px;">' +
                                            'Tipo de Bloque : [[block.block_type_name]]' +
                                        '</span>'+

                                        '<div data-ng-if="block.block_type_tree_slug == \'content-block-type-media-gallery\' && block.elements.length > 0" ' +
                                        'data-ng-repeat="gallery in block.elements" ' +
                                        'class="col-xs-12" style="margin-bottom:30px;">' +
                                            '<div class="media-gallery-standard-container">' +
                                                '<div class="row media-gallery-standard-icon-container">' +
                                                    '<!-- for Images elements -->' +
                                                    '<div data-ng-if="gallery.childrens.length > 0 && gallery.gallery_type_tree_slug == \'gallery-type-image\'" ' +
                                                    'data-ng-repeat="children in gallery.childrens" ' +
                                                    'class="col-xs-3 col-md-1 media-gallery-standard-icon">' +
                                                        '<img data-ng-src="[[children.web_filtered_standard_thumbnail_url]]">' +
                                                    '</div>' +
                                                    '<!-- for Videos elements -->' +
                                                    '<div data-ng-if="gallery.childrens.length > 0 && gallery.gallery_type_tree_slug == \'gallery-type-video\'" ' +
                                                    'data-ng-repeat="children in gallery.childrens" ' +
                                                    'class="col-xs-6 col-md-3 media-gallery-standard-video-icon">' +
                                                        '<img src="images/shared/video-default-standard-thumbnail.jpg">' +
                                                    '</div>' +
                                                    '<!-- No data to show -->' +
                                                    '<div data-ng-if="gallery.childrens.length == 0" style="display:block;text-align: center; color:#93a2a9; padding: 20px;">' +
                                                        'No se han seleccionado elementos para esta galería......' +
                                                    '</div>' +
                                                '</div>' +
                                                '<span style="float:left;width:100%; color:#777;padding:10px;border-top: 1px solid #eee;">' +
                                                    '[[gallery.name_es]]' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div data-ng-if="block.block_type_tree_slug == \'content-block-type-media-image\' && block.elements.length > 0"' +
                                        ' data-ng-repeat="image in block.elements" class="col-xs-6 col-md-2">' +
                                            '<div class="media-image-standard-thumbnail-container cursor-pointer"> ' +
                                                '<img data-ng-src="[[image.web_filtered_standard_thumbnail_url]]">'+
                                            '</div>' +
                                        '</div>'+

                                        '<div data-ng-if="block.block_type_tree_slug == \'content-block-type-media-video\' && block.elements.length > 0"' +
                                        ' data-ng-repeat="video in block.elements" class="col-xs-6 col-md-4">' +
                                            '<div class="media-video-standard-thumbnail-container" style="margin-bottom:20px;">' +
                                                '<div class="media-video-standard-thumbnail" data-video-id="[[video.id]]" style="text-align:center;"> ' +
                                                    '<img src="images/shared/video-default-standard-thumbnail.jpg" style="margin-top: 20%;">'+
                                                '</div>' +
                                                '<span style="float:left;width:100%; color:#777;padding:10px;border-top: 1px solid #eee;" xmlns="http://www.w3.org/1999/html">' +
                                                    '[[video.name_es]]' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>'+


                                        '<div data-ng-if="block.block_type_tree_slug == \'content-block-type-opinion\' && block.elements.length > 0" ' +
                                        'class="col-xs-12" >'+
                                            '<div class="general-item-list">' +
                                                '<div data-ng-repeat="opinion in block.elements" ' +
                                                'class="item" style="padding:20px 0px;">' +
                                                    '<div class="item-head" style="margin-top:10px;">' +
                                                        '<div class="item-details">' +
                                                            '<a class="item-name primary-link" style="cursor:auto;text-decoration: none;">' +
                                                            '[[opinion.title_es]]' +
                                                            '</a>' +
                                                        '</div>' +
                                                    '</div>' +
                                                    '<div class="item-body" style="margin-bottom: 10px;">' +
                                                        '[[opinion.content_es]]' +
                                                    '</div>' +
                                                    '<span class="label label-sm [[opinion.post_status_name ==\'Pendiente\'? \'label-warning\' : \'label-success\']] pull-left" style="margin-right:5px;">' +
                                                        '[[opinion.post_status_name]]' +
                                                    '</span>' +
                                                    '<span data-ng-if="opinion.categoriesCollection.length > 0" ' +
                                                    'data-ng-repeat="category in opinion.categoriesCollection" ' +
                                                    'class="label label-sm label-default pull-right" style="margin-right:5px;">' +
                                                        '[[category.name_es]]' +
                                                    '</span>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>'+

                        '<div data-ng-if="model.blocksCollection.length == 0" style="position: relative;">'+
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


                    '<!-- Modal with blocks -->' +
                    '<div id="block-selector-modal" class="modal fade" tabindex="-1" data-width="1200" data-backdrop="static" data-keyboard="false">' +
                        '<div class="modal-header">'+
                            '<button type="button" class="close" data-ng-click="hideBlockSelectorModal()"></button>'+
                            '<h4 class="modal-title">[[model.createAction == true ? \'Agregar \' : \'Editar \']] bloque de [[model.selectedBlock.block_type_name]]</h4>'+
                        '</div>'+
                        '<div class="modal-body min-height-400">' +
                            '<form class="form horizontal-form" style="min-height: 150px;">' +
                                '<div class="form-body">' +
                                    '<div class="row">' +
                                        '<div class="col-xs-12">' +
                                            '<div class="form-group [[model.titleHasError ? \'has-error\' : \'\']]">' +
                                                '<label class="control-label">' +
                                                    'Nombre' +
                                                '</label> ' +
                                                '<input class="form-control" type="text" placeholder="No más de 100 caracteres." ' +
                                                ' data-ng-model="model.selectedBlock.title_es" ' +
                                                'maxlength="100"> ' +
                                                '<span class="help-block">' +
                                                    '<p data-ng-if="model.titleHasError">Valor incorrecto o en blanco.</p>' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="row">' +
                                        '<div class="col-xs-12">'+
                                            '<!-- Search bar -->'+
                                            '<div class="inputs">'+
                                                '<div class="portlet-input input-small input-inline" style="width: 100% !important;' +
                                                'border-bottom: 1px solid #eee;border-top: 1px solid #eee;margin-bottom: 20px;' +
                                                'padding-bottom: 20px;padding-top: 20px;">'+
                                                    '<div class="input-icon right">'+
                                                        '<i class="icon-magnifier"></i>'+
                                                        '<input type="text" class="form-control form-control-solid" placeholder="Buscar..." data-ng-model="model.blockElementsGeneralSearchValue" data-ng-keyup="searchBlockElements($event)" style="color:#2a3239;">'+
                                                    '</div>' +
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<!-- Pagination-->'+
                                        '<div class="col-xs-12 col-md-6 paginator">'+
                                            '<span> Mostrar </span>'+
                                            '<div class="form-group inline m-b-xs" style="float:left;">'+
                                                '<select class="form-control input-xsmall" ' +
                                                'data-ng-model="model.blockElementsCurrentPageSize" ' +
                                                'data-ng-options="blockElementsEntrySize for blockElementsEntrySize in model.blockElementsEntriesSizesCollection" ' +
                                                'data-ng-change="resetPaginationPages(\'block-element\')">'+
                                                '</select>'+
                                            '</div>'+
                                            '<span> entradas </span>'+
                                            '<span style="border-left: 1px solid #eee;padding-left: 10px;">' +
                                                '<strong>[[model.blockElementsCurrentResultStart]]</strong> ' +
                                            '</span>'+
                                            '<span> - </span>'+
                                            '<span><strong>[[model.blockElementsCurrentResultLimit]]</strong></span>'+
                                            '<span> de <strong>[[model.blockElementsCollection.length]]</strong> entradas </span>'+
                                            '<span style="border-left: 1px solid #eee;padding-left: 10px;"> Página </span>'+
                                            '<div class="form-group inline m-b-xs" style="float:left;">' +
                                                '<select class="form-control input-s-xs" ' +
                                                'data-ng-model="model.blockElementsCurrentPage" ' +
                                                'data-ng-options="page for page in model.blockElementsPagesCollection" ' +
                                                'data-ng-change="updatePaginationValues(\'block-element\')">' +
                                                '</select>' +
                                            '</div>'+
                                            '<span> de <strong>[[model.blockElementsPagesCollection.length]]</strong></span>'+
                                        '</div>'+
                                        '<div class="col-xs-12 col-md-6">'+
                                            '<a class="btn btn-circle btn-icon-only btn-default pull-right reload" style="margin-left:10px;" ' +
                                            'data-ng-click="getBlockElements()">' +
                                                '<i class="icon-refresh"></i>' +
                                            '</a>'+
                                        '</div>'+
                                    '</div>'+
                                    '<!-- Block Elements List-->' +
                                    '<div class="row" style="position:relative; margin-top:20px;">' +
                                        '<div data-ng-if="model.blockElementsHasError" class="col-xs-12 has-error">' +
                                            '<span class="help-block">' +
                                                '<p data-ng-if="model.blockElementsHasError">Debe de seleccionar al menos un elemento.</p>' +
                                            '</span>' +
                                        '</div>'+
                                        '<div data-ng-if="model.selectedBlock.block_type_tree_slug == \'content-block-type-media-gallery\' && model.blockElementsCollection.length > 0" ' +
                                        'data-ng-repeat="gallery in model.blockElementsCollection | limitTo : (model.blockElementsCurrentResultLimit - (model.blockElementsCurrentResultStart - 1)) : (model.blockElementsCurrentResultStart - 1)"  ' +
                                        'class="col-xs-12 col-md-4">' +
                                            '<div class="media-gallery-standard-container">' +
                                                '<div class="row media-gallery-standard-icon-container">' +
                                                    '<!-- for Images elements -->' +
                                                    '<div data-ng-if="gallery.childrens.length > 0 && gallery.gallery_type_tree_slug == \'gallery-type-image\'" ' +
                                                    'data-ng-repeat="children in gallery.childrens | limitTo: 12" ' +
                                                    'class="col-xs-3 col-md-3 media-gallery-standard-icon">' +
                                                        '<img data-ng-src="[[children.web_filtered_standard_thumbnail_url]]">' +
                                                    '</div>' +
                                                    '<!-- for Videos elements -->' +
                                                    '<div data-ng-if="gallery.childrens.length > 0 && gallery.gallery_type_tree_slug == \'gallery-type-video\'" ' +
                                                    'data-ng-repeat="children in gallery.childrens | limitTo: 4" ' +
                                                    'class="col-xs-6 col-md-6 media-gallery-standard-video-icon">' +
                                                        '<img src="images/shared/video-default-standard-thumbnail.jpg">' +
                                                    '</div>' +
                                                    '<!-- No data to show -->' +
                                                    '<div data-ng-if="gallery.childrens.length == 0" style="display:block;text-align: center; color:#93a2a9; padding: 20px;">' +
                                                        'No se han seleccionado elementos para esta galería......' +
                                                    '</div>' +
                                                '</div>' +
                                                '<span style="float:left;width:100%; color:#777;padding:10px;border-top: 1px solid #eee;">' +
                                                    '[[gallery.name_es]]' +
                                                '</span>' +
                                                '<div style="background-color: white; position: absolute;top: 0 !important;margin-top: 5px;' +
                                                'margin-left: 5px;width: 22px; height: 22px">' +
                                                    '<div class="icheckbox_square-blue hover [[gallery.selected ? \'checked\' : \'\']]" ' +
                                                    'data-ng-click="selectBlockElement($event, gallery)">' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div data-ng-if="model.selectedBlock.block_type_tree_slug == \'content-block-type-media-image\' && model.blockElementsCollection.length > 0" ' +
                                        'data-ng-repeat="image in model.blockElementsCollection | limitTo : (model.blockElementsCurrentResultLimit - (model.blockElementsCurrentResultStart - 1)) : (model.blockElementsCurrentResultStart - 1)"  ' +
                                        'class="col-xs-6 col-md-2">' +
                                            '<div class="media-image-standard-thumbnail-container cursor-pointer" data-ng-click="selectBlockElement($event, image)">' +
                                                '<img data-ng-src="[[image.web_filtered_standard_thumbnail_url]]">' +
                                                '<div style="background-color: white; position: absolute;top: 0 !important;margin-top: 5px;' +
                                                'margin-left: 5px;width: 22px; height: 22px">' +
                                                    '<div class="icheckbox_square-blue hover [[image.selected ? \'checked\' : \'\']]" ' +
                                                    'data-ng-click="selectBlockElement($event, image)">' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div data-ng-if="model.selectedBlock.block_type_tree_slug == \'content-block-type-media-video\' && model.blockElementsCollection.length > 0"' +
                                        ' data-ng-repeat="video in model.blockElementsCollection | limitTo : (model.blockElementsCurrentResultLimit - (model.blockElementsCurrentResultStart - 1)) : (model.blockElementsCurrentResultStart - 1)"' +
                                        ' class="col-xs-6 col-md-4">' +
                                            '<div class="media-video-standard-thumbnail-container" style="margin-bottom:20px;">' +
                                                '<div class="media-video-standard-thumbnail" data-video-id="[[video.id]]" style="text-align:center;"> ' +
                                                    '<img src="images/shared/video-default-standard-thumbnail.jpg" style="margin-top: 20%;">'+
                                                '</div>' +
                                                '<span style="float:left;width:100%; color:#777;padding:10px;border-top: 1px solid #eee;" xmlns="http://www.w3.org/1999/html">' +
                                                    '[[video.name_es]]' +
                                                '</span>' +
                                                '<div style="background-color: white; position: absolute;top: 0 !important;margin-top: 5px;' +
                                                'margin-left: 5px;width: 22px; height: 22px">' +
                                                    '<div class="icheckbox_square-blue hover [[video.selected ? \'checked\' : \'\']]" ' +
                                                    'data-ng-click="selectBlockElement($event, video)">' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div class="col-xs-12">' +
                                            '<div data-ng-if="model.selectedBlock.block_type_tree_slug == \'content-block-type-opinion\' && model.blockElementsCollection.length > 0" ' +
                                            'class="general-item-list" style="margin-top:25px;">' +
                    '<taxonomy-filter selectedtaxonomiescollection="model.selectedTaxonomiesCollection"' +
                'taxonomytypetreeslug="opinion-category">' +
                '</taxonomy-filter>'+

                                                '<div data-ng-repeat="opinion in model.blockElementsCollection | limitTo : (model.blockElementsCurrentResultLimit - (model.blockElementsCurrentResultStart - 1)) : (model.blockElementsCurrentResultStart - 1)"" ' +
                                                'class="item" style="padding:20px 0px;">' +
                                                    '<div class="icheckbox_square-blue [[opinion.selected ? \'checked\' : \'\']]" ' +
                                                    'data-ng-click="selectBlockElement($event, opinion)">' +
                                                    '</div>' +
                                                    '<div class="item-head" style="margin-top:10px;">' +
                                                        '<div class="item-details">' +
                                                            '<a class="item-name primary-link" style="cursor:auto;text-decoration: none;">' +
                                                            '[[opinion.title_es]]' +
                                                            '</a>' +
                                                        '</div>' +
                                                    '</div>' +
                                                    '<div class="item-body" style="margin-bottom: 10px;">' +
                                                        '[[opinion.content_es]]' +
                                                    '</div>' +
                                                    '<span class="label label-sm [[opinion.post_status_name ==\'Pendiente\'? \'label-warning\' : \'label-success\']] pull-left" style="margin-right:5px;">' +
                                                        '[[opinion.post_status_name]]' +
                                                    '</span>' +
                                                    '<span data-ng-if="opinion.categoriesCollection.length > 0" ' +
                                                    'data-ng-repeat="category in opinion.categoriesCollection" ' +
                                                    'class="label label-sm label-default pull-right" style="margin-right:5px;">' +
                                                        '[[category.name_es]]' +
                                                    '</span>' +
                                                '</div>' +
                                            '</div>'+
                                        '</div>'+

                                        '<!-- No data to show -->' +
                                        '<div data-ng-show="model.blockElementsCollection.length == 0">'+
                                            '<div style="display:block;text-align: center; color:#93a2a9; padding: 20px;">' +
                                            'No existen [[model.selectedBlock.block_type_name]] para mostrar...' +
                                            '</div>'+
                                        '</div>'+
                                        '<!-- Loader -->' +
                                        '<div data-ng-show="model.loadingData">'+
                                            '<div class="data-loader">' +
                                                '<div class="sk-data-loader-spinner sk-spinner-three-bounce">' +
                                                    '<div class="sk-bounce1"></div>' +
                                                    '<div class="sk-bounce2"></div>' +
                                                    '<div class="sk-bounce3"></div>' +
                                                '</div>' +
                                            '</div>'+
                                        '</div>'+
                                    '</div>' +
                                '</div>' +
                                '<div class="form-actions" style="background-color:white;">' +
                                    '<div class="row">' +
                                        '<div class="col-xs-12 col-md-offset-4 col-md-8">' +
                                            '<button class="btn default btn-footer" type="button" data-ng-click="hideBlockSelectorModal()">'+
                                            'Cancelar </button>'+
                                            '<button class="btn blue btn-blue btn-footer" type="submit" ' +
                                            'data-ng-click="saveBlockData()">'+
                                            'Guardar </button>'+
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
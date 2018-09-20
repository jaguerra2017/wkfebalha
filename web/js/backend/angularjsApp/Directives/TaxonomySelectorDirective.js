/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.taxonomySelectorDirective', ['BncBackend.taxonomyFactory']);

    /* Declaring directive functions for this module */
    angular.module('BncBackend.taxonomySelectorDirective').directive('taxonomySelector', [function()
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            scope : {
                taxonomylabel : '@',
                selectedtaxonomiescollection : "=",
                taxonomytypetreeslug : '@'
            },
            controller: function($scope, $element, taxonomyFact) {

                /*
                 * Global variables
                 *
                 * */
                var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");


                /*
                 * Operations Functions
                 *
                 * */
                $scope.deleteSelectedTaxonomy = function(selectedTaxonomy){
                    if($scope.selectedtaxonomiescollection.length > 0){
                        var tempCollection = [];
                        for(var i=0; i<$scope.selectedtaxonomiescollection.length; i++){
                            if($scope.selectedtaxonomiescollection[i].id != selectedTaxonomy.id){
                                tempCollection.push($scope.selectedtaxonomiescollection[i]);
                            }
                        }

                        $scope.selectedtaxonomiescollection = tempCollection;
                    }
                }

                /* get the Taxonomy Data Collection */
                $scope.getTaxonomiesData = function()
                {
                    $scope.toggleDataLoader();
                    var searchParametersCollection = {
                        taxonomyTypeTreeSlug: $scope.taxonomytypetreeslug,
                        returnDataInTree: true
                    };
                    taxonomyFact.getTaxonomiesData($scope, searchParametersCollection, function(response){
                        $scope.model.taxonomiesCollection = response.data.taxonomiesDataCollection;
                        if($scope.model.taxonomiesCollection.length > 0 &&
                        $scope.selectedtaxonomiescollection.length > 0){

                            for(var i=0; i<$scope.selectedtaxonomiescollection.length; i++){
                                var selectedTaxonomy = $scope.selectedtaxonomiescollection[i];

                                for(var j=0; j<$scope.model.taxonomiesCollection.length; j++){
                                    if(selectedTaxonomy.id != undefined &&
                                        selectedTaxonomy.id == $scope.model.taxonomiesCollection[j].id){
                                        $scope.model.taxonomiesCollection[j].selected = true;
                                    }

                                    if($scope.model.taxonomiesCollection[j].childs != undefined &&
                                        $scope.model.taxonomiesCollection[j].childs.length > 0){
                                        var childsCollection = $scope.model.taxonomiesCollection[j].childs;
                                        for(var k=0; k<childsCollection.length; k++){
                                            if(selectedTaxonomy.id != undefined &&
                                                selectedTaxonomy.id == childsCollection[k].id){
                                                childsCollection[k].selected = true;
                                            }

                                            if(childsCollection[k].childs != undefined &&
                                                childsCollection[k].childs.length > 0){
                                                var grandChildsCollection = childsCollection[k].childs;
                                                for(var m=0; m<grandChildsCollection.length; m++){
                                                    if(selectedTaxonomy.id != undefined &&
                                                        selectedTaxonomy.id == grandChildsCollection[m].id){
                                                        grandChildsCollection[m].selected = true;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                /* function on scope for go ahead to top */
                $scope.goToTop = function()
                {
                    var pageHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
                    $('html, body').animate({scrollTop: pageHeading.height()}, 1000);
                }

                /*hide taxonomy selector*/
                $scope.hideTaxonomySelector = function(){
                    $scope.model.taxonomiesCollection = [];
                    $('#taxonomy-selector-modal').modal('hide');
                    $scope.goToTop();
                }

                /* reset the page size to default value 1 */
                $scope.resetPaginationPages = function(element)
                {
                    /*$scope.model.taxonomyTaxonomyCurrentPage = 1;
                    $scope.model.taxonomyTaxonomyPagesCollection = [];
                    $scope.model.taxonomyTaxonomyPagesCollection.push(1);
                    $scope.model.taxonomyTaxonomyCurrentResultStart = 0;
                    $scope.model.taxonomyTaxonomyResultLimit = 0;
                    $scope.updatePaginationValues();*/
                }

                /*save the selected image*/
                $scope.saveSelectedTaxonomy = function(taxonomiesCollection){
                    var taxonsCollection = taxonomiesCollection
                    if(taxonsCollection == undefined){
                        $scope.selectedtaxonomiescollection = [];
                        taxonsCollection = $scope.model.taxonomiesCollection;
                    }
                    if(taxonsCollection.length > 0){
                        for(var i=0; i<taxonsCollection.length; i++){
                            if(taxonsCollection[i].selected == true){
                                $scope.selectedtaxonomiescollection.push(taxonsCollection[i])
                            }
                            if(taxonsCollection[i].childs != undefined &&
                                taxonsCollection[i].childs.length > 0){
                                $scope.saveSelectedTaxonomy(taxonsCollection[i].childs);
                            }
                        }
                    }

                    if(taxonomiesCollection == undefined /*&& taxonomiesCounter == $scope.model.taxonomiesCollection.length*/){
                        $('#taxonomy-selector-modal').modal('hide');
                    }
                }

                /*selecting/deselecting taxonomy */
                $scope.SelectTaxonomy= function(event,taxonomy){
                    if(taxonomy != undefined && taxonomy.selected != undefined){
                        taxonomy.selected = !taxonomy.selected;
                    }
                    else{
                        taxonomy.selected = true;
                    }
                }

                /*show modal with taxonomy taxonomies collection*/
                $scope.showTaxonomySelector = function(){
                    $('#taxonomy-selector-modal').modal('show');
                    $scope.getTaxonomiesData();

                }

                /* update values of the pagination options */
                $scope.updatePaginationValues = function(element){

                    /*$scope.model.taxonomyTaxonomyCurrentResultStart = 0;
                    $scope.model.taxonomyTaxonomyCurrentResultLimit = 0;
                    $scope.model.taxonomyTaxonomyCurrentPage = ($scope.model.taxonomyTaxonomyCurrentPage*1);
                    $scope.model.taxonomyTaxonomyCurrentPageSize = ($scope.model.taxonomyTaxonomyCurrentPageSize*1);

                    if($scope.model.taxonomiesCollection.length > 0){
                        $scope.model.taxonomyTaxonomyCurrentResultStart = ($scope.model.taxonomyTaxonomyCurrentPage - 1) * $scope.model.taxonomyTaxonomyCurrentPageSize + 1;
                        $scope.model.taxonomyTaxonomyCurrentResultLimit = ($scope.model.taxonomyTaxonomyCurrentPageSize * $scope.model.taxonomyTaxonomyCurrentPage);
                        if($scope.model.taxonomiesCollection.length < ($scope.model.taxonomyTaxonomyCurrentPageSize * $scope.model.taxonomyTaxonomyCurrentPage)){

                            $scope.model.taxonomyTaxonomyCurrentResultLimit = $scope.model.taxonomiesCollection.length;
                        }
                        var totalPages = Math.ceil($scope.model.taxonomiesCollection.length / $scope.model.taxonomyTaxonomyCurrentPageSize);
                        $scope.model.taxonomyTaxonomyPagesCollection = [];
                        if(totalPages > 0){
                            for(var i=1; i<=totalPages; i++){
                                $scope.model.taxonomyTaxonomyPagesCollection.push(i);
                            }
                        }
                        else{
                            $scope.model.taxonomyTaxonomyPagesCollection.push(1);
                        }
                    }*/
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


                        /*if(suffix == 'taxonomy-type'){
                            $('#taxonomy-types-modal-selector').modal('hide');
                        }*/
                    }

                }

                function init(){
                    /*generals variables*/
                    $scope.model = {};
                    $scope.success = false;
                    $scope.error = false;
                    /*list view variables*/
                    $scope.model.generalSearchValue = null;
                    $scope.model.taxonomiesCollection = [];
                    $scope.model.loadingData = false;
                    $scope.model.processingData = false;
                    /*taxonomies pagination*/
                    $scope.model.taxonomyTaxonomyEntriesSizesCollection = [];
                    $scope.model.taxonomyTaxonomyEntriesSizesCollection = [5,10,20,50,100,150,200];
                    $scope.model.taxonomyTaxonomyCurrentPageSize = 20;
                    $scope.model.taxonomyTaxonomyCurrentPage = 1;
                    $scope.model.taxonomyTaxonomyPagesCollection = [];
                    $scope.model.taxonomyTaxonomyPagesCollection.push(1);
                    $scope.model.taxonomyTaxonomyCurrentResultStart = 0;
                    $scope.model.taxonomyTaxonomyCurrentResultLimit = 0;
                }
                init();
            },
            template:
                '<div class="form-group margin-top-20">' +
                    '<label class="control-label">[[taxonomylabel]]</label>' +
                    '<div class="input-group" style="width:100px;">' +
                        '<div class="input-group-btn">' +
                            '<a class="btn toolbar-btn-dropdown-text btn-sm btn-default" ' +
                            'style="text-align: left; font-size: 14px;"' +
                            'data-ng-click="showTaxonomySelector()">' +
                                'Seleccione' +
                            '</a>' +
                        '</div>' +
                    '</div>' +
                    '<div class="selected-taxonomies-container">' +
                        '<div data-ng-if="selectedtaxonomiescollection == undefined || selectedtaxonomiescollection.length == 0" ' +
                        'style="display:block;text-align: center; color:#93a2a9; padding: 20px;">' +
                            'No ha seleccionado ninguna categoría' +
                        '</div>' +
                        '<span  data-ng-if="selectedtaxonomiescollection.length > 0" ' +
                        'data-ng-repeat="selectedTaxonomy in selectedtaxonomiescollection" ' +
                        'class="taxonomy-selected-container">' +
                            '[[selectedTaxonomy.name_es]] ' +
                            '<a class="btn default btn-icon-only btn-circle btn-delete-selected-taxonomy" ' +
                            'data-ng-click="deleteSelectedTaxonomy(selectedTaxonomy)" >' +
                                '<i class="fa fa-close"></i>' +
                            '</a>' +
                        '</span>' +
                    '</div>' +
                    '<!-- Modal with taxonomies -->' +
                    '<div id="taxonomy-selector-modal" class="modal fade" tabindex="-1" data-width="600" data-backdrop="static" data-keyboard="false">'+
                        '<div class="modal-header">'+
                            '<button type="button" class="close" data-ng-click="hideTaxonomySelector()"></button>'+
                            '<h4 class="modal-title">Seleccione la [[taxonomylabel]]</h4>'+
                        '</div>'+
                        '<div class="modal-body min-height-400">'+
                            '<form class="form horizontal-form" style="position: relative;min-height: 150px;">'+
                                '<div class="form-body" style="min-height: 300px;">'+
                                    '<div class="row">' +
                                        '<div class="col-md-12">' +
                                            '<div class="dd">' +
                                                '<ol class="dd-list">' +
                                                    '<li data-ng-repeat="taxonomy in model.taxonomiesCollection" ' +
                                                    'class="dd-item">' +
                                                        '<!-- Level 1 -->' +
                                                        '<div class="dd-handle">' +
                                                            '<div class="icheckbox_square-blue checkbox-[[taxonomy.tree_slug]] ' +
                                                            '[[taxonomy.selected ? \'checked\' : \'\']] [[taxonomy.childs.length == 0? \'margin-right-30\' : \'\']]" ' +
                                                            'data-ng-click="SelectTaxonomy($event,taxonomy)" ' +
                                                            'data-ng-mouseover="updateICheckStyles($event, \'checkbox\', \'checkbox-\'+taxonomy.tree_slug, null)" ' +
                                                            'data-ng-mouseleave="updateICheckStyles($event, \'checkbox\', \'checkbox-\'+taxonomy.tree_slug, null)">' +
                                                            '</div>' +
                                                            '<a data-ng-if="taxonomy.childs.length > 0" style="color: #666 !important;margin-right:5px;">' +
                                                                '<i class="fa fa-angle-down"></i>' +
                                                            '</a>' +
                                                            '[[taxonomy.name_es]]' +
                                                        '</div>' +
                                                        '<!-- Level 2 -->' +
                                                        '<ol data-ng-if="taxonomy.childs.length > 0" class="dd-list">' +
                                                            '<li data-ng-repeat="child in taxonomy.childs" class="dd-item">' +
                                                                '<div class="dd-handle">' +
                                                                    '<div class="icheckbox_square-blue checkbox-[[child.tree_slug]] [[child.selected ? \'checked\' : \'\']] ' +
                                                                    '[[child.childs.length == 0? \'margin-right-30\' : \'\']]" ' +
                                                                    'data-ng-click="SelectTaxonomy($event,child)"' +
                                                                    'data-ng-mouseover="updateICheckStyles($event, \'checkbox\', \'checkbox-\'+child.tree_slug, null)" ' +
                                                                    'data-ng-mouseleave="updateICheckStyles($event, \'checkbox\', \'checkbox-\'+child.tree_slug, null)">' +
                                                                    '</div>' +
                                                                    '<a data-ng-if="child.childs.length > 0" style="color: #666 !important;margin-right:5px;">' +
                                                                        '<i class="fa fa-angle-down"></i>' +
                                                                    '</a>' +
                                                                    '[[child.name_es]]' +
                                                                '</div>' +
                                                                '<!-- Level 3 (and last) -->' +
                                                                '<ol data-ng-if="child.childs.length > 0" class="dd-list">' +
                                                                    '<li data-ng-repeat="grand_child in child.childs" class="dd-item">' +
                                                                        '<div class="dd-handle">' +
                                                                            '<div class="icheckbox_square-blue checkbox-[[grand_child.tree_slug]] [[grand_child.selected ? \'checked\' : \'\']] ' +
                                                                            '[[grand_child.childs.length == 0? \'margin-right-30\' : \'\']]" ' +
                                                                            'data-ng-click="SelectTaxonomy($event,grand_child)" ' +
                                                                            'data-ng-mouseover="updateICheckStyles($event, \'checkbox\', \'checkbox-\'+grand_child.tree_slug, null)" ' +
                                                                            'data-ng-mouseleave="updateICheckStyles($event, \'checkbox\', \'checkbox-\'+grand_child.tree_slug, null)">' +
                                                                            '</div>' +
                                                                            '<a data-ng-if="grand_child.childs.length > 0" style="color: #666 !important;margin-right:5px;">' +
                                                                                '<i class="fa fa-angle-down"></i>' +
                                                                            '</a>' +
                                                                            '[[grand_child.name_es]]' +
                                                                        '</div>' +
                                                                    '</li>' +
                                                                '</ol>' +
                                                            '</li>' +
                                                        '</ol>' +
                                                    '</li>' +
                                                '</ol>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>'+

                                    '<div data-ng-if="model.taxonomiesCollection.length == 0" style="position: relative;">'+
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
                                '<div class="form-actions" style="background-color:white;">'+
                                    '<div class="row">'+
                                        '<div class="col-xs-12 col-md-offset-4 col-md-8">'+
                                            '<button class="btn default btn-footer" type="button" ' +
                                            'data-ng-click="hideTaxonomySelector()">'+
                                                'Cancelar </button>'+
                                            '<button class="btn blue btn-blue btn-footer" type="submit" ' +
                                            'data-ng-click="saveSelectedTaxonomy()">'+
                                                'Aceptar </button>'+
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
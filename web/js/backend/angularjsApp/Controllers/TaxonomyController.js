/*
 * File for handling controllers for Backend Taxonomy Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.taxonomyController', ['BncBackend.taxonomyFactory']);


    /* Controller for handling Taxonomy functions */
    function taxonomyCtrller($scope, taxonomyFact){

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
        $scope.clearErrorsTaxonomyForm = function(){
            $scope.taxonomyModel.nameHasError = false;
            $scope.taxonomyModel.nameErrorClass = '';

            $scope.taxonomyModel.urlSlugHasError = false;
            $scope.taxonomyModel.urlSlugErrorClass = '';
        }
        
        /* clear form values */
        $scope.clearTaxonomyForm = function(){
            $scope.taxonomyModel.name = null;
            $scope.taxonomyModel.url_slug = null;
            $scope.taxonomyModel.parent_id = null;
            $scope.taxonomyModel.selected_parent = null;
        }
        
        /* create taxonomies */
        $scope.createTaxonomy = function()
        {
            if($scope.taxonomyModel.canCreateTaxonomy == true)
            {
                $scope.taxonomyModel.createAction = true;
                $scope.showTaxonomyForm();
            }
        }

        /* delete taxonomy */
        $scope.deleteTaxonomy = function(taxonomy_id)
        {
            var proceed = true;
            if(typeof taxonomy_id == 'string' && !$scope.taxonomyModel.canDeleteTaxonomy){
                proceed = false;
            }
            if(proceed){
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
                            $scope.taxonomyModel.createAction = false;
                            var taxonomiesIdCollection = [];
                            if(typeof taxonomy_id == 'string'){
                                if($scope.taxonomyModel.taxonomiesCollection.length > 0){
                                    for(var i=0; i<$scope.taxonomyModel.taxonomiesCollection.length; i++){
                                        if($scope.taxonomyModel.taxonomiesCollection[i].selected != undefined &&
                                            $scope.taxonomyModel.taxonomiesCollection[i].selected == true)
                                        {
                                            taxonomiesIdCollection.push($scope.taxonomyModel.taxonomiesCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                taxonomiesIdCollection.push(taxonomy_id);
                            }
                            var data = {
                                taxonomiesId: taxonomiesIdCollection
                            };
                            taxonomyFact.DeleteTaxonomies($scope, data);
                        }
                    });
            }
        }

        /* edit taxonomy */
        $scope.editTaxonomy = function(taxonomy)
        {
            $scope.taxonomyModel.createAction = false;
            $scope.taxonomyModel.selectedTaxonomy = taxonomy;
            $scope.showTaxonomyForm();
        }

        /* change the view mode of the taxonomies data */
        $scope.changeViewMode = function(option)
        {
            $scope.taxonomyModel.taxonomiesCollection = [];
            $scope.taxonomyModel.activeView = option;
            $scope.getTaxonomies();
        }

        /* get the Taxonomies Collection */
        $scope.getTaxonomies = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.taxonomyModel.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.taxonomyModel.generalSearchValue) &&
                $scope.taxonomyModel.showTaxonomyForm == false){
                    searchParametersCollection.generalSearchValue = $scope.taxonomyModel.generalSearchValue;
                }
            }
            if($scope.taxonomyModel.selectedType != null)
            {
                searchParametersCollection.taxonomyTypeTreeSlug = $scope.taxonomyModel.selectedType.tree_slug;
            }
            if($scope.taxonomyModel.showTaxonomyForm == true){
                searchParametersCollection.returnDataInTree = true;
            }
            else{
                searchParametersCollection.returnDataInTree = $scope.taxonomyModel.activeView == 'tree' ? true : false;
            }
            taxonomyFact.getTaxonomiesData($scope,searchParametersCollection);
        }

        /* function on scope for go ahead to top */
        $scope.goToTop = function()
        {
            var pageHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
            $('html, body').animate({scrollTop: pageHeading.height()}, 1000);
        }

        /* disabled options for CRUD operations */
        $scope.handleCrudOperations = function(option)
        {
            /* when option is 'disable' */
            if(option == 'disable'){
                $scope.taxonomyModel.canCreateTaxonomy = false;
                $scope.taxonomyModel.canEditTaxonomy = false;
                $scope.taxonomyModel.canDeleteTaxonomy = false;
            }
            else{/* else if 'reset'*/
                $scope.taxonomyModel.canCreateTaxonomy = true;
                $scope.taxonomyModel.canEditTaxonomy = false;
                $scope.taxonomyModel.canDeleteTaxonomy = false;
                $scope.taxonomyModel.allTaxonomiesSelected = false;
                /*$scope.taxonomyModel.selectedTaxonomy = null;*/
            }

        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.handleFormInputKeyEvents = function(event, field)
        {
            /* key events from input 'taxonomy_name' */
            if(event.currentTarget.id == 'taxonomy_name'){
                if(event.type == 'keyup' && $scope.taxonomyModel.name.length > 0){
                    $scope.taxonomyModel.url_slug = slugify($scope.taxonomyModel.name);
                }
                else if($scope.taxonomyModel.name.length == 0){
                    $scope.taxonomyModel.url_slug = null;
                }
            }
        }

        /* Hide the CRUD form */
        $scope.hideTaxonomyForm = function()
        {
            $scope.taxonomyModel.showTaxonomyForm = false;
            $scope.handleCrudOperations('reset');
            $scope.getTaxonomies();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.taxonomyModel.taxonomiesCurrentPage = 1;
            $scope.taxonomyModel.taxonomiesPagesCollection = [];
            $scope.taxonomyModel.taxonomiesPagesCollection.push(1);
            $scope.taxonomyModel.taxonomiesCurrentResultStart = 0;
            $scope.taxonomyModel.taxonomiesCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save taxonomy data */
        $scope.saveTaxonomyData = function(option)
        {
            $scope.toggleDataLoader();
            var canProceed = true;
            $scope.clearErrorsTaxonomyForm();
            var taxonomyData = {};
            taxonomyData.name = $scope.taxonomyModel.name;
            taxonomyData.url_slug = $scope.taxonomyModel.url_slug;
            taxonomyData.type_id = $scope.taxonomyModel.selectedType.id;
            taxonomyData.parent_id = null;
            if( $scope.taxonomyModel.selectedType.tree_slug != 'tag' && $scope.taxonomyModel.selected_parent != null){
                taxonomyData.parent_id = $scope.taxonomyModel.selected_parent.id;
            }
            if(taxonomyData.name == null || !alfaNumericRegExpr.test(taxonomyData.name) ||
            taxonomyData.url_slug == null || !alfaNumericRegExpr.test(taxonomyData.url_slug)){
                canProceed = false;
                if(taxonomyData.name == null || !alfaNumericRegExpr.test(taxonomyData.name)){
                    $scope.taxonomyModel.nameHasError = true;
                    $scope.taxonomyModel.nameErrorClass = 'has-error';
                }
                if(taxonomyData.url_slug == null || !alfaNumericRegExpr.test(taxonomyData.url_slug)){
                    $scope.taxonomyModel.urlSlugHasError = true;
                    $scope.taxonomyModel.urlSlugErrorClass = 'has-error';
                }
            }

            if(canProceed){
                if($scope.taxonomyModel.createAction == false){
                    taxonomyData.taxonomy_id = $scope.taxonomyModel.selectedTaxonomy.id
                }
                var taxonomyData = {taxonomyData: taxonomyData};
                var action = $scope.taxonomyModel.createAction == true ? 'create' : 'edit';

                taxonomyFact.SaveTaxonomyData($scope, taxonomyData, option, action);
            }
            else{
                $scope.toggleDataLoader();
                toastr.options.timeOut = 3000;
                toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
            }
        }

        /* search Taxonomies through Search Input Field */
        $scope.searchTaxonomies = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.taxonomyModel.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.taxonomyModel.generalSearchValue)){
                $scope.getTaxonomies();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.taxonomyModel.generalSearchValue = null;
                $scope.getTaxonomies();
            }
        }

        /* selecting/deselecting all taxonomies */
        $scope.SelectAllTaxonomies = function(event){
            var canDeleteAll = true;
            $scope.taxonomyModel.allTaxonomiesSelected = !$scope.taxonomyModel.allTaxonomiesSelected;
            if(!$scope.taxonomyModel.allTaxonomiesSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.taxonomyModel.taxonomiesCollection.length; i++){
                $scope.taxonomyModel.taxonomiesCollection[i].selected = $scope.taxonomyModel.allTaxonomiesSelected;
                if($scope.taxonomyModel.allTaxonomiesSelected == true && $scope.taxonomyModel.taxonomiesCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.taxonomyModel.canDeleteTaxonomy = canDeleteAll;
        }

        /*selecting/deselecting taxonomy */
        $scope.SelectTaxonomy= function(event,taxonomy){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalTaxonomiesSelected = 1;
            taxonomy.selected = !taxonomy.selected;
            if($scope.taxonomyModel.taxonomiesCollection.length == 1){
                if(taxonomy.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalTaxonomiesSelected = 0;
                }
                if(taxonomy.canDelete == 0){
                    canDeleteAll = false;
                }
                if(taxonomy.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.taxonomyModel.taxonomiesCollection.length > 1){
                totalTaxonomiesSelected = 0;
                for(var i=0; i<$scope.taxonomyModel.taxonomiesCollection.length; i++){
                    var taxonomy = $scope.taxonomyModel.taxonomiesCollection[i];
                    if(taxonomy.selected == true){
                        totalTaxonomiesSelected++;
                        if(taxonomy.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(taxonomy.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalTaxonomiesSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.taxonomyModel.canDeleteTaxonomy = true;
                    if(totalTaxonomiesSelected == $scope.taxonomyModel.taxonomiesCollection.length){
                        $scope.taxonomyModel.allTaxonomiesSelected = true;
                    }
                    else{
                        $scope.taxonomyModel.allTaxonomiesSelected = false;
                    }
                }
                if(totalTaxonomiesSelected == 1 && canEditAll == true){
                    $scope.taxonomyModel.canEditTaxonomy = true;
                }
                else{
                    $scope.taxonomyModel.canEditTaxonomy = false;
                }
            }
            else{
                $scope.taxonomyModel.canEditTaxonomy = false;
                $scope.taxonomyModel.canDeleteTaxonomy = false;
                $scope.taxonomyModel.allTaxonomiesSelected = false;
            }
        }

        /* select the taxonomy parent */
        $scope.selectTaxonomyParent = function()
        {
            if($scope.taxonomyModel.taxonomiesCollection.length > 0){
                var isParentSelected = false;
                for(var i=0; i<$scope.taxonomyModel.taxonomiesCollection.length; i++){
                    var taxonomy = $scope.taxonomyModel.taxonomiesCollection[i];
                    if(taxonomy.selected != undefined && taxonomy.selected == true){
                        isParentSelected = true;
                        $scope.taxonomyModel.selected_parent = taxonomy;
                        break;
                    }
                    else if(taxonomy.childs.length > 0){
                        for(var j=0; j<taxonomy.childs.length; j++){
                            var child = taxonomy.childs[j];
                            if(child.selected != undefined && child.selected == true){
                                isParentSelected = true;
                                $scope.taxonomyModel.selected_parent = child;
                                break;
                            }
                            else if(child.childs.length > 0){
                                for(var k=0; k<child.childs.length; k++){
                                    var grandChild = child.childs[k];
                                    if(grandChild.selected != undefined && grandChild.selected == true){
                                        isParentSelected = true;
                                        $scope.taxonomyModel.selected_parent = grandChild;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                if(isParentSelected == false){
                    $scope.taxonomyModel.selected_parent = null;
                }

                $('#taxonomy-parents-modal-selector').modal('hide');
            }
        }

        /* show parents selector */
        $scope.showParentsSelector = function(){
            $scope.taxonomyModel.taxonomiesCollection = [];
            $('#taxonomy-parents-modal-selector').modal('show');
            $scope.getTaxonomies();
        }

        /* show the form to Create/Edit Taxonomies */
        $scope.showTaxonomyForm = function()
        {
            $scope.taxonomyModel.taxonomyFormTitle = ($scope.taxonomyModel.createAction ? 'Nueva ' : 'Editar ')
                + $scope.taxonomyModel.selectedType.name_es;
            $scope.handleCrudOperations('disable');
            $scope.clearErrorsTaxonomyForm();
            $scope.clearTaxonomyForm();
            if($scope.taxonomyModel.createAction){

            }
            else{
                $scope.taxonomyModel.name = $scope.taxonomyModel.selectedTaxonomy.name_es;
                $scope.taxonomyModel.url_slug = $scope.taxonomyModel.selectedTaxonomy.url_slug_es;
                if($scope.taxonomyModel.selectedTaxonomy.parent_id != null){
                    $scope.taxonomyModel.selected_parent = {
                        id:  $scope.taxonomyModel.selectedTaxonomy.parent_id,
                        name_es:$scope.taxonomyModel.selectedTaxonomy.parent_name
                    };
                }
            }
            $scope.taxonomyModel.showTaxonomyForm = true;
            $scope.goToTop();
        }

        /* slugify text */
        function slugify(textToSlugify){
            var slugifiedText = textToSlugify.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\\-]+/g, '')
                .replace(/\\-\\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');

            return slugifiedText;
        }

        /* toggle data-loading message */
        $scope.toggleDataLoader = function()
        {
            $scope.taxonomyModel.loadingData = !$scope.taxonomyModel.loadingData;
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


                if(suffix == 'taxonomy-type'){
                    $('#taxonomy-types-modal-selector').modal('hide');
                }
            }

        }
        
        /* update the Selected Taxonomy Type value */
        $scope.updateSelectedTaxonomyType = function(event, taxonomyType){
            $scope.taxonomyModel.selectedType = taxonomyType;
            $scope.updateICheckStyles(event, 'radio', 'taxonomy-type', 'label-'+taxonomyType.tree_slug);
            $scope.getTaxonomies();
        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.taxonomyModel.taxonomiesCurrentResultStart = 0;
            $scope.taxonomyModel.taxonomiesCurrentResultLimit = 0;
            $scope.taxonomyModel.taxonomiesCurrentPage = ($scope.taxonomyModel.taxonomiesCurrentPage*1);
            $scope.taxonomyModel.taxonomiesCurrentPageSize = ($scope.taxonomyModel.taxonomiesCurrentPageSize*1);

            if($scope.taxonomyModel.taxonomiesCollection.length > 0){
                $scope.taxonomyModel.taxonomiesCurrentResultStart = ($scope.taxonomyModel.taxonomiesCurrentPage - 1) * $scope.taxonomyModel.taxonomiesCurrentPageSize + 1;
                $scope.taxonomyModel.taxonomiesCurrentResultLimit = ($scope.taxonomyModel.taxonomiesCurrentPageSize * $scope.taxonomyModel.taxonomiesCurrentPage);
                if($scope.taxonomyModel.taxonomiesCollection.length < ($scope.taxonomyModel.taxonomiesCurrentPageSize * $scope.taxonomyModel.taxonomiesCurrentPage)){

                    $scope.taxonomyModel.taxonomiesCurrentResultLimit = $scope.taxonomyModel.taxonomiesCollection.length;
                }

                var totalPages = Math.ceil($scope.taxonomyModel.taxonomiesCollection.length / $scope.taxonomyModel.taxonomiesCurrentPageSize);
                $scope.taxonomyModel.taxonomiesPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.taxonomyModel.taxonomiesPagesCollection.push(i);
                    }
                }
                else{
                    $scope.taxonomyModel.taxonomiesPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        


        /*
        * Initialization Functions
        * 
        * */
        $scope.initVisualization = function (crudOperationOption){
            /*list view variables*/
            $scope.taxonomyModel.createAction = null;
            if(crudOperationOption == undefined){
                crudOperationOption = 'reset';
            }
            $scope.handleCrudOperations(crudOperationOption);
            $scope.taxonomyModel.allTaxonomiesSelected = false;
            $scope.taxonomyModel.loadingData = false;
            $scope.taxonomyModel.activeView = 'list';
            if($scope.taxonomyModel.taxonomyTypesCollection.length > 0){
                $scope.taxonomyModel.type = $scope.taxonomyModel.taxonomyTypesCollection[0].tree_slug;
                $scope.taxonomyModel.selectedType = $scope.taxonomyModel.taxonomyTypesCollection[0];
                setTimeout(function(){
                    $scope.updateICheckStyles(null, 'radio', 'taxonomy-type', 'label-'+$scope.taxonomyModel.selectedType.tree_slug);
                },1000);
            }
            /*form view variables*/
            $scope.taxonomyModel.showTaxonomyForm = false;
            $scope.taxonomyModel.processingData = false;

            $scope.updatePaginationValues();
            $scope.clearErrorsTaxonomyForm();
        }
        function initValues(){
            /*generals variables*/
            $scope.taxonomyModel = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.taxonomyModel.taxonomiesCollection = [];
            $scope.taxonomyModel.taxonomyTypesCollection = [];
            $scope.taxonomyModel.taxonomySelectedCounter = 0;
            $scope.taxonomyModel.generalSearchValue = null;
            $scope.taxonomyModel.selectedType = null;
            $scope.taxonomyModel.selectedTaxonomy = null;
            /*pagination*/
            $scope.taxonomyModel.entriesSizesCollection = [];
            $scope.taxonomyModel.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.taxonomyModel.taxonomiesCurrentPageSize = 20;
            $scope.taxonomyModel.taxonomiesCurrentPage = 1;
            $scope.taxonomyModel.taxonomiesPagesCollection = [];
            $scope.taxonomyModel.taxonomiesPagesCollection.push(1);
            $scope.taxonomyModel.taxonomiesCurrentResultStart = 0;
            $scope.taxonomyModel.taxonomiesCurrentResultLimit = 0;
            /*form view variables*/
            $scope.taxonomyModel.parentsCollection = [];
            $scope.taxonomyModel.taxonomyFormTitle = '';
            
            $scope.clearTaxonomyForm();
            var searchParametersCollection = {};
            taxonomyFact.loadInitialsData($scope,searchParametersCollection);
        }
        function init(){
            initValues();
        }
        init();

    }


    /* Declaring controllers functions for this module */
    angular.module('BncBackend.taxonomyController').controller('taxonomyCtrller',taxonomyCtrller);
})();
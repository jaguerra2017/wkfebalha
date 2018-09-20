/*
 * File for handling controllers for Backend Publications Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.publicationsController', ['BncBackend.publicationsFactory']);


    /* Controller for handling Publications functions */
    function publicationsCtrller($scope, $filter, publicationsFact){

        /*
         * Global variables
         * 
         * */
        var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");
        var dateFilter = $filter("date");
        var dateRegExpress = new RegExp("[0-9]{2}/\[0-9]{2}/\[0-9]{4}");

        
        /*
         * Operations Functions
         * 
         * */
        /* clear errors of the form */
        $scope.clearErrorsPublicationsForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearPublicationsForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedPublication = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create publications */
        $scope.createPublications = function()
        {
            if($scope.model.canCreatePublications == true)
            {
                $scope.model.createAction = true;
                $scope.clearPublicationsForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showPublicationsForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedPublication.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedPublication.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedPublication.published_date;
                    var publishedDateCollection = publishedDate.split('/');
                    if(publishedDateCollection.length == 3){
                        var currentDate = new Date();
                        var publishedDate = new Date(publishedDateCollection[2],publishedDateCollection[1]-1,publishedDateCollection[0]);
                        if(publishedDate > currentDate){
                            proceed = false;
                        }
                    }
                }
            }
            return proceed;
        }

        /* delete publications */
        $scope.deletePublications = function(publications_id)
        {
            var proceed = true;
            if(typeof publications_id == 'string' && !$scope.model.canDeletePublications){
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
                            $scope.model.createAction = false;
                            var publicationsIdCollection = [];
                            if(typeof publications_id == 'string'){
                                if($scope.model.publicationsCollection.length > 0){
                                    for(var i=0; i<$scope.model.publicationsCollection.length; i++){
                                        if($scope.model.publicationsCollection[i].selected != undefined &&
                                            $scope.model.publicationsCollection[i].selected == true)
                                        {
                                            publicationsIdCollection.push($scope.model.publicationsCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                publicationsIdCollection.push(publications_id);
                            }
                            var data = {
                                publicationsId: publicationsIdCollection
                            };
                            publicationsFact.deletePublications($scope, data);
                        }
                    });
            }
        }

        /* edit publications */
        $scope.editPublications = function(publication)
        {
            $scope.model.createAction = false;
            $scope.clearPublicationsForm();
            $scope.clearPublicationsForm();
            $scope.model.selectedPublication = publication;
            $scope.showPublicationsForm();
        }

        /* change the view mode of the publications data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.publicationsCollection = [];
            $scope.model.activeView = option;
            $scope.getPublications();
        }

        /* get the Publications Collection */
        $scope.getPublications = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showPublicationsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            if($scope.model.selectedType != null)
            {
                searchParametersCollection.publicationsTypeTreeSlug = $scope.model.selectedType.tree_slug;
            }
            if($scope.model.showPublicationsForm == true){
                searchParametersCollection.returnDataInTree = true;
            }
            else{
                searchParametersCollection.returnDataInTree = $scope.model.activeView == 'tree' ? true : false;
            }
            publicationsFact.getPublicationsData($scope,searchParametersCollection);
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
                $scope.model.canCreatePublications = false;
                $scope.model.canEditPublications = false;
                $scope.model.canDeletePublications = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreatePublications = true;
                $scope.model.canEditPublications = false;
                $scope.model.canDeletePublications = false;
                $scope.model.allPublicationsSelected = false;
                $scope.model.selectedPublication = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hidePublicationsForm = function()
        {
            $scope.model.showPublicationsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getPublications();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.publicationsCurrentPage = 1;
            $scope.model.publicationsPagesCollection = [];
            $scope.model.publicationsPagesCollection.push(1);
            $scope.model.publicationsCurrentResultStart = 0;
            $scope.model.publicationsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save publications data */
        $scope.savePublicationsData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsPublicationsForm();

                if($scope.model.selectedPublication.title_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedPublication.title_es) ||
                $scope.model.selectedPublication.url_slug_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedPublication.url_slug_es) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedPublication.title_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedPublication.title_es)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedPublication.url_slug_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedPublication.url_slug_es)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedPublication.content_es = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedPublication.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedPublication.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedPublication.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedPublication.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     var publicationsData = {publicationData: $scope.model.selectedPublication};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     publicationsFact.savePublicationsData($scope, publicationsData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Publications through Search Input Field */
        $scope.searchPublications = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getPublications();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getPublications();
            }
        }

        /* selecting/deselecting all publications */
        $scope.selectAllPublications = function(event){
            var canDeleteAll = true;
            $scope.model.allPublicationsSelected = !$scope.model.allPublicationsSelected;
            if(!$scope.model.allPublicationsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.publicationsCollection.length; i++){
                $scope.model.publicationsCollection[i].selected = $scope.model.allPublicationsSelected;
                if($scope.model.allPublicationsSelected == true && $scope.model.publicationsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeletePublications = canDeleteAll;
        }

        /*selecting/deselecting publications */
        $scope.selectPublications= function(event,publications){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalPublicationsSelected = 1;
            publications.selected = !publications.selected;
            if($scope.model.publicationsCollection.length == 1){
                if(publications.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalPublicationsSelected = 0;
                }
                if(publications.canDelete == 0){
                    canDeleteAll = false;
                }
                if(publications.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.publicationsCollection.length > 1){
                totalPublicationsSelected = 0;
                for(var i=0; i<$scope.model.publicationsCollection.length; i++){
                    var publications = $scope.model.publicationsCollection[i];
                    if(publications.selected == true){
                        totalPublicationsSelected++;
                        if(publications.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(publications.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalPublicationsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeletePublications = true;
                    if(totalPublicationsSelected == $scope.model.publicationsCollection.length){
                        $scope.model.allPublicationsSelected = true;
                    }
                    else{
                        $scope.model.allPublicationsSelected = false;
                    }
                }
                if(totalPublicationsSelected == 1 && canEditAll == true){
                    $scope.model.canEditPublications = true;
                }
                else{
                    $scope.model.canEditPublications = false;
                }
            }
            else{
                $scope.model.canEditPublications = false;
                $scope.model.canDeletePublications = false;
                $scope.model.allPublicationsSelected = false;
            }
        }

        /* show the form to Create/Edit Publications */
        $scope.showPublicationsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showPublicationsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showPublicationsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    publicationId : $scope.model.selectedPublication.id
                };
                publicationsFact.getPublicationsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedPublication = response.data.publicationData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedPublication.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedPublication.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedPublication.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedPublication.featured_image_url,
                            id : $scope.model.selectedPublication.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedPublication.content_es);

                });
            }
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


                if(suffix == 'publications-type'){
                    $('#publications-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.publicationsCurrentResultStart = 0;
            $scope.model.publicationsCurrentResultLimit = 0;
            $scope.model.publicationsCurrentPage = ($scope.model.publicationsCurrentPage*1);
            $scope.model.publicationsCurrentPageSize = ($scope.model.publicationsCurrentPageSize*1);

            if($scope.model.publicationsCollection.length > 0){
                $scope.model.publicationsCurrentResultStart = ($scope.model.publicationsCurrentPage - 1) * $scope.model.publicationsCurrentPageSize + 1;
                $scope.model.publicationsCurrentResultLimit = ($scope.model.publicationsCurrentPageSize * $scope.model.publicationsCurrentPage);
                if($scope.model.publicationsCollection.length < ($scope.model.publicationsCurrentPageSize * $scope.model.publicationsCurrentPage)){

                    $scope.model.publicationsCurrentResultLimit = $scope.model.publicationsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.publicationsCollection.length / $scope.model.publicationsCurrentPageSize);
                $scope.model.publicationsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.publicationsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.publicationsPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updatePublicationsForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedPublication.title_es != null &&
                        alfaNumericRegExpr.test($scope.model.selectedPublication.title_es)){
                        $scope.model.selectedPublication.url_slug_es = slugify($scope.model.selectedPublication.title_es);
                    }
                    else{
                        $scope.model.selectedPublication.url_slug_es = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedPublication.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedPublication.published_date = null;
                            $scope.model.publishedDateHasError = false;
                        }
                    break;
            }
        }

        /*update form views section*/
        $scope.updateFormSection = function(section){
            $scope.model.formActiveView = section;
        }

        


        /*
        * Initialization Functions
        * 
        * */
        $scope.initVisualization = function (){

            $scope.updatePaginationValues();
            $scope.clearErrorsPublicationsForm();
        }
        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.model.publicationsCollection = [];
            $scope.model.publicationsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.publicationsCurrentPageSize = 20;
            $scope.model.publicationsCurrentPage = 1;
            $scope.model.publicationsPagesCollection = [];
            $scope.model.publicationsPagesCollection.push(1);
            $scope.model.publicationsCurrentResultStart = 0;
            $scope.model.publicationsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allPublicationsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showPublicationsForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPublication = null;

            $scope.clearPublicationsForm();
            publicationsFact.loadInitialsData($scope);
        }
        init();

    }


    /* Declaring controllers functions for this module */
    angular.module('BncBackend.publicationsController').controller('publicationsCtrller',publicationsCtrller);
})();
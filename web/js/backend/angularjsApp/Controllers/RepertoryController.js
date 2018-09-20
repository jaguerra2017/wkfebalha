/*
 * File for handling controllers for Backend Repertory Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.repertoryController', ['BncBackend.repertoryFactory']);


    /* Controller for handling Repertory functions */
    function repertoryCtrller($scope, $filter, repertoryFact){

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
        $scope.clearErrorsRepertoryForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearRepertoryForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedRepertory = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create repertory */
        $scope.createRepertory = function()
        {
            if($scope.model.canCreateRepertory == true)
            {
                $scope.model.createAction = true;
                $scope.clearRepertoryForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showRepertoryForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedRepertory.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedRepertory.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedRepertory.published_date;
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

        /* delete repertory */
        $scope.deleteRepertory = function(repertory_id)
        {
            var proceed = true;
            if(typeof repertory_id == 'string' && !$scope.model.canDeleteRepertory){
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
                            var repertoryIdCollection = [];
                            if(typeof repertory_id == 'string'){
                                if($scope.model.repertoryCollection.length > 0){
                                    for(var i=0; i<$scope.model.repertoryCollection.length; i++){
                                        if($scope.model.repertoryCollection[i].selected != undefined &&
                                            $scope.model.repertoryCollection[i].selected == true)
                                        {
                                            repertoryIdCollection.push($scope.model.repertoryCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                repertoryIdCollection.push(repertory_id);
                            }
                            var data = {
                                repertoryId: repertoryIdCollection
                            };
                            repertoryFact.deleteRepertory($scope, data);
                        }
                    });
            }
        }

        /* edit repertory */
        $scope.editRepertory = function(repertory)
        {
            $scope.model.createAction = false;
            $scope.clearRepertoryForm();
            $scope.clearRepertoryForm();
            $scope.model.selectedRepertory = repertory;
            $scope.showRepertoryForm();
        }

        /* change the view mode of the repertory data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.repertoryCollection = [];
            $scope.model.activeView = option;
            $scope.getRepertory();
        }

        /* get the Repertory Collection */
        $scope.getRepertory = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showRepertoryForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            repertoryFact.getRepertoryData($scope,searchParametersCollection, function(response){
                    $scope.model.repertoryCollection = response.data.repertoryDataCollection;
                    $scope.updatePaginationValues();
                    $scope.toggleDataLoader();
                },
                function(response){
                    t$scope.updatePaginationValues();
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

        /* disabled options for CRUD operations */
        $scope.handleCrudOperations = function(option)
        {
            /* when option is 'disable' */
            if(option == 'disable'){
                $scope.model.canCreateRepertory = false;
                $scope.model.canEditRepertory = false;
                $scope.model.canDeleteRepertory = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateRepertory = true;
                $scope.model.canEditRepertory = false;
                $scope.model.canDeleteRepertory = false;
                $scope.model.allRepertorySelected = false;
                $scope.model.selectedRepertory = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideRepertoryForm = function()
        {
            $scope.model.showRepertoryForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getRepertory();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.repertoryCurrentPage = 1;
            $scope.model.repertoryPagesCollection = [];
            $scope.model.repertoryPagesCollection.push(1);
            $scope.model.repertoryCurrentResultStart = 0;
            $scope.model.repertoryCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save repertory data */
        $scope.saveRepertoryData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsRepertoryForm();

                if($scope.model.selectedRepertory.title_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedRepertory.title_es) ||
                $scope.model.selectedRepertory.url_slug_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedRepertory.url_slug_es) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedRepertory.title_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedRepertory.title_es)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedRepertory.url_slug_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedRepertory.url_slug_es)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedRepertory.content_es = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedRepertory.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedRepertory.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedRepertory.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedRepertory.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     var repertoryData = {repertoryData: $scope.model.selectedRepertory};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     repertoryFact.saveRepertoryData($scope, repertoryData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Repertory through Search Input Field */
        $scope.searchRepertory = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getRepertory();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getRepertory();
            }
        }

        /* selecting/deselecting all repertory */
        $scope.selectAllRepertory = function(event){
            var canDeleteAll = true;
            $scope.model.allRepertorySelected = !$scope.model.allRepertorySelected;
            if(!$scope.model.allRepertorySelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.repertoryCollection.length; i++){
                $scope.model.repertoryCollection[i].selected = $scope.model.allRepertorySelected;
                if($scope.model.allRepertorySelected == true && $scope.model.repertoryCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteRepertory = canDeleteAll;
        }

        /*selecting/deselecting repertory */
        $scope.selectRepertory= function(event,repertory){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalRepertorySelected = 1;
            repertory.selected = !repertory.selected;
            if($scope.model.repertoryCollection.length == 1){
                if(repertory.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalRepertorySelected = 0;
                }
                if(repertory.canDelete == 0){
                    canDeleteAll = false;
                }
                if(repertory.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.repertoryCollection.length > 1){
                totalRepertorySelected = 0;
                for(var i=0; i<$scope.model.repertoryCollection.length; i++){
                    var repertory = $scope.model.repertoryCollection[i];
                    if(repertory.selected == true){
                        totalRepertorySelected++;
                        if(repertory.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(repertory.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalRepertorySelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteRepertory = true;
                    if(totalRepertorySelected == $scope.model.repertoryCollection.length){
                        $scope.model.allRepertorySelected = true;
                    }
                    else{
                        $scope.model.allRepertorySelected = false;
                    }
                }
                if(totalRepertorySelected == 1 && canEditAll == true){
                    $scope.model.canEditRepertory = true;
                }
                else{
                    $scope.model.canEditRepertory = false;
                }
            }
            else{
                $scope.model.canEditRepertory = false;
                $scope.model.canDeleteRepertory = false;
                $scope.model.allRepertorySelected = false;
            }
        }

        /* show the form to Create/Edit Repertory */
        $scope.showRepertoryForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showRepertoryForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showRepertoryForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    repertoryId : $scope.model.selectedRepertory.id
                };
                repertoryFact.getRepertoryData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedRepertory = response.data.repertoryData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedRepertory.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedRepertory.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedRepertory.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedRepertory.featured_image_url,
                            id : $scope.model.selectedRepertory.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedRepertory.content_es);

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


                if(suffix == 'repertory-type'){
                    $('#repertory-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.repertoryCurrentResultStart = 0;
            $scope.model.repertoryCurrentResultLimit = 0;
            $scope.model.repertoryCurrentPage = ($scope.model.repertoryCurrentPage*1);
            $scope.model.repertoryCurrentPageSize = ($scope.model.repertoryCurrentPageSize*1);

            if($scope.model.repertoryCollection.length > 0){
                $scope.model.repertoryCurrentResultStart = ($scope.model.repertoryCurrentPage - 1) * $scope.model.repertoryCurrentPageSize + 1;
                $scope.model.repertoryCurrentResultLimit = ($scope.model.repertoryCurrentPageSize * $scope.model.repertoryCurrentPage);
                if($scope.model.repertoryCollection.length < ($scope.model.repertoryCurrentPageSize * $scope.model.repertoryCurrentPage)){

                    $scope.model.repertoryCurrentResultLimit = $scope.model.repertoryCollection.length;
                }

                var totalPages = Math.ceil($scope.model.repertoryCollection.length / $scope.model.repertoryCurrentPageSize);
                $scope.model.repertoryPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.repertoryPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.repertoryPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateRepertoryForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedRepertory.title_es != null &&
                        alfaNumericRegExpr.test($scope.model.selectedRepertory.title_es)){
                        $scope.model.selectedRepertory.url_slug_es = slugify($scope.model.selectedRepertory.title_es);
                    }
                    else{
                        $scope.model.selectedRepertory.url_slug_es = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedRepertory.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedRepertory.published_date = null;
                            $scope.model.publishedDateHasError = false;
                        }
                    break;
            }
        }

        /*update form views section*/
        $scope.updateFormSection = function(section){
            $scope.model.formActiveView = section;
        }

        


        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.model.repertoryCollection = [];
            $scope.model.repertorySelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.repertoryCurrentPageSize = 20;
            $scope.model.repertoryCurrentPage = 1;
            $scope.model.repertoryPagesCollection = [];
            $scope.model.repertoryPagesCollection.push(1);
            $scope.model.repertoryCurrentResultStart = 0;
            $scope.model.repertoryCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allRepertorySelected = false;
            $scope.model.loadingData = false;
            $scope.model.showRepertoryForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedRepertory = null;

            $scope.clearRepertoryForm();
            repertoryFact.loadInitialsData($scope, function(response){
                $scope.model.repertoryCollection = response.data.initialsData.repertoryDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showRepertoryForm = response.data.initialsData.showRepertoryForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsRepertoryForm();

                if(showRepertoryForm == true){
                        $scope.createRepertory();
                    }
            },
            function(response){
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
        init();

    }


    /* Declaring controllers functions for this module */
    angular.module('BncBackend.repertoryController').controller('repertoryCtrller',repertoryCtrller);
})();
/*
 * File for handling controllers for Backend Jewels Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.jewelsController', ['BncBackend.jewelsFactory']);


    /* Controller for handling Jewels functions */
    function jewelsCtrller($scope, $filter, jewelsFact){

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
        $scope.clearErrorsJewelsForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearJewelsForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedJewel = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create jewels */
        $scope.createJewels = function()
        {
            if($scope.model.canCreateJewels == true)
            {
                $scope.model.createAction = true;
                $scope.clearJewelsForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showJewelsForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedJewel.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedJewel.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedJewel.published_date;
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

        /* delete jewels */
        $scope.deleteJewels = function(jewels_id)
        {
            var proceed = true;
            if(typeof jewels_id == 'string' && !$scope.model.canDeleteJewels){
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
                            var jewelsIdCollection = [];
                            if(typeof jewels_id == 'string'){
                                if($scope.model.jewelsCollection.length > 0){
                                    for(var i=0; i<$scope.model.jewelsCollection.length; i++){
                                        if($scope.model.jewelsCollection[i].selected != undefined &&
                                            $scope.model.jewelsCollection[i].selected == true)
                                        {
                                            jewelsIdCollection.push($scope.model.jewelsCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                jewelsIdCollection.push(jewels_id);
                            }
                            var data = {
                                jewelsId: jewelsIdCollection
                            };
                            jewelsFact.deleteJewels($scope, data);
                        }
                    });
            }
        }

        /* edit jewels */
        $scope.editJewels = function(jewel)
        {
            $scope.model.createAction = false;
            $scope.clearJewelsForm();
            $scope.clearJewelsForm();
            $scope.model.selectedJewel = jewel;
            $scope.showJewelsForm();
        }

        /* change the view mode of the jewels data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.jewelsCollection = [];
            $scope.model.activeView = option;
            $scope.getJewels();
        }

        /* get the Jewels Collection */
        $scope.getJewels = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showJewelsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            jewelsFact.getJewelsData($scope,searchParametersCollection, function(response){
                    $scope.model.jewelsCollection = response.data.jewelsDataCollection;
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
                $scope.model.canCreateJewels = false;
                $scope.model.canEditJewels = false;
                $scope.model.canDeleteJewels = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateJewels = true;
                $scope.model.canEditJewels = false;
                $scope.model.canDeleteJewels = false;
                $scope.model.allJewelsSelected = false;
                $scope.model.selectedJewel = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideJewelsForm = function()
        {
            $scope.model.showJewelsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getJewels();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.jewelsCurrentPage = 1;
            $scope.model.jewelsPagesCollection = [];
            $scope.model.jewelsPagesCollection.push(1);
            $scope.model.jewelsCurrentResultStart = 0;
            $scope.model.jewelsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save jewels data */
        $scope.saveJewelsData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsJewelsForm();

                if($scope.model.selectedJewel.title_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedJewel.title_es) ||
                $scope.model.selectedJewel.url_slug_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedJewel.url_slug_es) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedJewel.title_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedJewel.title_es)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedJewel.url_slug_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedJewel.url_slug_es)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedJewel.content_es = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedJewel.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedJewel.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedJewel.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedJewel.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     var jewelsData = {jewelData: $scope.model.selectedJewel};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     jewelsFact.saveJewelsData($scope, jewelsData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Jewels through Search Input Field */
        $scope.searchJewels = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getJewels();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getJewels();
            }
        }

        /* selecting/deselecting all jewels */
        $scope.selectAllJewels = function(event){
            var canDeleteAll = true;
            $scope.model.allJewelsSelected = !$scope.model.allJewelsSelected;
            if(!$scope.model.allJewelsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.jewelsCollection.length; i++){
                $scope.model.jewelsCollection[i].selected = $scope.model.allJewelsSelected;
                if($scope.model.allJewelsSelected == true && $scope.model.jewelsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteJewels = canDeleteAll;
        }

        /*selecting/deselecting jewels */
        $scope.selectJewels= function(event,jewels){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalJewelsSelected = 1;
            jewels.selected = !jewels.selected;
            if($scope.model.jewelsCollection.length == 1){
                if(jewels.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalJewelsSelected = 0;
                }
                if(jewels.canDelete == 0){
                    canDeleteAll = false;
                }
                if(jewels.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.jewelsCollection.length > 1){
                totalJewelsSelected = 0;
                for(var i=0; i<$scope.model.jewelsCollection.length; i++){
                    var jewels = $scope.model.jewelsCollection[i];
                    if(jewels.selected == true){
                        totalJewelsSelected++;
                        if(jewels.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(jewels.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalJewelsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteJewels = true;
                    if(totalJewelsSelected == $scope.model.jewelsCollection.length){
                        $scope.model.allJewelsSelected = true;
                    }
                    else{
                        $scope.model.allJewelsSelected = false;
                    }
                }
                if(totalJewelsSelected == 1 && canEditAll == true){
                    $scope.model.canEditJewels = true;
                }
                else{
                    $scope.model.canEditJewels = false;
                }
            }
            else{
                $scope.model.canEditJewels = false;
                $scope.model.canDeleteJewels = false;
                $scope.model.allJewelsSelected = false;
            }
        }

        /* show the form to Create/Edit Jewels */
        $scope.showJewelsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showJewelsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showJewelsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    jewelId : $scope.model.selectedJewel.id
                };
                jewelsFact.getJewelsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedJewel = response.data.jewelData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedJewel.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedJewel.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedJewel.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedJewel.featured_image_url,
                            id : $scope.model.selectedJewel.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedJewel.content_es);

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


                if(suffix == 'jewels-type'){
                    $('#jewels-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.jewelsCurrentResultStart = 0;
            $scope.model.jewelsCurrentResultLimit = 0;
            $scope.model.jewelsCurrentPage = ($scope.model.jewelsCurrentPage*1);
            $scope.model.jewelsCurrentPageSize = ($scope.model.jewelsCurrentPageSize*1);

            if($scope.model.jewelsCollection.length > 0){
                $scope.model.jewelsCurrentResultStart = ($scope.model.jewelsCurrentPage - 1) * $scope.model.jewelsCurrentPageSize + 1;
                $scope.model.jewelsCurrentResultLimit = ($scope.model.jewelsCurrentPageSize * $scope.model.jewelsCurrentPage);
                if($scope.model.jewelsCollection.length < ($scope.model.jewelsCurrentPageSize * $scope.model.jewelsCurrentPage)){

                    $scope.model.jewelsCurrentResultLimit = $scope.model.jewelsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.jewelsCollection.length / $scope.model.jewelsCurrentPageSize);
                $scope.model.jewelsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.jewelsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.jewelsPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateJewelsForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedJewel.title_es != null &&
                        alfaNumericRegExpr.test($scope.model.selectedJewel.title_es)){
                        $scope.model.selectedJewel.url_slug_es = slugify($scope.model.selectedJewel.title_es);
                    }
                    else{
                        $scope.model.selectedJewel.url_slug_es = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedJewel.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedJewel.published_date = null;
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
            $scope.model.jewelsCollection = [];
            $scope.model.jewelsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.jewelsCurrentPageSize = 20;
            $scope.model.jewelsCurrentPage = 1;
            $scope.model.jewelsPagesCollection = [];
            $scope.model.jewelsPagesCollection.push(1);
            $scope.model.jewelsCurrentResultStart = 0;
            $scope.model.jewelsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allJewelsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showJewelsForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedJewel = null;

            $scope.clearJewelsForm();
            jewelsFact.loadInitialsData($scope, function(response){
                $scope.model.jewelsCollection = response.data.initialsData.jewelsDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showJewelsForm = response.data.initialsData.showJewelsForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsJewelsForm();

                if(showJewelsForm == true){
                        $scope.createJewels();
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
    angular.module('BncBackend.jewelsController').controller('jewelsCtrller',jewelsCtrller);
})();
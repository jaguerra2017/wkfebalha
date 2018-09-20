/*
 * File for handling controllers for Backend Composition Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.compositionController', ['BncBackend.compositionFactory']);


    /* Controller for handling Composition functions */
    function compositionCtrller($scope, $filter, compositionFact){

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
        $scope.clearErrorsCompositionForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearCompositionForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedComposition = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create composition */
        $scope.createComposition = function()
        {
            if($scope.model.canCreateComposition == true)
            {
                $scope.model.createAction = true;
                $scope.clearCompositionForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showCompositionForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedComposition.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedComposition.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedComposition.published_date;
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

        /* delete composition */
        $scope.deleteComposition = function(composition_id)
        {
            var proceed = true;
            if(typeof composition_id == 'string' && !$scope.model.canDeleteComposition){
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
                            var compositionIdCollection = [];
                            if(typeof composition_id == 'string'){
                                if($scope.model.compositionCollection.length > 0){
                                    for(var i=0; i<$scope.model.compositionCollection.length; i++){
                                        if($scope.model.compositionCollection[i].selected != undefined &&
                                            $scope.model.compositionCollection[i].selected == true)
                                        {
                                            compositionIdCollection.push($scope.model.compositionCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                compositionIdCollection.push(composition_id);
                            }
                            var data = {
                                compositionId: compositionIdCollection
                            };
                            compositionFact.deleteComposition($scope, data);
                        }
                    });
            }
        }

        /* edit composition */
        $scope.editComposition = function(composition)
        {
            $scope.model.createAction = false;
            $scope.clearCompositionForm();
            $scope.clearCompositionForm();
            $scope.model.selectedComposition = composition;
            $scope.showCompositionForm();
        }

        /* change the view mode of the composition data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.compositionCollection = [];
            $scope.model.activeView = option;
            $scope.getComposition();
        }

        /* get the Composition Collection */
        $scope.getComposition = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showCompositionForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            compositionFact.getCompositionData($scope,searchParametersCollection, function(response){
                    $scope.model.compositionCollection = response.data.compositionDataCollection;
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
                $scope.model.canCreateComposition = false;
                $scope.model.canEditComposition = false;
                $scope.model.canDeleteComposition = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateComposition = true;
                $scope.model.canEditComposition = false;
                $scope.model.canDeleteComposition = false;
                $scope.model.allCompositionSelected = false;
                $scope.model.selectedComposition = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideCompositionForm = function()
        {
            $scope.model.showCompositionForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getComposition();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.compositionCurrentPage = 1;
            $scope.model.compositionPagesCollection = [];
            $scope.model.compositionPagesCollection.push(1);
            $scope.model.compositionCurrentResultStart = 0;
            $scope.model.compositionCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save composition data */
        $scope.saveCompositionData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsCompositionForm();

                if($scope.model.selectedComposition.title_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedComposition.title_es) ||
                $scope.model.selectedComposition.url_slug_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedComposition.url_slug_es) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedComposition.title_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedComposition.title_es)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedComposition.url_slug_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedComposition.url_slug_es)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedComposition.content_es = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedComposition.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedComposition.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedComposition.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedComposition.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     var compositionData = {compositionData: $scope.model.selectedComposition};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     compositionFact.saveCompositionData($scope, compositionData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Composition through Search Input Field */
        $scope.searchComposition = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getComposition();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getComposition();
            }
        }

        /* selecting/deselecting all composition */
        $scope.selectAllComposition = function(event){
            var canDeleteAll = true;
            $scope.model.allCompositionSelected = !$scope.model.allCompositionSelected;
            if(!$scope.model.allCompositionSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.compositionCollection.length; i++){
                $scope.model.compositionCollection[i].selected = $scope.model.allCompositionSelected;
                if($scope.model.allCompositionSelected == true && $scope.model.compositionCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteComposition = canDeleteAll;
        }

        /*selecting/deselecting composition */
        $scope.selectComposition= function(event,composition){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalCompositionSelected = 1;
            composition.selected = !composition.selected;
            if($scope.model.compositionCollection.length == 1){
                if(composition.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalCompositionSelected = 0;
                }
                if(composition.canDelete == 0){
                    canDeleteAll = false;
                }
                if(composition.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.compositionCollection.length > 1){
                totalCompositionSelected = 0;
                for(var i=0; i<$scope.model.compositionCollection.length; i++){
                    var composition = $scope.model.compositionCollection[i];
                    if(composition.selected == true){
                        totalCompositionSelected++;
                        if(composition.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(composition.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalCompositionSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteComposition = true;
                    if(totalCompositionSelected == $scope.model.compositionCollection.length){
                        $scope.model.allCompositionSelected = true;
                    }
                    else{
                        $scope.model.allCompositionSelected = false;
                    }
                }
                if(totalCompositionSelected == 1 && canEditAll == true){
                    $scope.model.canEditComposition = true;
                }
                else{
                    $scope.model.canEditComposition = false;
                }
            }
            else{
                $scope.model.canEditComposition = false;
                $scope.model.canDeleteComposition = false;
                $scope.model.allCompositionSelected = false;
            }
        }

        /* show the form to Create/Edit Composition */
        $scope.showCompositionForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showCompositionForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showCompositionForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    compositionId : $scope.model.selectedComposition.id
                };
                compositionFact.getCompositionData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedComposition = response.data.compositionData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedComposition.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedComposition.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedComposition.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedComposition.featured_image_url,
                            id : $scope.model.selectedComposition.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedComposition.content_es);

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


                if(suffix == 'composition-type'){
                    $('#composition-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.compositionCurrentResultStart = 0;
            $scope.model.compositionCurrentResultLimit = 0;
            $scope.model.compositionCurrentPage = ($scope.model.compositionCurrentPage*1);
            $scope.model.compositionCurrentPageSize = ($scope.model.compositionCurrentPageSize*1);

            if($scope.model.compositionCollection.length > 0){
                $scope.model.compositionCurrentResultStart = ($scope.model.compositionCurrentPage - 1) * $scope.model.compositionCurrentPageSize + 1;
                $scope.model.compositionCurrentResultLimit = ($scope.model.compositionCurrentPageSize * $scope.model.compositionCurrentPage);
                if($scope.model.compositionCollection.length < ($scope.model.compositionCurrentPageSize * $scope.model.compositionCurrentPage)){

                    $scope.model.compositionCurrentResultLimit = $scope.model.compositionCollection.length;
                }

                var totalPages = Math.ceil($scope.model.compositionCollection.length / $scope.model.compositionCurrentPageSize);
                $scope.model.compositionPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.compositionPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.compositionPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateCompositionForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedComposition.title_es != null &&
                        alfaNumericRegExpr.test($scope.model.selectedComposition.title_es)){
                        $scope.model.selectedComposition.url_slug_es = slugify($scope.model.selectedComposition.title_es);
                    }
                    else{
                        $scope.model.selectedComposition.url_slug_es = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedComposition.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedComposition.published_date = null;
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
            $scope.model.compositionCollection = [];
            $scope.model.compositionSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.compositionCurrentPageSize = 20;
            $scope.model.compositionCurrentPage = 1;
            $scope.model.compositionPagesCollection = [];
            $scope.model.compositionPagesCollection.push(1);
            $scope.model.compositionCurrentResultStart = 0;
            $scope.model.compositionCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allCompositionSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showCompositionForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedComposition = null;

            $scope.clearCompositionForm();
            compositionFact.loadInitialsData($scope, function(response){
                $scope.model.compositionCollection = response.data.initialsData.compositionDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showCompositionForm = response.data.initialsData.showCompositionForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsCompositionForm();

                if(showCompositionForm == true){
                        $scope.createComposition();
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
    angular.module('BncBackend.compositionController').controller('compositionCtrller',compositionCtrller);
})();
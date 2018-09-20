/*
 * File for handling controllers for Backend HistoricalMoments Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.historicalMomentsController', ['BncBackend.historicalMomentsFactory']);


    /* Controller for handling HistoricalMoments functions */
    function historicalMomentsCtrller($scope, $filter, historicalMomentsFact){

        /*
         * Global variables
         * 
         * */
        var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");
        var numericRegExpr = new RegExp("[0-9]{4}");
        var dateFilter = $filter("date");
        var dateRegExpress = new RegExp("[0-9]{2}/\[0-9]{2}/\[0-9]{4}");

        
        /*
         * Operations Functions
         * 
         * */
        /* clear errors of the form */
        $scope.clearErrorsHistoricalMomentsForm = function(){
            $scope.model.yearHasError = false;
            $scope.model.contentHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearHistoricalMomentsForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedHistoricalMoment = {};
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
        }
        
        /* create historicalMoments */
        $scope.createHistoricalMoments = function()
        {
            if($scope.model.canCreateHistoricalMoments == true)
            {
                $scope.model.createAction = true;
                $scope.clearHistoricalMomentsForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showHistoricalMomentsForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedHistoricalMoment.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedHistoricalMoment.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedHistoricalMoment.published_date;
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

        /* delete historicalMoments */
        $scope.deleteHistoricalMoments = function(historicalMoments_id)
        {
            var proceed = true;
            if(typeof historicalMoments_id == 'string' && !$scope.model.canDeleteHistoricalMoments){
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
                            var historicalMomentsIdCollection = [];
                            if(typeof historicalMoments_id == 'string'){
                                if($scope.model.historicalMomentsCollection.length > 0){
                                    for(var i=0; i<$scope.model.historicalMomentsCollection.length; i++){
                                        if($scope.model.historicalMomentsCollection[i].selected != undefined &&
                                            $scope.model.historicalMomentsCollection[i].selected == true)
                                        {
                                            historicalMomentsIdCollection.push($scope.model.historicalMomentsCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                historicalMomentsIdCollection.push(historicalMoments_id);
                            }
                            var data = {
                                historicalMomentsId: historicalMomentsIdCollection
                            };
                            historicalMomentsFact.deleteHistoricalMoments($scope, data);
                        }
                    });
            }
        }

        /* edit historicalMoments */
        $scope.editHistoricalMoments = function(historicalMoment)
        {
            $scope.model.createAction = false;
            $scope.clearHistoricalMomentsForm();
            $scope.clearHistoricalMomentsForm();
            $scope.model.selectedHistoricalMoment = historicalMoment;
            $scope.showHistoricalMomentsForm();
        }

        /* change the view mode of the historicalMoments data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.historicalMomentsCollection = [];
            $scope.model.activeView = option;
            $scope.getHistoricalMoments();
        }

        /* get the HistoricalMoments Collection */
        $scope.getHistoricalMoments = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showHistoricalMomentsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            searchParametersCollection.view = $scope.model.activeView;
            historicalMomentsFact.getHistoricalMomentsData($scope,searchParametersCollection, function(response){
                    $scope.model.historicalMomentsCollection = response.data.historicalMomentsDataCollection;
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
                $scope.model.canCreateHistoricalMoments = false;
                $scope.model.canEditHistoricalMoments = false;
                $scope.model.canDeleteHistoricalMoments = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateHistoricalMoments = true;
                $scope.model.canEditHistoricalMoments = false;
                $scope.model.canDeleteHistoricalMoments = false;
                $scope.model.allHistoricalMomentsSelected = false;
                $scope.model.selectedHistoricalMoment = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideHistoricalMomentsForm = function()
        {
            $scope.model.showHistoricalMomentsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getHistoricalMoments();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.historicalMomentsCurrentPage = 1;
            $scope.model.historicalMomentsPagesCollection = [];
            $scope.model.historicalMomentsPagesCollection.push(1);
            $scope.model.historicalMomentsCurrentResultStart = 0;
            $scope.model.historicalMomentsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save historicalMoments data */
        $scope.saveHistoricalMomentsData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsHistoricalMomentsForm();

                if($scope.model.selectedHistoricalMoment.year == null ||
                !numericRegExpr.test($scope.model.selectedHistoricalMoment.year) ||
                $scope.model.selectedHistoricalMoment.content_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedHistoricalMoment.content_es) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedHistoricalMoment.year == null ||
                    !numericRegExpr.test($scope.model.selectedHistoricalMoment.year)){
                        $scope.model.yearHasError = true;
                    }

                    if($scope.model.selectedHistoricalMoment.content_es == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedHistoricalMoment.content_es)){
                        $scope.model.contentHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedHistoricalMoment.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                     var historicalMomentsData = {historicalMomentData: $scope.model.selectedHistoricalMoment};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     historicalMomentsFact.saveHistoricalMomentsData($scope, historicalMomentsData, action,
                         function(response){
                             $scope.model.processingData = false;
                             $scope.toggleDataLoader();
                             if(response.data.success == 0){
                                 toastr.options.timeOut = 5000;
                                 toastr.error(response.data.message,"Error");
                             }
                             else{
                                 $scope.clearErrorsHistoricalMomentsForm();
                                 if(option == 'clear'){
                                     $scope.clearHistoricalMomentsForm();
                                 }
                                 else if(option == 'close'){
                                     $scope.clearHistoricalMomentsForm();
                                     $scope.hideHistoricalMomentsForm();
                                 }
                                 else if(option == 'stay'){
                                     $scope.model.createAction = false;
                                     $scope.model.selectedHistoricalMoment.id = response.data.historicalMomentId;
                                 }
                                 //toastr.options.timeOut = 3000;
                                 toastr.success(response.data.message,"¡Hecho!");
                             }

                             $scope.goToTop();
                         },
                         function(response){
                             $scope.model.processingData = true;
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

        /* search HistoricalMoments through Search Input Field */
        $scope.searchHistoricalMoments = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getHistoricalMoments();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getHistoricalMoments();
            }
        }

        /* selecting/deselecting all historicalMoments */
        $scope.selectAllHistoricalMoments = function(event){
            var canDeleteAll = true;
            $scope.model.allHistoricalMomentsSelected = !$scope.model.allHistoricalMomentsSelected;
            if(!$scope.model.allHistoricalMomentsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.historicalMomentsCollection.length; i++){
                $scope.model.historicalMomentsCollection[i].selected = $scope.model.allHistoricalMomentsSelected;
                if($scope.model.allHistoricalMomentsSelected == true && $scope.model.historicalMomentsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteHistoricalMoments = canDeleteAll;
        }

        /*selecting/deselecting historicalMoments */
        $scope.selectHistoricalMoments= function(event,historicalMoments){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalHistoricalMomentsSelected = 1;
            historicalMoments.selected = !historicalMoments.selected;
            if($scope.model.historicalMomentsCollection.length == 1){
                if(historicalMoments.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalHistoricalMomentsSelected = 0;
                }
                if(historicalMoments.canDelete == 0){
                    canDeleteAll = false;
                }
                if(historicalMoments.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.historicalMomentsCollection.length > 1){
                totalHistoricalMomentsSelected = 0;
                for(var i=0; i<$scope.model.historicalMomentsCollection.length; i++){
                    var historicalMoments = $scope.model.historicalMomentsCollection[i];
                    if(historicalMoments.selected == true){
                        totalHistoricalMomentsSelected++;
                        if(historicalMoments.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(historicalMoments.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalHistoricalMomentsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteHistoricalMoments = true;
                    if(totalHistoricalMomentsSelected == $scope.model.historicalMomentsCollection.length){
                        $scope.model.allHistoricalMomentsSelected = true;
                    }
                    else{
                        $scope.model.allHistoricalMomentsSelected = false;
                    }
                }
                if(totalHistoricalMomentsSelected == 1 && canEditAll == true){
                    $scope.model.canEditHistoricalMoments = true;
                }
                else{
                    $scope.model.canEditHistoricalMoments = false;
                }
            }
            else{
                $scope.model.canEditHistoricalMoments = false;
                $scope.model.canDeleteHistoricalMoments = false;
                $scope.model.allHistoricalMomentsSelected = false;
            }
        }

        /* show the form to Create/Edit HistoricalMoments */
        $scope.showHistoricalMomentsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showHistoricalMomentsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showHistoricalMomentsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    historicalMomentId : $scope.model.selectedHistoricalMoment.id
                };
                historicalMomentsFact.getHistoricalMomentsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedHistoricalMoment = response.data.historicalMomentData;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedHistoricalMoment.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
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


                if(suffix == 'historicalMoments-type'){
                    $('#historicalMoments-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.historicalMomentsCurrentResultStart = 0;
            $scope.model.historicalMomentsCurrentResultLimit = 0;
            $scope.model.historicalMomentsCurrentPage = ($scope.model.historicalMomentsCurrentPage*1);
            $scope.model.historicalMomentsCurrentPageSize = ($scope.model.historicalMomentsCurrentPageSize*1);

            if($scope.model.historicalMomentsCollection.length > 0){
                $scope.model.historicalMomentsCurrentResultStart = ($scope.model.historicalMomentsCurrentPage - 1) * $scope.model.historicalMomentsCurrentPageSize + 1;
                $scope.model.historicalMomentsCurrentResultLimit = ($scope.model.historicalMomentsCurrentPageSize * $scope.model.historicalMomentsCurrentPage);
                if($scope.model.historicalMomentsCollection.length < ($scope.model.historicalMomentsCurrentPageSize * $scope.model.historicalMomentsCurrentPage)){

                    $scope.model.historicalMomentsCurrentResultLimit = $scope.model.historicalMomentsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.historicalMomentsCollection.length / $scope.model.historicalMomentsCurrentPageSize);
                $scope.model.historicalMomentsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.historicalMomentsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.historicalMomentsPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateHistoricalMomentsForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedHistoricalMoment.title_es != null &&
                        alfaNumericRegExpr.test($scope.model.selectedHistoricalMoment.title_es)){
                        $scope.model.selectedHistoricalMoment.url_slug_es = slugify($scope.model.selectedHistoricalMoment.title_es);
                    }
                    else{
                        $scope.model.selectedHistoricalMoment.url_slug_es = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedHistoricalMoment.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedHistoricalMoment.published_date = null;
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
            $scope.model.historicalMomentsCollection = [];
            $scope.model.historicalMomentsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'timeline_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.historicalMomentsCurrentPageSize = 20;
            $scope.model.historicalMomentsCurrentPage = 1;
            $scope.model.historicalMomentsPagesCollection = [];
            $scope.model.historicalMomentsPagesCollection.push(1);
            $scope.model.historicalMomentsCurrentResultStart = 0;
            $scope.model.historicalMomentsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allHistoricalMomentsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showHistoricalMomentsForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedHistoricalMoment = null;

            $scope.clearHistoricalMomentsForm();
            historicalMomentsFact.loadInitialsData($scope, function(response){
                $scope.model.historicalMomentsCollection = response.data.initialsData.historicalMomentsDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showHistoricalMomentsForm = response.data.initialsData.showHistoricalMomentsForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsHistoricalMomentsForm();

                if(showHistoricalMomentsForm == true){
                        $scope.createHistoricalMoments();
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
    angular.module('BncBackend.historicalMomentsController').controller('historicalMomentsCtrller',historicalMomentsCtrller);
})();
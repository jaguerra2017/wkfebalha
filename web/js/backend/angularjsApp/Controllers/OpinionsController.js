/*
 * File for handling controllers for Backend Opinions Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.opinionsController', ['BncBackend.opinionsFactory']);


    /* Controller for handling Opinions functions */
    function opinionsCtrller($scope, $filter, $sce, opinionsFact){

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
        $scope.clearErrorsOpinionsForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.publishedDateHasError = false;
            $scope.model.contentHasError = false;
        }
        
        /* clear form values */
        $scope.clearOpinionsForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedOpinion = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create opinions */
        $scope.createOpinions = function()
        {
            if($scope.model.canCreateOpinions == true)
            {
                $scope.model.createAction = true;
                $scope.clearErrorsOpinionsForm();
                $scope.clearOpinionsForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showOpinionsForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedOpinion.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedOpinion.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedOpinion.published_date;
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

        /* delete opinions */
        $scope.deleteOpinions = function(opinions_id)
        {
            var proceed = true;
            if(typeof opinions_id == 'string' && !$scope.model.canDeleteOpinions){
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
                            var opinionsIdCollection = [];
                            if(typeof opinions_id == 'string'){
                                if($scope.model.opinionsCollection.length > 0){
                                    for(var i=0; i<$scope.model.opinionsCollection.length; i++){
                                        if($scope.model.opinionsCollection[i].selected != undefined &&
                                            $scope.model.opinionsCollection[i].selected == true)
                                        {
                                            opinionsIdCollection.push($scope.model.opinionsCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                opinionsIdCollection.push(opinions_id);
                            }
                            var data = {
                                opinionsId: opinionsIdCollection
                            };
                            opinionsFact.deleteOpinions($scope, data, function(response){
                                if(response.data.success == 0){
                                    toastr.options.timeOut = 5000;
                                    toastr.error(response.data.message,"Error");
                                }
                                else{
                                    toastr.success(response.data.message,"¡Hecho!");
                                }

                                $scope.getOpinions();
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
        }

        /* edit opinions */
        $scope.editOpinions = function(opinion)
        {
            $scope.model.createAction = false;
            $scope.clearOpinionsForm();
            $scope.model.selectedOpinion = opinion;
            $scope.showOpinionsForm();
        }

        /* change the view mode of the opinions data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.opinionsCollection = [];
            $scope.model.activeView = option;
            $scope.getOpinions();
        }

        /* get the Opinions Collection */
        $scope.getOpinions = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showOpinionsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            if($scope.model.selectedTaxonomiesCollection.length > 0){
                var taxonomieIdsCollection = [];
                for(var i=0; i<$scope.model.selectedTaxonomiesCollection.length; i++){
                    taxonomieIdsCollection.push($scope.model.selectedTaxonomiesCollection[i].id);
                }
                searchParametersCollection.searchByTaxonomies = true;
                searchParametersCollection.taxonomieIdsCollection = taxonomieIdsCollection;
            }
            opinionsFact.getOpinionsData($scope,searchParametersCollection, function(response){
                $scope.model.opinionsCollection = response.data.opinionsDataCollection;
                if($scope.model.opinionsCollection.length > 0){
                    for(var i=0; i<$scope.model.opinionsCollection.length; i++){
                        $scope.model.opinionsCollection[i].html_filtered_content_es = $sce.trustAsHtml($scope.model.opinionsCollection[i].content_es);
                    }
                }
                $scope.updatePaginationValues();
                $scope.toggleDataLoader();
            },
            function(response){
                $scope.updatePaginationValues();
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
                $scope.model.canCreateOpinions = false;
                $scope.model.canEditOpinions = false;
                $scope.model.canDeleteOpinions = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateOpinions = true;
                $scope.model.canEditOpinions = false;
                $scope.model.canDeleteOpinions = false;
                $scope.model.allOpinionsSelected = false;
                $scope.model.selectedOpinion = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideOpinionsForm = function()
        {
            $scope.model.showOpinionsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getOpinions();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.opinionsCurrentPage = 1;
            $scope.model.opinionsPagesCollection = [];
            $scope.model.opinionsPagesCollection.push(1);
            $scope.model.opinionsCurrentResultStart = 0;
            $scope.model.opinionsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save opinions data */
        $scope.saveOpinionsData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsOpinionsForm();

                $scope.model.selectedOpinion.content_es = $('#textEditor').code();

                if($scope.model.selectedOpinion.title_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedOpinion.title_es) ||
                $scope.model.selectedOpinion.content_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedOpinion.content_es) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedOpinion.title_es == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedOpinion.title_es)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedOpinion.content_es == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedOpinion.content_es)){
                        $scope.model.contentHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){

                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedOpinion.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedOpinion.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedOpinion.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     var opinionsData = {opinionData: $scope.model.selectedOpinion};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     opinionsFact.saveOpinionsData($scope, opinionsData, action, function(response){
                         $scope.model.processingData = false;
                         $scope.toggleDataLoader();
                         if(response.data.success == 0){
                             toastr.options.timeOut = 5000;
                             toastr.error(response.data.message,"Error");
                         }
                         else{
                             $scope.clearErrorsOpinionsForm();
                             if(option == 'clear'){
                                 $scope.clearOpinionsForm();
                             }
                             else if(option == 'close'){
                                 $scope.clearOpinionsForm();
                                 $scope.hideOpinionsForm();
                             }
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

        /* search Opinions through Search Input Field */
        $scope.searchOpinions = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getOpinions();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getOpinions();
            }
        }

        /* selecting/deselecting all opinions */
        $scope.selectAllOpinions = function(event){
            var canDeleteAll = true;
            $scope.model.allOpinionsSelected = !$scope.model.allOpinionsSelected;
            if(!$scope.model.allOpinionsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.opinionsCollection.length; i++){
                $scope.model.opinionsCollection[i].selected = $scope.model.allOpinionsSelected;
                if($scope.model.allOpinionsSelected == true && $scope.model.opinionsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteOpinions = canDeleteAll;
        }

        /*selecting/deselecting opinions */
        $scope.selectOpinions= function(event,opinions){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalOpinionsSelected = 1;
            opinions.selected = !opinions.selected;
            if($scope.model.opinionsCollection.length == 1){
                if(opinions.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalOpinionsSelected = 0;
                }
                if(opinions.canDelete == 0){
                    canDeleteAll = false;
                }
                if(opinions.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.opinionsCollection.length > 1){
                totalOpinionsSelected = 0;
                for(var i=0; i<$scope.model.opinionsCollection.length; i++){
                    var opinions = $scope.model.opinionsCollection[i];
                    if(opinions.selected == true){
                        totalOpinionsSelected++;
                        if(opinions.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(opinions.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalOpinionsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteOpinions = true;
                    if(totalOpinionsSelected == $scope.model.opinionsCollection.length){
                        $scope.model.allOpinionsSelected = true;
                    }
                    else{
                        $scope.model.allOpinionsSelected = false;
                    }
                }
                if(totalOpinionsSelected == 1 && canEditAll == true){
                    $scope.model.canEditOpinions = true;
                }
                else{
                    $scope.model.canEditOpinions = false;
                }
            }
            else{
                $scope.model.canEditOpinions = false;
                $scope.model.canDeleteOpinions = false;
                $scope.model.allOpinionsSelected = false;
            }
        }

        /* show the form to Create/Edit Opinions */
        $scope.showOpinionsForm = function()
        {
            $scope.handleCrudOperations('disable');

            if($scope.model.createAction == false){
                $scope.model.selectedCategoriesCollection = $scope.model.selectedOpinion.categoriesCollection;
                if($scope.model.postStatusCollection.length > 0){
                    for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                        if($scope.model.postStatusCollection[i].id == $scope.model.selectedOpinion.post_status_id){
                            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                        }
                    }
                }
                $('#textEditor').code($scope.model.selectedOpinion.content_es);
            }
            $scope.model.showOpinionsForm = true;
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


                if(suffix == 'opinions-type'){
                    $('#opinions-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.opinionsCurrentResultStart = 0;
            $scope.model.opinionsCurrentResultLimit = 0;
            $scope.model.opinionsCurrentPage = ($scope.model.opinionsCurrentPage*1);
            $scope.model.opinionsCurrentPageSize = ($scope.model.opinionsCurrentPageSize*1);

            if($scope.model.opinionsCollection.length > 0){
                $scope.model.opinionsCurrentResultStart = ($scope.model.opinionsCurrentPage - 1) * $scope.model.opinionsCurrentPageSize + 1;
                $scope.model.opinionsCurrentResultLimit = ($scope.model.opinionsCurrentPageSize * $scope.model.opinionsCurrentPage);
                if($scope.model.opinionsCollection.length < ($scope.model.opinionsCurrentPageSize * $scope.model.opinionsCurrentPage)){

                    $scope.model.opinionsCurrentResultLimit = $scope.model.opinionsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.opinionsCollection.length / $scope.model.opinionsCurrentPageSize);
                $scope.model.opinionsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.opinionsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.opinionsPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateOpinionsForm = function(event, field, element)
        {
            switch(field){
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedOpinion.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedOpinion.published_date = null;
                            $scope.model.publishedDateHasError = false;
                        }
                    break;
            }
        }

        /*update form views section*/
        $scope.updateFormSection = function(section){
            $scope.model.formActiveView = section;
        }

        /*watching changes on Filtered Taxonomies Collection*/
        $scope.$watch('model.selectedTaxonomiesCollection', function(newValue, oldValue) {
            if(newValue != undefined && newValue != null){
                $scope.getOpinions();
            }
        });


        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.model.opinionsCollection = [];
            $scope.model.opinionsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'chat';
            $scope.model.selectedTaxonomiesCollection = [];
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.opinionsCurrentPageSize = 20;
            $scope.model.opinionsCurrentPage = 1;
            $scope.model.opinionsPagesCollection = [];
            $scope.model.opinionsPagesCollection.push(1);
            $scope.model.opinionsCurrentResultStart = 0;
            $scope.model.opinionsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.formActiveView = 'general-info';
            $scope.model.allOpinionsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showOpinionsForm = false;
            $scope.model.processingData = false;
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedOpinion = null;

            $scope.clearOpinionsForm();
            opinionsFact.loadInitialsData($scope, function(response){
                $scope.model.opinionsCollection = response.data.initialsData.opinionsDataCollection;
                if($scope.model.opinionsCollection.length > 0){
                    for(var i=0; i<$scope.model.opinionsCollection.length; i++){
                        $scope.model.opinionsCollection[i].html_filtered_content_es = $sce.trustAsHtml($scope.model.opinionsCollection[i].content_es);
                    }
                }

                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                var showOpinionsForm = response.data.initialsData.showOpinionsForm;

                $scope.updatePaginationValues();

                if(showOpinionsForm == true){
                    $scope.createOpinions();
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
    angular.module('BncBackend.opinionsController').controller('opinionsCtrller',opinionsCtrller);
})();
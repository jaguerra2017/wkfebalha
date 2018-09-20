/*
 * File for handling controllers for Backend Awards Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.awardsController', ['BncBackend.awardsFactory']);


    /* Controller for handling Awards functions */
    function awardsCtrller($scope, $filter, awardsFact) {

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
        $scope.clearErrorsAwardsForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearAwardsForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedAward = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create awards */
        $scope.createAwards = function()
        {
            if($scope.model.canCreateAwards == true)
            {
                $scope.model.createAction = true;
                $scope.clearAwardsForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showAwardsForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedAward.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedAward.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedAward.published_date;
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

        /* delete awards */
        $scope.deleteAwards = function(awards_id)
        {
            var proceed = true;
            if(typeof awards_id == 'string' && !$scope.model.canDeleteAwards){
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
                            var awardsIdCollection = [];
                            if(typeof awards_id == 'string'){
                                if($scope.model.awardsCollection.length > 0){
                                    for(var i=0; i<$scope.model.awardsCollection.length; i++){
                                        if($scope.model.awardsCollection[i].selected != undefined &&
                                            $scope.model.awardsCollection[i].selected == true)
                                        {
                                            awardsIdCollection.push($scope.model.awardsCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                awardsIdCollection.push(awards_id);
                            }
                            var data = {
                                awardsId: awardsIdCollection
                            };
                            awardsFact.deleteAwards($scope, data);
                        }
                    });
            }
        }

        /* edit awards */
        $scope.editAwards = function(award)
        {
            $scope.model.createAction = false;
            $scope.clearAwardsForm();
            $scope.clearAwardsForm();
            $scope.model.selectedAward = award;
            $scope.showAwardsForm();
        }

        /* change the view mode of the awards data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.awardsCollection = [];
            $scope.model.activeView = option;
            $scope.getAwards();
        }

        /* get the Awards Collection */
        $scope.getAwards = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showAwardsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            awardsFact.getAwardsData($scope,searchParametersCollection, function(response){
                    $scope.model.awardsCollection = response.data.awardsDataCollection;
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
                $scope.model.canCreateAwards = false;
                $scope.model.canEditAwards = false;
                $scope.model.canDeleteAwards = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateAwards = true;
                $scope.model.canEditAwards = false;
                $scope.model.canDeleteAwards = false;
                $scope.model.allAwardsSelected = false;
                $scope.model.selectedAward = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideAwardsForm = function()
        {
            $scope.model.showAwardsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getAwards();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.awardsCurrentPage = 1;
            $scope.model.awardsPagesCollection = [];
            $scope.model.awardsPagesCollection.push(1);
            $scope.model.awardsCurrentResultStart = 0;
            $scope.model.awardsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save awards data */
        $scope.saveAwardsData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsAwardsForm();

                if($scope.model.selectedAward.title_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedAward.title_es) ||
                $scope.model.selectedAward.url_slug_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedAward.url_slug_es) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedAward.title_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedAward.title_es)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedAward.url_slug_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedAward.url_slug_es)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedAward.content_es = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedAward.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedAward.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedAward.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedAward.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     var awardsData = {awardData: $scope.model.selectedAward};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     awardsFact.saveAwardsData($scope, awardsData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Awards through Search Input Field */
        $scope.searchAwards = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getAwards();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getAwards();
            }
        }

        /* selecting/deselecting all awards */
        $scope.selectAllAwards = function(event){
            var canDeleteAll = true;
            $scope.model.allAwardsSelected = !$scope.model.allAwardsSelected;
            if(!$scope.model.allAwardsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.awardsCollection.length; i++){
                $scope.model.awardsCollection[i].selected = $scope.model.allAwardsSelected;
                if($scope.model.allAwardsSelected == true && $scope.model.awardsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteAwards = canDeleteAll;
        }

        /*selecting/deselecting awards */
        $scope.selectAwards= function(event,awards){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalAwardsSelected = 1;
            awards.selected = !awards.selected;
            if($scope.model.awardsCollection.length == 1){
                if(awards.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalAwardsSelected = 0;
                }
                if(awards.canDelete == 0){
                    canDeleteAll = false;
                }
                if(awards.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.awardsCollection.length > 1){
                totalAwardsSelected = 0;
                for(var i=0; i<$scope.model.awardsCollection.length; i++){
                    var awards = $scope.model.awardsCollection[i];
                    if(awards.selected == true){
                        totalAwardsSelected++;
                        if(awards.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(awards.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalAwardsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteAwards = true;
                    if(totalAwardsSelected == $scope.model.awardsCollection.length){
                        $scope.model.allAwardsSelected = true;
                    }
                    else{
                        $scope.model.allAwardsSelected = false;
                    }
                }
                if(totalAwardsSelected == 1 && canEditAll == true){
                    $scope.model.canEditAwards = true;
                }
                else{
                    $scope.model.canEditAwards = false;
                }
            }
            else{
                $scope.model.canEditAwards = false;
                $scope.model.canDeleteAwards = false;
                $scope.model.allAwardsSelected = false;
            }
        }

        /* show the form to Create/Edit Awards */
        $scope.showAwardsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showAwardsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showAwardsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    awardId : $scope.model.selectedAward.id
                };
                awardsFact.getAwardsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedAward = response.data.awardData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedAward.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedAward.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedAward.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedAward.featured_image_url,
                            id : $scope.model.selectedAward.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedAward.content_es);

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


                if(suffix == 'awards-type'){
                    $('#awards-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.awardsCurrentResultStart = 0;
            $scope.model.awardsCurrentResultLimit = 0;
            $scope.model.awardsCurrentPage = ($scope.model.awardsCurrentPage*1);
            $scope.model.awardsCurrentPageSize = ($scope.model.awardsCurrentPageSize*1);

            if($scope.model.awardsCollection.length > 0){
                $scope.model.awardsCurrentResultStart = ($scope.model.awardsCurrentPage - 1) * $scope.model.awardsCurrentPageSize + 1;
                $scope.model.awardsCurrentResultLimit = ($scope.model.awardsCurrentPageSize * $scope.model.awardsCurrentPage);
                if($scope.model.awardsCollection.length < ($scope.model.awardsCurrentPageSize * $scope.model.awardsCurrentPage)){

                    $scope.model.awardsCurrentResultLimit = $scope.model.awardsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.awardsCollection.length / $scope.model.awardsCurrentPageSize);
                $scope.model.awardsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.awardsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.awardsPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateAwardsForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedAward.title_es != null &&
                        alfaNumericRegExpr.test($scope.model.selectedAward.title_es)){
                        $scope.model.selectedAward.url_slug_es = slugify($scope.model.selectedAward.title_es);
                    }
                    else{
                        $scope.model.selectedAward.url_slug_es = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedAward.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedAward.published_date = null;
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
            $scope.model.awardsCollection = [];
            $scope.model.awardsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.awardsCurrentPageSize = 20;
            $scope.model.awardsCurrentPage = 1;
            $scope.model.awardsPagesCollection = [];
            $scope.model.awardsPagesCollection.push(1);
            $scope.model.awardsCurrentResultStart = 0;
            $scope.model.awardsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allAwardsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showAwardsForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedAward = null;

            $scope.clearAwardsForm();
            awardsFact.loadInitialsData($scope, function(response){
                $scope.model.awardsCollection = response.data.initialsData.awardsDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showAwardsForm = response.data.initialsData.showAwardsForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsAwardsForm();

                if(showAwardsForm == true){
                        $scope.createAwards();
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
    angular.module('BncBackend.awardsController').controller('awardsCtrller',awardsCtrller);
})();
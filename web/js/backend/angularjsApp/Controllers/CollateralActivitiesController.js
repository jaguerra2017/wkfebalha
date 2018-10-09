/*
 * File for handling controllers for Backend CollateralActivities Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.collateralactivitiesController', ['BncBackend.collateralactivitiesFactory']);


    /* Controller for handling CollateralActivities functions */
    function collateralactivitiesCtrller($scope, $filter, collateralactivitiesFact){

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
        $scope.clearErrorsCollateralActivitiesForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearCollateralActivitiesForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedCollateralActivitie = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create collateralactivities */
        $scope.createCollateralActivities = function()
        {
            if($scope.model.canCreateCollateralActivities == true)
            {
                $scope.model.createAction = true;
                $scope.clearCollateralActivitiesForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showCollateralActivitiesForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedCollateralActivitie.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedCollateralActivitie.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedCollateralActivitie.published_date;
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

        /* delete collateralactivities */
        $scope.deleteCollateralActivities = function(collateralactivities_id)
        {
            var proceed = true;
            if(typeof collateralactivities_id == 'string' && !$scope.model.canDeleteCollateralActivities){
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
                            var collateralactivitiesIdCollection = [];
                            if(typeof collateralactivities_id == 'string'){
                                if($scope.model.collateralactivitiesCollection.length > 0){
                                    for(var i=0; i<$scope.model.collateralactivitiesCollection.length; i++){
                                        if($scope.model.collateralactivitiesCollection[i].selected != undefined &&
                                            $scope.model.collateralactivitiesCollection[i].selected == true)
                                        {
                                            collateralactivitiesIdCollection.push($scope.model.collateralactivitiesCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                collateralactivitiesIdCollection.push(collateralactivities_id);
                            }
                            var data = {
                                collateralactivitiesId: collateralactivitiesIdCollection
                            };
                            collateralactivitiesFact.deleteCollateralActivities($scope, data);
                        }
                    });
            }
        }

        /* edit collateralactivities */
        $scope.editCollateralActivities = function(collateralactivitie)
        {
            $scope.model.createAction = false;
            $scope.clearCollateralActivitiesForm();
            $scope.clearCollateralActivitiesForm();
            $scope.model.selectedCollateralActivitie = collateralactivitie;
            $scope.showCollateralActivitiesForm();
        }

        /* change the view mode of the collateralactivities data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.collateralactivitiesCollection = [];
            $scope.model.activeView = option;
            $scope.getCollateralActivities();
        }

        /* get the CollateralActivities Collection */
        $scope.getCollateralActivities = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showCollateralActivitiesForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            collateralactivitiesFact.getCollateralActivitiesData($scope,searchParametersCollection, function(response){
                    $scope.model.collateralactivitiesCollection = response.data.collateralactivitiesDataCollection;
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
                $scope.model.canCreateCollateralActivities = false;
                $scope.model.canEditCollateralActivities = false;
                $scope.model.canDeleteCollateralActivities = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateCollateralActivities = true;
                $scope.model.canEditCollateralActivities = false;
                $scope.model.canDeleteCollateralActivities = false;
                $scope.model.allCollateralActivitiesSelected = false;
                $scope.model.selectedCollateralActivitie = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideCollateralActivitiesForm = function()
        {
            $scope.model.showCollateralActivitiesForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getCollateralActivities();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.collateralactivitiesCurrentPage = 1;
            $scope.model.collateralactivitiesPagesCollection = [];
            $scope.model.collateralactivitiesPagesCollection.push(1);
            $scope.model.collateralactivitiesCurrentResultStart = 0;
            $scope.model.collateralactivitiesCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save collateralactivities data */
        $scope.saveCollateralActivitiesData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.selectedCollateralActivitie.online_sale = $("#online_sale").is(":checked");
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsCollateralActivitiesForm();

                if($scope.model.selectedCollateralActivitie.title == null ||
                !alfaNumericRegExpr.test($scope.model.selectedCollateralActivitie.title) ||
                $scope.model.selectedCollateralActivitie.url_slug == null ||
                !alfaNumericRegExpr.test($scope.model.selectedCollateralActivitie.url_slug) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedCollateralActivitie.title == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedCollateralActivitie.title)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedCollateralActivitie.url_slug == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedCollateralActivitie.url_slug)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedCollateralActivitie.content = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedCollateralActivitie.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedCollateralActivitie.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedCollateralActivitie.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedCollateralActivitie.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     $scope.model.selectedCollateralActivitie.currentLanguage = $scope.model.selectedLanguage.value;
                     var collateralactivitiesData = {collateralactivitieData: $scope.model.selectedCollateralActivitie};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     collateralactivitiesFact.saveCollateralActivitiesData($scope, collateralactivitiesData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search CollateralActivities through Search Input Field */
        $scope.searchCollateralActivities = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getCollateralActivities();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getCollateralActivities();
            }
        }

        /* selecting/deselecting all collateralactivities */
        $scope.selectAllCollateralActivities = function(event){
            var canDeleteAll = true;
            $scope.model.allCollateralActivitiesSelected = !$scope.model.allCollateralActivitiesSelected;
            if(!$scope.model.allCollateralActivitiesSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.collateralactivitiesCollection.length; i++){
                $scope.model.collateralactivitiesCollection[i].selected = $scope.model.allCollateralActivitiesSelected;
                if($scope.model.allCollateralActivitiesSelected == true && $scope.model.collateralactivitiesCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteCollateralActivities = canDeleteAll;
        }

        /*selecting/deselecting collateralactivities */
        $scope.selectCollateralActivities= function(event,collateralactivities){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalCollateralActivitiesSelected = 1;
            collateralactivities.selected = !collateralactivities.selected;
            if($scope.model.collateralactivitiesCollection.length == 1){
                if(collateralactivities.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalCollateralActivitiesSelected = 0;
                }
                if(collateralactivities.canDelete == 0){
                    canDeleteAll = false;
                }
                if(collateralactivities.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.collateralactivitiesCollection.length > 1){
                totalCollateralActivitiesSelected = 0;
                for(var i=0; i<$scope.model.collateralactivitiesCollection.length; i++){
                    var collateralactivities = $scope.model.collateralactivitiesCollection[i];
                    if(collateralactivities.selected == true){
                        totalCollateralActivitiesSelected++;
                        if(collateralactivities.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(collateralactivities.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalCollateralActivitiesSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteCollateralActivities = true;
                    if(totalCollateralActivitiesSelected == $scope.model.collateralactivitiesCollection.length){
                        $scope.model.allCollateralActivitiesSelected = true;
                    }
                    else{
                        $scope.model.allCollateralActivitiesSelected = false;
                    }
                }
                if(totalCollateralActivitiesSelected == 1 && canEditAll == true){
                    $scope.model.canEditCollateralActivities = true;
                }
                else{
                    $scope.model.canEditCollateralActivities = false;
                }
            }
            else{
                $scope.model.canEditCollateralActivities = false;
                $scope.model.canDeleteCollateralActivities = false;
                $scope.model.allCollateralActivitiesSelected = false;
            }
        }

        /* show the form to Create/Edit CollateralActivities */
        $scope.showCollateralActivitiesForm = function()
        {
            $scope.handleCrudOperations('disable');
          $("#online_sale").bootstrapSwitch('state', $scope.model.selectedCollateralActivitie.online_sale);
            if($scope.model.createAction){
                $scope.model.showCollateralActivitiesForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showCollateralActivitiesForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    collateralactivitieId : $scope.model.selectedCollateralActivitie.id
                };
                searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
                collateralactivitiesFact.getCollateralActivitiesData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedCollateralActivitie = response.data.collateralactivitieData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedCollateralActivitie.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedCollateralActivitie.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedCollateralActivitie.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedCollateralActivitie.featured_image_url,
                            id : $scope.model.selectedCollateralActivitie.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedCollateralActivitie.content);

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


                if(suffix == 'collateralactivities-type'){
                    $('#collateralactivities-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.collateralactivitiesCurrentResultStart = 0;
            $scope.model.collateralactivitiesCurrentResultLimit = 0;
            $scope.model.collateralactivitiesCurrentPage = ($scope.model.collateralactivitiesCurrentPage*1);
            $scope.model.collateralactivitiesCurrentPageSize = ($scope.model.collateralactivitiesCurrentPageSize*1);

            if($scope.model.collateralactivitiesCollection.length > 0){
                $scope.model.collateralactivitiesCurrentResultStart = ($scope.model.collateralactivitiesCurrentPage - 1) * $scope.model.collateralactivitiesCurrentPageSize + 1;
                $scope.model.collateralactivitiesCurrentResultLimit = ($scope.model.collateralactivitiesCurrentPageSize * $scope.model.collateralactivitiesCurrentPage);
                if($scope.model.collateralactivitiesCollection.length < ($scope.model.collateralactivitiesCurrentPageSize * $scope.model.collateralactivitiesCurrentPage)){

                    $scope.model.collateralactivitiesCurrentResultLimit = $scope.model.collateralactivitiesCollection.length;
                }

                var totalPages = Math.ceil($scope.model.collateralactivitiesCollection.length / $scope.model.collateralactivitiesCurrentPageSize);
                $scope.model.collateralactivitiesPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.collateralactivitiesPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.collateralactivitiesPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateCollateralActivitiesForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedCollateralActivitie.title != null &&
                        alfaNumericRegExpr.test($scope.model.selectedCollateralActivitie.title)){
                        $scope.model.selectedCollateralActivitie.url_slug = slugify($scope.model.selectedCollateralActivitie.title);
                    }
                    else{
                        $scope.model.selectedCollateralActivitie.url_slug = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedCollateralActivitie.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedCollateralActivitie.published_date = null;
                            $scope.model.publishedDateHasError = false;
                        }
                    break;
            }
        }

        $scope.changeLoadDataOrSavedInfoByLanguage = function (event, from, language) {
            $scope.model.selectedLanguage = language;
            switch (from) {
              case 'list':
                $scope.model.collateralactivitiesCollection = [];
                $scope.getCollateralActivities()
                  break;
              case 'form':
                $scope.showCollateralActivitiesForm();
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
            $scope.model.collateralactivitiesCollection = [];
            $scope.model.collateralactivitiesSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.collateralactivitiesCurrentPageSize = 20;
            $scope.model.collateralactivitiesCurrentPage = 1;
            $scope.model.collateralactivitiesPagesCollection = [];
            $scope.model.collateralactivitiesPagesCollection.push(1);
            $scope.model.collateralactivitiesCurrentResultStart = 0;
            $scope.model.collateralactivitiesCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allCollateralActivitiesSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showCollateralActivitiesForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedCollateralActivitie = null;

            $scope.clearCollateralActivitiesForm();
            collateralactivitiesFact.loadInitialsData($scope, function(response){
                $scope.model.collateralactivitiesCollection = response.data.initialsData.collateralactivitiesDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                /* definiendo lenguajes */
                $scope.model.languages = response.data.initialsData.languages;
                $scope.model.selectedLanguage = $scope.model.languages[0];
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showCollateralActivitiesForm = response.data.initialsData.showCollateralActivitiesForm;
                    $scope.updatePaginationValues();
                    $scope.clearErrorsCollateralActivitiesForm();

                if(showCollateralActivitiesForm == true){
                        $scope.createCollateralActivities();
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
    angular.module('BncBackend.collateralactivitiesController').controller('collateralactivitiesCtrller',collateralactivitiesCtrller);
})();
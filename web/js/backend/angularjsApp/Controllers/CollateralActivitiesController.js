/*
 * File for handling controllers for Backend Collateral Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.collateralController', ['BncBackend.collateralFactory']);


    /* Controller for handling Collateral functions */
    function collateralCtrller($scope, $filter, collateralFact){

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
        $scope.clearErrorsCollateralForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearCollateralForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedCollateral = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create collateral */
        $scope.createCollateral = function()
        {
            if($scope.model.canCreateCollateral == true)
            {
                $scope.model.createAction = true;
                $scope.clearCollateralForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showCollateralForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedCollateral.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedCollateral.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedCollateral.published_date;
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

        /* delete collateral */
        $scope.deleteCollateral = function(collateral_id)
        {
            var proceed = true;
            if(typeof collateral_id == 'string' && !$scope.model.canDeleteCollateral){
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
                            var collateralIdCollection = [];
                            if(typeof collateral_id == 'string'){
                                if($scope.model.collateralCollection.length > 0){
                                    for(var i=0; i<$scope.model.collateralCollection.length; i++){
                                        if($scope.model.collateralCollection[i].selected != undefined &&
                                            $scope.model.collateralCollection[i].selected == true)
                                        {
                                            collateralIdCollection.push($scope.model.collateralCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                collateralIdCollection.push(collateral_id);
                            }
                            var data = {
                                collateralId: collateralIdCollection
                            };
                            collateralFact.deleteCollateral($scope, data);
                        }
                    });
            }
        }

        /* edit collateral */
        $scope.editCollateral = function(collateral)
        {
            $scope.model.createAction = false;
            $scope.clearCollateralForm();
            $scope.clearCollateralForm();
            $scope.model.selectedCollateral = collateral;
            $scope.showCollateralForm();
        }

        /* change the view mode of the collateral data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.collateralCollection = [];
            $scope.model.activeView = option;
            $scope.getCollateral();
        }

        /* get the Collateral Collection */
        $scope.getCollateral = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showCollateralForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }

            collateralFact.getCollateralData($scope,searchParametersCollection, function(response){
                    $scope.model.collateralCollection = response.data.collateralDataCollection;
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
                $scope.model.canCreateCollateral = false;
                $scope.model.canEditCollateral = false;
                $scope.model.canDeleteCollateral = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateCollateral = true;
                $scope.model.canEditCollateral = false;
                $scope.model.canDeleteCollateral = false;
                $scope.model.allCollateralSelected = false;
                $scope.model.selectedCollateral = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideCollateralForm = function()
        {
            $scope.model.showCollateralForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getCollateral();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.collateralCurrentPage = 1;
            $scope.model.collateralPagesCollection = [];
            $scope.model.collateralPagesCollection.push(1);
            $scope.model.collateralCurrentResultStart = 0;
            $scope.model.collateralCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save collateral data */
        $scope.saveCollateralData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.selectedCollateral.online_sale = $("#online_sale").is(":checked");
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsCollateralForm();

                if($scope.model.selectedCollateral.title == null ||
                !alfaNumericRegExpr.test($scope.model.selectedCollateral.title) ||
                $scope.model.selectedCollateral.url_slug == null ||
                !alfaNumericRegExpr.test($scope.model.selectedCollateral.url_slug) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedCollateral.title == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedCollateral.title)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedCollateral.url_slug == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedCollateral.url_slug)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedCollateral.content = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedCollateral.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedCollateral.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedCollateral.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedCollateral.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     $scope.model.selectedCollateral.currentLanguage = $scope.model.selectedLanguage.value;
                     var collateralData = {collateralData: $scope.model.selectedCollateral};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     collateralFact.saveCollateralData($scope, collateralData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Collateral through Search Input Field */
        $scope.searchCollateral = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getCollateral();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getCollateral();
            }
        }

        /* selecting/deselecting all collateral */
        $scope.selectAllCollateral = function(event){
            var canDeleteAll = true;
            $scope.model.allCollateralSelected = !$scope.model.allCollateralSelected;
            if(!$scope.model.allCollateralSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.collateralCollection.length; i++){
                $scope.model.collateralCollection[i].selected = $scope.model.allCollateralSelected;
                if($scope.model.allCollateralSelected == true && $scope.model.collateralCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteCollateral = canDeleteAll;
        }

        /*selecting/deselecting collateral */
        $scope.selectCollateral= function(event,collateral){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalCollateralSelected = 1;
            collateral.selected = !collateral.selected;
            if($scope.model.collateralCollection.length == 1){
                if(collateral.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalCollateralSelected = 0;
                }
                if(collateral.canDelete == 0){
                    canDeleteAll = false;
                }
                if(collateral.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.collateralCollection.length > 1){
                totalCollateralSelected = 0;
                for(var i=0; i<$scope.model.collateralCollection.length; i++){
                    var collateral = $scope.model.collateralCollection[i];
                    if(collateral.selected == true){
                        totalCollateralSelected++;
                        if(collateral.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(collateral.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalCollateralSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteCollateral = true;
                    if(totalCollateralSelected == $scope.model.collateralCollection.length){
                        $scope.model.allCollateralSelected = true;
                    }
                    else{
                        $scope.model.allCollateralSelected = false;
                    }
                }
                if(totalCollateralSelected == 1 && canEditAll == true){
                    $scope.model.canEditCollateral = true;
                }
                else{
                    $scope.model.canEditCollateral = false;
                }
            }
            else{
                $scope.model.canEditCollateral = false;
                $scope.model.canDeleteCollateral = false;
                $scope.model.allCollateralSelected = false;
            }
        }

        /* show the form to Create/Edit Collateral */
        $scope.showCollateralForm = function()
        {
            $scope.handleCrudOperations('disable');
          $("#online_sale").bootstrapSwitch('state', $scope.model.selectedCollateral.online_sale);
            if($scope.model.createAction){
                $scope.model.showCollateralForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showCollateralForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    collateralId : $scope.model.selectedCollateral.id
                };
                searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
                collateralFact.getCollateralData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedCollateral = response.data.collateralData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedCollateral.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedCollateral.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedCollateral.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedCollateral.featured_image_url,
                            id : $scope.model.selectedCollateral.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedCollateral.content);

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


                if(suffix == 'collateral-type'){
                    $('#collateral-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.collateralCurrentResultStart = 0;
            $scope.model.collateralCurrentResultLimit = 0;
            $scope.model.collateralCurrentPage = ($scope.model.collateralCurrentPage*1);
            $scope.model.collateralCurrentPageSize = ($scope.model.collateralCurrentPageSize*1);

            if($scope.model.collateralCollection.length > 0){
                $scope.model.collateralCurrentResultStart = ($scope.model.collateralCurrentPage - 1) * $scope.model.collateralCurrentPageSize + 1;
                $scope.model.collateralCurrentResultLimit = ($scope.model.collateralCurrentPageSize * $scope.model.collateralCurrentPage);
                if($scope.model.collateralCollection.length < ($scope.model.collateralCurrentPageSize * $scope.model.collateralCurrentPage)){

                    $scope.model.collateralCurrentResultLimit = $scope.model.collateralCollection.length;
                }

                var totalPages = Math.ceil($scope.model.collateralCollection.length / $scope.model.collateralCurrentPageSize);
                $scope.model.collateralPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.collateralPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.collateralPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateCollateralForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedCollateral.title != null &&
                        alfaNumericRegExpr.test($scope.model.selectedCollateral.title)){
                        $scope.model.selectedCollateral.url_slug = slugify($scope.model.selectedCollateral.title);
                    }
                    else{
                        $scope.model.selectedCollateral.url_slug = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedCollateral.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedCollateral.published_date = null;
                            $scope.model.publishedDateHasError = false;
                        }
                    break;
            }
        }

        $scope.changeLoadDataOrSavedInfoByLanguage = function (event, from, language) {
            $scope.model.selectedLanguage = language;
            switch (from) {
              case 'list':
                $scope.model.collateralCollection = [];
                $scope.getCollateral();
                  break;
              case 'form':
                $scope.showCollateralForm();
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
            $scope.model.collateralCollection = [];
            $scope.model.collateralSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.collateralCurrentPageSize = 20;
            $scope.model.collateralCurrentPage = 1;
            $scope.model.collateralPagesCollection = [];
            $scope.model.collateralPagesCollection.push(1);
            $scope.model.collateralCurrentResultStart = 0;
            $scope.model.collateralCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allCollateralSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showCollateralForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedCollateral = null;

            $scope.clearCollateralForm();
            collateralFact.loadInitialsData($scope, function(response){
                $scope.model.collateralCollection = response.data.initialsData.collateralDataCollection;
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
                var showCollateralForm = response.data.initialsData.showCollateralForm;
                    $scope.updatePaginationValues();
                    $scope.clearErrorsCollateralForm();

                console.log(showCollateralForm);
                if(showCollateralForm == true){
                        $scope.createCollateral();
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
    angular.module('BncBackend.collateralController').controller('collateralCtrller',collateralCtrller);
})();
/*
 * File for handling controllers for Backend HeadQuarters Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.headquartersController', ['BncBackend.headquartersFactory']);


    /* Controller for handling HeadQuarters functions */
    function headquartersCtrller($scope, $filter, headquartersFact){

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
        $scope.clearErrorsHeadQuartersForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearHeadQuartersForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedHeadQuarter = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create headquarters */
        $scope.createHeadQuarters = function()
        {
            if($scope.model.canCreateHeadQuarters == true)
            {
                $scope.model.createAction = true;
                $scope.clearHeadQuartersForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showHeadQuartersForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedHeadQuarter.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedHeadQuarter.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedHeadQuarter.published_date;
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

        /* delete headquarters */
        $scope.deleteHeadQuarters = function(headquarters_id)
        {
            var proceed = true;
            if(typeof headquarters_id == 'string' && !$scope.model.canDeleteHeadQuarters){
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
                            var headquartersIdCollection = [];
                            if(typeof headquarters_id == 'string'){
                                if($scope.model.headquartersCollection.length > 0){
                                    for(var i=0; i<$scope.model.headquartersCollection.length; i++){
                                        if($scope.model.headquartersCollection[i].selected != undefined &&
                                            $scope.model.headquartersCollection[i].selected == true)
                                        {
                                            headquartersIdCollection.push($scope.model.headquartersCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                headquartersIdCollection.push(headquarters_id);
                            }
                            var data = {
                                headquartersId: headquartersIdCollection
                            };
                            headquartersFact.deleteHeadQuarters($scope, data);
                        }
                    });
            }
        }

        /* edit headquarters */
        $scope.editHeadQuarters = function(headquarter)
        {
            $scope.model.createAction = false;
            $scope.clearHeadQuartersForm();
            $scope.clearHeadQuartersForm();
            $scope.model.selectedHeadQuarter = headquarter;
            $scope.showHeadQuartersForm();
        }

        /* change the view mode of the headquarters data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.headquartersCollection = [];
            $scope.model.activeView = option;
            $scope.getHeadQuarters();
        }

        /* get the HeadQuarters Collection */
        $scope.getHeadQuarters = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showHeadQuartersForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            headquartersFact.getHeadQuartersData($scope,searchParametersCollection, function(response){
                    $scope.model.headquartersCollection = response.data.headquartersDataCollection;
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
                $scope.model.canCreateHeadQuarters = false;
                $scope.model.canEditHeadQuarters = false;
                $scope.model.canDeleteHeadQuarters = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateHeadQuarters = true;
                $scope.model.canEditHeadQuarters = false;
                $scope.model.canDeleteHeadQuarters = false;
                $scope.model.allHeadQuartersSelected = false;
                $scope.model.selectedHeadQuarter = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideHeadQuartersForm = function()
        {
            $scope.model.showHeadQuartersForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getHeadQuarters();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.headquartersCurrentPage = 1;
            $scope.model.headquartersPagesCollection = [];
            $scope.model.headquartersPagesCollection.push(1);
            $scope.model.headquartersCurrentResultStart = 0;
            $scope.model.headquartersCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save headquarters data */
        $scope.saveHeadQuartersData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsHeadQuartersForm();

                if($scope.model.selectedHeadQuarter.title == null ||
                !alfaNumericRegExpr.test($scope.model.selectedHeadQuarter.title) ||
                $scope.model.selectedHeadQuarter.url_slug == null ||
                !alfaNumericRegExpr.test($scope.model.selectedHeadQuarter.url_slug) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedHeadQuarter.title == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedHeadQuarter.title)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedHeadQuarter.url_slug == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedHeadQuarter.url_slug)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedHeadQuarter.content = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedHeadQuarter.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedHeadQuarter.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedHeadQuarter.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedHeadQuarter.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     $scope.model.selectedHeadQuarter.currentLanguage = $scope.model.selectedLanguage.value;
                     var headquartersData = {headquarterData: $scope.model.selectedHeadQuarter};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     headquartersFact.saveHeadQuartersData($scope, headquartersData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search HeadQuarters through Search Input Field */
        $scope.searchHeadQuarters = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getHeadQuarters();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getHeadQuarters();
            }
        }

        /* selecting/deselecting all headquarters */
        $scope.selectAllHeadQuarters = function(event){
            var canDeleteAll = true;
            $scope.model.allHeadQuartersSelected = !$scope.model.allHeadQuartersSelected;
            if(!$scope.model.allHeadQuartersSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.headquartersCollection.length; i++){
                $scope.model.headquartersCollection[i].selected = $scope.model.allHeadQuartersSelected;
                if($scope.model.allHeadQuartersSelected == true && $scope.model.headquartersCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteHeadQuarters = canDeleteAll;
        }

        /*selecting/deselecting headquarters */
        $scope.selectHeadQuarters= function(event,headquarters){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalHeadQuartersSelected = 1;
            headquarters.selected = !headquarters.selected;
            if($scope.model.headquartersCollection.length == 1){
                if(headquarters.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalHeadQuartersSelected = 0;
                }
                if(headquarters.canDelete == 0){
                    canDeleteAll = false;
                }
                if(headquarters.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.headquartersCollection.length > 1){
                totalHeadQuartersSelected = 0;
                for(var i=0; i<$scope.model.headquartersCollection.length; i++){
                    var headquarters = $scope.model.headquartersCollection[i];
                    if(headquarters.selected == true){
                        totalHeadQuartersSelected++;
                        if(headquarters.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(headquarters.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalHeadQuartersSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteHeadQuarters = true;
                    if(totalHeadQuartersSelected == $scope.model.headquartersCollection.length){
                        $scope.model.allHeadQuartersSelected = true;
                    }
                    else{
                        $scope.model.allHeadQuartersSelected = false;
                    }
                }
                if(totalHeadQuartersSelected == 1 && canEditAll == true){
                    $scope.model.canEditHeadQuarters = true;
                }
                else{
                    $scope.model.canEditHeadQuarters = false;
                }
            }
            else{
                $scope.model.canEditHeadQuarters = false;
                $scope.model.canDeleteHeadQuarters = false;
                $scope.model.allHeadQuartersSelected = false;
            }
        }

        /* show the form to Create/Edit HeadQuarters */
        $scope.showHeadQuartersForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showHeadQuartersForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showHeadQuartersForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    headquarterId : $scope.model.selectedHeadQuarter.id
                };
                searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
                headquartersFact.getHeadQuartersData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedHeadQuarter = response.data.headquarterData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedHeadQuarter.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedHeadQuarter.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedHeadQuarter.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedHeadQuarter.featured_image_url,
                            id : $scope.model.selectedHeadQuarter.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedHeadQuarter.content);

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


                if(suffix == 'headquarters-type'){
                    $('#headquarters-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.headquartersCurrentResultStart = 0;
            $scope.model.headquartersCurrentResultLimit = 0;
            $scope.model.headquartersCurrentPage = ($scope.model.headquartersCurrentPage*1);
            $scope.model.headquartersCurrentPageSize = ($scope.model.headquartersCurrentPageSize*1);

            if($scope.model.headquartersCollection.length > 0){
                $scope.model.headquartersCurrentResultStart = ($scope.model.headquartersCurrentPage - 1) * $scope.model.headquartersCurrentPageSize + 1;
                $scope.model.headquartersCurrentResultLimit = ($scope.model.headquartersCurrentPageSize * $scope.model.headquartersCurrentPage);
                if($scope.model.headquartersCollection.length < ($scope.model.headquartersCurrentPageSize * $scope.model.headquartersCurrentPage)){

                    $scope.model.headquartersCurrentResultLimit = $scope.model.headquartersCollection.length;
                }

                var totalPages = Math.ceil($scope.model.headquartersCollection.length / $scope.model.headquartersCurrentPageSize);
                $scope.model.headquartersPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.headquartersPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.headquartersPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateHeadQuartersForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedHeadQuarter.title != null &&
                        alfaNumericRegExpr.test($scope.model.selectedHeadQuarter.title)){
                        $scope.model.selectedHeadQuarter.url_slug = slugify($scope.model.selectedHeadQuarter.title);
                    }
                    else{
                        $scope.model.selectedHeadQuarter.url_slug = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedHeadQuarter.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedHeadQuarter.published_date = null;
                            $scope.model.publishedDateHasError = false;
                        }
                    break;
            }
        }

        $scope.changeLoadDataOrSavedInfoByLanguage = function (event, from, language) {
            $scope.model.selectedLanguage = language;
            switch (from) {
              case 'list':
                $scope.model.headquartersCollection = [];
                $scope.getHeadQuarters()
                  break;
              case 'form':
                $scope.showHeadQuartersForm();
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
            $scope.model.headquartersCollection = [];
            $scope.model.headquartersSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.headquartersCurrentPageSize = 20;
            $scope.model.headquartersCurrentPage = 1;
            $scope.model.headquartersPagesCollection = [];
            $scope.model.headquartersPagesCollection.push(1);
            $scope.model.headquartersCurrentResultStart = 0;
            $scope.model.headquartersCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allHeadQuartersSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showHeadQuartersForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedHeadQuarter = null;

            $scope.clearHeadQuartersForm();
            headquartersFact.loadInitialsData($scope, function(response){
                $scope.model.headquartersCollection = response.data.initialsData.headquartersDataCollection;
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
                var showHeadQuartersForm = response.data.initialsData.showHeadQuartersForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsHeadQuartersForm();

                if(showHeadQuartersForm == true){
                        $scope.createHeadQuarters();
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
    angular.module('BncBackend.headquartersController').controller('headquartersCtrller',headquartersCtrller);
})();
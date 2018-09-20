/*
 * File for handling controllers for Backend Pages Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.pagesController', ['BncBackend.pagesFactory']);


    /* Controller for handling Pages functions */
    function pagesCtrller($scope, $filter, pagesFact){

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
        /*assign default template*/
        function assignDefaultTemplate(){
            if($scope.model.templatesCollection.length > 0){
                for(var i=0;i<$scope.model.templatesCollection.length;i++){
                   if($scope.model.templatesCollection[i].template_slug == 'default'){
                        $scope.model.selectedTemplate = $scope.model.templatesCollection[i];
                        //console.log($scope.model.selectedTemplate);
                    }
                }
            }
        }

        /* clear errors of the form */
        $scope.clearErrorsPagesForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
            $scope.model.templateHasError = false;
        }
        
        /* clear form values */
        $scope.clearPagesForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedPage = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            assignDefaultTemplate();
            $('#textEditor').code(null);

        }
        
        /* create pages */
        $scope.createPages = function()
        {
            if($scope.model.canCreatePages == true)
            {
                $scope.model.createAction = true;
                $scope.clearPagesForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showPagesForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedPage.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedPage.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedPage.published_date;
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

        /* delete pages */
        $scope.deletePages = function(pages_id)
        {
            var proceed = true;
            if(typeof pages_id == 'string' && !$scope.model.canDeletePages){
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
                            var pagesIdCollection = [];
                            if(typeof pages_id == 'string'){
                                if($scope.model.pagesCollection.length > 0){
                                    for(var i=0; i<$scope.model.pagesCollection.length; i++){
                                        if($scope.model.pagesCollection[i].selected != undefined &&
                                            $scope.model.pagesCollection[i].selected == true)
                                        {
                                            pagesIdCollection.push($scope.model.pagesCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                pagesIdCollection.push(pages_id);
                            }
                            var data = {
                                pagesId: pagesIdCollection
                            };
                            pagesFact.deletePages($scope, data);
                        }
                    });
            }
        }

        /* edit pages */
        $scope.editPages = function(page)
        {
            $scope.model.createAction = false;
            $scope.clearPagesForm();
            $scope.model.selectedPage = page;
            $scope.showPagesForm();
        }

        /* change the view mode of the pages data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.pagesCollection = [];
            $scope.model.activeView = option;
            $scope.getPages();
        }

        /* get the Pages Collection */
        $scope.getPages = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showPagesForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            pagesFact.getPagesData($scope,searchParametersCollection, function(response){
                $scope.model.pagesCollection = response.data.pagesDataCollection;
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
                $scope.model.canCreatePages = false;
                $scope.model.canEditPages = false;
                $scope.model.canDeletePages = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreatePages = true;
                $scope.model.canEditPages = false;
                $scope.model.canDeletePages = false;
                $scope.model.allPagesSelected = false;
                $scope.model.selectedPage = null;
            }
        }

        /* Hide the CRUD form */
        $scope.hidePagesForm = function()
        {
            $scope.model.showPagesForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getPages();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.pagesCurrentPage = 1;
            $scope.model.pagesPagesCollection = [];
            $scope.model.pagesPagesCollection.push(1);
            $scope.model.pagesCurrentResultStart = 0;
            $scope.model.pagesCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save pages data */
        $scope.savePagesData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsPagesForm();

                if($scope.model.selectedPage.title_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedPage.title_es) ||
                $scope.model.selectedPage.url_slug_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedPage.url_slug_es) ||
                $scope.model.selectedTemplate == null ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedPage.title_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedPage.title_es)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedPage.url_slug_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedPage.url_slug_es)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }

                    if($scope.model.selectedTemplate == null){
                        $scope.model.templateHasError = true;
                    }

                }

                if(canProceed){
                    $scope.model.selectedPage.content_es = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedPage.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedPage.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedPage.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedPage.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                    $scope.model.selectedPage.template = $scope.model.selectedTemplate;
                     var pagesData = {pageData: $scope.model.selectedPage};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     pagesFact.savePagesData($scope, pagesData, option, action, function(response){
                         $scope.model.processingData = false;
                         $scope.toggleDataLoader();
                         if(response.data.success == 0){
                             toastr.options.timeOut = 5000;
                             toastr.error(response.data.message,"Error");
                         }
                         else{
                             $scope.clearErrorsPagesForm();
                             if(option == 'clear'){
                                 $scope.clearPagesForm();
                             }
                             else if(option == 'close'){
                                 $scope.clearPagesForm();
                                 $scope.hidePagesForm();
                             }
                             else if(option == 'stay'){
                                 $scope.model.createAction = false;
                                 $scope.model.selectedPage.id = response.data.pageId;
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

        /* search Pages through Search Input Field */
        $scope.searchPages = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getPages();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getPages();
            }
        }

        /* selecting/deselecting all pages */
        $scope.selectAllPages = function(event){
            var canDeleteAll = true;
            $scope.model.allPagesSelected = !$scope.model.allPagesSelected;
            if(!$scope.model.allPagesSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.pagesCollection.length; i++){
                $scope.model.pagesCollection[i].selected = $scope.model.allPagesSelected;
                if($scope.model.allPagesSelected == true && $scope.model.pagesCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeletePages = canDeleteAll;
        }

        /*selecting/deselecting pages */
        $scope.selectPages= function(event,pages){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalPagesSelected = 1;
            pages.selected = !pages.selected;
            if($scope.model.pagesCollection.length == 1){
                if(pages.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalPagesSelected = 0;
                }
                if(pages.canDelete == 0){
                    canDeleteAll = false;
                }
                if(pages.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.pagesCollection.length > 1){
                totalPagesSelected = 0;
                for(var i=0; i<$scope.model.pagesCollection.length; i++){
                    var pages = $scope.model.pagesCollection[i];
                    if(pages.selected == true){
                        totalPagesSelected++;
                        if(pages.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(pages.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalPagesSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeletePages = true;
                    if(totalPagesSelected == $scope.model.pagesCollection.length){
                        $scope.model.allPagesSelected = true;
                    }
                    else{
                        $scope.model.allPagesSelected = false;
                    }
                }
                if(totalPagesSelected == 1 && canEditAll == true){
                    $scope.model.canEditPages = true;
                }
                else{
                    $scope.model.canEditPages = false;
                }
            }
            else{
                $scope.model.canEditPages = false;
                $scope.model.canDeletePages = false;
                $scope.model.allPagesSelected = false;
            }
        }

        /* show the form to Create/Edit Pages */
        $scope.showPagesForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showPagesForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showPagesForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    pageId : $scope.model.selectedPage.id
                };
                pagesFact.getPagesData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedPage = response.data.pageData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedPage.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedPage.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedPage.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedPage.featured_image_url,
                            id : $scope.model.selectedPage.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedPage.content_es);

                    if($scope.model.templatesCollection.length > 0){
                        for(var i=0; i<$scope.model.templatesCollection.length; i++){
                            if($scope.model.selectedPage.template.template_slug == $scope.model.templatesCollection[i].template_slug){
                                $scope.model.selectedTemplate = $scope.model.templatesCollection[i];
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


                if(suffix == 'pages-type'){
                    $('#pages-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.pagesCurrentResultStart = 0;
            $scope.model.pagesCurrentResultLimit = 0;
            $scope.model.pagesCurrentPage = ($scope.model.pagesCurrentPage*1);
            $scope.model.pagesCurrentPageSize = ($scope.model.pagesCurrentPageSize*1);

            if($scope.model.pagesCollection.length > 0){
                $scope.model.pagesCurrentResultStart = ($scope.model.pagesCurrentPage - 1) * $scope.model.pagesCurrentPageSize + 1;
                $scope.model.pagesCurrentResultLimit = ($scope.model.pagesCurrentPageSize * $scope.model.pagesCurrentPage);
                if($scope.model.pagesCollection.length < ($scope.model.pagesCurrentPageSize * $scope.model.pagesCurrentPage)){

                    $scope.model.pagesCurrentResultLimit = $scope.model.pagesCollection.length;
                }

                var totalPages = Math.ceil($scope.model.pagesCollection.length / $scope.model.pagesCurrentPageSize);
                $scope.model.pagesPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.pagesPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.pagesPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updatePagesForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedPage.title_es != null &&
                        alfaNumericRegExpr.test($scope.model.selectedPage.title_es)){
                        $scope.model.selectedPage.url_slug_es = slugify($scope.model.selectedPage.title_es);
                    }
                    else{
                        $scope.model.selectedPage.url_slug_es = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedPage.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedPage.published_date = null;
                            $scope.model.publishedDateHasError = false;
                        }
                    break;
                case 'template':
                    $scope.model.selectedTemplate = element;
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
            $scope.model.pagesCollection = [];
            $scope.model.pagesSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.pagesCurrentPageSize = 20;
            $scope.model.pagesCurrentPage = 1;
            $scope.model.pagesPagesCollection = [];
            $scope.model.pagesPagesCollection.push(1);
            $scope.model.pagesCurrentResultStart = 0;
            $scope.model.pagesCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allPagesSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showPagesForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.templatesCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPage = null;

            $scope.clearPagesForm();
            pagesFact.loadInitialsData($scope, function(response){

                $scope.model.pagesCollection = response.data.initialsData.pagesDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                $scope.model.templatesCollection = response.data.initialsData.templatesDataCollection;
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showPagesForm = response.data.initialsData.showPagesForm;

                $scope.updatePaginationValues();
                $scope.clearErrorsPagesForm();

                if(showPagesForm == true){
                    $scope.createPages();
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
    angular.module('BncBackend.pagesController').controller('pagesCtrller',pagesCtrller);
})();
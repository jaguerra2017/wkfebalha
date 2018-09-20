/*
 * File for handling controllers for Backend Partners Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.partnersController', ['BncBackend.partnersFactory']);


    /* Controller for handling Partners functions */
    function partnersCtrller($scope, $filter, partnersFact){

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
        $scope.clearErrorsPartnersForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearPartnersForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedPartner = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create partners */
        $scope.createPartners = function()
        {
            if($scope.model.canCreatePartners == true)
            {
                $scope.model.createAction = true;
                $scope.clearPartnersForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showPartnersForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedPartner.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedPartner.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedPartner.published_date;
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

        /* delete partners */
        $scope.deletePartners = function(partners_id)
        {
            var proceed = true;
            if(typeof partners_id == 'string' && !$scope.model.canDeletePartners){
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
                            var partnersIdCollection = [];
                            if(typeof partners_id == 'string'){
                                if($scope.model.partnersCollection.length > 0){
                                    for(var i=0; i<$scope.model.partnersCollection.length; i++){
                                        if($scope.model.partnersCollection[i].selected != undefined &&
                                            $scope.model.partnersCollection[i].selected == true)
                                        {
                                            partnersIdCollection.push($scope.model.partnersCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                partnersIdCollection.push(partners_id);
                            }
                            var data = {
                                partnersId: partnersIdCollection
                            };
                            partnersFact.deletePartners($scope, data);
                        }
                    });
            }
        }

        /* edit partners */
        $scope.editPartners = function(partner)
        {
            $scope.model.createAction = false;
            $scope.clearPartnersForm();
            $scope.clearPartnersForm();
            $scope.model.selectedPartner = partner;
            $scope.showPartnersForm();
        }

        /* change the view mode of the partners data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.partnersCollection = [];
            $scope.model.activeView = option;
            $scope.getPartners();
        }

        /* get the Partners Collection */
        $scope.getPartners = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showPartnersForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            partnersFact.getPartnersData($scope,searchParametersCollection, function(response){
                    $scope.model.partnersCollection = response.data.partnersDataCollection;
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
                $scope.model.canCreatePartners = false;
                $scope.model.canEditPartners = false;
                $scope.model.canDeletePartners = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreatePartners = true;
                $scope.model.canEditPartners = false;
                $scope.model.canDeletePartners = false;
                $scope.model.allPartnersSelected = false;
                $scope.model.selectedPartner = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hidePartnersForm = function()
        {
            $scope.model.showPartnersForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getPartners();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.partnersCurrentPage = 1;
            $scope.model.partnersPagesCollection = [];
            $scope.model.partnersPagesCollection.push(1);
            $scope.model.partnersCurrentResultStart = 0;
            $scope.model.partnersCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save partners data */
        $scope.savePartnersData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsPartnersForm();

                if($scope.model.selectedPartner.title_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedPartner.title_es) ||
                $scope.model.selectedPartner.url_slug_es == null ||
                !alfaNumericRegExpr.test($scope.model.selectedPartner.url_slug_es) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedPartner.title_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedPartner.title_es)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedPartner.url_slug_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedPartner.url_slug_es)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedPartner.content_es = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedPartner.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedPartner.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedPartner.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedPartner.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     var partnersData = {partnerData: $scope.model.selectedPartner};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     partnersFact.savePartnersData($scope, partnersData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Partners through Search Input Field */
        $scope.searchPartners = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getPartners();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getPartners();
            }
        }

        /* selecting/deselecting all partners */
        $scope.selectAllPartners = function(event){
            var canDeleteAll = true;
            $scope.model.allPartnersSelected = !$scope.model.allPartnersSelected;
            if(!$scope.model.allPartnersSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.partnersCollection.length; i++){
                $scope.model.partnersCollection[i].selected = $scope.model.allPartnersSelected;
                if($scope.model.allPartnersSelected == true && $scope.model.partnersCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeletePartners = canDeleteAll;
        }

        /*selecting/deselecting partners */
        $scope.selectPartners= function(event,partners){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalPartnersSelected = 1;
            partners.selected = !partners.selected;
            if($scope.model.partnersCollection.length == 1){
                if(partners.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalPartnersSelected = 0;
                }
                if(partners.canDelete == 0){
                    canDeleteAll = false;
                }
                if(partners.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.partnersCollection.length > 1){
                totalPartnersSelected = 0;
                for(var i=0; i<$scope.model.partnersCollection.length; i++){
                    var partners = $scope.model.partnersCollection[i];
                    if(partners.selected == true){
                        totalPartnersSelected++;
                        if(partners.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(partners.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalPartnersSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeletePartners = true;
                    if(totalPartnersSelected == $scope.model.partnersCollection.length){
                        $scope.model.allPartnersSelected = true;
                    }
                    else{
                        $scope.model.allPartnersSelected = false;
                    }
                }
                if(totalPartnersSelected == 1 && canEditAll == true){
                    $scope.model.canEditPartners = true;
                }
                else{
                    $scope.model.canEditPartners = false;
                }
            }
            else{
                $scope.model.canEditPartners = false;
                $scope.model.canDeletePartners = false;
                $scope.model.allPartnersSelected = false;
            }
        }

        /* show the form to Create/Edit Partners */
        $scope.showPartnersForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showPartnersForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showPartnersForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    partnerId : $scope.model.selectedPartner.id
                };
                partnersFact.getPartnersData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedPartner = response.data.partnerData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedPartner.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedPartner.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedPartner.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedPartner.featured_image_url,
                            id : $scope.model.selectedPartner.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedPartner.content_es);

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


                if(suffix == 'partners-type'){
                    $('#partners-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.partnersCurrentResultStart = 0;
            $scope.model.partnersCurrentResultLimit = 0;
            $scope.model.partnersCurrentPage = ($scope.model.partnersCurrentPage*1);
            $scope.model.partnersCurrentPageSize = ($scope.model.partnersCurrentPageSize*1);

            if($scope.model.partnersCollection.length > 0){
                $scope.model.partnersCurrentResultStart = ($scope.model.partnersCurrentPage - 1) * $scope.model.partnersCurrentPageSize + 1;
                $scope.model.partnersCurrentResultLimit = ($scope.model.partnersCurrentPageSize * $scope.model.partnersCurrentPage);
                if($scope.model.partnersCollection.length < ($scope.model.partnersCurrentPageSize * $scope.model.partnersCurrentPage)){

                    $scope.model.partnersCurrentResultLimit = $scope.model.partnersCollection.length;
                }

                var totalPages = Math.ceil($scope.model.partnersCollection.length / $scope.model.partnersCurrentPageSize);
                $scope.model.partnersPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.partnersPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.partnersPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updatePartnersForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedPartner.title_es != null &&
                        alfaNumericRegExpr.test($scope.model.selectedPartner.title_es)){
                        $scope.model.selectedPartner.url_slug_es = slugify($scope.model.selectedPartner.title_es);
                    }
                    else{
                        $scope.model.selectedPartner.url_slug_es = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedPartner.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedPartner.published_date = null;
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
            $scope.model.partnersCollection = [];
            $scope.model.partnersSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.partnersCurrentPageSize = 20;
            $scope.model.partnersCurrentPage = 1;
            $scope.model.partnersPagesCollection = [];
            $scope.model.partnersPagesCollection.push(1);
            $scope.model.partnersCurrentResultStart = 0;
            $scope.model.partnersCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allPartnersSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showPartnersForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPartner = null;

            $scope.clearPartnersForm();
            partnersFact.loadInitialsData($scope, function(response){
                $scope.model.partnersCollection = response.data.initialsData.partnersDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showPartnersForm = response.data.initialsData.showPartnersForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsPartnersForm();

                if(showPartnersForm == true){
                        $scope.createPartners();
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
    angular.module('BncBackend.partnersController').controller('partnersCtrller',partnersCtrller);
})();
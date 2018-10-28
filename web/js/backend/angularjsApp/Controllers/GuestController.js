/*
 * File for handling controllers for Backend Guests Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.guestsController', ['BncBackend.guestsFactory']);


    /* Controller for handling Guests functions */
    function guestsCtrller($scope, $filter, guestsFact){

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
        $scope.clearErrorsGuestsForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearGuestsForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedGuest = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create guests */
        $scope.createGuests = function()
        {
            if($scope.model.canCreateGuests == true)
            {
                $scope.model.createAction = true;
                $scope.clearGuestsForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showGuestsForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedGuest.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedGuest.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedGuest.published_date;
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

        /* delete guests */
        $scope.deleteGuests = function(guests_id)
        {
            var proceed = true;
            if(typeof guests_id == 'string' && !$scope.model.canDeleteGuests){
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
                            var guestsIdCollection = [];
                            if(typeof guests_id == 'string'){
                                if($scope.model.guestsCollection.length > 0){
                                    for(var i=0; i<$scope.model.guestsCollection.length; i++){
                                        if($scope.model.guestsCollection[i].selected != undefined &&
                                            $scope.model.guestsCollection[i].selected == true)
                                        {
                                            guestsIdCollection.push($scope.model.guestsCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                guestsIdCollection.push(guests_id);
                            }
                            var data = {
                                guestsId: guestsIdCollection
                            };
                            guestsFact.deleteGuests($scope, data);
                        }
                    });
            }
        }

        /* edit guests */
        $scope.editGuests = function(guest)
        {
            $scope.model.createAction = false;
            $scope.clearGuestsForm();
            $scope.clearGuestsForm();
            $scope.model.selectedGuest = guest;
            $scope.showGuestsForm();
        }

        /* change the view mode of the guests data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.guestsCollection = [];
            $scope.model.activeView = option;
            $scope.getGuests();
        }

        /* get the Guests Collection */
        $scope.getGuests = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showGuestsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            guestsFact.getGuestsData($scope,searchParametersCollection, function(response){
                    $scope.model.guestCollection = response.data.guestCollection;
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
                $scope.model.canCreateGuests = false;
                $scope.model.canEditGuests = false;
                $scope.model.canDeleteGuests = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateGuests = true;
                $scope.model.canEditGuests = false;
                $scope.model.canDeleteGuests = false;
                $scope.model.allGuestsSelected = false;
                $scope.model.selectedGuest = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideGuestsForm = function()
        {
            $scope.model.showGuestsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getGuests();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.guestsCurrentPage = 1;
            $scope.model.guestsPagesCollection = [];
            $scope.model.guestsPagesCollection.push(1);
            $scope.model.guestsCurrentResultStart = 0;
            $scope.model.guestsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save guests data */
        $scope.saveGuestsData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsGuestsForm();

                if($scope.model.selectedGuest.title == null ||
                !alfaNumericRegExpr.test($scope.model.selectedGuest.title) ||
                $scope.model.selectedGuest.url_slug == null ||
                !alfaNumericRegExpr.test($scope.model.selectedGuest.url_slug) ||
                !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedGuest.title == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedGuest.title)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedGuest.url_slug == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedGuest.url_slug)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedGuest.content = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedGuest.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedGuest.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedGuest.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedGuest.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     $scope.model.selectedGuest.currentLanguage = $scope.model.selectedLanguage.value;
                     var guestsData = {guestData: $scope.model.selectedGuest};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     guestsFact.saveGuestsData($scope, guestsData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Guests through Search Input Field */
        $scope.searchGuests = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getGuests();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getGuests();
            }
        }

        /* selecting/deselecting all guests */
        $scope.selectAllGuests = function(event){
            var canDeleteAll = true;
            $scope.model.allGuestsSelected = !$scope.model.allGuestsSelected;
            if(!$scope.model.allGuestsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.guestsCollection.length; i++){
                $scope.model.guestsCollection[i].selected = $scope.model.allGuestsSelected;
                if($scope.model.allGuestsSelected == true && $scope.model.guestsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteGuests = canDeleteAll;
        }

        /*selecting/deselecting guests */
        $scope.selectGuests= function(event,guests){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalGuestsSelected = 1;
            guests.selected = !guests.selected;
            if($scope.model.guestsCollection.length == 1){
                if(guests.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalGuestsSelected = 0;
                }
                if(guests.canDelete == 0){
                    canDeleteAll = false;
                }
                if(guests.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.guestsCollection.length > 1){
                totalGuestsSelected = 0;
                for(var i=0; i<$scope.model.guestsCollection.length; i++){
                    var guests = $scope.model.guestsCollection[i];
                    if(guests.selected == true){
                        totalGuestsSelected++;
                        if(guests.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(guests.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalGuestsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteGuests = true;
                    if(totalGuestsSelected == $scope.model.guestsCollection.length){
                        $scope.model.allGuestsSelected = true;
                    }
                    else{
                        $scope.model.allGuestsSelected = false;
                    }
                }
                if(totalGuestsSelected == 1 && canEditAll == true){
                    $scope.model.canEditGuests = true;
                }
                else{
                    $scope.model.canEditGuests = false;
                }
            }
            else{
                $scope.model.canEditGuests = false;
                $scope.model.canDeleteGuests = false;
                $scope.model.allGuestsSelected = false;
            }
        }

        /* show the form to Create/Edit Guests */
        $scope.showGuestsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showGuestsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showGuestsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    guestId : $scope.model.selectedGuest.id
                };
                searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
                guestsFact.getGuestsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedGuest = response.data.guestData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedGuest.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedGuest.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedGuest.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedGuest.featured_image_url,
                            id : $scope.model.selectedGuest.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedGuest.content);

                });
            }
        }

          $scope.changeLoadDataOrSavedInfoByLanguage = function (event, from, language) {
            $scope.model.selectedLanguage = language;
            switch (from) {
              case 'list':
                $scope.model.guestCollection = [];
                $scope.getGuests();
                break;
              case 'form':
                $scope.showGuestsForm();
                break;
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


                if(suffix == 'guests-type'){
                    $('#guests-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.guestsCurrentResultStart = 0;
            $scope.model.guestsCurrentResultLimit = 0;
            $scope.model.guestsCurrentPage = ($scope.model.guestsCurrentPage*1);
            $scope.model.guestsCurrentPageSize = ($scope.model.guestsCurrentPageSize*1);

            if($scope.model.guestsCollection.length > 0){
                $scope.model.guestsCurrentResultStart = ($scope.model.guestsCurrentPage - 1) * $scope.model.guestsCurrentPageSize + 1;
                $scope.model.guestsCurrentResultLimit = ($scope.model.guestsCurrentPageSize * $scope.model.guestsCurrentPage);
                if($scope.model.guestsCollection.length < ($scope.model.guestsCurrentPageSize * $scope.model.guestsCurrentPage)){

                    $scope.model.guestsCurrentResultLimit = $scope.model.guestsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.guestsCollection.length / $scope.model.guestsCurrentPageSize);
                $scope.model.guestsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.guestsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.guestsPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateGuestsForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedGuest.title != null &&
                        alfaNumericRegExpr.test($scope.model.selectedGuest.title)){
                        $scope.model.selectedGuest.url_slug = slugify($scope.model.selectedGuest.title);
                    }
                    else{
                        $scope.model.selectedGuest.url_slug = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedGuest.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedGuest.published_date = null;
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
            $scope.model.guestsCollection = [];
            $scope.model.guestsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.guestsCurrentPageSize = 20;
            $scope.model.guestsCurrentPage = 1;
            $scope.model.guestsPagesCollection = [];
            $scope.model.guestsPagesCollection.push(1);
            $scope.model.guestsCurrentResultStart = 0;
            $scope.model.guestsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allGuestsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showGuestsForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedGuest = null;

            $scope.clearGuestsForm();
            guestsFact.loadInitialsData($scope, function(response){

                $scope.model.guestsCollection = response.data.initialsData.guestDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                $scope.model.languages = response.data.initialsData.languages;
                $scope.model.selectedLanguage = $scope.model.languages[0];
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showGuestsForm = response.data.initialsData.showGuestForm;


                    $scope.updatePaginationValues();
                    $scope.clearErrorsGuestsForm();

                if(showGuestsForm == true){
                        $scope.createGuests();
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
    angular.module('BncBackend.guestsController').controller('guestsCtrller',guestsCtrller);
})();
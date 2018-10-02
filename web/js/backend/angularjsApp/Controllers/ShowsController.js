/*
 * File for handling controllers for Backend Shows Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.showsController', ['BncBackend.showsFactory']);


    /* Controller for handling Shows functions */
    function showsCtrller($scope, $filter, showsFact){

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
        $scope.clearErrorsShowsForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
            $scope.model.showDateHasError = false;
        }
        
        /* clear form values */
        $scope.clearShowsForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedShow = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }
        
        /* create shows */
        $scope.createShows = function()
        {
            if($scope.model.canCreateShows == true)
            {
                $scope.model.createAction = true;
                $scope.clearShowsForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showShowsForm();
            }
        }

        function checkDate(fieldDate){
          var proceed = true;
            switch (fieldDate) {
              case 'published':
                if($scope.model.selectedShow.published_date != null){
                  if(!dateRegExpress.test($scope.model.selectedShow.published_date)){
                    proceed = false;
                  }
                  else{
                    var publishedDate = $scope.model.selectedShow.published_date;
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
                  break;
              case 'show':
                if($scope.model.selectedShow.show_date != null){
                  if(!dateRegExpress.test($scope.model.selectedShow.show_date)){
                    proceed = false;
                  }
                }
                  break;
            }
            return proceed;
        }

        /* delete shows */
        $scope.deleteShows = function(shows_id)
        {
            var proceed = true;
            if(typeof shows_id == 'string' && !$scope.model.canDeleteShows){
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
                            var showsIdCollection = [];
                            if(typeof shows_id == 'string'){
                                if($scope.model.showsCollection.length > 0){
                                    for(var i=0; i<$scope.model.showsCollection.length; i++){
                                        if($scope.model.showsCollection[i].selected != undefined &&
                                            $scope.model.showsCollection[i].selected == true)
                                        {
                                            showsIdCollection.push($scope.model.showsCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                showsIdCollection.push(shows_id);
                            }
                            var data = {
                                showsId: showsIdCollection
                            };
                            showsFact.deleteShows($scope, data);
                        }
                    });
            }
        }

        /* edit shows */
        $scope.editShows = function(show)
        {
            $scope.model.createAction = false;
            $scope.clearShowsForm();
            $scope.clearShowsForm();
            $scope.model.selectedShow = show;
            $scope.showShowsForm();
        }

        /* change the view mode of the shows data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.showsCollection = [];
            $scope.model.activeView = option;
            $scope.getShows();
        }

        /* get the Shows Collection */
        $scope.getShows = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showShowsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            showsFact.getShowsData($scope,searchParametersCollection, function(response){
                    $scope.model.showsCollection = response.data.showsDataCollection;
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
                $scope.model.canCreateShows = false;
                $scope.model.canEditShows = false;
                $scope.model.canDeleteShows = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateShows = true;
                $scope.model.canEditShows = false;
                $scope.model.canDeleteShows = false;
                $scope.model.allShowsSelected = false;
                $scope.model.selectedShow = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideShowsForm = function()
        {
          console.log( $scope.model.selectedShow);
            $scope.model.showShowsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getShows();

            $scope.goToTop();
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.showsCurrentPage = 1;
            $scope.model.showsPagesCollection = [];
            $scope.model.showsPagesCollection.push(1);
            $scope.model.showsCurrentResultStart = 0;
            $scope.model.showsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }
        
        /* save shows data */
        $scope.saveShowsData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsShowsForm();

                if($scope.model.selectedShow.title == null ||
                !alfaNumericRegExpr.test($scope.model.selectedShow.title) ||
                $scope.model.selectedShow.url_slug == null ||
                !alfaNumericRegExpr.test($scope.model.selectedShow.url_slug) ||
                !checkDate('published')){
                    canProceed = false;

                    if($scope.model.selectedShow.title == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedShow.title)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedShow.url_slug == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedShow.url_slug)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkDate('published')){
                        $scope.model.publishedDateHasError = true;
                    }

                  if(!checkDate('show')){
                    $scope.model.showDateHasError = true;
                  }
                }

                if(canProceed){
                    $scope.model.selectedShow.content = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedShow.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedShow.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                    $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedShow.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedShow.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                     $scope.model.selectedShow.currentLanguage = $scope.model.selectedLanguage.value;
                     var showsData = {showData: $scope.model.selectedShow};
                     var action = $scope.model.createAction == true ? 'create' : 'edit';

                     showsFact.saveShowsData($scope, showsData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Shows through Search Input Field */
        $scope.searchShows = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getShows();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getShows();
            }
        }

        $scope.changeRoom = function ($event, room) {
          $scope.model.selectedShow.room = room.id;
        }

        /* selecting/deselecting all shows */
        $scope.selectAllShows = function(event){
            var canDeleteAll = true;
            $scope.model.allShowsSelected = !$scope.model.allShowsSelected;
            if(!$scope.model.allShowsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.showsCollection.length; i++){
                $scope.model.showsCollection[i].selected = $scope.model.allShowsSelected;
                if($scope.model.allShowsSelected == true && $scope.model.showsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteShows = canDeleteAll;
        }

        /*selecting/deselecting shows */
        $scope.selectShows= function(event,shows){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalShowsSelected = 1;
            shows.selected = !shows.selected;
            if($scope.model.showsCollection.length == 1){
                if(shows.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalShowsSelected = 0;
                }
                if(shows.canDelete == 0){
                    canDeleteAll = false;
                }
                if(shows.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.showsCollection.length > 1){
                totalShowsSelected = 0;
                for(var i=0; i<$scope.model.showsCollection.length; i++){
                    var shows = $scope.model.showsCollection[i];
                    if(shows.selected == true){
                        totalShowsSelected++;
                        if(shows.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(shows.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalShowsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteShows = true;
                    if(totalShowsSelected == $scope.model.showsCollection.length){
                        $scope.model.allShowsSelected = true;
                    }
                    else{
                        $scope.model.allShowsSelected = false;
                    }
                }
                if(totalShowsSelected == 1 && canEditAll == true){
                    $scope.model.canEditShows = true;
                }
                else{
                    $scope.model.canEditShows = false;
                }
            }
            else{
                $scope.model.canEditShows = false;
                $scope.model.canDeleteShows = false;
                $scope.model.allShowsSelected = false;
            }
        }

        /* show the form to Create/Edit Shows */
        $scope.showShowsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showShowsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showShowsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    showId : $scope.model.selectedShow.id
                };
                searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
                showsFact.getShowsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedShow = response.data.showData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedShow.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedShow.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedShow.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedShow.featured_image_url,
                            id : $scope.model.selectedShow.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedShow.content);

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


                if(suffix == 'shows-type'){
                    $('#shows-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.showsCurrentResultStart = 0;
            $scope.model.showsCurrentResultLimit = 0;
            $scope.model.showsCurrentPage = ($scope.model.showsCurrentPage*1);
            $scope.model.showsCurrentPageSize = ($scope.model.showsCurrentPageSize*1);

            if($scope.model.showsCollection.length > 0){
                $scope.model.showsCurrentResultStart = ($scope.model.showsCurrentPage - 1) * $scope.model.showsCurrentPageSize + 1;
                $scope.model.showsCurrentResultLimit = ($scope.model.showsCurrentPageSize * $scope.model.showsCurrentPage);
                if($scope.model.showsCollection.length < ($scope.model.showsCurrentPageSize * $scope.model.showsCurrentPage)){

                    $scope.model.showsCurrentResultLimit = $scope.model.showsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.showsCollection.length / $scope.model.showsCurrentPageSize);
                $scope.model.showsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.showsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.showsPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateShowsForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedShow.title != null &&
                        alfaNumericRegExpr.test($scope.model.selectedShow.title)){
                        $scope.model.selectedShow.url_slug = slugify($scope.model.selectedShow.title);
                    }
                    else{
                        $scope.model.selectedShow.url_slug = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedShow.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                            $scope.model.selectedShow.published_date = null;
                            $scope.model.publishedDateHasError = false;
                        }
                    break;
            }
        }

        $scope.changeLoadDataOrSavedInfoByLanguage = function (event, from, language) {
            $scope.model.selectedLanguage = language;
            switch (from) {
              case 'list':
                $scope.model.showsCollection = [];
                $scope.getShows()
                  break;
              case 'form':
                $scope.showShowsForm();
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
            $scope.model.showsCollection = [];
            $scope.model.showsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.showsCurrentPageSize = 20;
            $scope.model.showsCurrentPage = 1;
            $scope.model.showsPagesCollection = [];
            $scope.model.showsPagesCollection.push(1);
            $scope.model.showsCurrentResultStart = 0;
            $scope.model.showsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allShowsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showShowsForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.rooms = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedShow = null;
            $scope.model.canProceed = false;

            $scope.clearShowsForm();
            showsFact.loadInitialsData($scope, function(response){
                $scope.model.showsCollection = response.data.initialsData.showsDataCollection;
                $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                if($scope.model.postStatusCollection.length > 0){
                    $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                }
                /* definiendo lenguajes */
                $scope.model.languages = response.data.initialsData.languages;
                $scope.model.selectedLanguage = $scope.model.languages[0];
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                $scope.model.rooms = response.data.initialsData.rooms;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }
                var showShowsForm = response.data.initialsData.showShowsForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsShowsForm();

                if(showShowsForm == true){
                        $scope.createShows();
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
    angular.module('BncBackend.showsController').controller('showsCtrller',showsCtrller);
})();
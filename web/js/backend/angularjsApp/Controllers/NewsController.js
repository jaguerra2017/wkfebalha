/*
 * File for handling controllers for Backend News Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.newsController', ['BncBackend.newsFactory']);


    /* Controller for handling News functions */
    function newsCtrller($scope, $filter, newsFact){

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
        $scope.clearErrorsNewsForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
            $scope.model.templateHasError = false;
        }

        /* clear form values */
        $scope.clearNewsForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedNew = {};
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }

        /* create news */
        $scope.createNews = function()
        {
            if($scope.model.canCreateNews == true)
            {
                $scope.model.createAction = true;
                $scope.clearNewsForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showNewsForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedNew.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedNew.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedNew.published_date;
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

        /* delete news */
        $scope.deleteNews = function(news_id)
        {
            var proceed = true;
            if(typeof news_id == 'string' && !$scope.model.canDeleteNews){
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
                            var newsIdCollection = [];
                            if(typeof news_id == 'string'){
                                if($scope.model.newsCollection.length > 0){
                                    for(var i=0; i<$scope.model.newsCollection.length; i++){
                                        if($scope.model.newsCollection[i].selected != undefined &&
                                            $scope.model.newsCollection[i].selected == true)
                                        {
                                            newsIdCollection.push($scope.model.newsCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                newsIdCollection.push(news_id);
                            }
                            var data = {
                                newsId: newsIdCollection
                            };
                            newsFact.deleteNews($scope, data);
                        }
                    });
            }
        }

        /* edit news */
        $scope.editNews = function(news)
        {
            $scope.model.createAction = false;
            $scope.clearNewsForm();
            $scope.model.selectedNew = news;
            $scope.showNewsForm();
        }

        /* change the view mode of the news data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.newsCollection = [];
            $scope.model.activeView = option;
            $scope.getNews();
        }

        /* get the News Collection */
        $scope.getNews = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                    $scope.model.showNewsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            newsFact.getNewsData($scope,searchParametersCollection, function(response){
                    $scope.model.newsCollection = response.data.newsDataCollection;
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
            var newHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
            $('html, body').animate({scrollTop: newHeading.height()}, 1000);
        }

        /* disabled options for CRUD operations */
        $scope.handleCrudOperations = function(option)
        {
            /* when option is 'disable' */
            if(option == 'disable'){
                $scope.model.canCreateNews = false;
                $scope.model.canEditNews = false;
                $scope.model.canDeleteNews = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateNews = true;
                $scope.model.canEditNews = false;
                $scope.model.canDeleteNews = false;
                $scope.model.allNewsSelected = false;
                $scope.model.selectedNew = null;
            }
        }

        /* Hide the CRUD form */
        $scope.hideNewsForm = function()
        {
            $scope.model.showNewsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getNews();

            $scope.goToTop();
        }

        /* reset the new size to default value 1 */
        $scope.resetPaginationNews = function()
        {
            $scope.model.newsCurrentPage = 1;
            $scope.model.newsNewsCollection = [];
            $scope.model.newsNewsCollection.push(1);
            $scope.model.newsCurrentResultStart = 0;
            $scope.model.newsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }

        /* save news data */
        $scope.saveNewsData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsNewsForm();

                if($scope.model.selectedNew.title == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedNew.title) ||
                    $scope.model.selectedNew.url_slug == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedNew.url_slug) ||
                    !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedNew.title == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedNew.title)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedNew.url_slug == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedNew.url_slug)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedNew.content = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedNew.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedNew.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                        $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedNew.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedNew.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                    $scope.model.selectedNew.currentLanguage = $scope.model.selectedLanguage.value;
                    var newsData = {newData: $scope.model.selectedNew};
                    var action = $scope.model.createAction == true ? 'create' : 'edit';

                    newsFact.saveNewsData($scope, newsData, option, action, function(response){
                            $scope.model.processingData = false;
                            $scope.toggleDataLoader();
                            if(response.data.success == 0){
                                toastr.options.timeOut = 5000;
                                toastr.error(response.data.message,"Error");
                            }
                            else{
                                $scope.clearErrorsNewsForm();
                                if(option == 'clear'){
                                    $scope.clearNewsForm();
                                }
                                else if(option == 'close'){
                                    $scope.clearNewsForm();
                                    $scope.hideNewsForm();
                                }
                                else if(option == 'stay'){
                                    $scope.model.createAction = false;
                                    $scope.model.selectedNew.id = response.data.newId;
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

        /* search News through Search Input Field */
        $scope.searchNews = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue))
                || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getNews();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getNews();
            }
        }

        /* selecting/deselecting all news */
        $scope.selectAllNews = function(event){
            var canDeleteAll = true;
            $scope.model.allNewsSelected = !$scope.model.allNewsSelected;
            if(!$scope.model.allNewsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.newsCollection.length; i++){
                $scope.model.newsCollection[i].selected = $scope.model.allNewsSelected;
                if($scope.model.allNewsSelected == true && $scope.model.newsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteNews = canDeleteAll;
        }

        /*selecting/deselecting news */
        $scope.selectNews= function(event,news){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalNewsSelected = 1;
            news.selected = !news.selected;
            if($scope.model.newsCollection.length == 1){
                if(news.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalNewsSelected = 0;
                }
                if(news.canDelete == 0){
                    canDeleteAll = false;
                }
                if(news.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.newsCollection.length > 1){
                totalNewsSelected = 0;
                for(var i=0; i<$scope.model.newsCollection.length; i++){
                    var news = $scope.model.newsCollection[i];
                    if(news.selected == true){
                        totalNewsSelected++;
                        if(news.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(news.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalNewsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteNews = true;
                    if(totalNewsSelected == $scope.model.newsCollection.length){
                        $scope.model.allNewsSelected = true;
                    }
                    else{
                        $scope.model.allNewsSelected = false;
                    }
                }
                if(totalNewsSelected == 1 && canEditAll == true){
                    $scope.model.canEditNews = true;
                }
                else{
                    $scope.model.canEditNews = false;
                }
            }
            else{
                $scope.model.canEditNews = false;
                $scope.model.canDeleteNews = false;
                $scope.model.allNewsSelected = false;
            }
        }

        /* show the form to Create/Edit News */
        $scope.showNewsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showNewsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showNewsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    newId : $scope.model.selectedNew.id
                };
                searchParametersCollection.currentLanguage = $scope.model.selectedLanguage.value;
                newsFact.getNewsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedNew = response.data.newData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedNew.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedNew.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedNew.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedNew.featured_image_url,
                            id : $scope.model.selectedNew.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedNew.content);

                    if($scope.model.templatesCollection.length > 0){
                        for(var i=0; i<$scope.model.templatesCollection.length; i++){
                            if($scope.model.selectedNew.template.template_slug == $scope.model.templatesCollection[i].template_slug){
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


                if(suffix == 'news-type'){
                    $('#news-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.newsCurrentResultStart = 0;
            $scope.model.newsCurrentResultLimit = 0;
            $scope.model.newsCurrentPage = ($scope.model.newsCurrentPage*1);
            $scope.model.newsCurrentPageSize = ($scope.model.newsCurrentPageSize*1);

            if($scope.model.newsCollection.length > 0){
                $scope.model.newsCurrentResultStart = ($scope.model.newsCurrentPage - 1) * $scope.model.newsCurrentPageSize + 1;
                $scope.model.newsCurrentResultLimit = ($scope.model.newsCurrentPageSize * $scope.model.newsCurrentPage);
                if($scope.model.newsCollection.length < ($scope.model.newsCurrentPageSize * $scope.model.newsCurrentPage)){

                    $scope.model.newsCurrentResultLimit = $scope.model.newsCollection.length;
                }

                var totalNews = Math.ceil($scope.model.newsCollection.length / $scope.model.newsCurrentPageSize);
                $scope.model.newsNewsCollection = [];
                if(totalNews > 0){
                    for(var i=1; i<=totalNews; i++){
                        $scope.model.newsNewsCollection.push(i);
                    }
                }
                else{
                    $scope.model.newsNewsCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateNewsForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedNew.title != null &&
                        alfaNumericRegExpr.test($scope.model.selectedNew.title)){
                        $scope.model.selectedNew.url_slug = slugify($scope.model.selectedNew.title);
                    }
                    else{
                        $scope.model.selectedNew.url_slug = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedNew.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                        $scope.model.selectedNew.published_date = null;
                        $scope.model.publishedDateHasError = false;
                    }
                    break;
            }
        }

        /*update form views section*/
        $scope.updateFormSection = function(section){
            $scope.model.formActiveView = section;
        }


      $scope.changeLoadDataOrSavedInfoByLanguage = function (event, from, language) {
        $scope.model.selectedLanguage = language;
        switch (from) {
          case 'list':
            $scope.model.newsCollection = [];
            $scope.getNews()
            break;
          case 'form':
            $scope.showNewsForm();
            break;
        }
      }


        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.model.newsCollection = [];
            $scope.model.newsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.newsCurrentPageSize = 20;
            $scope.model.newsCurrentPage = 1;
            $scope.model.newsPagesCollection = [];
            $scope.model.newsPagesCollection.push(1);
            $scope.model.newsCurrentResultStart = 0;
            $scope.model.newsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allNewsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showNewsForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.templatesCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedNew = null;

            $scope.clearNewsForm();
            newsFact.loadInitialsData($scope, function(response){

                    $scope.model.newsCollection = response.data.initialsData.newsDataCollection;
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
                    var showNewsForm = response.data.initialsData.showNewsForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsNewsForm();

                    if(showNewsForm == true){
                        $scope.createNews();
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
    angular.module('BncBackend.newsController').controller('newsCtrller',newsCtrller);
})();
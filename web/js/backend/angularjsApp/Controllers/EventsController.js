/*
 * File for handling controllers for Backend Events Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.eventsController', ['BncBackend.eventsFactory']);

    /* Declaring controllers functions for this module */
    angular.module('BncBackend.eventsController').controller('eventsCtrller',
    ['$scope','$filter','$timeout','eventsFact','uiCalendarConfig',
    function($scope, $filter, $timeout,eventsFact, uiCalendarConfig){
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
        /**/
        function compareDates(start_date, end_date, comparison){
            var response = false;

            /*asumming the dates are in the format dd/MM/yyyy - HH:mm*/
            var start_date_collection = start_date.split(' ');
            if(start_date_collection.length == 2){
                var sdate_collection = start_date_collection[0].split('/');
                var stime_collection = start_date_collection[1].split(':');
                if(sdate_collection.length == 3 && stime_collection.length == 2){
                    var end_date_collection = end_date.split(' ');
                    if(end_date_collection.length == 2){
                        var edate_collection = end_date_collection[0].split('/');
                        var etime_collection = end_date_collection[1].split(':');

                        /*COMPARISON*/
                        var start_date_number = sdate_collection[2]+sdate_collection[1]+sdate_collection[0]+stime_collection[0]+stime_collection[1];
                        var enda_date_number = edate_collection[2]+edate_collection[1]+edate_collection[0]+etime_collection[0]+etime_collection[1];
                        switch(comparison){
                            case '<=':
                                if(start_date_number <= enda_date_number){
                                    response = true;
                                }
                                break;
                        }
                    }
                }
            }

            return response;
        }

        /* clear errors of the form */
        $scope.clearErrorsEventsForm = function(){
            $scope.model.titleHasError = false;
            $scope.model.urlSlugHasError = false;
            $scope.model.publishedDateHasError = false;
            $scope.model.startDateHasError = false;
            $scope.model.endDateHasError = false;
            $scope.model.placeHasError = false;
        }

        /* clear form values */
        $scope.clearEventsForm = function(){
            $scope.model.formActiveView = 'general-info';
            $scope.model.selectedEvent = {};
            var start_date = new Date();
            var start_day = start_date.getDate();
            var start_month = start_date.getMonth();
            var start_year = start_date.getFullYear();
            var start_hour = start_date.getHours();
            var start_minute = start_date.getMinutes();
            var end_date = new Date(start_year, start_month, start_day, start_hour, start_minute + 30);
            $scope.model.selectedEvent.start_date = dateFilter(start_date, 'dd/MM/yyyy HH:mm');
            $scope.model.selectedEvent.end_date = dateFilter(end_date, 'dd/MM/yyyy HH:mm');
            $scope.model.featureImage = {};
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
            $('#textEditor').code(null);

        }

        /* create events */
        $scope.createEvents = function()
        {
            if($scope.model.canCreateEvents == true)
            {
                $scope.model.createAction = true;
                $scope.clearEventsForm();
                $scope.model.formActiveView = 'general-info';
                $scope.showEventsForm();
            }
        }

        function checkPublishedDate(){
            var proceed = true;
            if($scope.model.selectedEvent.published_date != null){
                if(!dateRegExpress.test($scope.model.selectedEvent.published_date)){
                    proceed = false;
                }
                else{
                    var publishedDate = $scope.model.selectedEvent.published_date;
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

        /* delete events */
        $scope.deleteEvents = function(events_id)
        {
            var proceed = true;
            if(typeof events_id == 'string' && !$scope.model.canDeleteEvents){
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
                            var eventsIdCollection = [];
                            if(typeof events_id == 'string'){
                                if($scope.model.eventsCollection.length > 0){
                                    for(var i=0; i<$scope.model.eventsCollection.length; i++){
                                        if($scope.model.eventsCollection[i].selected != undefined &&
                                            $scope.model.eventsCollection[i].selected == true)
                                        {
                                            eventsIdCollection.push($scope.model.eventsCollection[i].id);
                                        }
                                    }
                                }
                            }
                            else{
                                eventsIdCollection.push(events_id);
                            }
                            var data = {
                                eventsId: eventsIdCollection
                            };
                            eventsFact.deleteEvents($scope, data);
                        }
                    });
            }
        }

        /* edit events */
        $scope.editEvents = function(event)
        {
            $scope.model.createAction = false;
            $scope.clearEventsForm();
            $scope.model.selectedEvent = event;
            $scope.showEventsForm();
        }

        /* change the view mode of the events data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.eventsCollection = [];
            $scope.model.activeView = option;
            if(option == 'calendar_list'){
                $timeout(function(){
                    $('.fc-button-group').find('button').each(function(){
                        $(this).addClass('btn');
                    });
                },500);
            }
            $scope.getEvents();
        }

        /* get the Events Collection */
        $scope.getEvents = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                    $scope.model.showEventsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            eventsFact.getEventsData($scope,searchParametersCollection, function(response){
                    $scope.model.eventsCollection = response.data.eventsDataCollection;
                    $scope.updateCalendarSources();
                    $scope.updatePaginationValues();
                    $scope.toggleDataLoader();
                },
                function(response){
                    $scope.updateCalendarSources();
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
                $scope.model.canCreateEvents = false;
                $scope.model.canEditEvents = false;
                $scope.model.canDeleteEvents = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateEvents = true;
                $scope.model.canEditEvents = false;
                $scope.model.canDeleteEvents = false;
                $scope.model.allEventsSelected = false;
                $scope.model.selectedEvent = null;
            }

        }

        /* Hide the CRUD form */
        $scope.hideEventsForm = function()
        {
            $scope.model.showEventsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getEvents();

            $scope.goToTop();
        }

        /*init calendar configs*/
        $scope.initCalendarConfigs = function(){

            $scope.uiConfig = {
                calendar:{
                    editable: true,
                    header:{
                        left: 'prev,next,today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    dayNames : ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                    dayNamesShort : ["Dom", "Lun", "Mar", "Mier", "Jue", "Vie", "Sab"],
                    monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre",
                        "Octubre", "Noviembre", "Diciembre"],
                    monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep",
                        "Oct", "Nov", "Dic"]
                }
            };
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.eventsCurrentPage = 1;
            $scope.model.eventsPagesCollection = [];
            $scope.model.eventsPagesCollection.push(1);
            $scope.model.eventsCurrentResultStart = 0;
            $scope.model.eventsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }

        /* save events data */
        $scope.saveEventsData = function(option)
        {
            if($scope.model.processingData == false){
                $scope.model.processingData = true;
                $scope.toggleDataLoader();
                var canProceed = true;
                $scope.clearErrorsEventsForm();

                if($scope.model.selectedEvent.title_es == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedEvent.title_es) ||
                    $scope.model.selectedEvent.url_slug_es == null ||
                    !alfaNumericRegExpr.test($scope.model.selectedEvent.url_slug_es) ||
                    $scope.model.selectedEvent.start_date == null
                    || $scope.model.selectedEvent.end_date == null ||
                    !compareDates($scope.model.selectedEvent.start_date,
                        $scope.model.selectedEvent.end_date, '<=') ||
                    !checkPublishedDate()){
                    canProceed = false;

                    if($scope.model.selectedEvent.title_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedEvent.title_es)){
                        $scope.model.titleHasError = true;
                    }

                    if($scope.model.selectedEvent.url_slug_es == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedEvent.url_slug_es)){
                        $scope.model.urlSlugHasError = true;
                    }

                    if($scope.model.selectedEvent.start_date == null
                        || $scope.model.selectedEvent.end_date == null ||
                        !compareDates($scope.model.selectedEvent.start_date,
                            $scope.model.selectedEvent.end_date, '<=')){
                        $scope.model.startDateHasError = true;
                        $scope.model.endDateHasError = true;
                    }

                    if(!checkPublishedDate()){
                        $scope.model.publishedDateHasError = true;
                    }
                }

                if(canProceed){
                    $scope.model.selectedEvent.content_es = $('#textEditor').code();
                    if($scope.model.selectedPostStatus != null){
                        $scope.model.selectedEvent.post_status_id = $scope.model.selectedPostStatus.id;
                    }
                    if($scope.model.featureImage != null){
                        $scope.model.selectedEvent.featured_image_id = $scope.model.featureImage.id;
                    }
                    if($scope.model.selectedCategoriesCollection != null &&
                        $scope.model.selectedCategoriesCollection.length > 0){
                        $scope.model.selectedEvent.selected_categories_id = [];
                        for(var i=0;i<$scope.model.selectedCategoriesCollection.length;i++){
                            $scope.model.selectedEvent.selected_categories_id.push($scope.model.selectedCategoriesCollection[i].id)
                        }
                    }
                    $scope.model.selectedEvent.start_date = tranformToInternationalDateTime($scope.model.selectedEvent.start_date);
                    $scope.model.selectedEvent.end_date = tranformToInternationalDateTime($scope.model.selectedEvent.end_date);

                    var eventsData = {eventData: $scope.model.selectedEvent};
                    var action = $scope.model.createAction == true ? 'create' : 'edit';

                    eventsFact.saveEventsData($scope, eventsData, option, action);
                }
                else{
                    $scope.model.processingData = false;
                    $scope.toggleDataLoader();
                    toastr.options.timeOut = 3000;
                    toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                }

            }
        }

        /* search Events through Search Input Field */
        $scope.searchEvents = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue))
                || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getEvents();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getEvents();
            }
        }

        /* selecting/deselecting all events */
        $scope.selectAllEvents = function(event){
            var canDeleteAll = true;
            $scope.model.allEventsSelected = !$scope.model.allEventsSelected;
            if(!$scope.model.allEventsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.eventsCollection.length; i++){
                $scope.model.eventsCollection[i].selected = $scope.model.allEventsSelected;
                if($scope.model.allEventsSelected == true && $scope.model.eventsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteEvents = canDeleteAll;
        }

        /*selecting/deselecting events */
        $scope.selectEvents= function(event,events){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalEventsSelected = 1;
            events.selected = !events.selected;
            if($scope.model.eventsCollection.length == 1){
                if(events.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalEventsSelected = 0;
                }
                if(events.canDelete == 0){
                    canDeleteAll = false;
                }
                if(events.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.eventsCollection.length > 1){
                totalEventsSelected = 0;
                for(var i=0; i<$scope.model.eventsCollection.length; i++){
                    var events = $scope.model.eventsCollection[i];
                    if(events.selected == true){
                        totalEventsSelected++;
                        if(events.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(events.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalEventsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteEvents = true;
                    if(totalEventsSelected == $scope.model.eventsCollection.length){
                        $scope.model.allEventsSelected = true;
                    }
                    else{
                        $scope.model.allEventsSelected = false;
                    }
                }
                if(totalEventsSelected == 1 && canEditAll == true){
                    $scope.model.canEditEvents = true;
                }
                else{
                    $scope.model.canEditEvents = false;
                }
            }
            else{
                $scope.model.canEditEvents = false;
                $scope.model.canDeleteEvents = false;
                $scope.model.allEventsSelected = false;
            }
        }

        /* show the form to Create/Edit Events */
        $scope.showEventsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showEventsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showEventsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    eventId : $scope.model.selectedEvent.id
                };
                eventsFact.getEventsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedEvent = response.data.eventData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedEvent.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedEvent.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedEvent.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedEvent.featured_image_url,
                            id : $scope.model.selectedEvent.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedEvent.content_es);

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

        /**/
        function tranformToInternationalDateTime(date){
            /*supousing the Date is in format dd/MM/yyyy HH:mm */
            var dateCollection = date.split('/');
            if(dateCollection.length == 3){
                date = dateCollection[1]+'/'+dateCollection[0]+'/'+dateCollection[2];
            }
            return date;
        }

        function transformToCalendarDateTime(date){
            /*supousing the Date is in format dd/MM/yyyy HH:mm */
            var dateTimeCollection = date.split(' ');
            if(dateTimeCollection.length == 2){
                var timeCollection = dateTimeCollection[1].split(':');
                var dateCollection = dateTimeCollection[0].split('/');
                if(dateCollection.length == 3){
                    date = new Date(dateCollection[2], (dateCollection[1]-1), dateCollection[0],timeCollection[0],timeCollection[1]);
                }
            }
            return date;
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


                if(suffix == 'events-type'){
                    $('#events-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.eventsCurrentResultStart = 0;
            $scope.model.eventsCurrentResultLimit = 0;
            $scope.model.eventsCurrentPage = ($scope.model.eventsCurrentPage*1);
            $scope.model.eventsCurrentPageSize = ($scope.model.eventsCurrentPageSize*1);

            if($scope.model.eventsCollection.length > 0){
                $scope.model.eventsCurrentResultStart = ($scope.model.eventsCurrentPage - 1) * $scope.model.eventsCurrentPageSize + 1;
                $scope.model.eventsCurrentResultLimit = ($scope.model.eventsCurrentPageSize * $scope.model.eventsCurrentPage);
                if($scope.model.eventsCollection.length < ($scope.model.eventsCurrentPageSize * $scope.model.eventsCurrentPage)){

                    $scope.model.eventsCurrentResultLimit = $scope.model.eventsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.eventsCollection.length / $scope.model.eventsCurrentPageSize);
                $scope.model.eventsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.eventsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.eventsPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        /*update Calendar Sources*/
        $scope.updateCalendarSources = function(){
            $scope.events = [];
            if($scope.model.eventsCollection.length > 0){
                for(var i=0; i<$scope.model.eventsCollection.length; i++){
                    var event = $scope.model.eventsCollection[i];
                    event.title = $scope.model.eventsCollection[i].title_es;
                    if(event.start_date != undefined){
                        event.start = transformToCalendarDateTime(event.start_date);
                    }
                    if(event.end_date != undefined){
                        event.end = transformToCalendarDateTime(event.end_date);
                    }
                    $scope.events.push(event);
                }
            }
            $scope.eventSources = {
                events: $scope.events,
                color: '#009dc7',
                textColor: 'white'
            };
            if( uiCalendarConfig.calendars.myCalendar != undefined){
                uiCalendarConfig.calendars.myCalendar.fullCalendar('removeEvents');
                uiCalendarConfig.calendars.myCalendar.fullCalendar('addEventSource', $scope.eventSources);
            }

        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.updateEventsForm = function(event, field, element)
        {
            switch(field){
                case 'title':
                    if($scope.model.selectedEvent.title_es != null &&
                        alfaNumericRegExpr.test($scope.model.selectedEvent.title_es)){
                        $scope.model.selectedEvent.url_slug_es = slugify($scope.model.selectedEvent.title_es);
                    }
                    else{
                        $scope.model.selectedEvent.url_slug_es = null;
                    }
                    break;
                case 'status':
                    $scope.model.selectedPostStatus = element;
                    if(element != undefined && element.tree_slug == 'generic-post-status-published'){

                        $scope.model.selectedEvent.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    else{
                        $scope.model.selectedEvent.published_date = null;
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
            $scope.model.eventsCollection = [];
            $scope.model.eventsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'calendar_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.eventsCurrentPageSize = 20;
            $scope.model.eventsCurrentPage = 1;
            $scope.model.eventsPagesCollection = [];
            $scope.model.eventsPagesCollection.push(1);
            $scope.model.eventsCurrentResultStart = 0;
            $scope.model.eventsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allEventsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showEventsForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedEvent = null;

            $scope.model.calendarLoaded = false;
            $scope.model.calendarUiConfig = null;
            $scope.events = [];
            $scope.eventSources = [];
            $scope.initCalendarConfigs();

            $scope.clearEventsForm();
            eventsFact.loadInitialsData($scope, function(response){
                    $scope.model.eventsCollection = response.data.initialsData.eventsDataCollection;
                    $scope.updateCalendarSources();
                    $scope.model.postStatusCollection = response.data.initialsData.postStatusDataCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        $scope.model.selectedPostStatus = $scope.model.postStatusCollection[0];
                    }
                    $scope.model.bncDomain = response.data.initialsData.bncDomain;
                    if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                        $scope.model.bncDomain = '(www.tudominio.com)';
                    }
                    var showEventsForm = response.data.initialsData.showEventsForm;

                    $scope.updatePaginationValues();
                    $scope.clearErrorsEventsForm();

                    if(showEventsForm == true){
                        $scope.createEvents();
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
    }]);
})();
/*
 * File for handling controllers for Backend Events Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend.eventsController', ['BncFrontend.eventsFactory']);

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.eventsController').controller('eventsCtrller',
    ['$scope','$filter','$timeout','$sce','eventsFact','uiCalendarConfig',
    function($scope, $filter, $timeout,$sce,eventsFact, uiCalendarConfig){
        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */
        /**/





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

        /*update form views section*/
        $scope.updateFormSection = function(section){
            $scope.model.formActiveView = section;
        }

        /*change View*/
        $scope.changeActiveView = function(view){
            $scope.model.activeView = view;
        }

        /*watching changes on Blocks Collection*/
        $scope.$watch('model.blocksCollection', function(newValue, oldValue) {
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            $scope.model.opinionsCollection = [];
            if(newValue != null && newValue != undefined && newValue.length > 0){
                for(var i=0; i<newValue.length; i++){
                    if(newValue[i].block_type_tree_slug == 'content-block-type-media-image'){
                        var imagesCollection = newValue[i].elements;
                        if(imagesCollection.length > 0){
                            for(var j=0; j<imagesCollection.length; j++){
                                $scope.model.imagesCollection.push(imagesCollection[j]);
                            }
                        }
                    }
                    else if(newValue[i].block_type_tree_slug == 'content-block-type-media-video'){
                        var videosCollection = newValue[i].elements;
                        if(videosCollection.length > 0){
                            for(var j=0; j<videosCollection.length; j++){
                                $scope.model.videosCollection.push(videosCollection[j]);
                            }
                        }
                    }
                    else if(newValue[i].block_type_tree_slug == 'content-block-type-opinion'){
                        var opinionsCollection = newValue[i].elements;
                        if(opinionsCollection.length > 0){
                            for(var j=0; j<opinionsCollection.length; j++){
                                opinionsCollection[j].html_filtered_content_es = $sce.trustAsHtml(opinionsCollection[j].content_es);
                                $scope.model.opinionsCollection.push(opinionsCollection[j]);
                            }
                        }
                    }
                }
            }
        });




        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.model.eventsCollection = [];
            $scope.model.blocksCollection = [];
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            $scope.model.opinionsCollection = [];
            $scope.model.activeView = 'calendar_list';

            /*form view variables*/
            $scope.model.formActiveView = 'general-info';
            $scope.model.loadingData = false;
            $scope.model.processingData = false;
            $scope.model.selectedEvent = null;

            $scope.model.calendarLoaded = false;
            $scope.model.calendarUiConfig = null;
            $scope.events = [];
            $scope.eventSources = [];
            $scope.initCalendarConfigs();

            $timeout(function(){
                $('.fc-button-group').find('button').each(function(){
                    $(this).addClass('btn');
                });
            },500);

            eventsFact.getEventsData({
                    start: null,
                    end: null
                },
                function(response){

                    if(response.data.singleResult != undefined && response.data.singleResult != null){
                        $scope.model.selectedEvent = response.data.eventsDataCollection;
                        $scope.model.selectedEvent.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedEvent.content_es);
                        $scope.model.activeView = 'event_data';
                    }
                    else{
                        $scope.model.eventsCollection = response.data.eventsDataCollection;
                        $scope.updateCalendarSources();
                        $scope.model.total = response.data.totalEvents;
                    }

                },
                function(response){

                });
            /*eventsFact.loadInitialsData($scope, function(response){
                    $scope.model.eventsCollection = response.data.initialsData.eventsDataCollection;
                    $scope.updateCalendarSources();

                },
                function(response){

                });*/
        }
        init();
    }]);
})();
/*
 * Main file Angular App for BncBackend
 * */
(function () {

    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend',
        [
            'ngAnimate',
            'ngTouch',
            'ui.calendar',
            'ui.bootstrap',
            'BncBackend.infoSummaryDirective',
            'BncBackend.mediaSelectorDirective',
            'BncBackend.profileDirective',
            'BncBackend.taxonomySelectorDirective',
            'BncBackend.blockSelectorDirective',
            'BncBackend.commentHandlerDirective',
            'BncBackend.commentNotifierDirective',
            'BncBackend.taxonomyFilterDirective',

            'BncBackend.dashboardController',
            'BncBackend.taxonomyController',
            'BncBackend.settingController',
            'BncBackend.mediaController',
            'BncBackend.newsController',
            'BncBackend.publicationsController',
            'BncBackend.eventsController',
            'BncBackend.pagesController',
            'BncBackend.opinionsController',
            'BncBackend.commentsController',
            'BncBackend.pagesController',
            'BncBackend.partnersController',
            'BncBackend.jewelsController',
            'BncBackend.historicalMomentsController',
            'BncBackend.compositionController',
            'BncBackend.repertoryController',
            'BncBackend.awardsController',
            'BncBackend.usersController'
        ]
    );


    /* Declare config function for configs elements for app */
    function BncConfigs ($locationProvider, $interpolateProvider ) {

        $locationProvider.html5Mode(true);

        /*interpolating symbols*/
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');


        /*
            Declaring Jquery plugins initializations
        */
        var getViewPort = function(){
            var e = window,
                a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }
            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        }
        var blockUI = function(options) {
            options = $.extend(true, {}, options);
            var html = '';
            if (options.animate) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
            } else if (options.iconOnly) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""></div>';
            } else if (options.textOnly) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            } else {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            }

            if (options.target) { // element blocking
                var el = $(options.target);
                if (el.height() <= ($(window).height())) {
                    options.cenrerY = true;
                }
                el.block({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    centerY: options.cenrerY !== undefined ? options.cenrerY : false,
                    css: {
                        top: '10%',
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            } else { // page blocking
                $.blockUI({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    css: {
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            }
        }
        var toggleFullScreen = function() {
            if (!document.fullscreenElement &&    // alternative standard method
                !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        }
        var handleFullScreenMode = function() {
            $('#trigger_fullscreen').click(function() {
                toggleFullScreen();
            });
        }
        /*var handleCalendar = function(){
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            $('#events-calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar
                events: [
                    {
                        title: 'All Day Event',
                        start: new Date(y, m, 1)
                    },
                    {
                        title: 'Long Event',
                        start: new Date(y, m, d-5),
                        end: new Date(y, m, d-2)
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: new Date(y, m, d-3, 16, 0),
                        allDay: false
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: new Date(y, m, d+4, 16, 0),
                        allDay: false
                    },
                    {
                        title: 'Meeting',
                        start: new Date(y, m, d, 10, 30),
                        allDay: false
                    },
                    {
                        title: 'Lunch',
                        start: new Date(y, m, d, 12, 0),
                        end: new Date(y, m, d, 14, 0),
                        allDay: false
                    },
                    {
                        title: 'Birthday Party',
                        start: new Date(y, m, d+1, 19, 0),
                        end: new Date(y, m, d+1, 22, 30),
                        allDay: false
                    },
                    {
                        title: 'Click for Google',
                        start: new Date(y, m, 28),
                        end: new Date(y, m, 29),
                        url: 'http://google.com/'
                    }
                ]
            });
        }*/
        var handleDatePicker = function(){
            $('.date-picker').datepicker({
                orientation: "left",
                autoclose: true,
                calendarWeeks: true,
                format: "dd/mm/yyyy",
                language : 'es'
            });
        }
        var handleDatetimePicker = function () {

            $(".datetime-picker").datetimepicker({
                autoclose: true,
                orientation: "left",
                format: "dd/mm/yyyy hh:ii",
                pickerPosition: "bottom-left",
                language : 'es'
            });
        }
        var handleSummernote = function () {
            $('#textEditor').summernote({
                height: 500,
                lang: 'es-ES'/*
                airMode: true*/
            });
        }

        /*handle window scroll*/
        $(window).scroll(function(){
            if ($(window).scrollTop() > 60) {
                $("body").addClass("page-on-scroll");
            } else {
                $("body").removeClass("page-on-scroll");
            }
        });
        /*handle window load*/
        $(window).on('load', function() {
            /*Preloader*/
            $('.window-loader').delay(350).fadeOut(800);
        });
        /*handling slim-scroll*/
        var slimScrollContainerHeight = Metronic.getViewPort().height - 60;
        $('.main-menu-options-container').slimScroll({
            width: '100%',
            height: slimScrollContainerHeight+'px',
            size: '5px',
            position: 'right',
            color: '#6c7b88',
            alwaysVisible: false,
            distance: '10px',
            railVisible: true,
            railColor: '#666',
            railOpacity: 0.3,
            wheelStep: 10,
            allowPageScroll: false,
            disableFadeOut: false
        });
        /*handling portlet fullscreen*/
        $('body').on('click', '.portlet > .portlet-title .fullscreen', function(e) {
            e.preventDefault();
            var portlet = $(this).closest(".portlet");
            if (portlet.hasClass('portlet-fullscreen')) {
                $(this).removeClass('on');
                portlet.removeClass('portlet-fullscreen');
                $('body').removeClass('page-portlet-fullscreen');
                portlet.children('.portlet-body').css('height', 'auto');
            } else {
                var height = Metronic.getViewPort().height -
                    portlet.children('.portlet-title').outerHeight() -
                    parseInt(portlet.children('.portlet-body').css('padding-top')) -
                    parseInt(portlet.children('.portlet-body').css('padding-bottom'));

                $(this).addClass('on');
                portlet.addClass('portlet-fullscreen');
                $('body').addClass('page-portlet-fullscreen');
                portlet.children('.portlet-body').css('height', height);
            }
        });
        /*handling main menu toggle class*/
        $('.open-menu, .close-menu').click(function (e) {
            $('body').toggleClass('page-quick-sidebar-open');
        });
        /*handling I-CHECKS components*/
        $('.icheck').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });
        /*handling date-pickers*/
        handleDatePicker();
        /*handling datetime-pickers*/
        handleDatetimePicker();
        /*handle bootstrap dropdown selector*/
        $('.bs-select').selectpicker({
            iconBase:'fa',
            tickIcon:'fa-check'
        });
        /*handle elements tooltip*/
        $('.tooltips').tooltip();
        /*handle Full Screen Mode*/
        handleFullScreenMode();
        /*handle Calendar*/
        /*handleCalendar();*/
        /*handle SUMMER-NOTE*/
        handleSummernote();

    }



    /* Declare association between configs function and BncBackend Module  */
    angular.module('BncBackend').config(BncConfigs);


})();

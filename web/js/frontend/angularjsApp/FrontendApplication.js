/*
 * Main file Angular App for BncBackend
 * */
(function () {

    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend',
        [
            'ngAnimate',
            'ngTouch',
            'ui.bootstrap',
            'ui.calendar',
            'BncFrontend.blockHandlerDirective',
            'BncFrontend.videoHandlerDirective',
            'BncFrontend.commentHandlerDirective',
            'BncFrontend.programDirective',
            'BncFrontend.reserveDirective',

            'BncFrontend.newsController',
            'BncFrontend.eventsController',
            'BncFrontend.publicationsController',
            'BncFrontend.repertoryController',
            'BncFrontend.partnersController',
            'BncFrontend.pagesController',
            'BncFrontend.companyPageController',
            'BncFrontend.jewelsController',
            'BncFrontend.dancersPageController',
            'BncFrontend.compositionController',
            'BncFrontend.aliciaPageController',
            'BncFrontend.defaultPageController',
            'BncFrontend.showController'
        ]
    );


    /* Declare config function for configs elements for app */
    function BncFrontendConfigs ($locationProvider, $interpolateProvider ) {

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
        var handleWowAnimations = function(){
            new WOW().init();
        }
        var handleGoTop = function () {
            var offset = 300;
            var duration = 500;

            if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {  // ios supported
                $(window).bind("touchend touchcancel touchleave", function(e){
                    if ($(this).scrollTop() > offset) {
                        $('.scroll-to-top').fadeIn(duration);
                    } else {
                        $('.scroll-to-top').fadeOut(duration);
                    }
                });
            } else {  // general
                $(window).scroll(function() {
                    if ($(this).scrollTop() > offset) {
                        $('.scroll-to-top').fadeIn(duration);
                    } else {
                        $('.scroll-to-top').fadeOut(duration);
                    }
                });
            }

            $('.scroll-to-top').click(function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, duration);
                return false;
            });
        };


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
        var slimScrollContainerHeight = getViewPort().height - 60;
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
        /*$('.page-body').slimScroll({
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
        });*/
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
        /*handle bootstrap dropdown selector*/
        $('.bs-select').selectpicker({
            iconBase:'fa',
            tickIcon:'fa-check'
        });
        /*handle elements tooltip*/
        $('.tooltips').tooltip();
        /*handle Full Screen Mode*/
        handleFullScreenMode();
        /*handling efects over search buttons*/
        $('.general-search-btn').click(function(e){
            if($('.general-search-input').hasClass('increase-animated-search-field')){
                $('.general-search-input').removeClass('increase-animated-search-field');
                $('.general-search-input').addClass('decrease-animated-search-field');
            }
            else{
                $('.general-search-input').removeClass('decrease-animated-search-field');
                $('.general-search-input').addClass('increase-animated-search-field');
            }
        });
        /*handling WOW animations*/
        handleWowAnimations();
        /*handling Go Top */
        handleGoTop();



    }



    /* Declare association between configs function and BncFrontend Module  */
    angular.module('BncFrontend').config(BncFrontendConfigs);


})();

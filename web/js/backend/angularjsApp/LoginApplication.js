/*
 * Main file Angular App for BncLogin
 * */
(function () {

    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncLogin',
        [
            'ngAnimate',
            'ngTouch',
            'ui.bootstrap'
        ]
    );


    /* Declare config function for configs elements for app */
    function BncLoginConfigs ($locationProvider, $interpolateProvider ) {

        $locationProvider.html5Mode(true);

        /*interpolating symbols*/
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');


        /*handle window load*/
        $(window).on('load', function() {
            /*Preloader*/
            $('.window-loader').delay(350).fadeOut(800);
        });
        /*Background Slider*/
        $.backstretch([
            "images/backend/login/bnc-login-background-1.jpg",
            "images/backend/login/bnc-login-background-2.jpg"
        ], {
            fade: 1000,
            duration: 8000
        });

    }



    /* Declare association between configs function and BncLogin Module  */
    angular.module('BncLogin').config(BncLoginConfigs);


})();

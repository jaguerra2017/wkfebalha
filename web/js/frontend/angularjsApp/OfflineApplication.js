/*
 * Main file Angular App for BncOfflineScreen
 * */
(function () {

    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncOfflineScreen',
        [
            'ngAnimate',
            'ngTouch',
            'ui.bootstrap'
        ]
    );


    /* Declare config function for configs elements for app */
    function BncOfflineScreenConfigs ($locationProvider, $interpolateProvider ) {

        $locationProvider.html5Mode(true);

        /*interpolating symbols*/
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');

        /*handle window load*/
        $(window).on('load', function() {
            /*Preloader*/
            $('.window-loader').delay(350).fadeOut(800);
        });
        /*handling the countdown*/
        $.ajax({
            url:Routing.generate('site_offline_view_data'),
            type:'POST',
            success:function(response){
                var launchDate = response.launchDate;
                launchDate = new Date(launchDate);
                $('#defaultCountdown').countdown({until: launchDate});
                $('#year').text(launchDate.getFullYear());

            }
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



    /* Declare association between configs function and BncOfflineScreen Module  */
    angular.module('BncOfflineScreen').config(BncOfflineScreenConfigs);


})();

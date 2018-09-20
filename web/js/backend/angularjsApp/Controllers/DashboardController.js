/*
 * File for handling controllers for Backend Dashboard Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.dashboardController', ['BncBackend.dashboardFactory']);


    /* Controller for handling Dashboard functions */
    function dashboardCtrller($scope, $filter, dashboardFact){

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



        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /**/
            $scope.model.genericPostId = null;
        }
        init();

    }


    /* Declaring controllers functions for this module */
    angular.module('BncBackend.dashboardController').controller('dashboardCtrller',dashboardCtrller);
})();
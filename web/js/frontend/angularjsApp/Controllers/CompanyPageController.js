/*
 * File for handling controllers for Backend Pages Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.companyPageController', ['BncFrontend.companyPageFactory']).controller('companyPageCtrller',
        ['$scope','$filter','$sce','$timeout','companyPageFact',function($scope, $filter, $sce, $timeout, companyPageFact){

            /*
             * Global variables
             *
             * */


            /*
             * Operations Functions
             *
             * */
            /* load more HM */
            $scope.loadMoreHistoricalMoments = function(){
                if($scope.model.hm_limitTo < $scope.model.historicalMomentsCollection.length){
                    $scope.model.hm_limitTo = ($scope.model.hm_limitTo + 5);
                }
            }

            /* function on scope for go ahead to top */
            $scope.goToTop = function(element_id)
            {
                if(element_id == undefined || element_id == null){
                    element_id = '.navbar-fixed-top';
                }
                else{
                    element_id = '#'+element_id;
                }
                var pageHeading = $(element_id);
                $('html, body').animate({scrollTop: (pageHeading.offset().top - 180)}, 1000);
            }

            /* toggle data-loading message */
            $scope.toggleDataLoader = function()
            {
                $scope.model.loadingData = !$scope.model.loadingData;
            }





            function init(){
                /*generals variables*/
                $scope.model = {};
                $scope.success = false;
                $scope.error = false;
                /*list view variables*/
                $scope.model.historicalMomentsCollection = [];
                $scope.model.jewelsCollection = [];
                $scope.model.blocksCollection = [];
                /*form view variables*/
                $scope.model.loadingData = false;
                $scope.model.showPagesForm = false;
                $scope.model.processingData = false;
                $scope.model.selectedPage = null;
                $scope.model.hm_limitTo = 5;

                companyPageFact.getPageData({
                        load_historical_moments: true,
                        load_jewels: true
                    },
                    function(response){
                        if(response.data.singleResult != undefined && response.data.singleResult != null){
                            $scope.model.selectedPage = response.data.pagesDataCollection;
                            $scope.model.selectedPage.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedPage.content_es);
                            $scope.model.historicalMomentsCollection = response.data.historicalMomentsCollection;
                            $scope.model.jewelsCollection = response.data.jewelsCollection;

                            if(response.data.current_tag != undefined && response.data.current_tag != null){
                                $timeout(function(){
                                    $scope.goToTop(response.data.current_tag);
                                },1000);
                            }
                        }
                    },
                    function(response){

                    });
            }
            init();
        }]);
})();
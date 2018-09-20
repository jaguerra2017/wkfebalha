/*
 * File for handling controllers for Backend Pages Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.pagesController', ['BncFrontend.pagesFactory']).controller('pagesCtrller',
    ['$scope','$filter','$sce','$timeout','pagesFact',function($scope, $filter, $sce, $timeout, pagesFact){

        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */

        /* get the Pages Collection */
        $scope.getPages = function()
        {
            $scope.toggleDataLoader();
            $scope.model.start = ($scope.model.end + 1);
            $scope.model.end = ($scope.model.end + 10);
            pagesFact.getPagesData({
                start: $scope.model.start,
                end: $scope.model.end
            },
            function(response){
                var tempCollection = response.data.pagesDataCollection;
                if(tempCollection.length > 0){
                    for(var i=0; i<tempCollection.length; i++){
                        $scope.model.pagesCollection.push(tempCollection[i]);
                    }
                }
                $scope.model.total = response.data.totalPosts;
                $scope.toggleDataLoader();
            },
            function(response){
                $scope.toggleDataLoader();
            });
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
            $scope.model.pagesCollection = [];
            $scope.model.blocksCollection = [];
            /*form view variables*/
            $scope.model.loadingData = false;
            $scope.model.showPagesForm = false;
            $scope.model.processingData = false;
            $scope.model.selectedPage = null;
            $scope.model.start = 0;
            $scope.model.end = 10;
            $scope.model.total = 0;

            pagesFact.getPagesData({
                    start: $scope.model.start,
                    end: $scope.model.end
                },
                function(response){

                    if(response.data.singleResult != undefined && response.data.singleResult != null){
                        $scope.model.selectedPage = response.data.pagesDataCollection;
                        $scope.model.selectedPage.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedPage.content_es);
                        if(response.data.current_tag != undefined && response.data.current_tag != null){
                            $timeout(function(){
                                $scope.goToTop(response.data.current_tag);
                            },1000);
                        }
                    }
                    else{
                        $scope.model.pagesCollection = response.data.pagesDataCollection;
                        $scope.model.total = response.data.totalPages;
                    }

                },
                function(response){

                });
        }
        init();
    }]);
})();
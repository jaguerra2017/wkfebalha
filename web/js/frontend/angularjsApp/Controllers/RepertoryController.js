/*
 * File for handling controllers for Backend Repertory Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.repertoryController', ['BncFrontend.repertoryFactory']).controller('repertoryCtrller',
    ['$scope','$filter','$sce', '$timeout', 'repertoryFact',function($scope, $filter, $sce, $timeout, repertoryFact){

        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */
        /* get the Repertory Collection */
        $scope.getRepertory = function()
        {
            $scope.toggleDataLoader();
            $scope.model.start = ($scope.model.start + 16);
            $scope.model.end = 16/*($scope.model.end + 16)*/;
            repertoryFact.getRepertoryData({
                start: $scope.model.start,
                end: $scope.model.end
            },
            function(response){
                var tempCollection = response.data.repertoryDataCollection;
                if(tempCollection.length > 0){
                    for(var i=0; i<tempCollection.length; i++){
                        $scope.model.repertoryCollection.push(tempCollection[i]);
                    }
                }
                $scope.model.total = response.data.totalRepertory;
                $scope.toggleDataLoader();
            },
            function(response){
                $scope.toggleDataLoader();
            });
        }

        /* function on scope for go ahead to top */
        $scope.goToTop = function()
        {
            var repertoryHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
            $('html, body').animate({scrollTop: repertoryHeading.height()}, 1000);
        }

        /*change View*/
        $scope.changeActiveView = function(view){
            $scope.model.activeView = view;
        }

        /* toggle data-loading message */
        $scope.toggleDataLoader = function()
        {
            $scope.model.loadingData = !$scope.model.loadingData;
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
            $scope.model.repertoryCollection = [];
            $scope.model.blocksCollection = [];
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            $scope.model.opinionsCollection = [];
            /*form view variables*/
            $scope.model.loadingData = false;
            $scope.model.showRepertoryForm = false;
            $scope.model.processingData = false;
            $scope.model.selectedRepertory = null;
            $scope.model.activeView = 'repertory_data';
            $scope.model.start = 0;
            $scope.model.end = 16;
            $scope.model.total = 0;

            repertoryFact.getRepertoryData({
                    start: $scope.model.start,
                    end: $scope.model.end
                },
                function(response){

                    if(response.data.singleResult != undefined && response.data.singleResult != null){
                        $scope.model.selectedRepertory = response.data.repertoryDataCollection;
                        $scope.model.selectedRepertory.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedRepertory.content_es);
                    }
                    else{
                        $scope.model.repertoryCollection = response.data.repertoryDataCollection;
                        $scope.model.total = response.data.totalRepertory;
                    }
                },
                function(response){

                });
        }
        init();
    }]);
})();
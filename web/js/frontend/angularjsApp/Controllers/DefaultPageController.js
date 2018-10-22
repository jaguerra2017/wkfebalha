/*
 * File for handling controllers for Backend Composition Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.defaultPageController', ['BncFrontend.defaultPageFactory']).controller('defaultPageCtrller',
    ['$scope','$filter','$sce','defaultPageFact',function($scope, $filter,$sce, defaultPageFact){

        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */
        /* toggle data-loading message */
        $scope.toggleDataLoader = function()
        {
            $scope.model.loadingData = !$scope.model.loadingData;
        }

        /*watching changes on Blocks Collection*/
        $scope.$watch('model.blocksCollection', function(newValue, oldValue) {
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
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
                }
            }
        });





        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.model.blocksCollection = [];
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            $scope.model.opinionsCollection = [];
            /*form view variables*/
            $scope.model.loadingData = false;
            $scope.model.showCompositionForm = false;
            $scope.model.processingData = false;
            $scope.model.selectedPage = null;
            $scope.model.activeView = 'page_images';

            defaultPageFact.getPageData({},
                function(response){
                    if(response.data.singleResult != undefined && response.data.singleResult != null){
                        $scope.model.selectedPage = response.data.pagesDataCollection;
                        $scope.model.selectedPage.html_filtered_content = $sce.trustAsHtml($scope.model.selectedPage.content);
                    }
                },
                function(response){

                });
        }
        init();
    }]);
})();
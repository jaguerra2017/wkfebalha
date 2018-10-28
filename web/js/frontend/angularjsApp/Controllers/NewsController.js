/*
 * File for handling controllers for Backend News Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.newsController', ['BncFrontend.newsFactory']).controller('newsCtrller',
    ['$scope','$filter','$sce','newsFact',function($scope, $filter,$sce, newsFact){

        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */

        /* get the News Collection */
        $scope.getNews = function()
        {
            $scope.toggleDataLoader();
            $scope.model.start = ($scope.model.end);
            $scope.model.end = ($scope.model.end + 10);
            newsFact.getNewsData({
                start: $scope.model.start,
                end: $scope.model.end
            },
            function(response){
                var tempCollection = response.data.newsDataCollection;
                if(tempCollection.length > 0){
                    for(var i=0; i<tempCollection.length; i++){
                        $scope.model.newsCollection.push(tempCollection[i]);
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
        $scope.goToTop = function()
        {
            var newHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
            $('html, body').animate({scrollTop: newHeading.height()}, 1000);
        }


        /* Hide the CRUD form */
        $scope.hideNewsForm = function()
        {
            $scope.model.showNewsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getNews();

            $scope.goToTop();
        }

        /* show the form to Create/Edit News */
        $scope.showNewsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showNewsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showNewsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    newId : $scope.model.selectedNew.id
                };
                newsFact.getNewsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedNew = response.data.newData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedNew.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedNew.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedNew.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedNew.featured_image_url,
                            id : $scope.model.selectedNew.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedNew.content);

                    if($scope.model.templatesCollection.length > 0){
                        for(var i=0; i<$scope.model.templatesCollection.length; i++){
                            if($scope.model.selectedNew.template.template_slug == $scope.model.templatesCollection[i].template_slug){
                                $scope.model.selectedTemplate = $scope.model.templatesCollection[i];
                            }
                        }
                    }
                });
            }
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
            $scope.model.newsCollection = [];
            $scope.model.blocksCollection = [];
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            /*form view variables*/
            $scope.model.loadingData = false;
            $scope.model.showNewsForm = false;
            $scope.model.processingData = false;
            $scope.model.selectedNew = null;
            $scope.model.activeView = 'new_images';
            $scope.model.start = 0;
            $scope.model.end = 10;
            $scope.model.total = 0;

            newsFact.getNewsData({
                    start: $scope.model.start,
                    end: $scope.model.end
                },
                function(response){

                    if(response.data.singleResult != undefined && response.data.singleResult != null){
                        $scope.model.selectedNew = response.data.newsDataCollection;
                        $scope.model.selectedNew.html_filtered_content = $sce.trustAsHtml($scope.model.selectedNew.content);
                    }
                    else{
                        $scope.model.newsCollection = response.data.newsDataCollection;
                        $scope.model.total = response.data.totalPosts;
                    }

                },
                function(response){

                });
        }
        init();
    }]);
})();
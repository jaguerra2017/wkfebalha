/*
 * File for handling controllers for Backend Jewels Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.jewelsController', ['BncFrontend.jewelsFactory']).controller('jewelsCtrller',
    ['$scope','$filter','$sce','jewelsFact',function($scope, $filter,$sce, jewelsFact){

        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */

        /* get the Jewels Collection */
        $scope.getJewels = function()
        {
            $scope.toggleDataLoader();
            $scope.model.start = ($scope.model.start + 10);
            jewelsFact.getJewelsData({
                start: $scope.model.start,
                end: $scope.model.end
            },
            function(response){
                var tempCollection = response.data.jewelsDataCollection;
                if(tempCollection.length > 0){
                    for(var i=0; i<tempCollection.length; i++){
                        $scope.model.jewelsCollection.push(tempCollection[i]);
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
            var jewelHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
            $('html, body').animate({scrollTop: jewelHeading.height()}, 1000);
        }


        /* Hide the CRUD form */
        $scope.hideJewelsForm = function()
        {
            $scope.model.showJewelsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getJewels();

            $scope.goToTop();
        }

        /* show the form to Create/Edit Jewels */
        $scope.showJewelsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showJewelsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showJewelsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    jewelId : $scope.model.selectedJewel.id
                };
                jewelsFact.getJewelsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedJewel = response.data.jewelData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedJewel.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedJewel.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedJewel.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedJewel.featured_image_url,
                            id : $scope.model.selectedJewel.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedJewel.content_es);

                    if($scope.model.templatesCollection.length > 0){
                        for(var i=0; i<$scope.model.templatesCollection.length; i++){
                            if($scope.model.selectedJewel.template.template_slug == $scope.model.templatesCollection[i].template_slug){
                                $scope.model.selectedTemplate = $scope.model.templatesCollection[i];
                            }
                        }
                    }
                });
            }
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
            $scope.model.jewelsCollection = [];
            $scope.model.blocksCollection = [];
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            $scope.model.opinionsCollection = [];
            /*form view variables*/
            $scope.model.loadingData = false;
            $scope.model.showJewelsForm = false;
            $scope.model.processingData = false;
            $scope.model.selectedJewel = null;
            $scope.model.activeView = 'jewel_data';
            $scope.model.start = 0;
            $scope.model.end = 10;
            $scope.model.total = 0;

            jewelsFact.getJewelsData({
                    start: $scope.model.start,
                    end: $scope.model.end
                },
                function(response){

                    if(response.data.singleResult != undefined && response.data.singleResult != null){
                        $scope.model.selectedJewel = response.data.jewelsDataCollection;
                        $scope.model.selectedJewel.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedJewel.content_es);
                    }
                    else{
                        $scope.model.jewelsCollection = response.data.jewelsDataCollection;
                        $scope.model.total = response.data.totalJewels;
                    }

                },
                function(response){

                });
        }
        init();
    }]);
})();
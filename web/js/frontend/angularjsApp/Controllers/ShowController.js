/*
 * File for handling controllers for Backend Shows Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.showController', ['BncFrontend.showsFactory']).controller('showCtrller',
    ['$scope','$filter','$sce','showsFact',function($scope, $filter,$sce, showsFact){

        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */

        /* get the Shows Collection */
        $scope.getShows = function()
        {
            $scope.toggleDataLoader();
            $scope.model.start = ($scope.model.end + 1);
            $scope.model.end = ($scope.model.end + 10);
            showsFact.getShowsData({
                start: $scope.model.start,
                end: $scope.model.end
            },
            function(response){
                var tempCollection = response.data.showDataCollection;
                if(tempCollection.length > 0){
                    for(var i=0; i<tempCollection.length; i++){
                        $scope.model.showCollection.push(tempCollection[i]);
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
        $scope.hideShowsForm = function()
        {
            $scope.model.showShowsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getShows();

            $scope.goToTop();
        }

        /* show the form to Create/Edit Shows */
        $scope.showShowsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showShowsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showShowsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    newId : $scope.model.selectedShow.id
                };
                showsFact.getShowsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedShow = response.data.newData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedShow.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedShow.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedShow.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedShow.featured_image_url,
                            id : $scope.model.selectedShow.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedShow.content);

                    if($scope.model.templatesCollection.length > 0){
                        for(var i=0; i<$scope.model.templatesCollection.length; i++){
                            if($scope.model.selectedShow.template.template_slug == $scope.model.templatesCollection[i].template_slug){
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
            $scope.model.showCollection = [];
            $scope.model.blocksCollection = [];
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            /*form view variables*/
            $scope.model.loadingData = false;
            $scope.model.showShowsForm = false;
            $scope.model.processingData = false;
            $scope.model.selectedShow = null;
            $scope.model.activeView = 'show_images';
            $scope.model.start = 0;
            $scope.model.end = 10;
            $scope.model.total = 0;

            showsFact.getShowData({
                    start: $scope.model.start,
                    end: $scope.model.end
                },
                function(response){

                    if(response.data.singleResult != undefined && response.data.singleResult != null){

                        $scope.model.selectedShow = response.data.showsDataCollection;
                        $scope.model.selectedShow.html_filtered_content = $sce.trustAsHtml($scope.model.selectedShow.content);
                    }
                    else{
                        $scope.model.showCollection = response.data.showsDataCollection;
                        $scope.model.total = response.data.totalShows;
                    }

                },
                function(response){

                });
        }
        init();
    }]);
})();
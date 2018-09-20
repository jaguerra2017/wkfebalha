/*
 * File for handling controllers for Backend Publications Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.publicationsController', ['BncFrontend.publicationsFactory']).controller('publicationsCtrller',
    ['$scope','$filter','$sce','publicationsFact',function($scope, $filter,$sce, publicationsFact){

        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */

        /* get the Publications Collection */
        $scope.getPublications = function()
        {
            $scope.toggleDataLoader();
            $scope.model.start = ($scope.model.end + 1);
            $scope.model.end = ($scope.model.end + 10);
            publicationsFact.getPublicationsData({
                start: $scope.model.start,
                end: $scope.model.end
            },
            function(response){
                var tempCollection = response.data.publicationsDataCollection;
                if(tempCollection.length > 0){
                    for(var i=0; i<tempCollection.length; i++){
                        $scope.model.publicationsCollection.push(tempCollection[i]);
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
            var publicationHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
            $('html, body').animate({scrollTop: publicationHeading.height()}, 1000);
        }


        /* Hide the CRUD form */
        $scope.hidePublicationsForm = function()
        {
            $scope.model.showPublicationsForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getPublications();

            $scope.goToTop();
        }

        /* show the form to Create/Edit Publications */
        $scope.showPublicationsForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showPublicationsForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showPublicationsForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    publicationId : $scope.model.selectedPublication.id
                };
                publicationsFact.getPublicationsData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedPublication = response.data.publicationData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedPublication.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedPublication.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedPublication.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedPublication.featured_image_url,
                            id : $scope.model.selectedPublication.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedPublication.content_es);

                    if($scope.model.templatesCollection.length > 0){
                        for(var i=0; i<$scope.model.templatesCollection.length; i++){
                            if($scope.model.selectedPublication.template.template_slug == $scope.model.templatesCollection[i].template_slug){
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

        /*change View*/
        $scope.changeActiveView = function(view){
            $scope.model.activeView = view;
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
            $scope.model.publicationsCollection = [];
            $scope.model.blocksCollection = [];
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            $scope.model.opinionsCollection = [];
            /*form view variables*/
            $scope.model.loadingData = false;
            $scope.model.showPublicationsForm = false;
            $scope.model.processingData = false;
            $scope.model.selectedPublication = null;
            $scope.model.activeView = 'publication_data';
            $scope.model.start = 0;
            $scope.model.end = 10;
            $scope.model.total = 0;

            publicationsFact.getPublicationsData({
                    start: $scope.model.start,
                    end: $scope.model.end
                },
                function(response){

                    if(response.data.singleResult != undefined && response.data.singleResult != null){
                        $scope.model.selectedPublication = response.data.publicationsDataCollection;
                        $scope.model.selectedPublication.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedPublication.content_es);
                    }
                    else{
                        $scope.model.publicationsCollection = response.data.publicationsDataCollection;
                        $scope.model.total = response.data.totalPublications;
                    }

                },
                function(response){

                });
        }
        init();
    }]);
})();
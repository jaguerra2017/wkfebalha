/*
 * File for handling controllers for Backend Composition Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.compositionController', ['BncFrontend.compositionFactory']).controller('compositionCtrller',
    ['$scope','$filter','$sce','compositionFact',function($scope, $filter,$sce, compositionFact){

        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */

        /* get the Composition Collection */
        $scope.getComposition = function()
        {
            $scope.toggleDataLoader();
            $scope.model.start = ($scope.model.start + 10);
            compositionFact.getCompositionData({
                start: $scope.model.start,
                end: $scope.model.end
            },
            function(response){
                var tempCollection = response.data.compositionDataCollection;
                if(tempCollection.length > 0){
                    for(var i=0; i<tempCollection.length; i++){
                        $scope.model.compositionCollection.push(tempCollection[i]);
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
            var compositionHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
            $('html, body').animate({scrollTop: compositionHeading.height()}, 1000);
        }


        /* Hide the CRUD form */
        $scope.hideCompositionForm = function()
        {
            $scope.model.showCompositionForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getComposition();

            $scope.goToTop();
        }

        /* show the form to Create/Edit Composition */
        $scope.showCompositionForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showCompositionForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showCompositionForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    compositionId : $scope.model.selectedComposition.id
                };
                compositionFact.getCompositionData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedComposition = response.data.compositionData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedComposition.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedComposition.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedComposition.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedComposition.featured_image_url,
                            id : $scope.model.selectedComposition.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedComposition.content_es);

                    if($scope.model.templatesCollection.length > 0){
                        for(var i=0; i<$scope.model.templatesCollection.length; i++){
                            if($scope.model.selectedComposition.template.template_slug == $scope.model.templatesCollection[i].template_slug){
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
            $scope.model.compositionCollection = [];
            $scope.model.firstsDancersCollection = [];
            $scope.model.firstCaractersDancersCollection = [];
            $scope.model.mainDancersCollection = [];
            $scope.model.firstSolosCollection = [];
            $scope.model.solosCollection = [];
            $scope.model.chorifeosCollection = [];
            $scope.model.danceBodyCollection = [];

            $scope.model.blocksCollection = [];
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            $scope.model.opinionsCollection = [];
            /*form view variables*/
            $scope.model.loadingData = false;
            $scope.model.showCompositionForm = false;
            $scope.model.processingData = false;
            $scope.model.selectedComposition = null;
            $scope.model.activeView = 'composition_data';
            $scope.model.start = 0;
            $scope.model.end = 10;
            $scope.model.total = 0;

            compositionFact.getCompositionData({},
                function(response){

                    if(response.data.singleResult != undefined && response.data.singleResult != null){
                        $scope.model.selectedComposition = response.data.compositionDataCollection;
                        $scope.model.selectedComposition.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedComposition.content_es);
                    }
                    else{
                        $scope.model.compositionCollection = response.data.compositionDataCollection;

                        if($scope.model.compositionCollection.length > 0){
                            for(var i=0; i<$scope.model.compositionCollection.length; i++){
                                var member = $scope.model.compositionCollection[i];
                                if(member.categoriesCollection != undefined && member.categoriesCollection.length > 0){
                                    for(var j=0; j<member.categoriesCollection.length; j++){
                                        var category = member.categoriesCollection[j];
                                        switch(category.tree_slug){
                                            case 'composition-category-primeros-bailarines':
                                                $scope.model.firstsDancersCollection.push(member);
                                                break;
                                            case 'composition-category-primer-bailarn-de-caracter':
                                                $scope.model.firstCaractersDancersCollection.push(member);
                                                break;
                                            case 'composition-category-bailarines-principales':
                                                $scope.model.mainDancersCollection.push(member);
                                                break;
                                            case 'composition-category-primeros-solistas':
                                                $scope.model.firstSolosCollection.push(member);
                                                break;
                                            case 'composition-category-solistas':
                                                $scope.model.solosCollection.push(member);
                                                break;
                                            case 'composition-category-corifeos':
                                                $scope.model.chorifeosCollection.push(member);
                                                break;
                                            case 'composition-category-cuerpo-de-baile':
                                                $scope.model.danceBodyCollection.push(member);
                                                break;
                                        }
                                    }
                                }
                            }
                        }
                    }

                },
                function(response){

                });
        }
        init();
    }]);
})();
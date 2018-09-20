/*
 * File for handling controllers for Backend Partners Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.partnersController', ['BncFrontend.partnersFactory']).controller('partnersCtrller',
    ['$scope','$filter','$sce','partnersFact',function($scope, $filter,$sce, partnersFact){

        /*
         * Global variables
         *
         * */


        /*
         * Operations Functions
         *
         * */

        /* get the Partners Collection */
        $scope.getPartners = function()
        {
            $scope.toggleDataLoader();
            $scope.model.start = ($scope.model.end + 1);
            $scope.model.end = ($scope.model.end + 10);
            partnersFact.getPartnersData({
                start: $scope.model.start,
                end: $scope.model.end
            },
            function(response){
                var tempCollection = response.data.partnersDataCollection;
                if(tempCollection.length > 0){
                    for(var i=0; i<tempCollection.length; i++){
                        $scope.model.partnersCollection.push(tempCollection[i]);
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
            var partnerHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
            $('html, body').animate({scrollTop: partnerHeading.height()}, 1000);
        }


        /* Hide the CRUD form */
        $scope.hidePartnersForm = function()
        {
            $scope.model.showPartnersForm = false;
            $scope.model.formActiveView = 'general-info';
            $scope.handleCrudOperations('reset');
            $scope.getPartners();

            $scope.goToTop();
        }

        /* show the form to Create/Edit Partners */
        $scope.showPartnersForm = function()
        {
            $scope.handleCrudOperations('disable');
            if($scope.model.createAction){
                $scope.model.showPartnersForm = true;
                $scope.goToTop();
            }
            else{
                $scope.model.showPartnersForm = true;
                $scope.goToTop();
                $scope.toggleDataLoader();
                var searchParametersCollection = {
                    singleResult : true,
                    partnerId : $scope.model.selectedPartner.id
                };
                partnersFact.getPartnersData($scope, searchParametersCollection, function(response){
                    $scope.toggleDataLoader();
                    $scope.model.selectedPartner = response.data.partnerData;
                    $scope.model.selectedCategoriesCollection = $scope.model.selectedPartner.categoriesCollection;
                    if($scope.model.postStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.postStatusCollection.length; i++){
                            if($scope.model.postStatusCollection[i].id == $scope.model.selectedPartner.post_status_id){
                                $scope.model.selectedPostStatus = $scope.model.postStatusCollection[i];
                            }
                        }
                    }
                    if($scope.model.selectedPartner.have_featured_image == true){
                        $scope.model.featureImage = {
                            url : $scope.model.selectedPartner.featured_image_url,
                            id : $scope.model.selectedPartner.featured_image_id
                        }
                    }
                    $('#textEditor').code($scope.model.selectedPartner.content_es);

                    if($scope.model.templatesCollection.length > 0){
                        for(var i=0; i<$scope.model.templatesCollection.length; i++){
                            if($scope.model.selectedPartner.template.template_slug == $scope.model.templatesCollection[i].template_slug){
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
            $scope.model.partnersCollection = [];
            $scope.model.blocksCollection = [];
            $scope.model.imagesCollection = [];
            $scope.model.videosCollection = [];
            $scope.model.opinionsCollection = [];
            /*form view variables*/
            $scope.model.loadingData = false;
            $scope.model.showPartnersForm = false;
            $scope.model.processingData = false;
            $scope.model.selectedPartner = null;
            $scope.model.activeView = 'partner_data';
            $scope.model.start = 0;
            $scope.model.end = 10;
            $scope.model.total = 0;

            partnersFact.getPartnersData({
                    start: $scope.model.start,
                    end: $scope.model.end
                },
                function(response){

                    if(response.data.singleResult != undefined && response.data.singleResult != null){
                        $scope.model.selectedPartner = response.data.partnersDataCollection;
                        $scope.model.selectedPartner.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedPartner.content_es);
                    }
                    else{
                        $scope.model.partnersCollection = response.data.partnersDataCollection;
                        $scope.model.total = response.data.totalPartners;
                    }

                },
                function(response){

                });
        }
        init();
    }]);
})();
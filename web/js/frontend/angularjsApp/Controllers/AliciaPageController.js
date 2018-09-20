/*
 * File for handling controllers for Backend Pages Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.aliciaPageController', ['BncFrontend.aliciaPageFactory']).controller('aliciaPageCtrller',
        ['$scope','$filter','$sce','$timeout','aliciaPageFact',function($scope, $filter, $sce, $timeout, aliciaPageFact){

            /*
             * Global variables
             *
             * */


            /*
             * Operations Functions
             *
             * */
            /*change opinion in carrousel*/
            $scope.changeOpinion = function(option){
                var currentOpinion = $scope.model.selectedOpinion;
                $scope.model.selectedOpinion = null;
                if(option == 'next'){
                    var nextOpinion = $scope.model.opinionsCollection[(currentOpinion.index + 1)];
                    $scope.model.selectedOpinion = nextOpinion;

                }
                if(option == 'before'){
                    var beforeOpinion = $scope.model.opinionsCollection[(currentOpinion.index - 1)];
                    $scope.model.selectedOpinion = beforeOpinion;
                }

            }

            /* get the Awards Collection */
            $scope.getAwards = function(type)
            {
                var searchParametersCollection = {};
                var start = 0;
                var end = 10;
                switch(type){
                    case 'na':
                        searchParametersCollection.load_na = true;
                        searchParametersCollection.na_start = ($scope.model.na_start + 10);
                        searchParametersCollection.na_end = 10;
                        break;
                    case 'ia':
                        searchParametersCollection.load_ia = true;
                        searchParametersCollection.ia_start = ($scope.model.ia_start + 10);
                        searchParametersCollection.ia_end = 10;
                        break;
                    case 'la':
                        searchParametersCollection.load_la = true;
                        searchParametersCollection.la_start = ($scope.model.la_start + 10);
                        searchParametersCollection.la_end = 10;
                        break;
                }

                $scope.toggleDataLoader();

                aliciaPageFact.getPageData(searchParametersCollection,
                    function(response){

                        switch(type){
                            case 'na':
                                var tempCollection = response.data.nationalAwardsCollection;
                                if(tempCollection.length > 0){
                                    for(var i=0; i<tempCollection.length; i++){
                                        $scope.model.nationalAwardsCollection.push(tempCollection[i]);
                                    }
                                }
                                $scope.model.na_total = response.data.totalNa;
                                break;
                            case 'ia':
                                var tempCollection = response.data.internationalAwardsCollection;
                                if(tempCollection.length > 0){
                                    for(var i=0; i<tempCollection.length; i++){
                                        $scope.model.internationalAwardsCollection.push(tempCollection[i]);
                                    }
                                }
                                $scope.model.ia_total = response.data.totalIa;
                                break;
                            case 'la':
                                var tempCollection = response.data.latamAwardsCollection;
                                if(tempCollection.length > 0){
                                    for(var i=0; i<tempCollection.length; i++){
                                        $scope.model.latamAwardsCollection.push(tempCollection[i]);
                                    }
                                }
                                $scope.model.la_total = response.data.totalLa;
                                break;
                        }



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
                                    opinionsCollection[j].index = j;
                                    opinionsCollection[j].filtered_html_content = $sce.trustAsHtml( opinionsCollection[j].content_es);
                                    $scope.model.opinionsCollection.push(opinionsCollection[j]);
                                }
                            }
                        }
                    }
                }

                if( $scope.model.opinionsCollection.length > 0){
                    $scope.model.selectedOpinion = $scope.model.opinionsCollection[0];
                }
            });





            function init(){
                /*generals variables*/
                $scope.model = {};
                $scope.success = false;
                $scope.error = false;
                /*list view variables*/
                $scope.model.awardsCollection = [];
                $scope.model.nationalAwardsCollection = [];
                $scope.model.internationalAwardsCollection = [];
                $scope.model.latamAwardsCollection = [];
                $scope.model.blocksCollection = [];
                $scope.model.imagesCollection = [];
                $scope.model.videosCollection = [];
                $scope.model.opinionsCollection = [];
                $scope.model.selectedOpinion = null;
                /*form view variables*/
                $scope.model.na_start = 0;
                $scope.model.na_end = 10;
                $scope.model.na_total = 10;
                $scope.model.ia_start = 0;
                $scope.model.ia_end = 10;
                $scope.model.ia_total = 10;
                $scope.model.la_start = 0;
                $scope.model.la_end = 10;
                $scope.model.la_total = 10;
                $scope.model.loadingData = false;
                $scope.model.processingData = false;
                $scope.model.selectedPage = null;
                $scope.model.showPageContent = false;

                aliciaPageFact.getPageData({
                        load_na: true,
                        na_start: $scope.model.na_start,
                        na_end: $scope.model.na_end,
                        load_ia: true,
                        ia_start: $scope.model.ia_start,
                        ia_end: $scope.model.ia_end,
                        load_la: true,
                        la_start: $scope.model.la_start,
                        la_end: $scope.model.la_end
                    },
                    function(response){
                        if(response.data.singleResult != undefined && response.data.singleResult != null){
                            $scope.model.selectedPage = response.data.pagesDataCollection;
                            $scope.model.selectedPage.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedPage.content_es);

                            $scope.model.nationalAwardsCollection = response.data.nationalAwardsCollection;
                            $scope.model.na_total = response.data.totalNa;
                            $scope.model.internationalAwardsCollection = response.data.internationalAwardsCollection;
                            $scope.model.ia_total = response.data.totalIa;
                            $scope.model.latamAwardsCollection = response.data.latamAwardsCollection;
                            $scope.model.la_total = response.data.totalLa;

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
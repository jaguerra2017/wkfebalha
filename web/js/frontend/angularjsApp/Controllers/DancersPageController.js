/*
 * File for handling controllers for Backend Pages Feature
 * */

(function () {
    'use strict';

    /* Declaring controllers functions for this module */
    angular.module('BncFrontend.dancersPageController', ['BncFrontend.dancersPageFactory']).controller('dancersPageCtrller',
        ['$scope','$filter','$sce','$timeout','dancersPageFact',function($scope, $filter, $sce, $timeout, dancersPageFact){

            /*
             * Global variables
             *
             * */


            /*
             * Operations Functions
             *
             * */
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
                $scope.model.compositionCollection = [];
                $scope.model.firstsDancersCollection = [];
                $scope.model.firstCaractersDancersCollection = [];
                $scope.model.mainDancersCollection = [];
                $scope.model.firstSolosCollection = [];
                $scope.model.solosCollection = [];
                $scope.model.chorifeosCollection = [];
                $scope.model.danceBodyCollection = [];
                $scope.model.maitresCollection = [];
                $scope.model.choreographersCollection = [];
                $scope.model.coordinatorsCollection = [];

                /*form view variables*/
                $scope.model.loadingData = false;
                $scope.model.showPagesForm = false;
                $scope.model.processingData = false;
                $scope.model.selectedPage = null;

                dancersPageFact.getPageData({
                        load_composition: true
                    },
                    function(response){
                        if(response.data.singleResult != undefined && response.data.singleResult != null){
                            $scope.model.selectedPage = response.data.pagesDataCollection;
                            $scope.model.selectedPage.html_filtered_content_es = $sce.trustAsHtml($scope.model.selectedPage.content_es);

                            $scope.model.compositionCollection = response.data.compositionCollection;
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
                                                case 'composition-category-maitres-de-ballet':
                                                    $scope.model.maitresCollection.push(member);
                                                    break;
                                                case 'composition-category-coreografos':
                                                    $scope.model.choreographersCollection.push(member);
                                                    break;
                                                case 'composition-category-coordinacion-del-trabajo-artisitico':
                                                    $scope.model.coordinatorsCollection.push(member);
                                                    break;
                                            }
                                        }
                                    }
                                }
                            }

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
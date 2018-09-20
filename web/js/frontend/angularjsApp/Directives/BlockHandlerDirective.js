/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend.blockHandlerDirective', ['BncFrontend.blockFactory']);

    /* Declaring directive functions for this module */
    angular.module('BncFrontend.blockHandlerDirective').directive('blockHandler', [function()
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            scope : {
                elementid : '=',
                blockscollection : '=',
                elementtype : '@'
            },
            controller: function($scope, $element, blockFact) {

                /*
                 * Global variables
                 *
                 * */


                /*
                 * Operations Functions
                 *
                 * */
                /* clear errors of the form */

                /* get the BLocks Data Collection */
                $scope.getBlocksData = function()
                {
                    var searchParametersCollection = {
                        genericPostId: $scope.elementid
                    };
                    blockFact.getBlocksData($scope, searchParametersCollection,
                        function(response){
                            $scope.blockscollection = response.data.blocksDataCollection;
                        },
                        function(response){
                        });
                }



                function init(){
                    /*generals variables*/
                    $scope.model = {};
                    $scope.success = false;
                    $scope.error = false;

                    $scope.getBlocksData();
                }
                init();
            },
            template: ''

        }

        return directiveDefinitionObject;
    }]);
})();
/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend.videoHandlerDirective', []);

    /* Declaring directive functions for this module */
    angular.module('BncFrontend.videoHandlerDirective').directive('videoHandler',['$timeout',function($timeout)
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            scope : {
                videoscollection : '='
            },
            controller: function($scope, $element, $timeout) {

                /*
                 * Global variables
                 *
                 * */


                /*
                 * Operations Functions
                 *
                 * */
                /*hide video play modal*/
                $scope.hideVideoPlayModal = function(){
                    $('#video-play-modal').modal('hide');
                    $scope.model.selectedVideoToPlay = null;
                }

                /*show video play modal*/
                $scope.showVideoPlayModal = function(video){

                    if(video != undefined){
                        $scope.model.selectedVideoToPlay = null;
                        $scope.model.selectedVideoToPlay = video;

                        $timeout(function(){
                            $('.media-video-play').each(function(){
                                var iframeParent = $(this).parent();
                                var iframeVideoId = iframeParent.attr("data-video-id");
                                if(iframeVideoId == $scope.model.selectedVideoToPlay.id){
                                    $(this).attr('id',$scope.model.selectedVideoToPlay.id);
                                    $(this).attr('src',$scope.model.selectedVideoToPlay.youtube_url);
                                    $(this).attr('title',$scope.model.selectedVideoToPlay.name_es);
                                }
                            });
                        }, 1000);

                        $('#video-play-modal').modal('show');
                    }
                }


                function init(){
                    /*generals variables*/
                    $scope.model = {};
                    $scope.model.selectedVideoToPlay = null;
                }
                init();
            },
            template: '' +
            '<div class="row">' +
                '<div class="col-xs-6 col-md-4" data-ng-repeat="video in videoscollection">' +
                    '<div class="media-video-standard-thumbnail-container" style="margin-bottom:20px;">' +
                        '<div class="media-video-standard-thumbnail" data-video-id="[[video.id]]" style="text-align:center;">' +
                            '<a data-ng-click="showVideoPlayModal(video)">' +
                                '<img src="images/frontend/themes/default/video/frontend-video-play-thumbnail.png" ' +
                                'style="margin-top: 20%;">' +
                            '</a>' +
                        '</div>' +
                        '<span style="float:left;width:100%; color:white;padding:0px 10px;' +
                        'border-left: 2px solid #009dc7;margin-top: 10px;font-size:14px;">' +
                            '[[video.name_es]]' +
                        '</span>' +
                    '</div>' +
                    '' +
                    '<!-- Modal Video Player-->' +
                    '<div id="video-play-modal" class="modal fade" tabindex="-1" data-width="1280" ' +
                    ' data-keyboard="true">' +
                        '<div class="modal-header">' +

                        '</div>' +
                        '<div class="modal-body min-height-400">' +
                            '<form class="form horizontal-form">' +
                                '<div class="form-body">' +
                                    '<div class="media-video-play-thumbnail video-responsive" ' +
                                    'data-video-id="[[model.selectedVideoToPlay.id]]" style="text-align:center;">' +
                                        '<iframe class="media-video-play" allowfullscreen frameborder="0"></iframe>' +
                                    '</div>' +
                                '</div>' +
                            '</form>' +
                        '</div>' +
                    '</div>' +
                    '' +
                '</div>' +
            '</div>'

        }

        return directiveDefinitionObject;
    }]);
})();
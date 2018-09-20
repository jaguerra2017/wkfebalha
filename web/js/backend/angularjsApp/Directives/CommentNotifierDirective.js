/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.commentNotifierDirective', ['BncBackend.commentsFactory']);

    /* Declaring directive functions for this module */
    angular.module('BncBackend.commentNotifierDirective').directive('commentNotifier', [function()
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            scope : {},
            link:function(scope, element, attributes, controller, transcludeFn) {
                $('#notifications-counter').pulsate({
                    color: "#bf1c56"
                });
            },
            controller: function($scope, $element, commentsFact) {

                /*
                 * Global variables
                 *
                 * */
                var iterate = false;
                var isPulsating = false;
                var isFirstCheck = true;

                /*
                 * Operations Functions
                 *
                 * */
                /*check comments pending*/
                $scope.checkCommentsPending = function(){
                    commentsFact.checkCommentsPending($scope, function(response){
                        $scope.model.commentsPending = response.data.comments_pending;
                        var checkPendingComments = response.data.notificationConfig.check_pending_comments;
                        var isSiteStatusOnline = response.data.isSiteStatusOnline;

                        if(checkPendingComments == true && isSiteStatusOnline == true){
                            var checkingInterval = response.data.notificationConfig.checking_cicle_minutes;
                            if($scope.model.commentsPending > 0 && !isPulsating && !isFirstCheck){
                                isPulsating = true;
                                $('#notifications-counter').pulsate({
                                    color: "#bf1c56"
                                });
                            }
                            if(isFirstCheck){isFirstCheck = false;}
                            if(!iterate){
                                iterate = true;
                                setInterval(function(){
                                    $scope.checkCommentsPending();
                                },(checkingInterval * 60000));
                            }
                        }
                    },
                    function(response){
                        toastr.options.timeOut = 5000;
                        if(response.data && response.data.message){
                            toastr.error(response.data.message,"¡Error!");
                        }
                        else{
                            toastr.error("Ha ocurrido un error tratando de chequear los Comentarios Pendientes.","¡Error!");
                        }
                    })
                }




                function init(){
                    /*generals variables*/
                    $scope.model = {};
                    $scope.success = false;
                    $scope.error = false;
                    /*list view variables*/
                    $scope.model.commentsPending = 0;
                    $scope.model.commentsPath = '/backend/comentarios';
                    $scope.checkCommentsPending();
                }
                init();
            },
            template:
                '<div class="btn-group-notification btn-group" id="header_notification_bar">' +
                    '<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" ' +
                    'data-hover="dropdown" data-close-others="true">' +
                        '<i class="icon-bell"></i>' +
                        '<span id="notifications-counter" data-ng-if="model.commentsPending > 0" class="badge">[[model.commentsPending]]</span>' +
                    '</button>' +
                    '<ul class="dropdown-menu-v2">' +
                        '<li class="external">' +
                            '<h3><span class="bold">Notificaciones</span></h3>' +
                            '<a data-ng-click="checkCommentsPending()">' +
                                '<i class="icon-refresh"></i>' +
                            '</a>'+
                        '</li>' +
                        '<li>' +
                            '<ul class="dropdown-menu-list scroller" ' +
                            'style="height: 200px; padding: 0;" data-handle-color="#637283">' +
                                '<li data-ng-if="model.commentsPending > 0">' +
                                    '<a data-ng-href="[[model.commentsPath]]">' +
                                        '<span class="details" style="max-width: 100%;">' +
                                            '<span class="label label-sm label-icon label-success">' +
                                                '<i class="fa fa-comment"></i>' +
                                            '</span>' +
                                            'Tienes [[model.commentsPending]] [[model.commentsPending > 1 ? \'comentarios pendientes\' : \'comentario pendiente\']].' +
                                        '</span>' +
                                    '</a>' +
                                '</li>' +
                                '<li data-ng-if="model.commentsPending == 0">' +
                                    '<a href="javascript:;">' +
                                        '<span class="details">' +
                                            'No tienes comentarios pendientes.' +
                                        '</span>' +
                                    '</a>' +
                                '</li>' +
                            '</ul>' +
                        '</li>' +
                    '</ul>' +
                '</div>'
        }

        return directiveDefinitionObject;
    }]);
})();
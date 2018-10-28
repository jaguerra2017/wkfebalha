/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncFrontend.commentHandlerDirective', ['BncFrontend.commentsFactory']);

    /* Declaring directive functions for this module */
    angular.module('BncFrontend.commentHandlerDirective').directive('commentHandler', ['$timeout',function($timeout)
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            scope : {
                genericpostid : '=',
            },
            link:function(scope, element, attributes, controller, transcludeFn) {
                /*$('.date-picker').datepicker({
                    orientation: "left",
                    autoclose: true,
                    calendarWeeks: true,
                    format: "dd/mm/yyyy",
                    language : 'es'
                });*/
            },
            controller: function($scope, $element, $filter, $timeout, commentsFact) {

                /*
                 * Global variables
                 *
                 * */
                var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");
                var dateFilter = $filter("date");
                var dateRegExpress = new RegExp("[0-9]{2}/\[0-9]{2}/\[0-9]{4}");

                /*
                 * Operations Functions
                 *
                 * */
                /* clear errors of the form */
                $scope.clearErrorsCommentsForm = function(){
                    $scope.model.authorNameHasError = false;
                    $scope.model.emailHasError = false;
                    $scope.model.contentHasError = false;
                }

                /* clear form values */
                $scope.clearCommentsForm = function(anonymous){
                    $scope.model.selectedComment.content = null;
                }

                /*create comment*/
                $scope.createComment = function(parent){
                    $scope.model.createAction = true;
                    $scope.clearCommentsForm();
                    $scope.model.selectedComment.generic_post_id = $scope.genericpostid;
                    $scope.model.selectedComment.parent_id = null;
                    $scope.model.selectedParent = null;
                    if(parent != undefined){
                        $scope.model.selectedParent = parent;
                        $scope.model.selectedComment.parent_id = parent.id;
                    }
                    $scope.showCommentSelectorModal();
                }

                /*confirm is Not a Robot*/
                $scope.confirmIsNotARobot = function(event){
                    if(event != undefined && typeof event.originalEvent == 'object' && event.type == 'click'){
                        $scope.model.isARobot = false;
                    }
                }

                /* get the BLocks Data Collection */
                $scope.getCommentsData = function()
                {
                    $scope.toggleDataLoader();
                    var searchParametersCollection = {
                        genericPostId: $scope.genericpostid,
                        treeView : true,
                        loadCommentStatus: false
                    };
                    commentsFact.getCommentsData($scope, searchParametersCollection,
                        function(response){
                            $scope.model.commentsCollection = response.data.commentsDataCollection;
                            $scope.toggleDataLoader();
                            if($scope.model.commentsCollection.length == 0){
                                $scope.model.showCommentsList = false;
                                $scope.model.showCommentsForm = true;
                            }
                            else{
                                $scope.model.showCommentsForm = false;
                                $scope.model.showCommentsList = true;
                            }
                        },
                        function(response){
                            $scope.toggleDataLoader();
                        });
                }

                /*get comment elements*/
                $scope.getCommentElements = function(){
                    $scope.toggleDataLoader();
                    var searchParametersCollection = {
                        generalSearchValue: $scope.model.commentElementsGeneralSearchValue,
                        commentElementType: $scope.model.selectedComment.comment_type_tree_slug
                    };
                    commentsFact.getCommentElementsData($scope, searchParametersCollection,
                        function(response){
                            $scope.model.commentElementsCollection = response.data.commentElementsDataCollection;
                            if($scope.model.createAction == false &&
                            $scope.model.selectedComment.elements != undefined &&
                            $scope.model.selectedComment.elements.length > 0){
                                for(var i=0; i<$scope.model.selectedComment.elements.length; i++){
                                    for(var j=0; j<$scope.model.commentElementsCollection.length; j++){
                                        if($scope.model.selectedComment.elements[i].id == $scope.model.commentElementsCollection[j].id){
                                            $scope.model.commentElementsCollection[j].selected = true;
                                            break;
                                        }
                                    }
                                }
                            }
                            $scope.updatePaginationValues('comment-element');
                            $scope.toggleDataLoader();
                        },
                        function(response){
                            $scope.toggleDataLoader();
                        });
                }

                /*hide comment selector*/
                $scope.hideCommentSelectorModal = function(){
                    $scope.clearErrorsCommentsForm();
                    $scope.clearCommentsForm();
                    $scope.model.createAction = null;
                    $scope.model.showCommentsForm = false;
                    $timeout(function(){
                        $scope.model.showCommentsList = true;
                        $scope.getCommentsData();
                    },1000);
                }

                /*save the selected comments*/
                $scope.saveCommentData = function(){

                    if($scope.model.processingData == false && $scope.model.isARobot == false){
                        $scope.model.processingData = true;
                        $scope.toggleDataLoader();
                        var canProceed = true;
                        $scope.clearErrorsCommentsForm();

                        if($scope.model.selectedComment.author_name == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedComment.author_name) ||
                        $scope.model.selectedComment.email == null ||
                        $scope.model.selectedComment.content == null ||
                        $scope.model.selectedComment.content == undefined ||
                        !alfaNumericRegExpr.test($scope.model.selectedComment.content)){
                            canProceed = false;

                            if($scope.model.selectedComment.author_name == null ||
                            !alfaNumericRegExpr.test($scope.model.selectedComment.author_name)){
                                $scope.model.authorNameHasError = true;
                            }

                            if($scope.model.selectedComment.email == null){
                                $scope.model.emailHasError = true;
                            }

                            if($scope.model.selectedComment.content == null ||
                            $scope.model.selectedComment.content == undefined ||
                            !alfaNumericRegExpr.test($scope.model.selectedComment.content)){
                                $scope.model.contentHasError = true;
                            }
                        }

                        if(canProceed){
                            $scope.model.selectedComment.generic_post_id = $scope.genericpostid;
                            var commentsData = {commentData: $scope.model.selectedComment};
                            console.log(commentsData);
                            var action = $scope.model.createAction == true ? 'create' : 'edit';

                            commentsFact.saveCommentsData($scope, commentsData, action,
                                function(response){
                                    $scope.model.processingData = false;
                                    $scope.toggleDataLoader();
                                    if(response.data.success == 0){

                                    }
                                    else{
                                        $scope.clearErrorsCommentsForm();
                                        $scope.clearCommentsForm();
                                        $scope.hideCommentSelectorModal();


                                    }



                                },
                                function(response){
                                    /*$scope.model.processingData = true;
                                    $scope.toggleDataLoader();
                                    toastr.options.timeOut = 5000;
                                    if(response.data && response.data.message){
                                        toastr.error(response.data.message,"¡Error!");
                                    }
                                    else{
                                        toastr.error("Esta operación no ha podido ejecutarse.","¡Error!");
                                    }*/
                                });
                        }
                        else{
                            $scope.model.processingData = false;
                            $scope.toggleDataLoader();
                        }
                    }
                }

                /*show modal with comment comments collection*/
                $scope.showCommentSelectorModal = function(){
                    $scope.clearErrorsCommentsForm();
                    $scope.model.showCommentsList = false;
                    $scope.model.showCommentsForm = true;
                }

                /* toggle data-loading message */
                $scope.toggleDataLoader = function()
                {
                    $scope.model.loadingData = !$scope.model.loadingData;
                }

                /* handle key events triggered from input events in the CRUD form */
                $scope.updateCommentsForm = function(event, field, element)
                {
                    switch(field){
                        case 'status':
                            $scope.model.selectedCommentStatus = element;
                            if(element != undefined && element.tree_slug == 'comment-status-approved'){

                                $scope.model.selectedComment.published_date = dateFilter(new Date(), 'dd/MM/yyyy');
                            }
                            else{
                                $scope.model.selectedComment.published_date = null;
                                $scope.model.publishedDateHasError = false;
                            }
                            break;

                        case 'comment-mode':
                            if($scope.model.createAction == true){
                                $scope.model.selectedComment.anonymous = element;
                            }
                            break;

                        case 'filter-date':
                            if(dateRegExpress.test($scope.model.filterDate)){
                                $scope.getCommentsData();
                            }
                            break;
                    }
                }




                function init(){
                    /*generals variables*/
                    $scope.model = {};
                    $scope.success = false;
                    $scope.error = false;
                    /*list view variables*/
                    $scope.model.commentsCollection = [];
                    $scope.model.loadingData = false;
                    $scope.model.processingData = false;
                    /*form variables*/
                    $scope.model.selectedComment = {};
                    $scope.model.selectedParent = null;
                    $scope.model.createAction = true;
                    $scope.model.isARobot = true;
                    $scope.model.showCommentsForm = false;
                    $scope.model.showCommentsList = true;

                    $scope.getCommentsData();
                }
                init();
            },
            template:
                '<div class="container wow fadeInUpBig" style="max-width: 1280px;">' +
                    '<h4 style="font-family: Abril Fatface Regular">Comentarios</h4>' +
                    '<div class="row" style="position:relative;">' +

                        '<!-- Comments List -->'+
                        '<div data-ng-if="model.showCommentsForm == false && model.showCommentsList" ' +
                        'class="col-xs-12" style="position:relative;margin-top: 30px;">' +

                                '<!-- LEVEL 1 -->' +
                                '<div data-ng-repeat="comment in model.commentsCollection" class="media" ' +
                                'style="padding: 15px 5px;">' +
                                    '<div class="media-body">' +
                                        '<h4 class="media-heading">' +
                                            '<p class="comment-author" style="color:white;">[[comment.author_name]]</p>' +
                                            '<span class="notifier-event-date comment-date-actions" style="color:white;">' +
                                                '[[comment.created_date]] ' +
                                                '<a style="margin-bottom:5px;" data-ng-click="createComment(comment)"> | RESPONDER </a>' +
                                            '</span>' +
                                        '</h4>' +
                                        '<div class="comment-body">[[comment.content]]</div>' +

                                        '<!-- LEVEL 2 -->' +
                                        '<div data-ng-if="comment.childrens.length > 0" data-ng-repeat="children in comment.childrens" class="media" ' +
                                        'style="padding: 15px 30px;">' +
                                            '<div class="media-body">' +
                                                '<h4 class="media-heading">' +
                                                    '<p class="comment-author" style="color:white;">[[children.author_name]]</p>' +
                                                    '<span class="notifier-event-date comment-date-actions" style="color:white;">' +
                                                        '[[children.created_date]] ' +
                                                        '<a style="margin-bottom:5px;" data-ng-click="createComment(children)"> | RESPONDER </a>' +
                                                    '</span>' +
                                                '</h4>' +
                                                '<div class="comment-body">[[children.content]]</div>' +

                                                '<!-- LEVEL 3 -->' +
                                                '<div data-ng-if="children.childrens.length > 0" data-ng-repeat="grand_children in children.childrens" class="media" ' +
                                                'style="padding: 15px 60px;">' +
                                                    '<div class="media-body">' +
                                                        '<h4 class="media-heading">' +
                                                            '<p class="comment-author" style="color:white;">[[grand_children.author_name]]</p>' +
                                                            '<span class="notifier-event-date comment-date-actions" style="color:white;">' +
                                                                '[[grand_children.created_date]] ' +
                                                            '</span>' +
                                                        '</h4>' +
                                                        '<div class="comment-body">[[grand_children.content]]</div>' +
                                                    '</div>' +
                                                '</div>'+

                                            '</div>' +
                                        '</div>'+
                                    '</div>' +
                                '</div>' +

                                '<div class="row">' +
                                    '<div class="col-xs-12 text-center">' +
                                        '<a class="btn btn-add btn-circle btn-icon-only btn-default blue btn-blue" ' +
                                        'data-ng-click="createComment()" ' +
                                        'style="margin: auto;background: none;margin-right:15px;"> ' +
                                            '<i class="fa fa-plus" style="color: #3598dc;"></i>' +
                                        '</a>' +

                                        '<a class="btn btn-add btn-circle btn-icon-only btn-default blue btn-blue" ' +
                                        'data-ng-click="getCommentsData()" style="margin: auto;background: none;"> ' +
                                        '<i class="fa fa-refresh" style="color: #3598dc;"></i>' +
                                        '</a>' +
                                    '</div>' +
                                '</div>'+

                        '</div>' +

                        '<!-- Comment Form -->'+
                        '<div data-ng-if="model.showCommentsForm" ' +
                        'class="col-xs-12 col-sm-12 col-md-offset-3 col-md-6 wow fadeInRightBig">' +
                            '<button type="button" type="button" class="btn btn-icon-only pull-right" ' +
                            'data-ng-click="hideCommentSelectorModal()" ' +
                            'data-ng-if="model.commentsCollection.length > 0"' +
                            'style="background:none;color:#009dc7">' +
                                '<i class="fa fa-close" style="font-size:28px;"></i>' +
                            '</button>'+
                            '<form class="horizontal-form">' +
                                '<div class="form-body comment-form">' +
                                    '<div class="row">' +
                                        '<div class="col-xs-12">' +
                                            '<div class="form-group comment-form-group [[model.authorNameHasError ? \'has-error\' : \'\']]">' +
                                                '<label class="control-label">' +
                                                    'Nombre' +
                                                '</label>' +
                                                '<input class="form-control" type="text" ' +
                                                'data-ng-model="model.selectedComment.author_name" maxlength="200">' +
                                                '<span data-ng-if="model.authorNameHasError" class="help-block">' +
                                                    '<p>Valor incorrecto o en blanco.</p>' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div class="col-xs-12">' +
                                            '<div class="form-group comment-form-group [[model.emailHasError ? \'has-error\' : \'\']]">' +
                                                '<label class="control-label">' +
                                                    'Correo electrónico' +
                                                '</label>' +
                                                '<input class="form-control" type="text" ' +
                                                'data-ng-model="model.selectedComment.email" maxlength="60">' +
                                                '<span data-ng-if="model.emailHasError" class="help-block">' +
                                                    '<p>Valor incorrecto o en blanco.</p>' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div class="col-xs-12">' +
                                            '<div class="form-group comment-form-group [[model.contentHasError ? \'has-error\' : \'\']]">' +
                                                '<label class="control-label">' +
                                                    'Comentario' +
                                                '</label>' +
                                                '<textarea data-ng-model="model.selectedComment.content" ' +
                                                'class="form-control"' +
                                                'style="width: 100%;height: 100px;"></textarea>' +
                                                '<span data-ng-if="model.contentHasError" class="help-block">' +
                                                    '<p>Valor incorrecto o en blanco.</p>' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div data-ng-if="model.isARobot" class="col-xs-12" ' +
                                        'style="color:white;">' +
                                            '<div class="icheckbox_square-blue hover [[!model.isARobot ? \'checked\' : \'\']] wow fadeIn" ' +
                                            'data-ng-click="confirmIsNotARobot($event)" ' +
                                            'style="display: block;float: left;">' +
                                            '</div>' +
                                            'Confirma que no eres un ROBOT.'+
                                        '</div>' +

                                        '<div data-ng-if="!model.isARobot"  class="col-xs-12">' +
                                            '<button type="submit" class="custom-general-button comment-send wow fadeIn" ' +
                                            'data-ng-click="saveCommentData(\'clear\')" style="color:rgb(7,49,71);background-color:white;padding:7px 14px;width:100% !important;">' +
                                                'Publicar' +
                                            '</button>' +

                                            '<span class="help-block" ' +
                                            'style="float: left;color: #009dc7;font-size: 12px;margin-top: 30px;">' +
                                                '** Recuerde que su comentario primero deberá de ser revisado antes de publicarse **' +
                                            '</span>' +
                                        '</div>' +

                                    '</div>' +
                                '</div>' +
                            '</form>' +
                        '</div>' +


                        '<!-- Data Loader -->'+
                        '<div data-ng-show="model.loadingData">' +
                            '<div class="data-loader">' +
                                '<div class="sk-data-loader-spinner sk-spinner-three-bounce">' +
                                    '<div class="sk-bounce1"></div>' +
                                    '<div class="sk-bounce2"></div>' +
                                    '<div class="sk-bounce2"></div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +

                    '</div>'+



                '</div>'
        }

        return directiveDefinitionObject;
    }]);
})();
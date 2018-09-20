/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.commentHandlerDirective', ['BncBackend.commentsFactory']);

    /* Declaring directive functions for this module */
    angular.module('BncBackend.commentHandlerDirective').directive('commentHandler', [function()
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            scope : {
                genericpostid : '=',
            },
            link:function(scope, element, attributes, controller, transcludeFn) {
                $('.date-picker').datepicker({
                    orientation: "left",
                    autoclose: true,
                    calendarWeeks: true,
                    format: "dd/mm/yyyy",
                    language : 'es'
                });
            },
            controller: function($scope, $element, $filter, commentsFact) {

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
                    $scope.model.publishedDateHasError = false;
                    $scope.model.contentHasError = false;
                }

                /* clear form values */
                $scope.clearCommentsForm = function(anonymous){
                    $scope.model.selectedComment = {};
                    if(anonymous == undefined){
                        anonymous = true;
                    }
                    $scope.model.selectedComment.anonymous = anonymous;
                }

                /*create comment*/
                $scope.createComment = function(anonymous, parent){
                    $scope.model.createAction = true;
                    $scope.clearCommentsForm(anonymous);
                    $scope.model.selectedComment.generic_post_id = $scope.genericpostid;
                    $scope.model.selectedComment.parent_id = null;
                    $scope.model.selectedParent = null;
                    if(parent != undefined){
                        $scope.model.selectedParent = parent;
                        $scope.model.selectedComment.parent_id = parent.id;
                        $scope.model.selectedComment.generic_post_id = parent.generic_post_id;
                    }
                    $scope.showCommentSelectorModal();
                }

                /* delete comment*/
                $scope.deleteComments = function(comment_id)
                {
                    swal({
                            title: "Confirme ",
                            text: "Si confirma no será capaz de recuperar estos datos.",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#F3565D",
                            cancelButtonColor: "#E5E5E5 !important",
                            confirmButtonText: "Confirmar",
                            cancelButtonText: "Cancelar",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        },
                        function (isConfirm) {
                            if (isConfirm)
                            {
                                $scope.model.createAction = false;
                                var commentIdCollection = [];
                                if(typeof comment_id == 'string'){
                                    if($scope.model.commentsCollection.length > 0){
                                        for(var i=0; i<$scope.model.commentsCollection.length; i++){
                                            if($scope.model.commentsCollection[i].selected != undefined &&
                                                $scope.model.commentsCollection[i].selected == true)
                                            {
                                                commentIdCollection.push($scope.model.commentsCollection[i].id);
                                            }
                                        }
                                    }
                                }
                                else{
                                    commentIdCollection.push(comment_id);
                                }
                                var data = {
                                    commentsId: commentIdCollection
                                };
                                commentsFact.deleteComments($scope, data, function(response){
                                    if(response.data.success == 0){
                                        toastr.options.timeOut = 5000;
                                        toastr.error(response.data.message,"Error");
                                    }
                                    else{
                                        toastr.success(response.data.message,"¡Hecho!");
                                    }

                                    $scope.getCommentsData();
                                },
                                function(response){
                                    toastr.options.timeOut = 5000;
                                    if(response.data && response.data.message){
                                        toastr.error(response.data.message,"¡Error!");
                                    }
                                    else{
                                        toastr.error("Esta operación no ha podido ejecutarse.","¡Error!");
                                    }
                                });
                            }
                        });

                }

                /*edit comment*/
                $scope.editComment = function(comment){
                    $scope.model.createAction = false;
                    $scope.clearCommentsForm();
                    $scope.model.selectedComment = comment;
                    $scope.showCommentSelectorModal();
                }

                /*change comment priority*/
                $scope.changeCommentPriority = function(comment, operation){
                    $scope.toggleDataLoader();
                    var data = {
                        currentPriority : comment.priority,
                        desiredPriority : (comment.priority + operation),
                        commentId : comment.id
                    }

                    commentsFact.changeCommentPriority($scope, data, function(response){
                        $scope.toggleDataLoader();
                        $scope.getCommentsData();
                    },
                    function(){
                        $scope.toggleDataLoader();
                        $scope.getCommentsData();
                    });
                }

                /*change comment status*/
                $scope.changeCommentStatus = function(){

                    if($scope.model.processingData == false){
                        $scope.model.processingData = true;
                        $scope.toggleDataLoader();
                        var canProceed = true;
                        $scope.clearErrorsCommentsForm();

                        if(!checkPublishedDate()){
                            canProceed = false;

                            if(!checkPublishedDate()){
                                $scope.model.publishedDateHasError = true;
                            }
                        }

                        if(canProceed){
                            if($scope.model.selectedCommentStatus != null){
                                $scope.model.selectedComment.comment_status_id = $scope.model.selectedCommentStatus.id;
                            }
                            var commentsData = {commentData: $scope.model.selectedComment};

                            commentsFact.changeCommentStatus($scope, commentsData,
                                function(response){
                                    $scope.model.processingData = false;
                                    $scope.toggleDataLoader();
                                    if(response.data.success == 0){
                                        toastr.options.timeOut = 5000;
                                        toastr.error(response.data.message,"Error");
                                    }
                                    else{
                                        $scope.clearErrorsCommentsForm();
                                        $scope.clearCommentsForm();
                                        $scope.hideCommentChangeStatusModal();

                                        toastr.success(response.data.message,"¡Hecho!");
                                    }
                                    $scope.goToTop();
                                },
                                function(response){
                                    $scope.model.processingData = true;
                                    $scope.toggleDataLoader();
                                    toastr.options.timeOut = 5000;
                                    if(response.data && response.data.message){
                                        toastr.error(response.data.message,"¡Error!");
                                    }
                                    else{
                                        toastr.error("Esta operación no ha podido ejecutarse.","¡Error!");
                                    }
                                });
                        }
                        else{
                            $scope.model.processingData = false;
                            $scope.toggleDataLoader();
                            toastr.options.timeOut = 3000;
                            toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                        }
                    }
                }

                /*change the comment status*/
                $scope.changeStatus = function(comment){
                    $scope.model.selectedComment = comment;
                    if($scope.model.commentStatusCollection.length > 0){
                        for(var i=0; i<$scope.model.commentStatusCollection.length; i++){
                            if($scope.model.commentStatusCollection[i].id == $scope.model.selectedComment.comment_status_id){
                                $scope.model.selectedCommentStatus = $scope.model.commentStatusCollection[i];
                            }
                        }
                    }

                    $('#comment-change-status-modal').modal('show');
                }

                function checkPublishedDate(){
                    var proceed = true;
                    if($scope.model.selectedComment.published_date != null){
                        if(!dateRegExpress.test($scope.model.selectedComment.published_date)){
                            proceed = false;
                        }
                        else{
                            var publishedDate = $scope.model.selectedComment.published_date;
                            var publishedDateCollection = publishedDate.split('/');
                            if(publishedDateCollection.length == 3){
                                var currentDate = new Date();
                                var publishedDate = new Date(publishedDateCollection[2],publishedDateCollection[1]-1,publishedDateCollection[0]);
                                if(publishedDate > currentDate){
                                    proceed = false;
                                }
                            }
                        }
                    }
                    return proceed;
                }

                /* get the BLocks Data Collection */
                $scope.getCommentsData = function(loadCommentStatus)
                {
                    $scope.toggleDataLoader();
                    var searchParametersCollection = {
                        genericPostId: $scope.genericpostid,
                        treeView : true,
                        filterDate: $scope.model.filterDate,
                        loadCommentStatus: loadCommentStatus
                    };
                    commentsFact.getCommentsData($scope, searchParametersCollection,
                        function(response){
                            $scope.model.commentsCollection = response.data.commentsDataCollection;
                            if(searchParametersCollection.loadCommentStatus != undefined &&
                            searchParametersCollection.loadCommentStatus == true){
                                $scope.model.commentStatusCollection = response.data.commentStatusDataCollection;
                                if($scope.model.commentStatusCollection.length > 0){
                                    $scope.model.selectedCommentStatus = $scope.model.commentStatusCollection[0];
                                }
                            }
                            $scope.toggleDataLoader();
                        },
                        function(response){
                            $scope.toggleDataLoader();
                            toastr.options.timeOut = 16000;
                            if(response.data && response.data.message){
                                toastr.error(response.data.message,"Error");
                            }
                            else{
                                toastr.options.timeOut = 5000;
                                toastr.error("Ha ocurrido un error, por favor intente nuevamente en unos minutos." +
                                    " Si al intentar nuevamente persiste esta notificación de ERROR, asegúrese de que no sea debido " +
                                    "a la conexión o falla en servidores. De lo contrario contacte a los DESARROLLADORES.");
                            }
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
                            toastr.options.timeOut = 16000;
                            if(response.data && response.data.message){
                                toastr.error(response.data.message,"Error");
                            }
                            else{
                                toastr.options.timeOut = 5000;
                                toastr.error("Ha ocurrido un error, por favor intente nuevamente en unos minutos." +
                                    " Si al intentar nuevamente persiste esta notificación de ERROR, asegúrese de que no sea debido " +
                                    "a la conexión o falla en servidores. De lo contrario contacte a los DESARROLLADORES.");
                            }
                        });
                }

                /* function on scope for go ahead to top */
                $scope.goToTop = function()
                {
                    var pageHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
                    $('html, body').animate({scrollTop: pageHeading.height()}, 1000);
                }

                /*hide comment selector*/
                $scope.hideCommentSelectorModal = function(){
                    $scope.model.createAction = null;
                    $scope.clearErrorsCommentsForm();
                    $scope.clearCommentsForm();
                    $('#comment-selector-modal').modal('hide');
                    $scope.goToTop();
                    $scope.getCommentsData();
                }

                /*hide comment change status modal*/
                $scope.hideCommentChangeStatusModal = function(){
                    $scope.model.selectedComment = null;
                    $scope.model.selectedCommentStatus = null;

                    $('#comment-change-status-modal').modal('hide');
                    $scope.goToTop();
                    $scope.getCommentsData(false);
                }

                /* reset the page size to default value 1 */
                $scope.resetPaginationPages = function(element)
                {
                   if(element == 'comment' || element == 'both'){
                       $scope.model.commentCurrentPage = 1;
                       $scope.model.commentPagesCollection = [];
                       $scope.model.commentPagesCollection.push(1);
                       $scope.model.commentCurrentResultStart = 0;
                       $scope.model.commentResultLimit = 0;

                   }
                    if(element == 'comment-element' || element == 'both'){
                        $scope.model.commentElementsCurrentPage = 1;
                        $scope.model.commentElementsPagesCollection = [];
                        $scope.model.commentElementsPagesCollection.push(1);
                        $scope.model.commentElementsCurrentResultStart = 0;
                        $scope.model.commentElementsResultLimit = 0;

                    }
                    $scope.updatePaginationValues(element);

                }

                /*save the selected comments*/
                $scope.saveCommentData = function(){

                    if($scope.model.processingData == false){
                        $scope.model.processingData = true;
                        $scope.toggleDataLoader();
                        var canProceed = true;
                        $scope.clearErrorsCommentsForm();

                        if(($scope.model.selectedComment.anonymous == true &&
                        ($scope.model.selectedComment.author_name == null ||
                        !alfaNumericRegExpr.test($scope.model.selectedComment.author_name) ||
                        $scope.model.selectedComment.email == null )) ||
                        $scope.model.selectedComment.content == undefined ||
                        !alfaNumericRegExpr.test($scope.model.selectedComment.content) ||
                        !checkPublishedDate()){
                            canProceed = false;

                            if($scope.model.selectedComment.anonymous == true &&
                            ($scope.model.selectedComment.author_name == null ||
                            !alfaNumericRegExpr.test($scope.model.selectedComment.author_name))){
                                $scope.model.authorNameHasError = true;
                            }

                            if($scope.model.selectedComment.anonymous == true &&
                             $scope.model.selectedComment.email == null){
                                $scope.model.emailHasError = true;
                            }

                            if($scope.model.selectedComment.content == undefined ||
                            !alfaNumericRegExpr.test($scope.model.selectedComment.content)){
                                $scope.model.contentHasError = true;
                            }

                            if(!checkPublishedDate()){
                                $scope.model.publishedDateHasError = true;
                            }
                        }

                        if(canProceed){
                            if($scope.model.selectedCommentStatus != null){
                                $scope.model.selectedComment.comment_status_id = $scope.model.selectedCommentStatus.id;
                            }
                            var commentsData = {commentData: $scope.model.selectedComment};
                            console.log(commentsData);
                            var action = $scope.model.createAction == true ? 'create' : 'edit';

                            commentsFact.saveCommentsData($scope, commentsData, action,
                                function(response){
                                    $scope.model.processingData = false;
                                    $scope.toggleDataLoader();
                                    if(response.data.success == 0){
                                        toastr.options.timeOut = 5000;
                                        toastr.error(response.data.message,"Error");
                                    }
                                    else{
                                        $scope.clearErrorsCommentsForm();
                                        $scope.clearCommentsForm();
                                        $scope.hideCommentSelectorModal();

                                        toastr.success(response.data.message,"¡Hecho!");
                                    }
                                    $scope.goToTop();
                                },
                                function(response){
                                    $scope.model.processingData = true;
                                    $scope.toggleDataLoader();
                                    toastr.options.timeOut = 5000;
                                    if(response.data && response.data.message){
                                        toastr.error(response.data.message,"¡Error!");
                                    }
                                    else{
                                        toastr.error("Esta operación no ha podido ejecutarse.","¡Error!");
                                    }
                                });
                        }
                        else{
                            $scope.model.processingData = false;
                            $scope.toggleDataLoader();
                            toastr.options.timeOut = 3000;
                            toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
                        }
                    }
                }

                /*selecting/deselecting comment */
                $scope.selectCommentElement = function(event, commentElement){
                    if(commentElement != undefined && commentElement.selected != undefined){
                        commentElement.selected = !commentElement.selected;
                    }
                    else{
                        commentElement.selected = true;
                    }
                }


                /*show modal with comment comments collection*/
                $scope.showCommentSelectorModal = function(){
                    $scope.clearErrorsCommentsForm();
                    if($scope.model.createAction == true){
                        if($scope.model.commentStatusCollection.length > 0){
                            $scope.model.selectedCommentStatus = $scope.model.commentStatusCollection[0];
                        }
                    }
                    else{
                        $scope.model.selectedComment.anonymous = false;
                        if($scope.model.selectedComment.email != null){
                            $scope.model.selectedComment.anonymous = true;
                        }

                        if($scope.model.commentStatusCollection.length > 0){
                            for(var i=0; i<$scope.model.commentStatusCollection.length; i++){
                                if($scope.model.commentStatusCollection[i].id == $scope.model.selectedComment.comment_status_id){
                                    $scope.model.selectedCommentStatus = $scope.model.commentStatusCollection[i];
                                }
                            }
                        }

                        if($scope.model.selectedComment.parent_id == undefined || $scope.model.selectedComment.parent_id == null){
                            $scope.model.selectedParent = null;
                        }

                    }
                    $('#comment-selector-modal').modal('show');
                }

                /* update values of the pagination options */
                $scope.updatePaginationValues = function(element){

                    if(element != undefined && (element == 'comment' || element == 'both')){
                        $scope.model.commentCurrentResultStart = 0;
                        $scope.model.commentCurrentResultLimit = 0;
                        $scope.model.commentCurrentPage = ($scope.model.commentCurrentPage*1);
                        $scope.model.commentCurrentPageSize = ($scope.model.commentCurrentPageSize*1);

                        if($scope.model.commentsCollection.length > 0){
                            $scope.model.commentCurrentResultStart = ($scope.model.commentCurrentPage - 1) * $scope.model.commentCurrentPageSize + 1;
                            $scope.model.commentCurrentResultLimit = ($scope.model.commentCurrentPageSize * $scope.model.commentCurrentPage);
                            if($scope.model.commentsCollection.length < ($scope.model.commentCurrentPageSize * $scope.model.commentCurrentPage)){

                                $scope.model.commentCurrentResultLimit = $scope.model.commentsCollection.length;
                            }
                            var totalPages = Math.ceil($scope.model.commentsCollection.length / $scope.model.commentCurrentPageSize);
                            $scope.model.commentPagesCollection = [];
                            if(totalPages > 0){
                                for(var i=1; i<=totalPages; i++){
                                    $scope.model.commentPagesCollection.push(i);
                                }
                            }
                            else{
                                $scope.model.commentPagesCollection.push(1);
                            }
                        }
                    }

                    if(element != undefined && (element == 'comment-element' || element == 'both')){
                        $scope.model.commentElementsCurrentResultStart = 0;
                        $scope.model.commentElementsCurrentResultLimit = 0;
                        $scope.model.commentElementsCurrentPage = ($scope.model.commentElementsCurrentPage*1);
                        $scope.model.commentElementsCurrentPageSize = ($scope.model.commentElementsCurrentPageSize*1);

                        if($scope.model.commentElementsCollection.length > 0){
                            $scope.model.commentElementsCurrentResultStart = ($scope.model.commentElementsCurrentPage - 1) * $scope.model.commentElementsCurrentPageSize + 1;
                            $scope.model.commentElementsCurrentResultLimit = ($scope.model.commentElementsCurrentPageSize * $scope.model.commentElementsCurrentPage);
                            if($scope.model.commentElementsCollection.length < ($scope.model.commentElementsCurrentPageSize * $scope.model.commentElementsCurrentPage)){

                                $scope.model.commentElementsCurrentResultLimit = $scope.model.commentElementsCollection.length;
                            }
                            var totalPages = Math.ceil($scope.model.commentElementsCollection.length / $scope.model.commentElementsCurrentPageSize);
                            $scope.model.commentElementsPagesCollection = [];
                            if(totalPages > 0){
                                for(var i=1; i<=totalPages; i++){
                                    $scope.model.commentElementsPagesCollection.push(i);
                                }
                            }
                            else{
                                $scope.model.commentElementsPagesCollection.push(1);
                            }
                        }
                    }
                }

                /* toggle data-loading message */
                $scope.toggleDataLoader = function()
                {
                    $scope.model.loadingData = !$scope.model.loadingData;
                }

                /* update the styles of the I-checks components(radio or checkbox) */
                $scope.updateICheckStyles = function(event, icheckType, suffix, identifierClass){

                    var eventType = null;
                    /*ensuring the event comes from the view action*/
                    if(typeof event == 'object'){
                        if(event == null){eventType = 'click';}
                        else{eventType = event.type;}
                    }
                    else{eventType = event;}

                    /*if event is 'mouseover'*/
                    if(eventType == 'mouseover'){
                        if(identifierClass != null){
                            $('.'+identifierClass).find('.i'+icheckType+'_square-blue').addClass('hover');
                        }
                        else{
                            $('.'+suffix).addClass('hover');
                        }

                    }
                    else if(eventType == 'mouseleave'){
                        if(identifierClass != null){
                            $('.'+identifierClass).find('.i'+icheckType+'_square-blue').removeClass('hover');
                        }
                        else{
                            $('.'+suffix).removeClass('hover');
                        }
                    }
                    else{/* event is 'click'*/
                        $('.'+identifierClass).find('.i'+icheckType+'_square-blue').addClass('checked');
                        $('.'+suffix+'-icheck').each(function(){
                            if(icheckType == 'radio'){
                                if(!$(this).hasClass(identifierClass) && $(this).find('.i'+icheckType+'_square-blue').hasClass('checked')){
                                    $(this).find('.i'+icheckType+'_square-blue').removeClass('checked');
                                }
                            }
                        });


                        /*if(suffix == 'comment-type'){
                            $('#comment-types-modal-selector').modal('hide');
                        }*/
                    }

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
                                $scope.getCommentsData(false);
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
                    $scope.model.currentDate = dateFilter(new Date(), 'dd/MM/yyyy');
                    $scope.model.filterDate = null;
                    if($scope.genericpostid == undefined || $scope.genericpostid == null){
                        $scope.model.filterDate = dateFilter(new Date(), 'dd/MM/yyyy');
                    }
                    /*form variables*/
                    $scope.model.selectedComment = {};
                    $scope.model.selectedParent = null;
                    $scope.model.createAction = null;
                    $scope.model.commentStatusCollection = [];

                    /*comments pagination*/
                    $scope.model.commentEntriesSizesCollection = [];
                    $scope.model.commentEntriesSizesCollection = [5,10,20,50,100,150,200];
                    $scope.model.commentCurrentPageSize = 20;
                    $scope.model.commentCurrentPage = 1;
                    $scope.model.commentPagesCollection = [];
                    $scope.model.commentPagesCollection.push(1);
                    $scope.model.commentCurrentResultStart = 0;
                    $scope.model.commentCurrentResultLimit = 0;

                    $scope.getCommentsData(true);
                }
                init();
            },
            template:
                '<div class="row">' +

                    '<div class="col-xs-12">' +
                        '<div class="form-group margin-top-20">' +
                            '<div data-ng-if="genericpostid != undefined && genericpostid != null" class="input-group" style="width:180px;float:left;">' +
                                '<div class="input-group-btn">' +
                                    '<a class="btn toolbar-btn-dropdown-text btn-sm btn-default" ' +
                                    'style="text-align: left; font-size: 14px;" ' +
                                    'data-ng-click="createComment(true)">' +
                                        '<i class="fa fa-plus"></i> ' +
                                        ' Agregar comentario' +
                                    '</a>' +
                                '</div>' +
                            '</div>' +

                            '<a class="btn btn-circle btn-icon-only btn-default pull-right reload" style="" ' +
                            'data-ng-click="getCommentsData()">' +
                                '<i class="icon-refresh"></i>' +
                            '</a>'+

                            '<div data-ng-show="genericpostid == undefined || genericpostid == null" ' +
                            'class="form-group pull-left" style="margin-right:5px;"> '+
                                '<input  '+
                                'class="form-control form-control-inline date-picker " '+
                                'size="16" type="text" '+
                                'data-ng-model="model.filterDate" data-ng-change="updateCommentsForm($event, \'filter-date\')"/> ' +
                            '</div>'+
                            '<span data-ng-if="(genericpostid == undefined || genericpostid == null) && model.filterDate == model.currentDate" ' +
                            'class="help-block pull-left">' +
                                '<p>Hoy</p>' +
                            '</span>'+

                        '</div>' +
                    '</div>' +

                    '<div class="col-xs-12" style="position:relative;margin-top: 30px;">' +
                        '<div data-ng-show="model.commentsCollection.length > 0" class=" " ' +
                        'style="margin-top:35px;">' +
                            '<!-- LEVEL 1 -->' +
                            '<div data-ng-repeat="comment in model.commentsCollection" class="media" ' +
                            'style="padding: 15px 5px;border-top: 1px solid #eee;">' +
                                '<a class="pull-left" style="cursor:auto;text-decoration: none;">' +
                                    '<img class="media-object" ' +
                                    'src="uploads/images/liip_imagine_filtered/logued_user_thumbnail/uploads/images/original/bnc-default-user-avatar.png" alt="">' +
                                '</a>' +
                                '<div class="media-body">' +
                                    '<h4 class="media-heading">' +
                                        '<p style="width:50%">[[comment.author_name]]</p>' +
                                        '<span style="width:50%;text-align:right;">' +
                                            '[[comment.created_date]] ' +
                                            '<a style="margin-bottom:5px;" data-ng-click="createComment(false, comment)"> | Responder </a>' +
                                            '<a style="margin-bottom:5px;" data-ng-if="comment.canEdit" data-ng-click="editComment(comment)"> | Editar </a>' +
                                            '<a style="margin-bottom:5px;" data-ng-click="changeStatus(comment)"> | Cambiar Status </a>' +
                                            '<a style="margin-bottom:5px;color:#F3565D" data-ng-if="comment.canDelete" data-ng-click="deleteComments(comment.id)"> | Eliminar </a>' +
                                        '</span>' +
                                    '</h4>' +
                                    '<p>[[comment.content]]</p>' +
                                    '<p style="padding-bottom: 10px;padding-top: 10px;">' +
                                        '<span class="label label-sm ' +
                                        '[[comment.comment_status_tree_slug ==\'comment-status-pending\'? \'label-warning\' : \'label-success\']] ' +
                                        'pull-left" style="margin-right:5px;">'+
                                            '[[comment.comment_status_name]]'+
                                        '</span>'+
                                    '</p>'+

                                    '<!-- LEVEL 2 -->' +
                                    '<div data-ng-if="comment.childrens.length > 0" data-ng-repeat="children in comment.childrens" class="media" ' +
                                    'style="padding: 15px 5px;border-top: 1px solid #eee;">' +
                                        '<a class="pull-left" style="cursor:auto;text-decoration: none;">' +
                                            '<img class="media-object" ' +
                                            'src="uploads/images/liip_imagine_filtered/logued_user_thumbnail/uploads/images/original/bnc-default-user-avatar.png" alt="">' +
                                        '</a>' +
                                        '<div class="media-body">' +
                                            '<h4 class="media-heading">' +
                                                '<p style="width:50%">[[children.author_name]]</p> ' +
                                                '<span style="width:50%;text-align:right;">' +
                                                    '[[children.created_date]] ' +
                                                    '<a style="margin-bottom:5px;" data-ng-click="createComment(false, children)"> | Responder </a>' +
                                                    '<a style="margin-bottom:5px;" data-ng-if="children.canEdit" data-ng-click="editComment(children)"> | Editar </a>' +
                                                    '<a style="margin-bottom:5px;" data-ng-click="changeStatus(children)"> | Cambiar Status </a>' +
                                                    '<a style="margin-bottom:5px;color:#F3565D" data-ng-if="children.canDelete" data-ng-click="deleteComments(children.id)"> | Eliminar </a>' +
                                                '</span>' +
                                            '</h4>' +
                                            '<p>[[children.content]]</p>' +
                                            '<p style="padding-bottom: 10px;padding-top: 10px;">' +
                                                '<span class="label label-sm ' +
                                                '[[children.comment_status_tree_slug ==\'comment-status-pending\'? \'label-warning\' : \'label-success\']] ' +
                                                'pull-left" style="margin-right:5px;">'+
                                                    '[[children.comment_status_name]]'+
                                                '</span>'+
                                            '</p>'+

                                            '<!-- LEVEL 3 -->' +
                                            '<div data-ng-if="children.childrens.length > 0" data-ng-repeat="grand_children in children.childrens" class="media" ' +
                                            'style="padding: 15px 5px;border-top: 1px solid #eee;">' +
                                                '<a class="pull-left" style="cursor:auto;text-decoration: none;">' +
                                                    '<img class="media-object" ' +
                                                    'src="uploads/images/liip_imagine_filtered/logued_user_thumbnail/uploads/images/original/bnc-default-user-avatar.png" alt="">' +
                                                '</a>' +
                                                '<div class="media-body">' +
                                                    '<h4 class="media-heading">' +
                                                        '<p style="width:50%">[[grand_children.author_name]]</p>' +
                                                        '<span style="width:50%;text-align:right;">' +
                                                            '[[grand_children.created_date]] ' +
                                                            '<a style="margin-bottom:5px;" data-ng-if="grand_children.canEdit" data-ng-click="editComment(grand_children)"> | Editar </a>' +
                                                            '<a style="margin-bottom:5px;" data-ng-click="changeStatus(grand_children)"> | Cambiar Status </a>' +
                                                            '<a style="margin-bottom:5px;color:#F3565D" data-ng-if="grand_children.canDelete" data-ng-click="deleteComments(grand_children.id)"> | Eliminar </a>' +
                                                        '</span>' +
                                                    '</h4>' +
                                                    '<p>[[grand_children.content]]</p>' +
                                                    '<p style="padding-bottom: 10px;padding-top: 10px;">' +
                                                        '<span class="label label-sm ' +
                                                        '[[grand_children.comment_status_tree_slug ==\'comment-status-pending\'? \'label-warning\' : \'label-success\']] ' +
                                                        'pull-left" style="margin-right:5px;">'+
                                                            '[[grand_children.comment_status_name]]'+
                                                        '</span>'+
                                                    '</p>'+
                                                '</div>' +
                                            '</div>'+

                                        '</div>' +
                                    '</div>'+
                                '</div>' +
                            '</div>'+
                        '</div>'+

                        '<div data-ng-if="model.commentsCollection.length == 0" style="position: relative;">'+
                            '<!-- No data to show -->' +
                            '<div style="display:block;text-align: center; color:#93a2a9; padding: 20px;">' +
                                'Sin datos para mostrar...' +
                            '</div>'+
                        '</div>'+
                        '<div data-ng-show="model.loadingData">'+
                            '<div class="data-loader">' +
                                '<div class="sk-data-loader-spinner sk-spinner-three-bounce">' +
                                    '<div class="sk-bounce1"></div>' +
                                    '<div class="sk-bounce2"></div>' +
                                    '<div class="sk-bounce3"></div>' +
                                '</div>' +
                            '</div>'+
                        '</div>'+
                    '</div>'+


                    '<!-- Modal CRUD comments -->' +
                    '<div id="comment-selector-modal" class="modal fade" tabindex="-1" data-width="1200" data-backdrop="static" data-keyboard="false">' +
                        '<div class="modal-header">'+
                            '<button type="button" class="close" data-ng-click="hideCommentSelectorModal()"></button>'+
                            '<h4 class="modal-title">[[model.createAction == true ? \'Agregar \' : \'Responder \']] comentario</h4>'+
                        '</div>'+
                        '<div class="modal-body min-height-400">' +
                            '<form class="form horizontal-form" style="min-height: 150px;position:relative;">' +
                                '<div class="form-body">' +
                                    '<div class="row">' +
                                        '<div class="col-xs-12">' +
                                            '<div class="form-group">' +
                                                '<div class="iradio_square-blue [[!model.selectedComment.anonymous ? \'checked\' : \'\']]" ' +
                                                'data-ng-click="updateCommentsForm($event, \'comment-mode\', false)">' +
                                                '</div>' +
                                                '<span style="margin-right: 15px;">Como Moderador</span>' +
                                                '<div class="iradio_square-blue [[model.selectedComment.anonymous ? \'checked\' : \'\']]" ' +
                                                'data-ng-click="updateCommentsForm($event, \'comment-mode\', true)">' +
                                                '</div>' +
                                                '<span>Como Visitante</span>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div data-ng-if="model.selectedParent != null" class="col-xs-12" ' +
                                        'style="padding: 15px;background-color: #ddd;margin-bottom: 30px;">' +
                                            '<span>Respondiendo a</span>' +
                                            '<div style="padding: 15px 5px;border-top: 1px solid #eee;">' +
                                                '<a class="pull-left" style="cursor:auto;text-decoration: none;">' +
                                                    '<img class="media-object" ' +
                                                    'src="uploads/images/liip_imagine_filtered/logued_user_thumbnail/uploads/images/original/bnc-default-user-avatar.png" alt="">' +
                                                '</a>' +
                                                '<div class="media-body" style="padding-left: 10px;">' +
                                                    '<h4 class="media-heading">' +
                                                        '[[model.selectedParent.author_name]] ' +
                                                        '<span>' +
                                                            '[[model.selectedParent.created_date]]' +
                                                        '</span>' +
                                                    '</h4>' +
                                                    '<p>[[model.selectedParent.content]]</p>' +
                                                '</div>' +
                                            '</div>'+
                                        '</div>' +


                                        '<div data-ng-if="model.selectedComment.anonymous" class="col-xs-12 col-md-6">' +
                                            '<div class="form-group [[model.authorNameHasError ? \'has-error\' : \'\']]">' +
                                                '<label class="control-label">Nombre</label>' +
                                                '<input class="form-control" type="text" placeholder="No más de 200 caracteres." ' +
                                                'data-ng-model="model.selectedComment.author_name" maxlength="200">' +
                                                '<span  data-ng-if="model.authorNameHasError" class="help-block">' +
                                                    '<p>Valor incorrecto o en blanco.</p>' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div data-ng-if="model.selectedComment.anonymous" class="col-xs-12 col-md-6">' +
                                            '<div class="form-group [[model.emailHasError ? \'has-error\' : \'\']]">' +
                                                '<label class="control-label">Correo</label>' +
                                                '<input class="form-control" type="text" placeholder="No más de 60 caracteres." ' +
                                                'data-ng-model="model.selectedComment.email" maxlength="60">' +
                                                '<span data-ng-if="model.emailHasError" class="help-block">' +
                                                    '<p>Valor incorrecto o en blanco.</p>' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div class="col-xs-12 col-md-6">' +
                                            '<div class="form-group margin-top-20">' +
                                                '<label class="control-label">Status</label>' +
                                                '<div class="input-group">' +
                                                    '<div class="input-group-btn">' +
                                                        '<a class="btn toolbar-btn-dropdown-text btn-sm btn-default dropdown-toggle" ' +
                                                        ' style="text-align: left; font-size: 14px;" ' +
                                                        ' data-toggle="dropdown" data-hover="dropdown" data-close-others="true">' +
                                                            '[[model.selectedCommentStatus == null ? \'Seleccione\' : model.selectedCommentStatus.name_es]] ' +
                                                            '<i class="fa fa-angle-down"></i>' +
                                                        '</a>' +
                                                        '<div class="dropdown-menu hold-on-click dropdown-checkboxes " ' +
                                                        'style="min-width: 275px;top: 25px;margin-left: 0px;">' +
                                                            '<label data-ng-repeat="commentStatus in model.commentStatusCollection">' +
                                                                '<a class="btn" style="width: 100%;text-align: left;" ' +
                                                                'data-ng-click="updateCommentsForm($event, \'status\', commentStatus)"> ' +
                                                                    '[[commentStatus.name_es]] ' +
                                                                ' </a>' +
                                                            '</label>' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div class="col-xs-12 col-md-6">'+
                                            '<div class="form-group margin-top-20 [[model.publishedDateHasError ? \'has-error\' : \'\']]"> '+
                                                '<label class="control-label">Fecha de publicación</label>'+
                                                '<input data-ng-show="model.selectedCommentStatus.tree_slug == \'comment-status-approved\'" '+
                                                'class="form-control form-control-inline date-picker" '+
                                                'size="16" type="text" '+
                                                'data-ng-model="model.selectedComment.published_date"/> '+
                                                '<span class="help-block"> '+
                                                    '<p data-ng-if="model.selectedCommentStatus.tree_slug == \'comment-status-pending\'">'+
                                                        'El status debe de estar "Publicado".'+
                                                    '</p>'+
                                                    '<p data-ng-if="!model.publishedDateHasError && model.selectedCommentStatus.tree_slug == \'comment-status-approved\'">'+
                                                        'Ejemplo: 19/07/1985'+
                                                    '</p>'+
                                                    '<p data-ng-if="model.publishedDateHasError && model.selectedCommentStatus.tree_slug == \'comment-status-approved\'">'+
                                                       'Valor incorrecto o en blanco.'+
                                                    '</p>'+
                                                '</span>'+
                                            '</div>'+
                                        '</div>'+

                                        '<div class="col-xs-12">'+
                                            '<div class="form-group [[model.contentHasError ? \'has-error\' : \'\']]" style="margin-top:40px;">'+
                                                '<label class="control-label">'+
                                                    'Cuerpo del comentario'+
                                                '</label>'+
                                                '<textarea data-ng-model="model.selectedComment.content" '+
                                                'style="width: 100%;height: 200px;"></textarea>'+
                                                '<span class="help-block">'+
                                                    '<p data-ng-if="model.contentHasError"> '+
                                                        'Valor incorrecto o en blanco. '+
                                                    '</p>'+
                                                '</span>'+
                                            '</div>'+
                                        '</div>'+

                                    '</div>' +

                                '</div>' +
                                '<div class="form-actions" style="background-color:white;">' +
                                    '<div class="row">' +
                                        '<div class="col-xs-12 col-md-offset-4 col-md-8">' +
                                            '<button class="btn default btn-footer" type="button" ' +
                                            'data-ng-click="hideCommentSelectorModal()">'+
                                            'Cancelar </button>'+
                                            '<button class="btn blue btn-blue btn-footer" type="submit" ' +
                                            'data-ng-click="saveCommentData()">'+
                                            'Guardar </button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+

                                '<div data-ng-show="model.loadingData">'+
                                    '<div class="data-loader">' +
                                        '<div class="sk-data-loader-spinner sk-spinner-three-bounce">' +
                                            '<div class="sk-bounce1"></div>' +
                                            '<div class="sk-bounce2"></div>' +
                                            '<div class="sk-bounce3"></div>' +
                                        '</div>' +
                                    '</div>'+
                                '</div>'+

                            '</form>'+
                        '</div>'+
                    '</div>'+

                    '<!-- Modal Change Status -->' +
                    '<div id="comment-change-status-modal" class="modal fade" tabindex="-1" data-width="1200" data-backdrop="static" data-keyboard="false">' +
                        '<div class="modal-header">'+
                            '<button type="button" class="close" data-ng-click="hideCommentChangeStatusModal()"></button>'+
                            '<h4 class="modal-title"> Cambiar status</h4>'+
                        '</div>'+
                        '<div class="modal-body min-height-400">' +
                            '<form class="form horizontal-form" style="min-height: 150px;position:relative;">' +
                                '<div class="form-body">' +
                                    '<div class="row">' +

                                        '<div class="col-xs-12" ' +
                                        'style="padding: 15px;background-color: #ddd;margin-bottom: 30px;">' +
                                            '<span>Comentario :</span>' +
                                             '<div style="padding: 15px 5px;border-top: 1px solid #eee;">' +
                                                '<a class="pull-left" style="cursor:auto;text-decoration: none;">' +
                                                    '<img class="media-object" ' +
                                                    'src="uploads/images/liip_imagine_filtered/logued_user_thumbnail/uploads/images/original/bnc-default-user-avatar.png" alt="">' +
                                                '</a>' +
                                                '<div class="media-body" style="padding-left: 10px;">' +
                                                    '<h4 class="media-heading">' +
                                                        '[[model.selectedComment.author_name]] ' +
                                                        '<span>' +
                                                            '[[model.selectedComment.created_date]]' +
                                                        '</span>' +
                                                    '</h4>' +
                                                    '<p>[[model.selectedComment.content]]</p>' +
                                                '</div>' +
                                            '</div>'+
                                        '</div>' +

                                        '<div class="col-xs-12 col-md-6">' +
                                            '<div class="form-group margin-top-20">' +
                                                '<label class="control-label">Status</label>' +
                                                '<div class="input-group">' +
                                                    '<div class="input-group-btn">' +
                                                        '<a class="btn toolbar-btn-dropdown-text btn-sm btn-default dropdown-toggle" ' +
                                                        ' style="text-align: left; font-size: 14px;" ' +
                                                        ' data-toggle="dropdown" data-hover="dropdown" data-close-others="true">' +
                                                            '[[model.selectedCommentStatus == null ? \'Seleccione\' : model.selectedCommentStatus.name_es]] ' +
                                                            '<i class="fa fa-angle-down"></i>' +
                                                        '</a>' +
                                                        '<div class="dropdown-menu hold-on-click dropdown-checkboxes " ' +
                                                        'style="min-width: 275px;top: 25px;margin-left: 0px;">' +
                                                            '<label data-ng-repeat="commentStatus in model.commentStatusCollection">' +
                                                                '<a class="btn" style="width: 100%;text-align: left;" ' +
                                                                'data-ng-click="updateCommentsForm($event, \'status\', commentStatus)"> ' +
                                                                    '[[commentStatus.name_es]] ' +
                                                                ' </a>' +
                                                            '</label>' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +

                                        '<div class="col-xs-12 col-md-6">'+
                                            '<div class="form-group margin-top-20 [[model.publishedDateHasError ? \'has-error\' : \'\']]"> '+
                                                '<label class="control-label">Fecha de publicación</label>'+
                                                '<input data-ng-show="model.selectedCommentStatus.tree_slug == \'comment-status-approved\'" '+
                                                'class="form-control form-control-inline date-picker" '+
                                                'size="16" type="text" '+
                                                'data-ng-model="model.selectedComment.published_date"/> '+
                                                '<span class="help-block"> '+
                                                    '<p data-ng-if="model.selectedCommentStatus.tree_slug == \'comment-status-pending\'">'+
                                                        'El status debe de estar "Publicado".'+
                                                    '</p>'+
                                                    '<p data-ng-if="!model.publishedDateHasError && model.selectedCommentStatus.tree_slug == \'comment-status-approved\'">'+
                                                        'Ejemplo: 19/07/1985'+
                                                    '</p>'+
                                                    '<p data-ng-if="model.publishedDateHasError && model.selectedCommentStatus.tree_slug == \'comment-status-approved\'">'+
                                                        'Valor incorrecto o en blanco.'+
                                                    '</p>'+
                                                '</span>'+
                                            '</div>'+
                                        '</div>'+

                                    '</div>' +
                                '</div>' +
                                '<div class="form-actions" style="background-color:white;">' +
                                    '<div class="row">' +
                                        '<div class="col-xs-12 col-md-offset-4 col-md-8">' +
                                            '<button class="btn default btn-footer" type="button" ' +
                                            'data-ng-click="hideCommentChangeStatusModal()">'+
                                            'Cancelar </button>'+
                                            '<button class="btn blue btn-blue btn-footer" type="submit" ' +
                                            'data-ng-click="changeCommentStatus()">'+
                                            'Guardar </button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+

                                '<div data-ng-show="model.loadingData">'+
                                    '<div class="data-loader">' +
                                        '<div class="sk-data-loader-spinner sk-spinner-three-bounce">' +
                                            '<div class="sk-bounce1"></div>' +
                                            '<div class="sk-bounce2"></div>' +
                                            '<div class="sk-bounce3"></div>' +
                                        '</div>' +
                                    '</div>'+
                                '</div>'+

                            '</form>'+
                        '</div>'+
                    '</div>'+

                '</div>'
        }

        return directiveDefinitionObject;
    }]);
})();
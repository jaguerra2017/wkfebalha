/*
 * File for handling controllers for Backend Comments Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.commentsController', ['BncBackend.commentsFactory']);


    /* Controller for handling Comments functions */
    function commentsCtrller($scope, $filter, commentsFact){

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
            $scope.model.selectedComment.generic_post_id = null;
            $scope.model.selectedComment.parent_id = null;
            $scope.model.selectedParent = null;
            if(parent != undefined){
                $scope.model.selectedParent = parent;
                $scope.model.selectedComment.generic_post_id = parent.generic_post_id;
                $scope.model.selectedComment.parent_id = parent.id;
            }
            $scope.showCommentSelectorModal();
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

        /* delete comment*/
        $scope.deleteComments = function(comment_id)
        {
            var proceed = true;
            if(typeof comment_id == 'string' &&
            ($scope.model.canDeleteComments == false || $scope.model.canDeleteComments == undefined)){
                proceed = false;
            }

            if(proceed){
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

                                    $scope.getComments();
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
        }

        /*edit comment*/
        $scope.editComment = function(comment){
            $scope.model.createAction = false;
            $scope.clearCommentsForm();
            $scope.model.selectedComment = comment;
            $scope.showCommentSelectorModal();
        }

        /* change the view mode of the comments data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.commentsCollection = [];
            $scope.model.activeView = option;
            $scope.handleCrudOperations('reset');
            $scope.getComments();
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

        /* get the Comments Collection */
        $scope.getComments = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.activeView == 'pending_list'){
                searchParametersCollection.getOnlyPendings = true;
            }
            searchParametersCollection.getAll = true;
            commentsFact.getCommentsData($scope,searchParametersCollection, function(response){
                $scope.model.commentsCollection = response.data.commentsDataCollection;
                if($scope.model.activeView == 'simple_list'){
                    $scope.updatePaginationValues();
                }
                $scope.toggleDataLoader();
            },
            function(response){
                if($scope.model.activeView == 'simple_list'){
                    $scope.updatePaginationValues();
                }
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

        /* disabled options for CRUD operations */
        $scope.handleCrudOperations = function(option)
        {
            /* when option is 'disable' */
            if(option == 'disable'){
                $scope.model.canCreateComments = false;
                $scope.model.canEditComments = false;
                $scope.model.canDeleteComments = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateComments = true;
                $scope.model.canEditComments = false;
                $scope.model.canDeleteComments = false;
                $scope.model.allCommentsSelected = false;
                $scope.model.selectedComment = null;
            }

        }

        /*hide comment selector*/
        $scope.hideCommentSelectorModal = function(){
            $scope.model.createAction = null;
            $scope.clearErrorsCommentsForm();
            $scope.clearCommentsForm();
            $('#comment-selector-modal').modal('hide');
            $scope.goToTop();
            $scope.getComments();
        }

        /*hide comment change status modal*/
        $scope.hideCommentChangeStatusModal = function(){
            $scope.model.selectedComment = null;
            $scope.model.selectedCommentStatus = null;

            $('#comment-change-status-modal').modal('hide');
            $scope.goToTop();
            $scope.getComments(false);
        }

        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.commentsCurrentPage = 1;
            $scope.model.commentsPagesCollection = [];
            $scope.model.commentsPagesCollection.push(1);
            $scope.model.commentsCurrentResultStart = 0;
            $scope.model.commentsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
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

        /* search Comments through Search Input Field */
        $scope.searchComments = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getComments();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getComments();
            }
        }

        /* selecting/deselecting all comments */
        $scope.selectAllComments = function(event){
            var canDeleteAll = true;
            $scope.model.allCommentsSelected = !$scope.model.allCommentsSelected;
            if(!$scope.model.allCommentsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.commentsCollection.length; i++){
                $scope.model.commentsCollection[i].selected = $scope.model.allCommentsSelected;
                if($scope.model.allCommentsSelected == true && $scope.model.commentsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteComments = canDeleteAll;
        }

        /*selecting/deselecting comments */
        $scope.selectComments= function(event,comments){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalCommentsSelected = 1;
            comments.selected = !comments.selected;
            if($scope.model.commentsCollection.length == 1){
                if(comments.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalCommentsSelected = 0;
                }
                if(comments.canDelete == 0){
                    canDeleteAll = false;
                }
                if(comments.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.commentsCollection.length > 1){
                totalCommentsSelected = 0;
                for(var i=0; i<$scope.model.commentsCollection.length; i++){
                    var comments = $scope.model.commentsCollection[i];
                    if(comments.selected == true){
                        totalCommentsSelected++;
                        if(comments.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(comments.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalCommentsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteComments = true;
                    if(totalCommentsSelected == $scope.model.commentsCollection.length){
                        $scope.model.allCommentsSelected = true;
                    }
                    else{
                        $scope.model.allCommentsSelected = false;
                    }
                }
                if(totalCommentsSelected == 1 && canEditAll == true){
                    $scope.model.canEditComments = true;
                }
                else{
                    $scope.model.canEditComments = false;
                }
            }
            else{
                $scope.model.canEditComments = false;
                $scope.model.canDeleteComments = false;
                $scope.model.allCommentsSelected = false;
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

        /* slugify text */
        function slugify(textToSlugify){
            var slugifiedText = textToSlugify.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\\-]+/g, '')
                .replace(/\\-\\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');

            return slugifiedText;
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


                if(suffix == 'comments-type'){
                    $('#comments-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.commentsCurrentResultStart = 0;
            $scope.model.commentsCurrentResultLimit = 0;
            $scope.model.commentsCurrentPage = ($scope.model.commentsCurrentPage*1);
            $scope.model.commentsCurrentPageSize = ($scope.model.commentsCurrentPageSize*1);
            if($scope.model.commentsCollection.length > 0){
                $scope.model.commentsCurrentResultStart = ($scope.model.commentsCurrentPage - 1) * $scope.model.commentsCurrentPageSize + 1;
                $scope.model.commentsCurrentResultLimit = ($scope.model.commentsCurrentPageSize * $scope.model.commentsCurrentPage);
                if($scope.model.commentsCollection.length < ($scope.model.commentsCurrentPageSize * $scope.model.commentsCurrentPage)){

                    $scope.model.commentsCurrentResultLimit = $scope.model.commentsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.commentsCollection.length / $scope.model.commentsCurrentPageSize);
                $scope.model.commentsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.commentsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.commentsPagesCollection.push(1);
                }

            }
            $scope.handleCrudOperations('reset');
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
            }
        }

        /*update form views section*/
        $scope.updateFormSection = function(section){
            $scope.model.formActiveView = section;
        }

        


        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.model.commentsCollection = [];
            $scope.model.commentsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'pending_list';
            $scope.model.allCommentsSelected = false;
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.commentsCurrentPageSize = 20;
            $scope.model.commentsCurrentPage = 1;
            $scope.model.commentsPagesCollection = [];
            $scope.model.commentsPagesCollection.push(1);
            $scope.model.commentsCurrentResultStart = 0;
            $scope.model.commentsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.loadingData = false;
            $scope.model.showCommentsForm = false;
            $scope.model.processingData = false;
            $scope.model.commentStatusCollection = [];
            $scope.model.selectedComment = null;
            $scope.model.selectedParent = null;

            $scope.clearCommentsForm();
            commentsFact.loadInitialsData($scope, function(response){
                $scope.model.commentsCollection = response.data.initialsData.commentsDataCollection;
                $scope.model.commentStatusCollection = response.data.initialsData.commentStatusDataCollection;
                if($scope.model.commentStatusCollection.length > 0){
                    $scope.model.selectedCommentStatus = $scope.model.commentStatusCollection[0];
                }
            },
            function(response){
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
        init();

    }


    /* Declaring controllers functions for this module */
    angular.module('BncBackend.commentsController').controller('commentsCtrller',commentsCtrller);
})();
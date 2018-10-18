/*
 * File for handling controllers for Backend Bookings Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.checkBookingController', ['BncBackend.checkBookingFactory']);


    /* Controller for handling Bookings functions */
    function checkBookingCtrller($scope, $filter, checkBookingFact){

        /*
         * Global variables
         * 
         * */
        var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");
        var dateFilter = $filter("date");
        var dateRegExpress = new RegExp("[0-9]{2}/\[0-9]{2}/\[0-9]{4}");


        /* change the view mode of the checkBooking data */
        $scope.changeViewMode = function(option)
        {
            $scope.model.bookingsCollection = [];
            $scope.model.activeView = option;
            $scope.getBookings();
        }

        /* get the Bookings Collection */
        $scope.getBookings = function()
        {
            $scope.toggleDataLoader();
            var searchParametersCollection = {};
            if($scope.model.generalSearchValue != null){
                if(alfaNumericRegExpr.test($scope.model.generalSearchValue) &&
                $scope.model.showBookingsForm == false){
                    searchParametersCollection.generalSearchValue = $scope.model.generalSearchValue;
                }
            }
            checkBookingFact.getBookingsData($scope,searchParametersCollection, function(response){
                    $scope.model.bookingsCollection = response.data.bookingsDataCollection;
                    $scope.updatePaginationValues();
                    $scope.toggleDataLoader();
                },
                function(response){
                    t$scope.updatePaginationValues();
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
                $scope.model.canCreateBookings = false;
                $scope.model.canEditBookings = false;
                $scope.model.canDeleteBookings = false;
            }
            else{/* else if 'reset'*/
                $scope.model.canCreateBookings = true;
                $scope.model.canEditBookings = false;
                $scope.model.canDeleteBookings = false;
                $scope.model.allBookingsSelected = false;
                $scope.model.selectedBooking = null;
            }

        }


        /* reset the page size to default value 1 */
        $scope.resetPaginationPages = function()
        {
            $scope.model.bookingsCurrentPage = 1;
            $scope.model.bookingsPagesCollection = [];
            $scope.model.bookingsPagesCollection.push(1);
            $scope.model.bookingsCurrentResultStart = 0;
            $scope.model.bookingsCurrentResultLimit = 0;

            $scope.updatePaginationValues();
        }

        /* search Bookings through Search Input Field */
        $scope.searchBookings = function($event)
        {
            /*when ENTER key are press OR input value are empty */
            if(($event.keyCode == 13 && alfaNumericRegExpr.test($scope.model.generalSearchValue)) 
               || !alfaNumericRegExpr.test($scope.model.generalSearchValue)){
                $scope.getBookings();
            }/*when ESCAPE key are press*/
            else if($event.keyCode == 27){
                $scope.model.generalSearchValue = null;
                $scope.getBookings();
            }
        }

        /* selecting/deselecting all bookings */
        $scope.selectAllBookings = function(event){
            var canDeleteAll = true;
            $scope.model.allBookingsSelected = !$scope.model.allBookingsSelected;
            if(!$scope.model.allBookingsSelected){
                canDeleteAll = false;
            }
            for(var i= 0; i<$scope.model.bookingsCollection.length; i++){
                $scope.model.bookingsCollection[i].selected = $scope.model.allBookingsSelected;
                if($scope.model.allBookingsSelected == true && $scope.model.bookingsCollection[i].canDelete == 0){
                    canDeleteAll = false;
                }
            }

            $scope.model.canDeleteBookings = canDeleteAll;
        }

        /*selecting/deselecting bookings */
        $scope.selectBookings= function(event,bookings){
            var canDeleteAll = true;
            var canEditAll = true;
            var totalBookingsSelected = 1;
            bookings.selected = !bookings.selected;
            if($scope.model.bookingsCollection.length == 1){
                if(bookings.selected == false){
                    canDeleteAll = false;
                    canEditAll = false;
                    totalBookingsSelected = 0;
                }
                if(bookings.canDelete == 0){
                    canDeleteAll = false;
                }
                if(bookings.canDelete == 0){
                    canEditAll = false;
                }
            }
            else if($scope.model.bookingsCollection.length > 1){
                totalBookingsSelected = 0;
                for(var i=0; i<$scope.model.bookingsCollection.length; i++){
                    var bookings = $scope.model.bookingsCollection[i];
                    if(bookings.selected == true){
                        totalBookingsSelected++;
                        if(bookings.canDelete == 0){
                            canDeleteAll = false;
                        }
                        if(bookings.canEdit == 0){
                            canEditAll = false;
                        }
                    }
                }
            }

            if(totalBookingsSelected > 0)
            {
                if(canDeleteAll == true){
                    $scope.model.canDeleteBookings = true;
                    if(totalBookingsSelected == $scope.model.bookingsCollection.length){
                        $scope.model.allBookingsSelected = true;
                    }
                    else{
                        $scope.model.allBookingsSelected = false;
                    }
                }
                if(totalBookingsSelected == 1 && canEditAll == true){
                    $scope.model.canEditBookings = true;
                }
                else{
                    $scope.model.canEditBookings = false;
                }
            }
            else{
                $scope.model.canEditBookings = false;
                $scope.model.canDeleteBookings = false;
                $scope.model.allBookingsSelected = false;
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


                if(suffix == 'bookings-type'){
                    $('#bookings-types-modal-selector').modal('hide');
                }
            }

        }

        /* update values of the pagination options */
        $scope.updatePaginationValues = function(){
            $scope.model.bookingsCurrentResultStart = 0;
            $scope.model.bookingsCurrentResultLimit = 0;
            $scope.model.bookingsCurrentPage = ($scope.model.bookingsCurrentPage*1);
            $scope.model.bookingsCurrentPageSize = ($scope.model.bookingsCurrentPageSize*1);

            if($scope.model.bookingsCollection.length > 0){
                $scope.model.bookingsCurrentResultStart = ($scope.model.bookingsCurrentPage - 1) * $scope.model.bookingsCurrentPageSize + 1;
                $scope.model.bookingsCurrentResultLimit = ($scope.model.bookingsCurrentPageSize * $scope.model.bookingsCurrentPage);
                if($scope.model.bookingsCollection.length < ($scope.model.bookingsCurrentPageSize * $scope.model.bookingsCurrentPage)){

                    $scope.model.bookingsCurrentResultLimit = $scope.model.bookingsCollection.length;
                }

                var totalPages = Math.ceil($scope.model.bookingsCollection.length / $scope.model.bookingsCurrentPageSize);
                $scope.model.bookingsPagesCollection = [];
                if(totalPages > 0){
                    for(var i=1; i<=totalPages; i++){
                        $scope.model.bookingsPagesCollection.push(i);
                    }
                }
                else{
                    $scope.model.bookingsPagesCollection.push(1);
                }
            }

            $scope.handleCrudOperations('reset');
        }

        function init(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*list view variables*/
            $scope.model.bookingsCollection = [];
            $scope.model.bookingsSelectedCounter = 0;
            $scope.model.generalSearchValue = null;
            $scope.model.activeView = 'simple_list';
            /*pagination*/
            $scope.model.entriesSizesCollection = [];
            $scope.model.entriesSizesCollection = [5,10,20,50,100,150,200];
            $scope.model.bookingsCurrentPageSize = 20;
            $scope.model.bookingsCurrentPage = 1;
            $scope.model.bookingsPagesCollection = [];
            $scope.model.bookingsPagesCollection.push(1);
            $scope.model.bookingsCurrentResultStart = 0;
            $scope.model.bookingsCurrentResultLimit = 0;
            /*form view variables*/
            $scope.model.createAction = null;
            $scope.model.bncDomain = '';
            $scope.model.formActiveView = 'general-info';
            $scope.model.allBookingsSelected = false;
            $scope.model.loadingData = false;
            $scope.model.showBookingsForm = false;
            $scope.model.processingData = false;
            $scope.model.featureImage = {};
            $scope.model.postStatusCollection = [];
            $scope.model.selectedCategoriesCollection = null;
            $scope.model.selectedBooking = null;

            checkBookingFact.loadInitialsData($scope, function(response){
                $scope.model.bookingsCollection = response.data.initialsData.bookingsDataCollection;
                $scope.model.bncDomain = response.data.initialsData.bncDomain;
                if($scope.model.bncDomain == null || ($scope.model.bncDomain != null && $scope.model.bncDomain.length == 0)){
                    $scope.model.bncDomain = '(www.tudominio.com)';
                }

                    $scope.updatePaginationValues();
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
    angular.module('BncBackend.checkBookingController').controller('checkBookingCtrller',checkBookingCtrller);
})();
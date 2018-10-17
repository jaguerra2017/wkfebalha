/*
 * File for handling
 * */

(function () {
  'use strict';

  /* Declare app level module which depends on views, and components */
  angular.module('BncBackend.programDirective', ['BncBackend.programFactory']);

  /* Declaring directive functions for this module */
  angular.module('BncBackend.programDirective').directive('program', [function () {
    var directiveDefinitionObject = {
      restrict: "E",
      replace: true,
      scope: {
        showFormCtrlFn: '&',
        deleteCtrlFn: '&',
        currentLanguage: "=",
        userRole: "=",
        home: "="
      },
      controller: function ($scope, $element, programFact) {

        /*
         * Global variables
         *
         * */
        var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");

        /* toggle data-loading message */
        $scope.toggleDataLoader = function () {
          $scope.model.loadingData = !$scope.model.loadingData;
        }

        /* function on scope for go ahead to top */
        $scope.goToTop = function () {
          var pageHeading = $('.navbar-fixed-top');
          /*#go-to-top-anchor*/
          $('html, body').animate({scrollTop: pageHeading.height()}, 1000);
        }

        $scope.goBooking = function (show) {
          console.log(show);
        }

        $scope.addShow = function (date, room) {
          var extraData = {
            date: date,
            room: room
          };
          $scope.showFormCtrlFn({from:'directive', extraData: extraData});
        }

        $scope.deleteShow = function(show){
          $scope.deleteCtrlFn({showid:show.id});
        }



        $scope.scrollDown = function (element) {
          if(element == 'block_1'){
            $('html, body').animate({scrollTop: $("#"+element).offset().top + 800}, 1000);
          }
        }

        $scope.initVisualization = function () {
          /*list view variables*/
          $scope.model.loadingData = false;
        }

        function init() {
          /*generals variables*/
          $scope.model = {};
          if($scope.userRole == 'ROLE_ADMIN' || $scope.userRole == 'ROLE_SALESMAN'){

          }
          $scope.model.rooms = {};
          $scope.model.showData = {};
          $scope.model.outerIndex = null;
          $scope.model.selectedShow = null;

          $scope.toggleDataLoader();
          var searchParametersCollection = {
            'orderDates':true,
            'currentLanguage': $scope.currentLanguage,
            'home': $scope.home ? $scope.home : false
          };
          var programData = {programData: searchParametersCollection};
          programFact.loadInitialsData($scope, programData, function (response) {
            $scope.model.rooms = response.data.initialsData.programDataCollection.rooms;
            $scope.model.showData = response.data.initialsData.programDataCollection.shows;
            $scope.initVisualization();
          });
        }

        init();
      },
      template:
      '<div>'+
      '<div class="row" style="margin-top:40px;"> '+
      '<div class="col-xs-12" style="margin-bottom: 20px">' +
      // '<button style="float: right" type="button" class="btn btn-outline btn-primary">Descargar</button>' +
      '</div> '+
      '<div data-ng-repeat="room in model.rooms" class="col-md-2 col-sm-4 col-xs-6">\n' +
      '    <div class="dummy"></div>\n' +
      '    <div class="thumbnail purple">[[room.headquarter]]<br> ([[room.title]])</div>\n' +
      '  </div>'+
      '  </div>'+
      '<div class="row" data-ng-repeat="(key, show) in model.showData" ng-init="outerIndex = $index">\n' +
      '<div class="col-xs-12">'+
      '<h3 class="app-text-color">[[key]]</h3>'+
      '</div>' +
      '  <div style="margin-top: -50px;" data-ng-repeat="room in model.rooms" class="col-md-2 col-sm-4 col-xs-6">\n' +
      '    <div class="dummy"></div>\n' +
      '    <div data-ng-if="show[room.id] != null" class="thumbnail_show tumbnail_not_empty "><strong>[[show[room.id].show_time]]</strong><br>[[show[room.id].title]]' +
      '<div data-ng-if="userRole == \'ROLE_SALESMAN\'">' +
      '<reserve from="\'program\'" showid="show[room.id].id" user-role="userRole" current-language="currentLanguage" selectedroom="room.id"></reserve>'+
      '</div>'+
      // '<a data-ng-if="userRole == \'ROLE_ADMIN\'" title="Eliminar" data-ng-click="deleteShow(show[room.id])" class="btn btn-circle-sm btn-danger"><span><i class="icon-trash"></i></span> </a>' +
      '</div>\n' +
      '    <div data-ng-if="show[room.id] == null" class="thumbnail_show [[outerIndex % 2 == 0 ? \'tumbnail_odd_empty\' : \'tumbnail_pair_empty\']]">' +
      '<a style="color: white" data-ng-if="userRole == \'ROLE_ADMIN\'" data-ng-click="addShow(show.dateGeneral, room)" class="btn"><span><i style="font-size: 18px" class="icon-plus"></i></span> </a>' +
      '</div>\n' +
      '  </div>\n' +
      '</div>' +
      '</div>'
    }

    return directiveDefinitionObject;
  }]);
})();
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

        $scope.parseRoom = function (title) {
          var mella = title.search("Mella");
          var marti = title.search("Mart");
          if(mella > 0 || marti > 0){
            return '';
          }
          return '('+title+')';
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
      '<div class="row" style="margin-top:40px;display: flex;"> '+
      // '<div class="col-xs-12" style="margin-bottom: 20px">' +
      // '<button style="float: right" type="button" class="btn btn-outline btn-primary">Descargar</button>' +
      // '</div> '+
      '<div data-ng-repeat="room in model.rooms" class="col-md-3 col-sm-4 col-xs-6">\n' +
      '    <div class="thumbnail_mine purple schedule-header">' +
      '<div style="color: white" ' +
      // 'href="[[model.domain]]/[[model.headquarter_url_slug]]/[[currentLanguage]]/[[room.url_slug]]"
      '> [[room.headquarter]]<br> [[parseRoom(room.title)]] '+
      '</div></div>\n' +
      '  </div>'+
      '  </div>'+
      '<div style="display: flex; flex-direction: column;" class="row" data-ng-repeat="(key, show) in model.showData" ng-init="outerIndex = $index">\n' +
      ' <div class="col-xs-12">'+
      '   <h3 class="app-text-color schedule-date-title">[[key]]</br><p class="title-separator" style="float:left;"></p></h3>'+
      ' </div>' +
      '<div style="display: flex; width: 100%; margin-top: 38px" >'+
      '  <div data-ng-repeat="room in model.rooms" class="col-md-3 col-sm-4 col-xs-6">\n' +
      '<div class="thumbnail_show tumbnail_not_empty" data-ng-if="show[room.id] != null">'+
      '    <div ' +
      // 'href="[[model.domain]]/[[model.url_slug]]/[[currentLanguage]]/[[show[room.id].url_slug]]" ' +
      '>' +
      '<strong>[[show[room.id].show_time]]</strong><br>' +
      '[[show[room.id].title]]' +
      '</div>\n' +
      '<div data-ng-if="userRole == \'ROLE_SALESMAN\' && room.online_sale == true">' +
      '<reserve from="\'program\'" showid="show[room.id].id" user-role="userRole" current-language="currentLanguage" selectedroom="room.id"></reserve>'+
      '</div>'+
      '</div>\n' +
      '   <div data-ng-if="show[room.id] == null" class="thumbnail_show [[outerIndex % 2 == 0 ? \'tumbnail_odd_empty\' : \'tumbnail_pair_empty\']]">' +
      '</div>\n' +
      '  </div>\n' +
      '</div>'+
      '</div>' +
      '</div>'
    }

    return directiveDefinitionObject;
  }]);
})();
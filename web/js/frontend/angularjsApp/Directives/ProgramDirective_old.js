/*
 * File for handling
 * */

(function () {
  'use strict';

  /* Declare app level module which depends on views, and components */
  angular.module('BncFrontend.programDirective', ['BncFrontend.programFactory']);

  /* Declaring directive functions for this module */
  angular.module('BncFrontend.programDirective').directive('program', [function () {
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
          $scope.model.domain = null;

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
            $scope.model.domain = response.data.initialsData.bncDomain;
            $scope.initVisualization();
          });
        }

        init();
      },
      template:
      '<div>'+
      '<div class="row" style="margin-top:40px;"> '+
      /*'<div class="col-xs-12" style="margin-bottom: 20px">' +
      '<button style="float: right" type="button" class="btn btn-outline btn-primary">Descargar</button>' +
      '</div> '+*/
      '<div data-ng-repeat="room in model.rooms" class="hidden-xs hidden-sm  col-md-3 col-sm-4 col-xs-6">\n' +
      '    <div class="dummy"></div>\n' +
      '    <div class="thumbnail_mine purple schedule-header">[[room.headquarter]]<br> ([[room.title]])</div>\n' +
      '  </div>'+
      '  </div>'+
      '<div class="row hidden-xs hidden-sm" data-ng-repeat="(key, show) in model.showData" ng-init="outerIndex = $index">\n' +
      '<div class="row" data-ng-repeat="(key, show) in model.showData" data-ng-init="outerIndex = $index">\n' +
      '<div class="col-xs-12">'+
      '<h3 class="app-text-color schedule-date-title">[[key]]</h3>'+
      '</div>' +
      '  <div style="margin-top: -50px;" data-ng-repeat="room in model.rooms" class="col-md-3 col-sm-4 col-xs-6">\n' +
      '    <div class="dummy"></div>\n' +
      '    <a href="[[model.domain]]/[[show[room.id].url_slug]]" data-ng-if="show[room.id] != null" class="thumbnail_show tumbnail_not_empty "><strong>[[show[room.id].show_time]]</strong><br>[[show[room.id].title]]' +
      '<div data-ng-if="userRole != \'ROLE_ADMIN\'">' +
      '<reserve view="\'large\'" from="\'program\'" showid="show[room.id].id" user-role="userRole" current-language="currentLanguage" selectedroom="room.id"></reserve>'+
      '</div>'+
      '</a>\n' +
      '    <div data-ng-if="show[room.id] == null" class="thumbnail_show [[outerIndex % 2 == 0 ? \'tumbnail_odd_empty\' : \'tumbnail_pair_empty\']]">' +
      '</div>\n' +
      '  </div>\n' +
      '</div>' +
      '<div class="row hidden-lg hidden-md">' +
      '<div data-ng-repeat="room in model.rooms">'+
      '<div class="col-xs-12">\n' +
      '    <div class="dummy"></div>\n' +
      '    <div class="thumbnail_mine purple">[[room.headquarter]]<br> ([[room.title]])</div>\n' +
      '  </div>'+
      '<div class="col-xs-12" data-ng-repeat="(key, show) in model.showData" ng-init="outerIndex = $index">\n' +
      '<h3 style="text-align: center" class="app-text-color">[[key]]</h3>'+
      '<div class="dummy_show"></div>\n' +
      '<a href="[[model.domain]]/[[show[room.id].url_slug]]" data-ng-if="show[room.id] != null" class="thumbnail_show tumbnail_not_empty "><strong>[[show[room.id].show_time]]</strong><br>[[show[room.id].title]]' +
      '<div data-ng-if="userRole != \'ROLE_ADMIN\'">' +
      '<reserve view="\'mobile\'" from="\'program\'" showid="show[room.id].id" user-role="userRole" current-language="currentLanguage" selectedroom="room.id"></reserve>'+
      '</div>'+
      '</a>\n' +
      '    <div data-ng-if="show[room.id] == null" class="thumbnail_show tumbnail_odd_empty">' +
      '</div>'+
      '  </div>'+
      '</div> '+
      '</div>'
    }

    return directiveDefinitionObject;
  }]);
})();
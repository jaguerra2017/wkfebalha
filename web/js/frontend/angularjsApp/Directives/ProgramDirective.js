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
        
        $scope.parseRoom = function (title) {
          var mella = title.search("Mella");
          var marti = title.search("Mart");
          if(mella > 0 || marti > 0){
            return '';
          }
          return '('+title+')';
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
          $scope.model.url_slug = null;
          $scope.model.headquarter_url_slug = null;

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
            $scope.model.url_slug = response.data.initialsData.programDataCollection.type_url_slug;
            $scope.model.headquarter_url_slug = response.data.initialsData.programDataCollection.headquarter_type_url_slug;
            $scope.initVisualization();
          });
        }

        init();
      },
      template:
      '<div>'+
      '<div class="row" style="margin-top:40px; display: flex;"> '+
      '<div data-ng-repeat="room in model.rooms" class="hidden-xs hidden-sm col-md-3 col-sm-4 col-xs-6">\n' +
      '    <div class="thumbnail_mine purple schedule-header">' +
      '<div style="color: white" ' +
      // 'href="[[model.domain]]/[[model.headquarter_url_slug]]/[[currentLanguage]]/[[room.url_slug]]"
      '> [[room.headquarter]]<br> [[parseRoom(room.title)]] '+
      '</div></div>\n' +
      '  </div>'+
      '  </div>'+
      '<div style="display: flex; flex-direction: column;" class="row hidden-xs hidden-sm" data-ng-repeat="(key, show) in model.showData" ng-init="outerIndex = $index">\n' +
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
      '<div data-ng-if="(userRole == \'IS_AUTHENTICATED_ANONYMOUSLY\' || userRole == \'ROLE_TESTER\') && room.online_sale == true">' +
      '<reserve view="\'large\'" from="\'program\'" showid="show[room.id].id" user-role="userRole" current-language="currentLanguage" selectedroom="room.id"></reserve>'+
      '</div>'+
      '</div>\n' +
      '   <div data-ng-if="show[room.id] == null" class="thumbnail_show [[outerIndex % 2 == 0 ? \'tumbnail_odd_empty\' : \'tumbnail_pair_empty\']]">' +
      '</div>\n' +
      '  </div>\n' +
        '</div>'+
      '</div>' +
      '<div style="display: flex; flex-direction: column;" class="row hidden-lg hidden-md">' +
      '<div style="margin-bottom: 38px" data-ng-repeat="room in model.rooms">'+
      '<div class="col-xs-12">\n' +
      '    <div class="thumbnail_mine purple schedule-header"><a style="color: white" href="[[model.domain]]/[[model.headquarter_url_slug]]/[[currentLanguage]]/[[room.url_slug]]">[[room.headquarter]]<br> ([[room.title]])</a></div>\n' +
      '  </div>'+
      '<div  class="col-xs-12" data-ng-repeat="(key, show) in model.showData" ng-init="outerIndex = $index">\n' +
      '   <h3 class="app-text-color schedule-date-title">[[key]]</br><p class="title-separator" style="float:left;"></p></h3>'+
      '<div style="margin-top: 30px" class="thumbnail_show tumbnail_not_empty" data-ng-if="show[room.id] != null">'+
      '<a href="[[model.domain]]/[[model.url_slug]]/[[currentLanguage]]/[[show[room.id].url_slug]]"><strong>[[show[room.id].show_time]]</strong><br>[[show[room.id].title]]' +
      '</a>\n' +
      '<div data-ng-if="(userRole == \'IS_AUTHENTICATED_ANONYMOUSLY\' || userRole == \'ROLE_TESTER\') && room.online_sale == true">' +
      '<reserve view="\'mobile\'" from="\'program\'" showid="show[room.id].id" user-role="userRole" current-language="currentLanguage" selectedroom="room.id"></reserve>'+
      '</div>'+
      '</div>'+
      '    <div style="margin-top: 30px" data-ng-if="show[room.id] == null" class="thumbnail_show tumbnail_odd_empty">' +
      '</div>'+
      '  </div>'+
      '</div> '+
      '</div>'
    }

    return directiveDefinitionObject;
  }]);
})();
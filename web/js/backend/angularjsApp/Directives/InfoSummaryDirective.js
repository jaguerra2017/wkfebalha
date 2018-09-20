/*
 * File for handling
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.infoSummaryDirective', ['BncBackend.dashboardFactory']);

    /* Declaring directive functions for this module */
    angular.module('BncBackend.infoSummaryDirective').directive('infoSummary',function(dashboardFact)
    {
        var directiveDefinitionObject ={
            restrict:"E",
            replace : true,
            link:function(scope, element) {

                dashboardFact.loadInitialsData(scope, function(response){

                    var summaryDataCollection = response.data.initialsData.summaryDataCollection;
                    scope.model.summaryDataCollection = summaryDataCollection;
                    var chart = AmCharts.makeChart("dashboardSummaryAnimationChart", {
                            type: "serial",
                            fontSize: 12,
                            fontFamily: "Open Sans",
                            dataProvider: summaryDataCollection,

                            addClassNames: true,
                            startDuration: 1,
                            color: "#6c7b88",
                            marginLeft: 0,

                            categoryField: "element",
                            categoryAxis: {
                                autoGridCount: false,
                                gridCount: 50,
                                gridAlpha: 0.1,
                                gridColor: "#FFFFFF",
                                axisColor: "#555555"
                            },

                            valueAxes: [{
                                id: "a1",
                                title: "cantidad",
                                gridAlpha: 0,
                                axisAlpha: 0
                            }],
                            graphs: [{
                                id: "g1",
                                valueField:  "total",
                                title:  "Total",
                                type:  "column",
                                fillAlphas:  0.7,
                                valueAxis:  "a1",
                                balloonText:  "[[value]] [[element]]",
                                legendValueText:  "[[value]] [[element]]",
                                legendPeriodValueText:  "total: [[value.sum]] elementos",
                                lineColor:  "#08a3cc",
                                alphaField:  "alpha",
                            }],

                            chartCursor: {
                                zoomable: false,
                                cursorAlpha: 0,
                                categoryBalloonColor: "#e26a6a",
                                categoryBalloonAlpha: 0.8,
                                valueBalloonsEnabled: false
                            },
                            legend: {
                                bulletType: "round",
                                equalWidths: false,
                                valueWidth: 120,
                                useGraphSettings: true,
                                color: "#6c7b88"
                            }
                        });

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
            },
            controller: function($scope, $element, dashboardFact) {

                function init(){
                    /*generals variables*/
                    $scope.model = {};
                    $scope.success = false;
                    $scope.error = false;
                    /**/
                    $scope.model.summaryDataCollection = [];
                }
                init();
            },
            template:
                '<div class="portlet light bordered">' +
                    '<div class="portlet-title">' +
                        '<div class="caption font-dark">' +
                            '<i class="icon-bar-chart"></i>' +
                            '<span class="caption-subject bold uppercase">Resumen Informativo</span>' +
                            '<span class="caption-helper"> del sitio...</span>' +
                        '</div>' +
                        '<div class="actions">' +
                            '<a class="btn btn-circle btn-icon-only btn-default fullscreen"></a>' +
                        '</div>' +
                    '</div>' +
                    '<div class="portlet-body" style="min-height:400px;">' +
                        '<div class="row">' +
                            '<div class="col-xs-12">' +
                                '<div id="dashboardSummaryAnimationChart" class="CSSAnimationChart"></div>' +
                            '</div>' +
                        '</div>' +
                        '<div data-ng-if="model.summaryDataCollection.length > 0" class="row">' +
                            '<div data-ng-repeat="summaryElement in model.summaryDataCollection" class="col-md-3 col-sm-6 col-xs-12">' +
                                '<div class="dashboard-element-summary-container">' +
                                    '<div class="dashboard-stat [[summaryElement.color]]">' +
                                        '<div class="visual">' +
                                            '<i class="[[summaryElement.icon_class]]"></i>' +
                                        '</div>' +
                                        '<div class="details">' +
                                            '<div class="number">' +
                                                '[[summaryElement.total]]' +
                                            '</div>' +
                                            '<div class="desc">' +
                                                '[[summaryElement.element]]' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
        }

        return directiveDefinitionObject;
    });
})();
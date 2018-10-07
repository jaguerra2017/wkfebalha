/*
 * File for handling
 * */

(function () {
  'use strict';

  /* Declare app level module which depends on views, and components */
  angular.module('BncBackend.reserveDirective', ['BncBackend.reserveFactory']);

  /* Declaring directive functions for this module */
  angular.module('BncBackend.reserveDirective').directive('reserve', [function () {
    var directiveDefinitionObject = {
      restrict: "E",
      replace: true,
      scope: {
        selectedroom: "=",
        currentLanguage: "=",
        admin: "=",
        testData: "="
      },
      controller: function ($scope, $element, reserveFact) {

        /*
         * Global variables
         *
         * */
        var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");
        var price = 10;

        $scope.changeArea = function (event, area) {
          $scope.model.selectedArea = area;
          $scope.model.selectedAreaZones = area.zones;
        }

        $scope.showSeats = function () {
          // $('#seats-selector-modal').modal('show');
          $scope.drawSeats();
        }

        $scope.changeZone = function (event, zone) {
          $scope.model.selectedZone = zone;
          $scope.model.sc = null;
          $scope.showSeats();
          $scope.model.selectedSeats = null;
          $scope.changeStep('block_2');
        }

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


        $scope.scrollDown = function () {
          $('html, body').animate({scrollY: 200}, 1000);
        }

        $scope.changeStep = function (step) {
          $scope.model.currentStep = step;
          $scope.model.stepIndex++;
          $scope.scrollDown();
        }

        $scope.paySubmit = function () {
          $scope.model.stepIndex = 0;
          $scope.model.currentStep = 'block_4';
          $('#payment_gateway').submit()
        }

        /*hide block selector*/
        $scope.hideBlockSelectorModal = function () {
          $scope.toggleDataLoader();
          $('#seats-selector-modal').modal('hide');
          $scope.goToTop();
        }

        $scope.drawSeats = function () {
          $scope.toggleDataLoader();
          $scope.model.canBooking = true;
          var searchParametersCollection = {
            'area': $scope.model.selectedArea.id,
            'zone': $scope.model.selectedZone.id
          };
          var reserveData = {reserveData: searchParametersCollection};
          reserveFact.loadSeats($scope, reserveData, function (response) {
            $scope.toggleDataLoader();
            $scope.model.SeatsData = response;
            $scope.model.seatsMapId = response.data.seatsData.seatMapId;
            $scope.model.sc = $('#seat-map').seatCharts({
              map: $scope.model.SeatsData.data.seatsData.seatMap,
              naming: {
                rows: $scope.model.SeatsData.data.seatsData.rowsNames,
                columns: $scope.model.SeatsData.data.seatsData.seatNames,
                top: false,
                getLabel: function (character, row, column) {
                  return column;
                },
                getId: function (character, row, column) {
                 return  $scope.model.seatsMapId[row+'_'+column];
                }
              },
              legend: { //Definition legend
                node: $('#legend'),
                items: [
                  ['a', 'available', 'Disponible'],
                  ['a', 'unavailable', 'No disponible'],
                  ['a', 'selected', 'Reservado por usted']
                ]
              },
              click: function () { //Click event
                if (this.status() == 'available') { //optional seat
                  $counter.text($scope.model.sc.find('selected').length + 1);
                  $total.text($scope.recalculateTotal($scope.model.sc) + price);
                  $scope.model.selectedSeats = $scope.model.sc.find('selected');

                  return 'selected';

                } else if (this.status() == 'selected') { //Checked
                  //Update Number
                  $counter.text($scope.model.sc.find('selected').length - 1);
                  //update totalnum
                  $total.text($scope.recalculateTotal($scope.model.sc) - price);

                  //Delete reservation
                  // $('#cart-item-'+this.settings.id).remove();
                  //optional
                  return 'available';
                } else if (this.status() == 'unavailable') { //sold
                  return 'unavailable';
                } else {
                  return this.style();
                }
              }
            });

            if ($scope.model.SeatsData.data.seatsData.reverse) {
              $('.seatCharts-row ').css('flex-direction', 'row-reverse');
            }
          });
          var $cart = $('#selected-seats'), //Sitting Area
            $counter = $('#counter'), //Votes
            $total = $('#total'); //Total money

          // sc.get(['1_2', '4_4','4_5','6_6','6_7','8_5','8_6','8_7','8_8', '10_1', '10_2']).status('unavailable');
        }

        $scope.fillCheckoutForm = function (from) {
          if($scope.model.selectedSeats != null){
            switch (from) {
              case 'user':
                $scope.changeStep('block_3');
                $scope.model.showCheckout = true;
                $scope.model.selectedSeats = $scope.model.sc.find('selected');
                $scope.model.amountUSD = $scope.model.selectedSeats.length * price;
                break;
              case 'admin':
                $scope.model.selectedSeats = $scope.model.sc.find('selected');
                console.log($scope.model.selectedSeats);
                break;
            }
          }
        }

        //sum total money
        $scope.recalculateTotal = function (sc) {
          var total = 0;
          sc.find('selected').each(function () {
            total += price;
          });

          return total;
        }

        $scope.initVisualization = function () {
          /*list view variables*/
          $scope.model.loadingData = false;
        }

        function init() {
          /*generals variables*/
          $scope.model = {};
          $scope.model.permission = {
            'block_1': true,
            'block_2': true,
            'block_3': true,
            'block_4': true
          }
          $scope.model.seatsMap = '';
          $scope.model.amountUSD = 0;
          $scope.model.stepIndex = 1;
          $scope.model.clientData = {};
          $scope.model.seatsMapId = {};
          $scope.model.mapImageUrl = 'ijij.jpg';
          $scope.model.currentStep = 'block_1';
          $scope.model.AreaZone = {};
          $scope.model.SeatsData = {};
          $scope.model.selectedArea = null;
          $scope.model.selectedZone = null;
          $scope.model.sc = null;
          $scope.model.showCheckout = false;
          $scope.model.selectedAreaZones = {};
          $scope.model.selectedSeats = null;
          $scope.model.canBooking = false;
          $scope.success = false;
          $scope.error = false;
          console.log($scope.testData);

          $scope.toggleDataLoader();
          var searchParametersCollection = {
            'selectedRoom': $scope.selectedroom.id,
            'currentLanguage': $scope.currentLanguage
          };
          var reserveData = {reserveData: searchParametersCollection};
          reserveFact.loadInitialsData($scope, reserveData, function (response) {
            $scope.model.AreaZone = response.data.initialsData.reserveDataCollection;
            $scope.initVisualization();
          });
        }

        init();
      },
      template:
      '<div class="row"> '+
      '<div class="col-xs-12">' +
      '<div class="timeline-item" id="block_1" data-ng-if="model.permission.block_1 && model.stepIndex > 0">' +
      '<!-- Loader -->' +
      '<div data-ng-show="model.loadingData">' +
      '<div class="data-loader">' +
      '<div class="sk-data-loader-spinner sk-spinner-three-bounce">' +
      '<div class="sk-bounce1"></div>' +
      '<div class="sk-bounce2"></div>' +
      '<div class="sk-bounce3"></div>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '<div class="timeline-badge" style="z-index: 1;">' +
      '<div class="timeline-custom-badge [[model.currentStep == \'block_1\' ? \'active-badge\' : \'\']]">' +
      '                    <span>1</span>\n' +
      '                </div>' +
      '</div>'+
      '<div class="pairBlock col-xs-12">'+
      '<div class="timeline-body-content">'+
      '<div class="col-xs-8">' +
      '<img ng-src="[[model.mapImageUrl]]"> </div>' +
      '<div class="col-xs-4">' +
      'datos' +
      '</div> ' +
      '</div> ' +
      '<div class="row"> '+
      '<div class="col-xs-12 col-md-offset-4 col-xs-offset-3 col-md-8"> '+
      '<div class="actions custom-toolbar-actions">' +
      ' <div class="input-group" style="width: 75px">\n' +
      '                    <div class="input-group-btn">\n' +
      '                        <a class="btn toolbar-btn-dropdown-text btn-sm btn-default dropdown-toggle"\n' +
      '                           style="text-align: left; font-size: 11px;"\n' +
      '                           data-toggle="dropdown" data-hover="dropdown" data-close-others="true">\n' +
      '                            [[model.selectedArea == null ? \'Seleccione un área\' : model.selectedArea.title]] <i class="fa fa-angle-down"></i>\n' +
      '                        </a>\n' +
      '                        <div class="dropdown-menu hold-on-click dropdown-checkboxes "\n' +
      '                             style="min-width: 75px;top: 25px;margin-left: 0px;">\n' +
      '                            <label data-ng-repeat="area in model.AreaZone">\n' +
      '                                <a class="btn" style="width: 100%;text-align: left;"\n' +
      '                                   data-ng-click="changeArea($event, area)"\n' +
      '                                >\n' +
      '                                    [[area.title]]\n' +
      '                                </a>\n' +
      '                            </label>\n' +
      '                        </div>\n' +
      '                </div>\n' +
      '                    <div class="input-group-btn">\n' +
      '                        <a class="btn toolbar-btn-dropdown-text btn-sm btn-default dropdown-toggle  [[model.selectedArea == null ? \'disabled\' : \'\']]"\n' +
      '                           style="text-align: left; font-size: 11px; margin-left: 10px;"\n' +
      '                           data-toggle="dropdown" data-hover="dropdown" data-close-others="true">\n' +
      '                            [[model.selectedZone == null ? \'Seleccione la zona\' : model.selectedZone.title]] <i class="fa fa-angle-down"></i>\n' +
      '                        </a>\n' +
      '                        <div class="dropdown-menu hold-on-click dropdown-checkboxes "\n' +
      '                             style="min-width: 75px;top: 25px;margin-left: 0px;">\n' +
      '                            <label data-ng-repeat="zone in model.selectedAreaZones">\n' +
      '                                <a class="btn" style="width: 100%;text-align: left;"\n' +
      '                                   data-ng-click="changeZone($event, zone)"\n' +
      '                                >\n' +
      '                                    [[zone.title]]\n' +
      '                                </a>\n' +
      '                            </label>\n' +
      '                        </div>\n' +
      '                </div>\n' +
      '                </div>\n' +
      '                </div>\n' +
      '                </div>\n' +
      '                </div>\n' +
      '                </div>\n' +
      '</div>' +
      '<!-- Seats blocks -->' +
      '<div id="block_2" class="timeline-item col-xs-12 wow fadeInUp" data-ng-if="model.permission.block_2 && model.stepIndex > 1">' +
      '<div class="timeline-badge" style="z-index: 1;">' +
      '<div class="timeline-custom-badge [[model.currentStep == \'block_2\' ? \'active-badge\' : \'\']]">' +
      '                    <span>2</span>\n' +
      '                </div>' +
      '</div>'+
      '<div class="pairBlock col-xs-12">'+
      '<div class="timeline-body-content">'+
      '   <div id="seat-map" class="col-lg-9 col-xs-12">\n' +
      '    <div class="front">[[model.selectedArea.title]] / [[model.selectedZone.title]]</div>\n' +
      '   </div>\n' +
      '<div id="legend" class="col-lg-3 col-xs-12"></div>\n' +
      '<div class="row"> '+
       '<div class="col-xs-12 col-md-offset-4 col-xs-offset-3 col-md-8"> '+
      '<button type="button" class="btn checkout-button" data-ng-click="fillCheckoutForm(\'user\')">Reservar</button>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '<!-- Form block -->' +
      '<div id="block_3" class="timeline-item col-xs-12 wow fadeInUp" data-ng-if="model.permission.block_3 && model.stepIndex > 2">' +
      '<div class="timeline-badge" style="z-index: 1;">' +
      '<div class="timeline-custom-badge [[model.currentStep == \'block_3\' ? \'active-badge\' : \'\']]">' +
      '                    <span>3</span>\n' +
      '                </div>' +
      '</div>'+
      '<div class="pairBlock col-xs-12">'+
      '<div class="timeline-body-content" data-ng-if="model.currentStep == \'block_3\'">'+
      '<form id="payment_gateway" target="_blank" method="post" action="http://www.terminalpago.soycubano.com/procesar_pago.php">\n' +
      '    <input name="cod_entidad" type="hidden" value="FestivalBallet">\n' +
      '    <input name="id_transaccion" type="hidden" value="">\n' +
      '    <input name="concepto" type="hidden" value="">\n' +
      '    <input name="AmountUSD" data-ng-value="model.amountUSD" type="hidden">\n' +
      '    <input name="moneda" type="hidden" value="840 - usd">\n' +
      '    <input name="nombres" type="hidden" data-ng-value="model.clientData.name">\n' +
      '    <input name="apellidos" type="hidden" data-ng-value="model.clientData.lastName">\n' +
      '    <input name="email" type="hidden" data-ng-value="model.clientData.email">\n' +
      '    <input name="pais" type="hidden" value="">\n' +
      '</form>'+
      '<div class="row">\n' +
      '    <div class="col-xs-12 col-md-4">\n' +
      '                <div class="form-group margin-top-20">\n' +
      '                    <label class="control-label">\n' +
      '                        Nombres\n' +
      '                    </label>\n' +
      '                    <input class="form-control" type="text" placeholder=""\n' +
      '                    data-ng-model="model.clientData.name">\n' +
      '             </div>\n' +
      '     </div>\n' +
      '    <div class="col-xs-12 col-md-4">\n' +
      '                <div class="form-group margin-top-20">\n' +
      '                    <label class="control-label">\n' +
      '                        Apellidos\n' +
      '                    </label>\n' +
      '                    <input class="form-control" type="text" placeholder=""\n' +
      '                    data-ng-model="model.clientData.lastName">\n' +
      '             </div>\n' +
      '     </div>\n' +
      '    <div class="col-xs-12 col-md-4">\n' +
      '                <div class="form-group margin-top-20">\n' +
      '                    <label class="control-label">\n' +
      '                        Correo electr&oacute;nico\n' +
      '                    </label>\n' +
      '                    <input class="form-control" type="email" placeholder=""\n' +
      '                    data-ng-model="model.clientData.email">\n' +
      '             </div>\n' +
      '     </div>\n' +
      '<div class="col-xs-12 col-xs-offset-3 col-md-offset-6">' +
      '<button type="button" class="btn checkout-button" data-ng-click="paySubmit()">Comprar</button>\n' +
      '</div> '+
      '</div>'+
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '<!-- Message block -->' +
      '<div id="block_4" class="timeline-item col-xs-12 wow fadeInUp" data-ng-if="model.permission.block_4 && model.stepIndex == 0">' +
      '<div class="timeline-badge" style="z-index: 1;">' +
      '<div class="timeline-custom-badge [[model.currentStep == \'block_4\' ? \'active-badge\' : \'\']]">' +
      '                    <span>4</span>\n' +
      '                </div>' +
      '</div>'+
      '<div class="pairBlock col-xs-12">'+
      '<div class="timeline-body-content">'+
      '<div class="row">\n' +

      '</div>'+
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>'
    }

    return directiveDefinitionObject;
  }]);
})();
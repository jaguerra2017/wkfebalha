/*
 * File for handling
 * */

(function () {
  'use strict';

  /* Declare app level module which depends on views, and components */
  angular.module('BncFrontend.reserveDirective', ['BncFrontend.reserveFactory']);

  /* Declaring directive functions for this module */
  angular.module('BncFrontend.reserveDirective').directive('reserve', [function () {
    var directiveDefinitionObject = {
      restrict: "EA",
      replace: true,
      template:
      '<div class="row"> '+
      '<div class="col-xs-12" data-ng-if="model.availability != null && userRole ==\'ROLE_ADMIN\'">' +
      '<div style="text-align: center">\n' +
      '<p>\n' +
      'Esta función tiene <strong>[[model.availability]]</strong> asientos disponibles para la venta online.' +
      '</p>\n' +
      '</div>'+
      '</div>'+
      '<div class="col-lg-2 col-md-3 col-xs-6" data-ng-if="from == \'detail\'">' +
      '<div class="input-group-btn">' +
      '<a data-ng-if="userRole ==\'ROLE_ADMIN\'" class="btn toolbar-btn-dropdown-text btn-sm btn-default" ' +
      'style="text-align: left; font-size: 14px;" ' +
      'data-ng-click="showSeatModal()">' +
      '<i class="fa fa-bookmark"></i> ' +
      '   Gestionar' +
      '</a>' +
      '<button data-ng-click="showSeatModal()" data-ng-if="userRole !=\'ROLE_ADMIN\'" type="button" class="btn btn-outline btn-primary ">Reservar</button>' +
      '</div>' +
      '</div>' +
      '<a data-ng-if="userRole != \'ROLE_ADMIN\' && from == \'program\'" title="Reservar" data-ng-click="showSeatModal()" class="btn btn-circle-sm btn-primary"><span><i class="icon-tag"></i></span> </a>' +
      '<div class="col-xs-12">' +
      '<div id="seats-modal-[[showid]]" class="modal fade" tabindex="-1" data-width="1200" data-backdrop="static" data-keyboard="false">'+
      '<div class="modal-header">'+
      '<button type="button" class="close" title="Cancelar" data-ng-click="cancel()"></button>'+
      '<h4 class="modal-title"></h4>'+
      '</div>'+
      '<div class="modal-body min-height-500">'+
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
      '<div class="col-xs-12 col-md-8">' +
      '<img class="img-responsive" ng-src="[[model.mapImageUrl]]"> </div>' +
      '<div class="col-xs-12 col-md-4">' +
      '<strong>Obra: </strong> [[model.showData.title]] <br>' +
      '<strong>Fecha: </strong> [[model.showData.date]] <br>' +
      '<strong>Hora: </strong> [[model.showData.time]] <br>' +
      '<strong>Precio: </strong> $[[model.showData.price]] <br>' +
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
      '</div> ' +
      '</div> ' +
      '<div class="row"> '+
      '<div class="col-xs-12 col-md-offset-4 col-xs-offset-0 col-md-8"> '+
      '                </div>\n' +
      '                </div>\n' +
      '                </div>\n' +
      '</div>' +
      '<!-- Seats blocks -->' +
      '<div id="block_2" class="timeline-item col-xs-12" data-ng-if="model.permission.block_2 && model.stepIndex > 1">' +
      '<div class="timeline-badge" style="z-index: 1;">' +
      '<div class="timeline-custom-badge [[model.currentStep == \'block_2\' ? \'active-badge\' : \'\']]">' +
      '                    <span>2</span>\n' +
      '                </div>' +
      '</div>'+
      '<div class="pairBlock col-xs-12">'+
      '<div class="timeline-body-content">'+
      '   <div id="seat-map" class="col-lg-10 col-xs-12">\n' +
      '    <div class="front">[[model.selectedArea.title]] / [[model.selectedZone.title]]</div>\n' +
      '   </div>\n' +
      '<div id="legend" class="col-lg-2 col-xs-12">' +
      '<button type="button" style="margin-left: 5px;" data-ng-if="userRole == \'ROLE_ADMIN\'" class="btn checkout-button" data-ng-click="selectAll()">Habilitar todos</button>\n' +
      '</div>\n' +
      '<div class="row"> '+
      '<div class="col-xs-12 col-md-offset-3 col-xs-offset-2 col-md-8" style="margin-bottom: 10px"> '+
      '<button type="button" data-ng-if="userRole != \'ROLE_ADMIN\'" class="btn blue btn-blue btn-footer width-auto-important" data-ng-click="checkoutBooking(\'booking\')">Reservar</button>\n' +
      '<button type="button" data-ng-if="userRole == \'ROLE_ADMIN\'" class="btn blue btn-blue btn-footer width-auto-important" data-ng-click="checkoutBooking(\'admin\')">Aplicar</button>\n' +
      '<button type="button" class="btn default btn-footer width-auto-important" data-ng-click="cancel()">Cancelar</button>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '<!-- Form block -->' +
      '<div id="block_3" class="timeline-item col-xs-12" data-ng-if="model.permission.block_3 && model.stepIndex > 2">' +
      '<div class="timeline-badge" style="z-index: 1;">' +
      '<div class="timeline-custom-badge [[model.currentStep == \'block_3\' ? \'active-badge\' : \'\']]">' +
      '                    <span>3</span>\n' +
      '                </div>' +
      '</div>'+
      '<div class="pairBlock col-xs-12">'+
      '<div class="timeline-body-content" data-ng-if="model.currentStep == \'block_3\'">'+
      '<div data-ng-if="model.currentStep == \'block_3\'" class="note note-info">\n' +
      '<p>\n' +
      '<strong>Cantidad de asientos:</strong> [[model.selectedSeats.length]] &nbsp;&nbsp;&nbsp;' +
      '<strong>Monto total:</strong> $ [[model.amountUSD]]<br>' +
      '</p>\n' +
      '</div>'+
      // '<div data-ng-if="model.checkError" class="note note-warning">\n' +
      // '<p>\n' +
      // 'Debe llenar todos los campos' +
      // '</p>\n' +
      // '</div>'+
      '<form id="payment_gateway" target="_blank" method="post" action="http://www.terminalpago.soycubano.com/procesar_pago.php">\n' +
      '    <input name="cod_entidad" type="hidden" value="FestivalBallet">\n' +
      '    <input name="id_transaccion" type="hidden" data-ng-value="model.transactionNumber">\n' +
      '    <input name="concepto" type="hidden" value="SeatBooking">\n' +
      '    <input name="AmountUSD" data-ng-value="model.amountUSD" type="hidden">\n' +
      '    <input name="moneda" type="hidden" value="840 - usd">\n' +
      '    <input name="nombres" type="hidden" data-ng-value="model.clientData.name">\n' +
      '    <input name="apellidos" type="hidden" data-ng-value="model.clientData.lastName">\n' +
      '    <input name="email" type="hidden" data-ng-value="model.clientData.email">\n' +
      '    <input name="pais" type="hidden" data-ng-value="model.clientData.country.code">\n' +
      '</form>'+
      '<div class="row">\n' +
      '    <div class="col-xs-12 col-md-4">\n' +
      '                <div class="form-group margin-top-20 [[model.nameHasError ? \'has-error\' : \'\']]">\n' +
      '                    <label class="control-label">\n' +
      '                        Nombres\n' +
      '                    </label>\n' +
      '                    <input class="form-control" type="text" placeholder=""\n' +
      '                    data-ng-model="model.clientData.name">\n' +
      ' <span class="help-block">\n' +
      '      <p data-ng-if="model.nameHasError">Valor incorrecto o en blanco.</p>\n' +
      ' </span>' +
      '             </div>\n' +
      '     </div>\n' +
      '    <div class="col-xs-12 col-md-4">\n' +
      '                <div class="form-group margin-top-20 [[model.lastNameHasError ? \'has-error\' : \'\']]">\n' +
      '                    <label class="control-label">\n' +
      '                        Apellidos\n' +
      '                    </label>\n' +
      '                    <input class="form-control" type="text" placeholder=""\n' +
      '                    data-ng-model="model.clientData.lastName">\n' +
      ' <span class="help-block">\n' +
      '      <p data-ng-if="model.lastNameHasError">Valor incorrecto o en blanco.</p>\n' +
      ' </span>' +
      '             </div>\n' +
      '     </div>\n' +
      '    <div class="col-xs-12 col-md-4">\n' +
      '                <div class="form-group margin-top-20 [[model.emailAddressHasError ? \'has-error\' : \'\']]">\n' +
      '                    <label class="control-label">\n' +
      '                        Correo electr&oacute;nico\n' +
      '                    </label>\n' +
      '                    <input class="form-control" type="email" placeholder="example@domain.com"\n' +
      '                    data-ng-model="model.clientData.email_addres">\n' +
      ' <span class="help-block">\n' +
      '      <p data-ng-if="model.emailAddressHasError">Valor incorrecto o en blanco.</p>\n' +
      ' </span>' +
      '             </div>\n' +
      '     </div>\n' +
      '<div class="col-xs-12 col-md-4">\n' +
      '<div class="form-group [[model.countryHasError ? \'has-error\' : \'\']]">\n' +
      '<label class="control-label">Pa&iacute;s</label>\n' +
      '<select data-ng-model="model.clientData.country" class="bs-select form-control" data-live-search="true" data-size="8">\n' +
      ' <option data-ng-repeat="country in model.countries" ng-value="country">[[country.name]]</option>\n' +
      '</select>\n' +
      ' <span class="help-block">\n' +
      '      <p data-ng-if="model.countryHasError">Debe seleccionar un país.</p>\n' +
      ' </span>' +
      '</div>'+
      '</div>'+
      '<div class="col-xs-12 col-xs-offset-3 col-md-offset-4" style="margin-bottom: 5px">' +
      '<button type="button" class="btn blue btn-blue btn-footer width-auto-important" data-ng-click="paySubmit()">Comprar</button>\n' +
      '<button type="button" class="btn default btn-footer width-auto-important" data-ng-click="cancel()">Cancelar</button>\n' +
      '</div> '+
      '</div>'+
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '<!-- Message block -->' +
      '<div id="block_4" class="timeline-item col-xs-12" data-ng-if="model.permission.block_4 && model.stepIndex == 0">' +
      '<div class="timeline-badge" style="z-index: 1;">' +
      '<div class="timeline-custom-badge [[model.currentStep == \'block_4\' ? \'active-badge\' : \'\']]">' +
      '                    <span>4</span>\n' +
      '                </div>' +
      '</div>'+
      '<div class="pairBlock col-xs-12">'+
      '<div class="timeline-body-content">'+
      '<h4 class="block">Voucher</h4>\n' +
      '<p>\n' +
      'Usted ha reservado los asietos: ' +
      '<ul style="list-style: none">' +
      '<li data-ng-repeat="seat in model.voucherData.seats">[[seat]]</li>' +
      '</ul><br>' +
      'Por un monto total de <strong>[[model.voucherData.amount]]</strong> USD' +
      '</p>\n' +
      '</div>'+
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>\n' +
      '</div>',
      scope: {
        selectedroom: "=",
        showid: "=",
        from: "=",
        currentLanguage: "=",
        userRole: "="
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
          $scope.drawSeats();
        }

        $scope.changeZone = function (event, zone) {
          if($scope.model.selectedZone != null){
            $scope.model.sc = null;
            $('.seatCharts-row').remove();
            $('.seatCharts-legendItem').remove();
            $('#seat-map,#seat-map *').unbind().removeData();
          }
          else{
            $scope.changeStep('block_2');
          }

          $scope.model.selectedZone = zone;
          $scope.showSeats();
          $scope.model.selectedSeats = null;

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

        $scope.hideSeatModal = function(){
          $('#seats-modal-'+ $scope.showid).modal('hide');
        }

        $scope.showSeatModal = function(){
          $('#seats-modal-'+ $scope.showid).modal('show');
        }

        $scope.cancel = function () {
          $scope.hideSeatModal();
          $scope.model.currentStep = 'block_1';
          $scope.model.selectedArea = null;
          $scope.model.selectedZone = null;
          $scope.model.sc = null;
          $scope.model.stepIndex = 1;
        }


        $scope.scrollDown = function (element) {
          if(element == 'block_1'){
            $('#seats-modal').animate({scrollTop: $("#"+element).offset().top + 800}, 1000);
          }
        }

        $scope.changeStep = function (step) {
          var previusStep = $scope.model.currentStep;
          $scope.model.currentStep = step;
          $scope.model.stepIndex++;
          // $scope.scrollDown(previusStep);
        }

        $scope.paySubmit = function () {
          if($scope.model.clientData.name == null || $scope.model.clientData.name == ''){
            $scope.model.nameHasError = true;
          }
          if($scope.model.clientData.lastName == null || $scope.model.clientData.lastName == '')
              $scope.model.lastNameHasError = true;
          if( $scope.model.clientData.email_addres == null || $scope.model.clientData.email_addres == '')
              $scope.model.emailAddressHasError = true;
          if(!$scope.model.clientData.country)
            $scope.model.countryHasError = true;

          if($scope.model.clientData.name == null || $scope.model.clientData.name == ''
          || $scope.model.clientData.lastName == null || $scope.model.clientData.lastName == ''
          || $scope.model.clientData.email_addres == null || $scope.model.clientData.email_addres == ''
          || !$scope.model.clientData.country){
            $scope.model.checkError = true;
          }
          else{
            $scope.model.checkError = false;
          }

          if($scope.model.checkError == false){
            $scope.model.stepIndex = 0;
            $scope.model.currentStep = 'block_4';
            var bookingData = {
              role: $scope.userRole,
              amount: $scope.model.amountUSD,
              transactionId: $scope.model.transactionNumber,
              seats: $scope.model.selectedSeats.seatIds,
              showid: $scope.showid,
              name: $scope.model.clientData.name,
              lastName: $scope.model.clientData.lastName,
              email_address: $scope.model.clientData.email_addres,
              countryId: $scope.model.clientData.country.id
            };

            var parameterCollection = {
              bookingData: bookingData
            };
            $scope.toggleDataLoader();
            // $scope.goToTop();
            reserveFact.saveBookingData($scope, parameterCollection, function (response) {
              $scope.model.selectedArea = null;
              $scope.model.selectedZone = null;
              $scope.model.sc = null;
              $scope.model.voucherData = response.data.voucher;
            });
            $('#payment_gateway').submit()
          }
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
            area: $scope.model.selectedArea.id,
            zone: $scope.model.selectedZone.id,
            showid: $scope.showid,
            role: $scope.userRole,
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
                items: $scope.model.SeatsData.data.seatsData.seatsItemsLegend
              },
              click: function () { //Click event
                if (this.status() == 'available' || this.status() == 'selled') { //optional seat
                  // $counter.text($scope.model.sc.find('selected').length + 1);
                  // $total.text($scope.recalculateTotal($scope.model.sc) + price);
                  if($scope.userRole == 'ROLE_ADMIN'){
                    return 'available_admin';
                  }
                  else{
                    return 'selected';
                  }

                } else if (this.status() == 'selected' || this.status() == 'available_admin') { //Checked
                  //Update Number
                  // $counter.text($scope.model.sc.find('selected').length - 1);
                  //update totalnum
                  // $total.text($scope.recalculateTotal($scope.model.sc) - price);

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

            $scope.model.sc.get($scope.model.SeatsData.data.seatsData.selledId).status('selled');
            $scope.model.sc.get($scope.model.SeatsData.data.seatsData.availablesadminId).status('available_admin');
            $scope.model.sc.get($scope.model.SeatsData.data.seatsData.unavailablesId).status('unavailable');

            if ($scope.model.SeatsData.data.seatsData.reverse) {
              $('.seatCharts-row ').css('flex-direction', 'row-reverse');
            }
          });
          // var $cart = $('#selected-seats'), //Sitting Area
          //   $counter = $('#counter'), //Votes
          //   $total = $('#total'); //Total money
        }

        $scope.selectAll = function () {
          $scope.model.sc.find('available').status('available_admin');
        }

        $scope.checkoutBooking = function (from) {
          $scope.model.selectedSeats = $scope.userRole !='ROLE_ADMIN' ? $scope.model.sc.find('selected') :$scope.model.sc.find('available_admin');
          $scope.model.unselectedSeats = $scope.model.sc.find('available');
          $scope.model.transactionNumber = Math.floor(Math.random() * 1000000000);
            switch (from) {
              case 'booking':
                if($scope.model.selectedSeats != null && $scope.model.selectedSeats.length > 0){
                  $scope.model.amountUSD = $scope.model.selectedSeats.length * $scope.model.price;
                  if($scope.userRole =='ROLE_SALESMAN'){
                    var bookingData = {
                      role: $scope.userRole,
                      amount: $scope.model.amountUSD,
                      transactionId: $scope.model.transactionNumber,
                      seats: $scope.model.selectedSeats.seatIds,
                      showid: $scope.showid
                    };

                    var parameterCollection = {
                      bookingData: bookingData
                    };
                    $scope.toggleDataLoader();
                    $scope.goToTop();
                    reserveFact.saveBookingData($scope, parameterCollection, function (response) {
                      $scope.cancel();
                    });
                  }
                  else{
                    $scope.model.showCheckout = true;
                    $scope.changeStep('block_3');
                  }
                }
                break;
              case 'admin':
                if($scope.model.selectedSeats != null || $scope.model.unselectedSeats){
                  $scope.toggleDataLoader();
                  $scope.goToTop();
                  var parameterCollection = {
                    selectedSeats:$scope.model.sc.find('available_admin').seatIds,
                    unselectedSeats:$scope.model.sc.find('available').seatIds,
                    showid:$scope.showid
                  }
                  reserveFact.enableSeatsToSale($scope, parameterCollection, function (response) {
                    $scope.model.availability = response.data.availability;
                    $scope.cancel();
                  });
                }

                break;
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

          if($scope.userRole == 'ROLE_ADMIN' || $scope.userRole == 'ROLE_SALESMAN'){
            $scope.model.permission.block_3 = false;
            $scope.model.permission.block_4 = false;
          }
          $scope.model.seatsMap = '';
          $scope.model.amountUSD = 0;
          $scope.model.price = 0;
          $scope.model.stepIndex = 1;
          $scope.model.clientData = {};
          $scope.model.seatsMapId = {};
          $scope.model.mapImageUrl = null;
          $scope.model.currentStep = 'block_1';
          $scope.model.AreaZone = {};
          $scope.model.SeatsData = {};
          $scope.model.showData = {};
          $scope.model.countries = {};
          $scope.model.selectedArea = null;
          $scope.model.selectedZone = null;
          $scope.model.sc = null;
          $scope.model.showCheckout = false;
          $scope.model.availability = null;
          $scope.model.selectedAreaZones = {};
          $scope.model.voucherData = {};
          $scope.model.selectedSeats = null;
          $scope.model.canBooking = false;
          $scope.model.checkError = false;
          $scope.success = false;
          $scope.error = false;
          $scope.model.nameHasError = false;
          $scope.model.lastNameHasError = false;
          $scope.model.emailAddressHasError = false;
          $scope.model.countryHasError = false;
          $scope.model.transactionNumber = 0;

          $scope.toggleDataLoader();
          var searchParametersCollection = {
            'selectedRoom': $scope.selectedroom,
            'showid': $scope.showid,
            'currentLanguage': $scope.currentLanguage
          };
          var reserveData = {reserveData: searchParametersCollection};
          reserveFact.loadInitialsData($scope, reserveData, function (response) {
            $scope.model.AreaZone = response.data.initialsData.reserveDataCollection.areaZones;
            $scope.model.showData = response.data.initialsData.reserveDataCollection.showData;
            $scope.model.availability = response.data.initialsData.reserveDataCollection.availability;
            $scope.model.mapImageUrl = response.data.initialsData.reserveDataCollection.mapImageUrl;
            $scope.model.countries = response.data.initialsData.reserveDataCollection.countries;
            $scope.model.price = response.data.initialsData.reserveDataCollection.showData.price;
            $scope.initVisualization();
          });
        }

        init();
      }
    }

    return directiveDefinitionObject;
  }]);
})();
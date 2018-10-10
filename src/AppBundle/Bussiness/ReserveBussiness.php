<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\Booking;
use AppBundle\Entity\GenericPost;
use AppBundle\Entity\ShowSeat;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;


class ReserveBussiness
{
  private $em;

  public function __construct(EntityManager $em, ContainerInterface $container)
  {
    $this->em = $em;
    $this->container = $container;
  }

  public function loadInitialsData($parametersCollection)
  {
    try {
      $initialsData = array();
      $initialsData['reserveDataCollection'] = $this->getRoomsZoneList($parametersCollection);

      return $initialsData;
    } catch (\Exception $e) {
      throw new \Exception($e);
    }
  }

  public function getRoomsZoneList($parametersCollection)
  {
    try {
      $result = array();
      $roomsAndZones = array();
//      $headquarter = $this->em->getRepository('AppBundle:GenericPost')->find();
      $room = $this->em->getRepository('AppBundle:Room')->find($parametersCollection['selectedRoom']);
      $roomAreas = $this->em->getRepository('AppBundle:RoomArea')->findBy(
        array('room' => $room)
      );
      if ($roomAreas) {
        foreach ($roomAreas as $key => $roomArea) {
          $roomsAndZones[$key] = array(
            'id' => $roomArea->getId()->getId(),
            'title' => $roomArea->getId()->getTitle($parametersCollection['currentLanguage']),
            'zones' => array()
          );
          $zones = $this->em->getRepository('AppBundle:Zone')->findBy(array('roomArea' => $roomArea));
          if ($zones) {
            foreach ($zones as $zone) {
              $roomsAndZones[$key]['zones'][] = array(
                'id' => $zone->getId()->getId(),
                'title' => $zone->getId()->getTitle($parametersCollection['currentLanguage'])
              );
            }
          }
        }
      }
      $result['areaZones'] = $roomsAndZones;
      $result['countries'] = $this->em->getRepository('AppBundle:Country')->findAllToArray();

      $result['mapImageUrl'] = 'ajghasjkgh.jpg';
      if($room->getMapImage()){
        $result['mapImageUrl'] = $room->getMapImage()->getUrl();
      }
//      $objMediaImage = $this->em->getRepository('AppBundle:MediaImage')->find($show['featured_image_id']);
      $show = $this->em->getRepository('AppBundle:Show')->find($parametersCollection['showid']);
      $result['showData'] = array(
        'title'=>$show->getId()->getTitle($parametersCollection['currentLanguage']),
        'time'=>$show->getShowDate()->format('H:i'),
        'date'=>$show->getShowDate()->format('d/m/Y'),
        'price'=>$show->getSeatPrice()
      );
      $result['availability'] = $this->em->getRepository('AppBundle:ShowSeat')->getAvailabilityInfo($parametersCollection);

      return $result;
    } catch (\Exception $e) {
      throw new \Exception($e);
    }
  }

  public function loadSeats($parametersCollection)
  {
    $seatsMap = array();
    $rowsNames = array();
    $seatNames = array();
    $seatsMapId = array();
    $unavailablesId = array();
    $availablesadminId = array();
    $selledId = array();

    if($parametersCollection['role'] == 'IS_AUTHENTICATED_ANONYMOUSLY' ||
      $parametersCollection['role'] == 'ROLE_SALESMAN'){
      $seatsItemsLegend = array(
        array('a', 'available', 'Disponible'),
        array('a', 'unavailable', 'No disponible'),
        array('a', 'selected', 'Reservado por usted')
      );
    }
    else{
      $seatsItemsLegend = array(
        array('n', 'available', 'Disponible'),
        array('s', 'selled', 'Vendido'),
        array('d', 'available_admin', 'Vendible')
      );
    }

    $show = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['showid']);
    $zone = $this->em->getRepository('AppBundle:Zone')->find($parametersCollection['zone']);
    if ($zone) {
      $rowOrientation = $zone->getOrientation();
      $orderRows = 'DESC';
      if ($rowOrientation->getTreeSlug() == 'row-orientation-up-to-down') {
        $orderRows = 'ASC';
      }
      $zoneRows = $this->em->getRepository('AppBundle:ZoneRow')->findBy(array('zone' => $zone), array('identifier' => $orderRows, 'identifierNumber' => $orderRows));
      if ($zoneRows) {
        foreach ($zoneRows as $rowKey => $zoneRow) {
          $seatOrientation = $zoneRow->getOrientation();
          $orderSeat = 'ASC';
          if ($seatOrientation->getTreeSlug() == 'row-orientation-right-to-left') {
            $orderSeat = 'DESC';
          }
          $rowName = $zoneRow->getIdentifier() ? $zoneRow->getIdentifier() : $zoneRow->getIdentifierNumber();
          $rowsNames[] = $rowName;

          /*Search seats by role and show*/
          $searchParameters = array('zoneRow' => $zoneRow);

          $avaiableSeatsInShow = $this->em->getRepository('AppBundle:ShowSeat')->getAvaiableSeatsInShow($parametersCollection);
          $seatsObj = $this->em->getRepository('AppBundle:Seat')->findBy($searchParameters, array('name' => $orderSeat));
          foreach ($seatsObj as $key => $seat) {
            $seatsMapId[$rowName.'_'.$seat->getName()] = $seat->getId()->getId();
            $seatsMap[$rowKey] = isset($seatsMap[$rowKey]) ? $seatsMap[$rowKey] : '';

            if($parametersCollection['role'] == 'IS_AUTHENTICATED_ANONYMOUSLY' ||
              $parametersCollection['role'] == 'ROLE_SALESMAN'){
              if(!in_array($seat->getId()->getId(),$avaiableSeatsInShow)){
                $unavailablesId[] = $seat->getId()->getId();
              }
              $seatsMap[$rowKey] .= 'a';
            }
            else{
              $showSeat = $this->em->getRepository('AppBundle:ShowSeat')->findOneBy(array('show'=>$show,'seat'=>$seat));
              if(in_array($seat->getId()->getId(),$avaiableSeatsInShow)){
                $seatsMap[$rowKey] .= 'd';
                $availablesadminId[] =$seat->getId()->getId();
              }
              elseif ($showSeat && ($showSeat->getStatus()->getTreeSlug() == 'in-process' || $showSeat->getStatus()->getTreeSlug() == 'selled')){
                $seatsMap[$rowKey] .= 's';
                $selledId[] =$seat->getId()->getId();
              }
              else{
                $seatsMap[$rowKey] .= 'n';
              }
            }

            if ($zoneRow->getSeatNomenclature()->getTreeSlug() == 'letters')
              $seatNames[$key] = $seat->getName();
          }
          if ($zoneRow->getSeatNomenclature()->getTreeSlug() == 'numbers') {
            $starter = 2;
            $enderer = $zoneRow->getSeatCount() * 2;
            if($zoneRow->getSeatCounting()->getTreeSlug() == 'odd'){
              $starter = 1;
              $enderer = ($zoneRow->getSeatCount() * 2)-1;
            }
            if($zoneRow->getSeatCounting()->getTreeSlug() != 'normal'){
              for ($i = $starter; $i <= $enderer; $i+=2) {
                $seatNames[$i] = $i;
              }
            }
            else{
              for ($i = 1; $i <= $zoneRow->getSeatCount(); $i++) {
                $seatNames[$i] = $i;
              }
            }
          }
        }
      }
      $resultSeatsNames = array();
      foreach ($seatNames as $seatName) {
        $resultSeatsNames[] = $seatName;
      }
    }



    return array(
      'reverse' => $seatOrientation->getTreeSlug() == 'row-orientation-right-to-left' ? true : false,
      'seatMap' => $seatsMap,
      'seatMapId' => $seatsMapId,
      'availablesadminId' => $availablesadminId,
      'unavailablesId' => $unavailablesId,
      'selledId' => $selledId,
      'seatsItemsLegend' => $seatsItemsLegend,
      'rowsNames' => $rowsNames,
      'seatNames' => $resultSeatsNames
    );
  }


  public function returnResponse($parametersCollection)
  {
    return $parametersCollection;
  }

  public function enableDisableSeats($parametersCollection) {
    $show = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['showid']);
    $result = array();
    if($show){
      if(isset($parametersCollection['selectedSeats']) && count($parametersCollection['selectedSeats'])){
        $parametersCollection['status_slug'] = 'avaiable';
        $status = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'avaiable'));
        foreach ($parametersCollection['selectedSeats'] as $selectedSeat) {
          $seatObj = $this->em->getRepository('AppBundle:GenericPost')->find($selectedSeat);
          $seatShowObj = $this->em->getRepository('AppBundle:ShowSeat')->findOneBy(array('seat'=> $selectedSeat));
          if(!$seatShowObj)
            $seatShowObj = new ShowSeat();

          $seatShowObj->setAvailable(true);
          $seatShowObj->setSeat($seatObj);
          $seatShowObj->setShow($show);
          $seatShowObj->setStatus($status);
          $this->em->persist($seatShowObj);
        }
      }
      if(isset($parametersCollection['unselectedSeats']) && count($parametersCollection['unselectedSeats'])){
        $parametersCollection['value'] = 0;
        $resultQuery = $this->em->getRepository('AppBundle:ShowSeat')->deleteDisableSeats($parametersCollection);
      }
      $this->em->flush();
    }
    $result['message'] = 'Se han habilitado '.count($parametersCollection['selectedSeats']). ' asientos.';
    $result['availability'] = $this->em->getRepository('AppBundle:ShowSeat')->getAvailabilityInfo($parametersCollection);
    return $result;
  }

  public function saveBookingData($parametersCollection){
    $bookingType = $this->em->getRepository('AppBundle:GenericPostType')->findOneBy(array('tree_slug'=>'booking'));

    $show = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['showid']);
    $bookingSeatsData = array();

    $bookingGP = new GenericPost();
    $bookingGP->setGenericPostType($bookingType);
    $this->em->persist($bookingGP);
    $this->em->flush();

    $booking = new Booking();
    $booking->setId($bookingGP);
    $booking->setShow($show);
    $booking->setTransaction($parametersCollection['transactionId']);
    $booking->setTotalAmount($parametersCollection['amount']);

    switch ($parametersCollection['role']) {
      case 'ROLE_SALESMAN':
        $status = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'finished'));
        $country = $this->em->getRepository('AppBundle:Country')->findOneBy(array('country_code'=>'CU'));
        $booking->setName('Gestor de ventas');
        $booking->setLastname('');
        $booking->setEmail('gestor@bnc.com');
        $booking->setStatus($status);
        $booking->setCountry($country);
        break;
      default:
        $status = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'booking-in-process'));
        $country = $this->em->getRepository('AppBundle:Country')->find($parametersCollection['countryId']);
        $booking->setName($parametersCollection['name']);
        $booking->setLastname($parametersCollection['lastName']);
        $booking->setEmail($parametersCollection['email_address']);
        $booking->setStatus($status);
        $booking->setCountry($country);
        break;
    }

    $seatStatus = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'in-process'));
    foreach ($parametersCollection['seats'] as $seat) {
      $seatObj = $this->em->getRepository('AppBundle:GenericPost')->find($seat);
      $seatShow = $this->em->getRepository('AppBundle:ShowSeat')->findOneBy(array('seat'=>$seatObj, 'show'=>$show));
      $seatShow->setBooking($bookingGP);
      $seatShow->setStatus($seatStatus);
      $this->em->persist($seatShow);
      $seatObjChild = $this->em->getRepository('AppBundle:Seat')->find($seat);
      $zoneRowGP = $seatObjChild->getZoneRow();
      $zoneRow = $this->em->getRepository('AppBundle:ZoneRow')->find($zoneRowGP->getId());
      $rowName = $zoneRow->getIdentifier() ? $zoneRow->getIdentifier() : $zoneRow->getIdentifierNumber();
      $bookingSeatsData[] = $seatObjChild->getName(). ' Fila '.$rowName;
    }

    $this->em->persist($booking);
    $this->em->flush();

    $voucherData = array(
      'seats'=>$bookingSeatsData,
      'amount'=>$parametersCollection['amount'],
      'transaction'=> $booking->getTransaction()
    );


    $sharedFileFinder  = new SharedFileFinderBussiness();
    $mailConfig = $sharedFileFinder->getSettingsFile(array('decode_from_json'=>true,'section'=>'mail'));

    $mailParams = array(
      'subject' => 'SoyCubano Response',
      'from'=> 'adminbnc@gmail.com',
      'to'=> $booking->getEmail(),
      'message' => $mailConfig['after_booking_message_es']
    );

    $this->container->get('appbundle_mail')->sendMail($mailParams);


    return array('message'=>'Operación realizada con exito', 'voucher'=>$voucherData);

  }

}
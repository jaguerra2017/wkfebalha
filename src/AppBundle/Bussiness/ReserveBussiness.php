<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;


class ReserveBussiness
{
  private $em;

  public function __construct(EntityManager $em)
  {
    $this->em = $em;
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
      $headquarter = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['selectedRoom']);
      $room = $this->em->getRepository('AppBundle:Room')->findOneBy(array('headquarter' => $headquarter));
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
      $result['mapImageUrl'] = 'ajghasjkgh.jpg';
      $result['showData'] = array();

      return $roomsAndZones;
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
          $seatsObj = $this->em->getRepository('AppBundle:Seat')->findBy(array('zoneRow' => $zoneRow), array('name' => $orderSeat));
          foreach ($seatsObj as $key => $seat) {
            $seatsMapId[$rowName.'_'.$seat->getName()] = $seat->getId()->getId();
            $seatsMap[$rowKey] = isset($seatsMap[$rowKey]) ? $seatsMap[$rowKey] : '';
            $seatsMap[$rowKey] .= 'a';
            if ($zoneRow->getSeatNomenclature()->getTreeSlug() == 'letters')
              $seatNames[$key] = $seat->getName();
          }
          if ($zoneRow->getSeatNomenclature()->getTreeSlug() == 'numbers') {
            $starter = 2;
            if($zoneRow->getSeatCounting()->getTreeSlug() == 'odd'){
              $starter = 1;
            }
            if($zoneRow->getSeatCounting()->getTreeSlug() != 'normal'){
              for ($i = $starter; $i <= $zoneRow->getSeatCount(); $i+=2) {
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
    }

    $resultSeatsNames = array();
    foreach ($seatNames as $seatName) {
      $resultSeatsNames[] = $seatName;
    }

    return array(
      'reverse' => $seatOrientation->getTreeSlug() == 'row-orientation-right-to-left' ? true : false,
      'seatMap' => $seatsMap,
      'seatMapId' => $seatsMapId,
      'rowsNames' => $rowsNames,
      'seatNames' => $resultSeatsNames
    );
  }


  public function returnResponse($parametersCollection)
  {
    return $parametersCollection;
  }

}
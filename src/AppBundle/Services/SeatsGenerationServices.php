<?php

namespace AppBundle\Services;

use AppBundle\Entity\GenericPost;
use AppBundle\Entity\Seat;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;




class SeatsGenerationServices
{
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;

    }

    public function generateSeats($parametersCollection){
      $roomAreas = $this->em->getRepository('AppBundle:RoomArea')->findBy(
        array('room'=>$parametersCollection['room'])
      );

      $alphas = range('A', 'Z');
      $letras = array();
      foreach ($alphas as $alpha) {
        $letras[] = $alpha;
        if($alpha == 'N'){
          $letras[] = 'Ñ';
        }
      }

      if($roomAreas){
        foreach ($roomAreas as $roomArea) {
          $zones = $this->em->getRepository('AppBundle:Zone')->findBy(array('roomArea'=>$roomArea));
          if($zones){
            foreach ($zones as $zone) {
              $rowOrientation = $zone->getOrientation();
              $orderRows = 'ASC';
              if($rowOrientation->getTreeSlug() == 'row-orientation-up-to-down'){
                $orderRows = 'DESC';
              }
              $zoneRows = $this->em->getRepository('AppBundle:ZoneRow')->findBy(array('zone'=>$zone),array('identifier'=>$orderRows));
              if($zoneRows){
                foreach ($zoneRows as $zoneRow) {
                  $seatOrientation = $zoneRow->getOrientation();
                  $seatNomenclature = $zoneRow->getSeatNomenclature();
                  $seatCounting = $zoneRow->getSeatCounting();
                  $starter = 2;
                  $enderer = $zoneRow->getSeatCount() * 2;
                  if($seatCounting->getTreeSlug() == 'odd'){
                    $starter = 1;
                    $enderer = ($zoneRow->getSeatCount() * 2)-1;
                  }
                  switch ($seatOrientation->getTreeSlug()){
                    case 'row-orientation-right-to-left':
                      if($seatCounting->getTreeSlug() != 'normal'){

                        for ($i = $enderer; $i > 0; $i-=2) {
                          $this->seatCreateAux($i, $seatNomenclature, $letras, $zoneRow->getId());
                        }
                      }
                      else{
                        for($i = $zoneRow->getSeatCount(); $i > 0; $i--){
                          $this->seatCreateAux($i, $seatNomenclature, $letras, $zoneRow->getId());
                        }
                      }
                      break;
                    default:
                      if($seatCounting->getTreeSlug() != 'normal'){
                        for ($i = $starter; $i <= $zoneRow->getSeatCount(); $i+=2) {
                          $this->seatCreateAux($i, $seatNomenclature, $letras, $zoneRow->getId());
                        }
                      }
                      else{
                        for($i = 1; $i <= $zoneRow->getSeatCount(); $i++){
                          $this->seatCreateAux($i, $seatNomenclature, $letras, $zoneRow->getId());
                        }
                      }
                      break;
                  }
                }
              }
            }
          }
        }
      }
    }

  /**
   * @param $i
   * @param $seatNomenclature
   * @param $alphas
   * @return array
   * @throws \Exception
   */
  private function seatCreateAux($i, $seatNomenclature, $alphas, $zoneRow)
  {
    try {
      $seatGP = new GenericPost();
      $identifier = $i;
      if ($seatNomenclature->getTreeSlug() == 'letters') {
        $identifier = $alphas[$i-1];
      }
      $this->em->persist($seatGP);
      $this->em->flush();

      $seat = new Seat();
      $seat->setId($seatGP);
      $seat->setName($identifier);
      $seat->setZoneRow($zoneRow);
      $this->em->persist($seat);
      $this->em->flush();
      return 1;
    }catch (\Exception $e){
      return $e->getMessage();
    }


  }
}
<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\Booking;
use AppBundle\Entity\GenericPost;
use AppBundle\Entity\ShowSeat;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;


class ProgramBussiness
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
      $initialsData['programDataCollection'] = $this->getProgramList($parametersCollection);

      return $initialsData;
    } catch (\Exception $e) {
      throw new \Exception($e);
    }
  }

  public function getProgramList($parametersCollection)
  {
    try {
      $meses = array(
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
      );
      $result = array();
      $showsData = $this->em->getRepository('AppBundle:Show')->getShowsFullData($parametersCollection);
      $parametersCollection['post_type_tree_slug'] = 'room';
      $roomCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
      $roomResult = array();
      foreach ($roomCollection as $room) {
        $roomObj = $this->em->getRepository('AppBundle:Room')->find($room['id']);
        $room['headquarter'] = $roomObj->getHeadquarter()->getTitle($parametersCollection['currentLanguage']);
        $roomResult[$room['id']] = $room;
      }
      $result['rooms'] = $roomResult;
      $result['shows'] = array();
      if($parametersCollection['orderDates']){
        foreach ($showsData as $index => $show) {
          $date_show = $show['date_show']->format('jS F Y');
          if($parametersCollection['currentLanguage'] == 'es')
          $date_show = $fecha =$show['date_show']->format('d')." de ".$meses[$show['date_show']->format('n') - 1]." del ".$show['date_show']->format('Y');
          $show_time = $show['date_show']->format('h:i a');
          $show['date_show'] = $show['date_show']->format('d/m/Y');
          $show['show_time'] = $show_time;
          if(!isset($result['shows'][$date_show])){
            if($parametersCollection['home'] != 'false' && $index > 0)
              continue;
            $result['shows'][$date_show] = array('dateGeneral'=>$show['date_show']);
          }
            $result['shows'][$date_show][$show['idroom']] = $show;
         }
      }
      else{
        $result = $showsData;
      }
      return $result;
    } catch (\Exception $e) {
      throw new \Exception($e);
    }
  }

  public function returnResponse($parametersCollection)
  {
    return $parametersCollection;
  }
}
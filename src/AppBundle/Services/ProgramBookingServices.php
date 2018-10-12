<?php

namespace AppBundle\Services;

use AppBundle\Bussiness\AwardsBussiness;
use AppBundle\Bussiness\CompositionBussiness;
use AppBundle\Bussiness\EventsBussiness;
use AppBundle\Bussiness\HistoricalMomentsBussiness;
use AppBundle\Bussiness\JewelsBussiness;
use AppBundle\Bussiness\NewsBussiness;
use AppBundle\Bussiness\PagesBussiness;
use AppBundle\Bussiness\PartnersBussiness;
use AppBundle\Bussiness\ProgramBussiness;
use AppBundle\Bussiness\PublicationsBussiness;
use AppBundle\Bussiness\RepertoryBussiness;
use AppBundle\Bussiness\ReserveBussiness;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;




class ProgramBookingServices
{
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em, ContainerInterface $container)
    {
        $this->session = $session;
        $this->em = $em;
        $this->container = $container;
    }

    public function loadProgramInitialsData($parametersCollection) {
      $programBussinessObj = new ProgramBussiness($this->em);
      $initialsData = $programBussinessObj->loadInitialsData($parametersCollection);
      $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();
      return $initialsData;
    }

    public function loadReserveInitialsData($parametersCollection){
      $reserveBussinessObj = new ReserveBussiness($this->em, $this->container);
      $initialsData = $reserveBussinessObj->loadInitialsData($parametersCollection);


      $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();
      return $initialsData;
    }

    public function getSeats($parametersCollection) {
      $reserveBussinessObj = new ReserveBussiness($this->em, $this->container);
      $initialsData = $reserveBussinessObj->loadSeats($parametersCollection);
      $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();
      return $initialsData;
    }

    public function saveBookingData($parametersCollection){
      $reserveBussinessObj = new ReserveBussiness($this->em, $this->container);

      $result = $reserveBussinessObj->saveBookingData($parametersCollection);
      return $result;
    }
}
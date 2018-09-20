<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Bussiness\EventsBussiness;


class EventServices
{
    private $session;
    private $em;
    private $objEventBussiness;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
        $this->objEventBussiness = new EventsBussiness($this->em);

    }

    public function getEvents($parametersCollection){
        $eventsCollection = $this->objEventBussiness->getEventsList($parametersCollection);
        return $eventsCollection;
    }
}
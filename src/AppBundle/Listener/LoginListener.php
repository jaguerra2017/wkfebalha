<?php

namespace AppBundle\Listener;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Firewall\LogoutListener;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

use AppBundle\Bussiness\AccesControlBussiness;


class LoginListener
{
    private $em;
    private $router;

    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        /* Getting user access features*/
        $objAccesControlBussines = new AccesControlBussiness($this->em,$event->getAuthenticationToken());
        /* Storing the user access features on session */
        $userAccessFeaturesCollection = $objAccesControlBussines->getLoggedUserFeatures();
        $event->getRequest()->getSession()->set('userAccessFeatures',$userAccessFeaturesCollection);
        $userFrequentlyAccessFeaturesCollection = $objAccesControlBussines->getLoggedUserFrequentlyFeatures();
        $event->getRequest()->getSession()->set('userFrequentlyAccessFeatures',$userFrequentlyAccessFeaturesCollection);
        $userNotFrequentlyAccessFeaturesCollection = $objAccesControlBussines->getLoggedUserNotFrequentlyFeatures();
        $event->getRequest()->getSession()->set('userNotFrequentlyAccessFeatures',$userNotFrequentlyAccessFeaturesCollection);
    }
}


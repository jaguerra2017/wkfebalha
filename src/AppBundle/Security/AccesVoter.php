<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationExpiredException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Bussiness\SecurityAccesBussiness;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AccesVoter implements VoterInterface
{

    const ACTIONS = array('read','create','edit','delete','change-status');
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em){

        $this->session = $session;
        $this->em = $em;
    }


    public function supportsAttribute($action)
    {
        in_array($action,self::ACTIONS);
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $object, array $actions)
    {

        $action = $actions[0];
        if (false === $this->supportsAttribute($action)) {
            //nothing to do for now,go directly to the final RETURN
        }
        else{
            if(
                $object == 'news' || $object == 'publications' || $object == 'pages' || $object == 'events'
                || $object == 'comments' || $object == 'media' || $object == 'opinions'
                || $object == 'taxonomy' || $object == 'partners' || $object == 'historical-moments'
                || $object == 'jewels' || $object == 'composition' || $object == 'repertory'
                || $object == 'awards' || $object == 'settings' || $object == 'users'
            ){
                $functionalityTreeSlug = 'functionality-'.$object;
                $actionTreeSlug = $functionalityTreeSlug.'-action-'.$action;
                $userAccessFeaturesCollection = $this->session->get('userAccessFeatures');
                if(isset($userAccessFeaturesCollection[0])){
                    foreach($userAccessFeaturesCollection as $userAccessFeature){
                        if(isset($userAccessFeature['actions'][0])){
                            foreach($userAccessFeature['actions'] as $userAction){
                                if($userAction['tree_slug'] == $actionTreeSlug){
                                    return VoterInterface::ACCESS_GRANTED;
                                }
                            }
                        }
                    }
                }

                return VoterInterface::ACCESS_DENIED;
            }
        }


        return VoterInterface::ACCESS_ABSTAIN;
    }

}
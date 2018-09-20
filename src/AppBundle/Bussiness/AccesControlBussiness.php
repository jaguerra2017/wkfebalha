<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;



class AccesControlBussiness
{
    private $em;
    private $authenticationToken;
    private $loggedUserFeaturesCollection;


    public function __construct(EntityManager $em, TokenInterface $authenticationToken)
    {
        $this->em = $em;
        $this->authenticationToken = $authenticationToken;
        $this->loggedUserFeaturesCollection = array();
        $this->loggedUserFrequentlyFeaturesCollection = array();
        $this->loggedUserNotFrequentlyFeaturesCollection = array();


        $parametersCollection = new \stdClass();
        $userRoleSlug = $this->authenticationToken->getRoles()[0]->getRole();
        $userRole = $this->em->getRepository('AppBundle:Role')->findOneBy(array(
            'slug'=>$userRoleSlug
        ));
        if(isset($userRole)){
            $parametersCollection->roleId = $userRole->getId();
            $featuresCollection = $this->em->getRepository('AppBundle:RoleFunctionalityAction')->getFunctionalitiesByRole($parametersCollection);
            if(isset($featuresCollection[0])){
                foreach($featuresCollection as $key=>$feature){

                    $objNomFunctionality = $this->em->getRepository('AppBundle:NomFunctionality')->find($feature['functionality_id']);
                    $featuresCollection[$key]['url_index_action'] = $objNomFunctionality->getUrlIndexAction();
                    $featuresCollection[$key]['keyword_selected_class'] = $objNomFunctionality->getKeywordSelectedClass();
                    $featuresCollection[$key]['icon_class'] = $objNomFunctionality->getIconClass();
                    $featuresCollection[$key]['is_used_frequently'] = $objNomFunctionality->isUsedFrequently();

                    $parametersCollection->functionality_tree_slug = $feature['tree_slug'];
                    $featuresCollection[$key]['actions'] = $this->em->getRepository('AppBundle:RoleFunctionalityAction')->getActionsByFunctionality($parametersCollection);

                    if( $objNomFunctionality->isUsedFrequently()){
                        $this->loggedUserFrequentlyFeaturesCollection[] = $featuresCollection[$key];
                    }
                    else{
                        $this->loggedUserNotFrequentlyFeaturesCollection[] = $featuresCollection[$key];
                    }
                }
            }
        }



        $this->loggedUserFeaturesCollection = $featuresCollection;
    }

    public function getLoggedUserFeatures(){
        return $this->loggedUserFeaturesCollection;
    }

    public function getLoggedUserFrequentlyFeatures(){
        return $this->loggedUserFrequentlyFeaturesCollection;
    }

    public function getLoggedUserNotFrequentlyFeatures(){
        return $this->loggedUserNotFrequentlyFeaturesCollection;
    }

}
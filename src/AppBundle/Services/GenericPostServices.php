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
use AppBundle\Bussiness\PublicationsBussiness;
use AppBundle\Bussiness\RepertoryBussiness;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;




class GenericPostServices
{
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;

    }

    public function getGenericPosts($parametersCollection){
        $genericPostsCollection = array();
        if(isset($parametersCollection['post_type_tree_slug'])){
            switch($parametersCollection['post_type_tree_slug']){
                case 'post':
                    $objNewsBussiness = new NewsBussiness($this->em);
                    $genericPostsCollection = $objNewsBussiness->getNewsList($parametersCollection);
                    break;

                case 'partner':
                    $objPartnersBussiness = new PartnersBussiness($this->em);
                    $genericPostsCollection = $objPartnersBussiness->getPartnersList($parametersCollection);
                    break;

                case 'event':
                    $objEventsBussiness = new EventsBussiness($this->em);
                    $genericPostsCollection = $objEventsBussiness->getEventsList($parametersCollection);
                    break;

                case 'publication':
                    $objPublicationsBussiness = new PublicationsBussiness($this->em);
                    $genericPostsCollection = $objPublicationsBussiness->getPublicationsList($parametersCollection);
                    break;

                case 'repertory':
                    $objRepertoryBussiness = new RepertoryBussiness($this->em);
                    $genericPostsCollection = $objRepertoryBussiness->getRepertoryList($parametersCollection);
                    break;

                case 'page':
                    $objPagesBussiness = new PagesBussiness($this->em);
                    $genericPostsCollection = $objPagesBussiness->getPagesList($parametersCollection);
                    break;

                case 'historical-moment':
                    $objHMomentsBussiness = new HistoricalMomentsBussiness($this->em);
                    $genericPostsCollection = $objHMomentsBussiness->getHistoricalMomentsList($parametersCollection);
                    break;

                case 'jewel':
                    $objJewelsBussiness = new JewelsBussiness($this->em);
                    $genericPostsCollection = $objJewelsBussiness->getJewelsList($parametersCollection);

                    break;

                case 'composition':
                    $objCompositionBussiness = new CompositionBussiness($this->em);
                    $genericPostsCollection = $objCompositionBussiness->getCompositionList($parametersCollection);

                    break;

                case 'award':
                    $objAwardBussiness = new AwardsBussiness($this->em);
                    $genericPostsCollection = $objAwardBussiness->getAwardsList($parametersCollection);

                    break;
            }

        }


        return $genericPostsCollection;
    }
}
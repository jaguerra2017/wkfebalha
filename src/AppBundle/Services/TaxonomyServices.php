<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Bussiness\TaxonomyBussiness;


class TaxonomyServices
{
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function getTaxonomies($parametersCollection)
    {
        $objTaxBussiness = new TaxonomyBussiness($this->em);
        $taxonomiesCollection = $objTaxBussiness->getTaxonomiesList($parametersCollection);
        return $taxonomiesCollection;
    }

}
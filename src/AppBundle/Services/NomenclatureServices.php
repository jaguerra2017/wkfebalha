<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Bussiness\NomenclatureBussiness;


class NomenclatureServices
{
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function getNomenclatures($parametersCollection)
    {
        $objNomBussiness = new NomenclatureBussiness($this->em);
        $nomenclaturesCollection = $objNomBussiness->getNomenclatures($parametersCollection);
        return $nomenclaturesCollection;
    }

}
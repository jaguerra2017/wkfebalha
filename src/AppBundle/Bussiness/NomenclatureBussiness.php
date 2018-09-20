<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\NomType;
use AppBundle\Entity\Nomenclature;



class NomenclatureBussiness
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getNomenclatures($parametersCollection)
    {
        $nomenclaturesCollection = $this->em->getRepository('AppBundle:Nomenclature')->getNomenclatures($parametersCollection);
        return $nomenclaturesCollection;
    }
}
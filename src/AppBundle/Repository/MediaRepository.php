<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MediaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MediaRepository extends EntityRepository
{
    public function deleteByIdsCollection($idsCollection)
    {
        $em = $this->getEntityManager();
        $queryText = "DELETE FROM AppBundle:Media m
                      WHERE m.id IN (".$idsCollection.")";
        $query = $em->createQuery($queryText);
        $query->execute();
    }
}

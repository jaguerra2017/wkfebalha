<?php

namespace AppBundle\Repository;

/**
 * ShowRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShowRepository extends \Doctrine\ORM\EntityRepository
{
  public function getShowsFullData($parametersCollection){
    $qb = $this->getEntityManager()->createQueryBuilder();

    $selectChain = "sgp.id, sgp.title_{$parametersCollection['currentLanguage']} as title, 
    r.id as idroom, s.showDate as date_show";

    $qb->select($selectChain)
       ->from('AppBundle:Show','s')
       ->innerJoin('s.id','sgp')
       ->innerJoin('s.room','r')
       ->orderBy('s.showDate');

    $qb->getQuery()->setQueryCacheLifetime(3600);
    $qb->getQuery()->setResultCacheLifetime(3600);
    $qb->getQuery()->useQueryCache(true);
    return $qb->getQuery()->getResult();
  }
}

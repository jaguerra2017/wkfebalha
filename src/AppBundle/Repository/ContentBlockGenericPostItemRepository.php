<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ContentBlockGenericPostItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentBlockGenericPostItemRepository extends EntityRepository
{
    public function getContentBlockGenericPostItems($parametersCollection){
        $qb = $this->createQueryBuilder('cbgpi');
        $qb->select(' cbgpi.id,
                      cb.id as content_block_id,
                      gp.id as generic_post_id')
            ->innerJoin('cbgpi.content_block', 'cb')
            ->innerJoin('cbgpi.generic_post', 'gp');

        $whereAdded = false;
        if(isset($parametersCollection['searchByIdsCollection']) && $parametersCollection['searchByIdsCollection'] == true
            && isset($parametersCollection['idsCollection'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("cbgpi.id IN (".$parametersCollection['idsCollection'].") ");
            }
            else{
                $qb->andWhere("cbgpi.id IN (".$parametersCollection['idsCollection'].") ");
            }
        }
        if(isset($parametersCollection['searchByContentBlockId']) && $parametersCollection['searchByContentBlockId'] == true){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("cb.id = ".$parametersCollection['contentBlockId']);
            }
            else{
                $qb->andWhere("cb.id = ".$parametersCollection['contentBlockId']);
            }
        }


        $qb->orderBy('cbgpi.id','ASC');
        return $qb->getQuery()->getArrayResult();

    }

    public function deleteByIdsCollection($idsCollection)
    {
        $em = $this->getEntityManager();
        $queryText = "DELETE FROM AppBundle:ContentBlockGenericPostItem cbgpi
                      WHERE cbgpi.id IN (".$idsCollection.")";
        $query = $em->createQuery($queryText);
        $query->execute();
    }
}

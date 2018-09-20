<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ContentBlockGalleryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentBlockGalleryRepository extends EntityRepository
{

    public function getContentBlockGalleries($parametersCollection){
        $qb = $this->createQueryBuilder('cbg');
        $qb->select(' cbg.id,
                      cb.id as content_block_id,
                      mg.id as media_gallery_id')
            ->innerJoin('cbg.content_block', 'cb')
            ->innerJoin('cbg.gallery', 'mg');

        $whereAdded = false;
        if(isset($parametersCollection['searchByIdsCollection']) && $parametersCollection['searchByIdsCollection'] == true
            && isset($parametersCollection['idsCollection'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("cbg.id IN (".$parametersCollection['idsCollection'].") ");
            }
            else{
                $qb->andWhere("cbg.id IN (".$parametersCollection['idsCollection'].") ");
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


        $qb->orderBy('cbg.id','ASC');
        return $qb->getQuery()->getArrayResult();

    }

    public function deleteByIdsCollection($idsCollection)
    {
        $em = $this->getEntityManager();
        $queryText = "DELETE FROM AppBundle:ContentBlockGallery cbg
                      WHERE cbg.id IN (".$idsCollection.")";
        $query = $em->createQuery($queryText);
        $query->execute();
    }
}

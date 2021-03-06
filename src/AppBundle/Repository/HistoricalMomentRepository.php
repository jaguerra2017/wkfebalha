<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * HistoricalMomentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HistoricalMomentRepository extends EntityRepository
{
    public function getHistoricalMomentsFullData($parametersCollection)
    {
        $qb = $this->createQueryBuilder('hm');
        $qb->select('hm.year,
                    gp.id, gp.title_es, gp.url_slug_es, gp.excerpt_es, gp.content_es,
                    gp.created_date, gp.modified_date,
                    gp.published_date,
                    u.user_name as author_name, uu.user_name as modified_author')
            ->innerJoin('hm.id', 'gp')
            ->leftJoin('gp.created_author', 'u')
            ->leftJoin('gp.modified_author', 'uu');

        $whereAdded = false;
        if(isset($parametersCollection['generalSearchValue'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("hm.year = ".$parametersCollection['generalSearchValue']);
                $qb->orWhere("gp.content_es LIKE '%".$parametersCollection['generalSearchValue']."%' ");
            }
            else{
                $qb->andWhere("hm.year = ".$parametersCollection['generalSearchValue']);
                $qb->orWhere("gp.content_es LIKE '%".$parametersCollection['generalSearchValue']."%' ");
            }
        }
        if(isset($parametersCollection['searchByIdsCollection']) && $parametersCollection['searchByIdsCollection'] == true
        && isset($parametersCollection['idsCollection'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("gp.id IN (".$parametersCollection['idsCollection'].") ");
            }
            else{
                $qb->andWhere("gp.id IN (".$parametersCollection['idsCollection'].") ");
            }
        }
        if(isset($parametersCollection['searchByYear']) && $parametersCollection['searchByYear'] == true
        && isset($parametersCollection['year'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("hm.year = ".$parametersCollection['year']);
            }
            else{
                $qb->andWhere("hm.year = ".$parametersCollection['year']);
            }
        }



        $qb->orderBy('hm.year','ASC');
        return $qb->getQuery()->getArrayResult();
    }

    public function getHistoricalMomentYears($parametersCollection){
        $qb = $this->createQueryBuilder('hm');
        $qb->select('DISTINCT(hm.year), hm.year')
            ->innerJoin('hm.id', 'gp');

        $whereAdded = false;
        if(isset($parametersCollection['generalSearchValue'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("hm.year = ".$parametersCollection['generalSearchValue']);
            }
            else{
                $qb->andWhere("hm.year = ".$parametersCollection['generalSearchValue']);
            }
        }
        if(isset($parametersCollection['searchByYear']) && $parametersCollection['searchByYear'] == true
        && isset($parametersCollection['year'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("hm.year = ".$parametersCollection['year']);
            }
            else{
                $qb->andWhere("hm.year = ".$parametersCollection['year']);
            }
        }
        if(isset($parametersCollection['searchByPostStatusSlug']) && $parametersCollection['searchByPostStatusSlug'] == true
        && isset($parametersCollection['postStatusSlug']) && $parametersCollection['postStatusSlug'] != null){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("gp.post_status_slug = '".$parametersCollection['postStatusSlug']."' ");
            }
            else{
                $qb->andWhere("gp.post_status_slug = '".$parametersCollection['postStatusSlug']."' ");
            }
        }



        $qb->orderBy('hm.year','ASC');
        return $qb->getQuery()->getArrayResult();
    }
}

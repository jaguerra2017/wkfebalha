<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository
{
    public function getEvents($parametersCollection)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select(' e.start_date, e.end_date, e.place_es,
              gp.id, gp.title_es, gp.url_slug_es, gp.excerpt_es, gp.content_es,
              gp.have_featured_image, gp.priority, gp.created_date, gp.modified_date,
              gp.published_date,gp.post_status_slug,
              fi.id as featured_image_id, fi.url as featured_image_url, fi.name_es as featured_image_name,
              u.user_name as author_name,u.full_name as author_full_name, uu.user_name as modified_author')
            ->innerJoin('e.id', 'gp')
            ->innerJoin('gp.generic_post_type', 'gpt')
            ->leftJoin('gp.created_author', 'u')
            ->leftJoin('gp.modified_author', 'uu')
            ->leftJoin('gp.featured_image', 'fi');

        $whereAdded = false;
        if(isset($parametersCollection['generalSearchValue'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("gp.title_es LIKE '%".$parametersCollection['generalSearchValue']."%' ");
            }
            else{
                $qb->andWhere("gp.title_es LIKE '%".$parametersCollection['generalSearchValue']."%' ");
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
        if(isset($parametersCollection['searchByStartDate']) && $parametersCollection['searchByStartDate'] == true){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("e.start_date >= '".$parametersCollection['startDate']."' ");
            }
            else{
                $qb->andWhere("e.start_date >= '".$parametersCollection['startDate']."' ");
            }
        }
        if(isset($parametersCollection['searchByPagination']) && $parametersCollection['searchByPagination'] == true
        && isset($parametersCollection['start']) && isset($parametersCollection['end'])){
            $qb->setFirstResult($parametersCollection['start'])->setMaxResults($parametersCollection['end']);
        }
        if(isset($parametersCollection['returnByCustomOrder']) && $parametersCollection['returnByCustomOrder'] == true){
            $qb->orderBy($parametersCollection['customOrderField'],$parametersCollection['customOrderSort']);
        }
        else{
            $qb->orderBy('gp.created_date','DESC');
        }

        $dataCollection = $qb->getQuery()->getArrayResult();

        if(isset($parametersCollection['just_count']) && $parametersCollection['just_count'] == true){
            return count($dataCollection);
        }
        else{
            return $dataCollection;
        }
    }
}

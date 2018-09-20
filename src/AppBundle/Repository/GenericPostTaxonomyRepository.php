<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * GenericPostTaxonomyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GenericPostTaxonomyRepository extends EntityRepository
{
    public function getGenericPostTaxonomies($parametersCollection)
    {
        $qb = $this->createQueryBuilder('gpt');
        $qb->select('gpt.id as generic_post_taxonomy_id,
                    t.id as taxonomy_id, t.name_es as taxonomy_name,
                      t.id as id, t.name_es as name_es,
                     t.url_slug_es,
                    t.tree_slug, t.depth,
                    gp.id as generic_post_id, gp.title_es as generic_post_title')
            ->innerJoin('gpt.taxonomy', 't')
            ->innerJoin('gpt.generic_post', 'gp');

        $whereAdded = false;
        if(isset($parametersCollection['searchByTaxonomy'])){
            if(!$whereAdded){
                $whereAdded = true;
                if($parametersCollection['taxonomiesId'] != null){
                    $qb->where("t.id IN (".$parametersCollection['taxonomiesId'].")");
                }
            }
            else{
                if($parametersCollection['taxonomiesId'] != null){
                    $qb->andWhere("t.id IN (".$parametersCollection['taxonomiesId'].")");
                }
            }
        }

        if(isset($parametersCollection['searchByGenericPost'])){
            if(!$whereAdded){
                $whereAdded = true;
                if($parametersCollection['genericPostsId'] != null){
                    $qb->where("gp.id IN (".$parametersCollection['genericPostsId'].")");
                }
            }
            else{
                if($parametersCollection['genericPostsId'] != null){
                    $qb->andWhere("gp.id IN (".$parametersCollection['genericPostsId'].")");
                }
            }
        }

        $qb->orderBy('gpt.id','DESC');
        return $qb->getQuery()->getArrayResult();
    }

    public function getGenericPosts($parametersCollection)
    {
        $qb = $this->createQueryBuilder('gptax');
        $qb->select(' cat.id as category_id, cat.tree_slug as category_tree_slug,
                      gp.id, gp.title_es, gp.url_slug_es, gp.excerpt_es, gp.content_es,
                      gp.have_featured_image, gp.priority, gp.created_date, gp.modified_date,
                      gp.published_date,gp.post_status_slug,
                      fi.id as featured_image_id, fi.url as featured_image_url, fi.name_es as featured_image_name,
                      u.user_name as author_name,u.full_name as author_full_name, uu.user_name as modified_author')
            ->innerJoin('gptax.taxonomy', 'cat')
            ->innerJoin('gptax.generic_post', 'gp')
            ->innerJoin('gp.generic_post_type', 'gpt')
            ->leftJoin('gp.created_author', 'u')
            ->leftJoin('gp.modified_author', 'uu')
            ->leftJoin('gp.featured_image', 'fi');

        $whereAdded = false;
        if(isset($parametersCollection['post_type_tree_slug'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("gpt.tree_slug LIKE '%".$parametersCollection['post_type_tree_slug']."%' ");
            }
            else{
                $qb->andWhere("gp.tree_slug LIKE '%".$parametersCollection['post_type_tree_slug']."%' ");
            }
        }
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
        if(isset($parametersCollection['searchByTaxonomy']) && $parametersCollection['searchByTaxonomy'] == true
            && isset($parametersCollection['taxonomyIds']) && $parametersCollection['taxonomyIds'] != null){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("cat.id IN (".$parametersCollection['taxonomyIds'].") ");
            }
            else{
                $qb->andWhere("cat.id IN (".$parametersCollection['taxonomyIds'].") ");
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
        if(isset($parametersCollection['searchByPagination']) && $parametersCollection['searchByPagination'] == true
            && isset($parametersCollection['start']) && isset($parametersCollection['end'])){
            $qb->setFirstResult($parametersCollection['start'])->setMaxResults($parametersCollection['end']);
        }
        if(isset($parametersCollection['returnByCustomOrder']) && $parametersCollection['returnByCustomOrder'] == true){
            $qb->orderBy('gp.'.$parametersCollection['customOrderField'],$parametersCollection['customOrderSort']);
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

    public function getFullTotalGenericPost($parametersCollection)
    {
        $qb = $this->createQueryBuilder('gptax');
        $qb->select(' count(gp.id)')
            ->innerJoin('gptax.taxonomy', 'cat')
            ->innerJoin('gptax.generic_post', 'gp')
            ->innerJoin('gp.generic_post_type', 'gpt')
            ->leftJoin('gp.created_author', 'u')
            ->leftJoin('gp.modified_author', 'uu')
            ->leftJoin('gp.featured_image', 'fi');

        $whereAdded = false;
        if(isset($parametersCollection['post_type_tree_slug'])){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("gpt.tree_slug LIKE '%".$parametersCollection['post_type_tree_slug']."%' ");
            }
            else{
                $qb->andWhere("gp.tree_slug LIKE '%".$parametersCollection['post_type_tree_slug']."%' ");
            }
        }
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
        if(isset($parametersCollection['searchByTaxonomy']) && $parametersCollection['searchByTaxonomy'] == true
            && isset($parametersCollection['taxonomyIds']) && $parametersCollection['taxonomyIds'] != null){
            if(!$whereAdded){
                $whereAdded = true;
                $qb->where("cat.id IN (".$parametersCollection['taxonomyIds'].") ");
            }
            else{
                $qb->andWhere("cat.id IN (".$parametersCollection['taxonomyIds'].") ");
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

        return $qb->getQuery()->getSingleScalarResult();


    }

    public function deleteByIdsCollection($idsCollection)
    {
        $em = $this->getEntityManager();
        $queryText = "DELETE FROM AppBundle:GenericPostTaxonomy gpt
                      WHERE gpt.id IN (".$idsCollection.")";
        $query = $em->createQuery($queryText);
        $query->execute();
    }


}

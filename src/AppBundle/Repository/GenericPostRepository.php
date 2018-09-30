<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * GenericPostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GenericPostRepository extends EntityRepository
{
  public function getGenericPostsFullData($parametersCollection)
  {
    $language = isset($parametersCollection['currentLanguage']) ? $parametersCollection['currentLanguage'] : 'es';
    $selectByLanguage = "gp.title_{$language} as title, gp.url_slug_{$language} as url_slug,
     gp.excerpt_{$language} as excerpt, gp.content_{$language} as content,fi.name_es as featured_image_name,";

    $qb = $this->createQueryBuilder('gp');
    $qb->select(' gp.id,' . $selectByLanguage . ' 
                      gp.have_featured_image, gp.priority, gp.created_date, gp.modified_date,
                      gp.published_date,
                      fi.id as featured_image_id, fi.url as featured_image_url, 
                      u.user_name as author_name,u.full_name as author_full_name, uu.user_name as modified_author')
      ->innerJoin('gp.generic_post_type', 'gpt')
      ->leftJoin('gp.created_author', 'u')
      ->leftJoin('gp.modified_author', 'uu')
      ->leftJoin('gp.featured_image', 'fi');

    $whereAdded = false;
    if (isset($parametersCollection['post_type_tree_slug'])) {
      if (!$whereAdded) {
        $whereAdded = true;
        $qb->where("gpt.tree_slug LIKE '%" . $parametersCollection['post_type_tree_slug'] . "%' ");
      } else {
        $qb->andWhere("gp.tree_slug LIKE '%" . $parametersCollection['post_type_tree_slug'] . "%' ");
      }
    }
    if (isset($parametersCollection['generalSearchValue'])) {
      if (!$whereAdded) {
        $whereAdded = true;
        $qb->where("gp.title_{$language} LIKE '%" . $parametersCollection['generalSearchValue'] . "%' ");
      } else {
        $qb->andWhere("gp.title_{$language} LIKE '%" . $parametersCollection['generalSearchValue'] . "%' ");
      }
    }
    if (isset($parametersCollection['searchByIdsCollection']) && $parametersCollection['searchByIdsCollection'] == true
      && isset($parametersCollection['idsCollection'])) {
      if (!$whereAdded) {
        $whereAdded = true;
        $qb->where("gp.id IN (" . $parametersCollection['idsCollection'] . ") ");
      } else {
        $qb->andWhere("gp.id IN (" . $parametersCollection['idsCollection'] . ") ");
      }
    }


    $qb->orderBy('gp.created_date', 'DESC');
    return $qb->getQuery()->getArrayResult();
  }

  public function getGenericPostsBasicData($parametersCollection)
  {
    $language = isset($parametersCollection['currentLanguage']) ? $parametersCollection['currentLanguage'] : 'es';
    $selectByLanguage = "gp.title_{$language} as title, gp.url_slug_{$language} as url_slug,
     gp.excerpt_{$language} as excerpt, gp.content_{$language} as content,fi.name_es as featured_image_name,";

    $qb = $this->createQueryBuilder('gp');
    $qb->select(' gp.id, ' . $selectByLanguage . '
                      gp.have_featured_image, gp.priority, gp.created_date, gp.modified_date,
                      gp.published_date,gp.post_status_slug,
                      fi.id as featured_image_id, fi.url as featured_image_url, 
                      u.user_name as author_name,u.full_name as author_full_name, uu.user_name as modified_author')
      ->innerJoin('gp.generic_post_type', 'gpt')
      ->leftJoin('gp.created_author', 'u')
      ->leftJoin('gp.modified_author', 'uu')
      ->leftJoin('gp.featured_image', 'fi');

    $whereAdded = false;
    if (isset($parametersCollection['post_type_tree_slug'])) {
      if (!$whereAdded) {
        $whereAdded = true;
        $qb->where("gpt.tree_slug LIKE '%" . $parametersCollection['post_type_tree_slug'] . "%' ");
      } else {
        $qb->andWhere("gp.tree_slug LIKE '%" . $parametersCollection['post_type_tree_slug'] . "%' ");
      }
    }
    if (isset($parametersCollection['generalSearchValue'])) {
      if (!$whereAdded) {
        $whereAdded = true;
        $qb->where("gp.title_{$language} LIKE '%" . $parametersCollection['generalSearchValue'] . "%' ");
      } else {
        $qb->andWhere("gp.title_{$language} LIKE '%" . $parametersCollection['generalSearchValue'] . "%' ");
      }
    }
    if (isset($parametersCollection['searchByPostStatusSlug']) && $parametersCollection['searchByPostStatusSlug'] == true
      && isset($parametersCollection['postStatusSlug']) && $parametersCollection['postStatusSlug'] != null) {
      if (!$whereAdded) {
        $whereAdded = true;
        $qb->where("gp.post_status_slug = '" . $parametersCollection['postStatusSlug'] . "' ");
      } else {
        $qb->andWhere("gp.post_status_slug = '" . $parametersCollection['postStatusSlug'] . "' ");
      }
    }
    if (isset($parametersCollection['searchByIdsCollection']) && $parametersCollection['searchByIdsCollection'] == true
      && isset($parametersCollection['idsCollection'])) {
      if (!$whereAdded) {
        $whereAdded = true;
        $qb->where("gp.id IN (" . $parametersCollection['idsCollection'] . ") ");
      } else {
        $qb->andWhere("gp.id IN (" . $parametersCollection['idsCollection'] . ") ");
      }
    }
    if (isset($parametersCollection['searchByPagination']) && $parametersCollection['searchByPagination'] == true
      && isset($parametersCollection['start']) && isset($parametersCollection['end'])) {
      $qb->setFirstResult($parametersCollection['start'])->setMaxResults($parametersCollection['end']);
    }
    if (isset($parametersCollection['returnByCustomOrder']) && $parametersCollection['returnByCustomOrder'] == true) {
      $qb->orderBy('gp.' . $parametersCollection['customOrderField'], $parametersCollection['customOrderSort']);
    } else {
      $qb->orderBy('gp.created_date', 'DESC');
    }

    $dataCollection = $qb->getQuery()->getArrayResult();

    if (isset($parametersCollection['just_count']) && $parametersCollection['just_count'] == true) {
      return count($dataCollection);
    } else {
      return $dataCollection;
    }


  }

  public function getFullTotal($parametersCollection)
  {
    $qb = $this->createQueryBuilder('gp');
    $qb->select(' count(gp.id)')
      ->innerJoin('gp.generic_post_type', 'gpt');

    $whereAdded = false;
    if (isset($parametersCollection['post_type_tree_slug'])) {
      if (!$whereAdded) {
        $whereAdded = true;
        $qb->where("gpt.tree_slug LIKE '%" . $parametersCollection['post_type_tree_slug'] . "%' ");
      } else {
        $qb->andWhere("gp.tree_slug LIKE '%" . $parametersCollection['post_type_tree_slug'] . "%' ");
      }
    }
    return $qb->getQuery()->getSingleScalarResult();
  }

  public function deleteByIdsCollection($idsCollection)
  {
    $em = $this->getEntityManager();
    $queryText = "DELETE FROM AppBundle:GenericPost gp
                      WHERE gp.id IN (" . $idsCollection . ")";
    $query = $em->createQuery($queryText);
    $query->execute();
  }
}

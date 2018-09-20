<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\GenericPostTaxonomy;
use AppBundle\Entity\Award;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Entity\GenericPost;
use AppBundle\Bussiness\NomenclatureBussiness;
use AppBundle\Entity\GenericPostNomenclature;



class AwardsBussiness
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadInitialsData($parametersCollection)
    {
        try{
            $initialsData = array();
            $initialsData['awardsDataCollection'] = $this->getAwardsList($parametersCollection);

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getAwardsList($parametersCollection)
    {
        try{
            if(isset($parametersCollection['getFullTotal']) && $parametersCollection['getFullTotal'] == true){
                return $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getFullTotalGenericPost($parametersCollection);
            }


            $parametersCollection['post_type_tree_slug'] = 'award';
            if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                $parametersCollection['searchByIdsCollection'] = true;
                $idsCollection = array();
                $idsCollection[0] = $parametersCollection['awardId'];
                $idsCollection = implode(',', $idsCollection);
                $parametersCollection['idsCollection'] = $idsCollection;
                $awardsCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsFullData($parametersCollection);
            }
            else{
                if(isset($parametersCollection['searchByTaxonomy']) && $parametersCollection['searchByTaxonomy'] == true){
                    $awardsCollection = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPosts($parametersCollection);
                }
                else{
                    $awardsCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
                }
            }
            if(isset($awardsCollection[0])){
                foreach($awardsCollection as $key=>$award){
                    $canEdit = 1;
                    $canDelete = 1;
                    $awardsCollection[$key]['canEdit'] = $canEdit;
                    $awardsCollection[$key]['canDelete'] = $canDelete;

                    /*handling Post Status*/
                    $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($award['id']);
                    $objPostStatus = $this->em->getRepository('AppBundle:GenericPostNomenclature')->findOneBy(array(
                        'generic_post' => $objGenericPost,
                        'relation_slug' => 'post_status'
                    ));
                    if(isset($objPostStatus)){
                        $awardsCollection[$key]['post_status_name'] = $objPostStatus->getNomenclature()->getName();
                        if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                            $awardsCollection[$key]['post_status_id'] = $objPostStatus->getNomenclature()->getId();
                        }
                    }
                    
                    /*handling dates*/
                    $awardsCollection[$key]['created_date'] = date_format($award['created_date'],'d/m/Y H:i');
                    if($award['modified_date'] != null){
                        $awardsCollection[$key]['modified_date'] = date_format($award['modified_date'],'d/m/Y');
                    }
                    if($award['published_date'] != null){
                        $awardsCollection[$key]['published_date'] = date_format($award['published_date'],'d/m/Y');
                    }
                    
                    /*handling data for Single Result*/
                    if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                        /*handling Categories*/
                        $genericPostsId = array();
                        $genericPostsId[0] = $award['id'];
                        $categoriesCollection = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPostTaxonomies(array(
                            'searchByGenericPost' => true,
                            'genericPostsId' => implode(',', $genericPostsId)
                        ));
                        $awardsCollection[$key]['categoriesCollection'] = $categoriesCollection;
                    }
                }
            }

            if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true
            && isset($awardsCollection[0])){
                return $awardsCollection[0];
            }
            return $awardsCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveAwardData($parametersCollection){
        try{

            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGenericPostType = $this->em->getRepository('AppBundle:GenericPostType')->findOneBy(array(
                'tree_slug' => 'award'
            ));
            $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->findOneBy(array(
                'title_es' => $parametersCollection['title_es'],
                'generic_post_type' => $objGenericPostType
            ));
            if(isset($objGenericPost)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objGenericPost->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe una distincion con ese nombre.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
            }
            $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->findOneBy(array(
                'url_slug_es' => $parametersCollection['url_slug_es']/*,
                'generic_post_type' => $objGenericPostType*/
            ));
            if(isset($objGenericPost)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objGenericPost->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe un elemento con ese slug.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
            }

            /*persisting Generic Post Object*/
            $objGenericPost = new GenericPost();
            if($parametersCollection['isCreating'] == false){

                $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['id']);
                if(!isset($objGenericPost)){
                    $message = 'El asociado que desea editar ya no existe.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
                $objGenericPost->setModifiedDate(new \DateTime());
                $objGenericPost->setModifiedAuthor($parametersCollection['loggedUser']);
            }
            else{
                $objGenericPost->setCreatedAuthor($parametersCollection['loggedUser']);
            }
            $objGenericPost->setTitle($parametersCollection['title_es']);
            $objGenericPost->setUrlSlug($parametersCollection['url_slug_es']);
            $objGenericPost->setGenericPostType($objGenericPostType);
            $objGenericPost->setContent($parametersCollection['content_es']);
            if(isset($parametersCollection['excerpt_es'])){
                $objGenericPost->setExcerpt($parametersCollection['excerpt_es']);
            }
            $objGenericPost->setHaveFeaturedImage(false);
            if(isset($parametersCollection['featured_image_id'])){
                $objFeatureImage = $this->em->getRepository('AppBundle:Media')->find($parametersCollection['featured_image_id']);
                if(isset($objFeatureImage)){
                    $objGenericPost->setFeaturedImage($objFeatureImage);
                    $objGenericPost->setHaveFeaturedImage(true);
                }
            }
            $this->em->persist($objGenericPost);
            $this->em->flush($objGenericPost);

            /*persisting relation Post Status - Award */
            if(isset($parametersCollection['post_status_id'])){
                $objNomPostStatus = $this->em->getRepository('AppBundle:Nomenclature')->find($parametersCollection['post_status_id']);
                if(isset($objNomPostStatus)) {
                    $objPostStatus = $this->em->getRepository('AppBundle:GenericPostNomenclature')->findOneBy(array(
                        'generic_post' => $objGenericPost,
                        'relation_slug' => 'post_status'
                    ));
                    if (isset($objPostStatus)) {
                        $this->em->remove($objPostStatus);
                        $this->em->flush($objPostStatus);
                    }
                    $objPostStatus = new GenericPostNomenclature();
                    $objPostStatus->setGenericPost($objGenericPost);
                    $objPostStatus->setNomenclature($objNomPostStatus);
                    $objPostStatus->setRelationSlug('post_status');
                    $this->em->persist($objPostStatus);

                    $publishedDate = null;
                    if ($objNomPostStatus->getTreeSlug() == 'generic-post-status-published') {
                        $publishedDate = new \DateTime();
                        if (isset($parametersCollection['published_date']) &&
                            $parametersCollection['published_date'] != null
                        ) {
                            $publishedDateCollection = explode('/', $parametersCollection['published_date']);
                            if (isset($publishedDateCollection[2])) {
                                $publishedDate = new \DateTime($publishedDateCollection[1] . '/' . $publishedDateCollection[0] . '/' . $publishedDateCollection[2]);
                            }
                        }
                    }
                    $objGenericPost->setPublishedDate($publishedDate);
                    $objGenericPost->setPostStatusSlug($objNomPostStatus->getTreeSlug());
                    $this->em->persist($objGenericPost);
                }
            }

            /*Handling relation Taxonomy - Award*/
            /*deleting previous association between Generic Post and Taxonomy*/
            $genericPostsId = array();
            $genericPostsId[0] = $objGenericPost->getId();
            $genericPostsId = implode(',', $genericPostsId);
            $genericPostTaxCollection = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPostTaxonomies(array(
                'searchByGenericPost' => true,
                'genericPostsId' => $genericPostsId
            ));
            if(isset($genericPostTaxCollection[0])){
                $genericPostTaxonomiesId = array();
                foreach($genericPostTaxCollection as $key=>$genericPostTaxonomy){
                    $genericPostTaxonomiesId[$key] = $genericPostTaxonomy['generic_post_taxonomy_id'];
                }
                $genericPostTaxonomiesId = implode(',', $genericPostTaxonomiesId);
                $this->em->getRepository('AppBundle:GenericPostTaxonomy')->deleteByIdsCollection($genericPostTaxonomiesId);
            }
            /*persisting new relations between Generic Post and Taxonomy*/
            if(isset($parametersCollection['selected_categories_id']) &&
                isset($parametersCollection['selected_categories_id'][0])){
                foreach($parametersCollection['selected_categories_id'] as $selected_category_id){
                    $objTaxonomy = $this->em->getRepository('AppBundle:Taxonomy')->find($selected_category_id);
                    if(isset($objTaxonomy)){
                        $objGenericPostTaxonomy = new GenericPostTaxonomy();
                        $objGenericPostTaxonomy->setTaxonomy($objTaxonomy);
                        $objGenericPostTaxonomy->setGenericPost($objGenericPost);
                        $this->em->persist($objGenericPostTaxonomy);
                    }
                }
            }

            $this->em->flush();

            return $this->returnResponse(array('success'=>1,'message'=>$message, 'awardId'=>$objGenericPost->getId()));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteAwardsData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['awardsId'][0])) {
                $objCommentBussiness = new CommentsBussiness($this->em);
                $objCommentBussiness->updateCommentsPending(null, $parametersCollection['awardsId']);

                $idsCollection = implode(',',$parametersCollection['awardsId']);
                $this->em->getRepository('AppBundle:GenericPost')->deleteByIdsCollection($idsCollection);
            }
            else{
                $message = 'No existen publicaciones para eliminar.';
                return $this->returnResponse(array('sucsess'=>0,'message'=>$message));
            }


            return $this->returnResponse(array('sucsess'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }



    public function returnResponse($parametersCollection){
        return $parametersCollection;
    }

}
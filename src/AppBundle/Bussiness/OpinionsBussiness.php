<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\GenericPostTaxonomy;
use AppBundle\Entity\Opinion;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Entity\GenericPost;
use AppBundle\Bussiness\NomenclatureBussiness;
use AppBundle\Entity\GenericPostNomenclature;



class OpinionsBussiness
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
            $initialsData['opinionsDataCollection'] = $this->getOpinionsList($parametersCollection);

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
         }
    }

    public function getOpinionsList($parametersCollection)
    {
        try{

            $parametersCollection['post_type_tree_slug'] = 'opinion';
            if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                $parametersCollection['searchByIdsCollection'] = true;
                $idsCollection = array();
                $idsCollection[0] = $parametersCollection['opinionId'];
                $idsCollection = implode(',', $idsCollection);
                $parametersCollection['idsCollection'] = $idsCollection;
                $opinionsCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsFullData($parametersCollection);
            }
            else{

                if(isset($parametersCollection['searchByTaxonomy']) && $parametersCollection['searchByTaxonomy'] == true){
                    $parametersCollection['taxonomyIds'] = implode(',',$parametersCollection['taxonomyIds']);
                    $opinionsCollection = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPosts($parametersCollection);
                }
                else{
                    $opinionsCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
                }

            }
            if(isset($opinionsCollection[0])){
                foreach($opinionsCollection as $key=>$opinion){
                    $canEdit = 1;
                    $canDelete = 1;
                    $opinionsCollection[$key]['canEdit'] = $canEdit;
                    $opinionsCollection[$key]['canDelete'] = $canDelete;
                    /*handling Post Status*/
                    $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($opinion['id']);
                    $objPostStatus = $this->em->getRepository('AppBundle:GenericPostNomenclature')->findOneBy(array(
                        'generic_post' => $objGenericPost,
                        'relation_slug' => 'post_status'
                    ));
                    if(isset($objPostStatus)){
                        $opinionsCollection[$key]['post_status_name'] = $objPostStatus->getNomenclature()->getName();
                        if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                            $opinionsCollection[$key]['post_status_id'] = $objPostStatus->getNomenclature()->getId();
                        }
                    }
                    /*handling dates*/
                    $opinionsCollection[$key]['created_date'] = date_format($opinion['created_date'],'d/m/Y H:i');
                    /*handling Categories*/
                    $genericPostsId = array();
                    $genericPostsId[0] = $opinion['id'];
                    $categoriesCollection = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPostTaxonomies(array(
                        'searchByGenericPost' => true,
                        'genericPostsId' => implode(',', $genericPostsId)
                    ));
                    $opinionsCollection[$key]['categoriesCollection'] = $categoriesCollection;
                    /*handling data for Single Result*/
                    if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                        /*handling dates*/
                        if($opinion['modified_date'] != null){
                            $opinionsCollection[$key]['modified_date'] = date_format($opinion['modified_date'],'d/m/Y H:i');
                        }
                        if($opinion['published_date'] != null){
                            $opinionsCollection[$key]['published_date'] = date_format($opinion['published_date'],'d/m/Y H:i');
                        }
                    }

                    $opinionsCollection[$key]['content_es'] = $objGenericPost->getContent();
                }
            }

            if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true
            && isset($opinionsCollection[0])){
                return $opinionsCollection[0];
            }
            return $opinionsCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveOpinionData($parametersCollection){
        try{

            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGenericPostType = $this->em->getRepository('AppBundle:GenericPostType')->findOneBy(array(
                'tree_slug' => 'opinion'
            ));
            $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->findOneBy(array(
                'title_es' => $parametersCollection['title_es']
            ));
            if(isset($objGenericPost)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objGenericPost->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe una crítica / opinión con esa referencia.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
            }

            /*persisting Generic Post Object*/
            $objGenericPost = new GenericPost();
            if($parametersCollection['isCreating'] == false){

                $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['id']);
                if(!isset($objGenericPost)){
                    $message = 'La crítica / opinión que desea editar ya no existe.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
                $objGenericPost->setModifiedDate(new \DateTime());
                $objGenericPost->setModifiedAuthor($parametersCollection['loggedUser']);
            }
            $objGenericPost->setGenericPostType($objGenericPostType);
            $objGenericPost->setTitle($parametersCollection['title_es']);
            $objGenericPost->setContent($parametersCollection['content_es']);
            if($parametersCollection['isCreating'] == true){
                $objGenericPost->setCreatedAuthor($parametersCollection['loggedUser']);
            }
            else{
                $objGenericPost->setModifiedDate(new \DateTime());
                $objGenericPost->setModifiedAuthor($parametersCollection['loggedUser']);
            }
            $this->em->persist($objGenericPost);
            $this->em->flush($objGenericPost);

            /*persisting relation Post Status - Opinion */
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

            /*Handling relation Taxonomy - Opinion*/
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

            return $this->returnResponse(array('success'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteOpinionsData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['opinionsId'][0])) {
                $idsCollection = implode(',',$parametersCollection['opinionsId']);
                $this->em->getRepository('AppBundle:GenericPost')->deleteByIdsCollection($idsCollection);
            }
            else{
                $message = 'No existen opiniones para eliminar.';
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
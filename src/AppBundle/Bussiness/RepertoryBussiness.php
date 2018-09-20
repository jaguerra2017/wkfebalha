<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\GenericPostTaxonomy;
use AppBundle\Entity\Repertory;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Entity\GenericPost;
use AppBundle\Bussiness\NomenclatureBussiness;
use AppBundle\Entity\GenericPostNomenclature;



class RepertoryBussiness
{
    private $em;
    private $container;

    public function __construct(EntityManager $em, Container $container = null)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function loadInitialsData($parametersCollection)
    {
        try{
            $initialsData = array();
            $initialsData['repertoryDataCollection'] = $this->getRepertoryList($parametersCollection);

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
         }
    }

    public function getRepertoryList($parametersCollection)
    {
        try{
            if(isset($parametersCollection['getFullTotal']) && $parametersCollection['getFullTotal'] == true){
                return $this->em->getRepository('AppBundle:GenericPost')->getFullTotal($parametersCollection);
            }
            
            $parametersCollection['post_type_tree_slug'] = 'repertory';
            if(isset($parametersCollection['singleResult'])){
                $parametersCollection['searchByIdsCollection'] = true;
                $idsCollection = array();
                $idsCollection[0] = $parametersCollection['repertoryId'];
                $idsCollection = implode(',', $idsCollection);
                $parametersCollection['idsCollection'] = $idsCollection;
                $repertoryCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsFullData($parametersCollection);
            }
            else{
                $repertoryCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
            }
            if(isset($repertoryCollection[0])){
                foreach($repertoryCollection as $key=>$repertory){
                    $canEdit = 1;
                    $canDelete = 1;
                    $repertoryCollection[$key]['canEdit'] = $canEdit;
                    $repertoryCollection[$key]['canDelete'] = $canDelete;

                    /*handling Post Status*/
                    $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($repertory['id']);
                    $objPostStatus = $this->em->getRepository('AppBundle:GenericPostNomenclature')->findOneBy(array(
                        'generic_post' => $objGenericPost,
                        'relation_slug' => 'post_status'
                    ));
                    if(isset($objPostStatus)){
                        $repertoryCollection[$key]['post_status_name'] = $objPostStatus->getNomenclature()->getName();
                        if(isset($parametersCollection['singleResult'])){
                            $repertoryCollection[$key]['post_status_id'] = $objPostStatus->getNomenclature()->getId();
                        }
                    }

                    /*handling dates*/
                    $repertoryCollection[$key]['created_date'] = date_format($repertory['created_date'],'d/m/Y');
                    if($repertory['modified_date'] != null){
                        $repertoryCollection[$key]['modified_date'] = date_format($repertory['modified_date'],'d/m/Y');
                    }
                    if($repertory['published_date'] != null){
                        $repertoryCollection[$key]['published_date'] = date_format($repertory['published_date'],'d/m/Y');
                    }

                    /*handling featured image urls*/
                    if($repertory['have_featured_image'] == true){
                        $objMediaImage = $this->em->getRepository('AppBundle:MediaImage')->find($repertory['featured_image_id']);
                        $featured_image_extension = $objMediaImage->getExtension();
                        $objSharedFileFinderBussiness = new SharedFileFinderBussiness();
                        /*Simple List Mini-thumbnail*/
                        if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                            'filter_name' => 'list_featured_image_mini_thumbnail',
                            'image_name' => $repertory['featured_image_name'],
                            'image_extension' => $featured_image_extension,
                            'just_check' => true,
                            'just_web_filtered_url' => false
                        ))){
                            $repertoryCollection[$key]['web_filtered_list_featured_image_mini_thumbnail_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                'filter_name' => 'list_featured_image_mini_thumbnail',
                                'image_name' => $repertory['featured_image_name'],
                                'image_extension' => $featured_image_extension,
                                'just_check' => false,
                                'just_web_filtered_url' => true
                            ));
                        }
                        else{
                            $repertoryCollection[$key]['web_filtered_list_featured_image_mini_thumbnail_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($repertory['featured_image_url'], 'list_featured_image_mini_thumbnail');
                        }
                        /*Grid List thumbnail*/
                        if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                            'filter_name' => 'grid_featured_image_thumbnail',
                            'image_name' => $repertory['featured_image_name'],
                            'image_extension' => $featured_image_extension,
                            'just_check' => true,
                            'just_web_filtered_url' => false
                        ))){
                            $repertoryCollection[$key]['web_filtered_grid_featured_image_thumbnail_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                'filter_name' => 'grid_featured_image_thumbnail',
                                'image_name' => $repertory['featured_image_name'],
                                'image_extension' => $featured_image_extension,
                                'just_check' => false,
                                'just_web_filtered_url' => true
                            ));
                        }
                        else{
                            $repertoryCollection[$key]['web_filtered_grid_featured_image_thumbnail_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($repertory['featured_image_url'], 'grid_featured_image_thumbnail');
                        }

                        /*Feature Image Single Post*/
                        if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                            if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                'filter_name' => 'single_post_featured_image',
                                'image_name' => $repertory['featured_image_name'],
                                'image_extension' => $featured_image_extension,
                                'just_check' => true,
                                'just_web_filtered_url' => false
                            ))){
                                $repertoryCollection[$key]['web_filtered_single_post_feature_image_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                    'filter_name' => 'single_post_featured_image',
                                    'image_name' => $repertory['featured_image_name'],
                                    'image_extension' => $featured_image_extension,
                                    'just_check' => false,
                                    'just_web_filtered_url' => true
                                ));
                            }
                            else{
                                $repertoryCollection[$key]['web_filtered_single_post_feature_image_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($repertory['featured_image_url'], 'single_post_featured_image');
                            }
                        }
                    }

                    /*handling Url*/
                    if($this->container == null && isset($parametersCollection['container'])){
                        $this->container = $parametersCollection['container'];
                    }
                    if($this->container != null){
                        $siteDomain = $this->container->get('appbundle_site_settings')->getBncDomain();
                        $repertoryCollection[$key]['url'] = $siteDomain.'/es/repertorio/'.$repertory['url_slug_es'];
                    }

                    /*handling number of comments*/
                    $totalComments = 0;
                    $commentsCollection = $this->em->getRepository('AppBundle:Comment')->findBy(array(
                        'generic_post' => $objGenericPost
                    ));
                    if(isset($commentsCollection[0])){
                        $totalComments = count($commentsCollection);
                    }
                    $repertoryCollection[$key]['total_comments'] = $totalComments;
                    
                    /*handling data for Single Result*/
                    if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                        /*handling Categories*/
                        $genericPostsId = array();
                        $genericPostsId[0] = $repertory['id'];
                        $categoriesCollection = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPostTaxonomies(array(
                            'searchByGenericPost' => true,
                            'genericPostsId' => implode(',', $genericPostsId)
                        ));
                        $repertoryCollection[$key]['categoriesCollection'] = $categoriesCollection;
                    }

                    /*handling data for Repertory Object*/
                    $objRepertory = $this->em->getRepository('AppBundle:Repertory')->find($objGenericPost);
                    if(isset($objRepertory)){
                        $repertoryCollection[$key]['choreography'] = $objRepertory->getChoreography();
                        $repertoryCollection[$key]['music'] = $objRepertory->getMusic();
                        $repertoryCollection[$key]['scenery'] = $objRepertory->getScenery();
                        $repertoryCollection[$key]['apparel'] = $objRepertory->getApparel();
                        $repertoryCollection[$key]['production'] = $objRepertory->getProduction();
                    }
                }
            }

            if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] && isset($repertoryCollection[0])){
                return $repertoryCollection[0];
            }
            return $repertoryCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveRepertoryData($parametersCollection){
        try{

            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGenericPostType = $this->em->getRepository('AppBundle:GenericPostType')->findOneBy(array(
                'tree_slug' => 'repertory'
            ));
            $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->findOneBy(array(
                'title_es' => $parametersCollection['title_es'],
                'generic_post_type' => $objGenericPostType
            ));
            if(isset($objGenericPost)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objGenericPost->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe un repertorio con ese nombre.';
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

                $objRepertory = $this->em->getRepository('AppBundle:Repertory')->find($objGenericPost);
            }
            else{
                $objGenericPost->setCreatedAuthor($parametersCollection['loggedUser']);

                $objRepertory = new Repertory();
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

            /*persisting Repertory Object*/
            $objRepertory->setId($objGenericPost);
            if(isset($parametersCollection['apparel'])){
                $objRepertory->setApparel($parametersCollection['apparel']);
            }
            if(isset($parametersCollection['music'])){
                $objRepertory->setMusic($parametersCollection['music']);
            }
            if(isset($parametersCollection['scenery'])){
                $objRepertory->setScenery($parametersCollection['scenery']);
            }
            if(isset($parametersCollection['production'])){
                $objRepertory->setProduction($parametersCollection['production']);
            }
            if(isset($parametersCollection['choreography'])){
                $objRepertory->setChoreography($parametersCollection['choreography']);
            }
            $this->em->persist($objRepertory);

            /*persisting relation Post Status - Repertory */
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

            /*Handling relation Taxonomy - Repertory*/
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

            return $this->returnResponse(array('success'=>1,'message'=>$message, 'repertoryId'=>$objGenericPost->getId()));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteRepertoryData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['repertoryId'][0])) {
                $objCommentBussiness = new CommentsBussiness($this->em);
                $objCommentBussiness->updateCommentsPending(null, $parametersCollection['repertoryId']);

                $idsCollection = implode(',',$parametersCollection['repertoryId']);
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
<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Entity\GenericPost;
use AppBundle\Bussiness\NomenclatureBussiness;
use AppBundle\Entity\GenericPostNomenclature;
use AppBundle\Entity\GenericPostTaxonomy;


class CompositionBussiness
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
            $initialsData['compositionDataCollection'] = $this->getCompositionList($parametersCollection);

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
         }
    }

    public function getCompositionList($parametersCollection)
    {
        try{
            $parametersCollection['post_type_tree_slug'] = 'composition';
            if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                $parametersCollection['searchByIdsCollection'] = true;
                $idsCollection = array();
                $idsCollection[0] = $parametersCollection['compositionId'];
                $idsCollection = implode(',', $idsCollection);
                $parametersCollection['idsCollection'] = $idsCollection;
                $compositionCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsFullData($parametersCollection);
            }
            else{
                $compositionCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
            }
            if(isset($compositionCollection[0])){
                foreach($compositionCollection as $key=>$composition){
                    $canEdit = 1;
                    $canDelete = 1;
                    $compositionCollection[$key]['canEdit'] = $canEdit;
                    $compositionCollection[$key]['canDelete'] = $canDelete;

                    /*handling Post Status*/
                    $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($composition['id']);
                    $objPostStatus = $this->em->getRepository('AppBundle:GenericPostNomenclature')->findOneBy(array(
                        'generic_post' => $objGenericPost,
                        'relation_slug' => 'post_status'
                    ));
                    if(isset($objPostStatus)){
                        $compositionCollection[$key]['post_status_name'] = $objPostStatus->getNomenclature()->getName();
                        if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                            $compositionCollection[$key]['post_status_id'] = $objPostStatus->getNomenclature()->getId();
                        }
                    }

                    /*handling dates*/
                    $compositionCollection[$key]['created_date'] = date_format($composition['created_date'],'d/m/Y');
                    if($composition['modified_date'] != null){
                        $compositionCollection[$key]['modified_date'] = date_format($composition['modified_date'],'d/m/Y');
                    }
                    if($composition['published_date'] != null){
                        $compositionCollection[$key]['published_date'] = date_format($composition['published_date'],'d/m/Y');
                    }

                    /*handling featured image urls*/
                    if($composition['have_featured_image'] == true){
                        $objMediaImage = $this->em->getRepository('AppBundle:MediaImage')->find($composition['featured_image_id']);
                        $featured_image_extension = $objMediaImage->getExtension();
                        $objSharedFileFinderBussiness = new SharedFileFinderBussiness();
                        /*Simple List Mini-thumbnail*/
                        if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                            'filter_name' => 'list_featured_image_mini_thumbnail',
                            'image_name' => $composition['featured_image_name'],
                            'image_extension' => $featured_image_extension,
                            'just_check' => true,
                            'just_web_filtered_url' => false
                        ))){
                            $compositionCollection[$key]['web_filtered_list_featured_image_mini_thumbnail_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                'filter_name' => 'list_featured_image_mini_thumbnail',
                                'image_name' => $composition['featured_image_name'],
                                'image_extension' => $featured_image_extension,
                                'just_check' => false,
                                'just_web_filtered_url' => true
                            ));
                        }
                        else{
                            $compositionCollection[$key]['web_filtered_list_featured_image_mini_thumbnail_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($composition['featured_image_url'], 'list_featured_image_mini_thumbnail');
                        }
                        /*Grid List thumbnail*/
                        if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                            'filter_name' => 'grid_featured_image_thumbnail',
                            'image_name' => $composition['featured_image_name'],
                            'image_extension' => $featured_image_extension,
                            'just_check' => true,
                            'just_web_filtered_url' => false
                        ))){
                            $compositionCollection[$key]['web_filtered_grid_featured_image_thumbnail_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                'filter_name' => 'grid_featured_image_thumbnail',
                                'image_name' => $composition['featured_image_name'],
                                'image_extension' => $featured_image_extension,
                                'just_check' => false,
                                'just_web_filtered_url' => true
                            ));
                        }
                        else{
                            $compositionCollection[$key]['web_filtered_grid_featured_image_thumbnail_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($composition['featured_image_url'], 'grid_featured_image_thumbnail');
                        }

                        /*Feature Image Single Post*/
                        if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                            if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                'filter_name' => 'single_post_featured_image',
                                'image_name' => $composition['featured_image_name'],
                                'image_extension' => $featured_image_extension,
                                'just_check' => true,
                                'just_web_filtered_url' => false
                            ))){
                                $compositionCollection[$key]['web_filtered_single_post_feature_image_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                    'filter_name' => 'single_post_featured_image',
                                    'image_name' => $composition['featured_image_name'],
                                    'image_extension' => $featured_image_extension,
                                    'just_check' => false,
                                    'just_web_filtered_url' => true
                                ));
                            }
                            else{
                                $compositionCollection[$key]['web_filtered_single_post_feature_image_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($composition['featured_image_url'], 'single_post_featured_image');
                            }
                        }
                    }

                    /*handling Url*/
                    if($this->container == null && isset($parametersCollection['container'])){
                        $this->container = $parametersCollection['container'];
                    }
                    if($this->container != null){
                        $siteDomain = $this->container->get('appbundle_site_settings')->getBncDomain();
                        $compositionCollection[$key]['url'] = $siteDomain.'/es/composicion/'.$composition['url_slug_es'];
                    }

                    /*handling number of comments*/
                    $totalComments = 0;
                    $commentsCollection = $this->em->getRepository('AppBundle:Comment')->findBy(array(
                        'generic_post' => $objGenericPost
                    ));
                    if(isset($commentsCollection[0])){
                        $totalComments = count($commentsCollection);
                    }
                    $compositionCollection[$key]['total_comments'] = $totalComments;



                    /*handling data for Single Result*/
                    //if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                        /*handling Categories*/
                        $genericPostsId = array();
                        $genericPostsId[0] = $composition['id'];
                        $categoriesCollection = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPostTaxonomies(array(
                            'searchByGenericPost' => true,
                            'genericPostsId' => implode(',', $genericPostsId)
                        ));
                        $compositionCollection[$key]['categoriesCollection'] = $categoriesCollection;
                   // }

                }
            }

            if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true
            && isset($compositionCollection[0])){
                return $compositionCollection[0];
            }
            return $compositionCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveCompositionData($parametersCollection){
        try{

            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGenericPostType = $this->em->getRepository('AppBundle:GenericPostType')->findOneBy(array(
                'tree_slug' => 'composition'
            ));
            $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->findOneBy(array(
                'title_es' => $parametersCollection['title_es'],
                'generic_post_type' => $objGenericPostType
            ));
            if(isset($objGenericPost)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objGenericPost->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe un integrante con ese nombre.';
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
                    $message = 'El integrante que desea editar ya no existe.';
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

            /*persisting relation Post Status - Composition */
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

            /*Handling relation Taxonomy - Composition*/
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

            return $this->returnResponse(array('success'=>1,'message'=>$message, 'compositionId'=>$objGenericPost->getId()));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteCompositionData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['compositionId'][0])) {
                $objCommentBussiness = new CommentsBussiness($this->em);
                $objCommentBussiness->updateCommentsPending(null, $parametersCollection['compositionId']);

                $idsCollection = implode(',',$parametersCollection['compositionId']);
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
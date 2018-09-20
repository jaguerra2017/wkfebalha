<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\GenericPostTaxonomy;
use AppBundle\Entity\Event;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\DependencyInjection\Container;

use AppBundle\Entity\GenericPost;
use AppBundle\Bussiness\NomenclatureBussiness;
use AppBundle\Entity\GenericPostNomenclature;



class EventsBussiness
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
            $initialsData['eventsDataCollection'] = $this->getEventsList($parametersCollection);

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getEventsList($parametersCollection)
    {
        try{
            if(isset($parametersCollection['getFullTotal']) && $parametersCollection['getFullTotal'] == true){
                return $this->em->getRepository('AppBundle:GenericPost')->getFullTotal($parametersCollection);
            }


            $parametersCollection['post_type_tree_slug'] = 'event';
            if(isset($parametersCollection['singleResult'])){
                $parametersCollection['searchByIdsCollection'] = true;
                $idsCollection = array();
                $idsCollection[0] = $parametersCollection['eventId'];
                $idsCollection = implode(',', $idsCollection);
                $parametersCollection['idsCollection'] = $idsCollection;
                $eventsCollection = $this->em->getRepository('AppBundle:Event')->getEvents($parametersCollection);
            }
            $eventsCollection = $this->em->getRepository('AppBundle:Event')->getEvents($parametersCollection);
            if(isset($eventsCollection[0])){
                foreach($eventsCollection as $key=>$event){
                    $canEdit = 1;
                    $canDelete = 1;
                    $eventsCollection[$key]['canEdit'] = $canEdit;
                    $eventsCollection[$key]['canDelete'] = $canDelete;

                    /*handling Post Status*/
                    $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($event['id']);
                    $objPostStatus = $this->em->getRepository('AppBundle:GenericPostNomenclature')->findOneBy(array(
                        'generic_post' => $objGenericPost,
                        'relation_slug' => 'post_status'
                    ));
                    if(isset($objPostStatus)){
                        $eventsCollection[$key]['post_status_name'] = $objPostStatus->getNomenclature()->getName();
                        if(isset($parametersCollection['singleResult'])){
                            $eventsCollection[$key]['post_status_id'] = $objPostStatus->getNomenclature()->getId();
                        }
                    }

                    /*handling dates*/
                    $eventsCollection[$key]['created_date'] = date_format($event['created_date'],'d/m/Y H:i');

                    /*handling featured image urls*/
                    if($event['have_featured_image'] == true){
                        $objMediaImage = $this->em->getRepository('AppBundle:MediaImage')->find($event['featured_image_id']);
                        $featured_image_extension = $objMediaImage->getExtension();
                        $objSharedFileFinderBussiness = new SharedFileFinderBussiness();
                        /*Simple List Mini-thumbnail*/
                        if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                            'filter_name' => 'list_featured_image_mini_thumbnail',
                            'image_name' => $event['featured_image_name'],
                            'image_extension' => $featured_image_extension,
                            'just_check' => true,
                            'just_web_filtered_url' => false
                        ))){
                            $eventsCollection[$key]['web_filtered_list_featured_image_mini_thumbnail_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                'filter_name' => 'list_featured_image_mini_thumbnail',
                                'image_name' => $event['featured_image_name'],
                                'image_extension' => $featured_image_extension,
                                'just_check' => false,
                                'just_web_filtered_url' => true
                            ));
                        }
                        else{
                            $eventsCollection[$key]['web_filtered_list_featured_image_mini_thumbnail_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($event['featured_image_url'], 'list_featured_image_mini_thumbnail');
                        }
                        /*Grid List thumbnail*/
                        if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                            'filter_name' => 'grid_featured_image_thumbnail',
                            'image_name' => $event['featured_image_name'],
                            'image_extension' => $featured_image_extension,
                            'just_check' => true,
                            'just_web_filtered_url' => false
                        ))){
                            $eventsCollection[$key]['web_filtered_grid_featured_image_thumbnail_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage(array(
                                'filter_name' => 'grid_featured_image_thumbnail',
                                'image_name' => $event['featured_image_name'],
                                'image_extension' => $featured_image_extension,
                                'just_check' => false,
                                'just_web_filtered_url' => true
                            ));
                        }
                        else{
                            $eventsCollection[$key]['web_filtered_grid_featured_image_thumbnail_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($event['featured_image_url'], 'grid_featured_image_thumbnail');
                        }
                    }

                    /*handling Url*/
                    if($this->container == null && isset($parametersCollection['container'])){
                        $this->container = $parametersCollection['container'];
                    }
                    if($this->container != null){
                        $siteDomain = $this->container->get('appbundle_site_settings')->getBncDomain();
                        $eventsCollection[$key]['url'] = $siteDomain.'/es/eventos/'.$event['url_slug_es'];
                       // print_r($eventsCollection[$key]['url']);die;
                    }

                    /*handling number of comments*/
                    $totalComments = 0;
                    $commentsCollection = $this->em->getRepository('AppBundle:Comment')->findBy(array(
                        'generic_post' => $objGenericPost
                    ));
                    if(isset($commentsCollection[0])){
                        $totalComments = count($commentsCollection);
                    }
                    $eventsCollection[$key]['total_comments'] = $totalComments;

                    /*handling data for Single Result*/
                    if(isset($parametersCollection['singleResult'])){
                        /*handling dates*/
                        if($event['modified_date'] != null){
                            $eventsCollection[$key]['modified_date'] = date_format($event['modified_date'],'d/m/Y H:i');
                        }
                        if($event['published_date'] != null){
                            $eventsCollection[$key]['published_date'] = date_format($event['published_date'],'d/m/Y H:i');
                        }
                        /*handling Categories*/
                        $genericPostsId = array();
                        $genericPostsId[0] = $event['id'];
                        $categoriesCollection = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPostTaxonomies(array(
                            'searchByGenericPost' => true,
                            'genericPostsId' => implode(',', $genericPostsId)
                        ));
                        $eventsCollection[$key]['categoriesCollection'] = $categoriesCollection;
                    }

                    /*handling data for Event Object*/
                    $objEvent = $this->em->getRepository('AppBundle:Event')->find($objGenericPost);
                    if(isset($objEvent)){
                        if($objEvent->getStartDate() != null){
                            $eventsCollection[$key]['start_date'] = date_format($objEvent->getStartDate(),'d/m/Y H:i');
                        }
                        if($objEvent->getEndDate() != null){
                            $eventsCollection[$key]['end_date'] = date_format($objEvent->getEndDate(),'d/m/Y H:i');
                        }
                        if($objEvent->getPlace() != null){
                            $eventsCollection[$key]['place_es'] = $objEvent->getPlace();
                        }
                    }
                }
            }

            if(isset($parametersCollection['singleResult']) && isset($eventsCollection[0])){
                return $eventsCollection[0];
            }
            return $eventsCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveEventData($parametersCollection){
        try{

            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGenericPostType = $this->em->getRepository('AppBundle:GenericPostType')->findOneBy(array(
                'tree_slug' => 'event'
            ));
            /*$objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->findOneBy(array(
                'title_es' => $parametersCollection['title_es'],
                'generic_post_type' => $objGenericPostType
            ));
            if(isset($objGenericPost)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objGenericPost->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe un evento con ese nombre.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
            }*/
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

                $objEvent = $this->em->getRepository('AppBundle:Event')->find($objGenericPost);
            }
            else{
                $objGenericPost->setCreatedAuthor($parametersCollection['loggedUser']);

                $objEvent = new Event();
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

            /*persisting Event Object*/
            $objEvent->setId($objGenericPost);
            $objEvent->setStartDate(new \DateTime($parametersCollection['start_date']));
            $objEvent->setEndDate(new \DateTime($parametersCollection['end_date']));
            if(isset($parametersCollection['place_es'])){
                $objEvent->setPlace($parametersCollection['place_es']);
            }
            $this->em->persist($objEvent);

            /*persisting relation Post Status - Event */
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

            /*Handling relation Taxonomy - Event*/
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

            return $this->returnResponse(array('success'=>1,'message'=>$message, 'eventId'=>$objGenericPost->getId()));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteEventsData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['eventsId'][0])) {
                $objCommentBussiness = new CommentsBussiness($this->em);
                $objCommentBussiness->updateCommentsPending(null, $parametersCollection['eventsId']);

                $idsCollection = implode(',',$parametersCollection['eventsId']);
                $this->em->getRepository('AppBundle:GenericPost')->deleteByIdsCollection($idsCollection);
            }
            else{
                $message = 'No existen eventos para eliminar.';
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
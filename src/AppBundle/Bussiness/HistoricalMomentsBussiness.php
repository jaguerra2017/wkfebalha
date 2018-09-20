<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\GenericPostTaxonomy;
use AppBundle\Entity\HistoricalMoment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Entity\GenericPost;
use AppBundle\Bussiness\NomenclatureBussiness;
use AppBundle\Entity\GenericPostNomenclature;



class HistoricalMomentsBussiness
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
            $initialsData['historicalMomentsDataCollection'] = $this->getHistoricalMomentsList($parametersCollection);

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
         }
    }

    public function getHistoricalMomentsList($parametersCollection)
    {
        try{
            $parametersCollection['post_type_tree_slug'] = 'historical-moment';
            if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                $parametersCollection['searchByIdsCollection'] = true;
                $idsCollection = array();
                $idsCollection[0] = $parametersCollection['historicalMomentId'];
                $idsCollection = implode(',', $idsCollection);
                $parametersCollection['idsCollection'] = $idsCollection;
                $parametersCollection['view'] = 'simple_list';
                return $this->getSimpleList($parametersCollection);
            }
            else if(!isset($parametersCollection['view']) || $parametersCollection['view'] == null ||
            $parametersCollection['view'] == 'timeline_list'){
                $historicalMomentYearsCollection = $this->em->getRepository('AppBundle:HistoricalMoment')->getHistoricalMomentYears($parametersCollection);
                if(isset($historicalMomentYearsCollection[0])){
                    foreach($historicalMomentYearsCollection as $key=>$year){
                        $parametersCollection['searchByYear'] = true;
                        $parametersCollection['year'] = $year['year'];
                        $parametersCollection['view'] = 'simple_list';
                        $historicalMomentYearsCollection[$key]['childrens'] = $this->getSimpleList($parametersCollection);
                    }
                }
                return $historicalMomentYearsCollection;
            }
            else{
                return $this->getSimpleList($parametersCollection);
            }
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getSimpleList($parametersCollection){
        $historicalMomentsCollection = $this->em->getRepository('AppBundle:HistoricalMoment')->getHistoricalMomentsFullData($parametersCollection);
        if(isset($historicalMomentsCollection[0])){
            foreach($historicalMomentsCollection as $key=>$historicalMoment){
                $canEdit = 1;
                $canDelete = 1;
                $historicalMomentsCollection[$key]['canEdit'] = $canEdit;
                $historicalMomentsCollection[$key]['canDelete'] = $canDelete;
                /*handling Post Status*/
                $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($historicalMoment['id']);
                $objPostStatus = $this->em->getRepository('AppBundle:GenericPostNomenclature')->findOneBy(array(
                    'generic_post' => $objGenericPost,
                    'relation_slug' => 'post_status'
                ));
                if(isset($objPostStatus)){
                    $historicalMomentsCollection[$key]['post_status_name'] = $objPostStatus->getNomenclature()->getName();
                    if(isset($parametersCollection['singleResult'])){
                        $historicalMomentsCollection[$key]['post_status_id'] = $objPostStatus->getNomenclature()->getId();
                    }
                }
                /*handling dates*/
                $historicalMomentsCollection[$key]['created_date'] = date_format($historicalMoment['created_date'],'d/m/Y H:i');

                /*handling data for Single Result*/
                if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                    /*handling dates*/
                    if($historicalMoment['modified_date'] != null){
                        $historicalMomentsCollection[$key]['modified_date'] = date_format($historicalMoment['modified_date'],'d/m/Y H:i');
                    }
                    if($historicalMoment['published_date'] != null){
                        $historicalMomentsCollection[$key]['published_date'] = date_format($historicalMoment['published_date'],'d/m/Y H:i');
                    }
                }
            }
        }

        if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true &&
        isset($historicalMomentsCollection[0])){
            return $historicalMomentsCollection[0];
        }
        return $historicalMomentsCollection;
    }

    public function saveHistoricalMomentData($parametersCollection){
        try{

            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGenericPostType = $this->em->getRepository('AppBundle:GenericPostType')->findOneBy(array(
                'tree_slug' => 'historical-moment'
            ));
            

            /*persisting Generic Post Object*/
            $objGenericPost = new GenericPost();
            if($parametersCollection['isCreating'] == false){

                $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['id']);
                if(!isset($objGenericPost)){
                    $message = 'El hito histórico que desea editar ya no existe.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
                $objGenericPost->setModifiedDate(new \DateTime());
                $objGenericPost->setModifiedAuthor($parametersCollection['loggedUser']);

                $objHistoricalMoment = $this->em->getRepository('AppBundle:HistoricalMoment')->find($objGenericPost);
            }
            else{
                $objGenericPost->setCreatedAuthor($parametersCollection['loggedUser']);

                $objHistoricalMoment = new HistoricalMoment();
            }
            $objGenericPost->setGenericPostType($objGenericPostType);
            $objGenericPost->setContent($parametersCollection['content_es']);
            $this->em->persist($objGenericPost);
            $this->em->flush($objGenericPost);

            /*handling Historical Moment object*/
            $objHistoricalMoment->setId($objGenericPost);
            $objHistoricalMoment->setYear($parametersCollection['year']);
            $this->em->persist($objHistoricalMoment);

            /*persisting relation Post Status - HistoricalMoment */
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

            $this->em->flush();

            return $this->returnResponse(array('success'=>1,'message'=>$message, 'historicalMomentId'=>$objGenericPost->getId()));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteHistoricalMomentsData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['historicalMomentsId'][0])) {
                $objCommentBussiness = new CommentsBussiness($this->em);
                $objCommentBussiness->updateCommentsPending(null, $parametersCollection['historicalMomentsId']);

                $idsCollection = implode(',',$parametersCollection['historicalMomentsId']);
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
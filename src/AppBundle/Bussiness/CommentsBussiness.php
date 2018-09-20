<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Entity\GenericPost;
use AppBundle\Bussiness\NomenclatureBussiness;
use AppBundle\Entity\GenericPostNomenclature;
use AppBundle\Entity\Comment;
use AppBundle\Entity\GenericPostTaxonomy;



class CommentsBussiness
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
            $initialsData['commentsDataCollection'] = $this->getCommentsList($parametersCollection);

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
         }
    }

    public function getCommentsList($parametersCollection)
    {
        try{
            if(isset($parametersCollection['getOnlyPendings']) && $parametersCollection['getOnlyPendings'] == true){
                $objCommentStatusPending = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array(
                   'tree_slug' => 'comment-status-pending'
                ));
                if(isset($objCommentStatusPending)){
                    $parametersCollection['commentStatusPendingId'] = $objCommentStatusPending->getId();
                }
            }
            else if(isset($parametersCollection['getOnlyApproved']) && $parametersCollection['getOnlyApproved'] == true){
                $objCommentStatusApproved = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array(
                    'tree_slug' => 'comment-status-approved'
                ));
                if(isset($objCommentStatusApproved)){
                    $parametersCollection['commentStatusApprovedId'] = $objCommentStatusApproved->getId();
                }
            }
            $commentsCollection = $this->em->getRepository('AppBundle:Comment')->getComments($parametersCollection);

            if(isset($commentsCollection[0])){
                foreach($commentsCollection as $key=>$comment){
                    $canEdit = 1;
                    $canDelete = 1;
                    if($comment['is_auto_comment'] != true){
                        $canEdit = 0;
                    }
                    $commentsCollection[$key]['canEdit'] = $canEdit;
                    $commentsCollection[$key]['canDelete'] = $canDelete;
                    $commentsCollection[$key]['created_date'] = date_format($comment['created_date'],'d/m/Y H:i');
                    if($comment['modified_date'] != null){
                        $commentsCollection[$key]['modified_date'] = date_format($comment['modified_date'],'d/m/Y H:i');
                    }
                    if($comment['published_date'] != null){
                        $commentsCollection[$key]['published_date'] = date_format($comment['published_date'],'d/m/Y H:i');
                    }

                    if($parametersCollection['treeView'] == true){
                        $parametersCollection['searchByParent'] = true;
                        $parametersCollection['parentId'] = $comment['id'];
                        $commentsCollection[$key]['childrens'] = $this->getCommentsList($parametersCollection);
                    }

                }
            }

            return $commentsCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveCommentData($parametersCollection){
        try{

            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['generic_post_id']);
            if(!isset($objGenericPost)){
                $message = 'El elemento al cual desea agregarle / editarle el comentario ya no existe.';
                return $this->returnResponse(array('success'=>0, 'message'=>$message));
            }
            /*persisting Comment Object*/
            $objComment = new Comment();
            if($parametersCollection['isCreating'] == false){

                $objComment = $this->em->getRepository('AppBundle:Comment')->find($parametersCollection['id']);
                if(!isset($objComment)){
                    $message = 'El comentario que desea editar ya no existe.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
                $objComment->setModifiedDate(new \DateTime());
                $objComment->setModifiedAuthor($parametersCollection['loggedUser']);
                $originalCommentStatus = $objComment->getStatus()->getTreeSlug();
            }
            else{
                if($parametersCollection['anonymous'] == true){
                    $objComment->setCreatedAuthor($parametersCollection['loggedUser']);
                }
                $originalCommentStatus = null;
            }

            if(!$parametersCollection['isAutoComent'] || $parametersCollection['anonymous'] == true){
                $authorName = $parametersCollection['author_name'];
                $objComment->setEmail($parametersCollection['email']);
            }
            else{
                $authorName = $parametersCollection['loggedUser']->getFullName().' (WebMaster)';
            }
            $objComment->setIsAutoComment($parametersCollection['isAutoComent']);
            $objComment->setAuthorName($authorName);
            $objComment->setContent($parametersCollection['content']);
            $objComment->setGenericPost($objGenericPost);
            if(isset($parametersCollection['parent_id']) && $parametersCollection['parent_id'] != null){
                $objParent = $this->em->getRepository('AppBundle:Comment')->find($parametersCollection['parent_id']);
                if(isset($objParent)){
                    $objComment->setParent($objParent);
                    $objComment->setDepth($objParent->getDepth() + 1);
                }
            }

            if(!isset($parametersCollection['comment_status_id'])){
                $objCommentPendingStatus = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array(
                    'tree_slug' => 'comment-status-pending'
                ));
                if(isset($objCommentPendingStatus)){
                    $parametersCollection['comment_status_id'] = $objCommentPendingStatus->getId();
                }
            }
            $objCommentStatus = $this->em->getRepository('AppBundle:Nomenclature')->find($parametersCollection['comment_status_id']);
            if(isset($objCommentStatus)){
                $objComment->setStatus($objCommentStatus);
                $publishedDate = null;
                if ($objCommentStatus->getTreeSlug() == 'comment-status-approved') {
                    $publishedDate = new \DateTime();
                    if (isset($parametersCollection['published_date']) &&
                    $parametersCollection['published_date'] != null) {

                        $publishedDateCollection = explode('/', $parametersCollection['published_date']);
                        if (isset($publishedDateCollection[2])){
                            $publishedDate = new \DateTime($publishedDateCollection[1] . '/' . $publishedDateCollection[0] . '/' . $publishedDateCollection[2]);
                        }
                    }
                    else{
                        $publishedDate = new \DateTime();
                    }
                }
                $objComment->setPublishedDate($publishedDate);
            }
            $this->em->persist($objComment);

            $this->em->flush();

            if($originalCommentStatus != $objCommentStatus->getTreeSlug() &&
            (($parametersCollection['isCreating'] == false) ||
             ($parametersCollection['isCreating'] == true && $objCommentStatus->getTreeSlug() == 'comment-status-pending') )){
                $this->updateCommentsPending($objCommentStatus->getTreeSlug());
            }

            return $this->returnResponse(array('success'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function changeCommentStatus($parametersCollection){
        try{

            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['generic_post_id']);
            if(!isset($objGenericPost)){
                $message = 'El elemento al cual desea editarle el comentario ya no existe.';
                return $this->returnResponse(array('success'=>0, 'message'=>$message));
            }
            /*persisting Comment Object*/
            $objComment = $this->em->getRepository('AppBundle:Comment')->find($parametersCollection['id']);
            if(!isset($objComment)){
                $message = 'El comentario que desea editar ya no existe.';
                return $this->returnResponse(array('success'=>0,'message'=>$message));
            }
            $originalCommentStatus = $objComment->getStatus()->getTreeSlug();
            $objComment->setModifiedDate(new \DateTime());
            $objComment->setModifiedAuthor($parametersCollection['loggedUser']);
            $objCommentStatus = $this->em->getRepository('AppBundle:Nomenclature')->find($parametersCollection['comment_status_id']);
            if(isset($objCommentStatus)){
                $objComment->setStatus($objCommentStatus);
                $publishedDate = null;
                if ($objCommentStatus->getTreeSlug() == 'comment-status-approved') {
                    $publishedDate = new \DateTime();
                    if (isset($parametersCollection['published_date']) &&
                        $parametersCollection['published_date'] != null) {

                        $publishedDateCollection = explode('/', $parametersCollection['published_date']);
                        if (isset($publishedDateCollection[2])){
                            $publishedDate = new \DateTime($publishedDateCollection[1] . '/' . $publishedDateCollection[0] . '/' . $publishedDateCollection[2]);
                        }
                    }
                    else{
                        $publishedDate = new \DateTime();
                    }
                }
                $objComment->setPublishedDate($publishedDate);
            }
            $this->em->persist($objComment);
            $this->em->flush();

            if($originalCommentStatus != $objCommentStatus->getTreeSlug()){
                $this->updateCommentsPending($objCommentStatus->getTreeSlug());
            }


            return $this->returnResponse(array('success'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteCommentsData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['commentsId'][0])) {
                $idsCollection = implode(',',$parametersCollection['commentsId']);
                $this->em->getRepository('AppBundle:Comment')->deleteByIdsCollection($idsCollection);
            }
            else{
                $message = 'No existen commentes para eliminar.';
                return $this->returnResponse(array('sucsess'=>0,'message'=>$message));
            }


            return $this->returnResponse(array('sucsess'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }




    public function checkCommentsPending($parametersCollection){
        try{

            $parametersCollection = array();
            $parametersCollection['decode_from_json'] = true;
            $parametersCollection['listener'] = 'comments';
            $objFileFinderBussiness = new SharedFileFinderBussiness();
            $commentListenersJson = $objFileFinderBussiness->getListenersFile($parametersCollection);
            $commentsPending = $commentListenersJson['pending'];

            return $this->returnResponse(array('comments_pending'=>$commentsPending));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function updateCommentsPending($comment_tree_slug, $genericPostIdsCollection = null){

        $parametersCollection = array();
        $parametersCollection['decode_from_json'] = true;
        $parametersCollection['listener'] = 'comments';
        $objFileFinderBussiness = new SharedFileFinderBussiness();
        $commentListenersJson = $objFileFinderBussiness->getListenersFile($parametersCollection);
        $commentsPending = $commentListenersJson['pending'];

        /*when a comment is created, updated or their status was changed*/

        if($genericPostIdsCollection == null){
            if($comment_tree_slug == 'comment-status-approved'){
                $commentsPending--;
            }
            else if($comment_tree_slug == 'comment-status-pending'){
                $commentsPending++;
            }
        }
        else{/*when a Generic Post are deleted */
            if(isset($genericPostIdsCollection[0])){
                $genericPostCurrentPendings = 0;
                $objCommentStatusPending = $this->em->getRepository('AppBundle:Nomenclature')->findBy(array(
                    'tree_slug' => 'comment-status-pending'
                ));
                foreach($genericPostIdsCollection as $key=>$genericPostId){
                    $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($genericPostId);
                    if(isset($objGenericPost)){
                        $commentsCollection = $this->em->getRepository('AppBundle:Comment')->findBy(array(
                            'generic_post' => $objGenericPost,
                            'status' => $objCommentStatusPending
                        ));
                        if(isset($commentsCollection[0])){
                            $genericPostCurrentPendings = $genericPostCurrentPendings + (count($commentsCollection));
                        }
                    }
                }

                $commentsPending = ($commentsPending - $genericPostCurrentPendings);
            }
        }

        $commentListenersJson['pending'] = $commentsPending;
        $parametersCollection['listenerData'] = $commentListenersJson;
        $objFileFinderBussiness->writeListenersFile($parametersCollection);
    }

    public function returnResponse($parametersCollection){
        return $parametersCollection;
    }

}
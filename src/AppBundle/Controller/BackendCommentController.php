<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


use AppBundle\Bussiness\CommentsBussiness;
use Symfony\Component\Validator\Constraints\Date;


/**
 * BACKEND - Comments controller.
 *
 * @Route("backend/comentarios")
 */
class BackendCommentController extends Controller
{

    /**
     * Return the Comments View
     *
     * @Route("/", name="comments_index")
     * @Security("is_granted('read', 'comments')")
     * @Method("GET")
     */
    public function commentsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Comment/index.html.twig');
        }

    }

    /**
     * Return the Comments View
     *
     * @Route("/pendientes", name="comments_pending")
     * @Security("is_granted('read', 'comments')")
     * @Method("GET")
     */
    public function commentsPendingViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->redirectToRoute('comments_index');
        }

    }

    /**
     * Load initials data for Comments view
     *
     * @Route("/datos-iniciales", name="comments_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'comments')")
     * @Method("POST")
     */
    public function loadCommentsInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');
            $parametersCollection['getOnlyPendings'] = true;
            $parametersCollection['treeView'] = false;


            $commentsBussinessObj = new CommentsBussiness($em);
            $initialsData = $commentsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'comment-status';
            $initialsData['commentStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showCommentsForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showCommentsForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showCommentsForm'] = $showCommentsForm;

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'comment-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load comments collection data
     *
     * @Route("/datos-comentarios", name="comments_data", options={"expose"=true})
     * @Security("is_granted('read', 'comments')")
     * @Method("POST")
     */
    public function loadCommentsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['getOnlyPendings'] = $request->get('getOnlyPendings');
            if(isset($parametersCollection['getOnlyPendings']) && $parametersCollection['getOnlyPendings'] == 'true'){
                $parametersCollection['getOnlyPendings'] = true;
            }
            else{
                $parametersCollection['getOnlyPendings'] = false;
            }
            $parametersCollection['genericPostId'] = $request->get('genericPostId');
            $parametersCollection['treeView'] = $request->get('treeView');
            if(isset($parametersCollection['treeView']) && $parametersCollection['treeView'] == 'true'){
                $parametersCollection['treeView'] = true;
            }
            else{
                $parametersCollection['treeView'] = false;
            }

            $parametersCollection['filterDate'] = $request->get('filterDate');
            if(isset($parametersCollection['filterDate']) && $parametersCollection['filterDate'] != null){
                $filterDateArray = explode('/',$parametersCollection['filterDate']);
                $filterDate = $filterDateArray[2].'/'.$filterDateArray[1].'/'.$filterDateArray[0];
                $parametersCollection['filterDate'] = $filterDate;
            }
            $loadCommentStatus = $request->get('loadCommentStatus');

            $em = $this->getDoctrine()->getManager();
            $commentsBussinessObj = new CommentsBussiness($em);
            $commentsDataCollection = $commentsBussinessObj->getCommentsList($parametersCollection);

            $commentStatusDataCollection = array();
            if($loadCommentStatus != null && $loadCommentStatus == 'true'){
                $parametersCollection = new \stdClass();
                $parametersCollection->filterByTreeSlug = true;
                $parametersCollection->treeSlug = 'comment-status';
                $commentStatusDataCollection = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            }
            return new JsonResponse(array(
                'commentsDataCollection' => $commentsDataCollection,
                'commentStatusDataCollection' => $commentStatusDataCollection));
        }
    }

    /**
     * Save Comment data (CREATE action)
     *
     * @Route("/crear", name="comments_create", options={"expose"=true})
     * @Security("is_granted('create', 'comments')")
     * @Method("POST")
     */
    public function createCommentAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('commentData');
            $parametersCollection['isAutoComent'] = true;
            //print_r($parametersCollection);die;
            $parametersCollection['isCreating'] = true;
            $parametersCollection['loggedUser'] = $this->getUser();
            if($parametersCollection['anonymous'] == 'true'){
                $parametersCollection['anonymous'] = true;
            }
            else{
                $parametersCollection['anonymous'] = false;
            }

            $commentsBussinessObj = new CommentsBussiness($em);
            $response = $commentsBussinessObj->saveCommentData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Comment data (EDIT action)
     *
     * @Route("/editar", name="comments_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'comments')")
     * @Method("POST")
     */
    public function editCommentAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('commentData');
            $parametersCollection['isAutoComent'] = true;
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();
            if($parametersCollection['anonymous'] == 'true'){
                $parametersCollection['anonymous'] = true;
            }
            else{
                $parametersCollection['anonymous'] = false;
            }

            $commentsBussinessObj = new CommentsBussiness($em);
            $response = $commentsBussinessObj->saveCommentData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Change Comment Status
     *
     * @Route("/cambiar-status", name="comments_change_status", options={"expose"=true})
     * @Security("is_granted('edit', 'comments')")
     * @Method("POST")
     */
    public function changeCommentStatusAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('commentData');
            $parametersCollection['loggedUser'] = $this->getUser();

            $commentsBussinessObj = new CommentsBussiness($em);
            $response = $commentsBussinessObj->changeCommentStatus($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Comments
     *
     * @Route("/eliminar-publicacion", name="comments_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'comments')")
     * @Method("POST")
     */
    public function deleteCommentsAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['commentsId'] = $request->get('commentsId');

            $commentsBussinessObj = new CommentsBussiness($em);
            $response = $commentsBussinessObj->deleteCommentsData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Check Comments pending
     *
     * @Route("/chequear-pendientes", name="comments_check_pending", options={"expose"=true})
     * @Security("is_granted('read', 'comments')")
     * @Method("POST")
     */
    public function checkCommentsPendingAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();

            $commentsBussinessObj = new CommentsBussiness($em);
            $response = $commentsBussinessObj->checkCommentsPending($parametersCollection);

            $isSiteStatusOnline = $this->container->get('appbundle_site_settings')->isSiteStatusOnline();
            $notificationConfig = $this->container->get('appbundle_site_settings')->getSectionSettingsData('notification');

            $response['isSiteStatusOnline'] = $isSiteStatusOnline;
            $response['notificationConfig'] = $notificationConfig;

            return new JsonResponse($response);
        }
    }

}
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
 * FRONTEND - Comments controller.
 *
 * @Route("data-handler/comentarios")
 */
class FrontendCommentController extends Controller
{

    /**
     * Load initials data for Comments view
     *
     * @Route("/datos-iniciales", name="dh_comments_view_initials_data", options={"expose"=true})
     * @Method("POST")
     */
    public function loadCommentsInitialsDataAction(Request $request)
    {
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

    /**
     * Load comments collection data
     *
     * @Route("/datos-comentarios", name="dh_comments_data", options={"expose"=true})
     * @Method("POST")
     */
    public function loadCommentsDataAction(Request $request)
    {
        $parametersCollection = array();
        //$parametersCollection['getOnlyPendings'] = false;

        $parametersCollection['getOnlyApproved'] = true;

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

    /**
     * Save Comment data (CREATE action)
     *
     * @Route("/crear", name="dh_comments_create", options={"expose"=true})
     * @Method("POST")
     */
    public function createCommentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $parametersCollection = $request->get('commentData');
        $parametersCollection['isAutoComent'] = false;
        $parametersCollection['isCreating'] = true;
        $parametersCollection['loggedUser'] = $this->getUser();
        $parametersCollection['anonymous'] = false;

        $commentsBussinessObj = new CommentsBussiness($em);
        $response = $commentsBussinessObj->saveCommentData($parametersCollection);
        return new JsonResponse($response);
    }

}
<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\OpinionsBussiness;


/**
 * BACKEND - Opinions controller.
 *
 * @Route("backend/opiniones")
 */
class BackendOpinionController extends Controller
{

    /**
     * Return the Opinions View
     *
     * @Route("/", name="opinions_index")
     * @Security("is_granted('read', 'opinions')")
     * @Method("GET")
     */
    public function opinionsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Opinion/index.html.twig');
        }

    }

    /**
     * Load initials data for Opinions view
     *
     * @Route("/datos-iniciales", name="opinions_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'opinions')")
     * @Method("POST")
     */
    public function loadOpinionsInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $opinionsBussinessObj = new OpinionsBussiness($em);
            $initialsData = $opinionsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showOpinionsForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showOpinionsForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showOpinionsForm'] = $showOpinionsForm;

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'opinion-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load opinions collection data
     *
     * @Route("/datos-opiniones", name="opinions_data", options={"expose"=true})
     * @Security("is_granted('read', 'opinions')")
     * @Method("POST")
     */
    public function loadOpinionsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['opinionId'] = $request->get('opinionId');
            $parametersCollection['searchByTaxonomy'] = $request->get('searchByTaxonomies');
            if(isset($parametersCollection['searchByTaxonomy']) && $parametersCollection['searchByTaxonomy'] == 'true'){
                $parametersCollection['searchByTaxonomy'] = true;
                $parametersCollection['taxonomyIds'] = $request->get('taxonomieIdsCollection');
            }

            $em = $this->getDoctrine()->getManager();
            $opinionsBussinessObj = new OpinionsBussiness($em);
            $opinionsDataCollection = $opinionsBussinessObj->getOpinionsList($parametersCollection);
            return new JsonResponse(array('opinionsDataCollection' => $opinionsDataCollection));
        }
    }

    /**
     * Save Opinion data (CREATE action)
     *
     * @Route("/crear", name="opinions_create", options={"expose"=true})
     * @Security("is_granted('create', 'opinions')")
     *
     */
    public function createOpinionAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('opinionData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $opinionsBussinessObj = new OpinionsBussiness($em);
                $response = $opinionsBussinessObj->saveOpinionData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('opinions_index');
            }
        }
    }

    /**
     * Save Opinion data (EDIT action)
     *
     * @Route("/editar", name="opinions_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'opinions')")
     * @Method("POST")
     */
    public function editOpinionAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('opinionData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $opinionsBussinessObj = new OpinionsBussiness($em);
            $response = $opinionsBussinessObj->saveOpinionData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Opinions
     *
     * @Route("/eliminar-publicacion", name="opinions_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'opinions')")
     * @Method("POST")
     */
    public function deleteOpinionsAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['opinionsId'] = $request->get('opinionsId');

            $opinionsBussinessObj = new OpinionsBussiness($em);
            $response = $opinionsBussinessObj->deleteOpinionsData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
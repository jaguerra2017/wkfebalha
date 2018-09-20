<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\CompositionBussiness;


/**
 * BACKEND - Compositions controller.
 *
 * @Route("backend/composicion")
 */
class BackendCompositionController extends Controller
{

    /**
     * Return the Compositions View
     *
     * @Route("/", name="composition_index")
     * @Security("is_granted('read', 'composition')")
     * @Method("GET")
     */
    public function compositionsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Composition/index.html.twig');
        }

    }

    /**
     * Load initials data for Composition view
     *
     * @Route("/datos-iniciales", name="composition_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'composition')")
     * @Method("POST")
     */
    public function loadCompositionInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $compositionBussinessObj = new CompositionBussiness($em);
            $initialsData = $compositionBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showCompositionForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showCompositionForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showCompositionForm'] = $showCompositionForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'composition-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load composition collection data
     *
     * @Route("/datos-composicion-bnc", name="composition_data", options={"expose"=true})
     * @Security("is_granted('read', 'composition')")
     * @Method("POST")
     */
    public function loadCompositionDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['compositionId'] = $request->get('compositionId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $em = $this->getDoctrine()->getManager();
            $compositionBussinessObj = new CompositionBussiness($em);
            $compositionDataCollection = $compositionBussinessObj->getCompositionList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('compositionData' => $compositionDataCollection));
            }
            return new JsonResponse(array('compositionDataCollection' => $compositionDataCollection));
        }
    }

    /**
     * Save Composition data (CREATE action)
     *
     * @Route("/crear", name="composition_create", options={"expose"=true})
     * @Security("is_granted('create', 'composition')")
     *
     */
    public function createCompositionAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('compositionData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $compositionBussinessObj = new CompositionBussiness($em);
                $response = $compositionBussinessObj->saveCompositionData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('composition_index');
            }
        }
    }

    /**
     * Save Composition data (EDIT action)
     *
     * @Route("/editar", name="composition_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'composition')")
     * @Method("POST")
     */
    public function editCompositionAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('compositionData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $compositionBussinessObj = new CompositionBussiness($em);
            $response = $compositionBussinessObj->saveCompositionData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Composition
     *
     * @Route("/eliminar-composicion", name="composition_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'composition')")
     * @Method("POST")
     */
    public function deleteCompositionAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['compositionId'] = $request->get('compositionId');

            $compositionBussinessObj = new CompositionBussiness($em);
            $response = $compositionBussinessObj->deleteCompositionData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
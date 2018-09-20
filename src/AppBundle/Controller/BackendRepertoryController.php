<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\RepertoryBussiness;


/**
 * BACKEND - Repertory controller.
 *
 * @Route("backend/repertorio")
 */
class BackendRepertoryController extends Controller
{

    /**
     * Return the Repertory View
     *
     * @Route("/", name="repertory_index")
     * @Security("is_granted('read', 'repertory')")
     * @Method("GET")
     */
    public function repertoryViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Repertory/index.html.twig');
        }

    }

    /**
     * Load initials data for Repertory view
     *
     * @Route("/datos-iniciales", name="repertory_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'repertory')")
     * @Method("POST")
     */
    public function loadRepertoryInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $repertoryBussinessObj = new RepertoryBussiness($em);
            $initialsData = $repertoryBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showRepertoryForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showRepertoryForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showRepertoryForm'] = $showRepertoryForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'repertory-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load repertory collection data
     *
     * @Route("/datos-repertorio", name="repertory_data", options={"expose"=true})
     * @Security("is_granted('read', 'repertory')")
     * @Method("POST")
     */
    public function loadRepertoryDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['repertoryId'] = $request->get('repertoryId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $em = $this->getDoctrine()->getManager();
            $repertoryBussinessObj = new RepertoryBussiness($em);
            $repertoryDataCollection = $repertoryBussinessObj->getRepertoryList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('repertoryData' => $repertoryDataCollection));
            }
            return new JsonResponse(array('repertoryDataCollection' => $repertoryDataCollection));
        }
    }

    /**
     * Save Repertory data (CREATE action)
     *
     * @Route("/crear", name="repertory_create", options={"expose"=true})
     * @Security("is_granted('create', 'repertory')")
     *
     */
    public function createRepertoryAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('repertoryData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $repertoryBussinessObj = new RepertoryBussiness($em);
                $response = $repertoryBussinessObj->saveRepertoryData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('repertory_index');
            }
        }
    }

    /**
     * Save Repertory data (EDIT action)
     *
     * @Route("/editar", name="repertory_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'repertory')")
     * @Method("POST")
     */
    public function editRepertoryAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('repertoryData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $repertoryBussinessObj = new RepertoryBussiness($em);
            $response = $repertoryBussinessObj->saveRepertoryData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Repertory
     *
     * @Route("/eliminar-repertorio", name="repertory_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'repertory')")
     * @Method("POST")
     */
    public function deleteRepertoryAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['repertoryId'] = $request->get('repertoryId');

            $repertoryBussinessObj = new RepertoryBussiness($em);
            $response = $repertoryBussinessObj->deleteRepertoryData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
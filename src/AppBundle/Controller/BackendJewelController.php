<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\JewelsBussiness;


/**
 * BACKEND - Jewels controller.
 *
 * @Route("backend/joyas")
 */
class BackendJewelController extends Controller
{

    /**
     * Return the Jewels View
     *
     * @Route("/", name="jewels_index")
     * @Security("is_granted('read', 'jewels')")
     * @Method("GET")
     */
    public function jewelsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Jewel/index.html.twig');
        }

    }

    /**
     * Load initials data for Jewels view
     *
     * @Route("/datos-iniciales", name="jewels_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'jewels')")
     * @Method("POST")
     */
    public function loadJewelsInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $jewelsBussinessObj = new JewelsBussiness($em);
            $initialsData = $jewelsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showJewelsForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showJewelsForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showJewelsForm'] = $showJewelsForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'jewel-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load jewels collection data
     *
     * @Route("/datos-joyas", name="jewels_data", options={"expose"=true})
     * @Security("is_granted('read', 'jewels')")
     * @Method("POST")
     */
    public function loadJewelsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['jewelId'] = $request->get('jewelId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $em = $this->getDoctrine()->getManager();
            $jewelsBussinessObj = new JewelsBussiness($em);
            $jewelsDataCollection = $jewelsBussinessObj->getJewelsList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('jewelData' => $jewelsDataCollection));
            }
            return new JsonResponse(array('jewelsDataCollection' => $jewelsDataCollection));
        }
    }

    /**
     * Save Jewel data (CREATE action)
     *
     * @Route("/crear", name="jewels_create", options={"expose"=true})
     * @Security("is_granted('create', 'jewels')")
     *
     */
    public function createJewelAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('jewelData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $jewelsBussinessObj = new JewelsBussiness($em);
                $response = $jewelsBussinessObj->saveJewelData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('jewels_index');
            }
        }
    }

    /**
     * Save Jewel data (EDIT action)
     *
     * @Route("/editar", name="jewels_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'jewels')")
     * @Method("POST")
     */
    public function editJewelAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('jewelData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $jewelsBussinessObj = new JewelsBussiness($em);
            $response = $jewelsBussinessObj->saveJewelData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Jewels
     *
     * @Route("/eliminar-joya", name="jewels_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'jewels')")
     * @Method("POST")
     */
    public function deleteJewelsAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['jewelsId'] = $request->get('jewelsId');

            $jewelsBussinessObj = new JewelsBussiness($em);
            $response = $jewelsBussinessObj->deleteJewelsData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
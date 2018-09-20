<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\TaxonomyBussiness;


/**
 * BACKEND - Taxonomies controller.
 *
 * @Route("backend/taxonomias")
 */
class BackendTaxonomyController extends Controller
{

    /**
     * Return the Taxonomy View
     *
     * @Route("/", name="taxonomies_index")
     * @Security("is_granted('read', 'taxonomy')")
     * @Method("GET")
     */
    public function taxonomiesViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Taxonomy/index.html.twig');
        }

    }

    /**
     * Load initials data for Taxonomy view
     *
     * @Route("/datos-iniciales", name="taxonomies_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'taxonomy')")
     * @Method("POST")
     */
    public function loadTaxonomiesInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $taxonomyBussinessObj = new TaxonomyBussiness($em);
            $initialsData = $taxonomyBussinessObj->loadInitialsData();
            $showTaxonomyForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showTaxonomyForm = true;
                $request->getSession()->set('directAccessToCreate', false);
            }
            $initialsData['showTaxonomyForm'] = $showTaxonomyForm;

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load taxonomies collection data
     *
     * @Route("/datos-taxonomias", name="taxonomies_data", options={"expose"=true})
     * @Security("is_granted('read', 'taxonomy')")
     * @Method("POST")
     */
    public function loadTaxonomiesDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['taxonomyTypeTreeSlug'] = $request->get('taxonomyTypeTreeSlug');
            $parametersCollection['returnDataInTree'] = $request->get('returnDataInTree');
            if($parametersCollection['returnDataInTree'] == 'true'){
                $parametersCollection['searchByParent'] = true;
                $parametersCollection['parentId'] = null;
            }

            $em = $this->getDoctrine()->getManager();
            $taxonomyBussinessObj = new TaxonomyBussiness($em);
            $taxonomiesDataCollection = $taxonomyBussinessObj->getTaxonomiesList($parametersCollection);
            //print_r($taxonomiesDataCollection);die;
            return new JsonResponse(array('taxonomiesDataCollection' => $taxonomiesDataCollection));
        }
    }

    /**
     * Save taxonomy data (CREATE action)
     *
     * @Route("/crear", name="taxonomies_create", options={"expose"=true})
     * @Security("is_granted('create', 'taxonomy')")
     *
     */
    public function createTaxonomyAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('taxonomyData');
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $taxonomyBussinessObj = new TaxonomyBussiness($em);
                $response = $taxonomyBussinessObj->saveTaxonomyData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('taxonomies_index');
            }
        }
    }

    /**
     * Save taxonomy data (EDIT action)
     *
     * @Route("/editar", name="taxonomies_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'taxonomy')")
     * @Method("POST")
     */
    public function editTaxonomyAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('taxonomyData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $taxonomyBussinessObj = new TaxonomyBussiness($em);
            $response = $taxonomyBussinessObj->saveTaxonomyData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete taxonomies
     *
     * @Route("/eliminar", name="taxonomies_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'taxonomy')")
     * @Method("POST")
     */
    public function deleteTaxonomiesAction(Request $request){

        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['taxonomiesId'] = $request->get('taxonomiesId');

            $taxonomyBussinessObj = new TaxonomyBussiness($em);
            $response = $taxonomyBussinessObj->deleteTaxonomies($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
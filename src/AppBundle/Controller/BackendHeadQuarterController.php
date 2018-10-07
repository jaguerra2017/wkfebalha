<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\HeadQuarterBussiness;


/**
 * BACKEND - HeadQuarter controller.
 *
 * @Route("backend/sedes")
 */
class BackendHeadQuarterController extends Controller
{

    /**
     * Return the HeadQuarter View
     *
     * @Route("/", name="headquarters_index")
     * @Security("is_granted('read', 'headquarter')")
     * @Method("GET")
     */
    public function headquartersViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/HeadQuarter/index.html.twig',
              array('languages'=> $this->container->getParameter('app.avaiableLanguajes'))
            );
        }

    }

    /**
     * Load initials data for HeadQuarter view
     *
     * @Route("/datos-iniciales", name="headquarters_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'headquarter')")
     * @Method("POST")
     */
    public function loadHeadQuarterInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');
            $parametersCollection['currentLanguage'] = $request->get('currentLanguage') ? $request->get('currentLanguage') : $this->container->getParameter('app.default_locale');

            $headquartersBussinessObj = new HeadQuarterBussiness($em);
            $initialsData = $headquartersBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showHeadQuarterForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showHeadQuarterForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showHeadQuarterForm'] = $showHeadQuarterForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();
            $initialsData['languages'] = $this->container->getParameter('app.avaiableLanguajes');

//            $parametersCollection = array();
//            $parametersCollection['taxonomyTypeTreeSlug'] = 'headquarter-category';
//            $parametersCollection['returnDataInTree'] = true;
//            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load headquarters collection data
     *
     * @Route("/datos-sedes", name="headquarters_data", options={"expose"=true})
     * @Security("is_granted('read', 'headquarter')")
     * @Method("POST")
     */
    public function loadHeadQuarterDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['currentLanguage'] = $request->get('currentLanguage') ? $request->get('currentLanguage') : $this->container->getParameter('app.default_locale');
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['headquarterId'] = $request->get('headquarterId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $em = $this->getDoctrine()->getManager();
            $headquartersBussinessObj = new HeadQuarterBussiness($em);
            $headquartersDataCollection = $headquartersBussinessObj->getHeadQuartersList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('headquarterData' => $headquartersDataCollection));
            }
            return new JsonResponse(array('headquartersDataCollection' => $headquartersDataCollection));
        }
    }

    /**
     * Save HeadQuarter data (CREATE action)
     *
     * @Route("/crear", name="headquarters_create", options={"expose"=true})
     * @Security("is_granted('create', 'headquarter')")
     *
     */
    public function createHeadQuarterAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('headquarterData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
                $parametersCollection['loggedUser'] = $this->getUser();

                $headquartersBussinessObj = new HeadQuarterBussiness($em);
                $response = $headquartersBussinessObj->saveHeadQuarterData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('headquarters_index');
            }
        }
    }

    /**
     * Save HeadQuarter data (EDIT action)
     *
     * @Route("/editar", name="headquarters_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'headquarter')")
     * @Method("POST")
     */
    public function editHeadQuarterAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('headquarterData');
            $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $headquartersBussinessObj = new HeadQuarterBussiness($em);
            $response = $headquartersBussinessObj->saveHeadQuarterData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete HeadQuarter
     *
     * @Route("/eliminar-sede", name="headquarters_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'headquarter')")
     * @Method("POST")
     */
    public function deleteHeadQuarterAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['headquartersId'] = $request->get('headquartersId');
            $parametersCollection['currentLanguage'] = $request->get('currentLanguage') ? $request->get('currentLanguage') : $this->container->getParameter('app.default_locale');

            $headquartersBussinessObj = new HeadQuarterBussiness($em);
            $response = $headquartersBussinessObj->deleteHeadQuarterData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
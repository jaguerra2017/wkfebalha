<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\CollateralActivitiesBussiness;


/**
 * BACKEND - CollateralActivities controller.
 *
 * @Route("backend/actividades_colateraless")
 */
class BackendCollateralActivitiesController extends Controller
{

    /**
     * Return the CollateralActivities View
     *
     * @Route("/", name="collateralactivities_index")
     * @Security("is_granted('read', 'collateralactivities')")
     * @Method("GET")
     */
    public function collateralactivitiessViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/CollateralActivities/index.html.twig',
              array('languages'=> $this->container->getParameter('app.avaiableLanguajes'))
            );
        }

    }

    /**
     * Load initials data for CollateralActivities view
     *
     * @Route("/datos-iniciales", name="collateralactivitiess_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'collateralactivities')")
     * @Method("POST")
     */
    public function loadCollateralActivitiesInitialsDataAction(Request $request)
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

            $collateralactivitiessBussinessObj = new CollateralActivitiesBussiness($em);
            $initialsData = $collateralactivitiessBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showCollateralActivitiesForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showCollateralActivitiesForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showCollateralActivitiesForm'] = $showCollateralActivitiesForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();
            $initialsData['languages'] = $this->container->getParameter('app.avaiableLanguajes');

//            $parametersCollection = array();
//            $parametersCollection['taxonomyTypeTreeSlug'] = 'collateralactivities-category';
//            $parametersCollection['returnDataInTree'] = true;
//            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load collateralactivitiess collection data
     *
     * @Route("/datos-actividades_colateraless", name="collateralactivitiess_data", options={"expose"=true})
     * @Security("is_granted('read', 'collateralactivities')")
     * @Method("POST")
     */
    public function loadCollateralActivitiesDataAction(Request $request)
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
            $parametersCollection['collateralactivitiesId'] = $request->get('collateralactivitiesId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $em = $this->getDoctrine()->getManager();
            $collateralactivitiessBussinessObj = new CollateralActivitiesBussiness($em);
            $collateralactivitiessDataCollection = $collateralactivitiessBussinessObj->getCollateralActivitiessList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('collateralactivitiesData' => $collateralactivitiessDataCollection));
            }
            return new JsonResponse(array('collateralactivitiessDataCollection' => $collateralactivitiessDataCollection));
        }
    }

    /**
     * Save CollateralActivities data (CREATE action)
     *
     * @Route("/crear", name="collateralactivitiess_create", options={"expose"=true})
     * @Security("is_granted('create', 'collateralactivities')")
     *
     */
    public function createCollateralActivitiesAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('collateralactivitiesData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
                $parametersCollection['loggedUser'] = $this->getUser();

                $collateralactivitiessBussinessObj = new CollateralActivitiesBussiness($em);
                $response = $collateralactivitiessBussinessObj->saveCollateralActivitiesData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('collateralactivitiess_index');
            }
        }
    }

    /**
     * Save CollateralActivities data (EDIT action)
     *
     * @Route("/editar", name="collateralactivitiess_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'collateralactivities')")
     * @Method("POST")
     */
    public function editCollateralActivitiesAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('collateralactivitiesData');
            $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $collateralactivitiessBussinessObj = new CollateralActivitiesBussiness($em);
            $response = $collateralactivitiessBussinessObj->saveCollateralActivitiesData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete CollateralActivities
     *
     * @Route("/eliminar-actividades_colaterales", name="collateralactivitiess_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'collateralactivities')")
     * @Method("POST")
     */
    public function deleteCollateralActivitiesAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['collateralactivitiessId'] = $request->get('collateralactivitiessId');
            $parametersCollection['currentLanguage'] = $request->get('currentLanguage') ? $request->get('currentLanguage') : $this->container->getParameter('app.default_locale');

            $collateralactivitiessBussinessObj = new CollateralActivitiesBussiness($em);
            $response = $collateralactivitiessBussinessObj->deleteCollateralActivitiesData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
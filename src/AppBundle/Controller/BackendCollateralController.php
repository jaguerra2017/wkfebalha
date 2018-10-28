<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\CollateralBussiness;


/**
 * BACKEND - Collateral controller.
 *
 * @Route("backend/colaterales")
 */
class BackendCollateralController extends Controller
{

    /**
     * Return the Collateral View
     *
     * @Route("/", name="collateral_index")
     * @Security("is_granted('read', 'collateral')")
     * @Method("GET")
     */
    public function collateralsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Collateral/index.html.twig',
              array('languages'=> $this->container->getParameter('app.avaiableLanguajes'))
            );
        }

    }

    /**
     * Load initials data for Collateral view
     *
     * @Route("/datos-iniciales", name="collateral_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'collateral')")
     * @Method("POST")
     */
    public function loadCollateralInitialsDataAction(Request $request)
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

            $collateralsBussinessObj = new CollateralBussiness($em);
            $initialsData = $collateralsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showCollateralForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showCollateralForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showCollateralForm'] = $showCollateralForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();
            $initialsData['languages'] = $this->container->getParameter('app.avaiableLanguajes');

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'collateral-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load collaterals collection data
     *
     * @Route("/datos-colaterales", name="collateral_data", options={"expose"=true})
     * @Security("is_granted('read', 'collateral')")
     * @Method("POST")
     */
    public function loadCollateralDataAction(Request $request)
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
            $parametersCollection['collateralId'] = $request->get('collateralId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $em = $this->getDoctrine()->getManager();
            $collateralsBussinessObj = new CollateralBussiness($em);
            $collateralsDataCollection = $collateralsBussinessObj->getCollateralsList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('collateralData' => $collateralsDataCollection));
            }
            return new JsonResponse(array('collateralDataCollection' => $collateralsDataCollection));
        }
    }

    /**
     * Save Collateral data (CREATE action)
     *
     * @Route("/crear", name="collateral_create", options={"expose"=true})
     * @Security("is_granted('create', 'collateral')")
     *
     */
    public function createCollateralAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('collateralData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
                $parametersCollection['loggedUser'] = $this->getUser();

                $collateralsBussinessObj = new CollateralBussiness($em);
                $response = $collateralsBussinessObj->saveCollateralData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('collateral_index');
            }
        }
    }

    /**
     * Save Collateral data (EDIT action)
     *
     * @Route("/editar", name="collateral_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'collateral')")
     * @Method("POST")
     */
    public function editCollateralAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('collateralData');
            $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $collateralsBussinessObj = new CollateralBussiness($em);
            $response = $collateralsBussinessObj->saveCollateralData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Collateral
     *
     * @Route("/eliminar-colaterales", name="collaterals_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'collateral')")
     * @Method("POST")
     */
    public function deleteCollateralAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['collateralId'] = $request->get('collateralId');
            $parametersCollection['currentLanguage'] = $request->get('currentLanguage') ? $request->get('currentLanguage') : $this->container->getParameter('app.default_locale');

            $collateralsBussinessObj = new CollateralBussiness($em);
            $response = $collateralsBussinessObj->deleteCollateralData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\HistoricalMomentsBussiness;


/**
 * BACKEND - HistoricalMoments controller.
 *
 * @Route("backend/hitos-historicos")
 */
class BackendHistoricalMomentController extends Controller
{

    /**
     * Return the HistoricalMoments View
     *
     * @Route("/", name="historical_moments_index")
     * @Security("is_granted('read', 'historical-moments')")
     * @Method("GET")
     */
    public function historicalMomentsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/HistoricalMoment/index.html.twig');
        }

    }

    /**
     * Load initials data for HistoricalMoments view
     *
     * @Route("/datos-iniciales", name="historical_moments_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'historical-moments')")
     * @Method("POST")
     */
    public function loadHistoricalMomentsInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $historicalMomentsBussinessObj = new HistoricalMomentsBussiness($em);
            $initialsData = $historicalMomentsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showHistoricalMomentsForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showHistoricalMomentsForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showHistoricalMomentsForm'] = $showHistoricalMomentsForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'historicalMoment-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load historicalMoments collection data
     *
     * @Route("/datos-hitos-historicos", name="historical_moments_data", options={"expose"=true})
     * @Security("is_granted('read', 'historical-moments')")
     * @Method("POST")
     */
    public function loadHistoricalMomentsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['view'] = $request->get('view');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['historicalMomentId'] = $request->get('historicalMomentId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $em = $this->getDoctrine()->getManager();
            $historicalMomentsBussinessObj = new HistoricalMomentsBussiness($em);
            $historicalMomentsDataCollection = $historicalMomentsBussinessObj->getHistoricalMomentsList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('historicalMomentData' => $historicalMomentsDataCollection));
            }
            return new JsonResponse(array('historicalMomentsDataCollection' => $historicalMomentsDataCollection));
        }
    }

    /**
     * Save HistoricalMoment data (CREATE action)
     *
     * @Route("/crear", name="historical_moments_create", options={"expose"=true})
     * @Security("is_granted('create', 'historical-moments')")
     *
     */
    public function createHistoricalMomentAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('historicalMomentData');
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $historicalMomentsBussinessObj = new HistoricalMomentsBussiness($em);
                $response = $historicalMomentsBussinessObj->saveHistoricalMomentData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('historical_moments_index');
            }
        }
    }

    /**
     * Save HistoricalMoment data (EDIT action)
     *
     * @Route("/editar", name="historical_moments_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'historical-moments')")
     * @Method("POST")
     */
    public function editHistoricalMomentAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('historicalMomentData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $historicalMomentsBussinessObj = new HistoricalMomentsBussiness($em);
            $response = $historicalMomentsBussinessObj->saveHistoricalMomentData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete HistoricalMoments
     *
     * @Route("/eliminar-hitos-historicos", name="historical_moments_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'historical-moments')")
     * @Method("POST")
     */
    public function deleteHistoricalMomentsAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['historicalMomentsId'] = $request->get('historicalMomentsId');

            $historicalMomentsBussinessObj = new HistoricalMomentsBussiness($em);
            $response = $historicalMomentsBussinessObj->deleteHistoricalMomentsData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
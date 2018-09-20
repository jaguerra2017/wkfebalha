<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\AwardsBussiness;


/**
 * BACKEND - Awards controller.
 *
 * @Route("backend/distinciones")
 */
class BackendAwardController extends Controller
{

    /**
     * Return the Awards View
     *
     * @Route("/", name="awards_index")
     * @Security("is_granted('read', 'awards')")
     * @Method("GET")
     */
    public function awardsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Award/index.html.twig');
        }

    }

    /**
     * Load initials data for Awards view
     *
     * @Route("/datos-iniciales", name="awards_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'awards')")
     * @Method("POST")
     */
    public function loadAwardsInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $awardsBussinessObj = new AwardsBussiness($em);
            $initialsData = $awardsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showAwardsForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showAwardsForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showAwardsForm'] = $showAwardsForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'award-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load awards collection data
     *
     * @Route("/datos-distinciones", name="awards_data", options={"expose"=true})
     * @Security("is_granted('read', 'awards')")
     * @Method("POST")
     */
    public function loadAwardsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['awardId'] = $request->get('awardId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $em = $this->getDoctrine()->getManager();
            $awardsBussinessObj = new AwardsBussiness($em);
            $awardsDataCollection = $awardsBussinessObj->getAwardsList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('awardData' => $awardsDataCollection));
            }
            return new JsonResponse(array('awardsDataCollection' => $awardsDataCollection));
        }
    }

    /**
     * Save Award data (CREATE action)
     *
     * @Route("/crear", name="awards_create", options={"expose"=true})
     * @Security("is_granted('create', 'awards')")
     *
     */
    public function createAwardAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('awardData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $awardsBussinessObj = new AwardsBussiness($em);
                $response = $awardsBussinessObj->saveAwardData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('awards_index');
            }
        }
    }

    /**
     * Save Award data (EDIT action)
     *
     * @Route("/editar", name="awards_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'awards')")
     * @Method("POST")
     */
    public function editAwardAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('awardData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $awardsBussinessObj = new AwardsBussiness($em);
            $response = $awardsBussinessObj->saveAwardData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Awards
     *
     * @Route("/eliminar-distincion", name="awards_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'awards')")
     * @Method("POST")
     */
    public function deleteAwardsAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['awardsId'] = $request->get('awardsId');

            $awardsBussinessObj = new AwardsBussiness($em);
            $response = $awardsBussinessObj->deleteAwardsData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
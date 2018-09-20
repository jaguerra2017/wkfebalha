<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\PartnersBussiness;


/**
 * BACKEND - Partners controller.
 *
 * @Route("backend/socios")
 */
class BackendPartnerController extends Controller
{

    /**
     * Return the Partners View
     *
     * @Route("/", name="partners_index")
     * @Security("is_granted('read', 'partners')")
     * @Method("GET")
     */
    public function partnersViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Partner/index.html.twig');
        }

    }

    /**
     * Load initials data for Partners view
     *
     * @Route("/datos-iniciales", name="partners_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'partners')")
     * @Method("POST")
     */
    public function loadPartnersInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $partnersBussinessObj = new PartnersBussiness($em);
            $initialsData = $partnersBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showPartnersForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showPartnersForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showPartnersForm'] = $showPartnersForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'partner-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load partners collection data
     *
     * @Route("/datos-socios", name="partners_data", options={"expose"=true})
     * @Security("is_granted('read', 'partners')")
     * @Method("POST")
     */
    public function loadPartnersDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['partnerId'] = $request->get('partnerId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $em = $this->getDoctrine()->getManager();
            $partnersBussinessObj = new PartnersBussiness($em);
            $partnersDataCollection = $partnersBussinessObj->getPartnersList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('partnerData' => $partnersDataCollection));
            }
            return new JsonResponse(array('partnersDataCollection' => $partnersDataCollection));
        }
    }

    /**
     * Save Partner data (CREATE action)
     *
     * @Route("/crear", name="partners_create", options={"expose"=true})
     * @Security("is_granted('create', 'partners')")
     *
     */
    public function createPartnerAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('partnerData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $partnersBussinessObj = new PartnersBussiness($em);
                $response = $partnersBussinessObj->savePartnerData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('partners_index');
            }
        }
    }

    /**
     * Save Partner data (EDIT action)
     *
     * @Route("/editar", name="partners_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'partners')")
     * @Method("POST")
     */
    public function editPartnerAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('partnerData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $partnersBussinessObj = new PartnersBussiness($em);
            $response = $partnersBussinessObj->savePartnerData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Partners
     *
     * @Route("/eliminar-socio", name="partners_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'partners')")
     * @Method("POST")
     */
    public function deletePartnersAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['partnersId'] = $request->get('partnersId');

            $partnersBussinessObj = new PartnersBussiness($em);
            $response = $partnersBussinessObj->deletePartnersData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
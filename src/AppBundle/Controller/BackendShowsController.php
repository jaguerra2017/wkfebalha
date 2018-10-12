<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\ShowBussiness;


/**
 * BACKEND - Show controller.
 *
 * @Route("backend/funcions")
 */
class BackendShowsController extends Controller
{

    /**
     * Return the Show View
     *
     * @Route("/", name="shows_index")
     * @Security("is_granted('read', 'show')")
     * @Method("GET")
     */
    public function showsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Show/index.html.twig',
              array('languages'=> $this->container->getParameter('app.avaiableLanguajes'))
            );
        }

    }

    /**
     * Load initials data for Show view
     *
     * @Route("/datos-iniciales", name="shows_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'show')")
     * @Method("POST")
     */
    public function loadShowInitialsDataAction(Request $request)
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

            $showsBussinessObj = new ShowBussiness($em);
            $initialsData = $showsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showShowForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showShowForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showShowForm'] = $showShowForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();
            $initialsData['languages'] = $this->container->getParameter('app.avaiableLanguajes');

//            $parametersCollection = array();
//            $parametersCollection['taxonomyTypeTreeSlug'] = 'show-category';
//            $parametersCollection['returnDataInTree'] = true;
//            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load shows collection data
     *
     * @Route("/datos-funcions", name="shows_data", options={"expose"=true})
     * @Security("is_granted('read', 'show')")
     * @Method("POST")
     */
    public function loadShowDataAction(Request $request)
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
            $parametersCollection['showId'] = $request->get('showId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $em = $this->getDoctrine()->getManager();
            $showsBussinessObj = new ShowBussiness($em);
            $showsDataCollection = $showsBussinessObj->getShowsList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('showData' => $showsDataCollection));
            }
            return new JsonResponse(array('showsDataCollection' => $showsDataCollection));
        }
    }

    /**
     * Save Show data (CREATE action)
     *
     * @Route("/crear", name="shows_create", options={"expose"=true})
     * @Security("is_granted('create', 'show')")
     *
     */
    public function createShowAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('showData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
                $parametersCollection['loggedUser'] = $this->getUser();

                $showsBussinessObj = new ShowBussiness($em);
                $response = $showsBussinessObj->saveShowData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('shows_index');
            }
        }
    }

    /**
     * Save Show data (EDIT action)
     *
     * @Route("/editar", name="shows_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'show')")
     * @Method("POST")
     */
    public function editShowAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('showData');
            $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $showsBussinessObj = new ShowBussiness($em);
            $response = $showsBussinessObj->saveShowData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Show
     *
     * @Route("/eliminar-funcion", name="shows_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'show')")
     * @Method("POST")
     */
    public function deleteShowAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['showsId'] = $request->get('showsId');
            $parametersCollection['currentLanguage'] = $request->get('currentLanguage') ? $request->get('currentLanguage') : $this->container->getParameter('app.default_locale');

            $showsBussinessObj = new ShowBussiness($em);
            $response = $showsBussinessObj->deleteShowData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\PagesBussiness;


/**
 * BACKEND - pages controller.
 *
 * @Route("backend/paginas")
 */
class BackendPageController extends Controller
{

    /**
     * Return the pages View
     *
     * @Route("/", name="pages_index")
     * @Security("is_granted('read', 'pages')")
     * @Method("GET")
     */
    public function pagesViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Page/index.html.twig');
        }

    }

    /**
     * Load initials data for Pages view
     *
     * @Route("/datos-iniciales", name="pages_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'pages')")
     * @Method("POST")
     */
    public function loadPagesInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $pagesBussinessObj = new PagesBussiness($em);
            $initialsData = $pagesBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $parametersCollection = array();
            $parametersCollection['currentTheme'] = $this->container->get('appbundle_site_settings')->getCurrentTheme();
            $initialsData['templatesDataCollection'] = $this->container->get('appbundle_file_finder')->getThemeTemplatesNames($parametersCollection);

            $showPagesForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showPagesForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showPagesForm'] = $showPagesForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'page-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load pages collection data
     *
     * @Route("/datos-paginas", name="pages_data", options={"expose"=true})
     * @Security("is_granted('read', 'pages')")
     * @Method("POST")
     */
    public function loadPagesDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['pageId'] = $request->get('pageId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $em = $this->getDoctrine()->getManager();
            $pagesBussinessObj = new PagesBussiness($em);
            $pagesDataCollection = $pagesBussinessObj->getPagesList($parametersCollection);
            if(isset($parametersCollection['singleResult']) && $parametersCollection['singleResult'] == true){
                return new JsonResponse(array('pageData' => $pagesDataCollection));
            }
            return new JsonResponse(array('pagesDataCollection' => $pagesDataCollection));
        }
    }

    /**
     * Save Page data (CREATE action)
     *
     * @Route("/crear", name="pages_create", options={"expose"=true})
     * @Security("is_granted('create', 'pages')")
     *
     */
    public function createPageAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('pageData');
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $pagesBussinessObj = new PagesBussiness($em);
                $response = $pagesBussinessObj->savePageData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('pages_index');
            }
        }
    }

    /**
     * Save Page data (EDIT action)
     *
     * @Route("/editar", name="pages_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'pages')")
     * @Method("POST")
     */
    public function editPageAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('pageData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $pagesBussinessObj = new PagesBussiness($em);
            $response = $pagesBussinessObj->savePageData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Pages
     *
     * @Route("/eliminar-pagina", name="pages_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'pages')")
     * @Method("POST")
     */
    public function deletePagesAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['pagesId'] = $request->get('pagesId');

            $pagesBussinessObj = new PagesBussiness($em);
            $response = $pagesBussinessObj->deletePagesData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
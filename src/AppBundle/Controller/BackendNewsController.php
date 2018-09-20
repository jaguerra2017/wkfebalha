<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\NewsBussiness;


/**
 * BACKEND - News controller.
 *
 * @Route("backend/noticias")
 */
class BackendNewsController extends Controller
{

    /**
     * Return the News View
     *
     * @Route("/", name="news_index")
     * @Security("is_granted('read', 'news')")
     * @Method("GET")
     */
    public function newsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/New/index.html.twig');
        }

    }

    /**
     * Load initials data for News view
     *
     * @Route("/datos-iniciales", name="news_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'news')")
     * @Method("POST")
     */
    public function loadNewsInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $newsBussinessObj = new NewsBussiness($em);
            $initialsData = $newsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showNewsForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showNewsForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showNewsForm'] = $showNewsForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'new-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load news collection data
     *
     * @Route("/datos-paginas", name="news_data", options={"expose"=true})
     * @Security("is_granted('read', 'news')")
     * @Method("POST")
     */
    public function loadNewsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['newId'] = $request->get('newId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $em = $this->getDoctrine()->getManager();
            $newsBussinessObj = new NewsBussiness($em);
            $newsDataCollection = $newsBussinessObj->getNewsList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('newData' => $newsDataCollection));
            }
            return new JsonResponse(array('newsDataCollection' => $newsDataCollection));
        }
    }

    /**
     * Save New data (CREATE action)
     *
     * @Route("/crear", name="news_create", options={"expose"=true})
     * @Security("is_granted('create', 'news')")
     *
     */
    public function createNewAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('newData');
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $newsBussinessObj = new NewsBussiness($em);
                $response = $newsBussinessObj->saveNewData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('news_index');
            }
        }
    }

    /**
     * Save New data (EDIT action)
     *
     * @Route("/editar", name="news_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'news')")
     * @Method("POST")
     */
    public function editNewAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('newData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $newsBussinessObj = new NewsBussiness($em);
            $response = $newsBussinessObj->saveNewData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete News
     *
     * @Route("/eliminar-publicacion", name="news_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'news')")
     * @Method("POST")
     */
    public function deleteNewsAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['newsId'] = $request->get('newsId');

            $newsBussinessObj = new NewsBussiness($em);
            $response = $newsBussinessObj->deleteNewsData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
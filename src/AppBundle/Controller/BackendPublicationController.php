<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\PublicationsBussiness;


/**
 * BACKEND - Publications controller.
 *
 * @Route("backend/publicaciones")
 */
class BackendPublicationController extends Controller
{

    /**
     * Return the Publications View
     *
     * @Route("/", name="publications_index")
     * @Security("is_granted('read', 'publications')")
     * @Method("GET")
     */
    public function publicationsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Publication/index.html.twig');
        }

    }

    /**
     * Load initials data for Publications view
     *
     * @Route("/datos-iniciales", name="publications_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'publications')")
     * @Method("POST")
     */
    public function loadPublicationsInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $publicationsBussinessObj = new PublicationsBussiness($em);
            $initialsData = $publicationsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showPublicationsForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showPublicationsForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showPublicationsForm'] = $showPublicationsForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'publication-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load publications collection data
     *
     * @Route("/datos-publicaciones", name="publications_data", options={"expose"=true})
     * @Security("is_granted('read', 'publications')")
     * @Method("POST")
     */
    public function loadPublicationsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['publicationId'] = $request->get('publicationId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $em = $this->getDoctrine()->getManager();
            $publicationsBussinessObj = new PublicationsBussiness($em);
            $publicationsDataCollection = $publicationsBussinessObj->getPublicationsList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('publicationData' => $publicationsDataCollection));
            }
            return new JsonResponse(array('publicationsDataCollection' => $publicationsDataCollection));
        }
    }

    /**
     * Save Publication data (CREATE action)
     *
     * @Route("/crear", name="publications_create", options={"expose"=true})
     * @Security("is_granted('create', 'publications')")
     *
     */
    public function createPublicationAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('publicationData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $publicationsBussinessObj = new PublicationsBussiness($em);
                $response = $publicationsBussinessObj->savePublicationData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('publications_index');
            }
        }
    }

    /**
     * Save Publication data (EDIT action)
     *
     * @Route("/editar", name="publications_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'publications')")
     * @Method("POST")
     */
    public function editPublicationAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('publicationData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $publicationsBussinessObj = new PublicationsBussiness($em);
            $response = $publicationsBussinessObj->savePublicationData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Publications
     *
     * @Route("/eliminar-publicacion", name="publications_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'publications')")
     * @Method("POST")
     */
    public function deletePublicationsAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['publicationsId'] = $request->get('publicationsId');

            $publicationsBussinessObj = new PublicationsBussiness($em);
            $response = $publicationsBussinessObj->deletePublicationsData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
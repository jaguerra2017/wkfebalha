<?php

namespace AppBundle\Controller;

use AppBundle\Bussiness\NomenclatureBussiness;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\MediaBussiness;


/**
 * BACKEND - Media controller.
 *
 * @Route("backend/media")
 */
class BackendMediaController extends Controller
{

    /**
     * Return the Media View
     *
     * @Route("/", name="media_index")
     * @Security("is_granted('read', 'media')")
     * @Method("GET")
     */
    public function mediaViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Media/index.html.twig');
        }

    }

    /**
     * Load initials data for Media view
     *
     * @Route("/datos-iniciales", name="media_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'media')")
     * @Method("POST")
     */
    public function loadMediaInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');
            $mediaBussinessObj = new MediaBussiness($em);
            $initialsData = $mediaBussinessObj->loadInitialsData($parametersCollection);
            $showMediaForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showMediaForm = true;
                $request->getSession()->set('directAccessToCreate', false);
            }
            $initialsData['showMediaForm'] = $showMediaForm;

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load media collection data
     *
     * @Route("/datos-media", name="media_data", options={"expose"=true})
     * @Security("is_granted('read', 'media')")
     * @Method("POST")
     */
    public function loadMediaDataAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $this->getRequest()->request->get('generalSearchValue');
            $parametersCollection['mediaType'] = $this->getRequest()->request->get('mediaType');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $em = $this->getDoctrine()->getManager();
            $mediaBussinessObj = new MediaBussiness($em);
            $mediaDataCollection = $mediaBussinessObj->getMediaData($parametersCollection);
            $galleryTypesCollection = array();
            if($parametersCollection['mediaType'] == 'gallery'){
                $paramsCollection = new \stdClass();
                $paramsCollection->filterByTreeSlug = true;
                $paramsCollection->treeSlug = 'gallery-type';
                $objNomenclature = new NomenclatureBussiness($em);
                $galleryTypesCollection = $objNomenclature->getNomenclatures($paramsCollection);
            }
            return new JsonResponse(array(
                'mediaDataCollection' => $mediaDataCollection,
                'galleryTypesCollection' => $galleryTypesCollection
            ));
        }
    }

    /**
     * Action to handle direct GET request to CREATE, and redirect to the correct action.
     *
     * @Route("/crear", name="media_create", options={"expose"=true})
     * @Security("is_granted('create', 'media')")
     * @Method("GET")
     */
    public function createMediaAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $request->getSession()->set('directAccessToCreate', true);
            return $this->redirectToRoute('media_index');
        }
    }

    /**
     * Save Media Image data (CREATE action)
     *
     * @Route("/crear-media-imagen", name="media_image_create", options={"expose"=true})
     * @Security("is_granted('create', 'media')")
     * @Method("POST")
     */
    public function createMediaImageAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            if($request->files->has('media_images_uploaded')){
                $parametersCollection['media_images_uploaded'] = $request->files->get('media_images_uploaded');
                $parametersCollection['media_images_info_associated'] = $request->get('media_images_info_associated');
            }
            $parametersCollection['isCreating'] = true;
            $parametersCollection['slugger'] = $this->get('appbundle_slugger');
            $parametersCollection['loggedUser'] = $this->getUser();

            $mediaBussinessObj = new MediaBussiness($em);
            $response = $mediaBussinessObj->saveMediaImageData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Media Image data (EDIT action)
     *
     * @Route("/editar-media-imagen", name="media_image_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'media')")
     * @Method("POST")
     */
    public function editMediaImageAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('mediaData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['slugger'] = $this->get('appbundle_slugger');
            $parametersCollection['loggedUser'] = $this->getUser();

            $mediaBussinessObj = new MediaBussiness($em);
            $response = $mediaBussinessObj->saveMediaImageData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Media
     *
     * @Route("/eliminar-media-imagen", name="media_image_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'media')")
     * @Method("POST")
     */
    public function deleteMediaImageAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['mediaImagesId'] = $request->get('mediaImagesId');

            $mediaBussinessObj = new MediaBussiness($em);
            $response = $mediaBussinessObj->deleteMediaImageData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Media Video data (CREATE action)
     *
     * @Route("/crear-media-video", name="media_video_create", options={"expose"=true})
     * @Security("is_granted('create', 'media')")
     * @Method("POST")
     */
    public function createMediaVideoAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('mediaData');
            $parametersCollection['isCreating'] = true;
            $parametersCollection['slugger'] = $this->get('appbundle_slugger');
            $parametersCollection['loggedUser'] = $this->getUser();

            $mediaBussinessObj = new MediaBussiness($em);
            $response = $mediaBussinessObj->saveMediaVideoData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Media Video data (EDIT action)
     *
     * @Route("/editar-media-video", name="media_video_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'media')")
     * @Method("POST")
     */
    public function editMediaVideoAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('mediaData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['slugger'] = $this->get('appbundle_slugger');
            $parametersCollection['loggedUser'] = $this->getUser();

            $mediaBussinessObj = new MediaBussiness($em);
            $response = $mediaBussinessObj->saveMediaVideoData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Media Video
     *
     * @Route("/eliminar-media-video", name="media_video_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'media')")
     * @Method("POST")
     */
    public function deleteMediaVideoAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['mediaVideosId'] = $request->get('mediaVideosId');

            $mediaBussinessObj = new MediaBussiness($em);
            $response = $mediaBussinessObj->deleteMediaVideoData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Media Gallery data (CREATE action)
     *
     * @Route("/crear-media-galeria", name="media_gallery_create", options={"expose"=true})
     * @Security("is_granted('create', 'media')")
     * @Method("POST")
     */
    public function createMediaGalleryAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('mediaData');
            //print_r($parametersCollection);die;
            $parametersCollection['isCreating'] = true;
            $parametersCollection['slugger'] = $this->get('appbundle_slugger');
            $parametersCollection['loggedUser'] = $this->getUser();

            $mediaBussinessObj = new MediaBussiness($em);
            $response = $mediaBussinessObj->saveMediaGalleryData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Media Gallery data (EDIT action)
     *
     * @Route("/editar-media-galeria", name="media_gallery_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'media')")
     * @Method("POST")
     */
    public function editMediaGalleryAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('mediaData');
            //print_r($parametersCollection);die;
            $parametersCollection['isCreating'] = false;
            $parametersCollection['slugger'] = $this->get('appbundle_slugger');
            $parametersCollection['loggedUser'] = $this->getUser();

            $mediaBussinessObj = new MediaBussiness($em);
            $response = $mediaBussinessObj->saveMediaGalleryData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Media Gallery
     *
     * @Route("/eliminar-media-galeria", name="media_gallery_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'media')")
     * @Method("POST")
     */
    public function deleteMediaGalleryAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['mediaGalleriesId'] = $request->get('mediaGalleriesId');

            $mediaBussinessObj = new MediaBussiness($em);
            $response = $mediaBussinessObj->deleteMediaGalleryData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
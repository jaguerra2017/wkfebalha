<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\RoomsBussiness;
use AppBundle\Bussiness\MediaBussiness;
use AppBundle\Bussiness\OpinionsBussiness;


/**
 * BACKEND - Rooms controller.
 *
 * @Route("backend/salas")
 */
class BackendRoomsController extends Controller
{
  /**
   * Load initials data for Room view
   *
   * @Route("/datos-iniciales", name="rooms_view_initials_data", options={"expose"=true})
   * @Security("is_granted('read', 'rooms')")
   * @Method("POST")
   */
  public function loadRoomInitialsDataAction(Request $request)
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

      $roomsBussinessObj = new RoomsBussiness($em);
      $initialsData = $roomsBussinessObj->loadInitialsData($parametersCollection);

      $parametersCollection = new \stdClass();
      $parametersCollection->filterByTreeSlug = true;
      $parametersCollection->treeSlug = 'post-status';
      $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

      $showRoomForm = false;
      if($request->getSession()->get('directAccessToCreate') == true){
        $showRoomForm = true;
        $request->getSession()->remove('directAccessToCreate');
      }
      $initialsData['showRoomForm'] = $showRoomForm;

      $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();
      $initialsData['languages'] = $this->container->getParameter('app.avaiableLanguajes');

//            $parametersCollection = array();
//            $parametersCollection['taxonomyTypeTreeSlug'] = 'room-category';
//            $parametersCollection['returnDataInTree'] = true;
//            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

      return new JsonResponse(array('initialsData' => $initialsData));
    }
  }

  /**
   * Load rooms collection data
   *
   * @Route("/datos-sedes", name="rooms_data", options={"expose"=true})
   * @Security("is_granted('read', 'rooms')")
   * @Method("POST")
   */
  public function loadRoomDataAction(Request $request)
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
      $parametersCollection['roomId'] = $request->get('roomId');
      $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

      $parametersCollection['returnByCustomOrder'] = true;
      $parametersCollection['customOrderField'] = 'published_date';
      $parametersCollection['customOrderSort'] = 'DESC';

      $em = $this->getDoctrine()->getManager();
      $roomsBussinessObj = new RoomsBussiness($em);
      $roomsDataCollection = $roomsBussinessObj->getRoomsList($parametersCollection);
      if(isset($parametersCollection['singleResult'])){
        return new JsonResponse(array('roomData' => $roomsDataCollection));
      }
      return new JsonResponse(array('roomsDataCollection' => $roomsDataCollection));
    }
  }

  /**
   * Save Room data (CREATE action)
   *
   * @Route("/crear", name="rooms_create", options={"expose"=true})
   * @Security("is_granted('create', 'rooms')")
   *
   */
  public function createRoomAction(Request $request)
  {
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      if($request->getMethod() == 'POST'){
        $em = $this->getDoctrine()->getManager();
        $parametersCollection = $request->get('roomData');
        //print_r($parametersCollection);die;
        $parametersCollection['isCreating'] = true;
        $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
        $parametersCollection['loggedUser'] = $this->getUser();

        $roomsBussinessObj = new RoomsBussiness($em);
        $response = $roomsBussinessObj->saveRoomData($parametersCollection);
        return new JsonResponse($response);
      }
      else{
        $request->getSession()->set('directAccessToCreate', true);
        return $this->redirectToRoute('rooms_index');
      }
    }
  }

  /**
   * Save Room data (EDIT action)
   *
   * @Route("/editar", name="rooms_edit", options={"expose"=true})
   * @Security("is_granted('edit', 'rooms')")
   * @Method("POST")
   */
  public function editRoomAction(Request $request)
  {
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = $request->get('roomData');
      $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
      $parametersCollection['isCreating'] = false;
      $parametersCollection['loggedUser'] = $this->getUser();

      $roomsBussinessObj = new RoomsBussiness($em);
      $response = $roomsBussinessObj->saveRoomData($parametersCollection);
      return new JsonResponse($response);
    }
  }

  /**
   * Delete Room
   *
   * @Route("/eliminar-sede", name="rooms_delete", options={"expose"=true})
   * @Security("is_granted('delete', 'rooms')")
   * @Method("POST")
   */
  public function deleteRoomAction(Request $request)
  {
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection['roomsId'] = $request->get('roomsId');
      $parametersCollection['currentLanguage'] = $request->get('currentLanguage') ? $request->get('currentLanguage') : $this->container->getParameter('app.default_locale');

      $roomsBussinessObj = new RoomsBussiness($em);
      $response = $roomsBussinessObj->deleteRoomData($parametersCollection);
      return new JsonResponse($response);
    }
  }

}
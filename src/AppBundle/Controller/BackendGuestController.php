<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\GuestBussiness;


/**
 * BACKEND - Guest controller.
 *
 * @Route("backend/invitados")
 */
class BackendGuestController extends Controller
{

  /**
   * Return the Guest View
   *
   * @Route("/", name="guests_index")
   * @Security("is_granted('read', 'guest')")
   * @Method("GET")
   */
  public function guestViewAction()
  {
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      return $this->render('@app_backend_template_directory/Guest/index.html.twig',
        array('languages'=> $this->container->getParameter('app.avaiableLanguajes')));
    }

  }

  /**
   * Load initials data for Guest view
   *
   * @Route("/datos-iniciales", name="guest_view_initials_data", options={"expose"=true})
   * @Security("is_granted('read', 'guest')")
   * @Method("POST")
   */
  public function loadGuestInitialsDataAction(Request $request)
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

      $guestBussinessObj = new GuestBussiness($em);
      $initialsData = $guestBussinessObj->loadInitialsData($parametersCollection);

      $parametersCollection = new \stdClass();
      $parametersCollection->filterByTreeSlug = true;
      $parametersCollection->treeSlug = 'post-status';
      $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

      $showGuestForm = false;
      if($request->getSession()->get('directAccessToCreate') == true){
        $showGuestForm = true;
        $request->getSession()->remove('directAccessToCreate');
      }
      $initialsData['showGuestForm'] = $showGuestForm;

      $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

      $parametersCollection = array();
      $parametersCollection['taxonomyTypeTreeSlug'] = 'guest-category';
      $parametersCollection['returnDataInTree'] = true;
      $initialsData['languages'] = $this->container->getParameter('app.avaiableLanguajes');
      $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

      return new JsonResponse(array('initialsData' => $initialsData));
    }
  }

  /**
   * Load guest collection data
   *
   * @Route("/datos-invitados", name="guest_data", options={"expose"=true})
   * @Security("is_granted('read', 'guest')")
   * @Method("POST")
   */
  public function loadGuestDataAction(Request $request)
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
      $parametersCollection['guestId'] = $request->get('guestId');
      $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

      $parametersCollection['returnByCustomOrder'] = true;
      $parametersCollection['customOrderField'] = 'published_date';
      $parametersCollection['customOrderSort'] = 'DESC';

      $em = $this->getDoctrine()->getManager();
      $guestBussinessObj = new GuestBussiness($em);
      $guestDataCollection = $guestBussinessObj->getGuestsList($parametersCollection);
      if(isset($parametersCollection['singleResult'])){
        return new JsonResponse(array('guestData' => $guestDataCollection));
      }
      return new JsonResponse(array('guestDataCollection' => $guestDataCollection));
    }
  }

  /**
   * Save Guest data (CREATE action)
   *
   * @Route("/crear", name="guest_create", options={"expose"=true})
   * @Security("is_granted('create', 'guest')")
   *
   */
  public function createGuestAction(Request $request)
  {
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      if($request->getMethod() == 'POST'){
        $em = $this->getDoctrine()->getManager();
        $parametersCollection = $request->get('guestData');
        //print_r($parametersCollection);die;
        $parametersCollection['isCreating'] = true;
        $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
        $parametersCollection['loggedUser'] = $this->getUser();

        $guestBussinessObj = new GuestBussiness($em);
        $response = $guestBussinessObj->saveGuestData($parametersCollection);
        return new JsonResponse($response);
      }
      else{
        $request->getSession()->set('directAccessToCreate', true);
        return $this->redirectToRoute('guests_index');
      }
    }
  }

  /**
   * Save Guest data (EDIT action)
   *
   * @Route("/editar", name="guest_edit", options={"expose"=true})
   * @Security("is_granted('edit', 'guest')")
   * @Method("POST")
   */
  public function editGuestAction(Request $request)
  {
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = $request->get('guestData');
      $parametersCollection['isCreating'] = false;
      $parametersCollection['currentLanguage'] = $parametersCollection['currentLanguage'] ? $parametersCollection['currentLanguage'] : $this->container->getParameter('app.default_locale');
      $parametersCollection['loggedUser'] = $this->getUser();

      $guestBussinessObj = new GuestBussiness($em);
      $response = $guestBussinessObj->saveGuestData($parametersCollection);
      return new JsonResponse($response);
    }
  }

  /**
   * Delete Guest
   *
   * @Route("/eliminar-socio", name="guest_delete", options={"expose"=true})
   * @Security("is_granted('delete', 'guest')")
   * @Method("POST")
   */
  public function deleteGuestAction(Request $request)
  {
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection['guestId'] = $request->get('guestId');

      $guestBussinessObj = new GuestBussiness($em);
      $response = $guestBussinessObj->deleteGuestData($parametersCollection);
      return new JsonResponse($response);
    }
  }

}
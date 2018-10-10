<?php

namespace AppBundle\Controller;

use AppBundle\Bussiness\ReserveBussiness;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * BACKEND - Reserve controller.
 *
 * @Route("backend/reserve")
 */
class BackendReserveController extends Controller
{
  /**
   * Load initials data for Jewels view
   *
   * @Route("/datos-iniciales", name="reserve_initials_data", options={"expose"=true})
   * @Method("POST")
   */
  public function loadReserveInitialsDataAction(Request $request)
  {
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection = $request->get('reserveData');
      $reserveBussinessObj = new ReserveBussiness($em);
      $initialsData = $reserveBussinessObj->loadInitialsData($parametersCollection);


      $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

      return new JsonResponse(array('initialsData' => $initialsData));
    }
  }
  /**
   * Load initials data for Reserve view
   *
   * @Route("/datos-asientos", name="reserve_seats_data", options={"expose"=true})
   * @Method("POST")
   */
  public function getSeatsAction(Request $request){
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection = $request->get('reserveData');
      $reserveBussinessObj = new ReserveBussiness($em);
      $initialsData = $reserveBussinessObj->loadSeats($parametersCollection);
      $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

      return new JsonResponse(array('seatsData' => $initialsData));
    }
  }

  /**
   * Enable and disable seats for booking
   *
   * @Route("/habilitar-asientos", name="enable_seats_to_sale", options={"expose"=true})
   * @Method("POST")
   */
  public function enableDisableSeatsAction(Request $request){
    if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection['selectedSeats'] = $request->get('selectedSeats');
      $parametersCollection['unselectedSeats'] = $request->get('unselectedSeats');
      $parametersCollection['showid'] = $request->get('showid');
      $reserveBussinessObj = new ReserveBussiness($em);

      $result = $reserveBussinessObj->enableDisableSeats($parametersCollection);

      return new JsonResponse($result);
    }
  }

  /**
   * Booking sale process
   *
   * @Route("/reservar-asientos", name="save_booking_data", options={"expose"=true})
   * @Method("POST")
   */
  public function saveBookingDataAction(Request $request){
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection = $request->get('bookingData');
      $reserveBussinessObj = new ReserveBussiness($em);

      $result = $reserveBussinessObj->saveBookingData($parametersCollection);
      return new JsonResponse($result);
    }
}
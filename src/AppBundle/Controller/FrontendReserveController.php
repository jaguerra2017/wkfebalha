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
 * FRONTEND - Reserve controller.
 *
 * @Route("/reserve")
 */
class FrontendReserveController extends Controller
{
  /**
   * Load initials data for Jewels view
   *
   * @Route("/datos-iniciales", name="frontend_reserve_initials_data", options={"expose"=true})
   * @Method("POST")
   */
  public function loadReserveInitialsDataAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection = $request->get('reserveData');
      $initialsData = $this->get('appbundle_program_booking')->loadReserveInitialsData($parametersCollection);

      return new JsonResponse(array('initialsData' => $initialsData));
  }
  /**
   * Load initials data for Reserve view
   *
   * @Route("/datos-asientos", name="frontend_reserve_seats_data", options={"expose"=true})
   * @Method("POST")
   */
  public function getSeatsAction(Request $request){
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection = $request->get('reserveData');
      $initialsData = $this->get('appbundle_program_booking')->getSeats($parametersCollection);

      return new JsonResponse(array('seatsData' => $initialsData));
  }

  /**
   * Booking sale process
   *
   * @Route("/reservar-asientos", name="frontend_save_booking_data", options={"expose"=true})
   * @Method("POST")
   */
  public function saveBookingDataAction(Request $request){
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection = $request->get('bookingData');
      $result = $this->get('appbundle_program_booking')->saveBookingData($parametersCollection);
      return new JsonResponse($result);
    }
}
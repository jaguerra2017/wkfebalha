<?php

namespace AppBundle\Controller;

use AppBundle\Bussiness\ProgramBussiness;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * BACKEND - Program controller.
 *
 * @Route("backend/program")
 */
class BackendProgramController extends Controller
{
  /**
   * Load initials data for Program view
   *
   * @Route("/datos-iniciales", name="program_initials_data", options={"expose"=true})
   * @Method("POST")
   */
  public function loadProgramInitialsDataAction(Request $request)
  {
    if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
      throw $this->createAccessDeniedException();
    }
    else {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection = $request->get('programData');
      $programBussinessObj = new ProgramBussiness($em);
      $initialsData = $programBussinessObj->loadInitialsData($parametersCollection);


      $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

      return new JsonResponse(array('initialsData' => $initialsData));
    }
  }
  /**
   * Load initials data for Program view
   *
   * @Route("/datos-asientos", name="program_seats_data", options={"expose"=true})
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
      $parametersCollection = $request->get('programData');
      $programBussinessObj = new ProgramBussiness($em);
      $initialsData = $programBussinessObj->loadSeats($parametersCollection);
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
      $programBussinessObj = new ProgramBussiness($em);

      $result = $programBussinessObj->enableDisableSeats($parametersCollection);

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
      $programBussinessObj = new ProgramBussiness($em);

      $result = $programBussinessObj->saveBookingData($parametersCollection);
      return new JsonResponse($result);
    }
}
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
 * FRONTEND - Program controller.
 *
 * @Route("/program")
 */
class FrontendProgramController extends Controller
{
  /**
   * Load initials data for Program view
   *
   * @Route("/datos-iniciales", name="frontend_program_initials_data", options={"expose"=true})
   * @Method("POST")
   */
  public function loadProgramInitialsDataAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $parametersCollection = array();
      $parametersCollection = $request->get('programData');
      $initialsData = $this->get('appbundle_program_booking')->loadProgramInitialsData($parametersCollection);

      return new JsonResponse(array('initialsData' => $initialsData));
  }
}
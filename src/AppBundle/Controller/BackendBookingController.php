<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * BACKEND - Booking controller.
 *
 * @Route("backend/reservar")
 */
class BackendBookingController extends Controller
{

    /**
     * Return the Booking View
     *
     * @Route("/", name="booking_index")
     * @Security("is_granted('read-booking', 'booking')")
     * @Method("GET")
     */
    public function bookingViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Booking/index.html.twig');
        }

    }
}
<?php

namespace AppBundle\Controller;

use AppBundle\Bussiness\BookingBussiness;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * BACKEND - Check Booking controller.
 *
 * @Route("backend/lista-reservas")
 */
class BackendCheckBookingController extends Controller
{

    /**
     * Return the Check Booking View
     *
     * @Route("/", name="check_booking_index")
     * @Security("is_granted('read', 'check-booking')")
     * @Method("GET")
     */
    public function checkbookingViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/CheckBooking/index.html.twig');
        }
    }

      /**
       * Load initials data for Booking view
       *
       * @Route("/datos-iniciales", name="check_booking_view_initials_data", options={"expose"=true})
       * @Security("is_granted('read', 'check-booking')")
       * @Method("POST")
       */
      public function loadBookingInitialsDataAction(Request $request)
      {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
          throw $this->createAccessDeniedException();
        }
        else {
          $em = $this->getDoctrine()->getManager();
          $parametersCollection = array();
          $parametersCollection['currentLanguage'] = $request->get('currentLanguage') ? $request->get('currentLanguage') : $this->container->getParameter('app.default_locale');

          $bookingsBussinessObj = new BookingBussiness($em);
          $initialsData = $bookingsBussinessObj->loadInitialsData($parametersCollection);

          $parametersCollection = new \stdClass();
          $parametersCollection->filterByTreeSlug = true;
          $parametersCollection->treeSlug = 'post-status';

          $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();
          $initialsData['languages'] = $this->container->getParameter('app.avaiableLanguajes');

          return new JsonResponse(array('initialsData' => $initialsData));
        }
      }

    /**
     * Load headquarters collection data
     *
     * @Route("/datos-reservas", name="bookings_data", options={"expose"=true})
     * @Security("is_granted('read', 'check-booking')")
     * @Method("POST")
     */
    public function loadBookingDataAction(Request $request)
    {
      if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
      {
        throw $this->createAccessDeniedException();
      }
      else {
        $parametersCollection = array();
        $parametersCollection['currentLanguage'] = $request->get('currentLanguage') ? $request->get('currentLanguage') : $this->container->getParameter('app.default_locale');
        $parametersCollection['searchValue'] = $request->get('generalSearchValue');
        $parametersCollection['singleResult'] = $request->get('singleResult');
        $parametersCollection['bookingId'] = $request->get('bookingId');
        $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

        $parametersCollection['returnByCustomOrder'] = true;
        $parametersCollection['customOrderField'] = 'published_date';
        $parametersCollection['customOrderSort'] = 'DESC';

        $em = $this->getDoctrine()->getManager();
        $bookingsBussinessObj = new BookingBussiness($em);
        $bookingsDataCollection = $bookingsBussinessObj->getBookingsList($parametersCollection);
        if(isset($parametersCollection['singleResult'])){
          return new JsonResponse(array('bookingData' => $bookingsDataCollection));
        }
        return new JsonResponse(array('bookingsDataCollection' => $bookingsDataCollection));
      }
    }
}
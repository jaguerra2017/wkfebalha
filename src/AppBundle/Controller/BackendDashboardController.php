<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\DashboardBussiness;



/**
 * BACKEND - Dashboard controller.
 *
 * @Route("backend/dashboard")
 */
class BackendDashboardController extends Controller
{

    /**
     * @Route("/", name="dashboard_index")
     * @Method("GET")
     */
    public function dashboardViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Dashboard/index.html.twig');
        }

    }

    /**
     * Load initials data for Dashboard view
     *
     * @Route("/datos-iniciales", name="dashboard_view_initials_data", options={"expose"=true})
     * @Method("POST")
     */
    public function loadDashboardInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();

            $dashboardBussinessObj = new DashboardBussiness($em);
            $initialsData = $dashboardBussinessObj->loadInitialsData($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

}
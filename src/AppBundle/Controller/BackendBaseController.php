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
 * BACKEND - Base controller.
 *
 * @Route("/backend")
 */
class BackendBaseController extends Controller
{

    /**
     * @Route("/", name="backend_index")
     * @Method("GET")
     */
    public function backendViewAction()
    {
        return $this->redirectToRoute('dashboard_index');
    }

}
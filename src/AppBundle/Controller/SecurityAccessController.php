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
 * Security Access controller.
 *
 *
 */
class SecurityAccessController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        if( $this->isGranted('IS_AUTHENTICATED_FULLY') ||
            $this->isGranted ('IS_AUTHENTICATED_REMEMBERED') ){
            return $this->redirectToRoute('dashboard_index');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@app_backend_template_directory/SecurityAccess/index.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }


}

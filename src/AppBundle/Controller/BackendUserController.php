<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\UsersBussiness;


/**
 * BACKEND - User controller.
 *
 * @Route("backend/usuarios")
 */
class BackendUserController extends Controller
{

    /**
     * Return the Users Management View
     *
     * @Route("/", name="users_index")
     * @Security("is_granted('read', 'users')")
     * @Method("GET")
     */
    public function usersViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/User/index.html.twig');
        }

    }

    /**
     * Load user data
     *
     * @Route("/datos-usuario", name="users_user_data", options={"expose"=true})
     * @Security("is_granted('read', 'users')")
     * @Method("POST")
     */
    public function loadUserDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['searchByIdsCollection'] = $request->get('searchByUserId');
            $userId = $request->get('userId');

            if($parametersCollection['searchByIdsCollection'] == 'true'){
                $parametersCollection['searchByIdsCollection'] = true;
            }
            else{
                $parametersCollection['searchByIdsCollection'] = false;
            }
            if($userId == null){
                $userId = $this->getUser()->getId();
            }
            $parametersCollection['idsCollection'][0] = $userId;
            $parametersCollection['returnSingleResult'] = true;
            $parametersCollection['loggedUser'] = $this->getUser();

            $em = $this->getDoctrine()->getManager();
            $usersBussinessObj = new UsersBussiness($em);
            $userData = $usersBussinessObj->getUsersList($parametersCollection);
            return new JsonResponse(array('userData' => $userData));
        }
    }

    /**
     * Save User data (EDIT action)
     *
     * @Route("/editar-usuario", name="users_user_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'users')")
     * @Method("POST")
     */
    public function editUserDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection['id'] = $request->get('id');
            $parametersCollection['user_name'] = $request->get('user_name');
            $parametersCollection['full_name'] = $request->get('full_name');
            $parametersCollection['email'] = $request->get('email');
            $parametersCollection['role_id'] = $request->get('role_id');
            $parametersCollection['avatar_id'] = $request->get('avatar_id');
            $parametersCollection['password'] = $request->get('password');

            $parametersCollection['isCreating'] = false;
            if(isset($parametersCollection['password']) && $parametersCollection['password'] != null){
                $parametersCollection['container'] = $this->container;
            }
            $parametersCollection['loggedUser'] = $this->getUser();

            $usersBussinessObj = new UsersBussiness($em);
            $response = $usersBussinessObj->saveUserData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
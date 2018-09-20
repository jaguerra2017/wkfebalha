<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\EventsBussiness;


/**
 * BACKEND - Events controller.
 *
 * @Route("backend/eventos")
 */
class BackendEventController extends Controller
{

    /**
     * Return the Events View
     *
     * @Route("/", name="events_index")
     * @Security("is_granted('read', 'events')")
     * @Method("GET")
     */
    public function eventsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Event/index.html.twig');
        }

    }

    /**
     * Load initials data for Events view
     *
     * @Route("/datos-iniciales", name="events_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'events')")
     * @Method("POST")
     */
    public function loadEventsInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $eventsBussinessObj = new EventsBussiness($em);
            $initialsData = $eventsBussinessObj->loadInitialsData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'post-status';
            $initialsData['postStatusDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);

            $showEventsForm = false;
            if($request->getSession()->get('directAccessToCreate') == true){
                $showEventsForm = true;
                $request->getSession()->remove('directAccessToCreate');
            }
            $initialsData['showEventsForm'] = $showEventsForm;

            $initialsData['bncDomain'] = $this->container->get('appbundle_site_settings')->getBncDomain();

            $parametersCollection = array();
            $parametersCollection['taxonomyTypeTreeSlug'] = 'event-category';
            $parametersCollection['returnDataInTree'] = true;
            $initialsData['categoriesDataCollection'] = $this->container->get('appbundle_taxonomies')->getTaxonomies($parametersCollection);

            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Load events collection data
     *
     * @Route("/datos-eventos", name="events_data", options={"expose"=true})
     * @Security("is_granted('read', 'events')")
     * @Method("POST")
     */
    public function loadEventsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['singleResult'] = $request->get('singleResult');
            $parametersCollection['eventId'] = $request->get('eventId');
            $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');

            $em = $this->getDoctrine()->getManager();
            $eventsBussinessObj = new EventsBussiness($em);
            $eventsDataCollection = $eventsBussinessObj->getEventsList($parametersCollection);
            if(isset($parametersCollection['singleResult'])){
                return new JsonResponse(array('eventData' => $eventsDataCollection));
            }
            return new JsonResponse(array('eventsDataCollection' => $eventsDataCollection));
        }
    }

    /**
     * Save Event data (CREATE action)
     *
     * @Route("/crear", name="events_create", options={"expose"=true})
     * @Security("is_granted('create', 'events')")
     *
     */
    public function createEventAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                $parametersCollection = $request->get('eventData');
                //print_r($parametersCollection);die;
                $parametersCollection['isCreating'] = true;
                $parametersCollection['loggedUser'] = $this->getUser();

                $eventsBussinessObj = new EventsBussiness($em);
                $response = $eventsBussinessObj->saveEventData($parametersCollection);
                return new JsonResponse($response);
            }
            else{
                $request->getSession()->set('directAccessToCreate', true);
                return $this->redirectToRoute('events_index');
            }
        }
    }

    /**
     * Save Event data (EDIT action)
     *
     * @Route("/editar", name="events_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'events')")
     * @Method("POST")
     */
    public function editEventAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('eventData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $eventsBussinessObj = new EventsBussiness($em);
            $response = $eventsBussinessObj->saveEventData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Events
     *
     * @Route("/eliminar-eventos", name="events_delete", options={"expose"=true})
     * @Security("is_granted('delete', 'events')")
     * @Method("POST")
     */
    public function deleteEventsAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['eventsId'] = $request->get('eventsId');

            $eventsBussinessObj = new EventsBussiness($em);
            $response = $eventsBussinessObj->deleteEventsData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
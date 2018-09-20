<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * FRONTEND - Post (News) controller.
 *
 * @Route("data-handler/events")
 */
class FrontendEventController extends Controller
{

    /**
     *
     * @Route("/", name="dh_events_collection", options={"expose"=true})
     * @Method("POST")
     */
    public function dhEventsCollecctionAction(Request $request)
    {
        $response = array();
        $parametersCollection = array();
        if($request->getSession()->get('current_generic_post_id') != null){
            $parametersCollection['singleResult'] = true;
            $parametersCollection['eventId'] = $request->getSession()->get('current_generic_post_id');
            $request->getSession()->remove('current_generic_post_id');
            $response['singleResult'] = true;
        }
        else{
            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['start'] = $request->get('start');
            $parametersCollection['end'] = $request->get('end');
            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'gp.published_date';
            $parametersCollection['customOrderSort'] = 'DESC';
        }

        $parametersCollection['post_type_tree_slug'] = 'event';
        $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
        $parametersCollection['container'] = $this->container;
        $parametersCollection['searchByPostStatusSlug'] = true;
        $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
        $eventsCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
        $response['eventsDataCollection'] = $eventsCollection;

        if(isset($parametersCollection['searchByPagination']) && $parametersCollection['searchByPagination'] == true){
            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['getFullTotal'] = true;
            $totalEvents = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['totalPosts'] = $totalEvents;
        }




        return new JsonResponse($response);
    }

}

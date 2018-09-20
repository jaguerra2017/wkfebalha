<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * FRONTEND - Jewel controller.
 *
 * @Route("data-handler/jewels")
 */
class FrontendJewelController extends Controller
{

    /**
     * Load initials data for Jewels view
     *
     * @Route("/", name="dh_jewels_collection", options={"expose"=true})
     * @Method("POST")
     */
    public function dhJewelsCollecctionAction(Request $request)
    {
        $response = array();
        $parametersCollection = array();
        if($request->getSession()->get('current_generic_post_id') != null){
            $parametersCollection['singleResult'] = true;
            $parametersCollection['jewelId'] = $request->getSession()->get('current_generic_post_id');
            $request->getSession()->remove('current_generic_post_id');
            $response['singleResult'] = true;
        }
        else{
            $parametersCollection['searchByPagination'] = true;
            $parametersCollection['start'] = $request->get('start');
            $parametersCollection['end'] = $request->get('end');
            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';
        }

        $parametersCollection['post_type_tree_slug'] = 'jewel';
        $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
        $parametersCollection['container'] = $this->container;
        $parametersCollection['searchByPostStatusSlug'] = true;
        $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
        $jewelsCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
        $response['jewelsDataCollection'] = $jewelsCollection;

        if(isset($parametersCollection['searchByPagination']) && $parametersCollection['searchByPagination'] == true){
            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['getFullTotal'] = true;
            $totalJewels = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['totalJewels'] = $totalJewels;
        }



        return new JsonResponse($response);
    }

}

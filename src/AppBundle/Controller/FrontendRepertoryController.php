<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * FRONTEND - Publication controller.
 *
 * @Route("data-handler/repertory")
 */
class FrontendRepertoryController extends Controller
{

    /**
     * Load initials data for Repertory view
     *
     * @Route("/", name="dh_repertory_collection", options={"expose"=true})
     * @Method("POST")
     */
    public function dhRepertoryCollecctionAction(Request $request)
    {
        $response = array();
        $parametersCollection = array();
        if($request->getSession()->get('current_generic_post_id') != null){
            $parametersCollection['singleResult'] = true;
            $parametersCollection['repertoryId'] = $request->getSession()->get('current_generic_post_id');
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

        $parametersCollection['post_type_tree_slug'] = 'repertory';
        $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
        $parametersCollection['container'] = $this->container;
        $parametersCollection['searchByPostStatusSlug'] = true;
        $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
        $repertoryCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
        $response['repertoryDataCollection'] = $repertoryCollection;

        if(isset($parametersCollection['searchByPagination']) && $parametersCollection['searchByPagination'] == true){
            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['getFullTotal'] = true;
            $totalRepertory = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['totalRepertory'] = $totalRepertory;
        }



        return new JsonResponse($response);
    }

}

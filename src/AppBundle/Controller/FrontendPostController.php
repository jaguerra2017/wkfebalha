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
 * @Route("data-handler/posts")
 */
class FrontendPostController extends Controller
{

    /**
     * Load initials data for Awards view
     *
     * @Route("/", name="dh_posts_collection", options={"expose"=true})
     * @Method("POST")
     */
    public function dhPostsCollecctionAction(Request $request)
    {
        $response = array();
        $parametersCollection = array();
        if($request->getSession()->get('current_generic_post_id') != null){
            $parametersCollection['singleResult'] = true;
            $parametersCollection['newId'] = $request->getSession()->get('current_generic_post_id');
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

        $parametersCollection['post_type_tree_slug'] = 'post';
        $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
        $parametersCollection['container'] = $this->container;
        $parametersCollection['searchByPostStatusSlug'] = true;
        $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
        $postCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
        $response['newsDataCollection'] = $postCollection;

        if(isset($parametersCollection['searchByPagination']) && $parametersCollection['searchByPagination'] == true){
            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['getFullTotal'] = true;
            $totalPosts = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['totalPosts'] = $totalPosts;
        }



        return new JsonResponse($response);
    }

}

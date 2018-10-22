<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * FRONTEND - Show controller.
 *
 * @Route("data-handler/show")
 */
class FrontendShowController extends Controller
{

    /**
     * Load initials data for Awards view
     *
     * @Route("/", name="dh_shows_collection", options={"expose"=true})
     * @Method("POST")
     */
    public function dhShowsCollecctionAction(Request $request)
    {
        $response = array();
        $parametersCollection = array();
        $parametersCollection['currentLanguage'] = $request->getSession()->get('_locale');
        if($request->getSession()->get('current_generic_post_id') != null){
            $parametersCollection['singleResult'] = true;
            $parametersCollection['showId'] = $request->getSession()->get('current_generic_post_id');
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

        $parametersCollection['post_type_tree_slug'] = 'show';
        $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
        $parametersCollection['container'] = $this->container;
        $parametersCollection['searchByShowStatusSlug'] = true;
        $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
        $showCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
        $response['showsDataCollection'] = $showCollection;

        if(isset($parametersCollection['searchByPagination']) && $parametersCollection['searchByPagination'] == true){
            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['getFullTotal'] = true;
            $totalShows = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['totalShows'] = $totalShows;
        }



        return new JsonResponse($response);
    }

}

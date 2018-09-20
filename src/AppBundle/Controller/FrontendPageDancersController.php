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
 * FRONTEND - Custom Page controller for Custom Page DANCERS.
 *
 * @Route("data-handler/custom-page/dancers")
 */
class FrontendPageDancersController extends Controller
{

    /**
     * Load initials data
     *
     * @Route("/", name="dh_page_dancers_collection", options={"expose"=true})
     * @Method("POST")
     */
    public function dhPageDancersCollecctionAction(Request $request)
    {
        $response = array();
        $parametersCollection = array();
        $parametersCollection['searchByPostStatusSlug'] = true;
        $parametersCollection['postStatusSlug'] = 'generic-post-status-published';

        if($request->getSession()->get('current_generic_post_id') != null){
            $parametersCollection['singleResult'] = true;
            $parametersCollection['pageId'] = $request->getSession()->get('current_generic_post_id');
            $request->getSession()->remove('current_generic_post_id');
            $response['singleResult'] = true;

            if($request->getSession()->get('current_tag') != null){
                $response['current_tag'] = $request->getSession()->get('current_tag');
                $request->getSession()->remove('current_tag');
            }
            $parametersCollection['post_type_tree_slug'] = 'page';
            $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
            $parametersCollection['container'] = $this->container;
            $pagesCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['pagesDataCollection'] = $pagesCollection;
        }

        /*handling BNC Members*/
        $loadComposition = $request->get('load_composition');
        if(isset($loadComposition) && $loadComposition == 'true'){
            $parametersCollection['singleResult'] = false;
            $parametersCollection['post_type_tree_slug'] = 'composition';
            $parametersCollection['searchByPostStatusSlug'] = true;
            $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
            $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
            $parametersCollection['container'] = $this->container;
            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $compositionCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['compositionCollection'] = $compositionCollection;
        }

        return new JsonResponse($response);
    }

}

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
 * FRONTEND - Pages controller.
 *
 * @Route("data-handler/pages")
 */
class FrontendPageController extends Controller
{

    /**
     * Load initials data
     *
     * @Route("/", name="dh_pages_collection", options={"expose"=true})
     * @Method("POST")
     */
    public function dhPagesCollecctionAction(Request $request)
    {
        $response = array();
        $parametersCollection = array();
        if($request->getSession()->get('current_generic_post_id') != null){
            $parametersCollection['singleResult'] = true;
            $parametersCollection['pageId'] = $request->getSession()->get('current_generic_post_id');
            $request->getSession()->remove('current_generic_post_id');
            $response['singleResult'] = true;

            if($request->getSession()->get('current_tag') != null){
                $response['current_tag'] = $request->getSession()->get('current_tag');
                $request->getSession()->remove('current_tag');
            }
        }
        else{
            $parametersCollection['searchByPagination'] = true;
            $parametersCollection['start'] = $request->get('start');
            $parametersCollection['end'] = $request->get('end');
            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';
        }

        $parametersCollection['post_type_tree_slug'] = 'page';
        $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
        $parametersCollection['container'] = $this->container;
        $parametersCollection['searchByPostStatusSlug'] = true;
        $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
        $pagesCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
        $response['pagesDataCollection'] = $pagesCollection;

        if(isset($parametersCollection['searchByPagination']) && $parametersCollection['searchByPagination'] == true){
            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['getFullTotal'] = true;
            $totalPages = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['totalPages'] = $totalPages;
        }



        return new JsonResponse($response);
    }

    /**
     * Load data for Default Page Template
     *
     * @Route("/", name="dh_pages_default_page_data", options={"expose"=true})
     * @Method("POST")
     */
    public function dhPagesDefaultPageAction(Request $request)
    {
        $response = array();
        $parametersCollection = array();
        if($request->getSession()->get('current_generic_post_id') != null){
            $parametersCollection['singleResult'] = true;
            $parametersCollection['pageId'] = $request->getSession()->get('current_generic_post_id');
            $request->getSession()->remove('current_generic_post_id');
            $response['singleResult'] = true;

            if($request->getSession()->get('current_tag') != null){
                $response['current_tag'] = $request->getSession()->get('current_tag');
                $request->getSession()->remove('current_tag');
            }
        }


        $parametersCollection['post_type_tree_slug'] = 'page';
        $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
        $parametersCollection['container'] = $this->container;
        $parametersCollection['searchByPostStatusSlug'] = true;
        $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
        $pagesCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
        $response['pagesDataCollection'] = $pagesCollection;

        return new JsonResponse($response);
    }

}

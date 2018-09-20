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
 * FRONTEND - Custom Page controller for Custom Page COMPANY.
 *
 * @Route("data-handler/custom-page/company")
 */
class FrontendPageCompanyController extends Controller
{

    /**
     * Load initials data
     *
     * @Route("/", name="dh_page_company_collection", options={"expose"=true})
     * @Method("POST")
     */
    public function dhPageCompanyCollecctionAction(Request $request)
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

        /*handling Historical Moments*/
        $loadHistoricalMoments = $request->get('load_historical_moments');
        if(isset($loadHistoricalMoments) && $loadHistoricalMoments == 'true'){
            $parametersCollection['singleResult'] = false;
            $parametersCollection['post_type_tree_slug'] = 'historical-moment';
            $parametersCollection['searchByPostStatusSlug'] = true;
            $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
            $parametersCollection['view'] = 'timeline_list';
            $parametersCollection['imagineCacheManager'] = null;
            $parametersCollection['container'] = null;

            $historicalMomentsCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['historicalMomentsCollection'] = $historicalMomentsCollection;
        }

        /*handling Jewels*/
        $loadJewels = $request->get('load_jewels');
        if(isset($loadJewels) && $loadJewels == 'true'){
            $parametersCollection['singleResult'] = false;
            $parametersCollection['post_type_tree_slug'] = 'jewel';
            $parametersCollection['searchByPostStatusSlug'] = true;
            $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
            $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
            $parametersCollection['container'] = $this->container;
            $parametersCollection['searchByPagination'] = true;
            $parametersCollection['start'] = 0;
            $parametersCollection['end'] = 4;
            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';

            $jewelsCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['jewelsCollection'] = $jewelsCollection;
        }

        return new JsonResponse($response);
    }

}

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
 * FRONTEND - Custom Page controller for Custom Page ALICIA.
 *
 * @Route("data-handler/custom-page/alicia")
 */
class FrontendPageAliciaController extends Controller
{

    /**
     * Load initials data
     *
     * @Route("/", name="dh_page_alicia_collection", options={"expose"=true})
     * @Method("POST")
     */
    public function dhPageAliciaCollecctionAction(Request $request)
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

        $em = $this->getDoctrine()->getManager();

        /*handling National Awards*/
        $loadNationalAwards = $request->get('load_na');
        if(isset($loadNationalAwards) && $loadNationalAwards == 'true'){
            $parametersCollection['singleResult'] = false;
            $parametersCollection['post_type_tree_slug'] = 'award';
            $parametersCollection['searchByPostStatusSlug'] = true;
            $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
            $parametersCollection['searchByPagination'] = true;
            $parametersCollection['start'] = $request->get('na_start');
            $parametersCollection['end'] = $request->get('na_end');

            $parametersCollection['imagineCacheManager'] = null;
            $parametersCollection['container'] = null;
            $objTax = $em->getRepository('AppBundle:Taxonomy')->findOneBy(array(
                'tree_slug' => 'award-category-distincion-nacional-de-alicia-alonso'
            ));
            if(isset($objTax)){
                $parametersCollection['searchByTaxonomy'] = true;
                $parametersCollection['taxonomyIds'] = array();
                $parametersCollection['taxonomyIds'][0] = $objTax->getId();
                $parametersCollection['taxonomyIds'] = implode(',', $parametersCollection['taxonomyIds']);
            }
            $nationalAwardsCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['nationalAwardsCollection'] = $nationalAwardsCollection;

            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['getFullTotal'] = true;
            $totalNa = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['totalNa'] = $totalNa;
        }

        /*handling International Awards*/
        $loadInternationalAwards = $request->get('load_ia');
        if(isset($loadInternationalAwards) && $loadInternationalAwards == 'true'){
            $parametersCollection['singleResult'] = false;
            $parametersCollection['post_type_tree_slug'] = 'award';
            $parametersCollection['searchByPostStatusSlug'] = true;
            $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
            $parametersCollection['searchByPagination'] = true;
            $parametersCollection['getFullTotal'] = false;
            $parametersCollection['start'] = $request->get('ia_start');
            $parametersCollection['end'] = $request->get('ia_end');

            $parametersCollection['imagineCacheManager'] = null;
            $parametersCollection['container'] = null;
            $objTax = $em->getRepository('AppBundle:Taxonomy')->findOneBy(array(
                'tree_slug' => 'award-category-distincion-internacional-alicia-alonso'
            ));
            if(isset($objTax)){
                $parametersCollection['searchByTaxonomy'] = true;
                $parametersCollection['taxonomyIds'] = array();
                $parametersCollection['taxonomyIds'][0] = $objTax->getId();
                $parametersCollection['taxonomyIds'] = implode(',', $parametersCollection['taxonomyIds']);
            }
            $internationalAwardsCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['internationalAwardsCollection'] = $internationalAwardsCollection;

            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['getFullTotal'] = true;
            $totalIa = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['totalIa'] = $totalIa;
        }

        /*handling Latam Awards*/
        $loadLatamAwards = $request->get('load_la');
        if(isset($loadLatamAwards) && $loadLatamAwards == 'true'){
            $parametersCollection['singleResult'] = false;
            $parametersCollection['post_type_tree_slug'] = 'award';
            $parametersCollection['searchByPostStatusSlug'] = true;
            $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
            $parametersCollection['searchByPagination'] = true;
            $parametersCollection['getFullTotal'] = false;
            $parametersCollection['start'] = $request->get('la_start');
            $parametersCollection['end'] = $request->get('la_end');

            $parametersCollection['imagineCacheManager'] = null;
            $parametersCollection['container'] = null;
            $objTax = $em->getRepository('AppBundle:Taxonomy')->findOneBy(array(
                'tree_slug' => 'award-category-distincion-america-latina-alicia-alonso'
            ));
            if(isset($objTax)){
                $parametersCollection['searchByTaxonomy'] = true;
                $parametersCollection['taxonomyIds'] = array();
                $parametersCollection['taxonomyIds'][0] = $objTax->getId();
                $parametersCollection['taxonomyIds'] = implode(',', $parametersCollection['taxonomyIds']);
            }
            $latamAwardsCollection = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['latamAwardsCollection'] = $latamAwardsCollection;

            $parametersCollection['searchByPagination'] = false;
            $parametersCollection['getFullTotal'] = true;
            $totalLa = $this->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            $response['totalLa'] = $totalLa;
        }



        return new JsonResponse($response);
    }

}

<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\BlocksBussiness;
use AppBundle\Bussiness\MediaBussiness;
use AppBundle\Bussiness\OpinionsBussiness;


/**
 * FRONTEND - Blocks controller.
 *
 * @Route("data-handler/bloques")
 */
class FrontendBlockController extends Controller
{

    

    /**
     * Load blocks collection data
     *
     * @Route("/datos-bloques", name="dh_blocks_data", options={"expose"=true})
     * @Method("POST")
     */
    public function loadBlocksDataAction(Request $request)
    {
        $parametersCollection = array();
        $parametersCollection['genericPostId'] = $request->get('genericPostId');
        $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');
        $firstLoad = $request->get('firstLoad');

        $em = $this->getDoctrine()->getManager();
        $blocksBussinessObj = new BlocksBussiness($em);
        $blocksDataCollection = $blocksBussinessObj->getBlocksList($parametersCollection);
        $responseCollection['blocksDataCollection'] = $blocksDataCollection;
        if(isset($firstLoad) && $firstLoad == 'true'){
            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'content-block-type';
            $responseCollection['blockTypesDataCollection'] = $this->container->get('appbundle_nomenclatures')->getNomenclatures($parametersCollection);
        }
        return new JsonResponse($responseCollection);
    }

    /**
     * Load block elements collection data
     *
     * @Route("/datos-elementos-bloque", name="dh_blocks_elements_data", options={"expose"=true})
     * @Method("POST")
     */
    public function loadBlockElementsDataAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $blockElementsDataCollection = array();
        $parametersCollection = array();
        $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
        $parametersCollection['blockElementType'] = $request->get('blockElementType');
        if(isset($parametersCollection['blockElementType']) && $parametersCollection['blockElementType'] != null){
            switch($parametersCollection['blockElementType']){
                case 'content-block-type-opinion':
                    $opinionsBussinessObj = new OpinionsBussiness($em);
                    $blockElementsDataCollection = $opinionsBussinessObj->getOpinionsList($parametersCollection);
                    break;
                default:
                    /*content-block-type-media-gallery &&
                     content-block-type-media-image &&
                     content-block-type-media-video
                    */
                    $mediaType = 'image';
                    if($parametersCollection['blockElementType'] == 'content-block-type-media-video'){
                        $mediaType = 'video';
                    }
                    else if($parametersCollection['blockElementType'] == 'content-block-type-media-gallery'){
                        $mediaType = 'gallery';
                    }
                    $parametersCollection['mediaType'] =  $mediaType;
                    $parametersCollection['imagineCacheManager'] = $this->get('liip_imagine.cache.manager');
                    $mediaBussinessObj = new MediaBussiness($em);
                    $blockElementsDataCollection = $mediaBussinessObj->getMediaData($parametersCollection);

            }
        }
        $responseCollection['blockElementsDataCollection'] = $blockElementsDataCollection;

        return new JsonResponse($responseCollection);
    }

}
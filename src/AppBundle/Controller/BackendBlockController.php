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
 * BACKEND - Blocks controller.
 *
 * @Route("backend/bloques")
 */
class BackendBlockController extends Controller
{

    

    /**
     * Load blocks collection data
     *
     * @Route("/datos-bloques", name="blocks_data", options={"expose"=true})
     * @Method("POST")
     */
    public function loadBlocksDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
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
    }

    /**
     * Load block elements collection data
     *
     * @Route("/datos-elementos-bloque", name="blocks_elements_data", options={"expose"=true})
     * @Method("POST")
     */
    public function loadBlockElementsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $blockElementsDataCollection = array();
            $parametersCollection = array();
            $parametersCollection['generalSearchValue'] = $request->get('generalSearchValue');
            $parametersCollection['blockElementType'] = $request->get('blockElementType');
            if(isset($parametersCollection['blockElementType']) && $parametersCollection['blockElementType'] != null){
                switch($parametersCollection['blockElementType']){
                    case 'content-block-type-opinion':
                        $parametersCollection['searchByTaxonomy'] = $request->get('searchByTaxonomies');
                        if(isset($parametersCollection['searchByTaxonomy']) && $parametersCollection['searchByTaxonomy'] == 'true'){
                            $parametersCollection['searchByTaxonomy'] = true;
                            $parametersCollection['taxonomyIds'] = $request->get('taxonomieIdsCollection');
                        }

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

    /**
     * Save Block data (CREATE action)
     *
     * @Route("/crear", name="blocks_create", options={"expose"=true})
     * @Method("POST")
     */
    public function createBlockAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('blockData');
            $parametersCollection['isCreating'] = true;
            $parametersCollection['loggedUser'] = $this->getUser();

            $blocksBussinessObj = new BlocksBussiness($em);
            $response = $blocksBussinessObj->saveBlockData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Block data (EDIT action)
     *
     * @Route("/editar", name="blocks_edit", options={"expose"=true})
     * @Method("POST")
     */
    public function editBlockAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('blockData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $blocksBussinessObj = new BlocksBussiness($em);
            $response = $blocksBussinessObj->saveBlockData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Change Block priority
     *
     * @Route("/cambiar-prioridad", name="blocks_change_priority", options={"expose"=true})
     * @Method("POST")
     */
    public function editBlockPriorityAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection['currentPriority'] = $request->get('currentPriority');
            $parametersCollection['desiredPriority'] = $request->get('desiredPriority');
            $parametersCollection['blockId'] = $request->get('blockId');
            $parametersCollection['loggedUser'] = $this->getUser();

            $blocksBussinessObj = new BlocksBussiness($em);
            $response = $blocksBussinessObj->changeBlockPriority($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Blocks
     *
     * @Route("/eliminar-publicacion", name="blocks_delete", options={"expose"=true})
     * @Method("POST")
     */
    public function deleteBlocksAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['blocksId'] = $request->get('blocksId');

            $blocksBussinessObj = new BlocksBussiness($em);
            $response = $blocksBussinessObj->deleteBlocksData($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
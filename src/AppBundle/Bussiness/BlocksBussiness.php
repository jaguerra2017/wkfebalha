<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\ContentBlockGenericPostItem;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Entity\GenericPost;
use AppBundle\Bussiness\NomenclatureBussiness;
use AppBundle\Entity\GenericPostNomenclature;
use AppBundle\Entity\GenericPostTaxonomy;
use AppBundle\Entity\ContentBlock;
use AppBundle\Entity\ContentBlockGallery;
use AppBundle\Entity\ContentBlockMedia;



class BlocksBussiness
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getBlocksList($parametersCollection)
    {
        try{
            if(isset($parametersCollection['genericPostId']) && $parametersCollection['genericPostId'] != null){
                $parametersCollection['searchByGenericPost'] = true;
            }
            $blocksCollection = $this->em->getRepository('AppBundle:ContentBlock')->getContentBlocks($parametersCollection);
            if(isset($blocksCollection[0])){
                foreach($blocksCollection as $key=>$block){
                    $canEdit = 1;
                    $canDelete = 1;
                    $blocksCollection[$key]['canEdit'] = $canEdit;
                    $blocksCollection[$key]['canDelete'] = $canDelete;

                    /*handling Block Elements*/
                    $blockElementsDataCollection = array();
                    $objContentBlock = $this->em->getRepository('AppBundle:ContentBlock')->find($block['id']);
                    if(isset($objContentBlock)){
                        switch($block['block_type_tree_slug']){
                            case 'content-block-type-opinion':
                                $contentBlockOpinionsCollection = $this->em->getRepository('AppBundle:ContentBlockGenericPostItem')->findBy(array(
                                    'content_block' => $objContentBlock
                                ));
                                if(isset($contentBlockOpinionsCollection[0])){
                                    $idsCollection = array();
                                    foreach($contentBlockOpinionsCollection as $pos=>$contentBlockOpinion){
                                        $idsCollection[$pos] = $contentBlockOpinion->getGenericPost()->getId();
                                    }
                                    $idsCollection = implode(',', $idsCollection);
                                    $opinionBussinessObj = new OpinionsBussiness($this->em);
                                    $blockElementsDataCollection = $opinionBussinessObj->getOpinionsList(array(
                                        'searchByIdsCollection' => true,
                                        'idsCollection' => $idsCollection
                                    ));
                                }
                                break;
                            case 'content-block-type-media-gallery':
                                $contentBlockGalleriesCollection = $this->em->getRepository('AppBundle:ContentBlockGallery')->findBy(array(
                                    'content_block' => $objContentBlock
                                ));
                                if(isset($contentBlockGalleriesCollection[0])){
                                    $idsCollection = array();
                                    foreach($contentBlockGalleriesCollection as $pos=>$contentBlockGallery){
                                        $idsCollection[$pos] = $contentBlockGallery->getGallery()->getId();
                                    }
                                    $idsCollection = implode(',', $idsCollection);
                                    $mediaBussinessObj = new MediaBussiness($this->em);
                                    $blockElementsDataCollection = $mediaBussinessObj->getMediaData(array(
                                        'mediaType' => 'gallery',
                                        'imagineCacheManager' => $parametersCollection['imagineCacheManager'],
                                        'searchByIdsCollection' => true,
                                        'idsCollection' => $idsCollection
                                    ));
                                }
                                break;
                            default:
                                /*content-block-type-media-image
                                 *content-block-type-media-video
                                 * */
                                $mediaType = $block['block_type_tree_slug'] == 'content-block-type-media-image' ? 'image' : 'video';
                                $contentBlockMediasCollection = $this->em->getRepository('AppBundle:ContentBlockMedia')->findBy(array(
                                    'content_block' => $objContentBlock
                                ));
                                if(isset($contentBlockMediasCollection[0])){
                                    $idsCollection = array();
                                    foreach($contentBlockMediasCollection as $pos=>$contentBlockMedia){
                                        $idsCollection[$pos] = $contentBlockMedia->getMedia()->getId();
                                    }
                                    $idsCollection = implode(',', $idsCollection);
                                    $mediaBussinessObj = new MediaBussiness($this->em);
                                    $blockElementsDataCollection = $mediaBussinessObj->getMediaData(array(
                                        'mediaType' => $mediaType,
                                        'imagineCacheManager' => $parametersCollection['imagineCacheManager'],
                                        'searchByIdsCollection' => true,
                                        'idsCollection' => $idsCollection
                                    ));
                                }
                        }
                    }

                    $blocksCollection[$key]['elements'] = $blockElementsDataCollection;

                }
            }

            return $blocksCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveBlockData($parametersCollection){
        try{

            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($parametersCollection['generic_post_id']);
            if(!isset($objGenericPost)){
                $message = 'La publicación que desea editar ya no existe.';
                return $this->returnResponse(array('success'=>0,'message'=>$message));
            }

            $objContentBlock = $this->em->getRepository('AppBundle:ContentBlock')->findOneBy(array(
                'title_es' => $parametersCollection['title_es'],
                'generic_post' => $objGenericPost
            ));
            if(isset($objContentBlock)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objContentBlock->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe un bloque para esta publicación con ese nombre.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
            }

            /*persisting Content Block*/
            $objContentBlock = new ContentBlock();
            if($parametersCollection['isCreating'] == false){

                $objContentBlock = $this->em->getRepository('AppBundle:ContentBlock')->find($parametersCollection['id']);
                if(!isset($objContentBlock)){
                    $message = 'El bloque que desea editar ya no existe.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
                $objContentBlock->setModifiedDate(new \DateTime());
                $objContentBlock->setModifiedAuthor($parametersCollection['loggedUser']);
            }
            $objContentBlock->setTitle($parametersCollection['title_es']);
            $objContentBlock->setGenericPost($objGenericPost);
            if($parametersCollection['isCreating'] == true){
                $objContentBlock->setPriority($this->getNextPriorityValue($objGenericPost));
            }
            $objContentBlockType = $this->em->getRepository('AppBundle:Nomenclature')->find($parametersCollection['block_type_id']);
            $objContentBlock->setContentBlockType($objContentBlockType);
            $this->em->persist($objContentBlock);
            $this->em->flush();

            /*handling block elements*/
            switch($parametersCollection['block_type_tree_slug']){
                case 'content-block-type-media-gallery':
                    /*deleting existing relations*/
                    $contentBlockGalleriesCollection = $this->em->getRepository('AppBundle:ContentBlockGallery')->getContentBlockGalleries(array(
                        'searchByContentBlockId' => true,
                        'contentBlockId' => $objContentBlock->getId()
                    ));
                    if(isset($contentBlockGalleriesCollection[0])){
                        $idsCollection = array();
                        foreach($contentBlockGalleriesCollection as $key=>$contentBlockGallery){
                            $idsCollection[$key] = $contentBlockGallery['id'];
                        }
                        $idsCollection = implode(',', $idsCollection);
                        $this->em->getRepository('AppBundle:ContentBlockGallery')->deleteByIdsCollection($idsCollection);
                    }
                    /*creating new relations*/
                    if(isset($parametersCollection['elements'][0])){
                        foreach($parametersCollection['elements'] as $element){
                            $objGallery = $this->em->getRepository('AppBundle:Gallery')->find($element);
                            if(isset($objGallery)){
                                $objContentBlockGallery = new ContentBlockGallery();
                                $objContentBlockGallery->setGallery($objGallery);
                                $objContentBlockGallery->setContentBlock($objContentBlock);
                                $this->em->persist($objContentBlockGallery);
                            }
                        }
                    }
                    break;
                case 'content-block-type-opinion':
                    /*deleting existing relations*/
                    $contentBlockOpinionsCollection = $this->em->getRepository('AppBundle:ContentBlockGenericPostItem')->getContentBlockGenericPostItems(array(
                        'searchByContentBlockId' => true,
                        'contentBlockId' => $objContentBlock->getId()
                    ));
                    if(isset($contentBlockOpinionsCollection[0])){
                        $idsCollection = array();
                        foreach($contentBlockOpinionsCollection as $key=>$contentBlockOpinion){
                            $idsCollection[$key] = $contentBlockOpinion['id'];
                        }
                        $idsCollection = implode(',', $idsCollection);
                        $this->em->getRepository('AppBundle:ContentBlockGenericPostItem')->deleteByIdsCollection($idsCollection);
                    }
                    /*creating new relations*/
                    if(isset($parametersCollection['elements'][0])){
                        foreach($parametersCollection['elements'] as $element){
                            $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($element);
                            if(isset($objGenericPost)){
                                $objContentBlockOpinion = new ContentBlockGenericPostItem();
                                $objContentBlockOpinion->setGenericPost($objGenericPost);
                                $objContentBlockOpinion->setContentBlock($objContentBlock);
                                $this->em->persist($objContentBlockOpinion);
                            }
                        }
                    }
                    break;
                default:
                    /*content-block-type-media-image && content-block-type-media-video*/
                    /*deleting existing relations*/
                    $contentBlockMediasCollection = $this->em->getRepository('AppBundle:ContentBlockMedia')->getContentBlockMedias(array(
                        'searchByContentBlockId' => true,
                        'contentBlockId' => $objContentBlock->getId()
                    ));
                    if(isset($contentBlockMediasCollection[0])){
                        $idsCollection = array();
                        foreach($contentBlockMediasCollection as $key=>$contentBlockMedia){
                            $idsCollection[$key] = $contentBlockMedia['id'];
                        }
                        $idsCollection = implode(',', $idsCollection);
                        $this->em->getRepository('AppBundle:ContentBlockMedia')->deleteByIdsCollection($idsCollection);
                    }
                    /*creating new relations*/
                    if(isset($parametersCollection['elements'][0])){
                        foreach($parametersCollection['elements'] as $element){
                            $objMedia = $this->em->getRepository('AppBundle:Media')->find($element);
                            if(isset($objMedia)){
                                $objContentBlockMedia = new ContentBlockMedia();
                                $objContentBlockMedia->setMedia($objMedia);
                                $objContentBlockMedia->setContentBlock($objContentBlock);
                                $this->em->persist($objContentBlockMedia);
                            }
                        }
                    }
            }

            $this->em->flush();

            return $this->returnResponse(array('success'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function changeBlockPriority($parametersCollection){
        try{
            $currentObjBlock = $this->em->getRepository('AppBundle:ContentBlock')->find($parametersCollection['blockId']);
            if(isset($currentObjBlock)){
                $objBlock = $this->em->getRepository('AppBundle:ContentBlock')->findOneBy(array(
                    'priority' => $parametersCollection['desiredPriority']
                ));
                if(isset($objBlock)){
                    $objBlock->setPriority($parametersCollection['currentPriority']);
                    $this->em->persist($objBlock);
                }
                $currentObjBlock->setPriority($parametersCollection['desiredPriority']);
                $this->em->persist($currentObjBlock);
                $this->em->flush();

                return $this->returnResponse(array('success'=>1));
            }

            return $this->returnResponse(array('success'=>0,'message'=>'El bloque que usted desea editar no existe.'));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteBlocksData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['blocksId'][0])) {
                $idsCollection = implode(',',$parametersCollection['blocksId']);
                $this->em->getRepository('AppBundle:ContentBlock')->deleteByIdsCollection($idsCollection);
            }
            else{
                $message = 'No existen bloques para eliminar.';
                return $this->returnResponse(array('sucsess'=>0,'message'=>$message));
            }


            return $this->returnResponse(array('sucsess'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }



    public function getNextPriorityValue($objGenericPost){
        $nextPriorityValue = null;
        $parametersCollection = array();
        $parametersCollection['getBlockWithLowerPriority'] = true;
        $parametersCollection['searchByGenericPost'] = true;
        $parametersCollection['genericPostId'] = $objGenericPost->getId();
        $result = $this->em->getRepository('AppBundle:ContentBlock')->getContentBlocks($parametersCollection);
        if(!isset($result['id'])){
            $nextPriorityValue = 1;
        }
        else{
            $nextPriorityValue = $result['priority'] + 1;
        }
        return $nextPriorityValue;
    }

    public function returnResponse($parametersCollection){
        return $parametersCollection;
    }

}
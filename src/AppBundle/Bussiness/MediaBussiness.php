<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Finder\SplFileInfo;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

use AppBundle\Bussiness\SharedFileFinderBussiness;
use AppBundle\Bussiness\SettingBussiness;
use AppBundle\Entity\Media;
use AppBundle\Entity\MediaImage;
use AppBundle\Entity\MediaGallery;
use AppBundle\Entity\MediaVideo;
use AppBundle\Entity\Gallery;

class MediaBussiness
{
    private $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadInitialsData($parametersCollection)
    {
        $initialsData = array();
        $mediaImagesCollection = $this->getMediaImagesData($parametersCollection);
        $initialsData['imagesCollection'] = $mediaImagesCollection;
        $uploadRestrictionsCollection = $this->getUploadRestrictions();
        $initialsData['uploadRestrictionsCollection'] = $uploadRestrictionsCollection;

        return $initialsData;
    }

    public function getMediaData($parametersCollection){
        $mediaDataCollection = array();
        if(isset($parametersCollection['mediaType']) && $parametersCollection['mediaType'] == 'image'){
            $mediaDataCollection = $this->getMediaImagesData($parametersCollection);
        }
        else if(isset($parametersCollection['mediaType']) && $parametersCollection['mediaType'] == 'video'){
            $mediaDataCollection = $this->getMediaVideosData($parametersCollection);
        }
        else if(isset($parametersCollection['mediaType']) && $parametersCollection['mediaType'] == 'gallery'){
            $mediaDataCollection = $this->getGalleriesData($parametersCollection);
        }
        return $mediaDataCollection;
    }

    public function getMediaImagesData($parametersCollection){
        try{
            $mediaImagesCollection = $this->em->getRepository('AppBundle:MediaImage')->getMediaImages($parametersCollection);
            if(isset($mediaImagesCollection[0])){
                $objSharedFileFinderBussiness = new SharedFileFinderBussiness();
                $paramsCollection = array();
                $paramsCollection['filter_name'] = 'media_image_standard_thumbnail';
                foreach($mediaImagesCollection as $key=>$image){
                    $paramsCollection['image_name'] = $image['name_es'];
                    $paramsCollection['image_extension'] = $image['extension'];
                    $paramsCollection['just_check'] = true;
                    $paramsCollection['just_web_filtered_url'] = false;
                    if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage($paramsCollection)){
                        $paramsCollection['just_check'] = false;
                        $paramsCollection['just_web_filtered_url'] = true;
                        $mediaImagesCollection[$key]['web_filtered_standard_thumbnail_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage($paramsCollection);
                    }
                    else{
                        $mediaImagesCollection[$key]['web_filtered_standard_thumbnail_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($image['url'], 'media_image_standard_thumbnail');
                    }

                    $canEdit = $image['is_loaded_by_system'] == 1 ? 0 : 1;
                    $canDelete = $image['is_loaded_by_system'] == 1 ? 0 : 1;
                    $mediaImagesCollection[$key]['canEdit'] = $canEdit;
                    $mediaImagesCollection[$key]['canDelete'] = $canDelete;

                }
            }
            return $mediaImagesCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getMediaVideosData($parametersCollection){
        try{
            $mediaVideosCollection = $this->em->getRepository('AppBundle:MediaVideo')->getMediaVideos($parametersCollection);
            if(isset($mediaVideosCollection[0])){
                foreach($mediaVideosCollection as $key=>$video){

                    $canEdit = 1;
                    $canDelete = 1;
                    $mediaVideosCollection[$key]['canEdit'] = $canEdit;
                    $mediaVideosCollection[$key]['canDelete'] = $canDelete;
                    $mediaVideosCollection[$key]['youtube_url'] = $video['http_protocol'].'://www.youtube.com/embed/'.$video['url'];
                }
            }
            return $mediaVideosCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getGalleryMediaData($parametersCollection){
        try{
            $galleryElementsMediaData = $this->em->getRepository('AppBundle:MediaGallery')->getMediaGalleries($parametersCollection);
            if(isset($galleryElementsMediaData[0])){
                $objSharedFileFinderBussiness = new SharedFileFinderBussiness();
                $paramsCollection = array();
                $paramsCollection['filter_name'] = 'media_image_standard_thumbnail';
                foreach($galleryElementsMediaData as $key=>$galleryElement){
                    if($galleryElement['media_type_tree_slug'] == 'media-type-image'){
                        $objMediaImage =  $this->em->getRepository('AppBundle:MediaImage')->find($galleryElement['id']);
                        $paramsCollection['image_name'] = $galleryElement['name_es'];
                        $paramsCollection['image_extension'] = $objMediaImage->getExtension();
                        $paramsCollection['just_check'] = true;
                        $paramsCollection['just_web_filtered_url'] = false;
                        if($objSharedFileFinderBussiness->getExistenceFilteredUploadedImage($paramsCollection)){
                            $paramsCollection['just_check'] = false;
                            $paramsCollection['just_web_filtered_url'] = true;
                            $galleryElementsMediaData[$key]['web_filtered_standard_thumbnail_url'] = $objSharedFileFinderBussiness->getExistenceFilteredUploadedImage($paramsCollection);
                        }
                        else{
                            $galleryElementsMediaData[$key]['web_filtered_standard_thumbnail_url'] = $parametersCollection['imagineCacheManager']->getBrowserPath($galleryElement['url'], 'media_image_standard_thumbnail');
                        }
                    }
                    else{
                        $objMediaVideo =  $this->em->getRepository('AppBundle:MediaVideo')->find($galleryElement['id']);
                        $galleryElementsMediaData[$key]['youtube_url'] = $objMediaVideo->getHttpProtocol().'://www.youtube.com/embed/'.$galleryElement['url'];
                    }
                }
            }
            return $galleryElementsMediaData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getGalleriesData($parametersCollection){
        try{
            $galleriesCollection = $this->em->getRepository('AppBundle:Gallery')->getGalleries($parametersCollection);
            if(isset($galleriesCollection[0])){
                foreach($galleriesCollection as $key=>$gallery){
                    $canEdit = 1;
                    $canDelete = 1;
                    $galleriesCollection[$key]['canEdit'] = $canEdit;
                    $galleriesCollection[$key]['canDelete'] = $canDelete;

                    $galleriesCollection[$key]['childrens'] = $this->getGalleryMediaData(array(
                        'galleryId' => $gallery['id']
                    ));
                    if($key == 1){
                        //print_r( $galleriesCollection[$key]);die;
                    }
                }
            }

            return $galleriesCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getUploadRestrictions(){
        $objSettingBussiness = new SettingBussiness($this->em);
        $uploadRestrictionsCollection = $objSettingBussiness->getSectionSettingsData(array(
            'section' => 'media'
        ));
        return $uploadRestrictionsCollection;
    }

    public function saveMediaImageData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            $objSharedFileFinderBussiness = new SharedFileFinderBussiness();

            if($parametersCollection['isCreating'] == true){
                if(isset($parametersCollection['media_images_uploaded'][0])){
                    $paramsCollection = array();
                    foreach($parametersCollection['media_images_uploaded'] as $key=>$media_image){
                        /* Checking previous existence of the Image in the uploads directory */
                        $media_image_name = explode('.',$media_image->getClientOriginalName())[0];
                        $media_image_name = $parametersCollection['slugger']->slugifyFileName($media_image_name);
                        $media_image_extension = $media_image->guessExtension();
                        $paramsCollection['image_name'] = $media_image_name;
                        $paramsCollection['image_extension'] = $media_image_extension;
                        $paramsCollection['just_check'] = true;
                        if($objSharedFileFinderBussiness->getExistenceOriginalUploadedImage($paramsCollection) == false){

                            $objMedia = new Media();
                            $objMedia->setName($media_image_name);
                            $objMedia->setUrl($objSharedFileFinderBussiness->getWebOriginalImagesUploadDir().'/'.$media_image_name.'.'.$media_image_extension);
                            $objMediaType = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array(
                                'tree_slug' => 'media-type-image'
                            ));
                            $objMedia->setMediaType($objMediaType);
                            $objMedia->setCreatedAuthor($parametersCollection['loggedUser']);
                            $this->em->persist($objMedia);
                            $this->em->flush();

                            $objMediaImage = new MediaImage();
                            $objMediaImage->setId($objMedia);
                            $objMediaImage->setSize($media_image->getClientSize());
                            $objMediaImage->setAlternativeText($media_image_name);
                            $objMediaImage->setExtension($media_image_extension);
                            $objMediaImage->setDimension($parametersCollection['media_images_info_associated'][$key]['dimensions']);
                            $this->em->persist($objMediaImage);
                            $this->em->flush();

                            $media_image->move($objSharedFileFinderBussiness->getWebOriginalImagesUploadDir(), $media_image_name.'.'.$media_image_extension);
                        }
                        else{
                            $message.= ' Hubo imágenes que ya existían en la biblioteca.';
                        }
                    }
                }
                else{
                    $message = 'Las imágenes no se subieron correctamente.';
                    return $this->returnResponse(array('sucsess'=>0,'message'=>$message));
                }
            }
            else{
                /* Checking previous existence of the Media Image in BD */
                $objMedia = $this->em->getRepository('AppBundle:Media')->find($parametersCollection['id']);
                if(isset($objMedia)){
                    $media_image_name = $parametersCollection['slugger']->slugifyFileName($parametersCollection['name_es']);
                    $media_image_extension = $parametersCollection['extension'];
                    if($objMedia->getName() != $parametersCollection['name_es']) {
                        $paramsCollection = array();
                        $paramsCollection['image_name'] = $media_image_name;
                        $paramsCollection['image_extension'] = $media_image_extension;
                        $paramsCollection['just_check'] = true;
                        if ($objSharedFileFinderBussiness->getExistenceOriginalUploadedImage($paramsCollection) == true) {
                            $message = 'Ya existe otra imágen con ese nombre.';
                            return $this->returnResponse(array('sucsess'=>0,'message'=>$message));
                        }
                        else{
                            /* updating Image File name */
                            $objSharedFileFinderBussiness->moveMediaImageFile(array(
                                'file_type' => 'image',
                                'file_old_name' => $objMedia->getName(),
                                'file_new_name' => $media_image_name,
                                'file_extension' => $media_image_extension
                            ));

                            /* updating Image Filtered File versions's name */
                            $objSharedFileFinderBussiness->moveMediaImageFile(array(
                                'file_type' => 'image',
                                'file_old_name' => $objMedia->getName(),
                                'file_new_name' => $media_image_name,
                                'file_extension' => $media_image_extension,
                                'file_current_directory' => 'filtered_images'
                            ));
                        }
                    }

                    $objMedia->setName($media_image_name);
                    $objMedia->setDescription($parametersCollection['description_es']);
                    $objMedia->setUrl($objSharedFileFinderBussiness->getWebOriginalImagesUploadDir().'/'.$media_image_name.'.'.$media_image_extension);
                    $objMedia->setModifiedDate(new \DateTime());
                    $objMedia->setModifiedAuthor($parametersCollection['loggedUser']);
                    $this->em->persist($objMedia);

                    $objMediaImage =  $this->em->getRepository('AppBundle:MediaImage')->find($objMedia);
                    $objMediaImage->setAlternativeText($parametersCollection['alternative_text_es']);
                    $this->em->persist($objMediaImage);
                    $this->em->flush();

                }
                else{
                    $message = 'La imágen que desea editar ya no existe en el registro.';
                    return $this->returnResponse(array('sucsess'=>0,'message'=>$message));
                }
            }

            return $this->returnResponse(array('sucsess'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteMediaImageData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['mediaImagesId'][0])) {
                $paramsCollection = array();
                $paramsCollection['searchByIdsCollection'] = true;
                $paramsCollection['idsCollection'] = implode(",", $parametersCollection['mediaImagesId']);
                $mediaImagesCollection = $this->em->getRepository('AppBundle:MediaImage')->getMediaImages($paramsCollection);
                if(isset($mediaImagesCollection[0])){
                    foreach($mediaImagesCollection as $key=>$media_image){
                        $objSharedFileFinderBussiness = new SharedFileFinderBussiness();
                        $objSharedFileFinderBussiness->deleteMediaImageFile(array(
                            'image_name' => $media_image['name_es'],
                            'image_extension' => $media_image['extension'],
                            'image_url' => $media_image['url']
                        ));
                    }
                    $this->em->getRepository('AppBundle:Media')->deleteByIdsCollection($paramsCollection['idsCollection']);
                }
            }
            else{
                $message = 'No existen medias para eliminar.';
                return $this->returnResponse(array('sucsess'=>0,'message'=>$message));
            }


            return $this->returnResponse(array('sucsess'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveMediaVideoData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objMedia = $this->em->getRepository('AppBundle:Media')->findOneBy(array(
                'name_es' => $parametersCollection['name_es']
            ));
            if(isset($objMedia)){
                if($parametersCollection['isCreating'] == true ||
                ($parametersCollection['isCreating'] == false &&
                    $objMedia->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe un video con ese nombre.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
            }
            $objMedia = $this->em->getRepository('AppBundle:Media')->findOneBy(array(
                'url' => $parametersCollection['url']
            ));
            if(isset($objMedia)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objMedia->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe un video con esa url.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
            }

            $objMedia = new Media();
            if($parametersCollection['isCreating'] == false){
                $objMedia = $this->em->getRepository('AppBundle:Media')->find($parametersCollection['id']);
                if(!isset($objMedia)){
                    $message = 'El video que desea editar ya no existe.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
                $objMedia->setModifiedDate(new \DateTime());
                $objMedia->setModifiedAuthor($parametersCollection['loggedUser']);
            }
            $objMedia->setName($parametersCollection['name_es']);
            $objMedia->setDescription($parametersCollection['description_es']);
            $objMedia->setUrl($parametersCollection['url']);
            $objMediaType = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array(
                'tree_slug' => 'media-type-video'
            ));
            $objMedia->setMediaType($objMediaType);
            $this->em->persist($objMedia);
            $this->em->flush();

            if($parametersCollection['isCreating'] == true){
                $objMedia->setCreatedAuthor($parametersCollection['loggedUser']);
                $this->em->persist($objMedia);

                $objMediaVideo = new MediaVideo();
                $objMediaVideo->setId($objMedia);
                $objMediaVideo->setOrigin($parametersCollection['origin']);
            }
            else{
                $objMediaVideo = $this->em->getRepository('AppBundle:MediaVideo')->find($objMedia);
            }
            $objMediaVideo->setHttpProtocol($parametersCollection['http_protocol']);
            $this->em->persist($objMediaVideo);
            $this->em->flush();

            return $this->returnResponse(array('success'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteMediaVideoData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['mediaVideosId'][0])) {
                $idsCollection = implode(',',$parametersCollection['mediaVideosId']);
                $this->em->getRepository('AppBundle:Media')->deleteByIdsCollection($idsCollection);
            }
            else{
                $message = 'No existen videos para eliminar.';
                return $this->returnResponse(array('sucsess'=>0,'message'=>$message));
            }


            return $this->returnResponse(array('sucsess'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveMediaGalleryData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objGallery = $this->em->getRepository('AppBundle:Gallery')->findOneBy(array(
                'name_es' => $parametersCollection['name_es']
            ));
            if(isset($objGallery)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objGallery->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe una galería con ese nombre.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
            }
            $objGallery = new Gallery();
            if($parametersCollection['isCreating'] == false){
                $objGallery = $this->em->getRepository('AppBundle:Gallery')->find($parametersCollection['id']);
                if(!isset($objGallery)){
                    $message = 'La galería que desea editar ya no existe.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
                $objGallery->setModifiedDate(new \DateTime());
                $objGallery->setModifiedAuthor($parametersCollection['loggedUser']);
            }
            else{
                $objGallery->setCreatedAuthor($parametersCollection['loggedUser']);
            }

            $objGallery->setName($parametersCollection['name_es']);
            $objGallery->setDescription($parametersCollection['description_es']);
            $objGalleryType = $this->em->getRepository('AppBundle:Nomenclature')->find($parametersCollection['gallery_type_id']);
            $objGallery->setGalleryType($objGalleryType);
            $this->em->persist($objGallery);
            $this->em->flush();


            /*working with the elements of the gallery (images or videos)*/
            $paramsCollection = array();
            $paramsCollection['gallery_id'] = $objGallery->getId();
            $this->em->getRepository('AppBundle:MediaGallery')->deleteGalleryElements($paramsCollection);
            if(isset($parametersCollection['childrens']) && isset($parametersCollection['childrens'][0])){
                foreach($parametersCollection['childrens'] as $key=>$children){
                    $objMediaGallery = new MediaGallery();
                    $objMediaGallery->setGallery($objGallery);
                    $objMedia = $this->em->getRepository('AppBundle:Media')->find($children['id']);
                    if(isset($objMedia)){
                        $objMediaGallery->setMedia($objMedia);
                    }
                    $objMediaGallery->setPriority($key+1);
                    $this->em->persist($objMediaGallery);
                }
                $this->em->flush();
            }

            return $this->returnResponse(array('success'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteMediaGalleryData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            if(isset($parametersCollection['mediaGalleriesId'][0])) {
                $idsCollection = implode(',',$parametersCollection['mediaGalleriesId']);
                $this->em->getRepository('AppBundle:Gallery')->deleteByIdsCollection($idsCollection);
            }
            else{
                $message = 'No existen galerías para eliminar.';
                return $this->returnResponse(array('sucsess'=>0,'message'=>$message));
            }


            return $this->returnResponse(array('sucsess'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }


    public function returnResponse($parametersCollection){
        return $parametersCollection;
    }

}
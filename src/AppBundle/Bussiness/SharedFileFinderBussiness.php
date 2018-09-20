<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Finder\SplFileInfo;


class SharedFileFinderBussiness
{
    private $listenersDataDirectory;
    private $settingsDataDirectory;
    private $themesDataDirectory;
    private $uploadOriginalImagesDirectory;
    private $uploadedImagesFilteredDirectory;
    private $webUploadDir;
    private $webImagesUploadDir;
    private $webOriginalImagesUploadDir;
    private $webFilteredImagesUploadDir;
    private $webDocumentsUploadDir;


    /*Construct*/
    public function __construct()
    {
        $this->listenersDataDirectory = __DIR__.'/../FlatFileDB/Json/Listeners';
        $this->settingsDataDirectory = __DIR__.'/../FlatFileDB/Json/Settings';
        $this->themesDataDirectory = __DIR__.'/../Resources/views/Frontend/themes';
        $this->uploadOriginalImagesDirectory =  __DIR__.'/../../../web/uploads/images/original';
        $this->uploadedImagesFilteredDirectory = __DIR__.'/../../../web/uploads/images/liip_imagine_filtered';
        $this->webUploadDir = 'uploads';
        $this->webImagesUploadDir = $this->webUploadDir.'/images';
        $this->webOriginalImagesUploadDir = $this->webImagesUploadDir.'/original';
        $this->webFilteredImagesUploadDir = $this->webImagesUploadDir.'/liip_imagine_filtered';
        $this->webDocumentsUploadDir = $this->webUploadDir.'/documents';
    }



    /*Features*/
    public function getListenersFile($parametersCollection){
        $listener = $parametersCollection['listener'];
        $finder = new Finder();
        $finder->files()->in($this->listenersDataDirectory)->name($listener.'.json');
        if($finder->hasResults()){
            if(isset($parametersCollection['decode_from_json']) && $parametersCollection['decode_from_json'] == true){
                foreach ($finder as $key=>$file){
                    if($key == 0){
                        $listenerContentString = $file->getContents();
                        $listenerContentJson = json_decode($listenerContentString,true);
                        return $listenerContentJson;
                        break;
                    }
                }
            }
            else{
                return $finder;
            }
        }
        else{
            $exception = new \Exception('El archivo de configuracion no existe.');
            throw $exception;
        }
    }

    public function writeListenersFile($parametersCollection){
        $listenersFile = fopen($this->listenersDataDirectory.'/'.$parametersCollection['listener'].'.json','w+');
        fwrite($listenersFile,json_encode($parametersCollection['listenerData'],JSON_UNESCAPED_UNICODE));
        fclose ($listenersFile);
    }

    public function getSettingsFile($parametersCollection){
        $section = $parametersCollection['section'];
        $finder = new Finder();
        $finder->files()->in($this->settingsDataDirectory)->name($section.'.json');
        if($finder->hasResults()){
            if(isset($parametersCollection['decode_from_json']) && $parametersCollection['decode_from_json'] == true){
                foreach ($finder as $key=>$file){
                    if($key == 0){
                        $sectionContentString = $file->getContents();
                        $sectionContentJson = json_decode($sectionContentString,true);
                        return $sectionContentJson;
                        break;
                    }
                }
            }
            else{
                return $finder;
            }
        }
        else{
            $exception = new \Exception('El archivo de configuracion no existe.');
            throw $exception;
        }
    }

    public function getThemeDescriptorFile($parametersCollection){
        $themeName = $parametersCollection['theme_name'];
        $finder = new Finder();
        $finder->files()->in($this->themesDataDirectory.'/'.$themeName)->name($themeName.'.json');
        if($finder->hasResults()){
            if(isset($parametersCollection['decode_from_json']) && $parametersCollection['decode_from_json'] == true){
                foreach ($finder as $key=>$file){
                    if($key == 0){
                        $themeDescriptorContentString = $file->getContents();
                        $themeDescriptorContentJson = json_decode($themeDescriptorContentString,true);
                        return $themeDescriptorContentJson;
                        break;
                    }
                }
            }
            else{
                return $finder;
            }
        }
        else{
            $exception = new \Exception('El archivo de configuracion no existe.');
            throw $exception;
        }
    }

    public function getExistenceFilteredUploadedImage($parametersCollection){

        if(file_exists($this->uploadedImagesFilteredDirectory.'/'.$parametersCollection['filter_name'].'/'.$this->webOriginalImagesUploadDir)){
            $finder = new Finder();
            $finder->files()->in($this->uploadedImagesFilteredDirectory.'/'.$parametersCollection['filter_name'].'/'.$this->webOriginalImagesUploadDir)->name($parametersCollection['image_name'].'.'.$parametersCollection['image_extension']);
            if($finder->hasResults()){
                if($parametersCollection['just_check'] == true){
                    return true;
                }
                else if($parametersCollection['just_web_filtered_url'] == true){
                    return $this->webFilteredImagesUploadDir.'/'.$parametersCollection['filter_name'].'/'.$this->webOriginalImagesUploadDir.
                    '/'.$parametersCollection['image_name'].'.'.$parametersCollection['image_extension'];
                }
                else{
                    foreach ($finder as $key=>$file){
                        return $file;
                    }
                }
            }
        }
        return false;
    }

    public function getExistenceOriginalUploadedImage($parametersCollection){
        if(file_exists($this->webOriginalImagesUploadDir)){
            $finder = new Finder();
            $finder->files()->in($this->webOriginalImagesUploadDir)->name($parametersCollection['image_name'].'.'.$parametersCollection['image_extension']);
            if($finder->hasResults()){
                if($parametersCollection['just_check'] == true){
                    return true;
                }
                else{
                    foreach ($finder as $key=>$file){
                        return $file;
                    }
                }
            }
        }
        return false;
    }

    public function writeSettingsFile($parametersCollection){
        $settingsFile = fopen($this->settingsDataDirectory.'/'.$parametersCollection['section'].'.json','w+');
        fwrite($settingsFile,json_encode($parametersCollection['settingData'],JSON_UNESCAPED_UNICODE));
        fclose ($settingsFile);
    }

    public function moveMediaImageFile($parametersCollection, $filePath = null){

        if(isset($parametersCollection['file_current_directory']) &&
            $parametersCollection['file_current_directory'] == 'filtered_images'){
            $fileDirectory = $this->uploadedImagesFilteredDirectory;
            if($filePath == null){
                $filePath = $fileDirectory;
                /**
                 * WIRED
                 *
                 * Collection of Imagine Filters Sets must be returned from a Service, not this way.
                 **/
                $imagineFilterSetsCollection = array();
                $imagineFilterSetsCollection[0]['name'] = 'logued_user_thumbnail';
                $imagineFilterSetsCollection[1]['name'] = 'media_image_standard_thumbnail';
                $imagineFilterSetsCollection[2]['name'] = 'featured_image_mini_thumbnail';
                $imagineFilterSetsCollection[3]['name'] = 'grid_featured_image_thumbnail';
                $imagineFilterSetsCollection[4]['name'] = 'list_featured_image_mini_thumbnail';
                $imagineFilterSetsCollection[5]['name'] = 'default_theme_banner';
                $imagineFilterSetsCollection[6]['name'] = 'single_post_featured_image';
                $paramsCollection = array();
                $paramsCollection['image_name'] = $parametersCollection['file_old_name'];
                $paramsCollection['image_extension'] = $parametersCollection['file_extension'];
                foreach($imagineFilterSetsCollection as $filterSets){
                    $paramsCollection['filter_name'] = $filterSets['name'];
                    $paramsCollection['just_check'] = true;
                    if($this->getExistenceFilteredUploadedImage($paramsCollection) == true){

                        $filePath.= '/'.$filterSets['name'].'/'.$this->webOriginalImagesUploadDir.'/'.$parametersCollection['file_old_name'].'.'.$parametersCollection['file_extension'];
                        $objFile = new File($filePath, true);
                        $objFile->move($fileDirectory.'/'.$filterSets['name'].'/'.$this->webOriginalImagesUploadDir, $parametersCollection['file_new_name'].'.'.$parametersCollection['file_extension']);
                    }
                }
            }
        }
        else{
            $fileDirectory = $this->uploadOriginalImagesDirectory;
            $filePath = $fileDirectory;
            $filePath.= '/'.$parametersCollection['file_old_name'].'.'.$parametersCollection['file_extension'];
            $objFile = new File($filePath, true);
            $objFile->move($fileDirectory, $parametersCollection['file_new_name'].'.'.$parametersCollection['file_extension']);
        }
    }

    public function deleteMediaImageFile($parametersCollection){

        /*checking existentce of the original media image file*/
        if(file_exists($this->uploadOriginalImagesDirectory.'/'.$parametersCollection['image_name'].'.'.$parametersCollection['image_extension'])){
            unlink($this->uploadOriginalImagesDirectory.'/'.$parametersCollection['image_name'].'.'.$parametersCollection['image_extension']);
        }

        /*checking existence of filtered images files*/
        /**
         * WIRED
         *
         * Collection of Imagine Filters Sets must be returned from a Service, not this way.
         **/
        $imagineFilterSetsCollection = array();
        $imagineFilterSetsCollection[0]['name'] = 'logued_user_thumbnail';
        $imagineFilterSetsCollection[1]['name'] = 'media_image_standard_thumbnail';
        $imagineFilterSetsCollection[2]['name'] = 'featured_image_mini_thumbnail';
        $imagineFilterSetsCollection[3]['name'] = 'grid_featured_image_thumbnail';
        $imagineFilterSetsCollection[4]['name'] = 'list_featured_image_mini_thumbnail';
        $imagineFilterSetsCollection[5]['name'] = 'default_theme_banner';
        $imagineFilterSetsCollection[6]['name'] = 'single_post_featured_image';

        foreach($imagineFilterSetsCollection as $filterSets){
            if(file_exists($this->uploadedImagesFilteredDirectory.'/'.$filterSets['name'].'/'.$parametersCollection['image_url'])){
                unlink($this->uploadedImagesFilteredDirectory.'/'.$filterSets['name'].'/'.$parametersCollection['image_url']);
            }
        }
    }

    public function getThemePagesTemplatesNames($parametersCollection){

        $finder = new Finder();
        $finder->files()
               ->in($this->themesDataDirectory.'/'.$parametersCollection['currentTheme'].'/templates/'.$parametersCollection['template_type'])
               ->name('*.html.twig')
               ->contains('Template Name:');
        if($finder->hasResults()){
            if(isset($parametersCollection['get_file_content']) && $parametersCollection['get_file_content'] == true){
                $templatesCollection = array();
                $pos = 0;
                foreach ($finder as $key=>$file){
                    $templateFileName = $file->getRelativePathname();
                    $templateSlug = explode('.',$templateFileName)[0];
                    $templateContent = $file->getContents();
                    $templateContentArray = explode('Template Name:',$templateContent);
                    if(isset($templateContentArray[1])){
                        $templateNameArray = explode('*',$templateContentArray[1]);
                        if(isset($templateNameArray[0])){
                            $templatesCollection[$pos]['template_file_name'] = $templateFileName;
                            $templatesCollection[$pos]['template_slug'] = $templateSlug;
                            $templatesCollection[$pos]['template_name'] = $templateNameArray[0];
                            $pos++;
                        }
                    }
                }
                return $templatesCollection;
            }
            else{
                return $finder;
            }
        }
        else{
            $exception = new \Exception('Las plantillas del Tema '.$parametersCollection['currentTheme'].' no existen.');
            throw $exception;
        }
    }

    public function checkTemplateExistence($parametersCollection){
        $finder = new Finder();
        $finder->files()
            ->in($this->themesDataDirectory.'/'.$parametersCollection['currentTheme'].'/templates/'.$parametersCollection['template_type'])
            ->name($parametersCollection['template_prefix'].'.html.twig');
        if($finder->hasResults()){
            return true;
        }
        else{
            return false;
        }
    }



    /*Relative Paths*/
    public function getWebUploadDir(){
        return $this->webUploadDir;
    }

    public function getWebImagesUploadDir(){
        return $this->webImagesUploadDir;
    }

    public function getWebOriginalImagesUploadDir(){
        return $this->webOriginalImagesUploadDir;
    }

    public function getWebFilteredImagesUploadDir(){
        return $this->webFilteredImagesUploadDir;
    }

    public function getWebDocumentsUploadDir(){
        return $this->webDocumentsUploadDir;
    }
}
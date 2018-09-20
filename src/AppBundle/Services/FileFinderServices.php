<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Bussiness\SharedFileFinderBussiness;


class FileFinderServices
{
    private $session;
    private $em;
    private $objSharedFileBussiness;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
        $this->objSharedFileBussiness = new SharedFileFinderBussiness($this->em);
    }

    public function getThemeTemplatesNames($parametersCollection)
    {
        try{
            $templatesCollection = array();
            if(isset($parametersCollection['currentTheme']) && $parametersCollection['currentTheme'] != null){
                if(!isset($parametersCollection['template_type'])){
                    $parametersCollection['template_type'] = 'pages';
                }
                $parametersCollection['get_file_content'] = true;
                $templatesCollection = $this->objSharedFileBussiness->getThemePagesTemplatesNames($parametersCollection);
            }

            return $templatesCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function checkTemplateExistence($parametersCollection)
    {
        try{
            $isTemplateAvailable = false;
            if(isset($parametersCollection['currentTheme']) && $parametersCollection['currentTheme'] != null){
                if(!isset($parametersCollection['template_type'])){
                    $parametersCollection['template_type'] = 'pages';
                }
                $isTemplateAvailable = $this->objSharedFileBussiness->checkTemplateExistence($parametersCollection);

            }

            return $isTemplateAvailable;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }


}
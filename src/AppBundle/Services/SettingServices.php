<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Bussiness\SettingBussiness;


class SettingServices
{
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function isSiteStatusOnline()
    {
        try{
            $settingsData = $this->getSettingsData('availability');
            $isSiteStatusOnline = true;
            if(isset($settingsData['status']) && $settingsData['status'] == 'offline'){
                $isSiteStatusOnline = false;
            }
            return $isSiteStatusOnline;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getCurrentTheme(){
        try{
            $settingsData = $this->getSettingsData('appearance');
            $currentTheme = $settingsData['theme'];
            return $currentTheme;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getSectionSettingsData($section){
        try{
            return $this->getSettingsData($section);
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getBncDomain(){
        try{
            $settingsData =  $this->getSettingsData('availability');
            $bncDomain = $settingsData['domain_http_protocol'].'://'.$settingsData['domain_name'];
            return $bncDomain;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getMenus($parametersCollection){
        $settingBussinessObj = new SettingBussiness($this->em);
        $appearanceMenusData = $settingBussinessObj->getAppearanceMenusData($parametersCollection);
        if(isset($appearanceMenusData[0])){
            foreach($appearanceMenusData as $key=>$menu){
                $paramsCollection = array();
                $paramsCollection['menuId'] = $menu['id'];
                $paramsCollection['returnDataInTree']  = true;
                $paramsCollection['searchByParent'] = true;
                $paramsCollection['parentId'] = null;
                $menuItemsCollection = $settingBussinessObj->getAppearanceMenuItemsData($paramsCollection);
                $appearanceMenusData[$key]['childrens'] = $menuItemsCollection;
            }
        }
        return $appearanceMenusData;
    }





    function getSettingsData($section)
    {
        $parametersCollection = array();
        $parametersCollection['section'] = $section;
        $settingBussinessObj = new SettingBussiness($this->em);
        $settingsData = $settingBussinessObj->getSectionSettingsData($parametersCollection);
        return $settingsData;
    }
}
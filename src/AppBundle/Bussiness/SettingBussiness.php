<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;

use AppBundle\Bussiness\SharedFileFinderBussiness;
use AppBundle\Entity\Menu;
use AppBundle\Entity\MenuItem;
use AppBundle\Entity\MenuItemPage;
use AppBundle\Entity\MenuItemLink;

class SettingBussiness
{
    private $em;
    private $sharedFileFinderUtility;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->sharedFileFinderUtility = new SharedFileFinderBussiness();
    }

    public function loadInitialsData($parametersCollection)
    {
        $initialsData = array();
        $settingsData = $this->getSectionSettingsData($parametersCollection);
        $initialsData['settingsData'] = $settingsData;
        return $initialsData;
    }

    public function getSectionSettingsData($parametersCollection){
        try{
            $parametersCollection['decode_from_json'] = true;
            $sectionContentJson = $this->sharedFileFinderUtility->getSettingsFile($parametersCollection);
            if($parametersCollection['section'] == 'appearance'){
                $themeName = $sectionContentJson['theme'];
                $themeDescriptionJson = $this->sharedFileFinderUtility->getThemeDescriptorFile(array(
                    'theme_name' => $themeName,
                    'decode_from_json' => true
                ));
                $sectionContentJson['theme_description'] = $themeDescriptionJson;
            }
            else if($parametersCollection['section'] == 'notification'){
                if($sectionContentJson['check_pending_comments'] == 'true'){
                    $sectionContentJson['check_pending_comments'] = true;
                }
                else if($sectionContentJson['check_pending_comments'] == 'false'){
                    $sectionContentJson['check_pending_comments'] = false;
                }
            }
            else if($parametersCollection['section'] == 'media'){
                $sectionContentJson['system_max_filesize'] = explode('M',ini_get('upload_max_filesize'))[0];
                $sectionContentJson['system_max_filesupload'] = ini_get('max_file_uploads');
            }
            return $sectionContentJson;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getAppearanceMenusData($parametersCollection){
        try{
            $menusDataCollection = $this->em->getRepository('AppBundle:Menu')->getMenus($parametersCollection);
            return $menusDataCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getAppearanceMenuItemsData($parametersCollection){
        try{
            $menuItemsDataCollection = $this->em->getRepository('AppBundle:MenuItem')->getMenuItems($parametersCollection);
            if(isset($menuItemsDataCollection[0])){
                foreach($menuItemsDataCollection as $key=>$menuItem){

                    $canEdit = 1;
                    $canDelete = 1;
                    /* changing date format */
                    $created_date = date_format($menuItem['created_date'],'d/m/Y H:i');

                    $menuItemsDataCollection[$key]['canEdit'] = $canEdit;
                    $menuItemsDataCollection[$key]['canDelete'] = $canDelete;
                    $menuItemsDataCollection[$key]['created_date'] = $created_date;

                    if($menuItem['menu_item_type_tree_slug'] == 'menu-item-type-page'){
                        $objMenuItemPage = $this->em->getRepository('AppBundle:MenuItemPage')->find($menuItem['id']);
                        if(isset($objMenuItemPage)){
                            $menuItemsDataCollection[$key]['page_id'] = $objMenuItemPage->getPage()->getId();
                            $menuItemsDataCollection[$key]['tag'] = $objMenuItemPage->getTag();
                            $menuItemsDataCollection[$key]['url'] = " ";
                        }
                    }
                    else{
                        $objMenuItemLink = $this->em->getRepository('AppBundle:MenuItemLink')->find($menuItem['id']);
                        if(isset($objMenuItemLink)){
                            $menuItemsDataCollection[$key]['link_url'] = $objMenuItemLink->getLinkUrl();
                            $menuItemsDataCollection[$key]['url'] = $objMenuItemLink->getLinkUrl();
                        }
                    }

                    $menuItemsChildsCollection = array();
                    if(isset($parametersCollection['returnDataInTree']) && $parametersCollection['returnDataInTree'] == true)
                    {
                        $parametersCollection['searchByParent'] = true;
                        $parametersCollection['parentId'] = $menuItem['id'];
                        $menuItemsChildsCollection = $this->getAppearanceMenuItemsData($parametersCollection);
                    }
                    $menuItemsDataCollection[$key]['childs'] = $menuItemsChildsCollection;
                }
            }
            return $menuItemsDataCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveSettingsData($parametersCollection)
    {
        try{
            $canProceed = true;
            $success = 1;
            $message = 'Datos guardados.';

            if($parametersCollection['section'] == 'appearance'){
                $parametersCollection['settingData']['theme_description'] = array();
            }
            $this->sharedFileFinderUtility->writeSettingsFile($parametersCollection);

            /* Returning results */
            return array(
                'success'=>$success,
                'message'=>$message
            );
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveAppearanceMenuData($parametersCollection)
    {
        try{
            $canProceed = true;
            $success = 1;
            $message = 'Datos guardados.';

            if($parametersCollection['isCreating']){
                /* Checking previous existence */
                $objMenu = $this->em->getRepository('AppBundle:Menu')->findOneBy(array(
                    'name' => $parametersCollection['name']
                ));
                if(isset($objMenu)){
                    $canProceed = false;
                    $message = 'Ya existe una menú con ese nombre.';
                }
                else{/* ASSIGN singular values for CREATE operation */
                    $objMenu = new Menu();
                    $objMenu->setCreatedAuthor($parametersCollection['loggedUser']);
                }
            }
            else{
                /* Checking previous existence */
                $objMenu = $this->em->getRepository('AppBundle:Menu')->find($parametersCollection['menu_id']);
                if(isset($objMenu)){
                    $objMenuWithSameName = $this->em->getRepository('AppBundle:Menu')->findOneBy(array(
                        'name' => $parametersCollection['name']
                    ));
                    if(isset($objMenuWithSameName) && $objMenuWithSameName->getId() != $parametersCollection['menu_id']){
                        $canProceed = false;
                        $message = 'Ya existe un menú con el mismo nombre.';
                    }
                    else{
                        $objMenu->setModifiedDate(new \DateTime());
                        $objMenu->setModifiedAuthor($parametersCollection['loggedUser']);
                    }
                }
                else{
                    $canProceed = false;
                    $message = 'El menú que usted desea editar no se encuentra en los registros.';
                }
            }

            if($canProceed){
                /* ASSIGN common values */
                $objMenu->setName($parametersCollection['name']);
                $objMenu->setSlug($parametersCollection['slug']);
                $objMenu->setDescription($parametersCollection['description']);
                $this->em->persist($objMenu);
                $this->em->flush();
            }
            else{
                $success = 0;
            }

            /* Returning results */
            return array(
                'success'=>$success,
                'message'=>$message
            );
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveAppearanceMenuItemData($parametersCollection)
    {
        try{
            $message = 'Datos guardados.';

            /* Checking previous existence of Menu */
            $objMenu = $this->em->getRepository('AppBundle:Menu')->find($parametersCollection['menu_id']);
            if(isset($objMenu)){
                /* Checking previous existence of the possible Menu Item Parent */
                if($parametersCollection['parent_id'] != null){
                    $objMenuItemParent = $this->em->getRepository('AppBundle:MenuItem')->find($parametersCollection['parent_id']);
                    if(!isset($objMenuItemParent)){
                        $message = 'El elemento Padre seleccionado ya no se encuentra en los registros.';
                        $this->returnResponse(array('sucsess'=>0,'message'=>$message));
                    }
                }
                else{
                    $objMenuItemParent = null;
                }

                /* Checking previous existence of Menu Item Type */
                if($parametersCollection['item_type_id'] != null){
                    $objMenuItemType = $this->em->getRepository('AppBundle:Nomenclature')->find($parametersCollection['item_type_id']);
                    if(!isset($objMenuItemType)){
                        $message = 'El Tipo de menú seleccionado ya no se encuentra en los registros.';
                        $this->returnResponse(array('sucsess'=>0,'message'=>$message));
                    }
                }
                else{
                    $objMenuItemType = null;
                }

                /* Checking previous existence of the Menu Item */
                $objMenuItem = $this->em->getRepository('AppBundle:MenuItem')->findOneBy(array(
                    'name_es' => $parametersCollection['name']
                ));
                if((isset($objMenuItem) && $parametersCollection['isCreating']) ||
                (isset($objMenuItem) && !$parametersCollection['isCreating'] && $parametersCollection['menu_item_id'] != $objMenuItem->getId())){
                    $message = 'Ya existe un elemento del menú con ese nombre.';
                    $this->returnResponse(array('sucsess'=>0,'message'=>$message));
                }
                $objMenuItem = $this->em->getRepository('AppBundle:MenuItem')->findOneBy(array(
                    'url_slug_es' => $parametersCollection['url_slug']
                ));
                if((isset($objMenuItem) && $parametersCollection['isCreating']) ||
                (isset($objMenuItem) && !$parametersCollection['isCreating'] && $parametersCollection['menu_item_id'] != $objMenuItem->getId())){
                    $message = 'Ya existe un elemento del menú con ese slug.';
                    $this->returnResponse(array('sucsess'=>0,'message'=>$message));
                }
                if(isset($parametersCollection['menu_item_id'])){
                    $objMenuItem = $this->em->getRepository('AppBundle:MenuItem')->find($parametersCollection['menu_item_id']);
                    if(!isset($objMenuItem)){
                        $message = 'El elemento que desea modificar ya no existe en los registros.';
                        $this->returnResponse(array('sucsess'=>0,'message'=>$message));
                    }
                }
            }
            else{
                $message = 'El Menú al cual desea agregar este elemento ya no existe en los registros.';
                $this->returnResponse(array('sucsess'=>0,'message'=>$message));
            }

            if($parametersCollection['isCreating']){
                $objMenuItem = new MenuItem();
                $objMenuItem->setCreatedAuthor($parametersCollection['loggedUser']);
                $objMenuItem->setMenu($objMenu);
            }
            else{
                $objMenuItem->setModifiedDate(new \DateTime());
                $objMenuItem->setModifiedAuthor($parametersCollection['loggedUser']);
            }
            $objMenuItem->setName($parametersCollection['name']);
            $objMenuItem->setUrlSlug($parametersCollection['url_slug']);
            $objMenuItem->setTreeSlug($objMenu->getSlug().'-'.$parametersCollection['url_slug']);
            $objMenuItem->setPriority($parametersCollection['priority']);
            $objMenuItem->setDescription($parametersCollection['description']);
            $objMenuItem->setParent($objMenuItemParent);
            if($objMenuItemParent != null){
                $objMenuItem->setDepth($objMenuItemParent->getDepth() + 1);
            }
            $objMenuItem->setItemType($objMenuItemType);
            $this->em->persist($objMenuItem);
            $this->em->flush();

            /* Checkin previous existence */
            $objMenuItemPage = $this->em->getRepository('AppBundle:MenuItemPage')->find($objMenuItem->getId());
            $objMenuItemLink = $this->em->getRepository('AppBundle:MenuItemLink')->find($objMenuItem->getId());
            if($objMenuItemType->getTreeSlug() == 'menu-item-type-page'){
                if(isset($objMenuItemLink)){
                    $this->em->remove($objMenuItemLink);
                }
                if(!isset($objMenuItemPage)){
                    $objMenuItemPage = new MenuItemPage();
                    $objMenuItemPage->setId($objMenuItem);
                }
                $objPage = $this->em->getRepository('AppBundle:Page')->find($parametersCollection['item_type_page_id']);
                $objPage = isset($objPage)?$objPage:null;
                $objMenuItemPage->setPage($objPage);
                if($parametersCollection['item_type_page_tag'] != null){
                    $objMenuItemPage->setTag($parametersCollection['item_type_page_tag']);
                }
                $this->em->persist($objMenuItemPage);
                $this->em->flush();
            }
            else{
                if(isset($objMenuItemPage)){
                    $this->em->remove($objMenuItemPage);
                }
                if(!isset($objMenuItemLink)){
                    $objMenuItemLink = new MenuItemLink();
                    $objMenuItemLink->setId($objMenuItem);
                }
                $objMenuItemLink->setLinkUrl($parametersCollection['item_type_link']);
                $this->em->persist($objMenuItemLink);
                $this->em->flush();
            }

            return $this->returnResponse(array('sucsess'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteMenus($parametersCollection)
    {
        try{
            $canProceed = true;
            $success = 1;
            $message = 'Datos guardados.';
            if(isset($parametersCollection['menusId'][0])) {
                $parametersCollection['menusId'] = implode(",", $parametersCollection['menusId']);
                $this->em->getRepository('AppBundle:Menu')->deleteByIdsCollection($parametersCollection['menusId']);
            }
            else{
                $canProceed = false;
                $message = 'No existen menús para eliminar.';
            }

            if(!$canProceed){
                $success = 0;
            }

            /* Returning results */
            return array(
                'success'=>$success,
                'message'=>$message
            );

        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteMenuItems($parametersCollection)
    {
        try{
            $canProceed = true;
            $success = 1;
            $message = 'Datos guardados.';
            if(isset($parametersCollection['menuItemsId'][0])) {
                $parametersCollection['menuItemsId'] = implode(",", $parametersCollection['menuItemsId']);
                $this->em->getRepository('AppBundle:MenuItem')->deleteByIdsCollection($parametersCollection['menuItemsId']);
            }
            else{
                $canProceed = false;
                $message = 'No existen elementos de menú para eliminar.';
            }

            if(!$canProceed){
                $success = 0;
            }

            /* Returning results */
            return array(
                'success'=>$success,
                'message'=>$message
            );

        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function returnResponse($parametersCollection){
        return $parametersCollection;
    }

}
<?php

namespace AppBundle\Controller;

use AppBundle\Bussiness\NomenclatureBussiness;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\SettingBussiness;


/**
 * BACKEND - Settings controller.
 *
 * @Route("backend/configuracion")
 */
class BackendSettingController extends Controller
{

    /**
     * Return the Settings View
     *
     * @Route("/", name="settings_index")
     * @Security("is_granted('read', 'settings')")
     * @Method("GET")
     */
    public function settingsViewAction()
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            return $this->render('@app_backend_template_directory/Setting/index.html.twig');
        }

    }

    /**
     * Load initials data for Settings view
     *
     * @Route("/datos-iniciales", name="settings_view_initials_data", options={"expose"=true})
     * @Security("is_granted('read', 'settings')")
     * @Method("POST")
     */
    public function loadSettingsInitialsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['section'] = $request->get('section');
            $settingBussinessObj = new SettingBussiness($em);
            $initialsData = $settingBussinessObj->loadInitialsData($parametersCollection);
            return new JsonResponse(array('initialsData' => $initialsData));
        }
    }

    /**
     * Get settings data for Settings view
     *
     * @Route("/datos-configuracion", name="settings_data", options={"expose"=true})
     * @Security("is_granted('read', 'settings')")
     * @Method("POST")
     */
    public function loadSettingsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['section'] = $request->get('section');
            $settingBussinessObj = new SettingBussiness($em);
            $settingsData = $settingBussinessObj->getSectionSettingsData($parametersCollection);
            return new JsonResponse(array(
                'settingsData'=>$settingsData
            ));
        }
    }

    /**
     * Get Appearance Section Menus
     *
     * @Route("/menu", name="settings_appearance_menu_data", options={"expose"=true})
     * @Security("is_granted('read', 'settings')")
     * @Method("POST")
     */
    public function loadAppearanceMenusDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $settingBussinessObj = new SettingBussiness($em);
            $appearanceMenusData = $settingBussinessObj->getAppearanceMenusData($parametersCollection);

            $parametersCollection = new \stdClass();
            $parametersCollection->filterByTreeSlug = true;
            $parametersCollection->treeSlug = 'menu-item-type';
            $objNomenclatureBussiness = new NomenclatureBussiness($em);
            $appearanceMenuItemTypesData = $objNomenclatureBussiness->getNomenclatures($parametersCollection);

            return new JsonResponse(array(
                'appearanceMenusData'=>$appearanceMenusData,
                'appearanceMenuItemTypesData'=>$appearanceMenuItemTypesData
            ));
        }
    }

    /**
     * Get Appearance Section Menus
     *
     * @Route("/menu/items", name="settings_appearance_menu_items_data", options={"expose"=true})
     * @Security("is_granted('read', 'settings')")
     * @Method("POST")
     */
    public function loadAppearanceMenuItemsDataAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['menuId'] = $request->get('menuId');
            $parametersCollection['returnDataInTree']  = true;
            $parametersCollection['searchByParent'] = true;
            $parametersCollection['parentId'] = null;


            $settingBussinessObj = new SettingBussiness($em);
            $appearanceMenusData = $settingBussinessObj->getAppearanceMenuItemsData($parametersCollection);
            return new JsonResponse(array(
                'appearanceMenuItemsData'=>$appearanceMenusData
            ));
        }
    }

    /**
     * Save settings data
     *
     * @Route("/guardar", name="settings_save_data", options={"expose"=true})
     * @Security("is_granted('edit', 'settings')")
     * @Method("POST")
     */
    public function saveSettingsAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection['section'] = $request->get('section');
            $parametersCollection['settingData'] = $request->get('settingData');

            $settingBussinessObj = new SettingBussiness($em);
            $response = $settingBussinessObj->saveSettingsData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Appearance Menu settings data (CREATE  action)
     *
     * @Route("/menu/crear", name="settings_appearance_menu_create", options={"expose"=true})
     * @Security("is_granted('edit', 'settings')")
     * @Method("POST")
     */
    public function createAppearanceMenuAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('menuData');
            $parametersCollection['isCreating'] = true;
            $parametersCollection['loggedUser'] = $this->getUser();

            $settingBussinessObj = new SettingBussiness($em);
            $response = $settingBussinessObj->saveAppearanceMenuData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Appearance Menu Item settings data (EDIT  action)
     *
     * @Route("/menu/items/editar", name="settings_appearance_menu_item_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'settings')")
     * @Method("POST")
     */
    public function editAppearanceMenuItemAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('menuItemData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $settingBussinessObj = new SettingBussiness($em);
            $response = $settingBussinessObj->saveAppearanceMenuItemData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Appearance Menu settings data (EDIT action)
     *
     * @Route("/menu/editar", name="settings_appearance_menu_edit", options={"expose"=true})
     * @Security("is_granted('edit', 'settings')")
     * @Method("POST")
     */
    public function editAppearanceMenuAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('menuData');
            $parametersCollection['isCreating'] = false;
            $parametersCollection['loggedUser'] = $this->getUser();

            $settingBussinessObj = new SettingBussiness($em);
            $response = $settingBussinessObj->saveAppearanceMenuData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Save Appearance Menu Item settings data (CREATE  action)
     *
     * @Route("/menu/items/crear", name="settings_appearance_menu_item_create", options={"expose"=true})
     * @Security("is_granted('edit', 'settings')")
     * @Method("POST")
     */
    public function createAppearanceMenuItemAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = $request->get('menuItemData');
            $parametersCollection['isCreating'] = true;
            $parametersCollection['loggedUser'] = $this->getUser();

            $settingBussinessObj = new SettingBussiness($em);
            $response = $settingBussinessObj->saveAppearanceMenuItemData($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Appearance Menus
     *
     * @Route("/menu/eliminar", name="settings_appearance_menu_delete", options={"expose"=true})
     * @Security("is_granted('edit', 'settings')")
     * @Method("POST")
     */
    public function deleteAppearanceMenusAction(Request $request){

        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['menusId'] = $request->get('menusId');

            $settingBussinessObj = new SettingBussiness($em);
            $response = $settingBussinessObj->deleteMenus($parametersCollection);
            return new JsonResponse($response);
        }
    }

    /**
     * Delete Appearance Menu Items
     *
     * @Route("/menu/items/eliminar", name="settings_appearance_menu_item_delete", options={"expose"=true})
     * @Security("is_granted('edit', 'settings')")
     * @Method("POST")
     */
    public function deleteAppearanceMenuItemsAction(Request $request){

        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            throw $this->createAccessDeniedException();
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $parametersCollection = array();
            $parametersCollection['menuItemsId'] = $request->get('menuItemsId');

            $settingBussinessObj = new SettingBussiness($em);
            $response = $settingBussinessObj->deleteMenuItems($parametersCollection);
            return new JsonResponse($response);
        }
    }

}
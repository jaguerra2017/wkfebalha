/*
 * File for handling controllers for Backend Settings Feature
 * */

(function () {
    'use strict';

    /* Declare app level module which depends on views, and components */
    angular.module('BncBackend.settingController', ['BncBackend.settingFactory']);


    /* Controller for handling Taxonomy functions */
    var settingCtrller = function($scope, settingFact){

        /*
         * Global variables
         * 
         * */
        var alfaNumericRegExpr = new RegExp("[A-Za-z]|[0-9]");
        var numericRegExpr = new RegExp("[0-9]");
        var dateRegExpress = new RegExp("[0-9]{2}/\[0-9]{2}/\[0-9]{4}");

        /*
         * Operations Functions
         * 
         * */
        /*after load settings data*/
        $scope.afterLoadSectionData = function(section){
            switch(section){
                case 'appearance':
                    //var
                    break;
            }
        }
        /* clear errors of the Availability form */
        $scope.clearErrorsAvailabilityForm = function(){
            $scope.model.availabilityDomainHasError = false;
            $scope.model.availabilityLaunchDateHasError = false;
            $scope.model.availabilityMessageTitleHasError = false;
            $scope.model.availabilityMessageBodyHasError = false;
        }

        /* clear errors of the Appearance Menu form */
        $scope.clearErrorsAppearanceMenuForm = function(){
            $scope.model.appearanceMenuNameHasError = false;
            $scope.model.appearanceMenuSlugHasError = false;
        }

        /* clear errors of the Appearance Menu Item form */
        $scope.clearErrorsAppearanceMenuItemForm = function(){
            $scope.model.appearanceMenuItemNameHasError = false;
            $scope.model.appearanceMenuItemSlugHasError = false;
            $scope.model.appearanceMenuItemTypeHasError = false;
            $scope.model.appearanceMenuItemTypePageHasError = false;
            $scope.model.appearanceMenuItemTypeLinkHasError = false;
            $scope.model.appearanceMenuItemTypePageTagHasError = false;
            $scope.model.appearanceMenuItemDescriptionHasError = false;
            $scope.model.appearanceMenuItemPriorityHasError = false;
        }

        /* clear errors of the Notification form */
        $scope.clearErrorsAppearanceThemeForm = function(){
            $scope.model.appearanceWelcomeTextHasError = false;
            $scope.model.appearanceFacebookUrlHasError = false;
            $scope.model.appearanceTwitterUrlHasError = false;
            $scope.model.appearanceYoutubeUrlHasError = false;
        }

        /* clear errors of the Notification form */
        $scope.clearErrorsNotificationForm = function(){
            $scope.model.notificationCheckPendingCommentsHasError = false;
            $scope.model.notificationCheckingCicleHasError = false;
            $scope.model.notificationTotalShowHasError = false;
        }

        /* clear errors of the Media form */
        $scope.clearErrorsMediaForm = function(){
            $scope.model.mediaMaxSizeUploadHasError = false;
            $scope.model.mediaMaxFilesUploadHasError = false;
        }

        /* clear values of the Appearance Menu form */
        $scope.clearAppearanceMenuForm = function(){
            $scope.model.appearanceMenuName = null;
            $scope.model.appearanceMenuSlug = null;
            $scope.model.appearanceMenuDescription = null;
        }

        /* clear values of the Appearance Menu Item form */
        $scope.clearAppearanceMenuItemForm = function(){
            $scope.model.appearanceMenuItemSelectedLanguage = $scope.model.languages[0];
            $scope.model.appearanceMenuItemName = null;
            $scope.model.appearanceMenuItemSlug = null;
            //$scope.model.appearanceMenuItemParentSelected = null;
            $scope.model.appearanceMenuItemTypeSelected = null;
            $scope.model.appearanceMenuItemTypePageSelected = null;
            $scope.model.appearanceMenuItemTypeLink = null;
            $scope.model.appearanceMenuItemTypePageTag = null;
            $scope.model.appearanceMenuItemDescription = null;
            $scope.model.appearanceMenuItemPriority = 100;
        }

        /* create menu */
        $scope.createMenu = function(){
            $scope.model.createMenuAction = true;
            $scope.showAppearanceMenuForm();
        }

        /* create menu item */
        $scope.createMenuItem = function(menuItemParent){

            if(menuItemParent != undefined){
                $scope.model.appearanceMenuItemParentSelected = menuItemParent;
            }
            else{
                $scope.model.appearanceMenuItemParentSelected = null;
            }
            $scope.model.createMenuItemAction = true;
            $scope.showAppearanceMenuItemForm();
        }

        /* delete menu */
        $scope.deleteMenu = function(menu){
            swal({
                    title: "Confirme ",
                    text: "Si confirma no será capaz de recuperar estos datos.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#F3565D",
                    cancelButtonColor: "#E5E5E5 !important",
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm)
                    {
                        $scope.model.createMenuAction = null;
                        var menusIdCollection = [];
                        menusIdCollection.push(menu.id);
                        var data = {
                            menusId: menusIdCollection
                        };
                        settingFact.DeleteAppearanceMenus($scope, data);
                    }
                });
        }

        /* delete menu item */
        $scope.deleteMenuItem = function(menuItem){
            swal({
                    title: "Confirme ",
                    text: "Si confirma no será capaz de recuperar estos datos.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#F3565D",
                    cancelButtonColor: "#E5E5E5 !important",
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm)
                    {
                        $scope.model.createMenuItemAction = null;
                        var menuItemsIdCollection = [];
                        menuItemsIdCollection.push(menuItem.id);
                        var data = {
                            menuItemsId: menuItemsIdCollection
                        };
                        settingFact.DeleteAppearanceMenuItems($scope, data);
                    }
                });
        }

        /* edit menu*/
        $scope.editMenu = function(menu){
            $scope.model.createMenuAction = false;
            $scope.model.selectedAppearanceMenu = menu;
            $scope.showAppearanceMenuForm();
        }

        /* edit menu item*/
        $scope.editMenuItem = function(menuItem){
            $scope.model.createMenuItemAction = false;
            $scope.model.selectedAppearanceMenuItem = menuItem;
            $scope.model.appearanceMenuItemParentSelected = null;
            $scope.showAppearanceMenuItemForm();
        }

        /* change settings parameter for Availability Section */
        $scope.changeAvailabilitySettings = function(event,parameter,value){
            switch(parameter){
                case 'domain_http_protocol':
                    $scope.model.availabilitySettingsObject.domain_http_protocol = value;
                    break;
                case 'status':
                    $scope.model.availabilitySettingsObject.status = value;
                    if(value == 'online'){
                        $scope.model.availabilitySettingsObject.launch_date = null;
                        $scope.model.availabilitySettingsObject.message_title = null;
                        $scope.model.availabilitySettingsObject.message_body = null;
                        $scope.clearErrorsAvailabilityForm();
                    }
                    break;
            }
        }

        /* change settings parameter for Notification Section */
        $scope.changeNotificationSettings = function(event){
            $scope.model.notificationSettingsObject.check_pending_comments = !$scope.model.notificationSettingsObject.check_pending_comments;
            if($scope.model.notificationSettingsObject.check_pending_comments == false){
                $scope.model.notificationSettingsObject.checking_cicle_minutes = null;
                $scope.model.notificationSettingsObject.total_show_in_box = null;
            }
            else{
                $scope.model.notificationSettingsObject.checking_cicle_minutes = 5;
                $scope.model.notificationSettingsObject.total_show_in_box = 3;
            }
            $scope.clearErrorsNotificationForm();
        }

        function findMenuItemParent(menuItemsCollection, childId){
            if(menuItemsCollection.length > 0){
                for(var i=0; i<menuItemsCollection.length; i++){
                    if(menuItemsCollection[i].id == childId){
                        $scope.model.appearanceMenuItemParentSelected = menuItemsCollection[i];
                        return;
                    }
                    else if(menuItemsCollection[i].childs.length > 0){
                        findMenuItemParent(menuItemsCollection[i].childs, childId);
                    }
                }
            }
            return;
        }

        /* get settings data for specific section */
        $scope.getSettingsData = function(section){
            var searchParametersCollection = {
                section: section
            }
            settingFact.getSettingsData($scope, searchParametersCollection);
        }

        /* get menus for Appearance Settings */
        $scope.getAppearanceMenus = function(){
            $scope.toggleDataLoader();
            settingFact.getAppearanceMenusData($scope);
        }

        /* get menu items for Menus in Appearance Settings */
        $scope.getAppearanceMenuItems = function(){
            $scope.toggleDataLoader();
            var parametersSearchCollection = {
                menuId: $scope.model.selectedAppearanceMenu.id
            }
            settingFact.getAppearanceMenuItemsData($scope, parametersSearchCollection);
        }

        /* function on scope for go ahead to top */
        $scope.goToTop = function()
        {
            var pageHeading = $('.navbar-fixed-top');/*#go-to-top-anchor*/
            $('html, body').animate({scrollTop: pageHeading.height()}, 1000);
        }

        /* handle key events triggered from input events in the CRUD form */
        $scope.handleMenuFormInputKeyEvents = function(event)
        {
            /* key events from input 'menu_name' */
            if(event.currentTarget.id == 'menu_name'){
                if(event.type == 'keyup' && $scope.model.appearanceMenuName.length > 0){
                    $scope.model.appearanceMenuSlug = slugify($scope.model.appearanceMenuName);
                }
                else if($scope.model.appearanceMenuName.length == 0){
                    $scope.model.appearanceMenuSlug = null;
                }
            }
            else if(event.currentTarget.id == 'menu_item_name'){
                if(event.type == 'keyup' && $scope.model.appearanceMenuItemName.length > 0){
                    $scope.model.appearanceMenuItemSlug = slugify($scope.model.appearanceMenuItemName);
                }
                else if($scope.model.appearanceMenuItemName.length == 0){
                    $scope.model.appearanceMenuItemSlug = null;
                }
            }
        }

        /*hide the form for Appearance menu CRUD*/
        $scope.hideAppearanceMenuForm = function(){
            $scope.model.createMenuAction = null;
            $('#appearance-menu-crud-modal').modal('hide');
            $scope.getAppearanceMenus();
        }

        /*hide the form for Appearance menu item CRUD*/
        $scope.hideAppearanceMenuItemForm = function(){
            $scope.model.createMenuItemAction = null;
            $scope.model.showMenuItemForm = false;
            $('#appearance-menu-item-crud-modal').modal('hide');
            $scope.getAppearanceMenuItems();
        }

        $scope.hideMenuItemParentsSelector = function(){
            $('#appearance-menu-item-parent-modal').modal('hide');
        }

        /*load the configured Menus in Appearance Section*/
        $scope.loadAppearanceMenus = function(){
            if(!$scope.model.appearanceMenuSettingsLoaded){
                $scope.model.appearanceMenuSettingsLoaded = true;
                $scope.getAppearanceMenus();
            }
        }

        /*load the configured Menu Items for Menus in Appearance Section*/
        $scope.loadAppearanceMenuItems = function(menu){
            $scope.model.selectedAppearanceMenu = menu;
            $scope.getAppearanceMenuItems();
        }

        /* load data for the selected section */
        $scope.loadSettingsSectionData = function(section){
            var proceed = false;
            switch (section){
                case 'availability':
                    $scope.model.sectionTitle = 'Dominio';
                    $scope.model.sectionSubTitle = 'de publicación, status y más..';
                    if(!$scope.model.availabilitySettingsLoaded){
                        proceed = true;
                        $scope.model.availabilitySettingsLoaded = true;
                        $scope.clearErrorsAvailabilityForm();
                    }
                    break;
                case 'appearance':
                    $scope.model.sectionTitle = 'Temas';
                    $scope.model.sectionSubTitle = 'del sitio, presentación y menú..';
                    if(!$scope.model.appearanceSettingsLoaded){
                        proceed = true;
                        $scope.model.appearanceSettingsLoaded = true;
                        $scope.clearErrorsAppearanceThemeForm();
                    }
                    break;
                case 'notification':
                    $scope.model.sectionTitle = 'Chequeo';
                    $scope.model.sectionSubTitle = 'de nuevos comentarios...';
                    if(!$scope.model.notificationSettingsLoaded){
                        proceed = true;
                        $scope.model.notificationSettingsLoaded = true;
                        $scope.clearErrorsNotificationForm();
                    }
                    break;
                case 'media':
                    $scope.model.sectionTitle = 'Tamaño';
                    $scope.model.sectionSubTitle = 'de imágenes, peso y más...';
                    if(!$scope.model.mediaSettingsLoaded){
                        proceed = true;
                        $scope.model.mediaSettingsLoaded = true;
                        $scope.clearErrorsMediaForm();
                    }
                    break;
            }
            if(proceed){
                $scope.toggleDataLoader();
                $scope.getSettingsData(section);
            }
        }

        /* save setting for Availability Section */
        $scope.saveAvailabilitySectionData = function(){
            $scope.toggleDataLoader();
            var canProceed = true;
            $scope.clearErrorsAvailabilityForm();

            if($scope.model.availabilitySettingsObject.domain_name == null ||
                !alfaNumericRegExpr.test($scope.model.availabilitySettingsObject.domain_name) ||
                ($scope.model.availabilitySettingsObject.status == 'offline' &&
                ($scope.model.availabilitySettingsObject.launch_date == null ||
                !dateRegExpress.test($scope.model.availabilitySettingsObject.launch_date) ||
                $scope.model.availabilitySettingsObject.message_title  == null ||
                !alfaNumericRegExpr.test($scope.model.availabilitySettingsObject.message_title) ||
                $scope.model.availabilitySettingsObject.message_body == null ||
                !alfaNumericRegExpr.test($scope.model.availabilitySettingsObject.message_body)))){
                canProceed = false;

                if($scope.model.availabilitySettingsObject.domain_name == null ||
                    !alfaNumericRegExpr.test($scope.model.availabilitySettingsObject.domain_name)){
                    $scope.model.availabilityDomainHasError = true;
                }

                if($scope.model.availabilitySettingsObject.status == 'offline' &&
                    ($scope.model.availabilitySettingsObject.launch_date == null ||
                    !dateRegExpress.test($scope.model.availabilitySettingsObject.launch_date))){
                    $scope.model.availabilityLaunchDateHasError = true;
                }

                if($scope.model.availabilitySettingsObject.status == 'offline' &&
                    ($scope.model.availabilitySettingsObject.message_title  == null ||
                    !alfaNumericRegExpr.test($scope.model.availabilitySettingsObject.message_title))){
                    $scope.model.availabilityMessageTitleHasError = true;
                }

                if($scope.model.availabilitySettingsObject.status == 'offline' &&
                    ($scope.model.availabilitySettingsObject.message_body  == null ||
                    !alfaNumericRegExpr.test($scope.model.availabilitySettingsObject.message_body))){
                    $scope.model.availabilityMessageBodyHasError = true;
                }
            }

            if(canProceed){
                var settingData = {
                    section: 'availability',
                    settingData: $scope.model.availabilitySettingsObject
                };
                settingFact.SaveSettingsData($scope, settingData);
            }
            else{
                $scope.toggleDataLoader();
                toastr.options.timeOut = 3000;
                toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
            }
        }

        /* save setting for Appearance Theme Section */
        $scope.saveAppearanceThemeSectionData = function(){
            $scope.toggleDataLoader();
            var canProceed = true;
            $scope.clearErrorsAppearanceThemeForm();

            /*if($scope.model.appearanceSettingsObject.configurations[0].welcome_text == null ||
             !alfaNumericRegExpr.test($scope.model.appearanceSettingsObject.configurations[0].welcome_text) ||
             $scope.model.appearanceSettingsObject.configurations[1].facebook_url == null ||
             !alfaNumericRegExpr.test($scope.model.appearanceSettingsObject.configurations[1].facebook_url) ||
             $scope.model.appearanceSettingsObject.configurations[1].twitter_url == null ||
             !alfaNumericRegExpr.test($scope.model.appearanceSettingsObject.configurations[1].twitter_url) ||
             $scope.model.appearanceSettingsObject.configurations[1].youtube_url == null ||
             !alfaNumericRegExpr.test($scope.model.appearanceSettingsObject.configurations[1].youtube_url))
             {
             canProceed = false;

             if($scope.model.appearanceSettingsObject.configurations[0].welcome_text == null ||
             !alfaNumericRegExpr.test($scope.model.appearanceSettingsObject.configurations[0].welcome_text)){
             $scope.model.appearanceWelcomeTextHasError = true;
             }

             if($scope.model.appearanceSettingsObject.configurations[1].facebook_url == null ||
             !alfaNumericRegExpr.test($scope.model.appearanceSettingsObject.configurations[1].facebook_url)){
             $scope.model.appearanceFacebookUrlHasError = true;
             }

             if($scope.model.appearanceSettingsObject.configurations[1].twitter_url == null ||
             !alfaNumericRegExpr.test($scope.model.appearanceSettingsObject.configurations[1].twitter_url)){
             $scope.model.appearanceTwitterUrlHasError = true;
             }

             if($scope.model.appearanceSettingsObject.configurations[1].youtube_url == null ||
             !alfaNumericRegExpr.test($scope.model.appearanceSettingsObject.configurations[1].youtube_url)){
             $scope.model.appearanceYoutubeUrlHasError = true;
             }
             }*/

            if(canProceed){
                var settingData = {
                    section: 'appearance',
                    settingData: $scope.model.appearanceSettingsObject
                };
                settingFact.SaveSettingsData($scope, settingData);
            }
            else{
                $scope.toggleDataLoader();
                toastr.options.timeOut = 3000;
                toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
            }
        }

        /*save settings for Appearance Menu */
        $scope.saveAppearanceThemeSectionMenuData = function(option){
            $scope.toggleDataLoader();
            var canProceed = true;
            $scope.clearErrorsAppearanceMenuForm();
            var menuData = {};
            menuData.name = $scope.model.appearanceMenuName;
            menuData.slug = $scope.model.appearanceMenuSlug;
            menuData.description = $scope.model.appearanceMenuDescription;
            if(menuData.name == null || !alfaNumericRegExpr.test(menuData.name) ||
                menuData.slug == null || !alfaNumericRegExpr.test(menuData.slug)){
                canProceed = false;
                if(menuData.name == null || !alfaNumericRegExpr.test(menuData.name)){
                    $scope.model.appearanceMenuNameHasError = true;
                }
                if(menuData.slug == null || !alfaNumericRegExpr.test(menuData.slug)){
                    $scope.model.appearanceMenuSlugHasError = true;
                }
            }

            if(canProceed){
                if(!$scope.model.createMenuAction){
                    menuData.menu_id = $scope.model.selectedAppearanceMenu.id
                }
                var menuData = {menuData: menuData};
                var action = $scope.model.createMenuAction == true ? 'create' : 'edit';

                settingFact.SaveAppearanceMenuData($scope, menuData, option, action);
            }
            else{
                $scope.toggleDataLoader();
                toastr.options.timeOut = 3000;
                toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
            }
        }

        /* save settings for Appearance Menu Item */
        $scope.saveAppearanceThemeSectionMenuItemData = function(option){
            $scope.toggleDataLoader();
            var canProceed = true;
            $scope.clearErrorsAppearanceMenuItemForm();
            var menuItemData = {};
            menuItemData.name = $scope.model.appearanceMenuItemName;
            menuItemData.url_slug = $scope.model.appearanceMenuItemSlug;
            menuItemData.description = $scope.model.appearanceMenuItemDescription;
            menuItemData.parent_id = $scope.model.appearanceMenuItemParentSelected != null?$scope.model.appearanceMenuItemParentSelected.id:null;
            menuItemData.item_type_id = $scope.model.appearanceMenuItemTypeSelected != null?$scope.model.appearanceMenuItemTypeSelected.id:null;
            menuItemData.item_type_page_id = $scope.model.appearanceMenuItemTypePageSelected != null?$scope.model.appearanceMenuItemTypePageSelected.id:null;
            menuItemData.item_type_page_tag = $scope.model.appearanceMenuItemTypePageTag;
            menuItemData.item_type_link = $scope.model.appearanceMenuItemTypeLink;
            menuItemData.priority = $scope.model.appearanceMenuItemPriority;
            if(
                menuItemData.name == null || !alfaNumericRegExpr.test(menuItemData.name) ||
                menuItemData.url_slug == null || !alfaNumericRegExpr.test(menuItemData.url_slug) ||
                menuItemData.priority == null || !numericRegExpr.test(menuItemData.priority) ||
                menuItemData.item_type_id == null ||
                (menuItemData.item_type_id != null &&
                $scope.model.appearanceMenuItemTypeSelected.tree_slug == 'menu-item-type-page' &&
                menuItemData.item_type_page_id == null) ||
                (menuItemData.item_type_id != null &&
                $scope.model.appearanceMenuItemTypeSelected.tree_slug == 'menu-item-type-url' &&
                (menuItemData.item_type_link == null || !alfaNumericRegExpr.test(menuItemData.item_type_link)))
            ){
                canProceed = false;

                if(menuItemData.name == null || !alfaNumericRegExpr.test(menuItemData.name)){
                    $scope.model.appearanceMenuItemNameHasError = true;
                }

                if(menuItemData.url_slug == null || !alfaNumericRegExpr.test(menuItemData.url_slug)){
                    $scope.model.appearanceMenuItemSlugHasError = true;
                }

                if(menuItemData.priority == null || !numericRegExpr.test(menuItemData.priority)){
                    $scope.model.appearanceMenuItemPriorityHasError = true;
                }

                if(menuItemData.item_type_id == null){
                    $scope.model.appearanceMenuItemTypeHasError = true;
                }

                if(menuItemData.item_type_id != null &&
                    $scope.model.appearanceMenuItemTypeSelected.tree_slug == 'menu-item-type-page' &&
                    menuItemData.item_type_page_id == null){
                    $scope.model.appearanceMenuItemTypePageHasError = true;
                }

                if(menuItemData.item_type_id != null &&
                    $scope.model.appearanceMenuItemTypeSelected.tree_slug == 'menu-item-type-url' &&
                    (menuItemData.item_type_link == null || !alfaNumericRegExpr.test(menuItemData.item_type_link))){
                    $scope.model.appearanceMenuItemTypeLinkHasError = true;
                }
            }

            if(canProceed){
                if(!$scope.model.createMenuItemAction){
                    menuItemData.menu_item_id = $scope.model.selectedAppearanceMenuItem.id
                }
                menuItemData.menu_id = $scope.model.selectedAppearanceMenu.id;
                menuItemData.language = $scope.model.appearanceMenuItemSelectedLanguage.slug;
                var menuItemData = {menuItemData: menuItemData};
                var action = $scope.model.createMenuItemAction == true ? 'create' : 'edit';

                settingFact.SaveAppearanceMenuItemData($scope, menuItemData, option, action);
            }
            else{
                $scope.toggleDataLoader();
                toastr.options.timeOut = 3000;
                toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
            }
        }

        /* save setting for Notification Section */
        $scope.saveNotificationSectionData = function(){
            $scope.toggleDataLoader();
            var canProceed = true;
            $scope.clearErrorsNotificationForm();

            if( $scope.model.notificationSettingsObject.check_pending_comments == true &&
                ($scope.model.notificationSettingsObject.checking_cicle_minutes == null ||
                !numericRegExpr.test($scope.model.notificationSettingsObject.checking_cicle_minutes) ||
                $scope.model.notificationSettingsObject.checking_cicle_minutes < 2 ||
                $scope.model.notificationSettingsObject.total_show_in_box == null ||
                !numericRegExpr.test($scope.model.notificationSettingsObject.total_show_in_box) ||
                ($scope.model.notificationSettingsObject.total_show_in_box == 0 &&
                $scope.model.notificationSettingsObject.total_show_in_box > 3) )){
                canProceed = false;

                if($scope.model.notificationSettingsObject.checking_cicle_minutes == null ||
                    !numericRegExpr.test($scope.model.notificationSettingsObject.checking_cicle_minutes) ||
                    $scope.model.notificationSettingsObject.checking_cicle_minutes < 2){
                    $scope.model.notificationCheckingCicleHasError = true;
                }

                if($scope.model.notificationSettingsObject.total_show_in_box == null ||
                    !numericRegExpr.test($scope.model.notificationSettingsObject.total_show_in_box) ||
                    ($scope.model.notificationSettingsObject.total_show_in_box == 0 &&
                    $scope.model.notificationSettingsObject.total_show_in_box > 3)){
                    $scope.model.notificationTotalShowHasError = true;
                }
            }

            if(canProceed){
                if($scope.model.notificationSettingsObject.check_pending_comments == false){
                    $scope.model.notificationSettingsObject.checking_cicle_minutes = null;
                    $scope.model.notificationSettingsObject.total_show_in_box = null;
                }
                var settingData = {
                    section: 'notification',
                    settingData: $scope.model.notificationSettingsObject
                };
                settingFact.SaveSettingsData($scope, settingData);
            }
            else{
                $scope.toggleDataLoader();
                toastr.options.timeOut = 3000;
                toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
            }
        }

        /* save settings for Media section */
        $scope.saveMediaSectionData = function(){
            $scope.toggleDataLoader();
            var canProceed = true;
            $scope.clearErrorsMediaForm();

            if( $scope.model.mediaSettingsObject.max_size_upload == null ||
                !numericRegExpr.test($scope.model.mediaSettingsObject.max_size_upload) ||
                $scope.model.mediaSettingsObject.max_size_upload < 1 ||
                $scope.model.mediaSettingsObject.max_size_upload > $scope.model.mediaSettingsObject.system_max_filesize ||
                $scope.model.mediaSettingsObject.max_files_upload == null ||
                !numericRegExpr.test($scope.model.mediaSettingsObject.max_files_upload) ||
                $scope.model.mediaSettingsObject.max_files_upload < 1 ||
                $scope.model.mediaSettingsObject.max_files_upload > $scope.model.mediaSettingsObject.system_max_filesupload){
                canProceed = false;

                if($scope.model.mediaSettingsObject.max_size_upload == null ||
                    !numericRegExpr.test($scope.model.mediaSettingsObject.max_size_upload) ||
                    $scope.model.mediaSettingsObject.max_size_upload < 1 ||
                    $scope.model.mediaSettingsObject.max_size_upload > $scope.model.mediaSettingsObject.system_max_filesize){
                    $scope.model.mediaMaxSizeUploadHasError = true;
                }

                if($scope.model.mediaSettingsObject.max_files_upload == null ||
                    !numericRegExpr.test($scope.model.mediaSettingsObject.max_files_upload) ||
                    $scope.model.mediaSettingsObject.max_files_upload < 1 ||
                    $scope.model.mediaSettingsObject.max_files_upload > $scope.model.mediaSettingsObject.system_max_filesupload){

                    $scope.model.mediaMaxFilesUploadHasError = true;
                }
            }

            if(canProceed){
                var settingData = {
                    section: 'media',
                    settingData: $scope.model.mediaSettingsObject
                };
                settingFact.SaveSettingsData($scope, settingData);
            }
            else{
                $scope.toggleDataLoader();
                toastr.options.timeOut = 3000;
                toastr.error("El formulario tiene valores incorrectos o en blanco.","¡Error!");
            }
        }

        /*selecting/deselecting menu item */
        $scope.selectMenuItem= function(event,menuItem){
            menuItem.selected = !menuItem.selected;
        }

        /* select the Menu Item Parent */
        $scope.selectMenuItemParent = function(menuItemsCollection){
            var originalMenuItemsCollection = false;
            if(menuItemsCollection == undefined){
                menuItemsCollection = $scope.model.appearanceMenuItemsCollection;
                originalMenuItemsCollection = true;
            }
            if(menuItemsCollection.length > 0){
                for(var i=0; i<menuItemsCollection.length; i++){
                    if(menuItemsCollection[i].selected){
                        $scope.model.appearanceMenuItemParentSelected = menuItemsCollection[i];
                        $scope.hideMenuItemParentsSelector();
                        return;
                    }
                    else if(menuItemsCollection[i].childs.length > 0){
                        $scope.selectMenuItemParent(menuItemsCollection[i].childs);
                    }
                }
            }

            if(originalMenuItemsCollection){
                $scope.model.appearanceMenuItemParentSelected = null;
                $scope.hideMenuItemParentsSelector();
            }
            return;
        }

        /*select appearance Welcome Image*/
        $scope.selectAppearanceWelcomeImage = function(){
            $('#media-images-selector-modal').modal('show');
        }

        /* slugify text */
        function slugify(textToSlugify){
            var slugifiedText = textToSlugify.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\\-]+/g, '')
                .replace(/\\-\\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');

            return slugifiedText;
        }

        /*show the form for Appearance menu CRUD*/
        $scope.showAppearanceMenuForm = function(){
            $scope.clearErrorsAppearanceMenuForm();
            $scope.clearAppearanceMenuForm();
            if(!$scope.model.createMenuAction){
                $scope.model.appearanceMenuName = $scope.model.selectedAppearanceMenu.name;
                $scope.model.appearanceMenuSlug = $scope.model.selectedAppearanceMenu.slug;
                $scope.model.appearanceMenuDescription = $scope.model.selectedAppearanceMenu.description;
            }
            $('#appearance-menu-crud-modal').modal('show');
        }

        /*show the form for Appearance menu item CRUD*/
        $scope.showAppearanceMenuItemForm = function(){
            $scope.clearErrorsAppearanceMenuItemForm();
            $scope.clearAppearanceMenuItemForm();
            if($scope.model.createMenuItemAction){
                $scope.model.appearanceMenuItemSelectedLanguage = $scope.model.languages[0]; /*es*/
            }
            if(!$scope.model.createMenuItemAction){
                $scope.model.appearanceMenuItemName = $scope.model.selectedAppearanceMenuItem.name_es;
                $scope.model.appearanceMenuItemSlug = $scope.model.selectedAppearanceMenuItem.url_slug_es;
                $scope.model.appearanceMenuItemPriority = $scope.model.selectedAppearanceMenuItem.priority;
                $scope.model.appearanceMenuItemDescription = $scope.model.selectedAppearanceMenuItem.description_es;

                console.log($scope.model.selectedAppearanceMenuItem);
                if($scope.model.selectedAppearanceMenuItem.parent_id != null){
                    findMenuItemParent($scope.model.appearanceMenuItemsCollection, $scope.model.selectedAppearanceMenuItem.parent_id);
                }
                else{
                    $scope.model.appearanceMenuItemParentSelected = null;
                }
                if($scope.model.appearanceMenuItemTypesCollection.length > 0){
                    for(var i=0; i<$scope.model.appearanceMenuItemTypesCollection.length; i++){
                        if($scope.model.appearanceMenuItemTypesCollection[i].id ==
                            $scope.model.selectedAppearanceMenuItem.menu_item_type_id){
                            $scope.model.appearanceMenuItemTypeSelected = $scope.model.appearanceMenuItemTypesCollection[i];
                            if($scope.model.appearanceMenuItemTypeSelected.tree_slug == 'menu-item-type-url'){
                                $scope.model.appearanceMenuItemTypeLink = $scope.model.selectedAppearanceMenuItem.link_url;
                            }
                            else{

                            }
                            break;
                        }
                    }
                }

            }
            $scope.model.showMenuItemForm = true;
            $('#appearance-menu-item-crud-modal').modal('show');
        }

        /* show the modal with the candidates for Menu Item Parent */
        $scope.showMenuItemParentsSelector = function(){

            $('#appearance-menu-item-parent-modal').modal('show');
        }

        /* toggle data-loading message */
        $scope.toggleDataLoader = function()
        {
            $scope.model.loadingData = !$scope.model.loadingData;
        }

        /* update the styles of the I-checks components(radio or checkbox) */
        $scope.updateICheckStyles = function(event, suffix){

            var eventType = null;
            /*ensuring the event comes from the view action*/
            if(typeof event == 'object'){
                if(event == null){eventType = 'click';}
                else{eventType = event.type;}
            }
            else{eventType = event;}

            /*if event is 'mouseover'*/
            if(eventType == 'mouseover'){
                $('.'+suffix).addClass('hover');
            }
            else if(eventType == 'mouseleave'){
                $('.'+suffix).removeClass('hover');
            }
        }

        /* update the Menu Item Type selected */
        $scope.updateMenuItemTypeSelected = function(menuItemType){
            $scope.model.appearanceMenuItemTypeSelected = menuItemType;
        }

        $scope.selectMenuItemCrudLanguage = function(language){
            $scope.model.appearanceMenuItemSelectedLanguage = language;
        }


        $scope.$watch('model.welcomeImage', function(newValue, oldValue) {
            if(newValue != null && newValue != oldValue && newValue.url != undefined){
                $scope.model.appearanceSettingsObject.configurations[0].welcome_image_url = newValue.url;
            }
        });


        /*
         * Initialization Functions
         *
         * */
        $scope.initVisualization = function (){
            /*general variables*/
            $scope.model.createMenuAction = null;
            $scope.model.createMenuItemAction = null;
            $scope.model.showMenuItemForm = false;
            $scope.model.loadingData = false;
            $scope.model.availabilitySettingsLoaded = true;
            $scope.model.sectionTitle = 'Dominio';
            $scope.model.sectionSubTitle = 'de publicación, status y más..';
            /*form view variables*/
            $scope.model.processingData = false;
            $scope.clearErrorsAvailabilityForm();
        }
        function initValues(){
            /*generals variables*/
            $scope.model = {};
            $scope.success = false;
            $scope.error = false;
            /*sections variables*/
            $scope.model.availabilitySettingsObject = {};
            $scope.model.appearanceSettingsObject = {};
            $scope.model.appearanceMenusCollection = [];
            $scope.model.appearanceMenuItemsCollection = [];
            $scope.model.appearanceMenuItemTypesCollection = [];
            $scope.model.selectedAppearanceMenu = null;
            $scope.model.selectedAppearanceMenuItem = null;
            $scope.model.notificationSettingsObject = {};
            $scope.model.mediaSettingsObject = {};
            $scope.model.availabilitySettingsLoaded = false;
            $scope.model.appearanceSettingsLoaded = false;
            $scope.model.appearanceMenuSettingsLoaded = false;
            $scope.model.notificationSettingsLoaded = false;
            $scope.model.mediaSettingsLoaded = false;
            $scope.model.languages = [
                {
                    name: 'Español',
                    slug: 'es'
                },
                {
                    name: 'Inglés',
                    slug: 'en'
                }
            ];
            /*appearance-header-section  variables*/
            $scope.model.welcomeImage = {};

            $scope.clearErrorsAvailabilityForm();
            var searchParametersCollection = {
                section: 'availability'
            }
            settingFact.loadInitialsData($scope, searchParametersCollection);
        }
        function init(){
            initValues();
        }
        init();

    }


    /* Declaring controllers functions for this module */
    angular.module('BncBackend.settingController').controller('settingCtrller',settingCtrller);
})();
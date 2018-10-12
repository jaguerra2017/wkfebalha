<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Bussiness\FrontendMainBussiness;
use AppBundle\Bussiness\FrontendHomeBussiness;


/**
 * FRONTEND - Main controller.
 *
 *@Route("/")
 */
class FrontendMainController extends Controller
{

    /**
     * @Route("/", name="_frontend_index")
     * @Method("GET")
     */
    public function _frontendIndexAction(Request $request)
    {
        return $this->redirectToRoute('frontend_index');
    }

    /**
     * @Route("/{_locale}", name="frontend_index", requirements={"_locale"="es|en"})
     * @Method("GET")
     */
    public function frontendIndexAction(Request $request)
    {
        $desiredLocale = $request->get('_locale');
        if(!isset($desiredLocale)){
            return $this->redirect($this->generateUrl('frontend_index', array(
            "request"=>$request,
            "_locale" =>'es')));
        }
        else {
            $currentLocale = $request->getSession()->get('_locale');
            if(!isset($currentLocale)){
                $request->getSession()->set('_locale', $desiredLocale);
            }
            else {
                if($desiredLocale != $currentLocale){
                    $request->getSession()->set('_locale', $desiredLocale);
                }
            }
        }


        if($this->checkIsSiteIsAvailableAction()){

            $em = $this->getDoctrine()->getManager();
            $container = $this->container;
            $objFrontendHomeBussiness = new FrontendHomeBussiness($em, $container);
            $homeData = $objFrontendHomeBussiness->getHomeData();

            $currentTheme = $this->get('appbundle_site_settings')->getCurrentTheme();
            return $this->render('@app_frontend_template_directory/themes/'.$currentTheme.'/index.html.twig',
            $homeData);
        }
        else{/*show the OFFLINE screen */
            return $this->showOfflineScreenAction();
        }
    }
    /**
     * @Route("/{_locale}/", name="_frontend_index_current_language", defaults={"_locale"="es"}, requirements={"_locale"="es|en"})
     * @Method("GET")
     */
    public function _frontendIndexLanguageAction(Request $request)
    {
        return $this->redirectToRoute('frontend_index');

    }



    /**
     * @Route("/{_locale}/{section}", name="frontend_section", defaults={"_locale"="es"}, requirements={"_locale"="es|en"})
     * @Method("GET")
     */
    public function frontendSectionAction(Request $request)
    {
        $parametersCollection = array();
        $parametersCollection['request_object'] = $request;
        $parametersCollection['section'] = $request->get('section');

        return $this->redirectToRoute('backend_index', array(
            "request"=>$request,
            "_locale" =>$request->getSession()->get('_locale')
        ));

        /*$em = $this->getDoctrine()->getManager();
        $container = $this->container;
        $objFrontendHomeBussiness = new FrontendHomeBussiness($em, $container);
        $requestHandler = $objFrontendHomeBussiness->handleRequest($parametersCollection);

        return $this->render($requestHandler['template_requested_directory'], array(
            'themeConfigsData' => $requestHandler['themeConfigsData']
        ));*/
    }
    /**
     * @Route("/{_locale}/{section}/", name="_frontend_section", defaults={"_locale"="es"}, requirements={"_locale"="es|en"})
     * @Method("GET")
     */
    public function _frontendSectionAction(Request $request)
    {
        return $this->redirectToRoute('frontend_section',array(
            "section"=>$request->get('section')
        ));

    }


    /**
     * @Route("/{_locale}/{section}/{url_slug}", name="frontend_section_element", defaults={"_locale"="es"}, requirements={"_locale"="es|en"})
     * @Method("GET")
     */
    public function frontendSectionElementAction(Request $request)
    {
        $parametersCollection = array();
        $parametersCollection['request_object'] = $request;
        $parametersCollection['section'] = $request->get('section');
        $parametersCollection['url_slug'] = $request->get('url_slug');
        $em = $this->getDoctrine()->getManager();
        $container = $this->container;
        $objFrontendHomeBussiness = new FrontendHomeBussiness($em, $container);
        $requestHandler = $objFrontendHomeBussiness->handleRequest($parametersCollection);
        return $this->render($requestHandler['template_requested_directory'], array(
            'themeConfigsData' => $requestHandler['themeConfigsData']
        ));
    }
    /**
     * @Route("/{_locale}/{section}/{url_slug}/", name="_frontend_section_element", defaults={"_locale"="es"}, requirements={"_locale"="es|en"})
     * @Method("GET")
     */
    public function _frontendSectionElementAction(Request $request)
    {
        return $this->redirectToRoute('frontend_section',array(
            "section"=>$request->get('section'),
            "url_slug"=>$request->get('url_slug')
        ));

    }


    /**
     * @Route("/{_locale}/{section}/{url_slug}/{tag}", name="frontend_section_element_tag", defaults={"_locale"="es"}, requirements={"_locale"="es|en"})
     * @Method("GET")
     */
    public function frontendSectionElementTagAction(Request $request)
    {
        $parametersCollection = array();
        $parametersCollection['request_object'] = $request;
        $parametersCollection['section'] = $request->get('section');
        $parametersCollection['url_slug'] = $request->get('url_slug');
        $parametersCollection['tag'] = $request->get('tag');
        $em = $this->getDoctrine()->getManager();
        $container = $this->container;
        $objFrontendHomeBussiness = new FrontendHomeBussiness($em, $container);
        $requestHandler = $objFrontendHomeBussiness->handleRequest($parametersCollection);
        return $this->render($requestHandler['template_requested_directory'], array(
            'themeConfigsData' => $requestHandler['themeConfigsData']
        ));
    }
    /**
     * @Route("/{_locale}/{section}/{url_slug}/{tag}/", name="_frontend_section_element_tag", defaults={"_locale"="es"}, requirements={"_locale"="es|en"})
     * @Method("GET")
     */
    public function _frontendSectionElementTagAction(Request $request)
    {
        return $this->redirectToRoute('frontend_section',array(
            "section"=>$request->get('section'),
            "url_slug"=>$request->get('url_slug'),
            "tag"=>$request->get('tag')
        ));

    }




    public function checkIsSiteIsAvailableAction(){
        $isSiteStatusOnline = $this->get('appbundle_site_settings')->isSiteStatusOnline();
        $isAuthenticatedFully = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
        $couldUserSeeSiteOffline = false;
        if($isAuthenticatedFully && $this->getUser()->getRole()->geSeeSiteStatusOffline() == true){
            $couldUserSeeSiteOffline = true;
        }
        /* if the site is online or it's accessible by user */
        if($isSiteStatusOnline || (!$isSiteStatusOnline && $couldUserSeeSiteOffline)){
            return true;
        }
        return false;
    }

    public function showOfflineScreenAction(){
        $offlineSiteSettings = $this->get('appbundle_site_settings')->getSectionSettingsData('availability');
        $appearanceSettings = $this->get('appbundle_site_settings')->getSectionSettingsData('appearance');
        $currentTheme = $this->get('appbundle_site_settings')->getCurrentTheme();
        return $this->render('@app_frontend_template_directory/themes/'.$currentTheme.'/offline.html.twig',array(
            'offlineSiteSettings'=>$offlineSiteSettings,
            'facebookLink'=>$appearanceSettings['configurations'][1]['facebook_url'],
            'twitterLink'=>$appearanceSettings['configurations'][1]['twitter_url'],
            'youTubeLink'=>$appearanceSettings['configurations'][1]['youtube_url']
        ));
    }

}

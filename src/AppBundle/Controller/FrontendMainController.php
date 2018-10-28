<?php

namespace AppBundle\Controller;

use AppBundle\Bussiness\ApiBussiness;
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
        return $this->redirectToRoute('frontend_index_es');
    }

    /**
     * @Route("/es", name="frontend_index_es")
     * @Method("GET")
     */
    public function frontendIndexEsAction(Request $request)
    {

        $this->checkLocaleValues($request);

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
     * @Route("/es/", name="_frontend_index_es")
     * @Method("GET")
     */
    public function _frontendIndexEsAction(Request $request)
    {
        return $this->redirectToRoute('frontend_index_es');

    }
    /**
     * @Route("/en", name="frontend_index_en")
     * @Method("GET")
     */
    public function frontendIndexEnAction(Request $request)
    {

        $this->checkLocaleValues($request, 'en');

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
     * @Route("/en/", name="_frontend_index_en")
     * @Method("GET")
     */
    public function _frontendIndexEnAction(Request $request)
    {
        return $this->redirectToRoute('frontend_index_en');

    }



    /**
     * @Route("/es/{section}", name="frontend_section_es")
     * @Method("GET")
     */
    public function frontendSectionEsAction(Request $request)
    {

        $this->checkLocaleValues($request);

        $parametersCollection = array();
        $parametersCollection['request_object'] = $request;
        $parametersCollection['section'] = $request->get('section');

        $em = $this->getDoctrine()->getManager();
        $container = $this->container;
        $objFrontendHomeBussiness = new FrontendHomeBussiness($em, $container);
        $requestHandler = $objFrontendHomeBussiness->handleRequest($parametersCollection);

        return $this->render($requestHandler['template_requested_directory'], array(
            'themeConfigsData' => $requestHandler['themeConfigsData']
        ));
    }
    /**
     * @Route("/es/{section}/", name="_frontend_section_es")
     * @Method("GET")
     */
    public function _frontendSectionEsAction(Request $request)
    {
        return $this->redirectToRoute('frontend_section_es',array(
            "section"=>$request->get('section')
        ));
    }
    /**
     * @Route("/en/{section}", name="frontend_section_en")
     * @Method("GET")
     */
    public function frontendSectionEnAction(Request $request)
    {

        $this->checkLocaleValues($request, 'en');

        $parametersCollection = array();
        $parametersCollection['request_object'] = $request;
        $parametersCollection['section'] = $request->get('section');

        $em = $this->getDoctrine()->getManager();
        $container = $this->container;
        $objFrontendHomeBussiness = new FrontendHomeBussiness($em, $container);
        $requestHandler = $objFrontendHomeBussiness->handleRequest($parametersCollection);

        return $this->render($requestHandler['template_requested_directory'], array(
            'themeConfigsData' => $requestHandler['themeConfigsData']
        ));
    }
    /**
     * @Route("/en/{section}/", name="_frontend_section_en")
     * @Method("GET")
     */
    public function _frontendSectionEnAction(Request $request)
    {
        return $this->redirectToRoute('frontend_section_en',array(
            "section"=>$request->get('section')
        ));

    }


    /**
     * @Route("/es/{section}/{url_slug}", name="frontend_section_element_es")
     * @Method({"GET","POST"})
     */
    public function frontendSectionElementEsAction(Request $request)
    {
        $this->checkLocaleValues($request);
        if($request->getMethod() == 'POST'){
          /*getting soy cubano parameters*/
          $id_transaccion = $request->get('id_transaccion');
          $notrans = $request->get('notrans');
          $resultado = $request->get('resultado');
          $codig = $request->get('codig');

          /*checking if the requeste comes from soy cubano*/
          if($id_transaccion && $id_transaccion != ''
            && $notrans && $notrans != ''
            && $resultado && $resultado != ''
            && $codig && $codig != ''
          ){
            try{
              $em = $this->getDoctrine()->getManager();
              $apiBussiness = new ApiBussiness($em, $this->container);
              $params = array(
                'id_transaction'=>$id_transaccion,
                'notrans'=>$notrans,
                'resultado'=>$resultado,
                'codig'=>$codig
              );
              $result = $apiBussiness->checkInvoice($params);
              if($result != 'error'){
                $em = $this->getDoctrine()->getManager();
                $container = $this->container;
                $parametersCollection = array();
                $parametersCollection['request_object'] = $request;
                $parametersCollection['section'] = $request->get('section');
                $parametersCollection['url_slug'] = $request->get('url_slug');

                $objFrontendHomeBussiness = new FrontendHomeBussiness($em, $container);
                $requestHandler = $objFrontendHomeBussiness->handleRequest($parametersCollection);
                return $this->render('@app_frontend_template_directory/themes/default/templates/pages/invoice.html.twig', array(
                  'themeConfigsData' => $requestHandler['themeConfigsData']
                ));
              } else {
                $excep = new \Exception("No existe la reserva",204);
                throw new \Exception($excep);
              }
            }
            catch (\Exception $e){
              throw new \Exception($e);
            }
          }
        }
        else {
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
    }
    /**
     * @Route("/es/{section}/{url_slug}/", name="_frontend_section_element_es")
     * @Method("GET")
     */
    public function _frontendSectionElementEsAction(Request $request)
    {
        return $this->redirectToRoute('frontend_section_element_es',array(
            "section"=>$request->get('section'),
            "url_slug"=>$request->get('url_slug')
        ));

    }
    /**
     * @Route("/en/{section}/{url_slug}", name="frontend_section_element_en")
     * @Method("GET")
     */
    public function frontendSectionElementEnAction(Request $request)
    {
        $this->checkLocaleValues($request, 'en');

      /*getting soy cubano parameters*/
      $id_transaccion = $request->get('id_transaccion');
      $notrans = $request->get('notrans');
      $resultado = $request->get('resultado');
      $codig = $request->get('codig');


      /*checking if the requeste comes from soy cubano*/
      if($id_transaccion && $id_transaccion != ''
        && $notrans && $notrans != ''
        && $resultado && $resultado != ''
        && $codig && $codig != ''
      ){
        try{
          $em = $this->getDoctrine()->getManager();
          $apiBussiness = new ApiBussiness($em, $this->container);
          $params = array(
            'id_transaction'=>$id_transaccion,
            'notrans'=>$notrans,
            'resultado'=>$resultado,
            'codig'=>$codig
          );
          $result = $apiBussiness->checkInvoice($params);
        if($result != 'error'){
          return $this->render('@app_frontend_template_directory/themes/default/templates/pages/invoice.html.twig');
        } else {
          $excep = new \Exception("No existe la reserva",204);
          throw new \Exception($excep);
        }
        }
        catch (\Exception $e){
          throw new \Exception($e);
        }

      }
      else{
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
    }
    /**
     * @Route("/en/{section}/{url_slug}/", name="_frontend_section_element_en")
     * @Method("GET")
     */
    public function _frontendSectionElementEnAction(Request $request)
    {
        return $this->redirectToRoute('frontend_section_element_en',array(
            "section"=>$request->get('section'),
            "url_slug"=>$request->get('url_slug')
        ));

    }


    /**
     * @Route("/es/{section}/{url_slug}/{tag}", name="frontend_section_element_tag_es")
     * @Method("GET")
     */
    public function frontendSectionElementTagEsAction(Request $request)
    {
        $this->checkLocaleValues($request);

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
     * @Route("/es/{section}/{url_slug}/{tag}/", name="_frontend_section_element_tag_es")
     * @Method("GET")
     */
    public function _frontendSectionElementTagEsAction(Request $request)
    {
        return $this->redirectToRoute('frontend_section_element_tag_es',array(
            "section"=>$request->get('section'),
            "url_slug"=>$request->get('url_slug'),
            "tag"=>$request->get('tag')
        ));

    }
    /**
     * @Route("/en/{section}/{url_slug}/{tag}", name="frontend_section_element_tag_en")
     * @Method("GET")
     */
    public function frontendSectionElementTagEnAction(Request $request)
    {
        $this->checkLocaleValues($request, 'en');

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
     * @Route("/en/{section}/{url_slug}/{tag}/", name="_frontend_section_element_tag_en")
     * @Method("GET")
     */
    public function _frontendSectionElementTagEnAction(Request $request)
    {

        return $this->redirectToRoute('frontend_section_element_tag_en',array(
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

    public function checkLocaleValues($request, $desiredLocale = 'es'){
        if(!isset($desiredLocale)){
            return $this->redirect($this->generateUrl('frontend_index_'.$desiredLocale));
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
    }

}

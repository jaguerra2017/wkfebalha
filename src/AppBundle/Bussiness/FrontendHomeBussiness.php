<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\DateTime;




class FrontendHomeBussiness
{
    private $em;
    private $container;

    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getHomeData(){
        try{
            $homeData = array();
            $homeData['themeConfigsData'] = $this->getThemeConfigsData();
            $homeData['closerEventsData'] = $this->getCloserEvents();
            $homeData['newsSummaryData'] = $this->getNewsSummary();
            $homeData['newsMainPageUrl'] = $this->container->get('appbundle_site_settings')->getBncDomain().'/es/noticias';
            $homeData['partnersSummaryData'] = $this->getPartnersSummary();
            //print_r($homeData);die;
            return $homeData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getThemeConfigsData(){
        try{
            $themeConfigData = array();
            $themeAppearanceConfigs = $this->container->get('appbundle_site_settings')->getSectionSettingsData('appearance');
            if(isset($themeAppearanceConfigs['configurations'])){
                $themeConfigData['headerConfigs'] = $themeAppearanceConfigs['configurations'][0];
                $themeConfigData['socialsConfigs'] = $themeAppearanceConfigs['configurations'][1];
            }
            $themeConfigData['menuConfigs'] = $this->getMainMenu();

            return $themeConfigData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getCloserEvents(){
        try{
            $parametersCollection = array();
            $parametersCollection['post_type_tree_slug'] = 'event';
            $parametersCollection['container'] = $this->container;
            $parametersCollection['searchByStartDate'] = true;
            $parametersCollection['startDate'] = date_format(new \DateTime(),'Y/m/d H:i');
            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'e.start_date';
            $parametersCollection['customOrderSort'] = 'ASC';
            $parametersCollection['searchByPagination'] = true;
            $parametersCollection['start'] = 0;
            $parametersCollection['end'] = 1;
            $parametersCollection['searchByPostStatusSlug'] = true;
            $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
            $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
            $closerEventData = $this->container->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            return $closerEventData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getMainMenu(){
        try{
            $themeMainMenu = array();
            $parametersCollection = array();
            $parametersCollection['searchBySlug'] = true;
            $parametersCollection['slug'] = 'main-menu';
            $menusCollection = $this->container->get('appbundle_site_settings')->getMenus($parametersCollection);
            if(isset($menusCollection[0]) && isset($menusCollection[0]['childrens'])){
                $themeMainMenu = $menusCollection[0]['childrens'];
            }
            return$themeMainMenu;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getNewsSummary(){
        try{
            $newsSummaryCollection = array();
            $parametersCollection = array();
            $parametersCollection['container'] = $this->container;
            $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
            $parametersCollection['searchByPagination'] = true;
            $parametersCollection['start'] = 0;
            $parametersCollection['end'] = 4;
            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['returnCustomOrderOnlyInGenericPost'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';
            $parametersCollection['post_type_tree_slug'] = 'post';
            $parametersCollection['searchByPostStatusSlug'] = true;
            $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
            $newsSummaryCollection = $this->container->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            //print_r($newsSummaryCollection);die;
            return $newsSummaryCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getPartnersSummary(){
        try{
            $partnersSummaryCollection = array();
            $parametersCollection = array();
            $parametersCollection['container'] = $this->container;
            $parametersCollection['imagineCacheManager'] = $this->container->get('liip_imagine.cache.manager');
            $parametersCollection['searchByPagination'] = true;
            $parametersCollection['start'] = 0;
            $parametersCollection['end'] = 4;
            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';
            $parametersCollection['post_type_tree_slug'] = 'partner';
            $parametersCollection['searchByPostStatusSlug'] = true;
            $parametersCollection['postStatusSlug'] = 'generic-post-status-published';
            $partnersSummaryCollection = $this->container->get('appbundle_generic_posts')->getGenericPosts($parametersCollection);
            return $partnersSummaryCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function handleRequest($parametersCollection){
         try{
             if(isset($parametersCollection['section'])){
                 switch($parametersCollection['section']){
                     case 'buscar':

                         break;
                     default:
                         $proceed = true;
                         $includeGenericPostSlug = false;
                         $includeTag = false;
                         $availableGenericPostType = $this->em->getRepository('AppBundle:GenericPostType')->findOneBy(array(
                            'section_available' => true,
                             'url_slug_es' => $parametersCollection['section']
                         ));
                         if(!isset($availableGenericPostType)){
                             $proceed = false;
                         }
                         if(isset($parametersCollection['url_slug']) && $parametersCollection['url_slug'] != null){
                             $includeGenericPostSlug = true;
                             $availableGenericPost = $this->em->getRepository('AppBundle:GenericPost')->findOneBy(array(
                                 'generic_post_type' => $availableGenericPostType,
                                 'url_slug_es' => $parametersCollection['url_slug'],
                                 'post_status_slug' => 'generic-post-status-published'
                             ));
                             if(!isset($availableGenericPost)){
                                 $proceed = false;
                             }
                         }

                        if($proceed){
                            $tree_slug = $availableGenericPostType->getTreeSlug();
                            $template_prefix = $tree_slug;
                            $currentTheme = $this->container->get('appbundle_site_settings')->getCurrentTheme();
                            $templateType = 'generic-posts';


                            switch($tree_slug){
                                case 'page':
                                    if($includeGenericPostSlug){
                                        $templateType = 'pages';
                                        $objPage = $this->em->getRepository('AppBundle:Page')->find($availableGenericPost->getId());
                                        if(isset($objPage)){
                                            $template_prefix = $objPage->getTemplateSlug();
                                        }
                                        if(isset($parametersCollection['tag']) && $parametersCollection['tag'] != null){
                                            $includeTag = true;
                                        }
                                    }
                                    break;

                                default:
                                    if($includeGenericPostSlug){
                                        $templateType = 'single';
                                    }
                            }

                            $isTemplateAvailable = $this->container->get('appbundle_file_finder')->checkTemplateExistence(array(
                                'currentTheme' => $currentTheme,
                                'template_type' => $templateType,
                                'template_prefix' => $template_prefix
                            ));
                            if($isTemplateAvailable){
                                if($includeGenericPostSlug){
                                    $parametersCollection['request_object']->getSession()->set('current_generic_post_id', $availableGenericPost->getId());
                                    if($includeTag){
                                        $parametersCollection['request_object']->getSession()->set('current_tag', $parametersCollection['tag']);
                                    }
                                }
                                $themeAppearanceConfigs = $this->container->get('appbundle_site_settings')->getSectionSettingsData('appearance');
                                $themeConfigsData = array();
                                $themeConfigsData['menuConfigs'] = $this->getMainMenu();
                                $themeConfigsData['socialsConfigs'] = $themeAppearanceConfigs['configurations'][1];
                                $templateDirectory = '@app_frontend_template_directory/themes/'.
                                    $currentTheme.'/templates/'.$templateType.'/'.$template_prefix.'.html.twig';
                                return array(
                                    'template_requested_directory' => $templateDirectory,
                                    'themeConfigsData' => $themeConfigsData
                                );
                            }
                        }
                 }
             }

             throw new NotFoundHttpException();
         }
         catch(NotFoundHttpException $e){
             //print_r($e->getTraceAsString());
                throw $e;
         }
         catch(\Exception $e){
             //print_r($e->getTraceAsString());
             throw $e;
         }
    }

}
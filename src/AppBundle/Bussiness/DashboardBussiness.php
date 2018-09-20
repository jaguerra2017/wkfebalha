<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Entity\GenericPost;
use AppBundle\Bussiness\NomenclatureBussiness;
use AppBundle\Entity\GenericPostNomenclature;
use AppBundle\Entity\GenericPostTaxonomy;
use AppBundle\Entity\Page;



class DashboardBussiness
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadInitialsData($parametersCollection)
    {
        try{
            $initialsData = array();
            $initialsData['summaryDataCollection'] = $this->getSummary($parametersCollection);

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getSummary($parametersCollection){
        try{
            $summaryDataCollection = array();
            $parametersCollection['just_count'] = true;
            $parametersCollection['filterByTreeSlug'] = true;

            /*news summary*/
            $parametersCollection['treeSlug'] = 'functionality-news';
            $newsDetailsCollection = $this->em->getRepository('AppBundle:NomFunctionality')->getFunctionalities($parametersCollection);
            if(isset($newsDetailsCollection[0])){
                $newsDetailsCollection = $newsDetailsCollection[0];
                $newsDetailsCollection['element'] = $newsDetailsCollection['name_es'];
            }
            $parametersCollection['post_type_tree_slug'] = 'post';
            $newsDetailsCollection['total'] = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
            $newsDetailsCollection['color'] = 'blue-madison';
            $summaryDataCollection[0] = $newsDetailsCollection;

            /*publications summary*/
            $parametersCollection['treeSlug'] = 'functionality-publications';
            $publicationsDetailsCollection = $this->em->getRepository('AppBundle:NomFunctionality')->getFunctionalities($parametersCollection);
            if(isset($publicationsDetailsCollection[0])){
                $publicationsDetailsCollection = $publicationsDetailsCollection[0];
                $publicationsDetailsCollection['element'] = $publicationsDetailsCollection['name_es'];
            }
            $parametersCollection['post_type_tree_slug'] = 'publication';
            $publicationsDetailsCollection['total'] = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
            $publicationsDetailsCollection['color'] = 'green-haze';
            $summaryDataCollection[1] = $publicationsDetailsCollection;

            /*events summary*/
            $parametersCollection['treeSlug'] = 'functionality-events';
            $eventsDetailsCollection = $this->em->getRepository('AppBundle:NomFunctionality')->getFunctionalities($parametersCollection);
            if(isset($eventsDetailsCollection[0])){
                $eventsDetailsCollection = $eventsDetailsCollection[0];
                $eventsDetailsCollection['element'] = $eventsDetailsCollection['name_es'];
            }
            $parametersCollection['post_type_tree_slug'] = 'event';
            $eventsDetailsCollection['total'] = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
            $eventsDetailsCollection['color'] = 'purple-plum';
            $summaryDataCollection[2] = $eventsDetailsCollection;

            /*pages summary*/
            $parametersCollection['treeSlug'] = 'functionality-pages';
            $pagesDetailsCollection = $this->em->getRepository('AppBundle:NomFunctionality')->getFunctionalities($parametersCollection);
            if(isset($pagesDetailsCollection[0])){
                $pagesDetailsCollection = $pagesDetailsCollection[0];
                $pagesDetailsCollection['element'] = $pagesDetailsCollection['name_es'];
            }
            $parametersCollection['post_type_tree_slug'] = 'page';
            $pagesDetailsCollection['total'] = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
            $pagesDetailsCollection['color'] = 'yellow-casablanca';
            $summaryDataCollection[3] = $pagesDetailsCollection;

            /*comments summary*/
            $parametersCollection['treeSlug'] = 'functionality-comments';
            $commentsDetailsCollection = $this->em->getRepository('AppBundle:NomFunctionality')->getFunctionalities($parametersCollection);
            if(isset($commentsDetailsCollection[0])){
                $commentsDetailsCollection = $commentsDetailsCollection[0];
                $commentsDetailsCollection['element'] = 'Comentarios';
            }
            $totalComments = 0;
            $commentsCollection = $this->em->getRepository('AppBundle:Comment')->findAll();
            if(isset($commentsCollection[0])){
                $totalComments = count($commentsCollection);
            }
            $commentsDetailsCollection['total'] = $totalComments;
            $commentsDetailsCollection['color'] = 'red-haze';
            $summaryDataCollection[4] = $commentsDetailsCollection;

            /*media summary*/
            $parametersCollection['treeSlug'] = 'functionality-media';
            $mediaDetailsCollection = $this->em->getRepository('AppBundle:NomFunctionality')->getFunctionalities($parametersCollection);
            $imagesDetailsCollection = array();
            $videosDetailsCollection = array();
            $galleriesDetailsCollection = array();
            if(isset($mediaDetailsCollection[0])){
                /*for Images*/
                $imagesDetailsCollection = $mediaDetailsCollection[0];
                $imagesDetailsCollection['element'] = 'Imágenes';
                $imagesDetailsCollection['icon_class'] = 'icon-picture';
                $imagesDetailsCollection['color'] = 'grey-silver';
                $totalImages = 0;
                $imagesCollection = $this->em->getRepository('AppBundle:MediaImage')->findAll();
                if(isset($imagesCollection[0])){
                    $totalImages = count($imagesCollection);
                }
                $imagesDetailsCollection['total'] = $totalImages;
                $summaryDataCollection[5] = $imagesDetailsCollection;

                /*for Videos*/
                $videosDetailsCollection = $mediaDetailsCollection[0];
                $videosDetailsCollection['element'] = 'Videos';
                $videosDetailsCollection['icon_class'] = 'icon-camcorder';
                $videosDetailsCollection['color'] = 'grey-cascade';
                $totalVideos = 0;
                $videosCollection = $this->em->getRepository('AppBundle:MediaVideo')->findAll();
                if(isset($videosCollection[0])){
                    $totalVideos = count($videosCollection);
                }
                $videosDetailsCollection['total'] = $totalVideos;
                $summaryDataCollection[6] = $videosDetailsCollection;

                /*for Galleries*/
                $galleriesDetailsCollection = $mediaDetailsCollection[0];
                $galleriesDetailsCollection['element'] = 'Galerías';
                $galleriesDetailsCollection['icon_class'] = 'icon-camera';
                $galleriesDetailsCollection['color'] = 'grey-gallery';
                $totalGalleries = 0;
                $galleriesCollection = $this->em->getRepository('AppBundle:Gallery')->findAll();
                if(isset($galleriesCollection[0])){
                    $totalGalleries = count($galleriesCollection);
                }
                $galleriesDetailsCollection['total'] = $totalGalleries;
                $summaryDataCollection[7] = $galleriesDetailsCollection;
            }

            return $summaryDataCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }




    public function returnResponse($parametersCollection){
        return $parametersCollection;
    }

}
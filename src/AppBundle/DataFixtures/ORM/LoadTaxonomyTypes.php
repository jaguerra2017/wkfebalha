<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\TaxonomyType;
use AppBundle\Entity\GenericPostTypeTaxonomyType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadTaxonomyTypes extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        /*Taxonomies Types*/
        $postCategoryTaxonomyType = new TaxonomyType();
        $postCategoryTaxonomyType->setName('Categoría de la noticia');
        $postCategoryTaxonomyType->setDescription('Taxonomía para categorizar las noticias');
        $postCategoryTaxonomyType->setUrlSlug('categoria-noticia');
        $postCategoryTaxonomyType->setTreeSlug('post-category');
        $manager->persist($postCategoryTaxonomyType);

        $pageCategoryTaxonomyType = new TaxonomyType();
        $pageCategoryTaxonomyType->setName('Categoría de la página');
        $pageCategoryTaxonomyType->setDescription('Taxonomía para categorizar las páginas');
        $pageCategoryTaxonomyType->setUrlSlug('categoria-pagina');
        $pageCategoryTaxonomyType->setTreeSlug('page-category');
        $manager->persist($pageCategoryTaxonomyType);

        $publicationCategoryTaxonomyType = new TaxonomyType();
        $publicationCategoryTaxonomyType->setName('Categoría de la publicación');
        $publicationCategoryTaxonomyType->setDescription('Taxonomía para categorizar las publicaciones');
        $publicationCategoryTaxonomyType->setUrlSlug('categoria-publicacion');
        $publicationCategoryTaxonomyType->setTreeSlug('publication-category');
        $manager->persist($publicationCategoryTaxonomyType);

        $opinionCategoryTaxonomyType = new TaxonomyType();
        $opinionCategoryTaxonomyType->setName('Categoría de la opinión/crítica');
        $opinionCategoryTaxonomyType->setDescription('Taxonomía para categorizar las opiniones/críticas');
        $opinionCategoryTaxonomyType->setUrlSlug('categoria-opinion');
        $opinionCategoryTaxonomyType->setTreeSlug('opinion-category');
        $manager->persist($opinionCategoryTaxonomyType);

        $eventCategoryTaxonomyType = new TaxonomyType();
        $eventCategoryTaxonomyType->setName('Categoría del evento');
        $eventCategoryTaxonomyType->setDescription('Taxonomía para categorizar los eventos');
        $eventCategoryTaxonomyType->setUrlSlug('categoria-evento');
        $eventCategoryTaxonomyType->setTreeSlug('event-category');
        $manager->persist($eventCategoryTaxonomyType);

        $repertoryCategoryTaxonomyType = new TaxonomyType();
        $repertoryCategoryTaxonomyType->setName('Categoría del repertorio');
        $repertoryCategoryTaxonomyType->setDescription('Taxonomía para categorizar el repertorio');
        $repertoryCategoryTaxonomyType->setUrlSlug('categoria-repertorio');
        $repertoryCategoryTaxonomyType->setTreeSlug('repertory-category');
        $manager->persist($repertoryCategoryTaxonomyType);

        $compositionCategoryTaxonomyType = new TaxonomyType();
        $compositionCategoryTaxonomyType->setName('Categoría de la composición');
        $compositionCategoryTaxonomyType->setDescription('Taxonomía para categorizar la composición del BNC');
        $compositionCategoryTaxonomyType->setUrlSlug('categoria-composicion');
        $compositionCategoryTaxonomyType->setTreeSlug('composition-category');
        $manager->persist($compositionCategoryTaxonomyType);

        $awardCategoryTaxonomyType = new TaxonomyType();
        $awardCategoryTaxonomyType->setName('Categoría de la distincion/premio');
        $awardCategoryTaxonomyType->setDescription('Taxonomía para categorizar las distinciones/premios');
        $awardCategoryTaxonomyType->setUrlSlug('categoria-distincion');
        $awardCategoryTaxonomyType->setTreeSlug('award-category');
        $manager->persist($awardCategoryTaxonomyType);

        $historicalMomentCategoryTaxonomyType = new TaxonomyType();
        $historicalMomentCategoryTaxonomyType->setName('Categoría del hito histórico');
        $historicalMomentCategoryTaxonomyType->setDescription('Taxonomía para categorizar los hitos históricos');
        $historicalMomentCategoryTaxonomyType->setUrlSlug('categoria-hito-historico');
        $historicalMomentCategoryTaxonomyType->setTreeSlug('historical-moment-category');
        $manager->persist($historicalMomentCategoryTaxonomyType);

        $partnerCategoryTaxonomyType = new TaxonomyType();
        $partnerCategoryTaxonomyType->setName('Categoría del socio');
        $partnerCategoryTaxonomyType->setDescription('Taxonomía para categorizar a los socios del BNC.');
        $partnerCategoryTaxonomyType->setUrlSlug('categoria-socio');
        $partnerCategoryTaxonomyType->setTreeSlug('partner-category');
        $manager->persist($partnerCategoryTaxonomyType);

        $tagTaxonomyType = new TaxonomyType();
        $tagTaxonomyType->setName('Etiqueta');
        $tagTaxonomyType->setDescription('Taxonomía para etiquetar noticias, páginas, publicaciones y eventos');
        $tagTaxonomyType->setUrlSlug('etiqueta');
        $tagTaxonomyType->setTreeSlug('tag');
        $manager->persist($tagTaxonomyType);

        $manager->flush();


        /*Relation between Taxonomy Types and Generic Post Types | GENERIC-POST-TAXONOMY-TYPE*/
        //Related to Post
        $postGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'post'
        ));
        if(isset($postGenericPostType)){
            $postTypePostCategory = new GenericPostTypeTaxonomyType();
            $postTypePostCategory->setGenericPostType($postGenericPostType);
            $postTypePostCategory->setTaxonomyType($postCategoryTaxonomyType);
            $manager->persist($postTypePostCategory);

            $postTypeTag = new GenericPostTypeTaxonomyType();
            $postTypeTag->setGenericPostType($postGenericPostType);
            $postTypeTag->setTaxonomyType($tagTaxonomyType);
            $manager->persist($postTypeTag);
        }

        //Related to Page
        $pageGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'page'
        ));
        if(isset($pageGenericPostType)){
            $pageTypePageCategory = new GenericPostTypeTaxonomyType();
            $pageTypePageCategory->setGenericPostType($pageGenericPostType);
            $pageTypePageCategory->setTaxonomyType($pageCategoryTaxonomyType);
            $manager->persist($pageTypePageCategory);

            $pageTypeTag = new GenericPostTypeTaxonomyType();
            $pageTypeTag->setGenericPostType($pageGenericPostType);
            $pageTypeTag->setTaxonomyType($tagTaxonomyType);
            $manager->persist($pageTypeTag);
        }

        //Related to Publication
        $publicationGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'publication'
        ));
        if(isset($publicationGenericPostType)){
            $publicationTypePublicationCategory = new GenericPostTypeTaxonomyType();
            $publicationTypePublicationCategory->setGenericPostType($publicationGenericPostType);
            $publicationTypePublicationCategory->setTaxonomyType($publicationCategoryTaxonomyType);
            $manager->persist($publicationTypePublicationCategory);

            $publicationTypeTag = new GenericPostTypeTaxonomyType();
            $publicationTypeTag->setGenericPostType($publicationGenericPostType);
            $publicationTypeTag->setTaxonomyType($tagTaxonomyType);
            $manager->persist($publicationTypeTag);
        }

        //Related to Event
        $eventGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'event'
        ));
        if(isset($eventGenericPostType)){
            $eventTypeEventCategory = new GenericPostTypeTaxonomyType();
            $eventTypeEventCategory->setGenericPostType($eventGenericPostType);
            $eventTypeEventCategory->setTaxonomyType($eventCategoryTaxonomyType);
            $manager->persist($eventTypeEventCategory);

            $eventTypeTag = new GenericPostTypeTaxonomyType();
            $eventTypeTag->setGenericPostType($eventGenericPostType);
            $eventTypeTag->setTaxonomyType($tagTaxonomyType);
            $manager->persist($eventTypeTag);
        }

        //Related to Opinion
        $opinionGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'opinion'
        ));
        if(isset($opinionGenericPostType)){
            $opinionTypeOpinionCategory = new GenericPostTypeTaxonomyType();
            $opinionTypeOpinionCategory->setGenericPostType($opinionGenericPostType);
            $opinionTypeOpinionCategory->setTaxonomyType($opinionCategoryTaxonomyType);
            $manager->persist($opinionTypeOpinionCategory);
        }

        //Related to Repertory
        $repertoryGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'repertory'
        ));
        if(isset($repertoryGenericPostType)){
            $repertoryTypeRepertoryCategory = new GenericPostTypeTaxonomyType();
            $repertoryTypeRepertoryCategory->setGenericPostType($repertoryGenericPostType);
            $repertoryTypeRepertoryCategory->setTaxonomyType($repertoryCategoryTaxonomyType);
            $manager->persist($repertoryTypeRepertoryCategory);
        }

        //Related to Composition
        $compositionGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'composition'
        ));
        if(isset($compositionGenericPostType)){
            $compositionTypeCompositionCategory = new GenericPostTypeTaxonomyType();
            $compositionTypeCompositionCategory->setGenericPostType($compositionGenericPostType);
            $compositionTypeCompositionCategory->setTaxonomyType($compositionCategoryTaxonomyType);
            $manager->persist($compositionTypeCompositionCategory);
        }

        //Related to Award
        $awardGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'award'
        ));
        if(isset($awardGenericPostType)){
            $awardTypeAwardCategory = new GenericPostTypeTaxonomyType();
            $awardTypeAwardCategory->setGenericPostType($awardGenericPostType);
            $awardTypeAwardCategory->setTaxonomyType($awardCategoryTaxonomyType);
            $manager->persist($awardTypeAwardCategory);
        }

        //Related to Historical Moments
        $historicalMomentGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'historical-moment'
        ));
        if(isset($historicalMomentGenericPostType)){
            $historicalMomentTypeHistoricalMomentCategory = new GenericPostTypeTaxonomyType();
            $historicalMomentTypeHistoricalMomentCategory->setGenericPostType($historicalMomentGenericPostType);
            $historicalMomentTypeHistoricalMomentCategory->setTaxonomyType($historicalMomentCategoryTaxonomyType);
            $manager->persist($historicalMomentTypeHistoricalMomentCategory);
        }


        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
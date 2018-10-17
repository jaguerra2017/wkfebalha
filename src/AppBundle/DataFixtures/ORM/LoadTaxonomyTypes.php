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

        $partnerCategoryTaxonomyType = new TaxonomyType();
        $partnerCategoryTaxonomyType->setName('Categoría del asociado');
        $partnerCategoryTaxonomyType->setDescription('Taxonomía para categorizar a los asociados del FIBHA.');
        $partnerCategoryTaxonomyType->setUrlSlug('categoria-asociado');
        $partnerCategoryTaxonomyType->setTreeSlug('partner-category');
        $manager->persist($partnerCategoryTaxonomyType);

        $guestCategoryTaxonomyType = new TaxonomyType();
        $guestCategoryTaxonomyType->setName('Categoría del invitado');
        $guestCategoryTaxonomyType->setDescription('Taxonomía para categorizar a los invitados del FIBHA.');
        $guestCategoryTaxonomyType->setUrlSlug('categoria-invitado');
        $guestCategoryTaxonomyType->setTreeSlug('guest-category');
        $manager->persist($guestCategoryTaxonomyType);

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

        //Related to Guest
        $guestGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'guest'
        ));
        if(isset($guestGenericPostType)){
            $guestTypePublicationCategory = new GenericPostTypeTaxonomyType();
            $guestTypePublicationCategory->setGenericPostType($guestGenericPostType);
            $guestTypePublicationCategory->setTaxonomyType($guestCategoryTaxonomyType);
            $manager->persist($guestTypePublicationCategory);

            $guestTypeTag = new GenericPostTypeTaxonomyType();
            $guestTypeTag->setGenericPostType($guestGenericPostType);
            $guestTypeTag->setTaxonomyType($tagTaxonomyType);
            $manager->persist($guestTypeTag);
        }

        //Related to Partners
        $partnerGenericPostType = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
            'tree_slug'=>'partner'
        ));
        if(isset($partnerGenericPostType)){
            $partnerTypePublicationCategory = new GenericPostTypeTaxonomyType();
            $partnerTypePublicationCategory->setGenericPostType($partnerGenericPostType);
            $partnerTypePublicationCategory->setTaxonomyType($guestCategoryTaxonomyType);
            $manager->persist($partnerTypePublicationCategory);

            $partnerTypeTag = new GenericPostTypeTaxonomyType();
            $partnerTypeTag->setGenericPostType($partnerGenericPostType);
            $partnerTypeTag->setTaxonomyType($tagTaxonomyType);
            $manager->persist($guestTypeTag);
        }


        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
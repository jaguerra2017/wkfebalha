<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\NomType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadNomenclatureTypes extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $nomTypeContentBlockType = new NomType();
        $nomTypeContentBlockType->setName('Tipo de Bloque de Contenido');
        $nomTypeContentBlockType->setDescription('Tipos de Bloques de Contenido para crear Posts y Páginas');
        $nomTypeContentBlockType->setUrlSlug('content-block-type');
        $nomTypeContentBlockType->setTreeSlug('content-block-type');
        $manager->persist($nomTypeContentBlockType);

        $nomTypeGenericPostStatus = new NomType();
        $nomTypeGenericPostStatus->setName('Status del Post Genérico');
        $nomTypeGenericPostStatus->setDescription('Status de publicación para Post, Noticias, Páginas y otros');
        $nomTypeGenericPostStatus->setUrlSlug('generic-post-status');
        $nomTypeGenericPostStatus->setTreeSlug('generic-post-status');
        $manager->persist($nomTypeGenericPostStatus);

        $nomTypeCommentStatus = new NomType();
        $nomTypeCommentStatus->setName('Status del Comentario');
        $nomTypeCommentStatus->setDescription('Status de publicación para Comentarios');
        $nomTypeCommentStatus->setUrlSlug('comment-status');
        $nomTypeCommentStatus->setTreeSlug('comment-status');
        $manager->persist($nomTypeCommentStatus);

        $nomTypeGalleryType = new NomType();
        $nomTypeGalleryType->setName('Tipo de Galería');
        $nomTypeGalleryType->setDescription('Tipos de Galería para mostrar los elementos de Media');
        $nomTypeGalleryType->setUrlSlug('gallery-type');
        $nomTypeGalleryType->setTreeSlug('gallery-type');
        $manager->persist($nomTypeGalleryType);

        $nomTypeMediaType = new NomType();
        $nomTypeMediaType->setName('Tipo de Media');
        $nomTypeMediaType->setDescription('Tipos de elementos de Media');
        $nomTypeMediaType->setUrlSlug('media-type');
        $nomTypeMediaType->setTreeSlug('media-type');
        $manager->persist($nomTypeMediaType);

        $nomTypeMenuItemType = new NomType();
        $nomTypeMenuItemType->setName('Tipo de elemento del Menú');
        $nomTypeMenuItemType->setDescription('Tipos de elementos para conformar el Menú');
        $nomTypeMenuItemType->setUrlSlug('menu-item-type');
        $nomTypeMenuItemType->setTreeSlug('menu-item-type');
        $manager->persist($nomTypeMenuItemType);

        $nomTypePartnerType = new NomType();
        $nomTypePartnerType->setName('Tipo de Asociado');
        $nomTypePartnerType->setDescription('Tipos de Asociados del BNC');
        $nomTypePartnerType->setUrlSlug('partner-type');
        $nomTypePartnerType->setTreeSlug('partner-type');
        $manager->persist($nomTypePartnerType);

        $nomTypeSystemFunctionality = new NomType();
        $nomTypeSystemFunctionality->setName('Funcionalidad del Sistema');
        $nomTypeSystemFunctionality->setDescription('Funcionalidades disponibles en el Sistema');
        $nomTypeSystemFunctionality->setUrlSlug('functionality');
        $nomTypeSystemFunctionality->setTreeSlug('functionality');
        $manager->persist($nomTypeSystemFunctionality);

        $nomTypeFunctionalityAction = new NomType();
        $nomTypeFunctionalityAction->setName('Acción de la Funcionalidad');
        $nomTypeFunctionalityAction->setDescription('Acciones asociadas a las funcionaldiades del Sistema');
        $nomTypeFunctionalityAction->setUrlSlug('action');
        $nomTypeFunctionalityAction->setTreeSlug('action');
        $manager->persist($nomTypeFunctionalityAction);


        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
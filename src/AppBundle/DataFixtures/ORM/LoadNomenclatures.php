<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Country;
use AppBundle\Entity\NomContentBlockType;
use AppBundle\Entity\Nomenclature;
use AppBundle\Entity\NomFunctionality;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadNomenclatures extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    /*Nomenclatures for content-Block-Type*/
    $objNomTypeContentBlockType = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'content-block-type'
    ));
    if (isset($objNomTypeContentBlockType)) {
      $nomMediaGalleryContentBlockType = new Nomenclature();
      $nomMediaGalleryContentBlockType->setName('Galeria de Medias');
      $nomMediaGalleryContentBlockType->setUrlSlug('bloque-contenido-galeria-media');
      $nomMediaGalleryContentBlockType->setTreeSlug('content-block-type-media-gallery');
      $nomMediaGalleryContentBlockType->setNomType($objNomTypeContentBlockType);
      $manager->persist($nomMediaGalleryContentBlockType);

      $nomMediaImageContentBlockType = new Nomenclature();
      $nomMediaImageContentBlockType->setName('Media - Imágenes');
      $nomMediaImageContentBlockType->setUrlSlug('bloque-contenido-media-imagen');
      $nomMediaImageContentBlockType->setTreeSlug('content-block-type-media-image');
      $nomMediaImageContentBlockType->setNomType($objNomTypeContentBlockType);
      $manager->persist($nomMediaImageContentBlockType);

      $nomMediaVideoContentBlockType = new Nomenclature();
      $nomMediaVideoContentBlockType->setName('Media - Videos');
      $nomMediaVideoContentBlockType->setUrlSlug('bloque-contenido-media-video');
      $nomMediaVideoContentBlockType->setTreeSlug('content-block-type-media-video');
      $nomMediaVideoContentBlockType->setNomType($objNomTypeContentBlockType);
      $manager->persist($nomMediaVideoContentBlockType);


      $manager->flush();

      $mediaGalleryNomContentBlock = new NomContentBlockType();
      $mediaGalleryNomContentBlock->setId($nomMediaGalleryContentBlockType);
      $mediaGalleryNomContentBlock->setIsReusable(true);
      $manager->persist($mediaGalleryNomContentBlock);
    }


    /*Nomenclatures for Generic-Post-Status*/
    $objNomGenericPostStatus = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'generic-post-status'
    ));
    if (isset($objNomGenericPostStatus)) {
      $nomPendingGenericPostStatus = new Nomenclature();
      $nomPendingGenericPostStatus->setName('Pendiente');
      $nomPendingGenericPostStatus->setUrlSlug('post-status-pendiente');
      $nomPendingGenericPostStatus->setTreeSlug('generic-post-status-pending');
      $nomPendingGenericPostStatus->setNomType($objNomGenericPostStatus);
      $manager->persist($nomPendingGenericPostStatus);

      $nomPublishedGenericPostStatus = new Nomenclature();
      $nomPublishedGenericPostStatus->setName('Publicado');
      $nomPublishedGenericPostStatus->setUrlSlug('post-status-publicado');
      $nomPublishedGenericPostStatus->setTreeSlug('generic-post-status-published');
      $nomPublishedGenericPostStatus->setNomType($objNomGenericPostStatus);
      $manager->persist($nomPublishedGenericPostStatus);
    }


    /*Nomenclatures for Comment-Status*/
    $objNomCommentStatus = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'comment-status'
    ));
    if (isset($objNomCommentStatus)) {
      $nomPendingCommentStatus = new Nomenclature();
      $nomPendingCommentStatus->setName('Pendiente');
      $nomPendingCommentStatus->setUrlSlug('comentario-status-pendiente');
      $nomPendingCommentStatus->setTreeSlug('comment-status-pending');
      $nomPendingCommentStatus->setNomType($objNomCommentStatus);
      $manager->persist($nomPendingCommentStatus);

      $nomApprovedCommentStatus = new Nomenclature();
      $nomApprovedCommentStatus->setName('Aprobado');
      $nomApprovedCommentStatus->setUrlSlug('comentario-status-aprobado');
      $nomApprovedCommentStatus->setTreeSlug('comment-status-approved');
      $nomApprovedCommentStatus->setNomType($objNomCommentStatus);
      $manager->persist($nomApprovedCommentStatus);
    }


    /*Nomenclatures for Gallery-Type*/
    $objNomGalleryType = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'gallery-type'
    ));
    if (isset($objNomGalleryType)) {
      $nomImageGalleryType = new Nomenclature();
      $nomImageGalleryType->setName('Imágenes');
      $nomImageGalleryType->setUrlSlug('galeria-tipo-imagen');
      $nomImageGalleryType->setTreeSlug('gallery-type-image');
      $nomImageGalleryType->setNomType($objNomGalleryType);
      $manager->persist($nomImageGalleryType);

      $nomVideoGalleryType = new Nomenclature();
      $nomVideoGalleryType->setName('Videos');
      $nomVideoGalleryType->setUrlSlug('galeria-tipo-video');
      $nomVideoGalleryType->setTreeSlug('gallery-type-video');
      $nomVideoGalleryType->setNomType($objNomGalleryType);
      $manager->persist($nomVideoGalleryType);
    }


    /*Nomenclatures for Media-Type*/
    $objNomMediaType = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'media-type'
    ));
    if (isset($objNomMediaType)) {
      $nomImageMediaType = new Nomenclature();
      $nomImageMediaType->setName('Imágen');
      $nomImageMediaType->setUrlSlug('media-tipo-imagen');
      $nomImageMediaType->setTreeSlug('media-type-image');
      $nomImageMediaType->setNomType($objNomMediaType);
      $manager->persist($nomImageMediaType);

      $nomVideoMediaType = new Nomenclature();
      $nomVideoMediaType->setName('Video');
      $nomVideoMediaType->setUrlSlug('video-tipo-imagen');
      $nomVideoMediaType->setTreeSlug('media-type-video');
      $nomVideoMediaType->setNomType($objNomMediaType);
      $manager->persist($nomVideoMediaType);
    }


    /*Nomenclatures for Menu-Item-Type*/
    $objNomMenuItemType = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'menu-item-type'
    ));
    if (isset($objNomMenuItemType)) {
      $nomPageMenuItemType = new Nomenclature();
      $nomPageMenuItemType->setName('Página');
      $nomPageMenuItemType->setUrlSlug('menu-elemento-tipo-pagina');
      $nomPageMenuItemType->setTreeSlug('menu-item-type-page');
      $nomPageMenuItemType->setNomType($objNomMenuItemType);
      $manager->persist($nomPageMenuItemType);

      $nomUrlMenuItemType = new Nomenclature();
      $nomUrlMenuItemType->setName('Url');
      $nomUrlMenuItemType->setUrlSlug('menu-elemento-tipo-url');
      $nomUrlMenuItemType->setTreeSlug('menu-item-type-url');
      $nomUrlMenuItemType->setNomType($objNomMenuItemType);
      $manager->persist($nomUrlMenuItemType);
    }


    /*Nomenclatures for Partner-Type*/
    $objNomPartnerType = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'partner-type'
    ));
    if (isset($objNomPartnerType)) {
      $nomPartnerPartnerType = new Nomenclature();
      $nomPartnerPartnerType->setName('Socio');
      $nomPartnerPartnerType->setUrlSlug('asociado-tipo-socio');
      $nomPartnerPartnerType->setTreeSlug('partner-type-partner');
      $nomPartnerPartnerType->setNomType($objNomPartnerType);
      $manager->persist($nomPartnerPartnerType);

      $nomSponsorPartnerType = new Nomenclature();
      $nomSponsorPartnerType->setName('Patrocinador');
      $nomSponsorPartnerType->setUrlSlug('asociado-tipo-patrocinador');
      $nomSponsorPartnerType->setTreeSlug('partner-type-sponsor');
      $nomSponsorPartnerType->setNomType($objNomPartnerType);
      $manager->persist($nomSponsorPartnerType);
    }


    $manager->flush();


    /*Nomenclature for Functionalities*/
    $objNomTypeFunctionality = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'functionality'
    ));
    if (isset($objNomTypeFunctionality)) {
      /*Nomenclature for Actions*/
      $objNomAction = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
        'tree_slug' => 'action'
      ));
      if (isset($objNomAction)) {

        /*Dashboard*/
        $nomDashboardFunctionality = new Nomenclature();
        $nomDashboardFunctionality->setName('Dashboard');
        $nomDashboardFunctionality->setUrlSlug('dashboard');
        $nomDashboardFunctionality->setTreeSlug('functionality-dashboard');
        $nomDashboardFunctionality->setNomType($objNomTypeFunctionality);
        $nomDashboardFunctionality->setPriority(1);
        $manager->persist($nomDashboardFunctionality);
        $manager->flush();
        $dashboardFunctionality = new NomFunctionality();
        $dashboardFunctionality->setId($nomDashboardFunctionality);
        $dashboardFunctionality->setIconClass('icon-home');
        $dashboardFunctionality->setUrlIndexAction('dashboard_index');
        $dashboardFunctionality->setKeywordSelectedClass($nomDashboardFunctionality->getUrlSlug());
        $dashboardFunctionality->setIsUsedFrequently(true);
        $manager->persist($dashboardFunctionality);
        //Actions for Dashboard
        //no-actions


        /*News*/
        $nomNewsFunctionality = new Nomenclature();
        $nomNewsFunctionality->setName('Noticias');
        $nomNewsFunctionality->setUrlSlug('noticias');
        $nomNewsFunctionality->setTreeSlug('functionality-news');
        $nomNewsFunctionality->setNomType($objNomTypeFunctionality);
        $nomNewsFunctionality->setPriority(2);
        $manager->persist($nomNewsFunctionality);
        $manager->flush();
        $newsFunctionality = new NomFunctionality();
        $newsFunctionality->setId($nomNewsFunctionality);
        $newsFunctionality->setIconClass('icon-note');
        $newsFunctionality->setUrlIndexAction('news_index');
        $newsFunctionality->setKeywordSelectedClass($nomNewsFunctionality->getUrlSlug());
        $newsFunctionality->setIsUsedFrequently(true);
        $manager->persist($newsFunctionality);

        //Actions for News
        $nomNewsReadAction = new Nomenclature();
        $nomNewsReadAction->setName('Leer');
        $nomNewsReadAction->setUrlSlug('noticias-accion-leer');
        $nomNewsReadAction->setTreeSlug('functionality-news-action-read');
        $nomNewsReadAction->setNomType($objNomAction);
        $nomNewsReadAction->setParent($nomNewsFunctionality);
        $nomNewsReadAction->setPriority(1);
        $manager->persist($nomNewsReadAction);

        $nomNewsCreateAction = new Nomenclature();
        $nomNewsCreateAction->setName('Crear');
        $nomNewsCreateAction->setUrlSlug('noticias-accion-crear');
        $nomNewsCreateAction->setTreeSlug('functionality-news-action-create');
        $nomNewsCreateAction->setNomType($objNomAction);
        $nomNewsCreateAction->setParent($nomNewsFunctionality);
        $nomNewsCreateAction->setPriority(2);
        $manager->persist($nomNewsCreateAction);

        $nomNewsEditAction = new Nomenclature();
        $nomNewsEditAction->setName('Editar');
        $nomNewsEditAction->setUrlSlug('noticias-accion-editar');
        $nomNewsEditAction->setTreeSlug('functionality-news-action-edit');
        $nomNewsEditAction->setNomType($objNomAction);
        $nomNewsEditAction->setParent($nomNewsFunctionality);
        $nomNewsEditAction->setPriority(3);
        $manager->persist($nomNewsEditAction);

        $nomNewsDeleteAction = new Nomenclature();
        $nomNewsDeleteAction->setName('Eliminar');
        $nomNewsDeleteAction->setUrlSlug('noticias-accion-eliminar');
        $nomNewsDeleteAction->setTreeSlug('functionality-news-action-delete');
        $nomNewsDeleteAction->setNomType($objNomAction);
        $nomNewsDeleteAction->setParent($nomNewsFunctionality);
        $nomNewsDeleteAction->setPriority(4);
        $manager->persist($nomNewsDeleteAction);

        $nomNewsChangeStatusAction = new Nomenclature();
        $nomNewsChangeStatusAction->setName('Cambiar Status');
        $nomNewsChangeStatusAction->setUrlSlug('noticias-accion-cambiar-status');
        $nomNewsChangeStatusAction->setTreeSlug('functionality-news-action-change-status');
        $nomNewsChangeStatusAction->setNomType($objNomAction);
        $nomNewsChangeStatusAction->setParent($nomNewsFunctionality);
        $nomNewsChangeStatusAction->setPriority(5);
        $manager->persist($nomNewsChangeStatusAction);


        /*Publications*/
        /*$nomPublicationsFunctionality = new Nomenclature();
        $nomPublicationsFunctionality->setName('Publicaciones');
        $nomPublicationsFunctionality->setUrlSlug('publicaciones');
        $nomPublicationsFunctionality->setTreeSlug('functionality-publications');
        $nomPublicationsFunctionality->setNomType($objNomTypeFunctionality);
        $nomPublicationsFunctionality->setPriority(3);
        $manager->persist($nomPublicationsFunctionality);
        $manager->flush();
        $publicationsFunctionality = new NomFunctionality();
        $publicationsFunctionality->setId($nomPublicationsFunctionality);
        $publicationsFunctionality->setIconClass('icon-book-open');
        $publicationsFunctionality->setUrlIndexAction('publications_index');
        $publicationsFunctionality->setKeywordSelectedClass($nomPublicationsFunctionality->getUrlSlug());
        $publicationsFunctionality->setIsUsedFrequently(true);
        $manager->persist($publicationsFunctionality);

        //Actions for Publications
        $nomPublicationsReadAction = new Nomenclature();
        $nomPublicationsReadAction->setName('Leer');
        $nomPublicationsReadAction->setUrlSlug('publicaciones-accion-leer');
        $nomPublicationsReadAction->setTreeSlug('functionality-publications-action-read');
        $nomPublicationsReadAction->setNomType($objNomAction);
        $nomPublicationsReadAction->setParent($nomPublicationsFunctionality);
        $nomPublicationsReadAction->setPriority(1);
        $manager->persist($nomPublicationsReadAction);

        $nomPublicationsCreateAction = new Nomenclature();
        $nomPublicationsCreateAction->setName('Crear');
        $nomPublicationsCreateAction->setUrlSlug('publicaciones-accion-crear');
        $nomPublicationsCreateAction->setTreeSlug('functionality-publications-action-create');
        $nomPublicationsCreateAction->setNomType($objNomAction);
        $nomPublicationsCreateAction->setParent($nomPublicationsFunctionality);
        $nomPublicationsCreateAction->setPriority(2);
        $manager->persist($nomPublicationsCreateAction);

        $nomPublicationsEditAction = new Nomenclature();
        $nomPublicationsEditAction->setName('Editar');
        $nomPublicationsEditAction->setUrlSlug('publicaciones-accion-editar');
        $nomPublicationsEditAction->setTreeSlug('functionality-publications-action-edit');
        $nomPublicationsEditAction->setNomType($objNomAction);
        $nomPublicationsEditAction->setParent($nomPublicationsFunctionality);
        $nomPublicationsEditAction->setPriority(3);
        $manager->persist($nomPublicationsEditAction);

        $nomPublicationsDeleteAction = new Nomenclature();
        $nomPublicationsDeleteAction->setName('Eliminar');
        $nomPublicationsDeleteAction->setUrlSlug('publicaciones-accion-eliminar');
        $nomPublicationsDeleteAction->setTreeSlug('functionality-publications-action-delete');
        $nomPublicationsDeleteAction->setNomType($objNomAction);
        $nomPublicationsDeleteAction->setParent($nomPublicationsFunctionality);
        $nomPublicationsDeleteAction->setPriority(4);
        $manager->persist($nomPublicationsDeleteAction);

        $nomPublicationsChangeStatusAction = new Nomenclature();
        $nomPublicationsChangeStatusAction->setName('Cambiar Status');
        $nomPublicationsChangeStatusAction->setUrlSlug('publicaciones-accion-cambiar-status');
        $nomPublicationsChangeStatusAction->setTreeSlug('functionality-publications-action-change-status');
        $nomPublicationsChangeStatusAction->setNomType($objNomAction);
        $nomPublicationsChangeStatusAction->setParent($nomPublicationsFunctionality);
        $nomPublicationsChangeStatusAction->setPriority(5);
        $manager->persist($nomPublicationsChangeStatusAction);*/


        /*Events*/
       /* $nomEventsFunctionality = new Nomenclature();
        $nomEventsFunctionality->setName('Eventos');
        $nomEventsFunctionality->setUrlSlug('eventos');
        $nomEventsFunctionality->setTreeSlug('functionality-events');
        $nomEventsFunctionality->setNomType($objNomTypeFunctionality);
        $nomEventsFunctionality->setPriority(4);
        $manager->persist($nomEventsFunctionality);
        $manager->flush();
        $eventsFunctionality = new NomFunctionality();
        $eventsFunctionality->setId($nomEventsFunctionality);
        $eventsFunctionality->setIconClass('icon-calendar');
        $eventsFunctionality->setUrlIndexAction('events_index');
        $eventsFunctionality->setKeywordSelectedClass($nomEventsFunctionality->getUrlSlug());
        $eventsFunctionality->setIsUsedFrequently(true);
        $manager->persist($eventsFunctionality);

        //Actions for Events
        $nomEventsReadAction = new Nomenclature();
        $nomEventsReadAction->setName('Leer');
        $nomEventsReadAction->setUrlSlug('eventos-accion-leer');
        $nomEventsReadAction->setTreeSlug('functionality-events-action-read');
        $nomEventsReadAction->setNomType($objNomAction);
        $nomEventsReadAction->setParent($nomEventsFunctionality);
        $nomEventsReadAction->setPriority(1);
        $manager->persist($nomEventsReadAction);

        $nomEventsCreateAction = new Nomenclature();
        $nomEventsCreateAction->setName('Crear');
        $nomEventsCreateAction->setUrlSlug('eventos-accion-crear');
        $nomEventsCreateAction->setTreeSlug('functionality-events-action-create');
        $nomEventsCreateAction->setNomType($objNomAction);
        $nomEventsCreateAction->setParent($nomEventsFunctionality);
        $nomEventsCreateAction->setPriority(2);
        $manager->persist($nomEventsCreateAction);

        $nomEventsEditAction = new Nomenclature();
        $nomEventsEditAction->setName('Editar');
        $nomEventsEditAction->setUrlSlug('eventos-accion-editar');
        $nomEventsEditAction->setTreeSlug('functionality-events-action-edit');
        $nomEventsEditAction->setNomType($objNomAction);
        $nomEventsEditAction->setParent($nomEventsFunctionality);
        $nomEventsEditAction->setPriority(3);
        $manager->persist($nomEventsEditAction);

        $nomEventsDeleteAction = new Nomenclature();
        $nomEventsDeleteAction->setName('Eliminar');
        $nomEventsDeleteAction->setUrlSlug('eventos-accion-eliminar');
        $nomEventsDeleteAction->setTreeSlug('functionality-events-action-delete');
        $nomEventsDeleteAction->setNomType($objNomAction);
        $nomEventsDeleteAction->setParent($nomEventsFunctionality);
        $nomEventsDeleteAction->setPriority(4);
        $manager->persist($nomEventsDeleteAction);

        $nomEventsChangeStatusAction = new Nomenclature();
        $nomEventsChangeStatusAction->setName('Cambiar Status');
        $nomEventsChangeStatusAction->setUrlSlug('eventos-accion-cambiar-status');
        $nomEventsChangeStatusAction->setTreeSlug('functionality-events-action-change-status');
        $nomEventsChangeStatusAction->setNomType($objNomAction);
        $nomEventsChangeStatusAction->setParent($nomEventsFunctionality);
        $nomEventsChangeStatusAction->setPriority(5);
        $manager->persist($nomEventsChangeStatusAction);*/

        /*pages*/
        $nomPagesFunctionality = new Nomenclature();
        $nomPagesFunctionality->setName('Páginas');
        $nomPagesFunctionality->setUrlSlug('paginas');
        $nomPagesFunctionality->setTreeSlug('functionality-pages');
        $nomPagesFunctionality->setNomType($objNomTypeFunctionality);
        $nomPagesFunctionality->setPriority(5);
        $manager->persist($nomPagesFunctionality);
        $manager->flush();
        $pagesFunctionality = new NomFunctionality();
        $pagesFunctionality->setId($nomPagesFunctionality);
        $pagesFunctionality->setIconClass('icon-doc');
        $pagesFunctionality->setUrlIndexAction('pages_index');
        $pagesFunctionality->setKeywordSelectedClass($nomPagesFunctionality->getUrlSlug());
        $pagesFunctionality->setIsUsedFrequently(true);
        $manager->persist($pagesFunctionality);

        //Actions for pages
        $nomPagesReadAction = new Nomenclature();
        $nomPagesReadAction->setName('Leer');
        $nomPagesReadAction->setUrlSlug('paginas-accion-leer');
        $nomPagesReadAction->setTreeSlug('functionality-pages-action-read');
        $nomPagesReadAction->setNomType($objNomAction);
        $nomPagesReadAction->setParent($nomPagesFunctionality);
        $nomPagesReadAction->setPriority(1);
        $manager->persist($nomPagesReadAction);

        $nomPagesCreateAction = new Nomenclature();
        $nomPagesCreateAction->setName('Crear');
        $nomPagesCreateAction->setUrlSlug('paginas-accion-crear');
        $nomPagesCreateAction->setTreeSlug('functionality-pages-action-create');
        $nomPagesCreateAction->setNomType($objNomAction);
        $nomPagesCreateAction->setParent($nomPagesFunctionality);
        $nomPagesCreateAction->setPriority(2);
        $manager->persist($nomPagesCreateAction);

        $nomPagesEditAction = new Nomenclature();
        $nomPagesEditAction->setName('Editar');
        $nomPagesEditAction->setUrlSlug('paginas-accion-editar');
        $nomPagesEditAction->setTreeSlug('functionality-pages-action-edit');
        $nomPagesEditAction->setNomType($objNomAction);
        $nomPagesEditAction->setParent($nomPagesFunctionality);
        $nomPagesEditAction->setPriority(3);
        $manager->persist($nomPagesEditAction);

        $nomPagesDeleteAction = new Nomenclature();
        $nomPagesDeleteAction->setName('Eliminar');
        $nomPagesDeleteAction->setUrlSlug('paginas-accion-eliminar');
        $nomPagesDeleteAction->setTreeSlug('functionality-pages-action-delete');
        $nomPagesDeleteAction->setNomType($objNomAction);
        $nomPagesDeleteAction->setParent($nomPagesFunctionality);
        $nomPagesDeleteAction->setPriority(4);
        $manager->persist($nomPagesDeleteAction);

        $nomPagesChangeStatusAction = new Nomenclature();
        $nomPagesChangeStatusAction->setName('Cambiar Status');
        $nomPagesChangeStatusAction->setUrlSlug('paginas-accion-cambiar-status');
        $nomPagesChangeStatusAction->setTreeSlug('functionality-pages-action-change-status');
        $nomPagesChangeStatusAction->setNomType($objNomAction);
        $nomPagesChangeStatusAction->setParent($nomPagesFunctionality);
        $nomPagesChangeStatusAction->setPriority(5);
        $manager->persist($nomPagesChangeStatusAction);


        /*Comments*/
        $nomCommentsFunctionality = new Nomenclature();
        $nomCommentsFunctionality->setName('Comentarios');
        $nomCommentsFunctionality->setUrlSlug('comentarios');
        $nomCommentsFunctionality->setTreeSlug('functionality-comments');
        $nomCommentsFunctionality->setNomType($objNomTypeFunctionality);
        $nomCommentsFunctionality->setPriority(6);
        $manager->persist($nomCommentsFunctionality);
        $manager->flush();
        $commentsFunctionality = new NomFunctionality();
        $commentsFunctionality->setId($nomCommentsFunctionality);
        $commentsFunctionality->setIconClass('icon-bubbles');
        $commentsFunctionality->setUrlIndexAction('comments_index');
        $commentsFunctionality->setKeywordSelectedClass($nomCommentsFunctionality->getUrlSlug());
        $commentsFunctionality->setIsUsedFrequently(true);
        $manager->persist($commentsFunctionality);

        //Actions for Comments
        $nomCommentsReadAction = new Nomenclature();
        $nomCommentsReadAction->setName('Leer');
        $nomCommentsReadAction->setUrlSlug('comentarios-accion-leer');
        $nomCommentsReadAction->setTreeSlug('functionality-comments-action-read');
        $nomCommentsReadAction->setNomType($objNomAction);
        $nomCommentsReadAction->setParent($nomCommentsFunctionality);
        $nomCommentsReadAction->setPriority(1);
        $manager->persist($nomCommentsReadAction);

        $nomCommentsCreateAction = new Nomenclature();
        $nomCommentsCreateAction->setName('Crear');
        $nomCommentsCreateAction->setUrlSlug('comentarios-accion-crear');
        $nomCommentsCreateAction->setTreeSlug('functionality-comments-action-create');
        $nomCommentsCreateAction->setNomType($objNomAction);
        $nomCommentsCreateAction->setParent($nomCommentsFunctionality);
        $nomCommentsCreateAction->setPriority(2);
        $manager->persist($nomCommentsCreateAction);

        $nomCommentsEditAction = new Nomenclature();
        $nomCommentsEditAction->setName('Editar');
        $nomCommentsEditAction->setUrlSlug('comentarios-accion-editar');
        $nomCommentsEditAction->setTreeSlug('functionality-comments-action-edit');
        $nomCommentsEditAction->setNomType($objNomAction);
        $nomCommentsEditAction->setParent($nomCommentsFunctionality);
        $nomCommentsEditAction->setPriority(3);
        $manager->persist($nomCommentsEditAction);

        $nomCommentsDeleteAction = new Nomenclature();
        $nomCommentsDeleteAction->setName('Eliminar');
        $nomCommentsDeleteAction->setUrlSlug('comentarios-accion-eliminar');
        $nomCommentsDeleteAction->setTreeSlug('functionality-comments-action-delete');
        $nomCommentsDeleteAction->setNomType($objNomAction);
        $nomCommentsDeleteAction->setParent($nomCommentsFunctionality);
        $nomCommentsDeleteAction->setPriority(4);
        $manager->persist($nomCommentsDeleteAction);

        $nomCommentsChangeStatusAction = new Nomenclature();
        $nomCommentsChangeStatusAction->setName('Cambiar Status');
        $nomCommentsChangeStatusAction->setUrlSlug('comentarios-accion-cambiar-status');
        $nomCommentsChangeStatusAction->setTreeSlug('functionality-comments-action-change-status');
        $nomCommentsChangeStatusAction->setNomType($objNomAction);
        $nomCommentsChangeStatusAction->setParent($nomCommentsFunctionality);
        $nomCommentsChangeStatusAction->setPriority(5);
        $manager->persist($nomCommentsChangeStatusAction);


        /*Media*/
        $nomMediaFunctionality = new Nomenclature();
        $nomMediaFunctionality->setName('Media');
        $nomMediaFunctionality->setUrlSlug('media');
        $nomMediaFunctionality->setTreeSlug('functionality-media');
        $nomMediaFunctionality->setNomType($objNomTypeFunctionality);
        $nomMediaFunctionality->setPriority(8);
        $manager->persist($nomMediaFunctionality);
        $manager->flush();
        $mediaFunctionality = new NomFunctionality();
        $mediaFunctionality->setId($nomMediaFunctionality);
        $mediaFunctionality->setIconClass('icon-camera');
        $mediaFunctionality->setUrlIndexAction('media_index');
        $mediaFunctionality->setKeywordSelectedClass($nomMediaFunctionality->getUrlSlug());
        $manager->persist($mediaFunctionality);

        //Actions for Media
        $nomMediaReadAction = new Nomenclature();
        $nomMediaReadAction->setName('Leer');
        $nomMediaReadAction->setUrlSlug('media-accion-leer');
        $nomMediaReadAction->setTreeSlug('functionality-media-action-read');
        $nomMediaReadAction->setNomType($objNomAction);
        $nomMediaReadAction->setParent($nomMediaFunctionality);
        $nomMediaReadAction->setPriority(1);
        $manager->persist($nomMediaReadAction);

        $nomMediaCreateAction = new Nomenclature();
        $nomMediaCreateAction->setName('Crear');
        $nomMediaCreateAction->setUrlSlug('media-accion-crear');
        $nomMediaCreateAction->setTreeSlug('functionality-media-action-create');
        $nomMediaCreateAction->setNomType($objNomAction);
        $nomMediaCreateAction->setParent($nomMediaFunctionality);
        $nomMediaCreateAction->setPriority(2);
        $manager->persist($nomMediaCreateAction);

        $nomMediaEditAction = new Nomenclature();
        $nomMediaEditAction->setName('Editar');
        $nomMediaEditAction->setUrlSlug('media-accion-editar');
        $nomMediaEditAction->setTreeSlug('functionality-media-action-edit');
        $nomMediaEditAction->setNomType($objNomAction);
        $nomMediaEditAction->setParent($nomMediaFunctionality);
        $nomMediaEditAction->setPriority(3);
        $manager->persist($nomMediaEditAction);

        $nomMediaDeleteAction = new Nomenclature();
        $nomMediaDeleteAction->setName('Eliminar');
        $nomMediaDeleteAction->setUrlSlug('media-accion-eliminar');
        $nomMediaDeleteAction->setTreeSlug('functionality-media-action-delete');
        $nomMediaDeleteAction->setNomType($objNomAction);
        $nomMediaDeleteAction->setParent($nomMediaFunctionality);
        $nomMediaDeleteAction->setPriority(4);
        $manager->persist($nomMediaDeleteAction);


        /*Opinions*/
        /*$nomOpinionsFunctionality = new Nomenclature();
        $nomOpinionsFunctionality->setName('Críticas y Opiniones');
        $nomOpinionsFunctionality->setUrlSlug('opiniones');
        $nomOpinionsFunctionality->setTreeSlug('functionality-opinion');
        $nomOpinionsFunctionality->setNomType($objNomTypeFunctionality);
        $nomOpinionsFunctionality->setPriority(9);
        $manager->persist($nomOpinionsFunctionality);
        $manager->flush();
        $opinionsFunctionality = new NomFunctionality();
        $opinionsFunctionality->setId($nomOpinionsFunctionality);
        $opinionsFunctionality->setIconClass('icon-speech');
        $opinionsFunctionality->setUrlIndexAction('opinions_index');
        $opinionsFunctionality->setKeywordSelectedClass($nomOpinionsFunctionality->getUrlSlug());
        $manager->persist($opinionsFunctionality);

        //Actions for Opinions
        $nomOpinionsReadAction = new Nomenclature();
        $nomOpinionsReadAction->setName('Leer');
        $nomOpinionsReadAction->setUrlSlug('opiniones-accion-leer');
        $nomOpinionsReadAction->setTreeSlug('functionality-opinions-action-read');
        $nomOpinionsReadAction->setNomType($objNomAction);
        $nomOpinionsReadAction->setParent($nomOpinionsFunctionality);
        $nomOpinionsReadAction->setPriority(1);
        $manager->persist($nomOpinionsReadAction);

        $nomOpinionsCreateAction = new Nomenclature();
        $nomOpinionsCreateAction->setName('Crear');
        $nomOpinionsCreateAction->setUrlSlug('opiniones-accion-crear');
        $nomOpinionsCreateAction->setTreeSlug('functionality-opinions-action-create');
        $nomOpinionsCreateAction->setNomType($objNomAction);
        $nomOpinionsCreateAction->setParent($nomOpinionsFunctionality);
        $nomOpinionsCreateAction->setPriority(2);
        $manager->persist($nomOpinionsCreateAction);

        $nomOpinionsEditAction = new Nomenclature();
        $nomOpinionsEditAction->setName('Editar');
        $nomOpinionsEditAction->setUrlSlug('opiniones-accion-editar');
        $nomOpinionsEditAction->setTreeSlug('functionality-opinions-action-edit');
        $nomOpinionsEditAction->setNomType($objNomAction);
        $nomOpinionsEditAction->setParent($nomOpinionsFunctionality);
        $nomOpinionsEditAction->setPriority(3);
        $manager->persist($nomOpinionsEditAction);

        $nomOpinionsDeleteAction = new Nomenclature();
        $nomOpinionsDeleteAction->setName('Eliminar');
        $nomOpinionsDeleteAction->setUrlSlug('opiniones-accion-eliminar');
        $nomOpinionsDeleteAction->setTreeSlug('functionality-opinions-action-delete');
        $nomOpinionsDeleteAction->setNomType($objNomAction);
        $nomOpinionsDeleteAction->setParent($nomOpinionsFunctionality);
        $nomOpinionsDeleteAction->setPriority(4);
        $manager->persist($nomOpinionsDeleteAction);

        $nomOpinionsChangeStatusAction = new Nomenclature();
        $nomOpinionsChangeStatusAction->setName('Cambiar Status');
        $nomOpinionsChangeStatusAction->setUrlSlug('opiniones-accion-eliminar-status');
        $nomOpinionsChangeStatusAction->setTreeSlug('functionality-opinions-action-change-status');
        $nomOpinionsChangeStatusAction->setNomType($objNomAction);
        $nomOpinionsChangeStatusAction->setParent($nomOpinionsFunctionality);
        $nomOpinionsChangeStatusAction->setPriority(5);
        $manager->persist($nomOpinionsChangeStatusAction);*/


        /*Partners*/
        /*$nomPartnersFunctionality = new Nomenclature();
        $nomPartnersFunctionality->setName('Socios');
        $nomPartnersFunctionality->setUrlSlug('socios');
        $nomPartnersFunctionality->setTreeSlug('functionality-partners');
        $nomPartnersFunctionality->setNomType($objNomTypeFunctionality);
        $nomPartnersFunctionality->setPriority(10);
        $manager->persist($nomPartnersFunctionality);
        $manager->flush();
        $partnersFunctionality = new NomFunctionality();
        $partnersFunctionality->setId($nomPartnersFunctionality);
        $partnersFunctionality->setIconClass('icon-users');
        $partnersFunctionality->setUrlIndexAction('partners_index');
        $partnersFunctionality->setKeywordSelectedClass($nomPartnersFunctionality->getUrlSlug());
        $manager->persist($partnersFunctionality);

        //Actions for Partners
        $nomPartnersReadAction = new Nomenclature();
        $nomPartnersReadAction->setName('Leer');
        $nomPartnersReadAction->setUrlSlug('socios-accion-leer');
        $nomPartnersReadAction->setTreeSlug('functionality-partners-action-read');
        $nomPartnersReadAction->setNomType($objNomAction);
        $nomPartnersReadAction->setParent($nomPartnersFunctionality);
        $nomPartnersReadAction->setPriority(1);
        $manager->persist($nomPartnersReadAction);

        $nomPartnersCreateAction = new Nomenclature();
        $nomPartnersCreateAction->setName('Crear');
        $nomPartnersCreateAction->setUrlSlug('socios-accion-crear');
        $nomPartnersCreateAction->setTreeSlug('functionality-partners-action-create');
        $nomPartnersCreateAction->setNomType($objNomAction);
        $nomPartnersCreateAction->setParent($nomPartnersFunctionality);
        $nomPartnersCreateAction->setPriority(2);
        $manager->persist($nomPartnersCreateAction);

        $nomPartnersEditAction = new Nomenclature();
        $nomPartnersEditAction->setName('Editar');
        $nomPartnersEditAction->setUrlSlug('socios-accion-editar');
        $nomPartnersEditAction->setTreeSlug('functionality-partners-action-edit');
        $nomPartnersEditAction->setNomType($objNomAction);
        $nomPartnersEditAction->setParent($nomPartnersFunctionality);
        $nomPartnersEditAction->setPriority(3);
        $manager->persist($nomPartnersEditAction);

        $nomPartnersDeleteAction = new Nomenclature();
        $nomPartnersDeleteAction->setName('Eliminar');
        $nomPartnersDeleteAction->setUrlSlug('socios-accion-eliminar');
        $nomPartnersDeleteAction->setTreeSlug('functionality-partners-action-delete');
        $nomPartnersDeleteAction->setNomType($objNomAction);
        $nomPartnersDeleteAction->setParent($nomPartnersFunctionality);
        $nomPartnersDeleteAction->setPriority(4);
        $manager->persist($nomPartnersDeleteAction);

        $nomPartnersChangeStatusAction = new Nomenclature();
        $nomPartnersChangeStatusAction->setName('Cambiar Status');
        $nomPartnersChangeStatusAction->setUrlSlug('socios-accion-cambiar-status');
        $nomPartnersChangeStatusAction->setTreeSlug('functionality-partners-action-change-status');
        $nomPartnersChangeStatusAction->setNomType($objNomAction);
        $nomPartnersChangeStatusAction->setParent($nomPartnersFunctionality);
        $nomPartnersChangeStatusAction->setPriority(5);
        $manager->persist($nomPartnersChangeStatusAction);*/


        /*Historical-Moments*/
        /*$nomHistoricalMomentsFunctionality = new Nomenclature();
        $nomHistoricalMomentsFunctionality->setName('Hitos Históricos');
        $nomHistoricalMomentsFunctionality->setUrlSlug('hitos-historicos');
        $nomHistoricalMomentsFunctionality->setTreeSlug('functionality-historical-moments');
        $nomHistoricalMomentsFunctionality->setNomType($objNomTypeFunctionality);
        $nomHistoricalMomentsFunctionality->setPriority(11);
        $manager->persist($nomHistoricalMomentsFunctionality);
        $manager->flush();
        $historicalMomentsFunctionality = new NomFunctionality();
        $historicalMomentsFunctionality->setId($nomHistoricalMomentsFunctionality);
        $historicalMomentsFunctionality->setIconClass('icon-globe-alt');
        $historicalMomentsFunctionality->setUrlIndexAction('historical_moments_index');
        $historicalMomentsFunctionality->setKeywordSelectedClass($nomHistoricalMomentsFunctionality->getUrlSlug());
        $manager->persist($historicalMomentsFunctionality);

        //Actions for Historical-Moments
        $nomHistoricalMomentsReadAction = new Nomenclature();
        $nomHistoricalMomentsReadAction->setName('Leer');
        $nomHistoricalMomentsReadAction->setUrlSlug('hitos-historicos-accion-leer');
        $nomHistoricalMomentsReadAction->setTreeSlug('functionality-historical-moments-action-read');
        $nomHistoricalMomentsReadAction->setNomType($objNomAction);
        $nomHistoricalMomentsReadAction->setParent($nomHistoricalMomentsFunctionality);
        $nomHistoricalMomentsReadAction->setPriority(1);
        $manager->persist($nomHistoricalMomentsReadAction);

        $nomHistoricalMomentsCreateAction = new Nomenclature();
        $nomHistoricalMomentsCreateAction->setName('Crear');
        $nomHistoricalMomentsCreateAction->setUrlSlug('hitos-historicos-accion-crear');
        $nomHistoricalMomentsCreateAction->setTreeSlug('functionality-historical-moments-action-create');
        $nomHistoricalMomentsCreateAction->setNomType($objNomAction);
        $nomHistoricalMomentsCreateAction->setParent($nomHistoricalMomentsFunctionality);
        $nomHistoricalMomentsCreateAction->setPriority(2);
        $manager->persist($nomHistoricalMomentsCreateAction);

        $nomHistoricalMomentsEditAction = new Nomenclature();
        $nomHistoricalMomentsEditAction->setName('Editar');
        $nomHistoricalMomentsEditAction->setUrlSlug('hitos-historicos-accion-editar');
        $nomHistoricalMomentsEditAction->setTreeSlug('functionality-historical-moments-action-edit');
        $nomHistoricalMomentsEditAction->setNomType($objNomAction);
        $nomHistoricalMomentsEditAction->setParent($nomHistoricalMomentsFunctionality);
        $nomHistoricalMomentsEditAction->setPriority(3);
        $manager->persist($nomHistoricalMomentsEditAction);

        $nomHistoricalMomentsDeleteAction = new Nomenclature();
        $nomHistoricalMomentsDeleteAction->setName('Eliminar');
        $nomHistoricalMomentsDeleteAction->setUrlSlug('hitos-historicos-accion-eliminar');
        $nomHistoricalMomentsDeleteAction->setTreeSlug('functionality-historical-moments-action-delete');
        $nomHistoricalMomentsDeleteAction->setNomType($objNomAction);
        $nomHistoricalMomentsDeleteAction->setParent($nomHistoricalMomentsFunctionality);
        $nomHistoricalMomentsDeleteAction->setPriority(4);
        $manager->persist($nomHistoricalMomentsDeleteAction);

        $nomHistoricalMomentsChangeStatusAction = new Nomenclature();
        $nomHistoricalMomentsChangeStatusAction->setName('Cambiar Status');
        $nomHistoricalMomentsChangeStatusAction->setUrlSlug('hitos-historicos-accion-cambiar-status');
        $nomHistoricalMomentsChangeStatusAction->setTreeSlug('functionality-historical-moments-action-change-status');
        $nomHistoricalMomentsChangeStatusAction->setNomType($objNomAction);
        $nomHistoricalMomentsChangeStatusAction->setParent($nomHistoricalMomentsFunctionality);
        $nomHistoricalMomentsChangeStatusAction->setPriority(5);
        $manager->persist($nomHistoricalMomentsChangeStatusAction);*/


        /*Jewels*/
        /*$nomJewelsFunctionality = new Nomenclature();
        $nomJewelsFunctionality->setName('Joyas del BNC');
        $nomJewelsFunctionality->setUrlSlug('joyas');
        $nomJewelsFunctionality->setTreeSlug('functionality-jewels');
        $nomJewelsFunctionality->setNomType($objNomTypeFunctionality);
        $nomJewelsFunctionality->setPriority(11);
        $manager->persist($nomJewelsFunctionality);
        $manager->flush();
        $jewelsFunctionality = new NomFunctionality();
        $jewelsFunctionality->setId($nomJewelsFunctionality);
        $jewelsFunctionality->setIconClass('icon-diamond');
        $jewelsFunctionality->setUrlIndexAction('jewels_index');
        $jewelsFunctionality->setKeywordSelectedClass($nomJewelsFunctionality->getUrlSlug());
        $manager->persist($jewelsFunctionality);

        //Actions for Jewels
        $nomJewelsReadAction = new Nomenclature();
        $nomJewelsReadAction->setName('Leer');
        $nomJewelsReadAction->setUrlSlug('joyas-accion-leer');
        $nomJewelsReadAction->setTreeSlug('functionality-jewels-action-read');
        $nomJewelsReadAction->setNomType($objNomAction);
        $nomJewelsReadAction->setParent($nomJewelsFunctionality);
        $nomJewelsReadAction->setPriority(1);
        $manager->persist($nomJewelsReadAction);

        $nomJewelsCreateAction = new Nomenclature();
        $nomJewelsCreateAction->setName('Crear');
        $nomJewelsCreateAction->setUrlSlug('joyas-accion-crear');
        $nomJewelsCreateAction->setTreeSlug('functionality-jewels-action-create');
        $nomJewelsCreateAction->setNomType($objNomAction);
        $nomJewelsCreateAction->setParent($nomJewelsFunctionality);
        $nomJewelsCreateAction->setPriority(2);
        $manager->persist($nomJewelsCreateAction);

        $nomJewelsEditAction = new Nomenclature();
        $nomJewelsEditAction->setName('Editar');
        $nomJewelsEditAction->setUrlSlug('joyas-accion-editar');
        $nomJewelsEditAction->setTreeSlug('functionality-jewels-action-edit');
        $nomJewelsEditAction->setNomType($objNomAction);
        $nomJewelsEditAction->setParent($nomJewelsFunctionality);
        $nomJewelsEditAction->setPriority(3);
        $manager->persist($nomJewelsEditAction);

        $nomJewelsDeleteAction = new Nomenclature();
        $nomJewelsDeleteAction->setName('Eliminar');
        $nomJewelsDeleteAction->setUrlSlug('joyas-accion-eliminar');
        $nomJewelsDeleteAction->setTreeSlug('functionality-jewels-action-delete');
        $nomJewelsDeleteAction->setNomType($objNomAction);
        $nomJewelsDeleteAction->setParent($nomJewelsFunctionality);
        $nomJewelsDeleteAction->setPriority(4);
        $manager->persist($nomJewelsDeleteAction);

        $nomJewelsChangeStatusAction = new Nomenclature();
        $nomJewelsChangeStatusAction->setName('Cambiar Status');
        $nomJewelsChangeStatusAction->setUrlSlug('joyas-accion-cambiar-status');
        $nomJewelsChangeStatusAction->setTreeSlug('functionality-jewels-action-change-status');
        $nomJewelsChangeStatusAction->setNomType($objNomAction);
        $nomJewelsChangeStatusAction->setParent($nomJewelsFunctionality);
        $nomJewelsChangeStatusAction->setPriority(4);
        $manager->persist($nomJewelsChangeStatusAction);*/


        /*Composition*/
       /* $nomCompositionFunctionality = new Nomenclature();
        $nomCompositionFunctionality->setName('Composición del BNC');
        $nomCompositionFunctionality->setUrlSlug('composicion');
        $nomCompositionFunctionality->setTreeSlug('functionality-composition');
        $nomCompositionFunctionality->setNomType($objNomTypeFunctionality);
        $nomCompositionFunctionality->setPriority(12);
        $manager->persist($nomCompositionFunctionality);
        $manager->flush();
        $compositionFunctionality = new NomFunctionality();
        $compositionFunctionality->setId($nomCompositionFunctionality);
        $compositionFunctionality->setIconClass('icon-users');
        $compositionFunctionality->setUrlIndexAction('composition_index');
        $compositionFunctionality->setKeywordSelectedClass($nomCompositionFunctionality->getUrlSlug());
        $manager->persist($compositionFunctionality);

        //Actions for Composition
        $nomCompositionReadAction = new Nomenclature();
        $nomCompositionReadAction->setName('Leer');
        $nomCompositionReadAction->setUrlSlug('composicion-accion-leer');
        $nomCompositionReadAction->setTreeSlug('functionality-composition-action-read');
        $nomCompositionReadAction->setNomType($objNomAction);
        $nomCompositionReadAction->setParent($nomCompositionFunctionality);
        $nomCompositionReadAction->setPriority(1);
        $manager->persist($nomCompositionReadAction);

        $nomCompositionCreateAction = new Nomenclature();
        $nomCompositionCreateAction->setName('Crear');
        $nomCompositionCreateAction->setUrlSlug('composicion-accion-crear');
        $nomCompositionCreateAction->setTreeSlug('functionality-composition-action-create');
        $nomCompositionCreateAction->setNomType($objNomAction);
        $nomCompositionCreateAction->setParent($nomCompositionFunctionality);
        $nomCompositionCreateAction->setPriority(2);
        $manager->persist($nomCompositionCreateAction);

        $nomCompositionEditAction = new Nomenclature();
        $nomCompositionEditAction->setName('Editar');
        $nomCompositionEditAction->setUrlSlug('composicion-accion-editar');
        $nomCompositionEditAction->setTreeSlug('functionality-composition-action-edit');
        $nomCompositionEditAction->setNomType($objNomAction);
        $nomCompositionEditAction->setParent($nomCompositionFunctionality);
        $nomCompositionEditAction->setPriority(3);
        $manager->persist($nomCompositionEditAction);

        $nomCompositionDeleteAction = new Nomenclature();
        $nomCompositionDeleteAction->setName('Eliminar');
        $nomCompositionDeleteAction->setUrlSlug('composicion-accion-eliminar');
        $nomCompositionDeleteAction->setTreeSlug('functionality-composition-action-delete');
        $nomCompositionDeleteAction->setNomType($objNomAction);
        $nomCompositionDeleteAction->setParent($nomCompositionFunctionality);
        $nomCompositionDeleteAction->setPriority(4);
        $manager->persist($nomCompositionDeleteAction);

        $nomCompositionChangeStatusAction = new Nomenclature();
        $nomCompositionChangeStatusAction->setName('Cambiar Status');
        $nomCompositionChangeStatusAction->setUrlSlug('composicion-accion-cambiar-status');
        $nomCompositionChangeStatusAction->setTreeSlug('functionality-composition-action-change-status');
        $nomCompositionChangeStatusAction->setNomType($objNomAction);
        $nomCompositionChangeStatusAction->setParent($nomCompositionFunctionality);
        $nomCompositionChangeStatusAction->setPriority(5);
        $manager->persist($nomCompositionChangeStatusAction);*/


        /*Repertory*/
        /*$nomRepertoryFunctionality = new Nomenclature();
        $nomRepertoryFunctionality->setName('Repertorio');
        $nomRepertoryFunctionality->setUrlSlug('repertorio');
        $nomRepertoryFunctionality->setTreeSlug('functionality-repertory');
        $nomRepertoryFunctionality->setNomType($objNomTypeFunctionality);
        $nomRepertoryFunctionality->setPriority(13);
        $manager->persist($nomRepertoryFunctionality);
        $manager->flush();
        $repertoryFunctionality = new NomFunctionality();
        $repertoryFunctionality->setId($nomRepertoryFunctionality);
        $repertoryFunctionality->setIconClass('icon-notebook');
        $repertoryFunctionality->setUrlIndexAction('repertory_index');
        $repertoryFunctionality->setKeywordSelectedClass($nomRepertoryFunctionality->getUrlSlug());
        $manager->persist($repertoryFunctionality);

        //Actions for Repertory
        $nomRepertoryReadAction = new Nomenclature();
        $nomRepertoryReadAction->setName('Leer');
        $nomRepertoryReadAction->setUrlSlug('repertorio-accion-leer');
        $nomRepertoryReadAction->setTreeSlug('functionality-repertory-action-read');
        $nomRepertoryReadAction->setNomType($objNomAction);
        $nomRepertoryReadAction->setParent($nomRepertoryFunctionality);
        $nomRepertoryReadAction->setPriority(1);
        $manager->persist($nomRepertoryReadAction);

        $nomRepertoryCreateAction = new Nomenclature();
        $nomRepertoryCreateAction->setName('Crear');
        $nomRepertoryCreateAction->setUrlSlug('repertorio-accion-crear');
        $nomRepertoryCreateAction->setTreeSlug('functionality-repertory-action-create');
        $nomRepertoryCreateAction->setNomType($objNomAction);
        $nomRepertoryCreateAction->setParent($nomRepertoryFunctionality);
        $nomRepertoryCreateAction->setPriority(2);
        $manager->persist($nomRepertoryCreateAction);

        $nomRepertoryEditAction = new Nomenclature();
        $nomRepertoryEditAction->setName('Editar');
        $nomRepertoryEditAction->setUrlSlug('repertorio-accion-editar');
        $nomRepertoryEditAction->setTreeSlug('functionality-repertory-action-edit');
        $nomRepertoryEditAction->setNomType($objNomAction);
        $nomRepertoryEditAction->setParent($nomRepertoryFunctionality);
        $nomRepertoryEditAction->setPriority(3);
        $manager->persist($nomRepertoryEditAction);

        $nomRepertoryDeleteAction = new Nomenclature();
        $nomRepertoryDeleteAction->setName('Eliminar');
        $nomRepertoryDeleteAction->setUrlSlug('repertorio-accion-eliminar');
        $nomRepertoryDeleteAction->setTreeSlug('functionality-repertory-action-delete');
        $nomRepertoryDeleteAction->setNomType($objNomAction);
        $nomRepertoryDeleteAction->setParent($nomRepertoryFunctionality);
        $nomRepertoryDeleteAction->setPriority(4);
        $manager->persist($nomRepertoryDeleteAction);

        $nomRepertoryChangeStatusAction = new Nomenclature();
        $nomRepertoryChangeStatusAction->setName('Cambiar Status');
        $nomRepertoryChangeStatusAction->setUrlSlug('repertorio-accion-cambiar-status');
        $nomRepertoryChangeStatusAction->setTreeSlug('functionality-repertory-action-change-status');
        $nomRepertoryChangeStatusAction->setNomType($objNomAction);
        $nomRepertoryChangeStatusAction->setParent($nomRepertoryFunctionality);
        $nomRepertoryChangeStatusAction->setPriority(5);
        $manager->persist($nomRepertoryChangeStatusAction);*/


        /*Awards*/
        /*$nomAwardsFunctionality = new Nomenclature();
        $nomAwardsFunctionality->setName('Distinciones');
        $nomAwardsFunctionality->setUrlSlug('distinciones');
        $nomAwardsFunctionality->setTreeSlug('functionality-awards');
        $nomAwardsFunctionality->setNomType($objNomTypeFunctionality);
        $nomAwardsFunctionality->setPriority(14);
        $manager->persist($nomAwardsFunctionality);
        $manager->flush();
        $awardsFunctionality = new NomFunctionality();
        $awardsFunctionality->setId($nomAwardsFunctionality);
        $awardsFunctionality->setIconClass('icon-badge');
        $awardsFunctionality->setUrlIndexAction('awards_index');
        $awardsFunctionality->setKeywordSelectedClass($nomAwardsFunctionality->getUrlSlug());
        $manager->persist($awardsFunctionality);

        //Actions for Awards
        $nomAwardsReadAction = new Nomenclature();
        $nomAwardsReadAction->setName('Leer');
        $nomAwardsReadAction->setUrlSlug('distinciones-accion-leer');
        $nomAwardsReadAction->setTreeSlug('functionality-awards-action-read');
        $nomAwardsReadAction->setNomType($objNomAction);
        $nomAwardsReadAction->setParent($nomAwardsFunctionality);
        $nomAwardsReadAction->setPriority(1);
        $manager->persist($nomAwardsReadAction);

        $nomAwardsCreateAction = new Nomenclature();
        $nomAwardsCreateAction->setName('Crear');
        $nomAwardsCreateAction->setUrlSlug('distinciones-accion-crear');
        $nomAwardsCreateAction->setTreeSlug('functionality-awards-action-create');
        $nomAwardsCreateAction->setNomType($objNomAction);
        $nomAwardsCreateAction->setParent($nomAwardsFunctionality);
        $nomAwardsCreateAction->setPriority(2);
        $manager->persist($nomAwardsCreateAction);

        $nomAwardsEditAction = new Nomenclature();
        $nomAwardsEditAction->setName('Editar');
        $nomAwardsEditAction->setUrlSlug('distinciones-accion-editar');
        $nomAwardsEditAction->setTreeSlug('functionality-awards-action-edit');
        $nomAwardsEditAction->setNomType($objNomAction);
        $nomAwardsEditAction->setParent($nomAwardsFunctionality);
        $nomAwardsEditAction->setPriority(3);
        $manager->persist($nomAwardsEditAction);

        $nomAwardsDeleteAction = new Nomenclature();
        $nomAwardsDeleteAction->setName('Eliminar');
        $nomAwardsDeleteAction->setUrlSlug('distinciones-accion-eliminar');
        $nomAwardsDeleteAction->setTreeSlug('functionality-awards-action-delete');
        $nomAwardsDeleteAction->setNomType($objNomAction);
        $nomAwardsDeleteAction->setParent($nomAwardsFunctionality);
        $nomAwardsDeleteAction->setPriority(4);
        $manager->persist($nomAwardsDeleteAction);

        $nomAwardsChangeStatusAction = new Nomenclature();
        $nomAwardsChangeStatusAction->setName('Cambiar Status');
        $nomAwardsChangeStatusAction->setUrlSlug('distinciones-accion-cambiar-status');
        $nomAwardsChangeStatusAction->setTreeSlug('functionality-awards-action-change-status');
        $nomAwardsChangeStatusAction->setNomType($objNomAction);
        $nomAwardsChangeStatusAction->setParent($nomAwardsFunctionality);
        $nomAwardsChangeStatusAction->setPriority(5);
        $manager->persist($nomAwardsChangeStatusAction);*/


        /*Users*/
        $nomUsersFunctionality = new Nomenclature();
        $nomUsersFunctionality->setName('Usuarios');
        $nomUsersFunctionality->setUrlSlug('usuarios');
        $nomUsersFunctionality->setTreeSlug('functionality-users');
        $nomUsersFunctionality->setNomType($objNomTypeFunctionality);
        $nomUsersFunctionality->setPriority(15);
        $manager->persist($nomUsersFunctionality);
        $manager->flush();
        $usersFunctionality = new NomFunctionality();
        $usersFunctionality->setId($nomUsersFunctionality);
        $usersFunctionality->setIconClass('icon-users');
        $usersFunctionality->setUrlIndexAction('users_index');
        $usersFunctionality->setKeywordSelectedClass($nomUsersFunctionality->getUrlSlug());
        $manager->persist($usersFunctionality);

        //Actions for Users
        $nomUsersReadAction = new Nomenclature();
        $nomUsersReadAction->setName('Leer');
        $nomUsersReadAction->setUrlSlug('usuarios-accion-leer');
        $nomUsersReadAction->setTreeSlug('functionality-users-action-read');
        $nomUsersReadAction->setNomType($objNomAction);
        $nomUsersReadAction->setParent($nomUsersFunctionality);
        $nomUsersReadAction->setPriority(1);
        $manager->persist($nomUsersReadAction);

        $nomUsersEditAction = new Nomenclature();
        $nomUsersEditAction->setName('Editar');
        $nomUsersEditAction->setUrlSlug('usuarios-accion-editar');
        $nomUsersEditAction->setTreeSlug('functionality-users-action-edit');
        $nomUsersEditAction->setNomType($objNomAction);
        $nomUsersEditAction->setParent($nomUsersFunctionality);
        $nomUsersEditAction->setPriority(2);
        $manager->persist($nomUsersEditAction);


        /*Taxonomies*/
        $nomTaxonomiesFunctionality = new Nomenclature();
        $nomTaxonomiesFunctionality->setName('Taxonomías');
        $nomTaxonomiesFunctionality->setUrlSlug('taxonomias');
        $nomTaxonomiesFunctionality->setTreeSlug('functionality-taxonomy');
        $nomTaxonomiesFunctionality->setNomType($objNomTypeFunctionality);
        $nomTaxonomiesFunctionality->setPriority(16);
        $manager->persist($nomTaxonomiesFunctionality);
        $manager->flush();
        $opinionsFunctionality = new NomFunctionality();
        $opinionsFunctionality->setId($nomTaxonomiesFunctionality);
        $opinionsFunctionality->setIconClass('icon-layers');
        $opinionsFunctionality->setUrlIndexAction('taxonomies_index');
        $opinionsFunctionality->setKeywordSelectedClass($nomTaxonomiesFunctionality->getUrlSlug());
        $manager->persist($opinionsFunctionality);

        //Actions for Taxonomies
        $nomTaxonomiesReadAction = new Nomenclature();
        $nomTaxonomiesReadAction->setName('Leer');
        $nomTaxonomiesReadAction->setUrlSlug('taxonomias-accion-leer');
        $nomTaxonomiesReadAction->setTreeSlug('functionality-taxonomy-action-read');
        $nomTaxonomiesReadAction->setNomType($objNomAction);
        $nomTaxonomiesReadAction->setParent($nomTaxonomiesFunctionality);
        $nomTaxonomiesReadAction->setPriority(1);
        $manager->persist($nomTaxonomiesReadAction);

        $nomTaxonomiesCreateAction = new Nomenclature();
        $nomTaxonomiesCreateAction->setName('Crear');
        $nomTaxonomiesCreateAction->setUrlSlug('taxonomias-accion-crear');
        $nomTaxonomiesCreateAction->setTreeSlug('functionality-taxonomy-action-create');
        $nomTaxonomiesCreateAction->setNomType($objNomAction);
        $nomTaxonomiesCreateAction->setParent($nomTaxonomiesFunctionality);
        $nomTaxonomiesCreateAction->setPriority(2);
        $manager->persist($nomTaxonomiesCreateAction);

        $nomTaxonomiesEditAction = new Nomenclature();
        $nomTaxonomiesEditAction->setName('Editar');
        $nomTaxonomiesEditAction->setUrlSlug('taxonomias-accion-editar');
        $nomTaxonomiesEditAction->setTreeSlug('functionality-taxonomy-action-edit');
        $nomTaxonomiesEditAction->setNomType($objNomAction);
        $nomTaxonomiesEditAction->setParent($nomTaxonomiesFunctionality);
        $nomTaxonomiesEditAction->setPriority(3);
        $manager->persist($nomTaxonomiesEditAction);

        $nomTaxonomiesDeleteAction = new Nomenclature();
        $nomTaxonomiesDeleteAction->setName('Eliminar');
        $nomTaxonomiesDeleteAction->setUrlSlug('taxonomias-accion-eliminar');
        $nomTaxonomiesDeleteAction->setTreeSlug('functionality-taxonomy-action-delete');
        $nomTaxonomiesDeleteAction->setNomType($objNomAction);
        $nomTaxonomiesDeleteAction->setParent($nomTaxonomiesFunctionality);
        $nomTaxonomiesDeleteAction->setPriority(4);
        $manager->persist($nomTaxonomiesDeleteAction);


        /*Settings*/
        $nomSettingsFunctionality = new Nomenclature();
        $nomSettingsFunctionality->setName('Configuración');
        $nomSettingsFunctionality->setUrlSlug('configuracion');
        $nomSettingsFunctionality->setTreeSlug('functionality-settings');
        $nomSettingsFunctionality->setNomType($objNomTypeFunctionality);
        $nomSettingsFunctionality->setPriority(17);
        $manager->persist($nomSettingsFunctionality);
        $manager->flush();
        $settingsFunctionality = new NomFunctionality();
        $settingsFunctionality->setId($nomSettingsFunctionality);
        $settingsFunctionality->setIconClass('icon-settings');
        $settingsFunctionality->setUrlIndexAction('settings_index');
        $settingsFunctionality->setKeywordSelectedClass($nomSettingsFunctionality->getUrlSlug());
        $manager->persist($settingsFunctionality);

        //Actions for Settings
        $nomSettingsReadAction = new Nomenclature();
        $nomSettingsReadAction->setName('Leer');
        $nomSettingsReadAction->setUrlSlug('configuracion-accion-leer');
        $nomSettingsReadAction->setTreeSlug('functionality-settings-action-read');
        $nomSettingsReadAction->setNomType($objNomAction);
        $nomSettingsReadAction->setParent($nomSettingsFunctionality);
        $nomSettingsReadAction->setPriority(1);
        $manager->persist($nomSettingsReadAction);

        $nomSettingsEditAction = new Nomenclature();
        $nomSettingsEditAction->setName('Editar');
        $nomSettingsEditAction->setUrlSlug('configuracion-accion-editar');
        $nomSettingsEditAction->setTreeSlug('functionality-settings-action-edit');
        $nomSettingsEditAction->setNomType($objNomAction);
        $nomSettingsEditAction->setParent($nomSettingsFunctionality);
        $nomSettingsEditAction->setPriority(2);
        $manager->persist($nomSettingsEditAction);

        /*Headquarters*/
        $nomHeadquartersFunctionality = new Nomenclature();
        $nomHeadquartersFunctionality->setName('Sedes');
        $nomHeadquartersFunctionality->setUrlSlug('sede');
        $nomHeadquartersFunctionality->setTreeSlug('functionality-headquarter');
        $nomHeadquartersFunctionality->setNomType($objNomTypeFunctionality);
        $nomHeadquartersFunctionality->setPriority(18);
        $manager->persist($nomHeadquartersFunctionality);
        $manager->flush();
        $headquarterFunctionality = new NomFunctionality();
        $headquarterFunctionality->setId($nomHeadquartersFunctionality);
        $headquarterFunctionality->setIconClass('icon-support');
        $headquarterFunctionality->setUrlIndexAction('headquarters_index');
        $headquarterFunctionality->setKeywordSelectedClass($nomHeadquartersFunctionality->getUrlSlug());
        $headquarterFunctionality->setIsUsedFrequently(false);
        $manager->persist($headquarterFunctionality);

        //Actions for Headquarters
        $nomHeadquarterReadAction = new Nomenclature();
        $nomHeadquarterReadAction->setName('Leer');
        $nomHeadquarterReadAction->setUrlSlug('sedes-accion-leer');
        $nomHeadquarterReadAction->setTreeSlug('functionality-headquarter-action-read');
        $nomHeadquarterReadAction->setNomType($objNomAction);
        $nomHeadquarterReadAction->setParent($nomHeadquartersFunctionality);
        $nomHeadquarterReadAction->setPriority(1);
        $manager->persist($nomHeadquarterReadAction);

        $nomHeadquarterCreateAction = new Nomenclature();
        $nomHeadquarterCreateAction->setName('Crear');
        $nomHeadquarterCreateAction->setUrlSlug('sedes-accion-crear');
        $nomHeadquarterCreateAction->setTreeSlug('functionality-headquarter-action-create');
        $nomHeadquarterCreateAction->setNomType($objNomAction);
        $nomHeadquarterCreateAction->setParent($nomHeadquartersFunctionality);
        $nomHeadquarterCreateAction->setPriority(2);
        $manager->persist($nomHeadquarterCreateAction);

        $nomHeadquarterEditAction = new Nomenclature();
        $nomHeadquarterEditAction->setName('Editar');
        $nomHeadquarterEditAction->setUrlSlug('sedes-accion-editar');
        $nomHeadquarterEditAction->setTreeSlug('functionality-headquarter-action-edit');
        $nomHeadquarterEditAction->setNomType($objNomAction);
        $nomHeadquarterEditAction->setParent($nomHeadquartersFunctionality);
        $nomHeadquarterEditAction->setPriority(3);
        $manager->persist($nomHeadquarterEditAction);

        $nomHeadquarterDeleteAction = new Nomenclature();
        $nomHeadquarterDeleteAction->setName('Eliminar');
        $nomHeadquarterDeleteAction->setUrlSlug('sedes-accion-eliminar');
        $nomHeadquarterDeleteAction->setTreeSlug('functionality-headquarter-action-delete');
        $nomHeadquarterDeleteAction->setNomType($objNomAction);
        $nomHeadquarterDeleteAction->setParent($nomHeadquartersFunctionality);
        $nomHeadquarterDeleteAction->setPriority(4);
        $manager->persist($nomHeadquarterDeleteAction);

        $nomHeadquarterChangeStatusAction = new Nomenclature();
        $nomHeadquarterChangeStatusAction->setName('Cambiar Status');
        $nomHeadquarterChangeStatusAction->setUrlSlug('sedes-accion-cambiar-status');
        $nomHeadquarterChangeStatusAction->setTreeSlug('functionality-headquarter-action-change-status');
        $nomHeadquarterChangeStatusAction->setNomType($objNomAction);
        $nomHeadquarterChangeStatusAction->setParent($nomHeadquartersFunctionality);
        $nomHeadquarterChangeStatusAction->setPriority(5);
        $manager->persist($nomHeadquarterChangeStatusAction);

        /* Shows*/
        $nomShowsFunctionality = new Nomenclature();
        $nomShowsFunctionality->setName('Función');
        $nomShowsFunctionality->setUrlSlug(' funcion');
        $nomShowsFunctionality->setTreeSlug('functionality-show');
        $nomShowsFunctionality->setNomType($objNomTypeFunctionality);
        $nomShowsFunctionality->setPriority(19);
        $manager->persist($nomShowsFunctionality);
        $manager->flush();
        $showFunctionality = new NomFunctionality();
        $showFunctionality->setId($nomShowsFunctionality);
        $showFunctionality->setIconClass('icon-support');
        $showFunctionality->setUrlIndexAction('shows_index');
        $showFunctionality->setKeywordSelectedClass($nomShowsFunctionality->getUrlSlug());
        $showFunctionality->setIsUsedFrequently(false);
        $manager->persist($showFunctionality);

        //Actions for Shows
        $nomShowReadAction = new Nomenclature();
        $nomShowReadAction->setName('Leer');
        $nomShowReadAction->setUrlSlug(' funciones-accion-leer');
        $nomShowReadAction->setTreeSlug('functionality-show-action-read');
        $nomShowReadAction->setNomType($objNomAction);
        $nomShowReadAction->setParent($nomShowsFunctionality);
        $nomShowReadAction->setPriority(1);
        $manager->persist($nomShowReadAction);

        $nomShowCreateAction = new Nomenclature();
        $nomShowCreateAction->setName('Crear');
        $nomShowCreateAction->setUrlSlug(' funciones-accion-crear');
        $nomShowCreateAction->setTreeSlug('functionality-show-action-create');
        $nomShowCreateAction->setNomType($objNomAction);
        $nomShowCreateAction->setParent($nomShowsFunctionality);
        $nomShowCreateAction->setPriority(2);
        $manager->persist($nomShowCreateAction);

        $nomShowEditAction = new Nomenclature();
        $nomShowEditAction->setName('Editar');
        $nomShowEditAction->setUrlSlug(' funciones-accion-editar');
        $nomShowEditAction->setTreeSlug('functionality-show-action-edit');
        $nomShowEditAction->setNomType($objNomAction);
        $nomShowEditAction->setParent($nomShowsFunctionality);
        $nomShowEditAction->setPriority(3);
        $manager->persist($nomShowEditAction);

        $nomShowDeleteAction = new Nomenclature();
        $nomShowDeleteAction->setName('Eliminar');
        $nomShowDeleteAction->setUrlSlug(' funciones-accion-eliminar');
        $nomShowDeleteAction->setTreeSlug('functionality-show-action-delete');
        $nomShowDeleteAction->setNomType($objNomAction);
        $nomShowDeleteAction->setParent($nomShowsFunctionality);
        $nomShowDeleteAction->setPriority(4);
        $manager->persist($nomShowDeleteAction);

        $nomShowChangeStatusAction = new Nomenclature();
        $nomShowChangeStatusAction->setName('Cambiar Status');
        $nomShowChangeStatusAction->setUrlSlug(' funciones-accion-cambiar-status');
        $nomShowChangeStatusAction->setTreeSlug('functionality-show-action-change-status');
        $nomShowChangeStatusAction->setNomType($objNomAction);
        $nomShowChangeStatusAction->setParent($nomShowsFunctionality);
        $nomShowChangeStatusAction->setPriority(5);
        $manager->persist($nomShowChangeStatusAction);

        $nomShowChangeStatusAction = new Nomenclature();
        $nomShowChangeStatusAction->setName('Leer disponibilidad');
        $nomShowChangeStatusAction->setUrlSlug(' funciones-accion-leer-disponibilidad');
        $nomShowChangeStatusAction->setTreeSlug('functionality-show-action-read-availability');
        $nomShowChangeStatusAction->setNomType($objNomAction);
        $nomShowChangeStatusAction->setParent($nomShowsFunctionality);
        $nomShowChangeStatusAction->setPriority(6);
        $manager->persist($nomShowChangeStatusAction);

        $nomShowChangeStatusAction = new Nomenclature();
        $nomShowChangeStatusAction->setName('Modificar disponibilidad');
        $nomShowChangeStatusAction->setUrlSlug(' funciones-accion-modificar-disponibilidad');
        $nomShowChangeStatusAction->setTreeSlug('functionality-show-action-edit-availability');
        $nomShowChangeStatusAction->setNomType($objNomAction);
        $nomShowChangeStatusAction->setParent($nomShowsFunctionality);
        $nomShowChangeStatusAction->setPriority(7);
        $manager->persist($nomShowChangeStatusAction);



        /* Booking*/
        $nomBookingFunctionality = new Nomenclature();
        $nomBookingFunctionality->setName('Reservar');
        $nomBookingFunctionality->setUrlSlug(' reservar');
        $nomBookingFunctionality->setTreeSlug('functionality-booking');
        $nomBookingFunctionality->setNomType($objNomTypeFunctionality);
        $nomBookingFunctionality->setPriority(20);
        $manager->persist($nomBookingFunctionality);
        $manager->flush();
        $bookingFunctionality = new NomFunctionality();
        $bookingFunctionality->setId($nomBookingFunctionality);
        $bookingFunctionality->setIconClass('icon-briefcase');
        $bookingFunctionality->setUrlIndexAction('booking_index');
        $bookingFunctionality->setKeywordSelectedClass($nomBookingFunctionality->getUrlSlug());
        $bookingFunctionality->setIsUsedFrequently(false);
        $manager->persist($bookingFunctionality);

        $nomBookingReadAction = new Nomenclature();
        $nomBookingReadAction->setName('Leer reservas');
        $nomBookingReadAction->setUrlSlug(' reservas-accion-leer-reservas');
        $nomBookingReadAction->setTreeSlug('functionality-booking-action-read-booking');
        $nomBookingReadAction->setNomType($objNomAction);
        $nomBookingReadAction->setParent($nomBookingFunctionality);
        $nomBookingReadAction->setPriority(1);
        $manager->persist($nomBookingReadAction);

        $nomBookingAction = new Nomenclature();
        $nomBookingAction->setName('Reservar');
        $nomBookingAction->setUrlSlug(' reservas-accion-reservar');
        $nomBookingAction->setTreeSlug('functionality-booking-action-booking');
        $nomBookingAction->setNomType($objNomAction);
        $nomBookingAction->setParent($nomBookingFunctionality);
        $nomBookingAction->setPriority(2);
        $manager->persist($nomBookingAction);
      }

      /* CheckBooking*/
      $nomCheckBookingFunctionality = new Nomenclature();
      $nomCheckBookingFunctionality->setName('Lista de reservas');
      $nomCheckBookingFunctionality->setUrlSlug(' lista_reservas');
      $nomCheckBookingFunctionality->setTreeSlug('functionality-check-booking');
      $nomCheckBookingFunctionality->setNomType($objNomTypeFunctionality);
      $nomCheckBookingFunctionality->setPriority(21);
      $manager->persist($nomCheckBookingFunctionality);
      $manager->flush();
      $bookingFunctionality = new NomFunctionality();
      $bookingFunctionality->setId($nomCheckBookingFunctionality);
      $bookingFunctionality->setIconClass('icon-book-open');
      $bookingFunctionality->setUrlIndexAction('check_booking_index');
      $bookingFunctionality->setKeywordSelectedClass($nomCheckBookingFunctionality->getUrlSlug());
      $bookingFunctionality->setIsUsedFrequently(false);
      $manager->persist($bookingFunctionality);

      $nomCheckBookingReadAction = new Nomenclature();
      $nomCheckBookingReadAction->setName('Leer reservas');
      $nomCheckBookingReadAction->setUrlSlug(' lista-accion-leer-reservas');
      $nomCheckBookingReadAction->setTreeSlug('functionality-check-booking-action-read-booking');
      $nomCheckBookingReadAction->setNomType($objNomAction);
      $nomCheckBookingReadAction->setParent($nomCheckBookingFunctionality);
      $nomCheckBookingReadAction->setPriority(1);
      $manager->persist($nomCheckBookingReadAction);

    }

    /*Nomenclature for Rows Orientation*/
    $nomTypeRowOrientation = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'row-orientation'
    ));

    if ($nomTypeRowOrientation) {
      $nomRowOrientation = new Nomenclature();
      $nomRowOrientation->setName('Ascendente');
      $nomRowOrientation->setUrlSlug('orientacion-ascendente');
      $nomRowOrientation->setTreeSlug('row-orientation-down-to-up');
      $nomRowOrientation->setNomType($nomTypeRowOrientation);
      $nomRowOrientation->setPriority(1);
      $manager->persist($nomRowOrientation);

      $nomRowOrientation = new Nomenclature();
      $nomRowOrientation->setName('Descendente');
      $nomRowOrientation->setUrlSlug('orientacion-descendente');
      $nomRowOrientation->setTreeSlug('row-orientation-up-to-down');
      $nomRowOrientation->setNomType($nomTypeRowOrientation);
      $nomRowOrientation->setPriority(2);
      $manager->persist($nomRowOrientation);
    }

    /*Nomenclature for Rows and Seat orientation*/
    $nomTypeRowSeatOrientation = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'row-seat-orientation'
    ));

    if ($nomTypeRowSeatOrientation) {
      $nomRowSeatOrientation = new Nomenclature();
      $nomRowSeatOrientation->setName('De izquierda a derecha');
      $nomRowSeatOrientation->setUrlSlug('orientacion-izq-der');
      $nomRowSeatOrientation->setTreeSlug('row-orientation-left-to-right');
      $nomRowSeatOrientation->setNomType($nomTypeRowSeatOrientation);
      $nomRowSeatOrientation->setPriority(1);
      $manager->persist($nomRowSeatOrientation);

      $nomRowSeatOrientation = new Nomenclature();
      $nomRowSeatOrientation->setName('De derecha a izquierda');
      $nomRowSeatOrientation->setUrlSlug('orientacion-der-izq');
      $nomRowSeatOrientation->setTreeSlug('row-orientation-right-to-left');
      $nomRowSeatOrientation->setNomType($nomTypeRowSeatOrientation);
      $nomRowSeatOrientation->setPriority(2);
      $manager->persist($nomRowSeatOrientation);
    }

    /*Nomenclature for Seat counting*/
    $nomTypeSeatCounting = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'seat-counting'
    ));

    if ($nomTypeSeatCounting) {
      $nomSeatCounting = new Nomenclature();
      $nomSeatCounting->setName('Par');
      $nomSeatCounting->setUrlSlug('par');
      $nomSeatCounting->setTreeSlug('pair');
      $nomSeatCounting->setNomType($nomTypeSeatCounting);
      $nomSeatCounting->setPriority(1);
      $manager->persist($nomSeatCounting);

      $nomSeatCounting = new Nomenclature();
      $nomSeatCounting->setName('Impar');
      $nomSeatCounting->setUrlSlug('impar');
      $nomSeatCounting->setTreeSlug('odd');
      $nomSeatCounting->setNomType($nomTypeSeatCounting);
      $nomSeatCounting->setPriority(2);
      $manager->persist($nomSeatCounting);

      $nomSeatCounting = new Nomenclature();
      $nomSeatCounting->setName('Normal');
      $nomSeatCounting->setUrlSlug('normal');
      $nomSeatCounting->setTreeSlug('normal');
      $nomSeatCounting->setNomType($nomTypeSeatCounting);
      $nomSeatCounting->setPriority(3);
      $manager->persist($nomSeatCounting);
    }

    /*Nomenclature for Rows and Seat nomenclature*/
    $nomTypeRowSeatNomenclature = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'row-seat-nomenclature'
    ));

    if ($nomTypeRowSeatNomenclature) {
      $nomRowSeatOrientation = new Nomenclature();
      $nomRowSeatOrientation->setName('Letras');
      $nomRowSeatOrientation->setUrlSlug('letters');
      $nomRowSeatOrientation->setTreeSlug('letters');
      $nomRowSeatOrientation->setNomType($nomTypeRowSeatNomenclature);
      $nomRowSeatOrientation->setPriority(1);
      $manager->persist($nomRowSeatOrientation);

      $nomRowSeatOrientation = new Nomenclature();
      $nomRowSeatOrientation->setName('Números');
      $nomRowSeatOrientation->setUrlSlug('numbers');
      $nomRowSeatOrientation->setTreeSlug('numbers');
      $nomRowSeatOrientation->setNomType($nomTypeRowSeatNomenclature);
      $nomRowSeatOrientation->setPriority(2);
      $manager->persist($nomRowSeatOrientation);
    }

    /*Nomenclature for Seat status*/
    $nomTypeSeatFlowStatus = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'seat-flow-status'
    ));

    if ($nomTypeSeatFlowStatus) {
      $nomSeatFlowStatus = new Nomenclature();
      $nomSeatFlowStatus->setName('Disponible');
      $nomSeatFlowStatus->setUrlSlug('disponible');
      $nomSeatFlowStatus->setTreeSlug('avaiable');
      $nomSeatFlowStatus->setNomType($nomTypeSeatFlowStatus);
      $nomSeatFlowStatus->setPriority(1);
      $manager->persist($nomSeatFlowStatus);

      $nomSeatFlowStatus = new Nomenclature();
      $nomSeatFlowStatus->setName('No disponible');
      $nomSeatFlowStatus->setUrlSlug('no-disponible');
      $nomSeatFlowStatus->setTreeSlug('unavailable');
      $nomSeatFlowStatus->setNomType($nomTypeSeatFlowStatus);
      $nomSeatFlowStatus->setPriority(2);
      $manager->persist($nomSeatFlowStatus);

      $nomSeatFlowStatus = new Nomenclature();
      $nomSeatFlowStatus->setName('En proceso');
      $nomSeatFlowStatus->setUrlSlug('en-proceso');
      $nomSeatFlowStatus->setTreeSlug('in-process');
      $nomSeatFlowStatus->setNomType($nomTypeSeatFlowStatus);
      $nomSeatFlowStatus->setPriority(3);
      $manager->persist($nomSeatFlowStatus);

      $nomSeatFlowStatus = new Nomenclature();
      $nomSeatFlowStatus->setName('Vendido');
      $nomSeatFlowStatus->setUrlSlug('vendido');
      $nomSeatFlowStatus->setTreeSlug('selled');
      $nomSeatFlowStatus->setNomType($nomTypeSeatFlowStatus);
      $nomSeatFlowStatus->setPriority(4);
      $manager->persist($nomSeatFlowStatus);
    }

    /*Nomenclature for Seat status*/
    $nomTypeBookingStatus = $manager->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'booking-status'
    ));

    if ($nomTypeBookingStatus) {
      $nomBookingStatus = new Nomenclature();
      $nomBookingStatus->setName('Terminada');
      $nomBookingStatus->setUrlSlug('terminada');
      $nomBookingStatus->setTreeSlug('finished');
      $nomBookingStatus->setNomType($nomTypeBookingStatus);
      $nomBookingStatus->setPriority(1);
      $manager->persist($nomBookingStatus);

      $nomBookingStatus = new Nomenclature();
      $nomBookingStatus->setName('En proceso');
      $nomBookingStatus->setUrlSlug('reserva-en-proceso');
      $nomBookingStatus->setTreeSlug('booking-in-process');
      $nomBookingStatus->setNomType($nomTypeBookingStatus);
      $nomBookingStatus->setPriority(2);
      $manager->persist($nomBookingStatus);

      $nomBookingStatus = new Nomenclature();
      $nomBookingStatus->setName('Cancelada');
      $nomBookingStatus->setUrlSlug('cancelada');
      $nomBookingStatus->setTreeSlug('cancelled');
      $nomBookingStatus->setNomType($nomTypeBookingStatus);
      $nomBookingStatus->setPriority(3);
      $manager->persist($nomBookingStatus);
    }


    $countries = array(
      'AF' => 'Afghanistan',
      'AL' => 'Albania',
      'DZ' => 'Algeria',
      'DS' => 'American Samoa',
      'AD' => 'Andorra',
      'AO' => 'Angola',
      'AI' => 'Anguilla',
      'AQ' => 'Antarctica',
      'AG' => 'Antigua and Barbuda',
      'AR' => 'Argentina',
      'AM' => 'Armenia',
      'AW' => 'Aruba',
      'AU' => 'Australia',
      'AT' => 'Austria',
      'AZ' => 'Azerbaijan',
      'BS' => 'Bahamas',
      'BH' => 'Bahrain',
      'BD' => 'Bangladesh',
      'BB' => 'Barbados',
      'BY' => 'Belarus',
      'BE' => 'Belgium',
      'BZ' => 'Belize',
      'BJ' => 'Benin',
      'BM' => 'Bermuda',
      'BT' => 'Bhutan',
      'BO' => 'Bolivia',
      'BA' => 'Bosnia and Herzegovina',
      'BW' => 'Botswana',
      'BV' => 'Bouvet Island',
      'BR' => 'Brazil',
      'IO' => 'British Indian Ocean Territory',
      'BN' => 'Brunei Darussalam',
      'BG' => 'Bulgaria',
      'BF' => 'Burkina Faso',
      'BI' => 'Burundi',
      'KH' => 'Cambodia',
      'CM' => 'Cameroon',
      'CA' => 'Canada',
      'CV' => 'Cape Verde',
      'KY' => 'Cayman Islands',
      'CF' => 'Central African Republic',
      'TD' => 'Chad',
      'CL' => 'Chile',
      'CN' => 'China',
      'CX' => 'Christmas Island',
      'CC' => 'Cocos (Keeling) Islands',
      'CO' => 'Colombia',
      'KM' => 'Comoros',
      'CG' => 'Congo',
      'CK' => 'Cook Islands',
      'CR' => 'Costa Rica',
      'HR' => 'Croatia (Hrvatska)',
      'CU' => 'Cuba',
      'CY' => 'Cyprus',
      'CZ' => 'Czech Republic',
      'DK' => 'Denmark',
      'DJ' => 'Djibouti',
      'DM' => 'Dominica',
      'DO' => 'Dominican Republic',
      'TP' => 'East Timor',
      'EC' => 'Ecuador',
      'EG' => 'Egypt',
      'SV' => 'El Salvador',
      'GQ' => 'Equatorial Guinea',
      'ER' => 'Eritrea',
      'EE' => 'Estonia',
      'ET' => 'Ethiopia',
      'FK' => 'Falkland Islands (Malvinas)',
      'FO' => 'Faroe Islands',
      'FJ' => 'Fiji',
      'FI' => 'Finland',
      'FR' => 'France',
      'FX' => 'France, Metropolitan',
      'GF' => 'French Guiana',
      'PF' => 'French Polynesia',
      'TF' => 'French Southern Territories',
      'GA' => 'Gabon',
      'GM' => 'Gambia',
      'GE' => 'Georgia',
      'DE' => 'Germany',
      'GH' => 'Ghana',
      'GI' => 'Gibraltar',
      'GK' => 'Guernsey',
      'GR' => 'Greece',
      'GL' => 'Greenland',
      'GD' => 'Grenada',
      'GP' => 'Guadeloupe',
      'GU' => 'Guam',
      'GT' => 'Guatemala',
      'GN' => 'Guinea',
      'GW' => 'Guinea-Bissau',
      'GY' => 'Guyana',
      'HT' => 'Haiti',
      'HM' => 'Heard and Mc Donald Islands',
      'HN' => 'Honduras',
      'HK' => 'Hong Kong',
      'HU' => 'Hungary',
      'IS' => 'Iceland',
      'IN' => 'India',
      'IM' => 'Isle of Man',
      'ID' => 'Indonesia',
      'IR' => 'Iran (Islamic Republic of)',
      'IQ' => 'Iraq',
      'IE' => 'Ireland',
      'IL' => 'Israel',
      'IT' => 'Italy',
      'CI' => 'Ivory Coast',
      'JE' => 'Jersey',
      'JM' => 'Jamaica',
      'JP' => 'Japan',
      'JO' => 'Jordan',
      'KZ' => 'Kazakhstan',
      'KE' => 'Kenya',
      'KI' => 'Kiribati',
      'KP' => 'Korea, Democratic People\'s Republic of',
      'KR' => 'Korea, Republic of',
      'XK' => 'Kosovo',
      'KW' => 'Kuwait',
      'KG' => 'Kyrgyzstan',
      'LA' => 'Lao People\'s Democratic Republic',
      'LV' => 'Latvia',
      'LB' => 'Lebanon',
      'LS' => 'Lesotho',
      'LR' => 'Liberia',
      'LY' => 'Libyan Arab Jamahiriya',
      'LI' => 'Liechtenstein',
      'LT' => 'Lithuania',
      'LU' => 'Luxembourg',
      'MO' => 'Macau',
      'MK' => 'Macedonia',
      'MG' => 'Madagascar',
      'MW' => 'Malawi',
      'MY' => 'Malaysia',
      'MV' => 'Maldives',
      'ML' => 'Mali',
      'MT' => 'Malta',
      'MH' => 'Marshall Islands',
      'MQ' => 'Martinique',
      'MR' => 'Mauritania',
      'MU' => 'Mauritius',
      'TY' => 'Mayotte',
      'MX' => 'Mexico',
      'FM' => 'Micronesia, Federated States of',
      'MD' => 'Moldova, Republic of',
      'MC' => 'Monaco',
      'MN' => 'Mongolia',
      'ME' => 'Montenegro',
      'MS' => 'Montserrat',
      'MA' => 'Morocco',
      'MZ' => 'Mozambique',
      'MM' => 'Myanmar',
      'NA' => 'Namibia',
      'NR' => 'Nauru',
      'NP' => 'Nepal',
      'NL' => 'Netherlands',
      'AN' => 'Netherlands Antilles',
      'NC' => 'New Caledonia',
      'NZ' => 'New Zealand',
      'NI' => 'Nicaragua',
      'NE' => 'Niger',
      'NG' => 'Nigeria',
      'NU' => 'Niue',
      'NF' => 'Norfolk Island',
      'MP' => 'Northern Mariana Islands',
      'NO' => 'Norway',
      'OM' => 'Oman',
      'PK' => 'Pakistan',
      'PW' => 'Palau',
      'PS' => 'Palestine',
      'PA' => 'Panama',
      'PG' => 'Papua New Guinea',
      'PY' => 'Paraguay',
      'PE' => 'Peru',
      'PH' => 'Philippines',
      'PN' => 'Pitcairn',
      'PL' => 'Poland',
      'PT' => 'Portugal',
      'PR' => 'Puerto Rico',
      'QA' => 'Qatar',
      'RE' => 'Reunion',
      'RO' => 'Romania',
      'RU' => 'Russian Federation',
      'RW' => 'Rwanda',
      'KN' => 'Saint Kitts and Nevis',
      'LC' => 'Saint Lucia',
      'VC' => 'Saint Vincent and the Grenadines',
      'WS' => 'Samoa',
      'SM' => 'San Marino',
      'ST' => 'Sao Tome and Principe',
      'SA' => 'Saudi Arabia',
      'SN' => 'Senegal',
      'RS' => 'Serbia',
      'SC' => 'Seychelles',
      'SL' => 'Sierra Leone',
      'SG' => 'Singapore',
      'SK' => 'Slovakia',
      'SI' => 'Slovenia',
      'SB' => 'Solomon Islands',
      'SO' => 'Somalia',
      'ZA' => 'South Africa',
      'SS' => 'South Sudan',
      'GS' => 'South Georgia South Sandwich Islands',
      'ES' => 'Spain',
      'LK' => 'Sri Lanka',
      'SH' => 'St. Helena',
      'PM' => 'St. Pierre and Miquelon',
      'SD' => 'Sudan',
      'SR' => 'Suriname',
      'SJ' => 'Svalbard and Jan Mayen Islands',
      'SZ' => 'Swaziland',
      'SE' => 'Sweden',
      'CH' => 'Switzerland',
      'SY' => 'Syrian Arab Republic',
      'TW' => 'Taiwan',
      'TJ' => 'Tajikistan',
      'TZ' => 'Tanzania, United Republic of',
      'TH' => 'Thailand',
      'TG' => 'Togo',
      'TK' => 'Tokelau',
      'TO' => 'Tonga',
      'TT' => 'Trinidad and Tobago',
      'TN' => 'Tunisia',
      'TR' => 'Turkey',
      'TM' => 'Turkmenistan',
      'TC' => 'Turks and Caicos Islands',
      'TV' => 'Tuvalu',
      'UG' => 'Uganda',
      'UA' => 'Ukraine',
      'AE' => 'United Arab Emirates',
      'GB' => 'United Kingdom',
      'US' => 'United States',
      'UM' => 'United States minor outlying islands',
      'UY' => 'Uruguay',
      'UZ' => 'Uzbekistan',
      'VU' => 'Vanuatu',
      'VA' => 'Vatican City State',
      'VE' => 'Venezuela',
      'VN' => 'Vietnam',
      'VG' => 'Virgin Islands (British)',
      'VI' => 'Virgin Islands (U.S.)',
      'WF' => 'Wallis and Futuna Islands',
      'EH' => 'Western Sahara',
      'YE' => 'Yemen',
      'ZR' => 'Zaire',
      'ZM' => 'Zambia',
      'ZW' => 'Zimbabwe'
    );

    foreach ($countries as $key => $country) {
      $countryObj = new Country();
      $countryObj->setCountryCode($key);
      $countryObj->setCountryName($country);
      $manager->persist($countryObj);
    }

    $manager->flush();
  }

  public function getOrder()
  {
    return 2;
  }
}
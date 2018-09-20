<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\GenericPostType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadGenericPostTypes extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $postGenericPostType = new GenericPostType();
        $postGenericPostType->setName('Noticia');
        $postGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/noticias').
        $postGenericPostType->setUrlSlug('noticias');
        $postGenericPostType->setTreeSlug('post');
        $postGenericPostType->setSearchableType(true);
        $postGenericPostType->setSectionAvailable(true);
        $manager->persist($postGenericPostType);

        $pageGenericPostType = new GenericPostType();
        $pageGenericPostType->setName('Página');
        $pageGenericPostType->setDescription('Tipo de entrada que identifica a las páginas').
        $pageGenericPostType->setUrlSlug('paginas');
        $pageGenericPostType->setTreeSlug('page');
        $pageGenericPostType->setSectionAvailable(true);
        $pageGenericPostType->setSearchableType(true);
        $manager->persist($pageGenericPostType);

        $publicationGenericPostType = new GenericPostType();
        $publicationGenericPostType->setName('Publicación');
        $publicationGenericPostType->setDescription('Tipo de entrada que identifica a las publicaciones').
        $publicationGenericPostType->setUrlSlug('publicaciones');
        $publicationGenericPostType->setTreeSlug('publication');
        $publicationGenericPostType->setSectionAvailable(true);
        $publicationGenericPostType->setSearchableType(true);
        $manager->persist($publicationGenericPostType);

        $opinionGenericPostType = new GenericPostType();
        $opinionGenericPostType->setName('Opinión');
        $opinionGenericPostType->setDescription('Tipo de entrada que identifica a las opiniones/críticas').
        $opinionGenericPostType->setUrlSlug('opiniones');
        $opinionGenericPostType->setTreeSlug('opinion');
        $manager->persist($opinionGenericPostType);

        $eventGenericPostType = new GenericPostType();
        $eventGenericPostType->setName('Evento');
        $eventGenericPostType->setDescription('Tipo de entrada que identifica a los eventos').
        $eventGenericPostType->setUrlSlug('eventos');
        $eventGenericPostType->setTreeSlug('event');
        $eventGenericPostType->setSectionAvailable(true);
        $eventGenericPostType->setSearchableType(true);
        $manager->persist($eventGenericPostType);

        $repertoryGenericPostType = new GenericPostType();
        $repertoryGenericPostType->setName('Repertorio');
        $repertoryGenericPostType->setDescription('Tipo de entrada que identifica a cada pieza del repertorio').
        $repertoryGenericPostType->setUrlSlug('repertorio');
        $repertoryGenericPostType->setTreeSlug('repertory');
        $repertoryGenericPostType->setSectionAvailable(true);
        $repertoryGenericPostType->setSearchableType(true);
        $manager->persist($repertoryGenericPostType);

        $compositionGenericPostType = new GenericPostType();
        $compositionGenericPostType->setName('Composición');
        $compositionGenericPostType->setDescription('Tipo de entrada que identifica a cada persona que compone el BNC.').
        $compositionGenericPostType->setUrlSlug('composicion');
        $compositionGenericPostType->setTreeSlug('composition');
        $compositionGenericPostType->setSectionAvailable(true);
        $compositionGenericPostType->setSearchableType(true);
        $manager->persist($compositionGenericPostType);

        $awardGenericPostType = new GenericPostType();
        $awardGenericPostType->setName('Distinción');
        $awardGenericPostType->setDescription('Tipo de entrada que identifica las distinciones/premios.').
        $awardGenericPostType->setUrlSlug('distinciones');
        $awardGenericPostType->setTreeSlug('award');
        $manager->persist($awardGenericPostType);

        $historicalMomentGenericPostType = new GenericPostType();
        $historicalMomentGenericPostType->setName('Hito Histórico');
        $historicalMomentGenericPostType->setDescription('Tipo de entrada que identifica los hitos históricos.').
        $historicalMomentGenericPostType->setUrlSlug('hitos-historicos');
        $historicalMomentGenericPostType->setTreeSlug('historical-moment');
        $manager->persist($historicalMomentGenericPostType);

        $partnerGenericPostType = new GenericPostType();
        $partnerGenericPostType->setName('Asociado');
        $partnerGenericPostType->setDescription('Tipo de entrada que identifica a los asociados.').
        $partnerGenericPostType->setUrlSlug('asociados');
        $partnerGenericPostType->setTreeSlug('partner');
        $partnerGenericPostType->setSectionAvailable(true);
        $partnerGenericPostType->setSearchableType(true);
        $manager->persist($partnerGenericPostType);

        $jewelGenericPostType = new GenericPostType();
        $jewelGenericPostType->setName('Joya');
        $jewelGenericPostType->setDescription('Tipo de entrada que identifica a las Joyas del BNC.').
        $jewelGenericPostType->setUrlSlug('joyas');
        $jewelGenericPostType->setTreeSlug('jewel');
        $manager->persist($jewelGenericPostType);



        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
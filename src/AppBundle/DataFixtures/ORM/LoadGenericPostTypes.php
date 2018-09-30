<?php /** @noinspection ALL */

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
        $postGenericPostType->setName('Notice','en');
        $postGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/noticias','en').
        $postGenericPostType->setUrlSlug('noticias','en');
        $postGenericPostType->setTreeSlug('post');
        $postGenericPostType->setSearchableType(true);
        $postGenericPostType->setSectionAvailable(true);
        $manager->persist($postGenericPostType);

        $pageGenericPostType = new GenericPostType();
        $pageGenericPostType->setName('Página');
        $pageGenericPostType->setDescription('Tipo de entrada que identifica a las páginas').
        $pageGenericPostType->setUrlSlug('paginas');
        $pageGenericPostType->setName('Página','en');
        $pageGenericPostType->setDescription('Tipo de entrada que identifica a las páginas','en').
        $pageGenericPostType->setUrlSlug('paginas','en');
        $pageGenericPostType->setTreeSlug('page');
        $pageGenericPostType->setSectionAvailable(true);
        $pageGenericPostType->setSearchableType(true);
        $manager->persist($pageGenericPostType);

        $publicationGenericPostType = new GenericPostType();
        $publicationGenericPostType->setName('Publicación');
        $publicationGenericPostType->setDescription('Tipo de entrada que identifica a las publicaciones').
        $publicationGenericPostType->setUrlSlug('publicaciones');
        $publicationGenericPostType->setName('Publicación','en');
        $publicationGenericPostType->setDescription('Tipo de entrada que identifica a las publicaciones','en').
        $publicationGenericPostType->setUrlSlug('publicaciones','en');
        $publicationGenericPostType->setTreeSlug('publication');
        $publicationGenericPostType->setSectionAvailable(true);
        $publicationGenericPostType->setSearchableType(true);
        $manager->persist($publicationGenericPostType);

        $opinionGenericPostType = new GenericPostType();
        $opinionGenericPostType->setName('Opinión');
        $opinionGenericPostType->setDescription('Tipo de entrada que identifica a las opiniones/críticas').
        $opinionGenericPostType->setUrlSlug('opiniones');
        $opinionGenericPostType->setName('Opinión','en');
        $opinionGenericPostType->setDescription('Tipo de entrada que identifica a las opiniones/críticas','en').
        $opinionGenericPostType->setUrlSlug('opiniones','en');
        $opinionGenericPostType->setTreeSlug('opinion');
        $manager->persist($opinionGenericPostType);

        $eventGenericPostType = new GenericPostType();
        $eventGenericPostType->setName('Evento');
        $eventGenericPostType->setDescription('Tipo de entrada que identifica a los eventos').
        $eventGenericPostType->setUrlSlug('eventos');
        $eventGenericPostType->setName('Evento','en');
        $eventGenericPostType->setDescription('Tipo de entrada que identifica a los eventos','en').
        $eventGenericPostType->setUrlSlug('eventos','en');
        $eventGenericPostType->setTreeSlug('event');
        $eventGenericPostType->setSectionAvailable(true);
        $eventGenericPostType->setSearchableType(true);
        $manager->persist($eventGenericPostType);

        $repertoryGenericPostType = new GenericPostType();
        $repertoryGenericPostType->setName('Repertorio');
        $repertoryGenericPostType->setDescription('Tipo de entrada que identifica a cada pieza del repertorio').
        $repertoryGenericPostType->setUrlSlug('repertorio');
        $repertoryGenericPostType->setName('Repertorio','en');
        $repertoryGenericPostType->setDescription('Tipo de entrada que identifica a cada pieza del repertorio','en').
        $repertoryGenericPostType->setUrlSlug('repertorio','en');
        $repertoryGenericPostType->setTreeSlug('repertory');
        $repertoryGenericPostType->setSectionAvailable(true);
        $repertoryGenericPostType->setSearchableType(true);
        $manager->persist($repertoryGenericPostType);

        $compositionGenericPostType = new GenericPostType();
        $compositionGenericPostType->setName('Composición');
        $compositionGenericPostType->setDescription('Tipo de entrada que identifica a cada persona que compone el BNC.').
        $compositionGenericPostType->setUrlSlug('composicion');
        $compositionGenericPostType->setName('Composición','en');
        $compositionGenericPostType->setDescription('Tipo de entrada que identifica a cada persona que compone el BNC.','en').
        $compositionGenericPostType->setUrlSlug('composicion','en');
        $compositionGenericPostType->setTreeSlug('composition');
        $compositionGenericPostType->setSectionAvailable(true);
        $compositionGenericPostType->setSearchableType(true);
        $manager->persist($compositionGenericPostType);

        $awardGenericPostType = new GenericPostType();
        $awardGenericPostType->setName('Distinción');
        $awardGenericPostType->setDescription('Tipo de entrada que identifica las distinciones/premios.').
        $awardGenericPostType->setUrlSlug('distinciones');
        $awardGenericPostType->setName('Distinción','en');
        $awardGenericPostType->setDescription('Tipo de entrada que identifica las distinciones/premios.','en').
        $awardGenericPostType->setUrlSlug('distinciones','en');
        $awardGenericPostType->setTreeSlug('award');
        $manager->persist($awardGenericPostType);

        $historicalMomentGenericPostType = new GenericPostType();
        $historicalMomentGenericPostType->setName('Hito Histórico');
        $historicalMomentGenericPostType->setDescription('Tipo de entrada que identifica los hitos históricos.').
        $historicalMomentGenericPostType->setUrlSlug('hitos-historicos');
        $historicalMomentGenericPostType->setName('Hito Histórico','en');
        $historicalMomentGenericPostType->setDescription('Tipo de entrada que identifica los hitos históricos.','en').
        $historicalMomentGenericPostType->setUrlSlug('hitos-historicos','en');
        $historicalMomentGenericPostType->setTreeSlug('historical-moment');
        $manager->persist($historicalMomentGenericPostType);

        $partnerGenericPostType = new GenericPostType();
        $partnerGenericPostType->setName('Asociado');
        $partnerGenericPostType->setDescription('Tipo de entrada que identifica a los asociados.').
        $partnerGenericPostType->setUrlSlug('asociados');
        $partnerGenericPostType->setName('Asociado','en');
        $partnerGenericPostType->setDescription('Tipo de entrada que identifica a los asociados.','en').
        $partnerGenericPostType->setUrlSlug('asociados','en');
        $partnerGenericPostType->setTreeSlug('partner');
        $partnerGenericPostType->setSectionAvailable(true);
        $partnerGenericPostType->setSearchableType(true);
        $manager->persist($partnerGenericPostType);

        $jewelGenericPostType = new GenericPostType();
        $jewelGenericPostType->setName('Joya');
        $jewelGenericPostType->setDescription('Tipo de entrada que identifica a las Joyas del BNC.').
        $jewelGenericPostType->setUrlSlug('joyas');
        $jewelGenericPostType->setName('Joya','en');
        $jewelGenericPostType->setDescription('Tipo de entrada que identifica a las Joyas del BNC.','en').
        $jewelGenericPostType->setUrlSlug('joyas','en');
        $jewelGenericPostType->setTreeSlug('jewel');
        $manager->persist($jewelGenericPostType);

      $headquarterGenericPostType = new GenericPostType();
      $headquarterGenericPostType->setName('Sede');
      $headquarterGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/sedes').
      $headquarterGenericPostType->setUrlSlug('sedes');
      $headquarterGenericPostType->setName('Headquarter','en');
      $headquarterGenericPostType->setDescription('Type of entry who identify headquarter/posts','en').
      $headquarterGenericPostType->setUrlSlug('headquarters','en');
      $headquarterGenericPostType->setTreeSlug('headquarter');
      $manager->persist($headquarterGenericPostType);


      $roomGenericPostType = new GenericPostType();
      $roomGenericPostType->setName('Sala');
      $roomGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/salas').
      $roomGenericPostType->setUrlSlug('salas');
      $roomGenericPostType->setName('Room','en');
      $roomGenericPostType->setDescription('Type of entry who identify room/posts','en').
      $roomGenericPostType->setUrlSlug('room','en');
      $roomGenericPostType->setTreeSlug('room');
      $manager->persist($roomGenericPostType);

      $roomAreaGenericPostType = new GenericPostType();
      $roomAreaGenericPostType->setName('Area vendible');
      $roomAreaGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/areas vendibles').
      $roomAreaGenericPostType->setUrlSlug('area-vendible');
      $roomAreaGenericPostType->setName('Salable area','en');
      $roomAreaGenericPostType->setDescription('Type of entry who identify salable area/posts','en').
      $roomAreaGenericPostType->setUrlSlug('salable-area','en');
      $roomAreaGenericPostType->setTreeSlug('salable-area');
      $manager->persist($roomAreaGenericPostType);

      $zoneGenericPostType = new GenericPostType();
      $zoneGenericPostType->setName('Zona');
      $zoneGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/zonas').
      $zoneGenericPostType->setUrlSlug('zona');
      $zoneGenericPostType->setName('Zone','en');
      $zoneGenericPostType->setDescription('Type of entry who identify zone/posts','en').
      $zoneGenericPostType->setUrlSlug('zone','en');
      $zoneGenericPostType->setTreeSlug('zone');
      $manager->persist($zoneGenericPostType);

      $rowGenericPostType = new GenericPostType();
      $rowGenericPostType->setName('Fila');
      $rowGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/filas').
      $rowGenericPostType->setUrlSlug('fila');
      $rowGenericPostType->setName('Row','en');
      $rowGenericPostType->setDescription('Type of entry who identify rows/posts','en').
      $rowGenericPostType->setUrlSlug('row','en');
      $rowGenericPostType->setTreeSlug('row');
      $manager->persist($rowGenericPostType);

      $seatGenericPostType = new GenericPostType();
      $seatGenericPostType->setName('Asiento');
      $seatGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/asientos').
      $seatGenericPostType->setUrlSlug('asiento');
      $seatGenericPostType->setName('Seats','en');
      $seatGenericPostType->setDescription('Type of entry who identify seats/posts','en').
      $seatGenericPostType->setUrlSlug('seat','en');
      $seatGenericPostType->setTreeSlug('seat');
      $manager->persist($seatGenericPostType);



      $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
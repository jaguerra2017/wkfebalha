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

        $guestGenericPostType = new GenericPostType();
        $guestGenericPostType->setName('Invitados');
        $guestGenericPostType->setDescription('Tipo de entrada que identifica a los invitados').
        $guestGenericPostType->setUrlSlug('invitados');
        $guestGenericPostType->setName('Guests','en');
        $guestGenericPostType->setDescription('Tipo de entrada que identifica a los invitados','en').
        $guestGenericPostType->setUrlSlug('guests','en');
        $guestGenericPostType->setTreeSlug('guest');
        $guestGenericPostType->setSectionAvailable(true);
        $guestGenericPostType->setSearchableType(true);
        $manager->persist($guestGenericPostType);



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

      $seatGenericPostType = new GenericPostType();
      $seatGenericPostType->setName('Funciones');
      $seatGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/función').
      $seatGenericPostType->setUrlSlug('espectaculo');
      $seatGenericPostType->setName('Show','en');
      $seatGenericPostType->setDescription('Type of entry who identify show/posts','en').
      $seatGenericPostType->setUrlSlug('show','en');
      $seatGenericPostType->setTreeSlug('show');
      $manager->persist($seatGenericPostType);

      $seatGenericPostType = new GenericPostType();
      $seatGenericPostType->setName('Reserva');
      $seatGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/reserva').
      $seatGenericPostType->setUrlSlug('reserva');
      $seatGenericPostType->setName('Booking','en');
      $seatGenericPostType->setDescription('Type of entry who identify booking/posts','en').
      $seatGenericPostType->setUrlSlug('booking','en');
      $seatGenericPostType->setTreeSlug('booking');
      $manager->persist($seatGenericPostType);

      $collateralActivityGenericPostType = new GenericPostType();
      $collateralActivityGenericPostType->setName('Colateral');
      $collateralActivityGenericPostType->setDescription('Tipo de entrada que identifica a las entradas/actividad colateral').
      $collateralActivityGenericPostType->setUrlSlug('colateral');
      $collateralActivityGenericPostType->setName('Collateral Activity','en');
      $collateralActivityGenericPostType->setDescription('Type of entry who identify collateral activity/posts','en').
      $collateralActivityGenericPostType->setUrlSlug('collateral','en');
      $collateralActivityGenericPostType->setTreeSlug('collateral');
      $manager->persist($collateralActivityGenericPostType);

    $partnerActivityGenericPostType = new GenericPostType();
    $partnerActivityGenericPostType->setName('Asociado');
    $partnerActivityGenericPostType->setDescription('Tipo de entrada que identifica a los asociados').
    $partnerActivityGenericPostType->setUrlSlug('asociado');
    $partnerActivityGenericPostType->setName('Partner','en');
    $partnerActivityGenericPostType->setDescription('Type of entry who identify partners','en').
    $partnerActivityGenericPostType->setUrlSlug('partner','en');
    $partnerActivityGenericPostType->setTreeSlug('partner');
    $manager->persist($partnerActivityGenericPostType);

      $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
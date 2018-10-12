<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\GenericPost;
use AppBundle\Entity\HeadQuarter;
use AppBundle\Entity\NomType;
use AppBundle\Entity\Room;
use AppBundle\Entity\RoomArea;
use AppBundle\Entity\Show;
use AppBundle\Entity\Zone;
use AppBundle\Entity\ZoneRow;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadHeadQuarterAndData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

  /**
   * @var ContainerInterface
   */
  private $container;

  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }

  public function load(ObjectManager $manager)
  {
    $headquarter = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'headquarter'
    ));

    if ($headquarter) {

      $user = $manager->getRepository('AppBundle:User')->find(2);
      /*Creating National theater*/
      $nationalTheaterGP = new GenericPost();
      $nationalTheaterGP->setTitle('Teatro Nacional');
      $nationalTheaterGP->setTitle('National theater', 'en');
      $nationalTheaterGP->setExcerpt('AAAAA');
      $nationalTheaterGP->setExcerpt('AAAAA', 'en');
      $nationalTheaterGP->setGenericPostType($headquarter);
      $nationalTheaterGP->setUrlSlug('teatro-nacional');
      $nationalTheaterGP->setUrlSlug('national-theater');
      $nationalTheaterGP->setContent('JJJJJJJJJJJJ');
      $nationalTheaterGP->setContent('JJJJJJJJJJJJ', 'en');
      $nationalTheaterGP->setPostStatusSlug('generic-post-status-published');
      $nationalTheaterGP->setCreatedAuthor($user);
      $manager->persist($nationalTheaterGP);

      $manager->flush();

      $nationalTheater = new HeadQuarter();
      $nationalTheater->setId($nationalTheaterGP);
      $nationalTheater->setAddress('Paseo y 39, PLAZA DE LA REVOLUCIÓN Telf.: 8704655');
      $nationalTheater->setAddress('Paseo and 39, Revolution Square. Ph: 8704655', 'en');
      $manager->persist($nationalTheater);

      /*Creating Great Havana theater*/
      $habanaTheaterGP = new GenericPost();
      $habanaTheaterGP->setTitle('Gran Teatro de la Habana');
      $habanaTheaterGP->setTitle('Great Theater of Havana', 'en');
      $habanaTheaterGP->setExcerpt('AAAAA');
      $habanaTheaterGP->setExcerpt('AAAAA', 'en');
      $habanaTheaterGP->setGenericPostType($headquarter);
      $habanaTheaterGP->setUrlSlug('teatro-habana');
      $habanaTheaterGP->setUrlSlug('habana-theater');
      $habanaTheaterGP->setContent('JJJJJJJJJJJJ');
      $habanaTheaterGP->setContent('JJJJJJJJJJJJ', 'en');
      $habanaTheaterGP->setPostStatusSlug('generic-post-status-published');
      $habanaTheaterGP->setCreatedAuthor($user);
      $manager->persist($habanaTheaterGP);

      $manager->flush();

      $habanaTheater = new HeadQuarter();
      $habanaTheater->setId($habanaTheaterGP);
      $habanaTheater->setAddress('');
      $habanaTheater->setAddress('', 'en');
      $manager->persist($habanaTheater);

      /*Creating Martí theater*/
      $martiTheaterGP = new GenericPost();
      $martiTheaterGP->setTitle('Teatro Martí');
      $martiTheaterGP->setTitle('Martí theater', 'en');
      $martiTheaterGP->setExcerpt('AAAAA');
      $martiTheaterGP->setExcerpt('AAAAA', 'en');
      $martiTheaterGP->setGenericPostType($headquarter);
      $martiTheaterGP->setUrlSlug('teatro-nacional');
      $martiTheaterGP->setUrlSlug('marti-theater');
      $martiTheaterGP->setContent('JJJJJJJJJJJJ');
      $martiTheaterGP->setContent('JJJJJJJJJJJJ', 'en');
      $martiTheaterGP->setPostStatusSlug('generic-post-status-published');
      $martiTheaterGP->setCreatedAuthor($user);
      $manager->persist($martiTheaterGP);

      $manager->flush();

      $martiTheater = new HeadQuarter();
      $martiTheater->setId($martiTheaterGP);
      $martiTheater->setAddress('');
      $martiTheater->setAddress('', 'en');
      $manager->persist($martiTheater);



      /*Creating Mella theater*/
      $mellaTheaterGP = new GenericPost();
      $mellaTheaterGP->setTitle('Teatro Mella');
      $mellaTheaterGP->setTitle('Mella theater', 'en');
      $mellaTheaterGP->setExcerpt('AAAAA');
      $mellaTheaterGP->setExcerpt('AAAAA', 'en');
      $mellaTheaterGP->setGenericPostType($headquarter);
      $mellaTheaterGP->setContent('JJJJJJJJJJJJ');
      $mellaTheaterGP->setContent('JJJJJJJJJJJJ', 'en');
      $mellaTheaterGP->setUrlSlug('teatro-mella');
      $mellaTheaterGP->setUrlSlug('mella-theater');
      $mellaTheaterGP->setPostStatusSlug('generic-post-status-published');
      $mellaTheaterGP->setCreatedAuthor($user);
      $manager->persist($mellaTheaterGP);

      $manager->flush();

      $mellaTheater = new HeadQuarter();
      $mellaTheater->setId($mellaTheaterGP);
      $mellaTheater->setAddress('Línea e/ A y B, El Vedado. Telf.: 8338696');
      $mellaTheater->setAddress('Línea between A and B Sts. El Vedado Ph: 8338696', 'en');
      $manager->persist($mellaTheater);

      $room = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
        'tree_slug' => 'room'
      ));

      /*Creating Avellaneda Hall*/
      $avellanedaRoomGP = new GenericPost();
      $avellanedaRoomGP->setTitle('Sala Avellaneda');
      $avellanedaRoomGP->setTitle('Avellaneda Hall', 'en');
      $avellanedaRoomGP->setGenericPostType($room);
      $manager->persist($avellanedaRoomGP);

      $manager->flush();

      $avellanedaMap = $manager->getRepository('AppBundle:Media')->findOneBy(array('name_es'=>'bnc-avellaneda-hall-map'));

      $avellanedaRoom = new Room();
      $avellanedaRoom->setId($avellanedaRoomGP);
      $avellanedaRoom->setHeadquarter($nationalTheaterGP);
      $avellanedaRoom->setMapImage($avellanedaMap);
      $manager->persist($avellanedaRoom);

      $area = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
        'tree_slug' => 'salable-area'
      ));

      /*creating a show*/
      $show = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
        'tree_slug' => 'show'
      ));

      $showGP = new GenericPost();
      $showGP->setTitle('Giselle');
      $showGP->setTitle('Giselle','en');
      $showGP->setGenericPostType($show);
      $showGP->setExcerpt('AAAAA');
      $showGP->setExcerpt('AAAAA', 'en');
      $showGP->setContent('JJJJJJJJJJJJ');
      $showGP->setContent('JJJJJJJJJJJJ', 'en');
      $showGP->setUrlSlug('gisella');
      $showGP->setPostStatusSlug('generic-post-status-published');
      $showGP->setCreatedAuthor($user);
      $manager->persist($showGP);

      $manager->flush();

      $showEl = new Show();
      $showEl->setId($showGP);
      $showEl->setRoom($avellanedaRoomGP);
      $showEl->setSeatPrice(54);
      $showEl->setDuration(1);
      $showEl->setShowDate(new \DateTime('2018-10-29 20:00'));
      $manager->persist($showEl);


      $showGP = new GenericPost();
      $showGP->setTitle('La bella durmiente del bosque');
      $showGP->setTitle('Sleeping beauty','en');
      $showGP->setGenericPostType($show);
      $showGP->setExcerpt('AAAAA');
      $showGP->setExcerpt('AAAAA', 'en');
      $showGP->setContent('JJJJJJJJJJJJ');
      $showGP->setContent('JJJJJJJJJJJJ', 'en');
      $showGP->setUrlSlug('sleeping-beauty');
      $showGP->setPostStatusSlug('generic-post-status-published');
      $showGP->setCreatedAuthor($user);
      $manager->persist($showGP);

      $manager->flush();

      $showEl = new Show();
      $showEl->setId($showGP);
      $showEl->setRoom($avellanedaRoomGP);
      $showEl->setSeatPrice(72);
      $showEl->setDuration(2);
      $showEl->setShowDate(new \DateTime('2018-10-29 21:00'));
      $manager->persist($showEl);
      /*end*/

      $plateaGP = new GenericPost();
      $plateaGP->setTitle('Platea');
      $plateaGP->setTitle('Orchestra', 'en');
      $plateaGP->setGenericPostType($area);
      $manager->persist($plateaGP);

      $manager->flush();

      $platea = new RoomArea();
      $platea->setId($plateaGP);
      $platea->setRoom($avellanedaRoomGP);
      $manager->persist($platea);

      $zone = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
        'tree_slug' => 'zone'
      ));

      $zoneGP = new GenericPost();
      $zoneGP->setTitle('Central');
      $zoneGP->setTitle('Center', 'en');
      $zoneGP->setGenericPostType($zone);
      $manager->persist($zoneGP);

      $manager->flush();

      $rowOrientation = $manager->getRepository('AppBundle:Nomenclature')->findOneBy(
        array('tree_slug' => 'row-orientation-down-to-up')
      );

      $centerZone = new Zone();
      $centerZone->setId($zoneGP);
      $centerZone->setRoomArea($plateaGP);
      $centerZone->setOrientation($rowOrientation);
      $manager->persist($centerZone);

      $filas = array(
        array(
          'identifier' => 'A',
          'identifierType' => 'letter',
          'cantSeats' => 15,
          'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'row-orientation-right-to-left'
          )),
          'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'normal'
          )),
          'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'numbers'
          ))
        ), array(
          'identifier' => 'B',
          'identifierType' => 'letter',
          'cantSeats' => 15,
          'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'row-orientation-right-to-left'
          )),
          'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'normal'
          )),
          'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'numbers'
          ))
        ), array(
          'identifier' => 'C',
          'identifierType' => 'letter',
          'cantSeats' => 16,
          'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'row-orientation-right-to-left'
          )),
          'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'normal'
          )),
          'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'numbers'
          ))
        ),
        array(
          'identifier' => 'D',
          'identifierType' => 'letter',
          'cantSeats' => 17,
          'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'row-orientation-right-to-left'
          )),
          'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'normal'
          )),
          'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'numbers'
          ))
        ), array(
          'identifier' => 'E',
          'identifierType' => 'letter',
          'cantSeats' => 18,
          'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'row-orientation-right-to-left'
          )),
          'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'normal'
          )),
          'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'numbers'
          ))
        ),
        array(
          'identifier' => 'F',
          'identifierType' => 'letter',
          'cantSeats' => 17,
          'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'row-orientation-right-to-left'
          )),
          'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'normal'
          )),
          'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'numbers'
          ))
        ), array(
          'identifier' => 'G',
          'identifierType' => 'letter',
          'cantSeats' => 18,
          'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'row-orientation-right-to-left'
          )),
          'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'normal'
          )),
          'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'numbers'
          ))
        ),
        array(
          'identifier' => 'H',
          'identifierType' => 'letter',
          'cantSeats' => 19,
          'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'row-orientation-right-to-left'
          )),
          'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'normal'
          )),
          'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug' => 'numbers'
          ))
        )
      );

    }

    $row = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'row'
    ));

    foreach ($filas as $fila) {
      $rowGP = new GenericPost();
      $rowGP->setTitle($fila['identifier']);
      $rowGP->setTitle($fila['identifier'], 'en');
      $rowGP->setGenericPostType($row);
      $manager->persist($rowGP);

      $manager->flush();

      $rowEl = new ZoneRow();
      $rowEl->setId($rowGP);
      $rowEl->setOrientation($fila['orientation']);

      if ($fila['identifierType'] == 'letter')
        $rowEl->setIdentifier($fila['identifier']);
      else
        $rowEl->setIdentifierNumber($fila['identifier']);
      $rowEl->setIdentifierType($fila['identifierType']);
      $rowEl->setSeatCount($fila['cantSeats']);
      $rowEl->setSeatCounting($fila['counting']);
      $rowEl->setZone($zoneGP);
      $rowEl->setSeatNomenclature($fila['seatsNom']);
      $manager->persist($rowEl);
      $manager->flush();
    }

    $parametersCollection = array(
      'room' => $avellanedaRoomGP
    );
    $this->container->get('appbundle_generate_seats')->generateSeats($parametersCollection);


    /*Creating Covarrubias Hall*/
    $covarrubiasRoomGP = new GenericPost();
    $covarrubiasRoomGP->setTitle('Sala Covarrubias');
    $covarrubiasRoomGP->setTitle('Covarrubias Hall', 'en');
    $covarrubiasRoomGP->setGenericPostType($room);
    $manager->persist($covarrubiasRoomGP);

    $manager->flush();

    $covarrubiasRoom = new Room();
    $covarrubiasRoom->setId($covarrubiasRoomGP);
    $covarrubiasRoom->setHeadquarter($nationalTheaterGP);
    $manager->persist($covarrubiasRoom);

    $area = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'salable-area'
    ));

    $plateaGP = new GenericPost();
    $plateaGP->setTitle('Planta baja');
    $plateaGP->setTitle('Low plant', 'en');
    $plateaGP->setGenericPostType($area);
    $manager->persist($plateaGP);

    $manager->flush();

    $platea = new RoomArea();
    $platea->setId($plateaGP);
    $platea->setRoom($covarrubiasRoomGP);
    $manager->persist($platea);

    $zone = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'zone'
    ));

    $zoneGP = new GenericPost();
    $zoneGP->setTitle('Central');
    $zoneGP->setTitle('Center', 'en');
    $zoneGP->setGenericPostType($zone);
    $manager->persist($zoneGP);

    $manager->flush();

    $rowOrientation = $manager->getRepository('AppBundle:Nomenclature')->findOneBy(
      array('tree_slug' => 'row-orientation-down-to-up')
    );

    $centerZone = new Zone();
    $centerZone->setId($zoneGP);
    $centerZone->setRoomArea($plateaGP);
    $centerZone->setOrientation($rowOrientation);
    $manager->persist($centerZone);

    $filas = array(
      array(
        'identifier' => 'C',
        'identifierType' => 'letter',
        'cantSeats' => 9,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),
      array(
        'identifier' => 'D',
        'identifierType' => 'letter',
        'cantSeats' => 9,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ), array(
        'identifier' => 'E',
        'identifierType' => 'letter',
        'cantSeats' => 9,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),
      array(
        'identifier' => 'F',
        'identifierType' => 'letter',
        'cantSeats' => 10,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ), array(
        'identifier' => 'G',
        'identifierType' => 'letter',
        'cantSeats' => 10,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),
      array(
        'identifier' => 'H',
        'identifierType' => 'letter',
        'cantSeats' => 10,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      )
    );


    $row = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'row'
    ));

    foreach ($filas as $fila) {
      $rowGP = new GenericPost();
      $rowGP->setTitle($fila['identifier']);
      $rowGP->setTitle($fila['identifier'], 'en');
      $rowGP->setGenericPostType($row);
      $manager->persist($rowGP);

      $manager->flush();

      $rowEl = new ZoneRow();
      $rowEl->setId($rowGP);
      $rowEl->setOrientation($fila['orientation']);

      if ($fila['identifierType'] == 'letter')
        $rowEl->setIdentifier($fila['identifier']);
      else
        $rowEl->setIdentifierNumber($fila['identifier']);

      $rowEl->setSeatCount($fila['cantSeats']);
      $rowEl->setSeatCounting($fila['counting']);
      $rowEl->setZone($zoneGP);
      $rowEl->setSeatNomenclature($fila['seatsNom']);
      $rowEl->setIdentifierType($fila['identifierType']);
      $manager->persist($rowEl);
      $manager->flush();
    }

    $parametersCollection = array(
      'room' => $covarrubiasRoomGP
    );
    $this->container->get('appbundle_generate_seats')->generateSeats($parametersCollection);


    /*Creating Mella Hall*/
    $mellaRoomGP = new GenericPost();
    $mellaRoomGP->setTitle('Mella');
    $mellaRoomGP->setTitle('Mella Hall', 'en');
    $mellaRoomGP->setGenericPostType($room);
    $manager->persist($mellaRoomGP);

    $manager->flush();

    $mellaRoom = new Room();
    $mellaRoom->setId($mellaRoomGP);
    $mellaRoom->setHeadquarter($mellaTheaterGP);
    $manager->persist($mellaRoom);

    $area = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'salable-area'
    ));

    $plateaGP = new GenericPost();
    $plateaGP->setTitle('Platea');
    $plateaGP->setTitle('Orchestra', 'en');
    $plateaGP->setGenericPostType($area);
    $manager->persist($plateaGP);

    $manager->flush();

    $platea = new RoomArea();
    $platea->setId($plateaGP);
    $platea->setRoom($mellaRoomGP);
    $manager->persist($platea);

    $zone = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'zone'
    ));

    $zoneGP = new GenericPost();
    $zoneGP->setTitle('Central');
    $zoneGP->setTitle('Center', 'en');
    $zoneGP->setGenericPostType($zone);
    $manager->persist($zoneGP);

    $manager->flush();

    $rowOrientation = $manager->getRepository('AppBundle:Nomenclature')->findOneBy(
      array('tree_slug' => 'row-orientation-down-to-up')
    );

    $centerZone = new Zone();
    $centerZone->setId($zoneGP);
    $centerZone->setRoomArea($plateaGP);
    $centerZone->setOrientation($rowOrientation);
    $manager->persist($centerZone);

    $filas = array(
      array(
        'identifier' => '4',
        'identifierType' => 'number',
        'cantSeats' => 15,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'letters'
        ))
      ),
      array(
        'identifier' => '5',
        'identifierType' => 'number',
        'cantSeats' => 16,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'letters'
        ))
      ), array(
        'identifier' => '6',
        'identifierType' => 'number',
        'cantSeats' => 15,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'letters'
        ))
      ),
      array(
        'identifier' => '7',
        'identifierType' => 'number',
        'cantSeats' => 16,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'letters'
        ))
      ), array(
        'identifier' => '8',
        'identifierType' => 'number',
        'cantSeats' => 15,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'letters'
        ))
      ),
      array(
        'identifier' => '9',
        'identifierType' => 'number',
        'cantSeats' => 16,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'letters'
        ))
      ), array(
        'identifier' => '10',
        'identifierType' => 'number',
        'cantSeats' => 15,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'letters'
        ))
      )
    );


    $row = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'row'
    ));

    foreach ($filas as $fila) {
      $rowGP = new GenericPost();
      $rowGP->setTitle($fila['identifier']);
      $rowGP->setTitle($fila['identifier'], 'en');
      $rowGP->setGenericPostType($row);
      $manager->persist($rowGP);

      $manager->flush();
      $rowEl = new ZoneRow();
      $rowEl->setId($rowGP);
      $rowEl->setOrientation($fila['orientation']);

      if ($fila['identifierType'] == 'letter')
        $rowEl->setIdentifier($fila['identifier']);
      else
        $rowEl->setIdentifierNumber($fila['identifier']);

      $rowEl->setSeatCount($fila['cantSeats']);
      $rowEl->setSeatCounting($fila['counting']);
      $rowEl->setZone($zoneGP);
      $rowEl->setSeatNomenclature($fila['seatsNom']);
      $rowEl->setIdentifierType($fila['identifierType']);
      $manager->persist($rowEl);
      $manager->flush();
    }

    $parametersCollection = array(
      'room' => $mellaRoomGP
    );
    $this->container->get('appbundle_generate_seats')->generateSeats($parametersCollection);

    /*Creating Great Theater Main Hall*/
    $habanaMainRoomGP = new GenericPost();
    $habanaMainRoomGP->setTitle('Principal');
    $habanaMainRoomGP->setTitle('Principal Hall', 'en');
    $habanaMainRoomGP->setGenericPostType($room);
    $manager->persist($habanaMainRoomGP);

    $manager->flush();

    $habanaMainRoom = new Room();
    $habanaMainRoom->setId($habanaMainRoomGP);
    $habanaMainRoom->setHeadquarter($habanaTheaterGP);
    $manager->persist($habanaMainRoom);

    $area = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'salable-area'
    ));

    $plateaGP = new GenericPost();
    $plateaGP->setTitle('Platea');
    $plateaGP->setTitle('Orchestra', 'en');
    $plateaGP->setGenericPostType($area);
    $manager->persist($plateaGP);

    $manager->flush();

    $platea = new RoomArea();
    $platea->setId($plateaGP);
    $platea->setRoom($habanaMainRoomGP);
    $manager->persist($platea);

    $zone = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'zone'
    ));

    /*Central Zone*/
    $zoneGP = new GenericPost();
    $zoneGP->setTitle('Central');
    $zoneGP->setTitle('Center', 'en');
    $zoneGP->setGenericPostType($zone);
    $manager->persist($zoneGP);

    $manager->flush();

    $rowOrientation = $manager->getRepository('AppBundle:Nomenclature')->findOneBy(
      array('tree_slug' => 'row-orientation-down-to-up')
    );

    $centerZone = new Zone();
    $centerZone->setId($zoneGP);
    $centerZone->setRoomArea($plateaGP);
    $centerZone->setOrientation($rowOrientation);
    $manager->persist($centerZone);

    $filas = array(
      array(
        'identifier' => 'A',
        'identifierType' => 'letter',
        'cantSeats' => 10,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ), array(
        'identifier' => 'B',
        'identifierType' => 'letter',
        'cantSeats' => 11,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ), array(
        'identifier' => 'C',
        'identifierType' => 'letter',
        'cantSeats' => 12,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),
      array(
        'identifier' => 'D',
        'identifierType' => 'letter',
        'cantSeats' => 11,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ), array(
        'identifier' => 'E',
        'identifierType' => 'letter',
        'cantSeats' => 12,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),
      array(
        'identifier' => 'F',
        'identifierType' => 'letter',
        'cantSeats' => 11,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ), array(
        'identifier' => 'G',
        'identifierType' => 'letter',
        'cantSeats' => 12,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),
      array(
        'identifier' => 'H',
        'identifierType' => 'letter',
        'cantSeats' => 11,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),
      array(
        'identifier' => 'I',
        'identifierType' => 'letter',
        'cantSeats' => 12,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),
      array(
        'identifier' => 'J',
        'identifierType' => 'letter',
        'cantSeats' => 11,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-left-to-right'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'normal'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      )
    );


    $row = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'row'
    ));

    foreach ($filas as $fila) {
      $rowGP = new GenericPost();
      $rowGP->setTitle($fila['identifier']);
      $rowGP->setTitle($fila['identifier'], 'en');
      $rowGP->setGenericPostType($row);
      $manager->persist($rowGP);

      $manager->flush();
      $rowEl = new ZoneRow();
      $rowEl->setId($rowGP);
      $rowEl->setOrientation($fila['orientation']);

      if ($fila['identifierType'] == 'letter')
        $rowEl->setIdentifier($fila['identifier']);
      else
        $rowEl->setIdentifierNumber($fila['identifier']);

      $rowEl->setSeatCount($fila['cantSeats']);
      $rowEl->setSeatCounting($fila['counting']);
      $rowEl->setZone($zoneGP);
      $rowEl->setSeatNomenclature($fila['seatsNom']);
      $rowEl->setIdentifierType($fila['identifierType']);
      $manager->persist($rowEl);
      $manager->flush();
    }


    /*Left Zone*/
    $zoneGP = new GenericPost();
    $zoneGP->setTitle('Izquierda');
    $zoneGP->setTitle('Left', 'en');
    $zoneGP->setGenericPostType($zone);
    $manager->persist($zoneGP);

    $manager->flush();

    $rowOrientation = $manager->getRepository('AppBundle:Nomenclature')->findOneBy(
      array('tree_slug' => 'row-orientation-down-to-up')
    );

    $centerZone = new Zone();
    $centerZone->setId($zoneGP);
    $centerZone->setRoomArea($plateaGP);
    $centerZone->setOrientation($rowOrientation);
    $manager->persist($centerZone);

    $filas = array(
      array(
        'identifier' => '5',
        'identifierType' => 'number',
        'cantSeats' => 8,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-right-to-left'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'pair'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ), array(
        'identifier' => '6',
        'identifierType' => 'number',
        'cantSeats' => 9,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-right-to-left'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'pair'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ), array(
        'identifier' => '7',
        'identifierType' => 'number',
        'cantSeats' => 9,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-right-to-left'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'pair'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),array(
        'identifier' => '8',
        'identifierType' => 'number',
        'cantSeats' => 9,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-right-to-left'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'pair'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),array(
        'identifier' => '9',
        'identifierType' => 'number',
        'cantSeats' => 9,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-right-to-left'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'pair'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),array(
        'identifier' => '10',
        'identifierType' => 'number',
        'cantSeats' => 8,
        'orientation' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'row-orientation-right-to-left'
        )),
        'counting' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'pair'
        )),
        'seatsNom' => $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
          'tree_slug' => 'numbers'
        ))
      ),
    );


    $row = $manager->getRepository('AppBundle:GenericPostType')->findOneBy(array(
      'tree_slug' => 'row'
    ));

    foreach ($filas as $fila) {
      $rowGP = new GenericPost();
      $rowGP->setTitle($fila['identifier']);
      $rowGP->setTitle($fila['identifier'], 'en');
      $rowGP->setGenericPostType($row);
      $manager->persist($rowGP);

      $manager->flush();
      $rowEl = new ZoneRow();
      $rowEl->setId($rowGP);
      $rowEl->setOrientation($fila['orientation']);

      if ($fila['identifierType'] == 'letter')
        $rowEl->setIdentifier($fila['identifier']);
      else
        $rowEl->setIdentifierNumber($fila['identifier']);

      $rowEl->setSeatCount($fila['cantSeats']);
      $rowEl->setSeatCounting($fila['counting']);
      $rowEl->setZone($zoneGP);
      $rowEl->setSeatNomenclature($fila['seatsNom']);
      $rowEl->setIdentifierType($fila['identifierType']);
      $manager->persist($rowEl);
      $manager->flush();
    }


    $parametersCollection = array(
      'room' => $habanaMainRoomGP
    );
    $this->container->get('appbundle_generate_seats')->generateSeats($parametersCollection);
    /*end gran theater*/

    $manager->flush();
  }

  public function getOrder()
  {
    return 8;
  }
}
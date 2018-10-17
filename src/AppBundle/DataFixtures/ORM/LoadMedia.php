<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\NomContentBlockType;
use AppBundle\Entity\Media;
use AppBundle\Entity\MediaImage;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadMedia extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /* Getting the media type IMAGE */
        $nomImageMediaType = $manager->getRepository('AppBundle:Nomenclature')->findOneBy(array(
            'tree_slug'=>'media-type-image'
        ));
        if(isset($nomImageMediaType)){

            /*Default media image for Users avatar*/
            $defaultUserAvatarMedia = new Media();
            $defaultUserAvatarMedia->setName('bnc-default-user-avatar');
            $defaultUserAvatarMedia->setDescription('Imagen por defecto para los usuarios del sitio BNC');
            $defaultUserAvatarMedia->setMediaType($nomImageMediaType);
            $defaultUserAvatarMedia->setUrl('uploads/images/original/bnc-default-user-avatar.png');
            $manager->persist($defaultUserAvatarMedia);
            $manager->flush($defaultUserAvatarMedia);

            $defaultUserAvatarMediaImage = new MediaImage();
            $defaultUserAvatarMediaImage->setId($defaultUserAvatarMedia);
            $defaultUserAvatarMediaImage->setAlternativeText('user-avatar');
            $defaultUserAvatarMediaImage->setExtension('png');
            $defaultUserAvatarMediaImage->setSize(5090);
            $defaultUserAvatarMediaImage->setDimension('200x200');
            $defaultUserAvatarMediaImage->setIsLoadedBySystem(true);
            $manager->persist($defaultUserAvatarMediaImage);
            //$manager->flush();


            /*Default media image for BNC banner*/
            $defaultBncBannerMedia = new Media();
            $defaultBncBannerMedia->setName('bnc-default-banner');
            $defaultBncBannerMedia->setDescription('Imagen por defecto para el banner del sitio BNC');
            $defaultBncBannerMedia->setMediaType($nomImageMediaType);
            $defaultBncBannerMedia->setUrl('uploads/images/original/bnc-default-banner.jpeg');
            $manager->persist($defaultBncBannerMedia);
            $manager->flush($defaultBncBannerMedia);

            $defaultBncBannerMediaImage = new MediaImage();
            $defaultBncBannerMediaImage->setId($defaultBncBannerMedia);
            $defaultBncBannerMediaImage->setAlternativeText('default-banner');
            $defaultBncBannerMediaImage->setExtension('jpeg');
            $defaultBncBannerMediaImage->setSize(570642);
            $defaultBncBannerMediaImage->setDimension('1920x900');
            $defaultBncBannerMediaImage->setIsLoadedBySystem(true);
            $manager->persist($defaultBncBannerMediaImage);

            /*Default media image for avellaneda hall map*/
            $avellanedaHallMapMedia = new Media();
            $avellanedaHallMapMedia->setName('bnc-avellaneda-hall-map');
            $avellanedaHallMapMedia->setDescription('Imagen del mapa de la Sala Avellaneda');
            $avellanedaHallMapMedia->setMediaType($nomImageMediaType);
            $avellanedaHallMapMedia->setUrl('uploads/images/original/bnc-avellaneda-hall-map.jpeg');
            $manager->persist($avellanedaHallMapMedia);
            $manager->flush($avellanedaHallMapMedia);

            $avellanedaHallMapMediaImage = new MediaImage();
            $avellanedaHallMapMediaImage->setId($avellanedaHallMapMedia);
            $avellanedaHallMapMediaImage->setAlternativeText('avellaneda-hall-map');
            $avellanedaHallMapMediaImage->setExtension('jpeg');
            $avellanedaHallMapMediaImage->setSize(3952);
            $avellanedaHallMapMediaImage->setDimension('2550X3300');
            $avellanedaHallMapMediaImage->setIsLoadedBySystem(true);
            $manager->persist($avellanedaHallMapMediaImage);

            /*Default media image for covarrubias hall map*/
            $covarrubiasHallMapMedia = new Media();
            $covarrubiasHallMapMedia->setName('bnc-covarrubias-hall-map');
            $covarrubiasHallMapMedia->setDescription('Imagen del mapa de la Sala covarrubias');
            $covarrubiasHallMapMedia->setMediaType($nomImageMediaType);
            $covarrubiasHallMapMedia->setUrl('uploads/images/original/bnc-covarrubias-hall-map.jpeg');
            $manager->persist($covarrubiasHallMapMedia);
            $manager->flush($covarrubiasHallMapMedia);

            $covarrubiasHallMapMediaImage = new MediaImage();
            $covarrubiasHallMapMediaImage->setId($covarrubiasHallMapMedia);
            $covarrubiasHallMapMediaImage->setAlternativeText('covarrubias-hall-map');
            $covarrubiasHallMapMediaImage->setExtension('jpeg');
            $covarrubiasHallMapMediaImage->setSize(2752);
            $covarrubiasHallMapMediaImage->setDimension('2550X3300');
            $covarrubiasHallMapMediaImage->setIsLoadedBySystem(true);
            $manager->persist($covarrubiasHallMapMediaImage);

            /*Default media image for mella hall map*/
            $mellaHallMapMedia = new Media();
            $mellaHallMapMedia->setName('fibha-mella-hall-map');
            $mellaHallMapMedia->setDescription('Imagen del mapa de la Sala mella');
            $mellaHallMapMedia->setMediaType($nomImageMediaType);
            $mellaHallMapMedia->setUrl('uploads/images/original/fibha-mella-hall-map.jpeg');
            $manager->persist($mellaHallMapMedia);
            $manager->flush($mellaHallMapMedia);

            $mellaHallMapMediaImage = new MediaImage();
            $mellaHallMapMediaImage->setId($mellaHallMapMedia);
            $mellaHallMapMediaImage->setAlternativeText('mella-hall-map');
            $mellaHallMapMediaImage->setExtension('jpeg');
            $mellaHallMapMediaImage->setSize(2752);
            $mellaHallMapMediaImage->setDimension('2550X3300');
            $mellaHallMapMediaImage->setIsLoadedBySystem(true);
            $manager->persist($mellaHallMapMediaImage);

          /*Default media image for mella hall map*/
          $gthhaHallMapMedia = new Media();
          $gthhaHallMapMedia->setName('fibha-gthha-hall-map');
          $gthhaHallMapMedia->setDescription('Imagen del mapa de la Sala del Gran teatro de la habana');
          $gthhaHallMapMedia->setMediaType($nomImageMediaType);
          $gthhaHallMapMedia->setUrl('uploads/images/original/fibha-gthha-hall-map.jpeg');
          $manager->persist($gthhaHallMapMedia);
          $manager->flush($gthhaHallMapMedia);

          $gthhaHallMapMediaImage = new MediaImage();
          $gthhaHallMapMediaImage->setId($gthhaHallMapMedia);
          $gthhaHallMapMediaImage->setAlternativeText('gthha-hall-map');
          $gthhaHallMapMediaImage->setExtension('jpeg');
          $gthhaHallMapMediaImage->setSize(2752);
          $gthhaHallMapMediaImage->setDimension('2550X3300');
          $gthhaHallMapMediaImage->setIsLoadedBySystem(true);
          $manager->persist($gthhaHallMapMediaImage);


            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 3;
    }
}
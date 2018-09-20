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

            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 3;
    }
}
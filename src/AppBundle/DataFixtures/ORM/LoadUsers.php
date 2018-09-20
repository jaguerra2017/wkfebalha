<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    private function encodePassword(User $user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user)
        ;
        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    public function load(ObjectManager $manager)
    {
        /*User JDY Dev*/
        $userJDYDev = new User();
        $userJDYDev->setFullName('JDY Dev');
        $userJDYDev->setEmail('jdydev@jdy.com');
        $userJDYDev->setUserName('jdy_dev');
        $userJDYDev->setPassword($this->encodePassword($userJDYDev, 'jdydev'));
        $roleJDYDev = $manager->getRepository('AppBundle:Role')->findOneBy(array(
            'slug' => 'ROLE_JDY_DEV'
        ));
        if(isset($roleJDYDev)){
            $userJDYDev->setRole($roleJDYDev);
        }

        $defaultAvatarMedia = $manager->getRepository('AppBundle:Media')->findOneBy(array(
            'name_es' => 'bnc-default-user-avatar'
        ));
        if(isset($defaultAvatarMedia)){
            $userJDYDev->setAvatar($defaultAvatarMedia);
        }
        $manager->persist($userJDYDev);
        $roleJDYDev->setTotalUsersAssigned(1);
        $manager->persist($roleJDYDev);


        /*User System Administrator*/
        $userAdmin = new User();
        $userAdmin->setFullName('Administrador de Sistema');
        $userAdmin->setEmail('bncadmin@bnc.cult.cu');
        $userAdmin->setUserName('admin_bnc');
        $userAdmin->setPassword($this->encodePassword($userAdmin, 'adminbnc'));
        $roleAdmin = $manager->getRepository('AppBundle:Role')->findOneBy(array(
            'slug' => 'ROLE_ADMIN'
        ));
        if(isset($roleAdmin)){
            $userAdmin->setRole($roleAdmin);
        }
        if(isset($defaultAvatarMedia)){
            $userAdmin->setAvatar($defaultAvatarMedia);
        }
        $manager->persist($userAdmin);
        $roleAdmin->setTotalUsersAssigned(1);
        $manager->persist($roleAdmin);



        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}
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
        $userAdmin->setEmail('admin@fibha.cult.cu');
        $userAdmin->setUserName('admin_fibha');
        $userAdmin->setPassword($this->encodePassword($userAdmin, 'adminfibha'));
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



        /*User WebMaster*/
        $userWebmaster = new User();
        $userWebmaster->setFullName('Webmaster del sitio FIBHA');
        $userWebmaster->setEmail('webmaster@fibha.cult.cu');
        $userWebmaster->setUserName('wmaster_fibha');
        $userWebmaster->setPassword($this->encodePassword($userWebmaster, 'wmasterfibha'));
        $roleWebmaster = $manager->getRepository('AppBundle:Role')->findOneBy(array(
            'slug' => 'ROLE_WEBMASTER'
        ));
        if(isset($roleWebmaster)){
            $userWebmaster->setRole($roleWebmaster);
        }
        if(isset($defaultAvatarMedia)){
            $userWebmaster->setAvatar($defaultAvatarMedia);
        }
        $manager->persist($userWebmaster);
        $roleWebmaster->setTotalUsersAssigned(1);
        $manager->persist($roleWebmaster);




        /*User Paradiso*/
        $userParadiso = new User();
        $userParadiso->setFullName('Usuario de Ventas Paradiso');
        $userParadiso->setEmail('paradiso@fibha.cult.cu');
        $userParadiso->setUserName('vparadiso_fibha');
        $userParadiso->setPassword($this->encodePassword($userParadiso, 'vparadisofibha'));
        $roleParadiso = $manager->getRepository('AppBundle:Role')->findOneBy(array(
            'slug' => 'ROLE_SALESMAN'
        ));
        if(isset($roleParadiso)){
            $userParadiso->setRole($roleParadiso);
        }
        if(isset($defaultAvatarMedia)){
            $userParadiso->setAvatar($defaultAvatarMedia);
        }
        $manager->persist($userParadiso);
        $roleParadiso->setTotalUsersAssigned(1);
        $manager->persist($roleParadiso);




        /*User Ventas Ballet*/
        $userBallet = new User();
        $userBallet->setFullName('Usuario de Ventas Ballet');
        $userBallet->setEmail('ventasballet@fibha.cult.cu');
        $userBallet->setUserName('vballet_fibha');
        $userBallet->setPassword($this->encodePassword($userBallet, 'vballetfibha'));
        $roleBallet = $manager->getRepository('AppBundle:Role')->findOneBy(array(
            'slug' => 'ROLE_SALESMAN'
        ));
        if(isset($roleBallet)){
            $userBallet->setRole($roleBallet);
        }
        if(isset($defaultAvatarMedia)){
            $userBallet->setAvatar($defaultAvatarMedia);
        }
        $manager->persist($userBallet);
        $roleBallet->setTotalUsersAssigned(1);
        $manager->persist($roleBallet);


        /*User System Tester*/
        $userTester = new User();
        $userTester->setFullName('Tester de Sistema');
        $userTester->setEmail('tester@fibha.cult.cu');
        $userTester->setUserName('tester');
        $userTester->setPassword($this->encodePassword($userTester, 'tester'));
        $roleTester = $manager->getRepository('AppBundle:Role')->findOneBy(array(
          'slug' => 'ROLE_TESTER'
        ));
        if(isset($roleTester)){
          $userTester->setRole($roleTester);
        }
        if(isset($defaultAvatarMedia)){
          $userTester->setAvatar($defaultAvatarMedia);
        }
        $manager->persist($userTester);
        $roleTester->setTotalUsersAssigned(1);
        $manager->persist($roleTester);



        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}
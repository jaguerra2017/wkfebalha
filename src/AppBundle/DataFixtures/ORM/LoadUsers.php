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
        $userAdmin->setEmail('fibhaadmin@fibha.cult.cu');
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

      /*User Webmaster*/
      $userWebMaster = new User();
      $userWebMaster->setFullName('WebMaster');
      $userWebMaster->setEmail('fibhawebmaster@fibha.cult.cu');
      $userWebMaster->setUserName('webmaster_fibha');
      $userWebMaster->setPassword($this->encodePassword($userWebMaster, 'webmasterfibha'));
      $roleWebMaster = $manager->getRepository('AppBundle:Role')->findOneBy(array(
        'slug' => 'ROLE_WEBMASTER'
      ));
      if(isset($roleWebMaster)){
        $userWebMaster->setRole($roleWebMaster);
      }
      if(isset($defaultAvatarMedia)){
        $userWebMaster->setAvatar($defaultAvatarMedia);
      }
      $manager->persist($userWebMaster);
      $roleWebMaster->setTotalUsersAssigned(1);
      $manager->persist($roleWebMaster);

      /*User Ballet Sales*/
      $userBalletSales = new User();
      $userBalletSales->setFullName('Ventas Ballet');
      $userBalletSales->setEmail('fibhaventasballet@fibha.cult.cu');
      $userBalletSales->setUserName('ventasballet_fibha');
      $userBalletSales->setPassword($this->encodePassword($userBalletSales, 'ventasballetfibha'));
      $roleBalletSales = $manager->getRepository('AppBundle:Role')->findOneBy(array(
        'slug' => 'ROLE_SALESMAN'
      ));
      if(isset($roleBalletSales)){
        $userBalletSales->setRole($roleBalletSales);
      }
      if(isset($defaultAvatarMedia)){
        $userBalletSales->setAvatar($defaultAvatarMedia);
      }
      $manager->persist($userBalletSales);
      $roleBalletSales->setTotalUsersAssigned(1);
      $manager->persist($roleBalletSales);

      /*User Paradiso Sales*/
      $userParadisoSales = new User();
      $userParadisoSales->setFullName('Ventas Paradiso');
      $userParadisoSales->setEmail('fibhaventasparadiso@fibha.cult.cu');
      $userParadisoSales->setUserName('ventasparadiso_fibha');
      $userParadisoSales->setPassword($this->encodePassword($userParadisoSales, 'ventasparadisofibha'));
      $roleParadisoSales = $manager->getRepository('AppBundle:Role')->findOneBy(array(
        'slug' => 'ROLE_SALESMAN'
      ));
      if(isset($roleParadisoSales)){
        $userParadisoSales->setRole($roleParadisoSales);
      }
      if(isset($defaultAvatarMedia)){
        $userParadisoSales->setAvatar($defaultAvatarMedia);
      }
      $manager->persist($userParadisoSales);
      $roleParadisoSales->setTotalUsersAssigned(1);
      $manager->persist($roleParadisoSales);

      /*User System Tester*/
      $userTester = new User();
      $userTester->setFullName('Tester de Sistema');
      $userTester->setEmail('bnctester@fibha.cult.cu');
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
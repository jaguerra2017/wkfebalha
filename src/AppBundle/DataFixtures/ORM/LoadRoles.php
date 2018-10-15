<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use AppBundle\Entity\RoleFunctionalityAction;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadRoles extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /*Role for JDY Developers*/
        $roleJdyDev = new Role();
        $roleJdyDev->setName('JDY Dev');
        $roleJdyDev->setDescription('Este es el Rol usado por los desarrolladores de JDY (solo en casos donde haya que restablecer el password del Administrador de Sistema). Este Rol no es editable.');
        $roleJdyDev->setSlug('ROLE_JDY_DEV');
        $manager->persist($roleJdyDev);

        /*Role for System Admin*/
        $roleAdmin = new Role();
        $roleAdmin->setName('Administrador de Sistema');
        $roleAdmin->setDescription('ESte es el Rol usado por el Administrador de Sistema. Este Rol no es editable.');
        $roleAdmin->setSlug('ROLE_ADMIN');
        $roleAdmin->setSeeSiteStatusOffline(true);
        $manager->persist($roleAdmin);

        /*Role for System WebMaster*/
        $roleWebMaster = new Role();
        $roleWebMaster->setName('Web Master');
        $roleWebMaster->setDescription('ESte es el Rol usado por el Webmaster. Este Rol no es editable.');
        $roleWebMaster->setSlug('ROLE_WEBMASTER');
        $roleWebMaster->setSeeSiteStatusOffline(true);
        $manager->persist($roleWebMaster);

        /*Role for System Salesman*/
        $roleSalesman = new Role();
        $roleSalesman->setName('Gestor de ventas');
        $roleSalesman->setDescription('ESte es el Rol usado por el Gestor de Ventas. Este Rol no es editable.');
        $roleSalesman->setSlug('ROLE_SALESMAN');
        $roleSalesman->setSeeSiteStatusOffline(true);
        $manager->persist($roleSalesman);

        /*Role for System Tester*/
        $roleTester = new Role();
        $roleTester->setName('Tester');
        $roleTester->setDescription('ESte es el Rol usado por el Gestor de Ventas. Este Rol no es editable.');
        $roleTester->setSlug('ROLE_TESTER');
        $roleTester->setSeeSiteStatusOffline(true);
        $manager->persist($roleTester);



        /*Settings, for Role-Functionality-Action*/
        $objNomTypeFunctionality = $manager->getRepository('AppBundle:NomType')->findOneBy(
            array(
                'tree_slug'=>'functionality'
            )
        );
        if(isset($objNomTypeFunctionality)){

            /*Functionalities for Role JDY Dev*/
            $usersFunctionality = $manager->getRepository('AppBundle:Nomenclature')->findOneBy(
                array(
                    'tree_slug'=>'functionality-users'
                )
            );
            if(isset($usersFunctionality)){
                $actions = $manager->getRepository('AppBundle:Nomenclature')->findBy(array(
                    'parent' =>$usersFunctionality
                ));
                if(isset($actions[0])){
                    foreach($actions as $action){
                        $objRoleFunctAction = new RoleFunctionalityAction();
                        $objRoleFunctAction->setRole($roleJdyDev);
                        $objRoleFunctAction->setFunctionality($usersFunctionality);
                        $objRoleFunctAction->setAction($action);
                        $manager->persist($objRoleFunctAction);
                    }
                }
            }

            /*Functionalities for Role Admin*/
            $functionalitiesCollection = $manager->getRepository('AppBundle:Nomenclature')->findBy(
                array(
                    'nom_type'=>$objNomTypeFunctionality
                )
            );
            if(isset($functionalitiesCollection[0])){
                foreach($functionalitiesCollection as $key=>$functionality){
                    //if($functionality->getTreeSlug() != 'functionality-users'){
                        $actions = $manager->getRepository('AppBundle:Nomenclature')->findBy(array(
                            'parent' => $functionality
                        ));
                        if(isset($actions[0])){
                            foreach($actions as $action){
                                $objRoleFunctAction = new RoleFunctionalityAction();
                                $objRoleFunctAction->setRole($roleAdmin);
                                $objRoleFunctAction->setFunctionality($functionality);
                                $objRoleFunctAction->setAction($action);
                                $manager->persist($objRoleFunctAction);
                            }
                        }
                    //}
                }
            }

            /*AQUI DEBE DE IR LAS ASIGNACIONES DE FUNCIONALIDADES PARA EL RESTO DE LOS ROLES, SEGUN LO CONVENIADO*/
        }


        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
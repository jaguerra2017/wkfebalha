<?php

namespace Application\Migrations;

use AppBundle\Entity\Nomenclature;
use AppBundle\Entity\NomFunctionality;
use AppBundle\Entity\RoleFunctionalityAction;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181022184112 extends AbstractMigration implements ContainerAwareInterface
{

  public function setContainer(ContainerInterface $container=null){
    $this->container = $container;
  }
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }

    public function postUp(Schema $schema){

    $em = $this->container->get('doctrine.orm.entity_manager');

    /* Partners*/
    $objNomTypeFunctionality = $em->getRepository('AppBundle:NomType')->findOneBy(array(
      'tree_slug' => 'functionality'
    ));
    if (isset($objNomTypeFunctionality)) {
      /*Nomenclature for Actions*/
      $objNomAction = $em->getRepository('AppBundle:NomType')->findOneBy(array(
        'tree_slug' => 'action'
      ));
      if (isset($objNomAction)) {
        $nomPartnersFunctionality = new Nomenclature();
        $nomPartnersFunctionality->setName('Asociados');
        $nomPartnersFunctionality->setUrlSlug('asociados');
        $nomPartnersFunctionality->setTreeSlug('functionality-partners');
        $nomPartnersFunctionality->setNomType($objNomTypeFunctionality);
        $nomPartnersFunctionality->setPriority(22);
        $em->persist($nomPartnersFunctionality);
        $em->flush();
        $partnerFunctionality = new NomFunctionality();
        $partnerFunctionality->setId($nomPartnersFunctionality);
        $partnerFunctionality->setIconClass('icon-book-open');
        $partnerFunctionality->setUrlIndexAction('partners_index');
        $partnerFunctionality->setKeywordSelectedClass($nomPartnersFunctionality->getUrlSlug());
        $partnerFunctionality->setIsUsedFrequently(false);
        $em->persist($partnerFunctionality);

        //Actions for Partners
        $nomPartnerReadAction = new Nomenclature();
        $nomPartnerReadAction->setName('Leer');
        $nomPartnerReadAction->setUrlSlug(' asociados-accion-leer');
        $nomPartnerReadAction->setTreeSlug('functionality-partners-action-read');
        $nomPartnerReadAction->setNomType($objNomAction);
        $nomPartnerReadAction->setParent($nomPartnersFunctionality);
        $nomPartnerReadAction->setPriority(1);
        $em->persist($nomPartnerReadAction);

        $nomPartnerCreateAction = new Nomenclature();
        $nomPartnerCreateAction->setName('Crear');
        $nomPartnerCreateAction->setUrlSlug(' asociados-accion-crear');
        $nomPartnerCreateAction->setTreeSlug('functionality-partners-action-create');
        $nomPartnerCreateAction->setNomType($objNomAction);
        $nomPartnerCreateAction->setParent($nomPartnersFunctionality);
        $nomPartnerCreateAction->setPriority(2);
        $em->persist($nomPartnerCreateAction);

        $nomPartnerEditAction = new Nomenclature();
        $nomPartnerEditAction->setName('Editar');
        $nomPartnerEditAction->setUrlSlug(' asociados-accion-editar');
        $nomPartnerEditAction->setTreeSlug('functionality-partners-action-edit');
        $nomPartnerEditAction->setNomType($objNomAction);
        $nomPartnerEditAction->setParent($nomPartnersFunctionality);
        $nomPartnerEditAction->setPriority(3);
        $em->persist($nomPartnerEditAction);

        $nomPartnerDeleteAction = new Nomenclature();
        $nomPartnerDeleteAction->setName('Eliminar');
        $nomPartnerDeleteAction->setUrlSlug(' asociados-accion-eliminar');
        $nomPartnerDeleteAction->setTreeSlug('functionality-partners-action-delete');
        $nomPartnerDeleteAction->setNomType($objNomAction);
        $nomPartnerDeleteAction->setParent($nomPartnersFunctionality);
        $nomPartnerDeleteAction->setPriority(4);
        $em->persist($nomPartnerDeleteAction);

        $nomPartnerChangeStatusAction = new Nomenclature();
        $nomPartnerChangeStatusAction->setName('Cambiar Status');
        $nomPartnerChangeStatusAction->setUrlSlug(' asociados-accion-cambiar-status');
        $nomPartnerChangeStatusAction->setTreeSlug('functionality-partners-action-change-status');
        $nomPartnerChangeStatusAction->setNomType($objNomAction);
        $nomPartnerChangeStatusAction->setParent($nomPartnersFunctionality);
        $nomPartnerChangeStatusAction->setPriority(5);
        $em->persist($nomPartnerChangeStatusAction);


        $nomGuestsFunctionality = new Nomenclature();
        $nomGuestsFunctionality->setName('Invitados');
        $nomGuestsFunctionality->setUrlSlug('invitados');
        $nomGuestsFunctionality->setTreeSlug('functionality-guest');
        $nomGuestsFunctionality->setNomType($objNomTypeFunctionality);
        $nomGuestsFunctionality->setPriority(23);
        $em->persist($nomGuestsFunctionality);
        $em->flush();
        $guestFunctionality = new NomFunctionality();
        $guestFunctionality->setId($nomGuestsFunctionality);
        $guestFunctionality->setIconClass('icon-book-open');
        $guestFunctionality->setUrlIndexAction('guests_index');
        $guestFunctionality->setKeywordSelectedClass($nomGuestsFunctionality->getUrlSlug());
        $guestFunctionality->setIsUsedFrequently(false);
        $em->persist($guestFunctionality);

        //Actions for Guests
        $nomGuestReadAction = new Nomenclature();
        $nomGuestReadAction->setName('Leer');
        $nomGuestReadAction->setUrlSlug(' invitados-accion-leer');
        $nomGuestReadAction->setTreeSlug('functionality-guest-action-read');
        $nomGuestReadAction->setNomType($objNomAction);
        $nomGuestReadAction->setParent($nomGuestsFunctionality);
        $nomGuestReadAction->setPriority(1);
        $em->persist($nomGuestReadAction);

        $nomGuestCreateAction = new Nomenclature();
        $nomGuestCreateAction->setName('Crear');
        $nomGuestCreateAction->setUrlSlug(' invitados-accion-crear');
        $nomGuestCreateAction->setTreeSlug('functionality-guest-action-create');
        $nomGuestCreateAction->setNomType($objNomAction);
        $nomGuestCreateAction->setParent($nomGuestsFunctionality);
        $nomGuestCreateAction->setPriority(2);
        $em->persist($nomGuestCreateAction);

        $nomGuestEditAction = new Nomenclature();
        $nomGuestEditAction->setName('Editar');
        $nomGuestEditAction->setUrlSlug(' invitados-accion-editar');
        $nomGuestEditAction->setTreeSlug('functionality-guest-action-edit');
        $nomGuestEditAction->setNomType($objNomAction);
        $nomGuestEditAction->setParent($nomGuestsFunctionality);
        $nomGuestEditAction->setPriority(3);
        $em->persist($nomGuestEditAction);

        $nomGuestDeleteAction = new Nomenclature();
        $nomGuestDeleteAction->setName('Eliminar');
        $nomGuestDeleteAction->setUrlSlug(' invitados-accion-eliminar');
        $nomGuestDeleteAction->setTreeSlug('functionality-guest-action-delete');
        $nomGuestDeleteAction->setNomType($objNomAction);
        $nomGuestDeleteAction->setParent($nomGuestsFunctionality);
        $nomGuestDeleteAction->setPriority(4);
        $em->persist($nomGuestDeleteAction);

        $nomGuestChangeStatusAction = new Nomenclature();
        $nomGuestChangeStatusAction->setName('Cambiar Status');
        $nomGuestChangeStatusAction->setUrlSlug(' invitados-accion-cambiar-status');
        $nomGuestChangeStatusAction->setTreeSlug('functionality-guest-action-change-status');
        $nomGuestChangeStatusAction->setNomType($objNomAction);
        $nomGuestChangeStatusAction->setParent($nomGuestsFunctionality);
        $nomGuestChangeStatusAction->setPriority(5);
        $em->persist($nomGuestChangeStatusAction);


        $em->flush();
      }
    }

    $roleSalesMan = $em->getRepository('AppBundle:Role')->findOneBy(array(
      'slug' => 'ROLE_ADMIN'
    ));
    if (isset($roleSalesMan)){
      /*Functionalities for Booking*/
      $partnerFunctionality = $em->getRepository('AppBundle:Nomenclature')->findOneBy(
        array(
          'tree_slug'=>'functionality-partner'
        )
      );
      if(isset($partnerFunctionality)){
        $actions = $em->getRepository('AppBundle:Nomenclature')->findBy(array(
          'parent' =>$partnerFunctionality
        ));
        if(isset($actions[0])){
          foreach($actions as $action){
            $objRoleFunctAction = new RoleFunctionalityAction();
            $objRoleFunctAction->setRole($roleSalesMan);
            $objRoleFunctAction->setFunctionality($partnerFunctionality);
            $objRoleFunctAction->setAction($action);
            $em->persist($objRoleFunctAction);
          }
          $em->flush();
        }
      }

      /*Functionalities for Partners*/
      $guestFunctionality = $em->getRepository('AppBundle:Nomenclature')->findOneBy(
        array(
          'tree_slug'=>'functionality-guest'
        )
      );
      if(isset($guestFunctionality)){
        $actions = $em->getRepository('AppBundle:Nomenclature')->findBy(array(
          'parent' =>$guestFunctionality
        ));
        if(isset($actions[0])){
          foreach($actions as $action){
            $objRoleFunctAction = new RoleFunctionalityAction();
            $objRoleFunctAction->setRole($roleSalesMan);
            $objRoleFunctAction->setFunctionality($guestFunctionality);
            $objRoleFunctAction->setAction($action);
            $em->persist($objRoleFunctAction);
          }
          $em->flush();
        }
      }
    }
  }
}

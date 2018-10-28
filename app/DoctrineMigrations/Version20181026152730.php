<?php

namespace Application\Migrations;

use AppBundle\Entity\GenericPostTypeTaxonomyType;
use AppBundle\Entity\Nomenclature;
use AppBundle\Entity\NomFunctionality;
use AppBundle\Entity\RoleFunctionalityAction;
use AppBundle\Entity\TaxonomyType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181026152730 extends AbstractMigration implements ContainerAwareInterface
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
        $nomPartnersFunctionality->setName('Colaterales');
        $nomPartnersFunctionality->setUrlSlug('colateral');
        $nomPartnersFunctionality->setTreeSlug('functionality-collateral');
        $nomPartnersFunctionality->setNomType($objNomTypeFunctionality);
        $nomPartnersFunctionality->setPriority(24);
        $em->persist($nomPartnersFunctionality);
        $em->flush();
        $partnerFunctionality = new NomFunctionality();
        $partnerFunctionality->setId($nomPartnersFunctionality);
        $partnerFunctionality->setIconClass('icon-book-open');
        $partnerFunctionality->setUrlIndexAction('collateral_index');
        $partnerFunctionality->setKeywordSelectedClass($nomPartnersFunctionality->getUrlSlug());
        $partnerFunctionality->setIsUsedFrequently(false);
        $em->persist($partnerFunctionality);

        //Actions for Partners
        $nomPartnerReadAction = new Nomenclature();
        $nomPartnerReadAction->setName('Leer');
        $nomPartnerReadAction->setUrlSlug(' colateral-accion-leer');
        $nomPartnerReadAction->setTreeSlug('functionality-collateral-action-read');
        $nomPartnerReadAction->setNomType($objNomAction);
        $nomPartnerReadAction->setParent($nomPartnersFunctionality);
        $nomPartnerReadAction->setPriority(1);
        $em->persist($nomPartnerReadAction);

        $nomPartnerCreateAction = new Nomenclature();
        $nomPartnerCreateAction->setName('Crear');
        $nomPartnerCreateAction->setUrlSlug(' colateral-accion-crear');
        $nomPartnerCreateAction->setTreeSlug('functionality-collateral-action-create');
        $nomPartnerCreateAction->setNomType($objNomAction);
        $nomPartnerCreateAction->setParent($nomPartnersFunctionality);
        $nomPartnerCreateAction->setPriority(2);
        $em->persist($nomPartnerCreateAction);

        $nomPartnerEditAction = new Nomenclature();
        $nomPartnerEditAction->setName('Editar');
        $nomPartnerEditAction->setUrlSlug(' colateral-accion-editar');
        $nomPartnerEditAction->setTreeSlug('functionality-collateral-action-edit');
        $nomPartnerEditAction->setNomType($objNomAction);
        $nomPartnerEditAction->setParent($nomPartnersFunctionality);
        $nomPartnerEditAction->setPriority(3);
        $em->persist($nomPartnerEditAction);

        $nomPartnerDeleteAction = new Nomenclature();
        $nomPartnerDeleteAction->setName('Eliminar');
        $nomPartnerDeleteAction->setUrlSlug(' colateral-accion-eliminar');
        $nomPartnerDeleteAction->setTreeSlug('functionality-collateral-action-delete');
        $nomPartnerDeleteAction->setNomType($objNomAction);
        $nomPartnerDeleteAction->setParent($nomPartnersFunctionality);
        $nomPartnerDeleteAction->setPriority(4);
        $em->persist($nomPartnerDeleteAction);

        $nomPartnerChangeStatusAction = new Nomenclature();
        $nomPartnerChangeStatusAction->setName('Cambiar Status');
        $nomPartnerChangeStatusAction->setUrlSlug(' colateral-accion-cambiar-status');
        $nomPartnerChangeStatusAction->setTreeSlug('functionality-collateral-action-change-status');
        $nomPartnerChangeStatusAction->setNomType($objNomAction);
        $nomPartnerChangeStatusAction->setParent($nomPartnersFunctionality);
        $nomPartnerChangeStatusAction->setPriority(5);
        $em->persist($nomPartnerChangeStatusAction);

        /*Taxonomies Types*/
        $collateralCategoryTaxonomyType = new TaxonomyType();
        $collateralCategoryTaxonomyType->setName('Categoría de la colateral');
        $collateralCategoryTaxonomyType->setDescription('Taxonomía para categorizar las colaterales');
        $collateralCategoryTaxonomyType->setUrlSlug('categoria-colateral');
        $collateralCategoryTaxonomyType->setTreeSlug('collateral-category');
        $em->persist($collateralCategoryTaxonomyType);


        $collateralGenericPostType = $em->getRepository('AppBundle:GenericPostType')->findOneBy(array(
          'tree_slug'=>'collateral'
        ));
        if(isset($collateralGenericPostType)){
          $collateralTypePostCategory = new GenericPostTypeTaxonomyType();
          $collateralTypePostCategory->setGenericPostType($collateralGenericPostType);
          $collateralTypePostCategory->setTaxonomyType($collateralCategoryTaxonomyType);
          $em->persist($collateralTypePostCategory);
        }

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
          'tree_slug'=>'functionality-collateral'
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
    }
  }
}

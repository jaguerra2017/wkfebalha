<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\TaxonomyType;
use AppBundle\Entity\Taxonomy;
use AppBundle\Bussiness\NomenclatureBussiness;
use Symfony\Component\Validator\Constraints\DateTime;


class TaxonomyBussiness
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadInitialsData()
    {
        $initialsData = array();
        $taxonomyTypesCollection = $this->getTaxonomyTypesList();
        $initialsData['taxonomyTypesDataCollection'] = $taxonomyTypesCollection;

        /*by default must try to load the taxonomies associated to the first Taxonomy Type of the Collection*/
        $parametersCollection = array();
        if(isset($taxonomyTypesCollection[0])){
            $parametersCollection['taxonomyTypeTreeSlug'] = $taxonomyTypesCollection[0]['tree_slug'];
        }
        $taxonomiesCollection = $this->getTaxonomiesList($parametersCollection);
        $initialsData['taxonomiesDataCollection'] = $taxonomiesCollection;

        return $initialsData;
    }

    public function getTaxonomiesList($parametersCollection)
    {
        $taxonomiesCollection = $this->em->getRepository('AppBundle:Taxonomy')->getTaxonomies($parametersCollection);
        if(isset($taxonomiesCollection[0])){
            foreach($taxonomiesCollection as $key=>$taxonomy){

                $canEdit = 1;
                $canDelete = 1;
                $allowTreeRelation = 1;
                $objTaxonomy = $this->em->getRepository('AppBundle:Taxonomy')->find($taxonomy['id']);
                $taxonomyGenericPost = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->findOneBy(array(
                    'taxonomy'=>$objTaxonomy
                ));
                if(isset($taxonomyGenericPost))
                {$canDelete = 0;}
                /* deny tree relation only for TAG */
                if($taxonomy['taxonomy_type_tree_slug'] == 'tag'){
                    $allowTreeRelation = 0;
                }
                /* changing date format */
                $created_date = date_format($taxonomy['created_date'],'d/m/Y H:i');

                $taxonomiesCollection[$key]['canEdit'] = $canEdit;
                $taxonomiesCollection[$key]['canDelete'] = $canDelete;
                $taxonomiesCollection[$key]['allowTreeRelation'] = $allowTreeRelation;
                $taxonomiesCollection[$key]['created_date'] = $created_date;

                $taxonomiesChildsCollection = array();
                if(isset($parametersCollection['returnDataInTree']) && $parametersCollection['returnDataInTree'] == true
                && isset($parametersCollection['taxonomyTypeTreeSlug']) && $allowTreeRelation == 1)
                {
                    $parametersCollection['searchByParent'] = true;
                    $parametersCollection['parentId'] = $taxonomy['id'];
                    $taxonomiesChildsCollection = $this->getTaxonomiesList($parametersCollection);
                }
                $taxonomiesCollection[$key]['childs'] = $taxonomiesChildsCollection;
            }
        }
        return $taxonomiesCollection;
    }

    public function getTaxonomyTypesList()
    {
        $parametersCollection = array();
        $taxonomyTypesCollection = $this->em->getRepository('AppBundle:TaxonomyType')->getTaxonomyTypes($parametersCollection);
        return $taxonomyTypesCollection;
    }

    public function saveTaxonomyData($parametersCollection)
    {
        try{
            $canProceed = true;
            $success = 1;
            $message = 'Datos guardados.';

            if($parametersCollection['isCreating']){
               /* Checking previous existence */
               $objTaxonomy = $this->em->getRepository('AppBundle:Taxonomy')->findOneBy(array(
                   'url_slug_es' => $parametersCollection['url_slug']
               ));
               if(isset($objTaxonomy)){
                   $canProceed = false;
                   $message = 'Ya existe una taxonomía con ese Slug.';
               }
               else{/* ASSIGN singular values for CREATE operation */
                   $objTaxonomy = new Taxonomy();
                   $objTaxonomyType = $this->em->getRepository('AppBundle:TaxonomyType')->find($parametersCollection['type_id']);
                   if(isset($objTaxonomyType)){
                       $objTaxonomy->setTaxonomyType($objTaxonomyType);
                       $objTaxonomy->setTreeSlug($objTaxonomyType->getTreeSlug().'-'.$parametersCollection['url_slug']);
                       $objTaxonomy->setCreatedAuthor($parametersCollection['loggedUser']);
                   }
                   else{
                       $canProceed = false;
                       $message = 'El tipado de la taxonomía no se encuentra en los registros.';
                   }
               }
            }
            else{
                /* Checking previous existence */
                $objTaxonomy = $this->em->getRepository('AppBundle:Taxonomy')->find($parametersCollection['taxonomy_id']);
                if(isset($objTaxonomy)){
                    $objTaxWithSameUrl = $this->em->getRepository('AppBundle:Taxonomy')->findOneBy(array(
                        'url_slug_es' => $parametersCollection['url_slug']
                    ));
                    if(isset($objTaxWithSameUrl) && $objTaxWithSameUrl->getId() != $parametersCollection['taxonomy_id']){
                        $canProceed = false;
                        $message = 'Ya existe una taxonomía con la misma url.';
                    }
                    else{
                        $objTaxonomy->setModifiedDate(new \DateTime());
                        $objTaxonomy->setModifiedAuthor($parametersCollection['loggedUser']);
                    }
                }
                else{
                    $canProceed = false;
                    $message = 'La taxonomía que usted desea editar no se encuentra en los registros.';
                }
            }

            if($canProceed){
                /* ASSIGN common values */
                $objTaxonomy->setName($parametersCollection['name']);
                $objTaxonomy->setUrlSlug($parametersCollection['url_slug']);
                if($parametersCollection['parent_id'] != null){
                    $objTaxonomyParent = $this->em->getRepository('AppBundle:Taxonomy')->find($parametersCollection['parent_id']);
                    if(isset($objTaxonomyParent)){
                        if($objTaxonomyParent->getDepth() < 3){
                            $objTaxonomy->setParent($objTaxonomyParent);
                            $objTaxonomy->setDepth($objTaxonomyParent->getDepth()+1);
                        }
                        else{
                            $canProceed = false;
                            $message = 'Solo es permitido crear taxonomías hijas hasta el tercer nivel.';
                        }
                    }
                    else{
                        $canProceed = false;
                        $message = 'La taxonomía que usted desea asignar como padre ya no existe en los registros.';
                    }
                }
                else{
                    $objTaxonomy->setParent(null);
                    $objTaxonomy->setDepth(1);
                }

                if($canProceed){
                    $this->em->persist($objTaxonomy);
                    $this->em->flush();
                }
            }
            if(!$canProceed){
                $success = 0;
            }


            /* Returning results */
            return array(
                'success'=>$success,
                'message'=>$message
            );
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function deleteTaxonomies($parametersCollection)
    {
        try{
            $canProceed = true;
            $success = 1;
            $message = 'Datos guardados.';
            if(isset($parametersCollection['taxonomiesId'][0])) {
                $taxonomiesId = $parametersCollection['taxonomiesId'];
                $parametersCollection['searchByTaxonomy'] = true;
                $parametersCollection['taxonomiesId'] = implode(",", $parametersCollection['taxonomiesId']);
                $taxonomyInUse = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPostTaxonomies($parametersCollection);
                if(isset($taxonomyInUse[0])){
                    $canProceed = false;
                    $message = 'Mo puede ejecutar la operación. La taxonomía que desea eliminar esta en uso.';
                    if(isset($taxonomiesId[1])){
                        $message = 'Mo puede ejecutar la operación. Al menos una de las taxonomías que desea eliminar esta en uso.';
                    }
                }
            }
            else{
                $canProceed = false;
                $message = 'No existen taxonomías para eliminar.';
            }

            if($canProceed){
                $this->em->getRepository('AppBundle:Taxonomy')->deleteByIdsCollection($parametersCollection['taxonomiesId']);
            }
            else{
                $success = 0;
            }

            /* Returning results */
            return array(
                'success'=>$success,
                'message'=>$message
            );

        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

}
<?php

namespace AppBundle\Bussiness;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\User;
use AppBundle\Bussiness\NomenclatureBussiness;
use Symfony\Component\Validator\Constraints\DateTime;


class UsersBussiness
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadInitialsData()
    {
        try{
            $initialsData = array();

            /*by default must try to load the users associated to the first Taxonomy Type of the Collection*/
            $parametersCollection = array();
            $usersCollection = array();
            $initialsData['usersDataCollection'] = $usersCollection;

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function getUsersList($parametersCollection)
    {
        try{
            if( isset($parametersCollection['searchByIdsCollection'])
                && $parametersCollection['searchByIdsCollection'] == true
                && isset($parametersCollection['idsCollection'])
                && isset($parametersCollection['idsCollection'][0])){
                $parametersCollection['idsCollection'] = implode(',', $parametersCollection['idsCollection']);
            }
            $usersCollection = $this->em->getRepository('AppBundle:User')->getUsers($parametersCollection);
            if(isset($usersCollection[0])){

                if(isset($parametersCollection['returnSingleResult']) && $parametersCollection['returnSingleResult'] == true){
                    return $usersCollection[0];
                }
            }
            return$usersCollection;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }

    public function saveUserData($parametersCollection){
        try{
            $message = 'Datos guardados.';
            /*checking previous existence*/
            $objUser = $this->em->getRepository('AppBundle:User')->findOneBy(array(
                'full_name' => $parametersCollection['full_name']
            ));
            if(isset($objUser)){
                if($parametersCollection['isCreating'] == true ||
                    ($parametersCollection['isCreating'] == false &&
                        $objUser->getId() != $parametersCollection['id'])){
                    $message = 'Ya existe un usuario con ese nombre.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
            }

            $objUser = new User();
            if($parametersCollection['isCreating'] == false){
                $objUser = $this->em->getRepository('AppBundle:User')->find($parametersCollection['id']);
                if(!isset($objUser)){
                    $message = 'El usuario que desea editar ya no existe.';
                    return $this->returnResponse(array('success'=>0,'message'=>$message));
                }
                $objUser->setModifiedDate(new \DateTime());
                $objUser->setModifiedAuthor($parametersCollection['loggedUser']);
            }

            $objUser->setFullName($parametersCollection['full_name']);
            $objMedia = $this->em->getRepository('AppBundle:Media')->find($parametersCollection['avatar_id']);
            if(isset($objMedia)){
                $objUser->setAvatar($objMedia);
            }
            if(isset($parametersCollection['password']) && $parametersCollection['password'] != null){
                $encoder = $parametersCollection['container']->get('security.encoder_factory')->getEncoder($objUser);
                $encodedPassword = $encoder->encodePassword($parametersCollection['password'], $objUser->getSalt());
                $objUser->setPassword($encodedPassword);
            }

            $this->em->persist($objUser);
            $this->em->flush();


            return $this->returnResponse(array('success'=>1,'message'=>$message));
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }



    public function returnResponse($parametersCollection){
        return $parametersCollection;
    }

}
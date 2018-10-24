<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\GenericPostTaxonomy;
use AppBundle\Entity\Booking;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\DateTime;

use AppBundle\Entity\GenericPost;
use AppBundle\Entity\GenericPostNomenclature;



class BookingBussiness
{
    private $container;

    public function __construct(EntityManager $em, Container $container = null)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function loadInitialsData($parametersCollection)
    {
        try{
            $initialsData = array();
            $parametersCollection['returnByCustomOrder'] = true;
            $parametersCollection['customOrderField'] = 'published_date';
            $parametersCollection['customOrderSort'] = 'DESC';
            $initialsData['bookingsDataCollection'] = $this->getBookingsList($parametersCollection);

            return $initialsData;
        }
        catch(\Exception $e){
            throw new \Exception($e);
         }
    }

    public function getBookingsList($parametersCollection)
    {
        try{
            if(isset($parametersCollection['getFullTotal']) && $parametersCollection['getFullTotal'] == true){
                return $this->em->getRepository('AppBundle:GenericPost')->getFullTotal($parametersCollection);
            }

            $result = array();
            $parametersCollection['post_type_tree_slug'] = 'booking';
            if(isset($parametersCollection['singleResult'])){
                $parametersCollection['searchByIdsCollection'] = true;
                $idsCollection = array();
                $idsCollection[0] = $parametersCollection['bookingId'];
                $idsCollection = implode(',', $idsCollection);
                $parametersCollection['idsCollection'] = $idsCollection;
                $bookingsCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsFullData($parametersCollection);
            }
            else{
                $bookingsCollection = $this->em->getRepository('AppBundle:GenericPost')->getGenericPostsBasicData($parametersCollection);
            }
            if(isset($bookingsCollection[0])){
                foreach($bookingsCollection as $key=>$booking){
                    $canEdit = 1;
                    $canDelete = 1;
                    $bookingsCollection[$key]['canEdit'] = $canEdit;
                    $bookingsCollection[$key]['canDelete'] = $canDelete;

                    /*handling Post Status*/
                    $objGenericPost = $this->em->getRepository('AppBundle:GenericPost')->find($booking['id']);
                    $objPostStatus = $this->em->getRepository('AppBundle:GenericPostNomenclature')->findOneBy(array(
                        'generic_post' => $objGenericPost,
                        'relation_slug' => 'post_status'
                    ));
                    if(isset($objPostStatus)){
                        $bookingsCollection[$key]['post_status_name'] = $objPostStatus->getNomenclature()->getName();
                        if(isset($parametersCollection['singleResult'])){
                            $bookingsCollection[$key]['post_status_id'] = $objPostStatus->getNomenclature()->getId();
                        }
                    }

                    /*handling dates*/
                    $bookingsCollection[$key]['created_date'] = date_format($booking['created_date'],'d/m/Y');
                    if($booking['modified_date'] != null){
                        $bookingsCollection[$key]['modified_date'] = date_format($booking['modified_date'],'d/m/Y');
                    }
                    if($booking['published_date'] != null){
                        $bookingsCollection[$key]['published_date'] = date_format($booking['published_date'],'d/m/Y');
                    }

                    /*handling Url*/
                    if($this->container == null && isset($parametersCollection['container'])){
                        $this->container = $parametersCollection['container'];
                    }
                    if($this->container != null){
                        $siteDomain = $this->container->get('appbundle_site_settings')->getBncDomain();
                        $bookingsCollection[$key]['url'] = $siteDomain.'/es/reservas/'.$booking['url_slug_'.$parametersCollection['currentLanguage']];
                    }

                    /*handling number of comments*/
                    /*$totalComments = 0;
                    $commentsCollection = $this->em->getRepository('AppBundle:Comment')->findBy(array(
                        'generic_post' => $objGenericPost
                    ));
                    if(isset($commentsCollection[0])){
                        $totalComments = count($commentsCollection);
                    }
                    $newsCollection[$key]['total_comments'] = $totalComments;*/

                    /*handling data for Single Result*/
//                    if(isset($parametersCollection['singleResult'])){
//                        /*handling dates*/
//                        if($booking['modified_date'] != null){
//                            $bookingsCollection[$key]['modified_date'] = date_format($booking['modified_date'],'d/m/Y H:i');
//                        }
//                        if($booking['published_date'] != null){
//                            $bookingsCollection[$key]['published_date'] = date_format($booking['published_date'],'d/m/Y H:i');
//                        }
//                        /*handling Categories*/
//                        $genericPostsId = array();
//                        $genericPostsId[0] = $booking['id'];
//                        $categoriesCollection = $this->em->getRepository('AppBundle:GenericPostTaxonomy')->getGenericPostTaxonomies(array(
//                            'searchByGenericPost' => true,
//                            'genericPostsId' => implode(',', $genericPostsId)
//                        ));
//                        $bookingsCollection[$key]['categoriesCollection'] = $categoriesCollection;
//                    }

                    /*handling data for Booking Object*/
                    $serachParams = array('id'=>$objGenericPost);
                    if(isset($parametersCollection['searchValue'])){
                      $serachParams['searchValue'] = $parametersCollection['searchValue'];
                    }

                    $objBooking = $this->em->getRepository('AppBundle:Booking')->getBookingDataBy($serachParams);
                    if(isset($objBooking[0])){
                      $objBooking = $objBooking[0];
                      $bookingSeats = array();
                      $bookingsCollection[$key]['clientData'] = $objBooking->getClientData();
                      $bookingsCollection[$key]['transaction'] = $objBooking->getTransaction();
                      $bookingsCollection[$key]['date'] = $objBooking->getBookingDate()->format('d/m/Y');
                      $bookingsCollection[$key]['time'] = $objBooking->getBookingDate()->format('h:i a');
                      $bookingsCollection[$key]['amount'] = $objBooking->getTotalAmount();
                      $bookingsCollection[$key]['show'] = $objBooking->getShow()->getTitle();
                      $show = $this->em->getRepository('AppBundle:Show')->find($objBooking->getShow());
                      $bookingsCollection[$key]['hall'] = $show->getRoom()->getTitle();
                      $bookingsCollection[$key]['showDate'] = $show->getShowDate()->format('d/m/Y');
                      $status = $this->em->getRepository('AppBundle:Nomenclature')->find($objBooking->getStatus());
                      $bookingsCollection[$key]['status'] = $status->getName();
                      $showSeats = $this->em->getRepository('AppBundle:ShowSeat')->findBy(array('booking'=>$objGenericPost));
//                      $bookingsCollection[$key]['seats'] = count($showSeats);
                      foreach ($showSeats as $seat) {
                        $seatObj = $this->em->getRepository('AppBundle:Seat')->find($seat->getSeat());
                        $zoneRow = $this->em->getRepository('AppBundle:ZoneRow')->find($seatObj->getZoneRow());
                        $identifier = $zoneRow->getIdentifier() ? $zoneRow->getIdentifier() : $zoneRow->getIdentifierNumber();
                        $bookingSeats[]
                          = $identifier.'-'.$seatObj->getName();
                      }
                      $bookingsCollection[$key]['seats'] = implode(',',$bookingSeats);
                      $result[] = $bookingsCollection[$key];
                    }
                }
            }

            if(isset($parametersCollection['singleResult']) && isset($bookingsCollection[0])){
                return $bookingsCollection[0];
            }
            return $result;
        }
        catch(\Exception $e){
            throw new \Exception($e);
        }
    }



    public function returnResponse($parametersCollection){
        return $parametersCollection;
    }

}
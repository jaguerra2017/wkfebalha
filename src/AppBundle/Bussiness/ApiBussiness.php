<?php

namespace AppBundle\Bussiness;

use AppBundle\Entity\ContentBlockGenericPostItem;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ApiBussiness
{
    private $em;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function checkInvoice($params) {
      $booking = $this->em->getRepository('AppBundle:Booking')->findOneBy(array('transaction'=> $params['id_transaction']));

      if($booking) {
        if($params['notrans']){
          $booking->setNotrans($params['notrans']);
        }
        if($params['codig']){
          $booking->setCodig($params['codig']);
        }

        $showSeats = $this->em->getRepository('AppBundle:ShowSeat')->findBy(array('booking'=>$booking->getId()));
        $bookingSeats = array();
        switch ($params['resultado']) {
          case 'APPROVED':
            $statusBooking = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'finished'));
            $statusSeats = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'selled'));
            $booking->setStatus($statusBooking);
            foreach ($showSeats as $seat) {
              $seatObj = $this->em->getRepository('AppBundle:Seat')->find($seat->getSeat());
              $zoneRow = $this->em->getRepository('AppBundle:ZoneRow')->find($seatObj->getZoneRow());
              $bookingSeats[$zoneRow->getIdentifier() ? $zoneRow->getIdentifier() : $zoneRow->getIdentifierNumber()][]
                = $seatObj->getName();
              $seat->setStatus($statusSeats);
              $seat->setAvailable(0);
              $this->em->persist($seat);
            }

            $sharedFileFinder  = new SharedFileFinderBussiness();
            $mailConfig = $sharedFileFinder->getSettingsFile(array('decode_from_json'=>true,'section'=>'mail'));

            $showObj = $this->em->getRepository('AppBundle:Show')->find($booking->getShow());
            $roomArea = $this->em->getRepository('AppBundle:RoomArea')->findOneBy(array('room'=>$showObj->getRoom()));
            $room = $this->em->getRepository('AppBundle:Room')->find($showObj->getRoom());
            $headquarter = $this->em->getRepository('AppBundle:HeadQuarter')->find($room->getHeadquarter());
	

            if($mailConfig['after_booking_mail_notifications'] == "true"){				
              if($mailConfig['after_booking_mail_voucher'] == "true"){
                $mailParams = array(
                  'subject' => 'Pago online',
                  'from'=> 'noreply@balletcuba.cult.cu',
                  'to'=> $booking->getEmail(),
                  'voucher'=>true,
                  'transaction'=>$booking->getTransaction(),
                  'price'=> $showObj->getSeatPrice(),
                  'date'=>date('d-m-Y'),
                  'theater'=>$showObj->getRoom()->getTitle('es'),
                  'showName'=>$showObj->getId()->getTitle('es'),
                  'showDateTime'=>$showObj->getShowDate(),
                  'area'=>$roomArea->getId()->getTitle('es'),
                  'seats'=>$bookingSeats,
                  'country'=>$booking->getCountryName(),
                  'message' => $mailConfig['after_booking_message_es']
                );
				
                $this->container->get('appbundle_mail')->sendMail($mailParams);
              }

              if($mailConfig['after_booking_mail_theater'] == "true"){
                $mailParams = array(
                  'subject' => 'Pago online',
                  'from'=> 'noreply@balletcuba.cult.cu',
                  'to'=> $headquarter->getEmail(),
                  'voucher'=>($mailConfig['after_booking_mail_voucher'] == "true") ? true:false,
                  'transaction'=>$booking->getTransaction(),
                  'price'=> $showObj->getSeatPrice(),
                  'date'=>date('d-m-Y'),
                  'theater'=>$showObj->getRoom()->getTitle('es'),
                  'showName'=>$showObj->getId()->getTitle('es'),
                  'showDateTime'=>$showObj->getShowDate(),
                  'area'=>$roomArea->getId()->getTitle('es'),
                  'seats'=>$bookingSeats,
                  'country'=>$booking->getCountryName(),
                  'message' => $mailConfig['after_booking_message_es']
                );
                $this->container->get('appbundle_mail')->sendMail($mailParams);
              }

              if ($mailConfig['after_booking_mail_interested_bool'] == "true") {
                $emails = explode(',',$mailConfig['after_booking_mail_interested']);
                foreach ($emails as $email) {
                  $mailParams = array(
                    'subject' => 'Pago online',
                    'from'=> 'noreply@balletcuba.cult.cu',
                    'to'=> $email,
                    'voucher'=>($mailConfig['after_booking_mail_voucher'] == "true") ? true:false,
                    'transaction'=>$booking->getTransaction(),
                    'price'=> $showObj->getSeatPrice(),
                    'date'=>date('d-m-Y'),
                    'theater'=>$showObj->getRoom()->getTitle('es'),
                    'showName'=>$showObj->getId()->getTitle('es'),
                    'showDateTime'=>$showObj->getShowDate(),
                    'area'=>$roomArea->getId()->getTitle('es'),
                    'seats'=>$bookingSeats,
                    'country'=>$booking->getCountryName(),
                    'message' => $mailConfig['after_booking_message_es']
                  );
                  $this->container->get('appbundle_mail')->sendMail($mailParams);

                }

              }
            }


            break;
          default:
            $statusBooking = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'cancelled'));
            $statusSeats = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'avaiable'));
            $booking->setStatus($statusBooking);
            foreach ($showSeats as $seat) {
              $seat->setStatus($statusSeats);
              $seat->setAvailable(1);
              $this->em->persist($seat);
            }
            break;
        }
        $this->em->persist($booking);
        $this->em->flush();

        return $params;
      }
      return 'error';
    }
}
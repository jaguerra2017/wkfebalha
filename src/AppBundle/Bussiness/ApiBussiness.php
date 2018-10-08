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
      $booking = $this->em->getRepository('AppBundle:Booking')->findOneBy(array('transaction'=>$params['id_transaction']));
      if($booking) {
        if($params['notrans']){
          $booking->setNotrans($params['notrans']);
        }
        if($params['codig']){
          $booking->setCodig($params['codig']);
        }
        switch ($params['result']) {
          case 'APPROVED':
            $statusBooking = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'finished'));
            $statusSeats = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'selled'));
            $booking->setStatus($statusBooking);
            foreach ($booking->getSeats() as $seat) {
              $seat->setStatus($statusSeats);
              $seat->setAvailable(0);
              $this->em->persist($seat);
            }
            break;
          default:
            $statusBooking = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'cancelled'));
            $statusSeats = $this->em->getRepository('AppBundle:Nomenclature')->findOneBy(array('tree_slug'=>'avaiable'));
            $booking->setStatus($statusBooking);
            foreach ($booking->getSeats() as $seat) {
              $seat->setStatus($statusSeats);
              $seat->setAvailable(1);
              $this->em->persist($seat);
            }
            break;
        }
        $this->em->persist($booking);
        $this->em->flush();


        $sharedFileFinder  = new SharedFileFinderBussiness();
        $mailConfig = $sharedFileFinder->getSettingsFile(array('decode_from_json'=>true,'section'=>'mail'));

        $mailParams = array(
          'subject' => 'SoyCubano Response',
          'from'=> 'emailsoycubano@gmail.com',
          'to'=> $booking->getEmail(),
          'message' => $mailConfig['after_booking_message']
        );

        $this->container->get('appbundle_mail')->sendMail($mailParams);

        return $params;
      }
      return 'error';
    }
}
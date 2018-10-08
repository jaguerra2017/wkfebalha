<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;


class MailServices
{
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em, \Swift_Mailer $mailer, $templating)
    {
        $this->session = $session;
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /*
     * function to send an e-mail
     */
    public function sendMail($params){
      $message = \Swift_Message::newInstance()
        ->setSubject($params['subject'])
        ->setFrom($params['from'])
        ->setTo($params['to'])
      ;

      if($params['voucher']){
        $message->setBody(
          $this->templating->render(
            '@app_shared_template_directory/voucher.html.twig',
            array('params' => $params)
          )
        );
      }
      else{
        $message->setBody(
          $params['message']
        );
      }

      $this->mailer->send($message);

      return 1;
    }

}
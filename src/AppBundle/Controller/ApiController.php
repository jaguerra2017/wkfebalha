<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 13/09/2018
 * Time: 10:52 PM
 */

namespace AppBundle\Controller;


use AppBundle\Bussiness\ApiBussiness;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
/**
 * Class ApiController
 * @package AppBundle\Controller
 * @Rest\Version("v1")
 */
class ApiController extends FOSRestController
{
  /**
   * @Rest\Get("/check_invoice", options={"expose"=true})
   */
  public function checkInvoiceAction(Request $request)
  {
    $result = array();
    $id_transaccion = $request->get('id_transaccion');
    $notrans = $request->get('notrans');
    $resultado = $request->get('resultado');
    $codig = $request->get('codig');
    $em = $this->getDoctrine()->getManager();
    $apiBussiness = new ApiBussiness($em, $this->container);
    $params = array(
      'id_transaction'=>$id_transaccion,
      'notrans'=>$notrans,
      'resultado'=>$resultado,
      'codig'=>$codig
    );
    $result = $apiBussiness->checkInvoice($params);
    if($result != 'error'){

    }
    return new JsonResponse($result);
  }
}
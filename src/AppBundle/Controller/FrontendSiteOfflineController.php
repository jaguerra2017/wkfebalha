<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * FRONTEND - Site Offline controller.
 *
 */
class FrontendSiteOfflineController extends Controller
{

    /**
     * Return the Site-Offline View Data
     *
     * @Route("/datos-sitio-offline", name="site_offline_view_data", options={"expose"=true})
     * @Method("POST")
     */
    public function siteOfflineViewDataAction()
    {
        $offlineSiteSettings = $this->get('appbundle_site_settings')->getSectionSettingsData('availability');
        $launchDate = $offlineSiteSettings['launch_date'];
        $launchDateCollection = explode('/',$launchDate);
        $launchDate = new \DateTime($launchDateCollection[2].'/'.$launchDateCollection[1].'/'.$launchDateCollection[0]);
        $launchDate = date_format($launchDate,'Y/m/d');
        return new JsonResponse(array(
            'launchDate'=>$launchDate
        ));
    }


}

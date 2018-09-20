<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;


class SlugServices
{
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function slugifyFileName($fileName = null){
        $fileNameSlugified = null;
        if($fileName != null){
            // replace non letter or digits by -
            $fileNameSlugified = preg_replace('~[^\\pL\d]+~u', '-', $fileName);

            // trim
            $fileNameSlugified = trim($fileNameSlugified, '-');

            // transliterate
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $fileNameSlugified);

            // lowercase
            $fileNameSlugified = strtolower($fileNameSlugified);

            // remove unwanted characters
            $fileNameSlugified = preg_replace('~[^-\w]+~', '', $fileNameSlugified);

            if (!empty($fileNameSlugified))
            {
                return $fileNameSlugified;
            }
        }
        return $fileNameSlugified;
    }
}
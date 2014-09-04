<?php

namespace MPK\APIBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;


class StationsController extends FOSRestController
{
    /**
     * @Rest\Get("/get_stations")
     * @Rest\View()
     */
    public function getStationsAction()
    {
        return array("aaa" => 'bbb');
    }

}

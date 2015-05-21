<?php

namespace MPK\APIBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

class StationsController extends FOSRestController
{

    /**
     * @Rest\Get("/stations")
     */
    public function getStationsAction()
    {       
        $stations = $this->get('doctrine_mongodb')->getRepository('MPKAPIBundle:Station')->findAllStations();

        return [
            "stations" => $stations
        ];
    }

    /**
     * @Rest\Get("/stations/{id}")
     * @param string $id
     */
    public function getStationAction($id)
    {
        $station = $this->get('doctrine_mongodb')->getRepository('MPKAPIBundle:Station')->findStation($id);

        return [
            "station" => $station
        ];
        
    }

}

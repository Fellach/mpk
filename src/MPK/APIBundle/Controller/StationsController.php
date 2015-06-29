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
        $stations = $this->get('doctrine_mongodb')->getRepository('MPKAPIBundle:Station')->findAllStationsGrouped();

        return [
            "stations" => $stations
        ];
    }

    /**
     * @Rest\Post("/stations/{id}")
     * @param string $id
     */
    public function getStationAction($id)
    {
        $data = json_decode($this->get('request')->getContent());
        $station = $this->get('doctrine_mongodb')->getRepository('MPKAPIBundle:Station')->findStation($id, $data->date);

        return [
            "station" => $station
        ];
        
    }

}

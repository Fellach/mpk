<?php

namespace MPK\APIBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

class StationsController extends FOSRestController
{

    /**
     * @Rest\Get("/get_stations")
     * desc.: Po lewej mamy liste mozliwych przystankow z wyszukiwarka 
     */
    public function getStationsAction()
    {
        $stations = $this->get('doctrine_mongodb')->getRepository('MPKAPIBundle:Station')->findAllStations();

        return [
            "stations" => $stations
        ];
    }

    /**
     * @Rest\Get("/get_station/{id}")
     * @param string $id
     * desc.: pojawia sie nazwa wybranego przystanku, aktualna godzina oraz 5 najbliższych odjazdów (w formacie Linia | Kierunek | 5 min).
     */
    public function getStationAction($id)
    {
        $station = $this->get('doctrine_mongodb')->getRepository('MPKAPIBundle:Station')->findStation($id);

        return [
            "station" => $station
        ];
        
    }

}

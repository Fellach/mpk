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
        return [
            "stations" => [
                [
                    "id" => "111",
                    "name" => "aaa",
                ],
                [
                    "id" => "222",
                    "name" => "bbb",
                ],
                [
                    "id" => "333",
                    "name" => "ccc",
                ],
            ]
        ];
    }
    
    /**
     * @Rest\Get("/get_station/{id}", requirements={"id"="\d+"})
     * @param type $id
     * desc.: pojawia sie nazwa wybranego przystanku, aktualna godzina oraz 5 najbliższych odjazdów (w formacie Linia | Kierunek | 5 min).
     */
    public function getStationAction($id)
    {
        return [
            "station" => [
                "name" => "name" . $id,
                "time" => new \DateTime(),
                "departures" => [
                    [
                        "line" => "zxc",
                        "direction" => "vbn",
                        "arrival" => 5
                    ],
                    [
                        "line" => "qwe",
                        "direction" => "mbn",
                        "arrival" => 6
                    ],
                    [
                        "line" => "rty",
                        "direction" => "xvc",
                        "arrival" => 7
                    ],
                    [
                        "line" => "yuio",
                        "direction" => "ljk",
                        "arrival" => 8
                    ],
                    [
                        "line" => "pio",
                        "direction" => "jhg",
                        "arrival" => 9
                    ],
                    
                ]
            ]
        ];
    }

}

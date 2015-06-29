<?php

namespace MPK\APIBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

class StationRepository extends DocumentRepository
{
    
    public function findAllStations()
    {
        $stations = $this->createQueryBuilder('Station')
                ->select('id', 'name')
                ->getQuery()
                ->execute();

        $result = [];
        foreach ($stations as $station) {
            $result[] = [
                "name" => $station->getName(),
                "id" => $station->getId(),
            ];
        }
        return $result;
    }
    
    public function findAllStationsGrouped()
    {
        $stations = $this->createQueryBuilder('Station')
                ->select('id', 'name')
                ->getQuery()
                ->execute();

        $result = [];
        foreach ($stations as $station) {
            $result[mb_substr($station->getName(), 0, 1, 'UTF-8')][] = [
                "name" => $station->getName(),
                "id" => $station->getId(),
            ];
        }
        return $result;
    }

    public function findStation($id, $date)
    {
        $date = new \DateTime($date);

        $station = $this->createQueryBuilder('Station')
                ->field('id')->equals($id)
                ->getQuery()
                ->getSingleResult();

        if (!$station) {
            return null;
        }

        $departures = [];
        foreach ($station->getLines() as $line) {
            $this->filterDepartures($line, $date, $departures);
        }
        $departures = array_slice($departures, 0, 5);
        
        return [
            "departures" => $departures,
            "name" => $station->getName(),
            "time" => $date,
        ];
    }

    private function filterDepartures(Line $line, \DateTime $date, &$departures)
    {
        $day = $this->getDayOfWeek($date);

        foreach ($line->getDepartures() as $departure) {
            if ($departure->getDay() === $day) {
                
                $diff = $date->diff(new \DateTime($departure->getDate()->format('H:i')));
                $arrival = ($diff->i + ($diff->h * 60)) * ($diff->invert === 1 ? -1 : 1 );
                
                if ($arrival >= 0){
                    $departures[] = [
                        "line" => $line->getName(),
                        "direction" => $line->getDirection(),
                        "arrival" => $arrival
                    ];
                }
            }
        }

        usort($departures, function($a, $b) {
            return $a["arrival"] <= $b["arrival"] ? -1 : 1;
        });
    }

    private function getDayOfWeek(\DateTime $date)
    {
        switch (date('N', $date->getTimestamp())) {
            case 6:
                return Station::saturday;
            case 7:
                return Station::holiday;
            default:
                return Station::dayweek;
        }
    }

}

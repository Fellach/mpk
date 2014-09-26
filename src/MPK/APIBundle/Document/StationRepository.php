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

    public function findStation($id)
    {
        $date = new \DateTime();

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
            if ($departure->getDay() === $day && $departure->getDate()->getTimestamp() < $date->getTimestamp()) {
                $diff = $date->diff(new \DateTime($departure->getDate()->format('H:i')));
                $departures[] = [
                    "line" => $line->getName(),
                    "direction" => $line->getDirection(),
                    "arrival" => $diff->i + ($diff->h * 60)
                ];
            }
        }

        usort($departures, function($a, $b) {
            return $a["arrival"] <= $b["arrival"] ? -1 : 1;
        });
        $departures = array_slice($departures, 0, 5);
    }

    private function getDayOfWeek(\DateTime $date)
    {
        switch (date('N', $date->getTimestamp())) {
            case 6:
                return 'sobota';
            case 7:
                return 'niedziela';
            default:
                return 'roboczy';
        }
    }

}

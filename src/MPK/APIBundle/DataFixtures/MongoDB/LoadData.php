<?php

namespace MPK\APIBundle\DataFixtures\MongoDB;

class LoadData implements \Doctrine\Common\DataFixtures\FixtureInterface
{

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        //1
        $line = new \MPK\APIBundle\Document\Line();
        $line->setName("110");
        $line->setDirection("last station1");
        
        $date = new \MongoDate(strtotime("17:20"));
        $departure = new \MPK\APIBundle\Document\Departure('roboczy', $date);
        $line->addDeparture($departure);
        $date = new \MongoDate(strtotime("18:20"));
        $departure = new \MPK\APIBundle\Document\Departure('sobota', $date);
        $line->addDeparture($departure);
        $date = new \MongoDate(strtotime("18:20"));
        $departure = new \MPK\APIBundle\Document\Departure('niedziela', $date);
        $line->addDeparture($departure);
        
        $station = new \MPK\APIBundle\Document\Station();
        $station->setName('abc');
        $station->addLine($line);
        
        $manager->persist($station);
        
        //2
        $line = new \MPK\APIBundle\Document\Line();
        $line->setName("110");
        $line->setDirection("last station2");
        
        $date = new \MongoDate(strtotime("11:20"));
        $departure = new \MPK\APIBundle\Document\Departure('roboczy', $date);
        $line->addDeparture($departure);
        $date = new \MongoDate(strtotime("12:20"));
        $departure = new \MPK\APIBundle\Document\Departure('sobota', $date);
        $line->addDeparture($departure);
        $date = new \MongoDate(strtotime("12:20"));
        $departure = new \MPK\APIBundle\Document\Departure('niedziela', $date);
        $line->addDeparture($departure);
        
        $station = new \MPK\APIBundle\Document\Station();
        $station->setName('def');
        $station->addLine($line);
        
        $manager->persist($station);
        
        //3
        $station = new \MPK\APIBundle\Document\Station();
        $station->setName('ghj');
        
        $line = new \MPK\APIBundle\Document\Line();
        $line->setName("220");
        $line->setDirection("last station3");
        
        $date = new \MongoDate(strtotime("21:20"));
        $departure = new \MPK\APIBundle\Document\Departure('roboczy', $date);
        $line->addDeparture($departure);
        $date = new \MongoDate(strtotime("22:20"));
        $departure = new \MPK\APIBundle\Document\Departure('sobota', $date);
        $line->addDeparture($departure);
        $date = new \MongoDate(strtotime("22:20"));
        $departure = new \MPK\APIBundle\Document\Departure('niedziela', $date);
        $line->addDeparture($departure);

        $station->addLine($line);
        
        $line = new \MPK\APIBundle\Document\Line();
        $line->setName("221");
        $line->setDirection("last station4");
        
        $date = new \MongoDate(strtotime("15:20"));
        $departure = new \MPK\APIBundle\Document\Departure('roboczy', $date);
        $line->addDeparture($departure);
        $date = new \MongoDate(strtotime("15:50"));
        $departure = new \MPK\APIBundle\Document\Departure('roboczy', $date);
        $line->addDeparture($departure);
        
        $station->addLine($line);
        
        $manager->persist($station);
        
               
        
        $manager->flush();       
    }

}

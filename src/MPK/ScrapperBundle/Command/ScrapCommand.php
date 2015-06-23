<?php

namespace MPK\ScrapperBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\DomCrawler\Crawler;
use MPK\APIBundle\Document\Station;
use MPK\APIBundle\Document\Line;
use MPK\APIBundle\Document\Departure;

class ScrapCommand extends ContainerAwareCommand
{
    private $input;
    private $output;
    private $uniqueUriDictionary;
    private $baseUri;
    private $client;

    protected function configure()
    {
        $this
            ->setName('mpk:scrap')
            ->setDescription('Crawler and scrapper for rozklady.mpk.krakow.pl')
            ->addOption('talkative', null, InputOption::VALUE_NONE, 'echo lists')
            ->addOption('start', null, InputOption::VALUE_OPTIONAL, 'start from stations with letter')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("start");
        $this->input = $input;
        $this->output = $output;
        $this->baseUri = 'http://rozklady.mpk.krakow.pl/aktualne/';
        $this->uniqueUriDictionary['przystan.htm'] = 'przystan.htm';
        $this->client = $this->getContainer()->get('guzzle.client');
        
        $this->doCommand();

        $output->writeln("finish");
    }
    
    
    private function doCommand()
    {
        /* @var $em \Doctrine\ODM\MongoDB\DocumentManager */
        $em = $this->getContainer()->get('doctrine_mongodb')->getManager();

        $crawler = $this->getCrawler('przystan.htm');

        $stationsUri = $this->getChildrenUri($crawler, 'a[href*="p/"]');

        foreach ($stationsUri as $stationName => $stationUri) {
            if (!empty($this->input->getOption('start')) && preg_match("/[A-" . $this->input->getOption('start') . "]/i", $stationName[0])) {
                continue;
            }
            $crawler = $this->getCrawler($stationUri);
            
            $station = $em->getRepository('MPKAPIBundle:Station')->findOneByName($stationName);
            if (!$station) {
                $station = new Station();
                $station->setName($stationName);
            } else {
                $station->clearLines();
            }

            $linesUri = $this->getChildrenUri($crawler, 'li a[href^="../"]');

            foreach ($linesUri as $lineWithDirection => $lineUri) {

                $lineAndDirection = explode(' - > ', $lineWithDirection);

                $line = new Line();
                $line->setName($lineAndDirection[0]);
                $line->setDirection($lineAndDirection[1]);

                $crawler = $this->getCrawler(str_replace('r', 't', $lineUri));
                try {
                    $crawler->filter('.celldepart > table > tr ')->each(function(Crawler $row, $i) use (&$line) {
                        $this->setDepartures($row, $line);
                    });
                } catch (\Exception $e){
                    $this->output->writeln(sprintf("%s: %s", $lineUri, $e->getMessage()));
                    continue;
                }

                $station->addLine($line);
            }

            $em->persist($station);
            $em->flush();
            
            if ($this->input->getOption('talkative')) {
                $this->output->writeln(sprintf("-----------\nSaved: %s", $stationName));
            }
        }
    }

    /**
     * 
     * @param GuzzleClient $client
     * @param string $uri
     * @return Crawler
     */
    private function getCrawler($uri)
    {
        try {
            return new Crawler(
                    str_replace(
                            '<?xml version="1.0" encoding="iso-8859-2"?>', '', //remove xml-doctype
                            $this->client
                                    ->get($this->baseUri . $uri)
                                    ->send()
                                    ->getBody(true)
                    )
            );
        } catch (\Exception $e) {
            $this->output->writeln(sprintf("%s: %s", $uri, $e->getMessage()));
        }
    }

    /**
     * 
     * @param Crawler $crawler
     * @param string $pattern
     * @return array uri-s
     */
    private function getChildrenUri(Crawler $crawler, $pattern)
    {
        $collection = [];
        $crawler->filter($pattern)->each(function (Crawler $node, $i) use (&$collection) {
            $href = $node->attr('href');
            if (strpos($href, '../') !== false) {
                $href = substr($href, 3);
            }
            if (!isset($this->uniqueUriDictionary[$href])) {
                $collection[$node->text()] = $href;
                $this->uniqueUriDictionary[$href] = null;
            }
        });
        if ($this->input->getOption('talkative')) {
            $this->output->writeln(print_r($collection, true));
        }
        return $collection;
    }

    /**
     * 
     * @param \Symfony\Component\DomCrawler\Crawler $row
     * @param \MPK\APIBundle\Document\Line $line
     */
    private function setDepartures(Crawler $row, Line &$line)
    {
        if ($row->filter('td')->count() === 6) {

            $this->setDeparture(Station::dayweek, 
                    $row->filter('td')->eq(0)->text(), 
                    $row->filter('td')->eq(1)->text(),
                    $line);

            $this->setDeparture(Station::saturday, 
                    $row->filter('td')->eq(2)->text(), 
                    $row->filter('td')->eq(3)->text(), 
                    $line);

            $this->setDeparture(Station::holiday, 
                    $row->filter('td')->eq(4)->text(), 
                    $row->filter('td')->eq(5)->text(), 
                    $line);
        }
        elseif ($row->filter('td')->count() === 2) {
            $this->setDeparture(Station::dayweek, 
                    $row->filter('td')->eq(0)->text(), 
                    $row->filter('td')->eq(1)->text(),
                    $line);
            
            $this->setDeparture(Station::saturday, 
                    $row->filter('td')->eq(0)->text(), 
                    $row->filter('td')->eq(1)->text(),
                    $line);
            
            $this->setDeparture(Station::holiday, 
                    $row->filter('td')->eq(0)->text(), 
                    $row->filter('td')->eq(1)->text(),
                    $line);
        }
    }
    
    private function setDeparture($day, $hour, $minutes, Line &$line)
    {
        $minutes = explode(' ', $minutes);
        foreach ($minutes as $minute) {
            $minute = filter_var($minute, FILTER_SANITIZE_NUMBER_INT);
            if (is_numeric($minute)) {
                $line->addDeparture(new Departure($day, new \DateTime("$hour:$minute")));
            }
        }
    }

}

?>
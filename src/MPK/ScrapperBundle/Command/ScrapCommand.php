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

    private $output;
    private $uniqueUriDictionary;

    protected function configure()
    {
        $this
                ->setName('mpk:scrap')
                ->setDescription('Crawler and scrapper for rozklady.mpk.krakow.pl')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("start");
        $this->output = $output;
        $baseUri = 'http://rozklady.mpk.krakow.pl/aktualne/';
        $this->uniqueUriDictionary['przystan.htm'] = 'przystan.htm';

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $client = $this->getContainer()->get('guzzle.client');

        $crawler = $this->getCrawler($client, $baseUri . 'przystan.htm');

        $stationsUri = $this->getChildrenUri($crawler, 'a[href*="p/"]');

        foreach ($stationsUri as $stationName => $stationUri) {
            $crawler = $this->getCrawler($client, $baseUri . $stationUri);

            $station = new Station();
            $station->setName($stationName);

            $linesUri = $this->getChildrenUri($crawler, 'li a[href^="../"]');

            foreach ($linesUri as $lineWithDirection => $lineUri) {

                $lineAndDirection = explode(' - > ', $lineWithDirection);

                $line = new Line();
                $line->setName($lineAndDirection[0]);
                $line->setDirection($lineAndDirection[1]);

                $crawler = $this->getCrawler($client, $baseUri . str_replace('r', 't', $lineUri));
                $crawler->filter('.celldepart table tr')->each(function($node, $i){
                    
                    /* TODO:
                     * 
                     * filter td.cellhour ->text()
                     * filter td.cellmin  explode ( ->text())
                     * 
                     * line->addDepart (new Departures)
                     * 
                     * for workdays, saturdays, holidays
                     */
                    
                });
                
                $station->addLine($line);
            }

            $em->persist($station);
            //$em->flush();


            break; //to remove
        }

        $output->writeln("finish");
    }

    /**
     * 
     * @param GuzzleClient $client
     * @param string $uri
     * @return Crawler
     */
    private function getCrawler($client, $uri)
    {
        try {
            return new Crawler(
                    $client
                            ->get($uri)
                            ->send()
                            ->getBody(true)
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
    public function getChildrenUri(Crawler $crawler, $pattern)
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
        $this->output->writeln(print_r($collection, true));       
        return $collection;
    }

}
?>

<!--<table border = "1" width = "100%" cellspacing = "4" cellpadding = "0">

    <tr align = "center">
        <td class = "cellday" colspan = "2">
            <b><font class = "fontday" color = "#000000">Dzień powszedni</font></b>
        </td>
        <td class = "cellday" colspan = "2"><b><font class = "fontday" color = "#000000">Soboty</font></b>
        </td>
        <td class = "cellday" colspan = "2"><b><font class = "fontday" color = "#000000">Święta</font></b>
        </td>
    </tr>
    <tr>
        <td class = "cellhour" align = "center" width = "5%"><b><font class = "fonthour">4</b>
        </td>
        <td class = "cellmin" nowrap = "nowrap"><font class = "fontmin"> 26 46</font>
        </td>
        <td class = "cellhour" align = "center" width = "5%"><b><font class = "fonthour">4</b>
        </td>
        <td class = "cellmin" nowrap = "nowrap"><font class = "fontmin"> -</font>
        </td>
        <td class = "cellhour" align = "center" width = "5%"><b><font class = "fonthour">4</b>
        </td>
        <td class = "cellmin" nowrap = "nowrap"><font class = "fontmin"> -</font>
        </td>
    </tr>
   
    <tr>
        <td class = "cellinfo" colspan = "6"><font class = "fontinfo1" color = "#000000">Zakłócenia w ruchu powodują zmiany czasów odjazdów.</font><br/><font class = "fontprzyp">A - Kurs przez: Nad Drwiną</font><br/><b><font class = "fontrozklad">Rozkład jazdy ważny od 22.09.2014 do odwołania</font></b><br/><br/><font class = "fontztm">MPK S.A. w Krakowie</font>
        </td>
    </tr></table>-->

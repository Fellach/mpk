<?php

namespace MPK\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StationsControllerTest extends WebTestCase
{
    public function testGetStations()
    {
        $client = static::createClient();

        $client->request(
                'GET', 
                '/api/get_stations', 
                array(), /* request params */ 
                array(), /* files */
                array('X-Requested-With' => "XMLHttpRequest")
            );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json'),
            $client->getResponse()->headers
        );
    }
}

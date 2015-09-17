<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerControllerTest extends WebTestCase
{
    public function testWrite()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/write');
    }

}

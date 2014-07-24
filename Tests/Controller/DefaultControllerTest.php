<?php

namespace BiberLtd\Cores\BundlesContentManagementBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/test/content_management_bundle');

        $this->assertTrue($crawler->filter('html:contains("Testing Content Management Bundle.")')->count() > 0);
    }
}

<?php

namespace Ecommerce\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{

	public function testIndex()
	{
		$client = static::createClient();

		//Home
		$crawler = $client->request('GET', '/');
		$this->assertTrue($client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\IndexController::indexAction', $client->getRequest()->attributes->get('_controller'));
		$this->assertCount(1, $crawler->filter('h1'));
	}

}

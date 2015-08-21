<?php

namespace Ecommerce\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{

	public function testCreateAccount()
	{
		/*
		  $kernel = static::createKernel();
		  $kernel->boot();
		  $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
		 */

		/*
		  $crawler = $client->followRedirect();
		  $client->followRedirects();
		 */

		$client = static::createClient(array(), array());

		//Home
		$crawler = $client->request('GET', '/');
		$this->assertTrue($client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\IndexController::indexAction', $client->getRequest()->attributes->get('_controller'));
		$this->assertCount(1, $crawler->filter('h1'));

		//Click on Login
		$login_link = $crawler->filter('#login')->link();
		$crawler = $client->click($login_link);

		$this->assertTrue($client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\SecurityController::loginAction', $client->getRequest()->attributes->get('_controller'));

		//Click on Create account
		$create_link = $crawler->filter('#create_account')->link();
		$crawler = $client->click($create_link);

		$this->assertTrue($client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::createAction', $client->getRequest()->attributes->get('_controller'));

		//Formulaire de création de compte
		$form = $crawler->filter('form[name="ecommerce_account_create"]')->form();
		$form['ecommerce_account_create[prefix]'] = 'm';
		$form['ecommerce_account_create[firstname]'] = 'Testfirst';
		$form['ecommerce_account_create[lastname]'] = 'Testlast';
		$form['ecommerce_account_create[email]'] = 'test@email.com';
		$form['ecommerce_account_create[password][first]'] = 'te$tpassword';
		$form['ecommerce_account_create[password][second]'] = 'te$tpassword';
		$client->submit($form);

		$this->assertTrue($client->getResponse()->isRedirect('/account'));
		$crawler = $client->followRedirect();

		//Account index
		$this->assertTrue($client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::indexAction', $client->getRequest()->attributes->get('_controller'));
		$this->assertRegExp('/Testfirst Testlast/', $client->getResponse()->getContent());
	}

	public function testAccount()
	{
		/*
		  $client = static::createClient(array(), array(
		  'PHP_AUTH_USER'	 => 'test@email.com',
		  'PHP_AUTH_PW'	 => 'te$tpassword',
		  ));

		  $crawler = $client->request('GET', '/account');
		  $this->assertTrue($client->getResponse()->isSuccessful());
		  $this->assertEquals('Ecommerce\Controller\AccountController::indexAction', $client->getRequest()->attributes->get('_controller'));
		  $this->assertRegExp('/Testfirst Testlast/', $client->getResponse()->getContent());
		 */
	}

}

<?php

namespace Ecommerce\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{

	protected $client;
	protected $container;
	protected $em;
	protected $login;
	protected $password;

	public function __construct()
	{

	}

	protected function setUp()
	{
		$this->login = 'test@email.com';
		$this->password = 'te$tpassword';

		$this->client = static::createClient(array(), array(
				'PHP_AUTH_USER'	 => $this->login,
				'PHP_AUTH_PW'	 => $this->password,
		));

		$this->container = $this->client->getContainer();
		$this->em = $this->container->get('doctrine.orm.entity_manager');

		$this->client->followRedirects();
	}

	public function testCreateAccount()
	{
		fwrite(STDOUT, __METHOD__ . PHP_EOL);
		$this->markTestSkipped('PHPUnit will skip this test method');

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

		//Create account form
		$form = $crawler->filter('form[name="ecommerce_account_create"]')->form();
		$form['ecommerce_account_create[prefix]'] = 'm';
		$form['ecommerce_account_create[firstname]'] = 'Testfirst';
		$form['ecommerce_account_create[lastname]'] = 'Testlast';
		$form['ecommerce_account_create[email]'] = $this->login;
		$form['ecommerce_account_create[password][first]'] = $this->password;
		$form['ecommerce_account_create[password][second]'] = $this->password;
		$client->submit($form);

		$this->assertTrue($client->getResponse()->isRedirect('/account'));
		$crawler = $client->followRedirect();

		//Account index
		$this->assertTrue($client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::indexAction', $client->getRequest()->attributes->get('_controller'));
		$this->assertRegExp('/Testfirst Testlast/', $client->getResponse()->getContent());
	}

	public function testIndex()
	{
		fwrite(STDOUT, PHP_EOL . __METHOD__ . PHP_EOL);

		$crawler = $this->client->request('GET', '/account');
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::indexAction', $this->client->getRequest()->attributes->get('_controller'));
		$this->assertRegExp('/Testfirst Testlast/', $this->client->getResponse()->getContent());
	}

	public function testEditDetails()
	{
		fwrite(STDOUT, PHP_EOL . __METHOD__ . PHP_EOL);

		$crawler = $this->client->request('GET', '/account/edit/details');
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::editDetailsAction', $this->client->getRequest()->attributes->get('_controller'));

		$user = $this->container->get('security.context')->getToken()->getUser();
		$customer_details_repository = $this->em->getRepository('Ecommerce:CustomerDetails');

		$customer_details = $customer_details_repository->findOneByCustomer($user);
		fwrite(STDOUT, "\t" . 'old firstname:' . $customer_details->getFirstname(). PHP_EOL);

		//Edit details form
		$form = $crawler->filter('form[action="/account/edit/details"]')->form();
		$form['form[prefix]'] = 'f';
		$form['form[firstname]'] = 'new Testfirst';
		$form['form[lastname]'] = 'new Testlast';
		$form['form[birthday][year]'] = '1990';
		$form['form[birthday][month]'] = '1';
		$form['form[birthday][day]'] = '1';
		$this->client->submit($form);

		//Check update
		$customer_details = $customer_details_repository->findOneByCustomer($user);
		fwrite(STDOUT, "\t" . 'new firstname:' . $customer_details->getFirstname(). PHP_EOL);
		$this->assertEquals('new Testfirst', $customer_details->getFirstname());

		//Reset value
		$crawler = $this->client->getCrawler();
		$form = $crawler->filter('form[action="/account/edit/details"]')->form();
		$form['form[prefix]'] = 'm';
		$form['form[firstname]'] = 'Testfirst';
		$form['form[lastname]'] = 'Testlast';
		$this->client->submit($form);

		$this->em->refresh($customer_details);
		fwrite(STDOUT, "\t" . 'reset firstname:' . $customer_details->getFirstname(). PHP_EOL);
		$this->assertEquals('Testfirst', $customer_details->getFirstname());
	}

	public function tearDown()
	{
		$this->client->getKernel()->getContainer()->get('doctrine')->getConnection()->close();
		parent::tearDown();
	}

}

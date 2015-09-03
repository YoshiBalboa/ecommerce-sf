<?php

namespace Ecommerce\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

		$this->client = static::createClient(array(), array());

		$this->container = $this->client->getContainer();
		$this->em = $this->container->get('doctrine.orm.entity_manager');
	}

	public function testCreateAccount()
	{
		fwrite(STDOUT, PHP_EOL . __METHOD__ . PHP_EOL);
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
		$form = $crawler->filter('form[name="e_account_create"]')->form();
		$form['e_account_create[prefix]'] = 'm';
		$form['e_account_create[firstname]'] = 'Testfirst';
		$form['e_account_create[lastname]'] = 'Testlast';
		$form['e_account_create[email]'] = $this->login;
		$form['e_account_create[password][first]'] = $this->password;
		$form['e_account_create[password][second]'] = $this->password;
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

		$this->logIn($this->login, $this->password);

		$crawler = $this->client->request('GET', '/account');
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::indexAction', $this->client->getRequest()->attributes->get('_controller'));
		$this->assertRegExp('/Testfirst Testlast/', $this->client->getResponse()->getContent());
	}

	public function testEditDetails()
	{
		fwrite(STDOUT, PHP_EOL . __METHOD__ . PHP_EOL);

		$this->client->followRedirects();
		$this->logIn($this->login, $this->password);

		$crawler = $this->client->request('GET', '/account/edit/details');
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::editDetailsAction', $this->client->getRequest()->attributes->get('_controller'));

		$user = $this->container->get('security.context')->getToken()->getUser();
		$customer_details_repository = $this->em->getRepository('Ecommerce:CustomerDetails');

		$customer_details = $customer_details_repository->findOneByCustomer($user);
		fwrite(STDOUT, "\t" . 'old firstname:' . $customer_details->getFirstname() . PHP_EOL);

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
		fwrite(STDOUT, "\t" . 'new firstname:' . $customer_details->getFirstname() . PHP_EOL);
		$this->assertEquals('new Testfirst', $customer_details->getFirstname());

		//Reset value
		$crawler = $this->client->getCrawler();
		$form = $crawler->filter('form[action="/account/edit/details"]')->form();
		$form['form[prefix]'] = 'm';
		$form['form[firstname]'] = 'Testfirst';
		$form['form[lastname]'] = 'Testlast';
		$this->client->submit($form);

		$this->em->refresh($customer_details);
		fwrite(STDOUT, "\t" . 'reset firstname:' . $customer_details->getFirstname() . PHP_EOL);
		$this->assertEquals('Testfirst', $customer_details->getFirstname());
	}

	public function testEditEmail()
	{
		fwrite(STDOUT, PHP_EOL . __METHOD__ . PHP_EOL);

		$this->logIn($this->login, $this->password);

		$crawler = $this->client->request('GET', '/account/edit/email');
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::editEmailAction', $this->client->getRequest()->attributes->get('_controller'));

		$user = $this->client->getContainer()->get('security.context')->getToken()->getUser();
		fwrite(STDOUT, "\t" . 'old email:' . $user->getEmail() . PHP_EOL);

		$new_email = 'test+1@email.com';

		//Edit email form
		$form = $crawler->filter('form[action="/account/edit/email"]')->form();
		$form['form[email]'] = $new_email;
		$form['form[password][first]'] = $this->password;
		$form['form[password][second]'] = $this->password;
		$this->client->submit($form);

		//logout
		$this->logOut();
		$this->logIn($new_email, $this->password);

		//Check update
		$user = $this->client->getContainer()->get('security.context')->getToken()->getUser();
		fwrite(STDOUT, "\t" . 'new email:' . $user->getEmail() . PHP_EOL);
		$this->assertEquals($new_email, $user->getEmail());

		//Reset value
		$crawler = $this->client->request('GET', '/account/edit/email');
		$form = $crawler->filter('form[action="/account/edit/email"]')->form();
		$form['form[email]'] = $this->login;
		$form['form[password][first]'] = $this->password;
		$form['form[password][second]'] = $this->password;
		$this->client->submit($form);

		$this->logOut();
		$this->logIn($this->login, $this->password);

		$user = $this->client->getContainer()->get('security.context')->getToken()->getUser();
		fwrite(STDOUT, "\t" . 'reset email:' . $user->getEmail() . PHP_EOL);
		$this->assertEquals($this->login, $user->getEmail());
	}

	public function testEditPassword()
	{
		fwrite(STDOUT, PHP_EOL . __METHOD__ . PHP_EOL);

		$this->logIn($this->login, $this->password);
		$encoder = $this->container->get('security.password_encoder');

		$crawler = $this->client->request('GET', '/account/edit/password');
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::editPasswordAction', $this->client->getRequest()->attributes->get('_controller'));

		$user = $this->client->getContainer()->get('security.context')->getToken()->getUser();
		fwrite(STDOUT, "\t" . 'old password:' . $user->getPassword() . PHP_EOL);
		$this->assertTrue($encoder->isPasswordValid($user, $this->password));

		$new_password = 'newte$tpassword';

		//Edit email form
		$form = $crawler->filter('form[action="/account/edit/password"]')->form();
		$form['form[new_password]'] = $new_password;
		$form['form[password][first]'] = $this->password;
		$form['form[password][second]'] = $this->password;
		$this->client->submit($form);

		//logout
		$this->logOut();
		$this->logIn($this->login, $new_password);

		//Check update
		$user = $this->client->getContainer()->get('security.context')->getToken()->getUser();
		fwrite(STDOUT, "\t" . 'new password:' . $user->getPassword() . PHP_EOL);
		$this->assertTrue($encoder->isPasswordValid($user, $new_password));

		//Reset value
		$crawler = $this->client->request('GET', '/account/edit/password');
		$form = $crawler->filter('form[action="/account/edit/password"]')->form();
		$form['form[new_password]'] = $this->password;
		$form['form[password][first]'] = $new_password;
		$form['form[password][second]'] = $new_password;
		$this->client->submit($form);

		$this->logOut();
		$this->logIn($this->login, $this->password);

		$user = $this->client->getContainer()->get('security.context')->getToken()->getUser();
		fwrite(STDOUT, "\t" . 'reset password:' . $user->getPassword() . PHP_EOL);
		$this->assertTrue($encoder->isPasswordValid($user, $this->password));
	}

	public function testAddresses()
	{
		fwrite(STDOUT, PHP_EOL . __METHOD__ . PHP_EOL);

		$this->logIn($this->login, $this->password);

		$crawler = $this->client->request('GET', '/account/addresses');
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::addressesAction', $this->client->getRequest()->attributes->get('_controller'));

		$address_repository = $this->em->getRepository('Ecommerce:CustomerAddress');
		$user = $this->client->getContainer()->get('security.context')->getToken()->getUser();

		$active_addresses = $address_repository->findBy(
			array('customer' => $user, 'isActive' => TRUE));

		$nb_addresses = $crawler->filter('#account_addresses tbody tr')->count();

		fwrite(STDOUT, "\t" . 'db_addresses: ' . count($active_addresses) . PHP_EOL);
		fwrite(STDOUT, "\t" . 'html_addresses: ' . $nb_addresses . PHP_EOL);
		$this->assertEquals($nb_addresses, count($active_addresses));
	}

	public function tearDown()
	{
		$this->client->getKernel()->getContainer()->get('doctrine')->getConnection()->close();
		parent::tearDown();
	}

	private function logIn($login, $password)
	{
		$session = $this->client->getContainer()->get('session');

		$firewall = 'default';
		$token = new UsernamePasswordToken($login, $password, $firewall, array());
		$session->set('_security_' . $firewall, serialize($token));
		$session->save();

		$cookie = new Cookie($session->getName(), $session->getId());
		$this->client->getCookieJar()->set($cookie);
	}

	private function logOut()
	{
		$session = $this->client->getContainer()->get('session');
		$session->invalidate();
		$session->clear();

		$this->client->restart();
	}

}

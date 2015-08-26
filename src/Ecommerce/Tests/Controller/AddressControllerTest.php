<?php

namespace Ecommerce\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AddressControllerTest extends WebTestCase
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

	public function testCreate()
	{
		fwrite(STDOUT, PHP_EOL . __METHOD__ . PHP_EOL);

		$this->logIn($this->login, $this->password);

		$crawler = $this->client->request('GET', '/address/create');
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AddressController::createAction', $this->client->getRequest()->attributes->get('_controller'));

		$address_repository = $this->em->getRepository('Ecommerce:CustomerAddress');
		$user = $this->client->getContainer()->get('security.context')->getToken()->getUser();

		$active_addresses = $address_repository->findBy(
			array('customer' => $user, 'isActive' => TRUE));

		fwrite(STDOUT, "\t" . 'db_addresses: ' . count($active_addresses) . PHP_EOL);

		//Create address form
		$form = $crawler->filter('form[name="e_address"]')->form();
		$form['e_address[prefix]'] = 'm';
		$form['e_address[firstname]'] = 'Testfirst';
		$form['e_address[lastname]'] = 'Testlast';
		$form['e_address[country]'] = 75; //France country_id
		$form['e_address[state]'] = 'Paris';
		$form['e_address[postcode]'] = '75007';
		$form['e_address[city]'] = 'Paris';
		$form['e_address[street]'] = 'Champ de Mars, 5 Avenue Anatole France';
		$form['e_address[telephone]'] = '0 892 70 12 39';
		$form['e_address[address_id]'] = '';
		$form['e_address[subdivision]'] = 1133; //Paris subdivision_id
		$form['e_address[location]'] = 36176; //Paris location_id
		$this->client->submit($form);

		$this->assertTrue($this->client->getResponse()->isRedirect('/account/addresses'));
		$crawler = $this->client->followRedirect();

		//Account addresses
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::addressesAction', $this->client->getRequest()->attributes->get('_controller'));

		$nb_addresses = $crawler->filter('#account_addresses tbody tr')->count();
		fwrite(STDOUT, "\t" . 'html_addresses: ' . $nb_addresses . PHP_EOL);

		$this->assertEquals($nb_addresses, (count($active_addresses) + 1));
	}

	public function testDelete()
	{
		fwrite(STDOUT, PHP_EOL . __METHOD__ . PHP_EOL);

		$this->logIn($this->login, $this->password);

		$crawler = $this->client->request('GET', '/');

		$address_repository = $this->em->getRepository('Ecommerce:CustomerAddress');
		$user = $this->client->getContainer()->get('security.context')->getToken()->getUser();

		$active_addresses = $address_repository->findBy(
			array('customer' => $user, 'isActive' => TRUE));

		fwrite(STDOUT, "\t" . 'db_addresses: ' . count($active_addresses) . PHP_EOL);

		if(count($active_addresses) <= 0)
		{
			fwrite(STDOUT, "\t" . 'Not enough address' . PHP_EOL);
			$this->markTestSkipped('Not enough address');
		}

		//Address delete
		$address_id = $active_addresses[0]->getAddressId();
		$crawler = $this->client->request('POST', '/address/delete/' . $address_id);
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AddressController::deleteAction', $this->client->getRequest()->attributes->get('_controller'));

		//Account addresses
		$crawler = $this->client->request('GET', '/account/addresses');
		$this->assertTrue($this->client->getResponse()->isSuccessful());
		$this->assertEquals('Ecommerce\Controller\AccountController::addressesAction', $this->client->getRequest()->attributes->get('_controller'));

		$nb_addresses = $crawler->filter('#account_addresses tbody tr')->count();
		fwrite(STDOUT, "\t" . 'html_addresses: ' . $nb_addresses . PHP_EOL);

		$this->assertEquals($nb_addresses, (count($active_addresses) - 1));
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

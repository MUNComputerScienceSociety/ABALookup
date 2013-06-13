<?php

namespace AbaLookupTest\Controller;

use
	AbaLookup\Entity\User,
	PHPUnit_Framework_Exception,
	ReflectionProperty,
	SimpleXMLElement,
	Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase
;

class BaseControllerTestCase extends AbstractHttpControllerTestCase
{
	/**
	 * Common HTTP response codes
	 */
	const HTTP_STATUS_OK = 200;
	const HTTP_STATUS_MOVED_PERMANENTLY = 301;
	const HTTP_STATUS_MOVED_TEMPORARILY = 302;
	const HTTP_STATUS_NOT_FOUND = 404;
	const HTTP_STATUS_SERVER_ERROR = 500;

	/**
	 * Show as much error information as possible
	 */
	protected $traceError = TRUE;

	/**
	 * Return a user for mocking
	 *
	 * Creates and returns an AbaLookup\Entity\User
	 * with the given ID.
	 *
	 * @param integer $id The ID of the user.
	 */
	public function getMockUser($id)
	{
		$user = new User('Jane', 'jane@email.com', 'password', TRUE, 'F', TRUE, TRUE);
		$reflectionProperty = new ReflectionProperty('AbaLookup\Entity\User', 'id');
		$reflectionProperty->setAccessible(TRUE);
		$reflectionProperty->setValue($user, $id);
		return $user;
	}

	/**
	 * Return a mock Doctrine\ORM\EntityRepository
	 *
	 * Creates and returns a mock EntityRepository
	 * and returns {@code $entity} on subsequent
	 * method calls to find entities in the repo.
	 *
	 * @param object $entity The entity to be returned by the EntityManager
	 */
	public function getMockEntityRepository($entity)
	{
		$mock = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
		             ->setMethods(['findOneBy'])
		             ->disableOriginalConstructor()
		             ->getMock();
		$mock->expects($this->any())
		     ->method('findOneBy')
		     ->will($this->returnValue($entity));
		return $mock;
	}

	/**
	 * Return a mock Doctrine\ORM\EntityManager
	 */
	public function getMockEntityManager($user = NULL, $schedule = NULL)
	{
		$mock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
		             ->setMethods(['getRepository', 'persist', 'flush'])
		             ->disableOriginalConstructor()
		             ->getMock();
		$map = [
			['AbaLookup\Entity\User', $this->getMockEntityRepository($user)],
			['AbaLookup\Entity\Schedule', $this->getMockEntityRepository($schedule)],
		];
		$mock->expects($this->any())
		     ->method('getRepository')
		     ->will($this->returnValueMap($map));
		$mock->expects($this->any())
		     ->method('persist')
		     ->will($this->returnValue(NULL));
		$mock->expects($this->any())
		     ->method('flush')
		     ->will($this->returnValue(NULL));
		return $mock;
	}

	/**
	 * Reset the application for isolation
	 */
	public function setUp()
	{
		$this->setApplicationConfig(
			include __DIR__ . '/../../../../../config/application.config.php'
		);
		// super class
		parent::setUp();
	}

	/**
	 * Reset the request but save the server data
	 */
	public function resetRequest()
	{
		// save server datas
		$session = $_SESSION;
		$get     = $_GET;
		$post    = $_POST;
		$cookie  = $_COOKIE;

		// reset server datas
		$this->reset();

		// restore server datas
		$_SESSION = $session;
		$_GET     = $get;
		$_POST    = $post;
		$cookie   = $cookie;

		return $this;
	}

	/**
	 * Assert that the given HTML validates
	 *
	 * Modified <http://git.io/BNxJcA>.
	 *
	 * @param string $html The HTML to validate
	 */
	public function assertValidHtml($html)
	{
		// cURL
		$curl = curl_init();
		curl_setopt_array($curl, [
			// CURLOPT_CONNECTTIMEOUT => 1,
			CURLOPT_URL => 'http://html5.validator.nu/',
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_POST => TRUE,
			CURLOPT_POSTFIELDS => [
				'out' => 'xml',
				'content' => $html,
			],
		]);
		$response = curl_exec($curl);
		if (!$response) {
			$this->markTestIncomplete(
				'Issues checking HTML validity.'
			);
			/*
			throw new PHPUnit_Framework_Exception(
				sprintf('Can\'t check validity. cURL returned error %s.', trigger_error(curl_error($curl)))
			);
			*/
		}
		curl_close($curl);

		// fail if errors
		$xml = new SimpleXMLElement($response);
		$nonDocumentErrors = $xml->{'non-document-error'};
		$errors = $xml->error;
		if (count($nonDocumentErrors) > 0) {
			// indeterminate
			$this->markTestIncomplete();
		} elseif (count($errors) > 0) {
			// invalid
			$this->fail("HTML output did not validate.");
		}

		// valid
		$this->assertTrue(TRUE);
	}
}

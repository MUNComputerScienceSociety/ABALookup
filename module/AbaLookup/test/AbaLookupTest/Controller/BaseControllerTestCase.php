<?php

namespace AbaLookupTest\Controller;

use
	AbaLookup\Entity\Schedule,
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
	const HTTP_STATUS_OK                = 200;
	const HTTP_STATUS_MOVED_PERMANENTLY = 301;
	const HTTP_STATUS_MOVED_TEMPORARILY = 302;
	const HTTP_STATUS_NOT_FOUND         = 404;
	const HTTP_STATUS_SERVER_ERROR      = 500;

	/**
	 * Show as much error information as possible
	 */
	protected $traceError = TRUE;

	/**
	 * Return a mock Doctrine\ORM\EntityRepository
	 *
	 * Mocks and returns a EntityRepository which
	 * returns the same {@code $entity} on subsequent
	 * method calls to find entities in the repo.
	 *
	 * @param object $entity The entity to be returned by the EntityManager.
	 * @return Doctrine\ORM\EntityRepository
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
	 * Mock a Doctrine\ORM\EntityManager
	 *
	 * @param User $user The mock user.
	 * @param Schedule $schedule The schedule for {@code $user}.
	 */
	public function mockEntityManager(User $user, Schedule $schedule)
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

		$serviceManager = $this->getApplicationServiceLocator();
		$serviceManager->setAllowOverride(TRUE);
		$serviceManager->setService('doctrine.entitymanager.orm_default', $mock);
	}

	/**
	 * Return a mock user
	 *
	 * Mocks and returns an AbaLookup\Entity\User.
	 *
	 * @return User
	 */
	public function mockUser()
	{
		// mock user
		$user = new User('Jane', 'jane@email.com', 'password', TRUE, 'F', TRUE, TRUE);
		$reflectionProperty = new ReflectionProperty('AbaLookup\Entity\User', 'id');
		$reflectionProperty->setAccessible(TRUE);
		$reflectionProperty->setValue($user, 1);

		// mock entity manager
		$this->mockEntityManager($user, new Schedule($user));

		return $user;
	}

	/**
	 * Reset the application for isolation
	 */
	public function setUp()
	{
		$this->setApplicationConfig(
			include __DIR__ . '/../../../../../config/application.config.php'
		);
		parent::setUp();
	}

	/**
	 * Assert that the given HTML markup validates
	 *
	 * Modified from <http://git.io/BNxJcA>.
	 *
	 * @param string $html The HTML markup to validate.
	 */
	public function assertValidHtml($html)
	{
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => 'http://html5.validator.nu/',
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_POST => TRUE,
			CURLOPT_POSTFIELDS => [
				'out' => 'xml',
				'content' => $html,
			]
		]);
		$response = curl_exec($curl);
		if (!$response) {
			$this->markTestSkipped('HTML validity cannot be checked');
		}
		curl_close($curl);

		$xml = new SimpleXMLElement($response);
		$errors = $xml->error;
		$this->assertTrue(count($errors) == 0, 'The HTML output contains validation errors');
	}
}

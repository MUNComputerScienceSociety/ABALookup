<?php

namespace AbaLookupTest;

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
	 * Returns a mock Doctrine\ORM\EntityRepository
	 *
	 * Mocks and returns a EntityRepository which
	 * returns the same {@code $entity} on subsequent
	 * method calls to find entities in the repo.
	 *
	 * @param object $entity The entity to be returned by the EntityRepository.
	 * @return Doctrine\ORM\EntityRepository
	 */
	public function getMockObjectRepository($entity)
	{
		$mock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
		             ->disableOriginalConstructor()
		             ->setMethods([
		                   'findOneBy',
		               ])
		             ->getMock();
		// Calling 'find' or 'findOneBy' will return the passed in entity
		$mock->expects($this->any())
		     ->method('find')
		     ->will($this->returnValue($entity));
		$mock->expects($this->any())
		     ->method('findOneBy')
		     ->will($this->returnValue($entity));
		// Return the mock object
		return $mock;
	}

	/**
	 * Mocks a Doctrine\ORM\EntityManager
	 *
	 * @param User $user The mock user.
	 * @param Schedule $schedule The schedule for {@code $user}.
	 */
	public function mockEntityManager(User $user, Schedule $schedule)
	{
		$mockEntityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
		                          ->setMethods([
		                                'find',
		                                'persist',
		                                'flush',
		                                'getRepository',
		                            ])
		                          ->disableOriginalConstructor()
		                          ->getMock();
		// Return value map for the mock entity manager
		$map = [
			['AbaLookup\Entity\User', $this->getMockObjectRepository($user)],
			['AbaLookup\Entity\Schedule', $this->getMockObjectRepository($schedule)],
		];
		// Mock entity manager
		$mockEntityManager->expects($this->any())
		                  ->method('getRepository')
		                  ->will($this->returnValueMap($map));
		// Override the real EM with the mock object
		$serviceLocator = $this->getApplicationServiceLocator();
		$serviceLocator->setAllowOverride(TRUE);
		$serviceLocator->setService('doctrine.entitymanager.orm_default', $mockEntityManager);
	}

	/**
	 * Returns a mock user
	 *
	 * Mocks and returns an AbaLookup\Entity\User.
	 *
	 * @return User
	 */
	public function mockUser()
	{
		// Mock user
		$user = new User('Jane', 'jane@email.com', 'password', TRUE, 'F', TRUE, TRUE);
		$reflectionProperty = new ReflectionProperty('AbaLookup\Entity\User', 'id');
		$reflectionProperty->setAccessible(TRUE);
		$reflectionProperty->setValue($user, 1);
		// Mock Entity Manager
		$this->mockEntityManager($user, new Schedule($user));
		return $user;
	}

	/**
	 * Resets the application for isolation
	 */
	public function setUp()
	{
		$this->setApplicationConfig(
			include realpath(sprintf('%s/../../../../config/application.config.php', __DIR__))
		);
		parent::setUp();
	}

	/**
	 * Asserts that the given HTML markup validates
	 *
	 * Modified from <http://git.io/BNxJcA>.
	 *
	 * @param string $markup The HTML markup to validate.
	 */
	public function assertValidHtml($markup)
	{
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => 'http://html5.validator.nu/',
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_POST => TRUE,
			CURLOPT_POSTFIELDS => [
				'out' => 'xml',
				'content' => $markup,
			]
		]);
		$response = curl_exec($curl);
		if (!$response) {
			$this->markTestSkipped('HTML validity cannot be checked');
		}
		curl_close($curl);
		// Create a simple XML object from the response
		// And assert that 0 errors exist
		$xml = new SimpleXMLElement($response);
		$this->assertTrue(count($xml->error) == 0, 'The HTML output contains validation errors');
	}
}

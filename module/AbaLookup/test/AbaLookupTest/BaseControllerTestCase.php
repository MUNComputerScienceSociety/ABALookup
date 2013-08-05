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
	 * Returns a mock Doctrine\Common\Persistence\ObjectRepository
	 *
	 * Mocks and returns a ObjectRepository which
	 * returns the same {@code $object} on subsequent
	 * method calls to find entities in the repo.
	 *
	 * @param object $object The object to be returned by the ObjectRepository.
	 * @return Doctrine\Common\Persistence\ObjectRepository
	 */
	public function getMockObjectRepository($object)
	{
		$mock = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')
		             ->disableOriginalConstructor()
		             ->setMethods([
		                   'find',
		                   'findAll',
		                   'findBy',
		                   'findOneBy',
		                   'getClassName'
		               ])
		             ->getMock();

		$mock->expects($this->any())
		     ->method('find')
		     ->will($this->returnValue($object));
		$mock->expects($this->any())
		     ->method('findOneBy')
		     ->will($this->returnValue($object));

		return $mock;
	}

	/**
	 * Mocks a Doctrine\Common\Persistence\ObjectManager
	 *
	 * @param User $user The mock user.
	 * @param Schedule $schedule The schedule for {@code $user}.
	 */
	public function mockObjectManager(User $user, Schedule $schedule)
	{
		$mockObjectManager = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
		                          ->setMethods([
		                                'find',
		                                'persist',
		                                'remove',
		                                'merge',
		                                'clear',
		                                'detach',
		                                'refresh',
		                                'flush',
		                                'getRepository',
		                                'getClassMetadata',
		                                'getMetadataFactory',
		                                'initializeObject',
		                                'contains'
		                            ])
		                          ->disableOriginalConstructor()
		                          ->getMock();

		$map = [
			['AbaLookup\Entity\User', $this->getMockObjectRepository($user)],
			['AbaLookup\Entity\Schedule', $this->getMockObjectRepository($schedule)],
		];

		$mockObjectManager->expects($this->any())
		                  ->method('getRepository')
		                  ->will($this->returnValueMap($map));

		$serviceLocator = $this->getApplicationServiceLocator();
		$serviceLocator->setAllowOverride(TRUE);
		$serviceLocator->setService('doctrine.entitymanager.orm_default', $mockObjectManager);
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
		// mock user
		$user = new User('Jane', 'jane@email.com', 'password', TRUE, 'F', TRUE, TRUE);
		$reflectionProperty = new ReflectionProperty('AbaLookup\Entity\User', 'id');
		$reflectionProperty->setAccessible(TRUE);
		$reflectionProperty->setValue($user, 1);

		// mock Object Manager
		$this->mockObjectManager($user, new Schedule($user));

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

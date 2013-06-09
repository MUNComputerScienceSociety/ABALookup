<?php

namespace AbaLookupTest\Controller;

use
	Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase
;

class BaseControllerTestCase extends AbstractHttpControllerTestCase
{
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
	 * HTTP response codes
	 */
	const HTTP_OK = 200;
}

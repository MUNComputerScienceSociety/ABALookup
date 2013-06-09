<?php

namespace AbaLookupTest\Controller;

/**
 * Test the UsersController
 */
class UsersControllerTest extends BaseControllerTestCase
{
	/**
	 * Return an array of actions to be tested
	 */
	public function actions()
	{
		return [
			['/users/register', 'auth'],
			['/users/login', 'auth'],
		];
	}
	/**
	 * Ensure the actions for the UsersController can be accessed
	 *
	 * @dataProvider actions
	 */
	public function testActionsCanBeAccessed($url, $route)
	{
		$this->dispatch($url);
		$this->assertResponseStatusCode(self::HTTP_OK);
		$this->assertModuleName('AbaLookup');
		$this->assertControllerName('AbaLookup\Controller\Users');
		$this->assertControllerClass('UsersController');
		$this->assertMatchedRouteName($route);
	}
}

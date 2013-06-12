<?php

namespace AbaLookupTest\Controller;

/**
 * Test the HomeController
 */
class HomeControllerTest extends BaseControllerTestCase
{
	/**
	 * Return an array of actions to be tested
	 */
	public function actions()
	{
		return [
			['/', 'home'],
			['/about', 'about'],
			['/privacy', 'privacy'],
			['/terms', 'terms'],
		];
	}
	/**
	 * Ensure the actions for the HomeController can be accessed
	 *
	 * @dataProvider actions
	 */
	public function testActionsCanBeAccessed($url, $route)
	{
		$this->dispatch($url);
		$this->assertResponseStatusCode(self::HTTP_STATUS_OK);
		$this->assertModuleName('AbaLookup');
		$this->assertControllerName('AbaLookup\Controller\Home');
		$this->assertControllerClass('HomeController');
		$this->assertMatchedRouteName($route);
	}
}

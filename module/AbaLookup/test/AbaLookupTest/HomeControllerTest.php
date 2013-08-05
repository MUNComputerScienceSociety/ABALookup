<?php

namespace AbaLookupTest;

/**
 * Test the HomeController
 */
class HomeControllerTest extends BaseControllerTestCase
{
	/**
	 * Returns an array of actions to be tested
	 */
	public function homeActions()
	{
		return [
			['/', 'home'],
			['/about', 'about'],
			['/privacy', 'privacy'],
			['/terms', 'terms'],
		];
	}

	/**
	 * Ensures the actions for the HomeController can be accessed and contain valid HTML
	 *
	 * @requires extension curl
	 * @dataProvider homeActions
	 */
	public function testActionsCanBeAccessedAndContainValidHtml($url, $route)
	{
		$this->dispatch($url);
		$this->assertResponseStatusCode(self::HTTP_STATUS_OK);
		$this->assertModuleName('AbaLookup');
		$this->assertControllerName('Home');
		$this->assertControllerClass('HomeController');
		$this->assertMatchedRouteName($route);
		$this->assertValidHtml($this->getResponse()->getContent());
	}
}

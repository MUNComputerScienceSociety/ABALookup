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
	 * Ensure the actions for the HomeController can be accessed
	 *
	 * @requires extension curl
	 * @dataProvider homeActions
	 */
	public function testActionsCanBeAccessedAndAreValid($url, $route)
	{
		$this->dispatch($url);
		$this->assertResponseStatusCode(self::HTTP_STATUS_OK);
		$this->assertModuleName('AbaLookup');
		$this->assertControllerName('AbaLookup\Controller\Home');
		$this->assertControllerClass('HomeController');
		$this->assertMatchedRouteName($route);
		// is the output valid?
		$html = $this->getResponse()->getContent();
		$this->assertValidHtml($html);
	}
}

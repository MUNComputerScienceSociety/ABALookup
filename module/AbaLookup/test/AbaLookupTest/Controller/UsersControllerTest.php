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
	public function authActions()
	{
		return [
			['/users/register', 'auth'],
			['/users/login', 'auth'],
		];
	}

	/**
	 * Return an array of actions to be tested
	 */
	public function usersActions()
	{
		return [
			['/users/42/profile', 'users'],
			['/users/42/schedule', 'users'],
			['/users/42/matches', 'users'],
		];
	}

	/**
	 * Ensure the authentication actions for the UsersController can be accessed
	 *
	 * @dataProvider authActions
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

	/**
	 * Certain URLs should redirect to the login page
	 *
	 * A user attempting to view their profile, schedule, or matches without
	 * logging in first should redirect them to the login page.
	 *
	 * @dataProvider usersActions
	 */
	public function testRedirectsToLoginPage($url, $route)
	{
		$this->dispatch($url);
		$this->assertRedirectTo('/users/login');
	}

	/**
	 * Ensure that a user can register
	 */
	public function testRegister()
	{
		// TODO
		$this->markTestIncomplete();
	}

	/**
	 * Ensure that a user can login
	 */
	public function testLogin()
	{
		// TODO
		$this->markTestIncomplete();
	}

	/**
	 * Edit user profiles
	 */
	public function testEditProfile()
	{
		// TODO
		$this->markTestIncomplete();
	}
}

<?php

namespace AbaLookupTest;

use
	AbaLookup\Entity\Schedule,
	AbaLookup\Form\LoginForm
;

/**
 * Test the UsersController
 */
class UsersControllerTest extends BaseControllerTestCase
{
	/**
	 * Returns an array of actions to be tested
	 */
	public function authActions()
	{
		return [
			['/users/register', 'auth'],
			['/users/login', 'auth'],
		];
	}

	/**
	 * Returns an array of actions to be tested
	 */
	public function usersActions()
	{
		return [
			['/users/1/profile', 'users'],
			['/users/1/schedule', 'users'],
			['/users/1/matches', 'users'],
		];
	}

	/**
	 * Ensures the authentication actions for UsersController contains valid HTML
	 *
	 * @requires extension curl
	 * @dataProvider authActions
	 */
	public function testAuthActionsContainValidHtml($url, $route)
	{
		$this->dispatch($url);
		$this->assertResponseStatusCode(self::HTTP_STATUS_OK);
		$this->assertModuleName('AbaLookup');
		$this->assertControllerName('Users');
		$this->assertControllerClass('UsersController');
		$this->assertMatchedRouteName($route);
		$this->assertValidHtml($this->getResponse()->getContent());
	}

	/**
	 * Tests that certain URLs should redirect to the login page
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
	 * Ensures that a user can login
	 *
	 * @requires extension curl
	 */
	public function testUserCanLogin()
	{
		$user = $this->mockUser();

		$data = [
			LoginForm::ELEMENT_NAME_EMAIL_ADDRESS => $user->getEmail(),
			LoginForm::ELEMENT_NAME_PASSWORD => 'password',
			LoginForm::ELEMENT_NAME_REMEMBER_ME => '0'
		];

		$this->dispatch('/users/login', 'POST', $data);
		$this->assertModuleName('AbaLookup');
		$this->assertControllerName('Users');
		$this->assertControllerClass('UsersController');
		$this->assertMatchedRouteName('auth');
		$this->assertRedirectTo('/users' . '/' . $user->getId() . '/profile');

		return $_SESSION;
	}

	/**
	 * Ensures that once logged in a user can access their pages
	 *
	 * @dataProvider usersActions
	 * @depends testUserCanLogin
	 */
	public function testLoggedInUserCanAccessPages($url, $route, $session)
	{
		$_SESSION = $session;
		$this->mockUser();

		$this->dispatch($url);
		$this->assertResponseStatusCode(self::HTTP_STATUS_OK);
		$this->assertModuleName('AbaLookup');
		$this->assertControllerName('Users');
		$this->assertControllerClass('UsersController');
		$this->assertMatchedRouteName($route);
		$this->assertValidHtml($this->getResponse()->getContent());
	}
}

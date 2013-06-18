<?php

namespace AbaLookupTest\Controller;

use AbaLookup\Entity\Schedule;

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
	public function testAuthenticationActionsCanBeAccessed($url, $route)
	{
		$this->dispatch($url);
		$this->assertResponseStatusCode(self::HTTP_STATUS_OK);
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
	 * Ensure that a user can login and access all actions
	 *
	 * @dataProvider usersActions
	 */
	public function testLoginAndAccessUsersActions($url, $route)
	{
		// the ID of the user attempting to login
		$id = 42;
		// the user about to login
		$user = $this->getMockUser($id);
		$schedule = new Schedule($user);

		// mock the EntityManager
		$serviceManager = $this->getApplicationServiceLocator();
		$serviceManager->setAllowOverride(true);
		$mockEntityManager = $this->getMockEntityManager($user, $schedule);
		$serviceManager->setService('doctrine.entitymanager.orm_default', $mockEntityManager);

		// POST data
		$data = [
			'email-address' => $user->getEmail(),
			'password' => 'password',
		];

		// can users login?
		$this->dispatch('/users/login', 'POST', $data);
		$this->assertModuleName('AbaLookup');
		$this->assertControllerName('AbaLookup\Controller\Users');
		$this->assertControllerClass('UsersController');
		$this->assertRedirectTo("/users/{$id}/profile");

		// reset
		$this->resetRequest();

		// re-mock the EntityManager post reset
		$serviceManager = $this->getApplicationServiceLocator();
		$serviceManager->setAllowOverride(true);
		$mockEntityManager = $this->getMockEntityManager($user, $schedule);
		$serviceManager->setService('doctrine.entitymanager.orm_default', $mockEntityManager);

		// can users access pages?
		$this->dispatch($url);
		$this->assertResponseStatusCode(self::HTTP_STATUS_OK);
		$this->assertModuleName('AbaLookup');
		$this->assertControllerName('AbaLookup\Controller\Users');
		$this->assertControllerClass('UsersController');
		$this->assertMatchedRouteName($route);
	}
}

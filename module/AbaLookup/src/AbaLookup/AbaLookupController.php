<?php

namespace AbaLookup;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

abstract class AbaLookupController extends AbstractActionController
{
	const EVENT_PRIORITY_BEFORE_ACTION = 100;

	/**
	 * Set the event manager instance used by this context
	 *
	 * @param EventManagerInterface $eventManager
	 * @return AbstractController
	 */
	public function setEventManager(EventManagerInterface $eventManager)
	{
		parent::setEventManager($eventManager);
		// Attach callable to run before each controller action
		$eventManager->attach(
			MvcEvent::EVENT_DISPATCH,
			[$this, 'action'],
			self::EVENT_PRIORITY_BEFORE_ACTION
		);
	}

	/**
	 * Returns the API object for the given name
	 *
	 * Gets from the API factory the appropriate API and returns
	 * an instance.
	 *
	 * @param string $name The name of the API class.
	 * @return mixed
	 */
	protected function getApi($name)
	{
		// TODO - Does the API factory itself throw an exception?
		$factory = $this->getServiceLocator()->get('Lookup\Api\Factory');
		return $factory->get($name);
	}

	/**
	 * @return ViewModel Redirect to the home route.
	 */
	protected function redirectHome()
	{
		return $this->redirect()->toRoute('home');
	}

	/**
	 * @return ViewModel Redirect to the login route.
	 */
	protected function redirectToLoginPage()
	{
		return $this->redirect()->toRoute('auth/login');
	}

	/**
	 * Redirects to the users route given a user ID and an action
	 *
	 * @param int $id The user ID.
	 * @param string $action The route action.
	 * @return ViewModel Redirect to the users route.
	 */
	protected function redirectToUsersRoute($id, $action = 'profile')
	{
		$params = ['id' => $id, 'action' => $action];
		return $this->redirect()->toRoute('users', $params);
	}

	/**
	 * Prepares the layout to be displayed for the given user
	 *
	 * Nests the footer widget into the layout, and attaches the current
	 * user to the layout as a variable.
	 *
	 * @param Lookup\Entity\User $user The user in session.
	 * @return void
	 */
	protected function prepareLayout(Lookup\Entity\User $user = NULL)
	{
		// Add the footer template
		$layout = $this->layout();
		$footer = new ViewModel();
		$footer->setTemplate('widget/footer');
		$layout->addChild($footer, 'footer');
		if (is_null($user)) {
			return;
		}
		// Attach the user to the view
		$layout->setVariable('user', $user);
	}
}

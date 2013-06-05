<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace AbaLookup\Controller;

use
	AbaLookup\AbaLookupController,
	Zend\View\Model\ViewModel
;

class HomeController extends AbaLookupController
{
	private function action() {
		// the profile of the use currently in session
		$profile;
		// use the 'home' layout
		$this->layout('layout/home');
		// add as a child view the footer template
		$layout = $this->layout();
		$footer = new ViewModel();
		$footer->setTemplate('widget/footer');
		$layout->addChild($footer, 'footer');
		// if user has logged in:
		// set their profile  URL
		if ($this->loggedIn()) {
			$profile = $this->currentUser();
			$userId = $profile->getId();
			$profile->url = "/users/{$userId}/profile";
			$layout->profile = $profile;
		}
		return array('profile' => $profile);
	}
	public function indexAction() {
		return $this->action();
	}
	public function aboutAction() {
		return $this->action();
	}
	public function privacyAction() {
		return $this->action();
	}
	public function termsAction() {
		return $this->action();
	}
}

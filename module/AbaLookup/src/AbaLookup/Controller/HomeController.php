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

class HomeController extends AbaLookupController {
	public function indexAction() {
		if ($this->loggedIn()) {
			$profile = $this->currentUser();
			$userId = $profile->getId();
			$profile->url = "/users/{$userId}/profile";
			$this->layout('layout/home');
			$layout = $this->layout();
			$layout->profile = $profile;
		}
		else {
			$this->layout('layout/home');
		}
		return new ViewModel();
	}
	public function aboutAction() {
		$this->layout('layout/home');
		return new ViewModel();
	}
	public function privacyAction() {
		$this->layout('layout/home');
		return new ViewModel();
	}
	public function termsAction() {
		$this->layout('layout/home');
		return new ViewModel();
	}
}

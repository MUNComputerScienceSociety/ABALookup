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
	AbaLookup\AbaLookupController
;

class HomeController extends AbaLookupController {
	private function returnArray() {
		$profile;
		$this->layout('layout/home');
		$layout = $this->layout();
		if ($this->loggedIn()) {
			$profile = $this->currentUser();
			$userId = $profile->getId();
			$profile->url = "/users/{$userId}/profile";
			$layout->profile = $profile;
		}
		return array(
			'profile' => $profile
		);
	}
	public function indexAction() {
		return $this->returnArray();
	}
	public function aboutAction() {
		return $this->returnArray();
	}
	public function privacyAction() {
		return $this->returnArray();
	}
	public function termsAction() {
		return $this->returnArray();
	}
}

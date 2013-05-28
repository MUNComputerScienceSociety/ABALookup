<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ABALookup\Controller;

use
	Zend\View\Model\ViewModel,
	ABALookup\ABALookupController
;

class HomeController extends ABALookupController {

	public function indexAction() {
		$this->layout('layout/layout_home');
		return new ViewModel();
	}

	public function aboutusAction() {
		return new ViewModel();
	}

	public function privacypolicyAction() {
		$this->layout('layout/layout_logged_out');
		return new ViewModel();
	}

	public function termsofuseAction() {
		$this->layout('layout/layout_logged_out');
		return new ViewModel();
	}
}

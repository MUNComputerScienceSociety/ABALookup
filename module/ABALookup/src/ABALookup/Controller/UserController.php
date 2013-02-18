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
	ABALookup\ABALookupController,
	ABALookup\Entity\User
;

class UserController extends ABALookupController {
	public function registerAction() {
		return new ViewModel();
	}
	public function loginAction() {
		return new ViewModel();
	}
	public function logoutAction() {
		return new ViewModel();
	}
	public function resetpasswordAction() {
		return new ViewModel();
	}
	public function verifyuserAction() {
		return new ViewModel();
	}
}

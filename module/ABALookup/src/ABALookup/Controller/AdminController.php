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

class AdminController extends ABALookupController {
	public function indexAction() {
		return new ViewModel();
	}
	public function confirmuserAction() {
		return new ViewModel();
	}
	public function deleteuserAction() {
		return new ViewModel();
	}
	public function resetpasswordAction() {
		return new ViewModel();
	}
	public function listusersAction() {
		return new ViewModel();
	}
	public function addadminAction() {
		return new ViewModel();
	}
	public function removeadminAction() {
		return new ViewModel();
	}
}

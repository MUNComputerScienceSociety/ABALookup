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

class TherapistProfileController extends ABALookupController {
	public function editAction() {
		return new ViewModel();
	}
	public function viewAction() {
		return new ViewModel();
	}
	public function contactAction() {
		return new ViewModel();
	}
}

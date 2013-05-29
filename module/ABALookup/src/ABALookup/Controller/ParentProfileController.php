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

class ParentProfileController extends AbaLookupController {

	public function indexAction() {
		if (!$this->loggedIn()) return $this->redirect()->toRoute('user', array('action' => 'login'));

		$profile = $this->currentUser();
		if (isset($_POST["edit"])) {
			$profile->setDisplayName($_POST["username"]);
			$this->getEntityManager()->persist($profile);
			$this->getEntityManager()->flush();

			return new ViewModel(array('profile' => $profile, 'confirm' => 'Your profile has been updated.'));
		}

		return new ViewModel(array('profile' => $profile));
	}

}

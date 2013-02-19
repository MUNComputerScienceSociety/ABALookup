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
        if (!$this->requiresAdmin()) return $this->redirect()->toRoute('user', array('action' => 'login'));

        $list = $this->getEntityManager()->getRepository('ABALookup\Entity\User');
        if (isset($_REQUEST['email'])) $list = $list->findBy(array('email' => $_REQUEST['email']));
        else $list = $list->findBy(array());

        return new ViewModel(array('model' => $list));
	}

	public function deleteAction() {
        return new ViewModel();
    }

    public function changepasswordAction() {
        return new ViewModel();
    }

    public function confirmAction() {
        return new ViewModel();
    }

    public function moderatorAction() {
        return new ViewModel();
    }

    public function unmoderatorAction() {
        return new ViewModel();
    }




	private function getUserByEmail($email) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('email' => $email));
    }

    private function getUserById($id) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('id' => $id));
    }

    private function requiresAdmin() {
        if ($this->loggedIn())
            if ($this->currentUser()->getModerator())
                return true;
        return false;
    }
}

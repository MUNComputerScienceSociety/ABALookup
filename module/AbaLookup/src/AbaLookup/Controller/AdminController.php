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

class AdminController extends AbaLookupController {

	public function indexAction() {
	//    if (!$this->requiresAdmin()) return $this->redirect()->toRoute('user', array('action' => 'login'));

		$list = $this->getEntityManager()->getRepository('AbaLookup\Entity\User');
		if (isset($_REQUEST['email'])) $list = $list->findBy(array('email' => $_REQUEST['email']));
		else $list = $list->findBy(array());

		return new ViewModel(array('model' => $list));
	}

	public function deleteAction() {
		if (!$this->requiresAdmin()) return $this->redirect()->toRoute('user', array('action' => 'login'));

		if (isset($_POST['submit'])) {
			$user_to_delete = $this->getUserById($_Post['user_id']);
			if (!$user_to_delete) {
				return new ViewModel(array(
					'error' => 'The user selected does not exist.'
				));
			}
			$this->getEntityManager()->remove($user_to_delete);

		}

		return new ViewModel();
	}

	public function changepasswordAction() {
		return new ViewModel();
	}

	public function confirmAction() {
		if (!$this->requiresAdmin()) return $this->redirect()->toRoute('user', array('action' => 'login'));

		if (isset($_POST['submit'])) {
			$user_to_confirm = $this->getUserById($_POST['user_id']);
			if (!$user_to_confirm) {
				return new ViewModel(array(
					'error' => 'The user selected does not exist.'
				));
			}
			$user_to_confirm->setVerified($verified);
		}
		return new ViewModel();
	}

	public function moderatorAction() {
		if (!$this->requiresAdmin()) return $this->redirect()->toRoute('user', array('action' => 'login'));

		if (isset($_Post['submit'])) {
			$user_to_moderator = $this->getUserById($_Post['user_id']);
			if (!$user_to_moderator) {
				return new ViewModel(array(
					'error' => 'The user selected does not exist.'
				));
			}
			$user_to_moderator->setModerator(true);
		}

		return new ViewModel();
	}

	public function unmoderatorAction() {
		$user_to_unmoderator = $this->getUserById($_Post['user_id']);
		if (isset($_Post['submit'])) {
			if (!$user_to_moderator) {
				return new ViewModel(array(
					'error' => 'The user selected does not exist.'
				));
			}
		}
		$user_to_unmoderator->setModerator(false);

		return new ViewModel();
	}

	private function getUserByEmail($email) {
		return $this->getEntityManager()->getRepository('AbaLookup\Entity\User')->findOneBy(array('email' => $email));
	}

	private function getUserById($id) {
		return $this->getEntityManager()->getRepository('AbaLookup\Entity\User')->findOneBy(array('id' => $id));
	}

	private function requiresAdmin() {
		if ($this->loggedIn())
			if ($this->currentUser()->getModerator())
				return true;
		return false;
	}
}

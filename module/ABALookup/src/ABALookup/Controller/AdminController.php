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
		#A check to see that the user is both logged in and has moderator status.
		if (!$this->loggedIn() || !$this->currentUser()->getModerator()) 
			return $this->redirect()->toRoute('home-index');
			
		return new ViewModel();	
	}
	public function confirmuserAction() {
		#A check to see that the user is both logged in and has moderator status.
		if (!$this->loggedIn() || !$this->currentUser()->getModerator()) 
			return $this->redirect()->toRoute('home-index');	
	
		return new ViewModel();
	}
	public function deleteuserAction() {
		#A check to see that the user is both logged in and has moderator status.
		if (!$this->loggedIn() || !$this->currentUser()->getModerator()) 
			return $this->redirect()->toRoute('home-index');
			
		if (isset ($_POST['submit'])) {
			$user_to_delete_id = $_POST['user_id'];
			
			$user_to_delete = $this_.getUserById($user_to_delete_id);
			if (!$user_to_delete) {
				return new ViewModel(array(
					'error' => 'The selected user does not exist.'
				));
			}	
			else {
				$this->getEntityManager()->remove($user_to_delete);
				
				###Confirmation???###
			}
		}
			
		return new ViewModel();
	}
	public function resetpasswordAction() {
		#A check to see that the user is both logged in and has moderator status.
		if (!$this->loggedIn() || !$this->currentUser()->getModerator()) 
			return $this->redirect()->toRoute('home-index');
			
		return new ViewModel();
	}
	public function listusersAction() {
		#A check to see that the user is both logged in and has moderator status.
		if (!$this->loggedIn() || !$this->currentUser()->getModerator()) 
			return $this->redirect()->toRoute('home-index');
	
		return new ViewModel();
	}
	public function addadminAction() {
		#A check to see that the user is both logged in and has moderator status.
		if (!$this->loggedIn() || !$this->currentUser()->getModerator()) 
			return $this->redirect()->toRoute('home-index');	
	
		return new ViewModel();
	}
	public function removeadminAction() {
		#A check to see that the user is both logged in and has moderator status.
		if (!$this->loggedIn() || !$this->currentUser()->getModerator()) 
			return $this->redirect()->toRoute('home-index');	
	
		return new ViewModel();
	}
	
	private function getUserByEmail($email) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('email' => $email));
    }

    private function getUserById($id) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('id' => $id));
    }
}

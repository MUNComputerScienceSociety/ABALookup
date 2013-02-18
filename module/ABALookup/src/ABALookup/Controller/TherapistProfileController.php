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
	Zend\View\Model\ViewModel;
	ABALookup\ABALookupController;
	Zend\Session\Container;
	ABALookup\Entity\User;

class TherapistProfileController extends ABALookupController {
	public function editAction() {
	
		if (isset(_POST['edit'])) {
		
		return new ViewModel();
	}
	public function viewAction() {
	
        if (loggedIn())
        $id->currentUser();
        $email->getEmailById($id);
        $sex->getSexById($id);
        $code_of_conduct->getCodeOfConductById($id);
        $aba_course->getAbaCourseById($id);
        $verified->getVerifiedById($id);
		return new ViewModel(array(
		"username" => $id,
		"email" => $email,
		"sex" => $sex,
		"code_of_conduct" => $code_of_conduct,
		"aba_course" => $aba_course,
		"verified" => $verified,
		));
	}
	public function contactAction() {
		return new ViewModel();
	}
	
	private function getEmailById($id) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('email' => $id));
    }
    private function getSexById($id) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('sex' => $id));
    }
    private function getCodeOfConductById($id) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('code_of_conduct' => $id));
    }
    private function getAbaCourseById($id) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('aba_course' => $id));
    }
    private function getVerifiedById($id) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('verified' => $id));
    }
}

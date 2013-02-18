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
	Zend\Session\Container
;

class TherapistProfileController extends ABALookupController {
	public function editAction() {
		return new ViewModel();
	}
	public function viewAction() {
	
		$session = new Container();
        if ($session->offsetExists('loggedIn'))
        $id->offsetGet('id');
        $user->getUserById($id);
        $email->getEmailById($id);
        $sex->getSexById($id);
        $code_of_conduct->getCodeOfConductById($id);
        $aba_course->getAbaCourseById($id);
        $verified->getVerifiedById($id);
		return new ViewModel(array(
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
	
	private function getUserById($id) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('id' => $id));
    }
}

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

        if (isset ($_POST['submit'])) {
            $usertype = $_POST['usertype'];
            $username = $_POST['username'];
            $emailaddress = $_POST['emailaddress'];
            $password = $_POST['password'];
            $confirmpassword = $_POST['confirmpassword'];

            if (empty($usertype) || empty($username) || empty($emailaddress) ||
                empty($password) || empty($confirmpassword)) {
                return new ViewModel(array(
                    "error" => "All fields are required",
                    "usertype" => $usertype,
                    "username" => $username,
                    "emailaddress" => $emailaddress,
                ));
            }

            if ($confirmpassword != $password) {
                return new ViewModel(array(
                    "error" => "Your passwords do not match",
                    "usertype" => $usertype,
                    "username" => $username,
                    "emailaddress" => $emailaddress,
                ));
            }

            if (strlen($password) < 6) {
                return new ViewModel(array(
                    "error" => "Your password must be at least 6 characters in length",
                    "usertype" => $usertype,
                    "username" => $username,
                    "emailaddress" => $emailaddress,
                ));
            }
        }

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
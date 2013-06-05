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
	AbaLookup\Configuration\Mail as MailConfig,
	AbaLookup\Entity\User,
	Zend\Crypt\Password\Bcrypt,
	Zend\Mail,
	Zend\Session\Container,
	Zend\View\Model\ViewModel
;

class UsersController extends AbaLookupController {
	public function registerAction() {
		$this->layout('layout/logged-out');
		if (isset ($_POST['submit'])) {
			$userType        = $_POST['user-type'];
			$username        = $_POST['username'];
			$emailAddress    = $_POST['email-address'];
			$password        = $_POST['password'];
			$confirmPassword = $_POST['confirm-password'];
			if (empty($userType)
			    || empty($username)
			    || empty($emailAddress)
			    || empty($password)
			    || empty($confirmPassword)
			) {
				return new ViewModel(array(
					"error" => "All fields are required.",
					"usertype" => $userType,
					"username" => $username,
					"emailAddress" => $emailAddress,
				));
			}
			if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
				return new ViewModel(array(
					"error" => "A valid email address is required.",
					"usertype" => $userType,
					"username" => $username,
					"emailAddress" => $emailAddress,
				));
			}
			if ($confirmPassword != $password) {
				return new ViewModel(array(
					"error" => "Your passwords do not match.",
					"usertype" => $userType,
					"username" => $username,
					"emailaddress" => $emailAddress,
				));
			}
			if (strlen($password) < 6) {
				return new ViewModel(array(
					"error" => "Your password must be at least 6 characters in length.",
					"usertype" => $userType,
					"username" => $username,
					"emailaddress" => $emailAddress,
				));
			}
			if ($this->getUserByEmail($emailAddress)) {
				return new ViewModel(array(
					"error" => "This email address is already in use.",
					"usertype" => $userType,
					"username" => $username,
					"emailaddress" => $emailAddress,
				));
			}
			$em = $this->getEntityManager();
			$bcrypt = new Bcrypt();
			/*
			$mailConfig = $this->serviceLocator->get("AbaLookup\Configuration\Mail");
			$mailTransport = new Mail\Transport\Sendmail();
			*/
			$user = new User($emailAddress, $bcrypt->create($password), ($userType === "therapist"), "", "", false, false, $username);
			$user->setVerified(true);
			$em->persist($user);
			$em->flush();
			/*
			$verificationUrl = $mailConfig->getUrl() . "/user/verifyuser/" . $user->getId() . "/" . $this->makeVerificationHash($user);
			$message = new Mail\Message();
			$message->addFrom($mailConfig->getMailFrom(), $mailConfig->getMailFromName());
			$message->addTo($user->getEmail());
			$message->setBody(str_replace("{URL}", $verificationUrl, $mailConfig->getVerificationMessage()));
			$message->setSubject($mailConfig->getVerificationSubject());
			$mailTransport->send($message);
			*/
			$session = new Container();
			$session->offsetSet("loggedIn", $user->getId());
			return $this->redirect()->toRoute('users', array(
				'id'     => $user->getId(),
				'action' => 'profile',
			));
		}
		return new ViewModel();
	}
	/*
	public function registerSuccessAction() {
		$this->layout('layout/logged-out');
		return new ViewModel();
	}
	*/
	public function loginAction() {
		$this->layout('layout/logged-out');
		if (isset($_POST['login'])) {
			$bcrypt = new Bcrypt();
			$emailAddress = $_POST['email-address'];
			$password = $_POST['password'];
			$user = $this->getUserByEmail($emailAddress);
			if ((!$user) || (!$bcrypt->verify($password, $user->getPassword()))) {
				return new ViewModel(array(
					'error' => 'The email you entered was incorrect or the password did not match.'
				));
			}
			/*
			if (!$user->getVerified()) {
				return new ViewModel(array(
					'error' => 'You need to verify your email to login.'
				));
			}
			*/
			$session = new Container();
			$session->offsetSet('loggedIn', $user->getId());
			return $this->redirect()->toRoute('users', array(
				'id'     => $user->getId(),
				'action' => 'profile',
			));
		}
		return new ViewModel();
	}
	public function logoutAction() {
		$session = new Container();
		if ($session->offsetExists('loggedIn')) {
			$session->offsetUnset('loggedIn');
		}
		return $this->redirect()->toRoute('home');
	}
	public function resetPasswordAction() {
		if (isset($_POST['email-address'])) {
			$email = $_POST['email-address'];
			$user = $this->getUserByEmail($email);
			if (!$user) {
				return new ViewModel(array(
					'error' => "The email you are using was not found in the database."
				));
			}
			/*
			$mailConfig = $this->serviceLocator->get("AbaLookup\Configuration\Mail");
			$mailTransport = new Mail\Transport\Sendmail();
			$resetUrl = $mailConfig->getUrl() . "/user/updatepassword/" . $user->getId() . "/" . $this->makeResetPasswordHash($user);
			$message = new Mail\Message();
			$message->addFrom($mailConfig->getMailFrom(), $mailConfig->getMailFromName());
			$message->addTo($user->getEmail());
			$message->setBody(str_replace("{URL}", $resetUrl, $mailConfig->getResetPasswordMessage()));
			$message->setSubject($mailConfig->getResetPasswordSubject());
			$mailTransport->send($message);
			*/
			return $this->redirect()->toRoute('user', array('action' => 'login'));
		}
		return new ViewModel();
	}
	/*
	public function resetPasswordSuccessAction() {
		return new ViewModel();
	}
	*/
	public function updatePasswordAction() {
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$verification = $this->getEvent()->getRouteMatch()->getParam('verification');
		$user = $this->getUserById($id);
		if ($verification && $this->makeVerificationHash($user)) {
			if (isset($_POST['newpassword']) && isset($_POST['confirmpassword']) ) {
				$newpassword = $_POST['newpassword'];
				$confirmpassword = $_POST['confirmpassword'];
				if (strlen($newpassword) < 6) {
					return new ViewModel(array(
						'id' => $id,
						'verification' => $verification,
						'error' => "Your password must be at least 6 characters."
					));
				}
				if ($newpassword == $confirmpassword) {
					$bcrypt = new Bcrypt();
					$user->setPassword($bcrypt->create($newpassword));
					$this->getEntityManager()->persist($user);
					$this->getEntityManager()->flush();
					$session = new Container();
					$session->offsetSet("loggedIn", $user->getId());
					if ($user->getTherapist()) {
						return $this->redirect()->toRoute('therapist');
					}
					else {
						return $this->redirect()->toRoute('parent');
					}
				}
				return new ViewModel(array(
					'id' => $id,
					'verification' => $verification,
					'error' => "Your passwords did not match."
				));
			}
			return new ViewModel(array(
				'id' => $id,
				'verification' => $verification
			));
		}
		return $this->redirect()->toRoute('user', array('action' => 'updatePasswordError'));
	}
	public function updatePasswordErrorAction() {
		return new ViewModel();
	}
	public function verifyUserAction() {
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$verification = $this->getEvent()->getRouteMatch()->getParam('verification');
		$user = $this->getUserById($id);
		if ($user) {
			if ($this->makeVerificationHash($user) == $verification) {
				$user->setVerified(true);
				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();
				$session = new Container();
				$session->offsetSet("loggedIn", $user->getId());
				if ($user->getTherapist()) {
					return $this->redirect()->toRoute('therapist-profile');
				}
				else {
					return $this->redirect()->toRoute('parent-profile');
				}
			}
		}
		return new ViewModel();
	}
	public function changePasswordAction() {
		if (isset($_POST["submit"])) {
			$password = $_POST["password"];
			$newpassword = $_POST["newpassword"];
			$confirmpassword = $_POST["confirmpassword"];
			if ($newpassword != $confirmpassword) {
				return new ViewModel(array('error' => 'Your new passwords do not match.'));
			}
			$bcrypt = new Bcrypt();
			$user = $this->currentUser();
			if (!$bcrypt->verify($password, $user->getPassword())) {
				return new ViewModel(array('error' => 'Your current password does not match.'));
			}
			$user->setPassword($bcrypt->create($newpassword));
			$this->getEntityManager()->persist($user);
			$this->getEntityManager()->flush();
			return new ViewModel(array('error' => 'Your password has been updated'));
		}
		return new ViewModel();
	}
	public function profileAction() {
		if (!$this->loggedIn()) {
			return $this->redirect()->toRoute('auth', array('action' => 'login'));
		}
		$profile = $this->currentUser();
		$userId = $profile->getId();
		$profile->url = "/users/{$userId}";
		$layout = $this->layout();
		$footer = new ViewModel();
		$footer->setTemplate('widget/footer');
		$layout->addChild($footer, 'footer');
		$layout->profile = $profile;
		return new ViewModel(array(
			'profile' => $profile
		));
	}
	public function scheduleAction() {
		if (!$this->loggedIn()) {
			return $this->redirect()->toRoute('auth', array('action' => 'login'));
		}
		$profile = $this->currentUser();
		$userId = $profile->getId();
		$profile->url = "/users/{$userId}";
		$layout = $this->layout();
		$footer = new ViewModel();
		$footer->setTemplate('widget/footer');
		$layout->addChild($footer, 'footer');
		$layout->profile = $profile;
		return new ViewModel(array(
			'profile' => $profile
		));
	}
	public function matchesAction() {
		if (!$this->loggedIn()) {
			return $this->redirect()->toRoute('auth', array('action' => 'login'));
		}
		$profile = $this->currentUser();
		$userId = $profile->getId();
		$profile->url = "/users/{$userId}";
		$layout = $this->layout();
		$footer = new ViewModel();
		$footer->setTemplate('widget/footer');
		$layout->addChild($footer, 'footer');
		$layout->profile = $profile;
		return new ViewModel(array(
			'profile' => $profile
		));
	}
	private function getUserByEmail($email) {
		return $this->getEntityManager()->getRepository('AbaLookup\Entity\User')->findOneBy(array('email' => $email));
	}
	private function getUserById($id) {
		return $this->getEntityManager()->getRepository('AbaLookup\Entity\User')->findOneBy(array('id' => $id));
	}
	private function makeVerificationHash($user) {
		return substr(hash('sha512', '!!!VerificationHash$' . $user->getEmail() . $user->getPassword()), -10);
	}
	private function makeResetPasswordHash($user) {
		return substr(hash('sha512', '%&@#^(jwgwetiyQ%Kgw$' . $user->getEmail() . $user->getPassword()), -10);
	}
}

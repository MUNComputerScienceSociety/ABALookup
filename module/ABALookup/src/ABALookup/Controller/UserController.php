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
	ABALookup\Entity\User,
    Zend\Crypt\Password\Bcrypt,
    Zend\Session\Container,
    Zend\Mail,
    ABALookup\Configuration\Mail as MailConfig
;

class UserController extends ABALookupController {

    public function registerAction() {
        $this->layout('layout/layout_logged_out');

        if (isset ($_POST['submit'])) {
            $usertype = $_POST['usertype'];
            $username = $_POST['username'];
            $emailaddress = $_POST['emailaddress'];
            $password = $_POST['password'];
            $confirmpassword = $_POST['confirmpassword'];

            if (empty($usertype) || empty($username) || empty($emailaddress) || empty($password) || empty($confirmpassword)) {
                return new ViewModel(array(
                    "error" => "All fields are required",
                    "usertype" => $usertype,
                    "username" => $username,
                    "emailaddress" => $emailaddress,
                ));
            }

            /*
            if (!filter_var($emailaddress, FILTER_VALIDATE_EMAIL)) {
                return new ViewModel(array(
                    "error" => "A valid email address is required",
                    "usertype" => $usertype,
                    "username" => $username,
                    "emailaddress" => $emailaddress,
                ));
            }
            */

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

            if ($this->getUserByEmail($emailaddress)) {
                return new ViewModel(array(
                    "error" => "This email is already in use.",
                    "usertype" => $usertype,
                    "username" => $username,
                    "emailaddress" => $emailaddress,
                ));
            }

            $em = $this->getEntityManager();
            $bcrypt = new Bcrypt();
            $mailConfig = $this->serviceLocator->get("ABALookup\Configuration\Mail");
            $mailTransport = new Mail\Transport\Sendmail();

            $user = new User($emailaddress, $bcrypt->create($password), ($usertype == "therapist"), "", "", false, false, $username);
            $em->persist($user);
            $em->flush();

            $verificationUrl = $mailConfig->getUrl() . "/user/verifyuser?id=" . $user->getId() . "&verification=" . $this->makeVerificationHash($user);

            $message = new Mail\Message();
            $message->addFrom($mailConfig->getMailFrom(), $mailConfig->getMailFromName());
            $message->addTo($user->getEmail());
            $message->setBody(str_replace("{URL}", $verificationUrl, $mailConfig->getVerificationMessage()));
            $message->setSubject($mailConfig->getVerificationSubject());
            $mailTransport->send($message);

            return $this->redirect()->toRoute('user', array('action' => 'registersuccess'));
        }

        return new ViewModel();
	}

    public function registersuccessAction() {
        $this->layout('layout/layout_logged_out');
        return new ViewModel();
    }

	public function loginAction() {
        $this->layout('layout/layout_logged_out');
        if (isset($_POST['login'])) {
            $bcrypt = new Bcrypt();

            $emailaddress = $_POST['emailaddress'];
            $password = $_POST['password'];

            $user = $this->getUserByEmail($emailaddress);

            if ((!$user) || (!$bcrypt->verify($password, $user->getPassword()))) {
                return new ViewModel(array(
                    'error' => 'The email you entered was incorrect or the password did not match'
                ));
            }

            if (!$user->getVerified()) {
                return new ViewModel(array(
                    'error' => 'You need to verify your email to login'
                ));
            }

            $session = new Container();
            $session->offsetSet('loggedIn', $user->getId());


            if ($user->getTherapist())
                return $this->redirect()->toRoute('therapist-profile');
            else
                return $this->redirect()->toRoute('parent-profile');
        }
		return new ViewModel();
	}

    public function logoutAction() {
        $session = new Container();
        if ($session->offsetExists('loggedIn')) $session->offsetUnset('loggedIn');
        return $this->redirect()->toRoute('home-index');
	}

	public function resetpasswordAction() {
        if (isset($_POST['emailaddress'])) {
            $email = $_POST['emailaddress'];
            $user = $this->getUserByEmail($email);

            if (!$user) {
                return new ViewModel(array('error' => "The email you are using was not found in the database."));
            }

            $mailConfig = $this->serviceLocator->get("ABALookup\Configuration\Mail");
            $mailTransport = new Mail\Transport\Sendmail();
            $resetUrl = $mailConfig->getUrl() . "/user/updatepassword?id=" . $user->getId()
                . "&verification=" . $this->makeResetPasswordHash($user);

            $message = new Mail\Message();
            $message->addFrom($mailConfig->getMailFrom(), $mailConfig->getMailFromName());
            $message->addTo($user->getEmail());
            $message->setBody(str_replace("{URL}", $resetUrl, $mailConfig->getResetPasswordMessage()));
            $message->setSubject($mailConfig->getResetPasswordSubject());
            $mailTransport->send($message);

            return $this->redirect()->toRoute('user', array('action' => 'resetpasswordsuccess'));
        }
		return new ViewModel();
	}

    public function resetpasswordsuccessAction() {
        return new ViewModel();
    }

    public function updatepasswordAction() {
        if (isset($_REQUEST['id']) && isset($_REQUEST['verification'])) {
            $id = $_REQUEST['id'];
            $verification = $_REQUEST['verification'];
            $user = $this->getUserById($id);

            if ($verification && $this->makeVerificationHash($user)) {
                    if (isset($_POST['newpassword']) && isset($_POST['confirmpassword']) ) {
                        $newpassword = $_POST['newpassword'];
                        $confirmpassword = $_POST['confirmpassword'];

                        if (strlen($newpassword) < 6) {
                            return new ViewModel(array(
                                'id' => $id,
                                'verification' => $verification,
                                'error' => "Your password must be at least 6 characters"
                            ));
                        }

                        if ($newpassword == $confirmpassword) {
                            $bcrypt = new Bcrypt();
                            $user->setPassword($bcrypt->create($newpassword));
                            $this->getEntityManager()->persist($user);
                            $this->getEntityManager()->flush();

                            $session = new Container();
                            $session->offsetSet("loggedIn", $user->getId());

                            if ($user->getTherapist())
                                return $this->redirect()->toRoute('therapist-profile');
                            else
                                return $this->redirect()->toRoute('parent-profile');
                        }
                        return new ViewModel(array(
                            'id' => $id,
                            'verification' => $verification,
                            'error' => "Your passwords did not match"
                        ));
                    }
                    return new ViewModel(array(
                        'id' => $id,
                        'verification' => $verification
                    ));
                }
        }
        return $this->redirect()->toRoute('user', array('action' => 'updatepassworderror'));
    }

    public function updatepassworderrorAction() {
        return new ViewModel();
    }

	public function verifyuserAction() {
        $id = $_REQUEST['id'];
        $verification = $_REQUEST['verification'];
        $user = $this->getUserById($id);
        die($id . " " . $verification);
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

    public function changepasswordAction() {
        if (isset($_POST["submit"])) {

            $password = $_POST["password"];
            $newpassword = $_POST["newpassword"];
            $confirmpassword = $_POST["confirmpassword"];

            if ($newpassword != $confirmpassword)
                return new ViewModel(array('error' => 'Your new passwords do not match.'));

            $bcrypt = new Bcrypt();
            $user = $this->currentUser();

            if (!$bcrypt->verify($password, $user->getPassword()))
                return new ViewModel(array('error' => 'Your current password does not match.'));

            $user->setPassword($bcrypt->create($newpassword));
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
            return new ViewModel(array('error' => 'Your password has been updated'));
        }
        return new ViewModel();
    }

    private function getUserByEmail($email) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('email' => $email));
    }

    private function getUserById($id) {
        return $this->getEntityManager()->getRepository('ABALookup\Entity\User')->findOneBy(array('id' => $id));
    }

    private function makeVerificationHash($user) {
        return substr(hash('sha512', '!!!VerificationHash$' . $user->getEmail() . $user->getPassword()), -10);
    }

    private function makeResetPasswordHash($user) {
        return substr(hash('sha512', '%&@#^(jwgwetiyQ%Kgw$' . $user->getEmail() . $user->getPassword()), -10);
    }
}

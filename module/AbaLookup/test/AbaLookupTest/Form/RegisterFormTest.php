<?php

namespace AbaLookupTest;

use
	AbaLookup\Form\RegisterForm,
	PHPUnit_FrameWork_TestCase
;

class RegisterFormTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Form\RegisterForm
	 */
	protected $form;

	protected function generateData($displayName,
	                                $emailAddress,
	                                $password,
	                                $confirmPassword,
	                                $phoneNumber   = '7095551234',
	                                $userType      = '0',
	                                $sex           = NULL,
	                                $abaCourse     = FALSE,
	                                $codeOfConduct = FALSE
	) {
		return [
			RegisterForm::ELEMENT_NAME_DISPLAY_NAME => $displayName,
			RegisterForm::ELEMENT_NAME_EMAIL_ADDRESS => $emailAddress,
			RegisterForm::ELEMENT_NAME_PASSWORD => $password,
			RegisterForm::ELEMENT_NAME_CONFIRM_PASSWORD => $confirmPassword,
			RegisterForm::ELEMENT_NAME_PHONE_NUMBER => $phoneNumber,
			RegisterForm::ELEMENT_NAME_USER_TYPE => $userType,
			RegisterForm::ELEMENT_NAME_SEX => $sex,
			RegisterForm::ELEMENT_NAME_ABA_COURSE => $abaCourse,
			RegisterForm::ELEMENT_NAME_CODE_OF_CONDUCT => $codeOfConduct
		];
	}

	public function setUp()
	{
		$this->form = new RegisterForm();
	}

	/**
	 * @expectedException Zend\Form\Exception\DomainException
	 */
	public function testExceptionIsThrownWhenValidatingFormWithoutData()
	{
		$this->form->isValid();
	}

	public function testValidDataDoesValidate()
	{
		$displayName = 'Jane Doe';
		$emailAddress = 'jdoe@email.com';
		$password = 'password';
		$phoneNumber = '7095551234';
		$userType = '0'; // parent
		$sex = 'F';
		$data = $this->generateData(
			$displayName,
			$emailAddress,
			$password,
			$password,
			$phoneNumber,
			$userType,
			$sex
		);
		$this->form->setData($data);
		$this->assertTrue($this->form->isValid());

		$user = $this->form->getUser();
		$this->assertInstanceOf('AbaLookup\Entity\User', $user);
		$this->assertEquals($displayName, $user->getDisplayName());
		$this->assertEquals($emailAddress, $user->getEmail());
		$this->assertTrue($user->verifyPassword($password));
		$this->assertFalse($user->getTherapist());
		$this->assertTrue(((int) $phoneNumber) === $user->getPhone());
		$this->assertEquals($sex, $user->getSex());
	}

	public function testNullDisplayNameDoesNotValidate()
	{
		$data = $this->generateData(
			NULL,
			'jdoe@email.com',
			'password',
			'password'
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testEmptyDisplayNameDoesNotValidate()
	{
		$data = $this->generateData(
			'',
			'jdoe@email.com',
			'password',
			'password'
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testDisplayNameFilledWithSpacesDoesNotValidate()
	{
		$data = $this->generateData(
			'     ',
			'jdoe@email.com',
			'password',
			'password'
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testEmptyEmailAddressDoesNotValidate()
	{
		$data = $this->generateData(
			'John Doe',
			'',
			'password',
			'password'
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullEmailAddressDoesNotValidate()
	{
		$data = $this->generateData(
			'John Doe',
			NULL,
			'password',
			'password'
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testPasswordTooShortDoesNotValidate()
	{
		$data = $this->generateData(
			'John Doe',
			'jdoe@email.com',
			'pass',
			'pass'
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testNotConfirmingPasswordDoesNotValidate()
	{
		$data = $this->generateData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'not the same password'
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullPasswordDoesNotValidate()
	{
		$data = $this->generateData(
			'John Doe',
			'jdoe@email.com',
			NULL,
			NULL
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testInvalidPhoneNumberDoesNotValidate()
	{
		$data = $this->generateData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'000'
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullUserTypeDoesNotValidate()
	{
		$data = $this->generateData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'7095551234',
			NULL
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testUndisclosedSexDoesValidate()
	{
		$data = $this->generateData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'7095551234',
			'1',
			'Undisclosed'
		);
		$this->form->setData($data);
		$this->assertTrue($this->form->isValid());
	}

	public function testNullSexDoesNotValidate()
	{
		$data = $this->generateData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'7095551234',
			'1',
			NULL
		);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}
}

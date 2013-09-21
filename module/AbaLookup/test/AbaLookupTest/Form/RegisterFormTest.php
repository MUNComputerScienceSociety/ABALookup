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

	/**
	 * Set the form data
	 */
	protected function setFormData($displayName,
	                               $emailAddress           = 'jdoe@email.com',
	                               $password               = 'password',
	                               $confirmPassword        = 'password',
	                               $phoneNumber            = '7095551234',
	                               $userType               = '0',
	                               $sex                    = NULL,
	                               $abaCourse              = FALSE,
	                               $certificateOfConduct   = FALSE
	) {
		$this->form->setData([
			RegisterForm::ELEMENT_NAME_DISPLAY_NAME            => $displayName,
			RegisterForm::ELEMENT_NAME_EMAIL_ADDRESS           => $emailAddress,
			RegisterForm::ELEMENT_NAME_PASSWORD                => $password,
			RegisterForm::ELEMENT_NAME_CONFIRM_PASSWORD        => $confirmPassword,
			RegisterForm::ELEMENT_NAME_PHONE_NUMBER            => $phoneNumber,
			RegisterForm::ELEMENT_NAME_USER_TYPE               => $userType,
			RegisterForm::ELEMENT_NAME_SEX                     => $sex,
			RegisterForm::ELEMENT_NAME_ABA_COURSE              => $abaCourse,
			RegisterForm::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT  => $certificateOfConduct
		]);
	}

	/**
	 * Resets for isolation
	 */
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
		$userType = '0'; // Parent
		$sex = 'F';
		$this->setFormData(
			$displayName,
			$emailAddress,
			$password,
			$password,
			$phoneNumber,
			$userType,
			$sex
		);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertInstanceOf('AbaLookup\Entity\User', $user);
		$this->assertEquals($displayName, $user->getDisplayName());
		$this->assertEquals($emailAddress, $user->getEmail());
		$this->assertTrue($user->verifyPassword($password));
		$this->assertFalse($user->isTherapist());
		$this->assertTrue(((int) $phoneNumber) === $user->getPhone());
		$this->assertEquals($sex, $user->getSex());
	}

	public function testDisplayNameWithNonEnglishCharactersDoesValidate()
	{
		$this->setFormData('Jöhn Dõę');
		$this->assertTrue($this->form->isValid());
	}

	public function testNullDisplayNameDoesNotValidate()
	{
		$this->setFormData(NULL);
		$this->assertFalse($this->form->isValid());
	}

	public function testEmptyDisplayNameDoesNotValidate()
	{
		$this->setFormData('');
		$this->assertFalse($this->form->isValid());
	}

	public function testDisplayNameFilledWithSpacesDoesNotValidate()
	{
		$this->setFormData('     ');
		$this->assertFalse($this->form->isValid());
	}

	public function testEmailAddressContainingNonEnglishCharactersDoesNotValidate()
	{
		$this->setFormData('John Doe', 'foö@bar.com');
		$this->assertFalse($this->form->isValid());
	}

	public function testEmptyEmailAddressDoesNotValidate()
	{
		$this->setFormData('John Doe', '');
		$this->assertFalse($this->form->isValid());
	}

	public function testNullEmailAddressDoesNotValidate()
	{
		$this->setFormData('John Doe', NULL);
		$this->assertFalse($this->form->isValid());
	}

	public function testPasswordTooShortDoesNotValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'pass',
			'pass'
		);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullPasswordDoesNotValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			NULL
		);
		$this->assertFalse($this->form->isValid());
	}

	public function testNotConfirmingPasswordDoesNotValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'not the same password'
		);
		$this->assertFalse($this->form->isValid());
	}

	public function testInvalidPhoneNumberDoesNotValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'000'
		);
		$this->assertFalse($this->form->isValid());
	}

	public function testPhoneNumberWithHyphensDoesValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'709-555-1234'
		);
		$this->assertTrue($this->form->isValid());
	}

	public function testPhoneNumberWithPeriodsBetweenSetsDoesValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'555.1234'
		);
		$this->assertTrue($this->form->isValid());
	}

	public function testPhoneNumberWithSpacesDoesValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'709 555 1133'
		);
		$this->assertTrue($this->form->isValid());
	}

	public function testNullUserTypeDoesNotValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'7095551234',
			NULL
		);
		$this->assertFalse($this->form->isValid());
	}

	public function testUndisclosedSexDoesValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'7095551234',
			'1',
			'Undisclosed'
		);
		$this->assertTrue($this->form->isValid());
	}

	public function testNullSexDoesValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'7095551234',
			'1',
			NULL
		);
		$this->assertTrue($this->form->isValid());
	}
}

<?php

namespace AbaLookupTest;

use
	AbaLookup\Entity\UserType,
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
	                               $userType               = UserType::TYPE_PARENT,
	                               $gender                 = NULL,
	                               $abaCourse              = FALSE,
	                               $certificateOfConduct   = 0
	) {
		$this->form->setData([
			RegisterForm::ELEMENT_NAME_DISPLAY_NAME            => $displayName,
			RegisterForm::ELEMENT_NAME_EMAIL_ADDRESS           => $emailAddress,
			RegisterForm::ELEMENT_NAME_PASSWORD                => $password,
			RegisterForm::ELEMENT_NAME_CONFIRM_PASSWORD        => $confirmPassword,
			RegisterForm::ELEMENT_NAME_PHONE_NUMBER            => $phoneNumber,
			RegisterForm::ELEMENT_NAME_USER_TYPE               => $userType,
			RegisterForm::ELEMENT_NAME_GENDER                  => $gender,
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
		$userType = UserType::TYPE_PARENT;
		$gender = 'F';
		$this->setFormData(
			$displayName,
			$emailAddress,
			$password,
			$password,
			$phoneNumber,
			$userType,
			$gender
		);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertInstanceOf('AbaLookup\Entity\User', $user);
		$this->assertEquals($displayName, $user->getDisplayName());
		$this->assertEquals($emailAddress, $user->getEmail());
		$this->assertTrue($user->verifyPassword($password));
		$this->assertEquals(UserType::TYPE_PARENT, $user->getUserType());
		$this->assertTrue(((int) $phoneNumber) === $user->getPhone());
		$this->assertEquals($gender, $user->getGender());
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

	public function testRandomUserTypeDoesNotValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'7095551234',
			'this is random'
		);
		$this->assertFalse($this->form->isValid());
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

	public function testUndisclosedGenderDoesValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'7095551234',
			UserType::TYPE_ABA_THERAPIST,
			'Undisclosed'
		);
		$this->assertTrue($this->form->isValid());
	}

	public function testNullGenderDoesValidate()
	{
		$this->setFormData(
			'John Doe',
			'jdoe@email.com',
			'password',
			'password',
			'7095551234',
			UserType::TYPE_ABA_THERAPIST,
			NULL
		);
		$this->assertTrue($this->form->isValid());
	}
}

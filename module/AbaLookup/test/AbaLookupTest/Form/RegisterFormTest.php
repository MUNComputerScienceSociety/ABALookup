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
	 *
	 * Sets the default values in the case that an empty array is passed, overwrites
	 * the values in the event that the array contains values.
	 *
	 * @param array $data The specific data values that are to be overridden.
	 * @return void
	 */
	protected function setFormData(array $data) {
		$defaultValues = [
			RegisterForm::ELEMENT_NAME_DISPLAY_NAME                => 'John Doe',
			RegisterForm::ELEMENT_NAME_EMAIL_ADDRESS               => 'jdoe@email.com',
			RegisterForm::ELEMENT_NAME_PASSWORD                    => 'password',
			RegisterForm::ELEMENT_NAME_CONFIRM_PASSWORD            => 'password',
			RegisterForm::ELEMENT_NAME_PHONE_NUMBER                => '7095551234',
			RegisterForm::ELEMENT_NAME_GENDER                      => NULL,
			RegisterForm::ELEMENT_NAME_ABA_COURSE                  => FALSE,
			RegisterForm::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT      => 0,
			RegisterForm::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE => date('Y-m-d'),
			RegisterForm::ELEMENT_NAME_POSTAL_CODE                 => '',
		];
		$this->form->setData($data + $defaultValues);
	}

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		// In this context (testing) the type makes no difference
		$this->form = new RegisterForm(UserType::TYPE_PARENT);
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
		$displayName          = 'Jane Doe';
		$emailAddress         = 'jdoe@email.com';
		$password             = 'password';
		$phoneNumber          = '7095551234';
		$gender               = 'F';
		$abaCourse            = TRUE;
		$this->setFormData([
			RegisterForm::ELEMENT_NAME_DISPLAY_NAME     => $displayName,
			RegisterForm::ELEMENT_NAME_EMAIL_ADDRESS    => $emailAddress,
			RegisterForm::ELEMENT_NAME_PASSWORD         => $password,
			RegisterForm::ELEMENT_NAME_CONFIRM_PASSWORD => $password,
			RegisterForm::ELEMENT_NAME_PHONE_NUMBER     => $phoneNumber,
			RegisterForm::ELEMENT_NAME_GENDER           => $gender,
			RegisterForm::ELEMENT_NAME_ABA_COURSE       => $abaCourse,
		]);
		// This dataset should validate
		$this->assertTrue($this->form->isValid());
		// The generated user should have the proper fields
		$user = $this->form->getUser();
		$this->assertInstanceOf('AbaLookup\Entity\User', $user);
		$this->assertEquals($displayName, $user->getDisplayName());
		$this->assertEquals($emailAddress, $user->getEmail());
		$this->assertTrue($user->verifyPassword($password));
		$this->assertEquals(UserType::TYPE_PARENT, $user->getUserType());
		$this->assertTrue(((int) $phoneNumber) === $user->getPhone());
		$this->assertEquals($gender, $user->getGender());
		$this->assertTrue($user->getAbaCourse());
	}

	public function testDisplayNameWithNonEnglishCharactersDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_DISPLAY_NAME => 'Jöhn Dõę']);
		$this->assertTrue($this->form->isValid());
		$this->form->getUser();
	}

	public function testNullDisplayNameDoesNotValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_DISPLAY_NAME => NULL]);
		$this->assertFalse($this->form->isValid());
	}

	public function testEmptyDisplayNameDoesNotValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_DISPLAY_NAME => '']);
		$this->assertFalse($this->form->isValid());
	}

	public function testDisplayNameFilledWithWhitespaceDoesNotValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_DISPLAY_NAME => '               ']);
		$this->assertFalse($this->form->isValid());
	}

	public function testEmailAddressContainingNonEnglishCharactersDoesNotValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_EMAIL_ADDRESS => 'foö@bar.com']);
		$this->assertFalse($this->form->isValid());
	}

	public function testEmptyEmailAddressDoesNotValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_EMAIL_ADDRESS => '']);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullEmailAddressDoesNotValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_EMAIL_ADDRESS => NULL]);
		$this->assertFalse($this->form->isValid());
	}

	public function testPasswordTooShortDoesNotValidate()
	{
		$this->setFormData([
			RegisterForm::ELEMENT_NAME_PASSWORD         => 'pass',
			RegisterForm::ELEMENT_NAME_CONFIRM_PASSWORD => 'pass',
		]);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullPasswordDoesNotValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_PASSWORD => NULL]);
		$this->assertFalse($this->form->isValid());
	}

	public function testNotConfirmingPasswordDoesNotValidate()
	{
		$this->setFormData([
			RegisterForm::ELEMENT_NAME_PASSWORD         => 'password',
			RegisterForm::ELEMENT_NAME_CONFIRM_PASSWORD => 'not the same password',
		]);
		$this->assertFalse($this->form->isValid());
	}

	public function testInvalidPhoneNumberDoesNotValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_PHONE_NUMBER => '000']);
		$this->assertFalse($this->form->isValid());
	}

	public function testPhoneNumberWithHyphensDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_PHONE_NUMBER => '709-555-1234']);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertEquals(7095551234, $user->getPhone());
	}

	public function testNicelyFormattedPhoneNumberDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_PHONE_NUMBER => '(709) 555-1234']);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertEquals(7095551234, $user->getPhone());
	}

	public function testPhoneNumberWithPeriodsBetweenSetsDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_PHONE_NUMBER => '555.1234']);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertEquals(5551234, $user->getPhone());
	}

	public function testPhoneNumberWithSpacesDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_PHONE_NUMBER => '709 555 1133']);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertEquals(7095551133, $user->getPhone());
	}

	public function testUndisclosedGenderDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_GENDER => 'Undisclosed']);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertNull($user->getGender());
	}

	public function testNullGenderDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_GENDER => NULL]);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertNull($user->getGender());
	}

	public function testEmptyStringGenderDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_GENDER => '']);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertNull($user->getGender());
	}

	public function testNullAbaCourseDoesRegister()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_ABA_COURSE => NULL]);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertNull($user->getAbaCourse());
	}

	public function testEmptyStringAbaCourseDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_ABA_COURSE => '']);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertFalse($user->getAbaCourse());
	}

	public function testValidPostalCodeWithSpaceDoesValidate()
	{
		$pc = 'A1B 3X9';
		$this->setFormData([RegisterForm::ELEMENT_NAME_POSTAL_CODE => $pc]);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertEquals('A1B3X9', $user->getPostalCode());
		// How about with a lot of whitespace?
		$pc = '       A1B            3X9                                  ';
		$this->setFormData([RegisterForm::ELEMENT_NAME_POSTAL_CODE => $pc]);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertEquals('A1B3X9', $user->getPostalCode());
	}

	public function testValidPostalCodeWithoutSpaceDoesValidate()
	{
		$pc = 'A1B3X9';
		$this->setFormData([RegisterForm::ELEMENT_NAME_POSTAL_CODE => $pc]);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertEquals($pc, $user->getPostalCode());
	}

	public function testEmptyPostalCodeDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_POSTAL_CODE => '']);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertNull($user->getPostalCode());
	}

	public function testInvalidPostalCodeDoesNotValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_POSTAL_CODE => 3]);
		$this->assertFalse($this->form->isValid());
		$this->setFormData([RegisterForm::ELEMENT_NAME_POSTAL_CODE => 'not a postal code']);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullPostalCodeDoesValidate()
	{
		$this->setFormData([RegisterForm::ELEMENT_NAME_POSTAL_CODE => NULL]);
		$this->assertTrue($this->form->isValid());
		$user = $this->form->getUser();
		$this->assertNull($user->getPostalCode());
	}
}

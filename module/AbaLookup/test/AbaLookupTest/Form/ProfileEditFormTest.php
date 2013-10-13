<?php

namespace AbaLookupTest\Form;

use
	AbaLookup\Entity\User,
	AbaLookup\Entity\UserType,
	AbaLookup\Form\ProfileEditForm,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the login form
 */
class ProfileEditFormTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Entity\User
	 */
	protected $user;

	/**
	 * @var AbaLookup\Form\ProfileEidtForm
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
			ProfileEditForm::ELEMENT_NAME_ABA_COURSE                  => '',
			ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME                => 'John Doe',
			ProfileEditForm::ELEMENT_NAME_EMAIL_ADDRESS               => 'foo@bar.com',
			ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER                => '',
			ProfileEditForm::ELEMENT_NAME_POSTAL_CODE                 => '',
			ProfileEditForm::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT      => 0,
			ProfileEditForm::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE => '',
		];
		$this->form->setData($data + $defaultValues);
	}

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->user = new User('Ramus', 'foo@bar.com', 'password', UserType::TYPE_ABA_THERAPIST);
		$this->form = new ProfileEditForm($this->user);
	}

	/**
	 * @expectedException Zend\Form\Exception\DomainException
	 */
	public function testExceptionIsThrownWhenValidatingFormWithoutData()
	{
		$this->form->isValid();
	}

	public function testValidDataDoesVaildate()
	{
		$this->setFormData([]);
		$this->assertTrue($this->form->isValid());
	}

	public function testDisplayNameContainingNonEnglishCharactersDoesValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME => 'Johñ Döe']);
		$this->assertTrue($this->form->isValid());
	}

	public function testInvalidDisplayNameDoesNotValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME => '']);
		$this->assertFalse($this->form->isValid());
		$this->assertInternalType('string', $this->form->getMessage());
	}

	public function testDisplayNameOnlySpacesDoesNotValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME => '         ']);
		$this->assertFalse($this->form->isValid());
		$this->assertInternalType('string', $this->form->getMessage());
	}

	public function testNullDisplayNameDoesNotValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME => NULL]);
		$this->assertFalse($this->form->isValid());
		$this->assertInternalType('string', $this->form->getMessage());
	}

	public function testEmptyEmailAddressDoesNotValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_EMAIL_ADDRESS => '']);
		$this->assertFalse($this->form->isValid());
		$this->assertInternalType('string', $this->form->getMessage());
	}

	public function testEmailAddressContainingNonEnglishCharactersDoesNotValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_EMAIL_ADDRESS => 'foö@bar.com']);
		$this->assertFalse($this->form->isValid());
		$this->assertInternalType('string', $this->form->getMessage());
	}

	public function testNullEmailAddressDoesNotValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_EMAIL_ADDRESS => NULL]);
		$this->assertFalse($this->form->isValid());
		$this->assertInternalType('string', $this->form->getMessage());
	}

	public function testInvalidPhoneNumberDoesNotValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => '1']);
		$this->assertFalse($this->form->isValid());
		$this->assertInternalType('string', $this->form->getMessage());
	}

	public function testNullPhoneNumberDoesValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => NULL]);
		$this->assertTrue($this->form->isValid());
	}

	public function testEmptyPhoneNumberDoesValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => '']);
		$this->assertTrue($this->form->isValid());
	}

	public function testPhoneNumberWithHyphensDoesValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => '709-555-1234']);
		$this->assertTrue($this->form->isValid());
	}

	public function testInvalidPostalCodeDoesNotValidate()
	{
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_POSTAL_CODE => 'this is not real']);
		$this->assertFalse($this->form->isValid());
		$this->assertInternalType('string', $this->form->getMessage());
	}

	public function testCanUpdateDisplayName()
	{
		$displayName = 'John Doe';
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME => $displayName]);
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertEquals($displayName, $this->user->getDisplayName());
		// How about a string prefixed with whitespace?
		$displayName = '      John Doe      ';
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME => $displayName]);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertEquals('John Doe', $this->user->getDisplayName());
	}

	public function testCanUpdateDisplayNameWithNonEnglishCharacters()
	{
		$displayName = 'ËèŒŁma Kæępø';
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME => $displayName]);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertEquals($displayName, $this->user->getDisplayName());
	}

	public function testCanUpdatePhoneNumber()
	{
		$this->assertNull($this->user->getPhone());
		$phoneNumber = '7095551234';
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => $phoneNumber]);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertTrue(7095551234 === $this->user->getPhone());
	}

	public function testCanUpdatePhoneNumberWithSpaces()
	{
		$this->assertNull($this->user->getPhone());
		$phoneNumber = '709 555 1234';
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => $phoneNumber]);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertTrue(7095551234 === $this->user->getPhone());
	}

	public function testCanUpdatePhoneNumberWithPeriodsBetweensSets()
	{
		$this->assertNull($this->user->getPhone());
		$phoneNumber = '709.555.1234';
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => $phoneNumber]);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertTrue(7095551234 === $this->user->getPhone());
	}

	public function testCanUpdatePhoneNumberWithHyphens()
	{
		$this->assertNull($this->user->getPhone());
		$phoneNumber = '709-555-1234';
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => $phoneNumber]);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertTrue(7095551234 === $this->user->getPhone());
	}

	public function testCanUpdatePostalCode()
	{
		$this->assertNull($this->user->getPostalCode());
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_POSTAL_CODE => 'A1B 3X9']);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertEquals('A1B3X9', $this->user->getPostalCode());
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_POSTAL_CODE => '']);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertNull($this->user->getPostalCode());
	}

	public function testCanUpdateAbaCourseStatus()
	{
		$this->assertNull($this->user->getAbaCourse());
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_ABA_COURSE => '1']);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertTrue($this->user->getAbaCourse());
		$this->setFormData([ProfileEditForm::ELEMENT_NAME_ABA_COURSE => '0']);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertFalse($this->user->getAbaCourse());
	}

	public function testCanUpdateCertificateOfConduct()
	{
		$this->assertNull($this->user->getCertificateOfConduct());
		$date = date('Y-m-d', strtotime('3 days ago'));
		$time = strtotime($date);
		$this->setFormData([
			ProfileEditForm::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT      => '1',
			ProfileEditForm::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE => $date,
		]);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertEquals($time, $this->user->getCertificateOfConduct());
		$this->setFormData([
			ProfileEditForm::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT      => '0',
			ProfileEditForm::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE => $date,
		]);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->updateUser($this->user));
		$this->assertNull($this->user->getCertificateOfConduct());
	}
}

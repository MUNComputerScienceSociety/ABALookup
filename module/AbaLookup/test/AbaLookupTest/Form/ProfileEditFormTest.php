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
	 */
	protected function setFormData($displayName,
	                               $emailAddress,
	                               $phoneNumber = NULL
	) {
		$this->form->setData([
			ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME => $displayName,
			ProfileEditForm::ELEMENT_NAME_EMAIL_ADDRESS => $emailAddress,
			ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => $phoneNumber
		]);
	}

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->user = new User('Ramus', 'foo@bar.com', 'password', UserType::TYPE_PARENT);
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
		$this->setFormData('John Doe', 'foo@bar.com');
		$this->assertTrue($this->form->isValid());
	}

	public function testDisplayNameContainingNonEnglishCharactersDoesValidate()
	{
		$this->setFormData('Johñ Döe', 'foo@bar.com');
		$this->assertTrue($this->form->isValid());
	}

	public function testInvalidDisplayNameDoesNotValidate()
	{
		$this->setFormData('', 'foo@bar.com');
		$this->assertFalse($this->form->isValid());
	}

	public function testDisplayNameOnlySpacesDoesNotValidate()
	{
		$this->setFormData('      ', 'foo@bar.com');
		$this->assertFalse($this->form->isValid());
	}

	public function testNullDisplayNameDoesNotValidate()
	{
		$this->setFormData(NULL, 'foo@bar.com');
		$this->assertFalse($this->form->isValid());
	}

	public function testEmptyEmailAddressDoesNotValidate()
	{
		$this->setFormData('John Doe', '');
		$this->assertFalse($this->form->isValid());
	}

	public function testEmailAddressContainingNonEnglishCharactersDoesNotValidate()
	{
		$this->setFormData('John Doe', 'foö@bar.com');
		$this->assertFalse($this->form->isValid());
	}

	public function testNullEmailAddressDoesNotValidate()
	{
		$this->setFormData('John Doe', NULL);
		$this->assertFalse($this->form->isValid());
	}

	public function testInvalidPhoneNumberDoesNotValidate()
	{
		$this->setFormData('John Doe', 'foo@bar.com', '1');
		$this->assertFalse($this->form->isValid());
	}

	public function testNullPhoneNumberDoesValidate()
	{
		$this->setFormData('John Doe', 'foo@bar.com', NULL);
		$this->assertTrue($this->form->isValid());
	}

	public function testEmptyPhoneNumberDoesValidate()
	{
		$this->setFormData('John Doe', 'foo@bar.com', '');
		$this->assertTrue($this->form->isValid());
	}

	public function testPhoneNumberWithHyphensDoesValidate()
	{
		$this->setFormData('John Doe', 'foo@bar.com', '709-555-1111');
		$this->assertTrue($this->form->isValid());
	}

	public function testCanUpdateDisplayName()
	{
		$displayName = 'John Doe';
		$this->setFormData($displayName, 'foo@bar.com');
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertEquals($displayName, $this->user->getDisplayName());
	}

	public function testCanUpdateDisplayNameWithNonEnglishCharacters()
	{
		$displayName = 'ËèŒŁma Kæępø';
		$this->setFormData($displayName, 'foo@bar.com');
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertEquals($displayName, $this->user->getDisplayName());
	}

	public function testCanUpdatePhoneNumber()
	{
		$this->assertEquals(NULL, $this->user->getPhone());
		$phoneNumber = '7095551234'; // String values are returned from forms
		$this->setFormData('John Doe', 'foo@bar.com', $phoneNumber);
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertTrue((int) $phoneNumber === $this->user->getPhone());
	}

	public function testCanUpdatePhoneNumberWithSpaces()
	{
		$this->assertEquals(NULL, $this->user->getPhone());
		$phoneNumber = '709 555 1234';
		$this->setFormData('John Doe', 'foo@bar.com', $phoneNumber);
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertTrue(7095551234 === $this->user->getPhone());
	}

	public function testCanUpdatePhoneNumberWithPeriodsBetweensSets()
	{
		$this->assertEquals(NULL, $this->user->getPhone());
		$phoneNumber = '709.555.1234';
		$this->setFormData('John Doe', 'foo@bar.com', $phoneNumber);
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertTrue(7095551234 === $this->user->getPhone());
	}

	public function testCanUpdatePhoneNumberWithHyphens()
	{
		$this->assertEquals(NULL, $this->user->getPhone());
		$phoneNumber = '709-555-1234';
		$this->setFormData('John Doe', 'foo@bar.com', $phoneNumber);
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertTrue(7095551234 === $this->user->getPhone());
	}
}

<?php

namespace AbaLookupTest\Form;

use
	AbaLookup\Entity\User,
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

	protected function generateData($displayName,
	                                $emailAddress,
	                                $oldPassword = '',
	                                $newPassword = '',
	                                $newPasswordConfirm = '',
	                                $phoneNumber = NULL
	) {
		return [
			ProfileEditForm::ELEMENT_NAME_DISPLAY_NAME => $displayName,
			ProfileEditForm::ELEMENT_NAME_EMAIL_ADDRESS => $emailAddress,
			ProfileEditForm::ELEMENT_NAME_OLD_PASSWORD => $oldPassword,
			ProfileEditForm::ELEMENT_NAME_NEW_PASSWORD => $newPassword,
			ProfileEditForm::ELEMENT_NAME_CONFIRM_NEW_PASSWORD => $newPasswordConfirm,
			ProfileEditForm::ELEMENT_NAME_PHONE_NUMBER => $phoneNumber
		];
	}

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->user = new User('Ramus', 'ramus@email.com', 'password', FALSE);
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
		$data = $this->generateData('John Doe', 'jdoe@email.com');
		$this->form->setData($data);
		$this->assertTrue($this->form->isValid());
	}

	public function testInvalidDisplayNameDoesNotValidate()
	{
		$data = $this->generateData('', 'jdoe@email.com');
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testDisplayNameOnlySpacesDoesNotValidate()
	{
		$data = $this->generateData('       ', 'jdoe@email.com');
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullDisplayNameDoesNotValidate()
	{
		$data = $this->generateData(NULL, 'jdoe@email.com');
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testEmptyEmailAddressDoesNotValidate()
	{
		$data = $this->generateData('John Doe', '');
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullEmailAddressDoesNotValidate()
	{
		$data = $this->generateData('John Doe', NULL);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testUpdatePasswordWithoutOldPasswordDoesNotValidate()
	{
		$data = $this->generateData('John Doe', 'jdoe@email.com', '', 'new password', 'new password');
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testEnterNewPasswordWithoutConfirmingDoesNotValidate()
	{
		$data = $this->generateData('John Doe', 'jdoe@email.com', 'password', 'new password');
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testShortPasswordDoesNotValidate()
	{
		$data = $this->generateData('John Doe', 'jdoe@email.com', 'password', 'foo', 'foo');
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testNullPasswordDoesNotValidate()
	{
		$data = $this->generateData('John Doe', 'jdoe@email.com', 'password', NULL, NULL);
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testCanUpdateDisplayName()
	{
		$displayName = 'John Doe';
		$data = $this->generateData($displayName, 'jdoe@email.com');
		$this->form->setData($data);
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertEquals($displayName, $this->user->getDisplayName());
	}

	public function testCanUpdatePassword()
	{
		$oldPassword = 'password';
		$newPassword = 'foobarbaz';
		$this->assertTrue($this->user->verifyPassword($oldPassword));
		$this->assertFalse($this->user->verifyPassword($newPassword));
		$data = $this->generateData(
			$this->user->getDisplayName(),
			$this->user->getEmail(),
			$oldPassword,
			$newPassword,
			$newPassword
		);
		$this->form->setData($data);
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertTrue($this->user->verifyPassword($newPassword));
	}

	public function testCanUpdatePhoneNumber()
	{
		$this->assertEquals(NULL, $this->user->getPhone());
		$phoneNumber = '7095551234'; // string values are returned from forms
		$data = $this->generateData('John Doe', 'jdoe@email.com', '', '', '', $phoneNumber);
		$this->form->setData($data);
		$this->assertTrue($this->form->isValid());
		$this->form->updateUser($this->user);
		$this->assertTrue((int) $phoneNumber === $this->user->getPhone());
	}
}

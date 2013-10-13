<?php

namespace AbaLookupTest\Form;

use
	AbaLookup\Form\LoginForm,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the login form
 */
class LoginFormTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Form\LoginForm
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
			LoginForm::ELEMENT_NAME_EMAIL_ADDRESS => 'foo@bar.com',
			LoginForm::ELEMENT_NAME_PASSWORD      => 'password',
			LoginForm::ELEMENT_NAME_REMEMBER_ME   => '',
		];
		$this->form->setData($data + $defaultValues);
	}

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->form = new LoginForm();
	}

	/**
	 * @expectedException Zend\Form\Exception\DomainException
	 */
	public function testExceptionIsThrownWhenValidatingEmptyForm()
	{
		$this->form->isValid();
	}

	public function testValidDataDoesValidate()
	{
		$this->setFormData([]);
		$this->assertTrue($this->form->isValid());
	}

	public function testInvalidEmailAddressDoesNotValidate()
	{
		$this->setFormData([LoginForm::ELEMENT_NAME_EMAIL_ADDRESS => 'foo']);
		$this->assertFalse($this->form->isValid());
	}

	public function testEmailAddressContainingNonEnglishCharactersDoesNotValidate()
	{
		$this->setFormData([LoginForm::ELEMENT_NAME_EMAIL_ADDRESS => 'foö@bar.com']);
		$this->assertFalse($this->form->isValid());
	}

	public function testShortPasswordDoesNotValidate()
	{
		$this->setFormData([LoginForm::ELEMENT_NAME_PASSWORD => 'bar']);
		$this->assertFalse($this->form->isValid());
	}

	public function testGetEmailAddressFromUnvalidatedForm()
	{
		$emailAddress = 'foo@bar.com';
		$this->setFormData([LoginForm::ELEMENT_NAME_EMAIL_ADDRESS => $emailAddress]);
		$this->assertNull($this->form->getEmailAddress());
	}

	public function testGetEmailAddressFromValidatedForm()
	{
		$emailAddress = 'foo@bar.com';
		$this->setFormData([LoginForm::ELEMENT_NAME_EMAIL_ADDRESS => $emailAddress]);
		$this->assertTrue($this->form->isValid());
		$this->assertEquals($emailAddress, $this->form->getEmailAddress());
	}

	public function testGetPasswordFromUnvalidatedForm()
	{
		$password = 'password';
		$this->setFormData([LoginForm::ELEMENT_NAME_PASSWORD => $password]);
		$this->assertNull($this->form->getPassword());
	}

	public function testGetPasswordFromValidatedForm()
	{
		$password = 'password';
		$this->setFormData([LoginForm::ELEMENT_NAME_PASSWORD => $password]);
		$this->assertTrue($this->form->isValid());
		$this->assertEquals($password, $this->form->getPassword());
	}

	public function testGetTrueRememberMeFromValidatedForm()
	{
		$this->setFormData([LoginForm::ELEMENT_NAME_REMEMBER_ME => TRUE]);
		$this->assertTrue($this->form->isValid());
		$this->assertTrue($this->form->rememberMe());
	}

	public function testGetFalseRememberMeFromValidatedForm()
	{
		$this->setFormData([]);
		$this->assertTrue($this->form->isValid());
		$this->assertFalse($this->form->rememberMe());
	}
}

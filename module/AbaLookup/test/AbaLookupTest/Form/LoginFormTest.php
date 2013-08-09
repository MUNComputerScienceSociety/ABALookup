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
	 */
	protected function setFormData($emailAddress, $password)
	{
		$this->form->setData([
			LoginForm::ELEMENT_NAME_EMAIL_ADDRESS => $emailAddress,
			LoginForm::ELEMENT_NAME_PASSWORD => $password,
			LoginForm::ELEMENT_NAME_REMEMBER_ME => ''
		]);
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
		$this->setFormData('foo@bar.com', 'password');
		$this->assertTrue($this->form->isValid());
	}

	public function testInvalidEmailAddressDoesNotValidate()
	{
		$this->setFormData('foo', 'password');
		$this->assertFalse($this->form->isValid());
	}

	public function testEmailAddressContainingNonEnglishCharactersDoesNotValidate()
	{
		$this->setFormData('foö@bar.com', 'password');
		$this->assertFalse($this->form->isValid());
	}

	public function testShortPasswordDoesNotValidate()
	{
		$this->setFormData('foo@bar.com', 'bar');
		$this->assertFalse($this->form->isValid());
	}

	public function testGetEmailAddressFromUnvalidatedForm()
	{
		$emailAddress = 'foo@bar.com';
		$this->setFormData($emailAddress, 'password');
		$this->assertEquals(NULL, $this->form->getEmailAddress());
	}

	public function testGetEmailAddressFromValidatedForm()
	{
		$emailAddress = 'foo@bar.com';
		$this->setFormData($emailAddress, 'password');
		$this->form->isValid();
		$this->assertEquals($emailAddress, $this->form->getEmailAddress());
	}

	public function testGetPasswordFromUnvalidatedForm()
	{
		$password = 'password';
		$this->setFormData('foo@bar.com', $password);
		$this->assertEquals(NULL, $this->form->getPassword());
	}

	public function testGetPasswordFromValidatedForm()
	{
		$password = 'password';
		$this->setFormData('foo@bar.com', $password);
		$this->form->isValid();
		$this->assertEquals($password, $this->form->getPassword());
	}
}

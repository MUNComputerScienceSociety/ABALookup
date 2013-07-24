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

	protected function generateData($emailAddress, $password)
	{
		return [
			LoginForm::ELEMENT_NAME_EMAIL_ADDRESS => $emailAddress,
			LoginForm::ELEMENT_NAME_PASSWORD => $password,
			LoginForm::ELEMENT_NAME_REMEMBER_ME => '0'
		];
	}

	/**
	 * Reset for isolation
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
		$data = $this->generateData('foo@bar.com', 'password');
		$this->form->setData($data);
		$this->assertTrue($this->form->isValid());
	}

	public function testInvalidEmailAddressDoesNotValidate()
	{
		$data = $this->generateData('foo', 'password');
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testInvalidPasswordDoesNotValidate()
	{
		$data = $this->generateData('foo@bar.com', 'bar');
		$this->form->setData($data);
		$this->assertFalse($this->form->isValid());
	}

	public function testGetEmailAddressFromUnvalidatedForm()
	{
		$emailAddress = 'foo@bar.com';
		$data = $this->generateData($emailAddress, 'password');
		$this->form->setData($data);
		$this->assertEquals(NULL, $this->form->getEmailAddress());
	}

	public function testGetEmailAddressFromValidatedForm()
	{
		$emailAddress = 'foo@bar.com';
		$data = $this->generateData($emailAddress, 'password');
		$this->form->setData($data);
		$this->form->isValid();
		$this->assertEquals($emailAddress, $this->form->getEmailAddress());
	}

	public function testGetPasswordFromUnvalidatedForm()
	{
		$password = 'password';
		$data = $this->generateData('foo@bar.com', $password);
		$this->form->setData($data);
		$this->assertEquals(NULL, $this->form->getPassword());
	}

	public function testGetPasswordFromValidatedForm()
	{
		$password = 'password';
		$data = $this->generateData('foo@bar.com', $password);
		$this->form->setData($data);
		$this->form->isValid();
		$this->assertEquals($password, $this->form->getPassword());
	}
}

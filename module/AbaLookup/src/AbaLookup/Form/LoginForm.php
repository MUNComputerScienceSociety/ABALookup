<?php

namespace AbaLookup\Form;

class LoginForm extends AbstractBaseForm
{
	public function __construct()
	{
		parent::__construct();
		// Email address
		$this->add([
			'name' => self::ELEMENT_NAME_EMAIL_ADDRESS,
			'type' => 'email',
			'attributes' => [
				'id' => self::ELEMENT_NAME_EMAIL_ADDRESS,
			],
			'options' => [
				'label' => 'Your email address',
			],
		]);
		// Password field
		$this->add([
			'name' => self::ELEMENT_NAME_PASSWORD,
			'type' => 'password',
			'attributes' => [
				'id' => self::ELEMENT_NAME_PASSWORD,
			],
			'options' => [
				'label' => 'Your password',
			],
		]);
		// Remember me
		$this->add([
			'name' => self::ELEMENT_NAME_REMEMBER_ME,
			'type' => 'checkbox',
			'attributes' => [
				'id' => self::ELEMENT_NAME_REMEMBER_ME
			],
			'options' => [
				'label' => 'Remember me',
				'checked_value' => TRUE,
			],
		]);
		// Submit
		$this->add([
			'name' => 'login',
			'type' => 'submit',
			'attributes' => [
				'value' => 'Login',
			],
		]);
	}
}

<?php

namespace AbaLookup\Form;

use
	Zend\Form\Element\Csrf,
	Zend\Form\Form,
	Zend\InputFilter\Factory as InputFilterFactory
;

/**
 * The form for registering users
 */
class RegisterForm extends Form
{
	/**
	 * Construct the register form via factory
	 */
	public function __construct()
	{
		// super constructor
		parent::__construct();

		// user type
		$this->add([
			'name' => 'user-type',
			'type' => 'select',
			'attributes' => ['id' => 'user-type'],
			'options' => [
				'label' => 'User type',
				'value_options' => [
					'parent' => 'Parent',
					'therapist' => 'Therapist',
				],
			],
		]);

		// display name
		$this->add([
			'name' => 'display-name',
			'type' => 'text',
			'attributes' => [
				'id' => 'display-name',
				'placeholder' => 'Your display name',
			],
			'options' => [
				'label' => 'Your display name',
			],
		]);

		// email address
		$this->add([
			'name' => 'email-address',
			'type' => 'email',
			'attributes' => [
				'id' => 'email-address',
				'placeholder' => 'Your email address',
			],
			'options' => [
				'label' => 'Your email address',
			],
		]);

		// password field
		$this->add([
			'name' => 'password',
			'type' => 'password',
			'attributes' => [
				'id' => 'password',
				'placeholder' => 'Choose a password',
			],
			'options' => [
				'label' => 'Choose a password',
			],
		]);

		// confirm password
		$this->add([
			'name' => 'confirm-password',
			'type' => 'password',
			'attributes' => [
				'id' => 'comfirm-password',
				'placeholder' => 'Confirm your password choice',
			],
			'options' => [
				'label' => 'Confirm your password choice',
			],
		]);

		// prevent CSRF
		$this->add(new Csrf('security'));

		// submit button
		$this->add([
			'name' => 'register',
			'type' => 'submit',
			'attributes' => [
				'value' => 'Register',
			],
		]);

		// create and add an input filter via factory
		$iff = new InputFilterFactory();
		$if = [
			'password' => [
				'name' => 'password',
				'required' => TRUE,
				'validators' => [
					['name' => 'not_empty'],
					[
						'name' => 'string_length',
						'options' => [
							'min' => 6,
						],
					],
				],
			],
		];
		$this->setInputFilter($iff->createInputFilter($if));
	}
}

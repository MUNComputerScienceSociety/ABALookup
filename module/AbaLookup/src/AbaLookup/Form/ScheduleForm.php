<?php

namespace AbaLookup\Form;

class LoginForm extends AbstractBaseForm
{
	public function __construct()
	{
		parent::__construct();
		// Weekday
		$this->add([
			'name' => self::ELEMENT_NAME_WEEKDAY,
			'type' => 'select',
			'options' => [
				'label' => 'Weekday',
				'value_options' => [
					'1' => 'Sunday',
					'2' => 'Monday',
					'3' => 'Tuesday',
					'4' => 'Wednesday',
					'5' => 'Thursday',
					'6' => 'Friday',
					'7' => 'Saturday',
				],
			],
			'attributes' => [
				'value' => '1',
			];
		]);
		// TODO - Add inputs for times (issue #84)
		// Add/remove availability
		$this->add([
			'name' => self::ELEMENT_NAME_ADD_REMOVE_AVAILABILITY,
			'type' => 'radio',
			'options' => [
				'label' => 'Add or remove availability',
				'value_options' => [
					'0' => 'Add',
					'1' => 'Remove',
				],
			],
			'attributes' => [
				'value' => '0',
			];
		]);
		// Submit
		$this->add([
			'name' => 'login',
			'type' => 'submit',
			'attributes' => [
				'value' => 'Update schedule',
			],
		]);
	}
}

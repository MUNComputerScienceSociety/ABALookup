<?php

namespace AbaLookup\Form;

use Zend\Form\Form;

abstract class AbstractBaseForm extends Form
{
	/**
	 * Constants for form element IDs and names
	 */
	const ELEMENT_NAME_ABA_COURSE                   = 'aba-course';
	const ELEMENT_NAME_CERTIFICATE_OF_CONDUCT       = 'certificate-of-conduct';
	const ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE  = 'certificate-of-conduct-date';
	const ELEMENT_NAME_CONFIRM_PASSWORD             = 'confirm-password';
	const ELEMENT_NAME_DISPLAY_NAME                 = 'display-name';
	const ELEMENT_NAME_EMAIL_ADDRESS                = 'email-address';
	const ELEMENT_NAME_GENDER                       = 'gender';
	const ELEMENT_NAME_PASSWORD                     = 'password';
	const ELEMENT_NAME_PHONE_NUMBER                 = 'phone-number';
	const ELEMENT_NAME_POSTAL_CODE                  = 'postal-code';
	const ELEMENT_NAME_REMEMBER_ME                  = 'remember-me';
	const ELEMENT_NAME_WEEKDAY                      = 'weekday';
	const ELEMENT_NAME_ADD_REMOVE_AVAILABILITY      = 'add-remove-availability';

	/**
	 * Constants for user types
	 */
	const USER_TYPE_ABA_THERAPIST = 'therapist';
	const USER_TYPE_PARENT        = 'parent';
}

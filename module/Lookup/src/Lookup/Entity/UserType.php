<?php

namespace ABALookup\Entity;

use
	ReflectionClass
;

/**
 * The types of users
 */
class UserType
{
	const TYPE_ABA_THERAPIST = 'therapist';
	const TYPE_PARENT        = 'parent';

	/**
	 * Returns whether the given string is a valid user type
	 *
	 * @param string $value The search value;
	 * @return bool
	 */
	public static function contains($value)
	{
		$r = new ReflectionClass(__CLASS__);
		$constants = $r->getConstants();
		foreach ($constants as $k => $v) {
			if ($v === $value) {
				return TRUE;
			}
		}
		return FALSE;
	}
}

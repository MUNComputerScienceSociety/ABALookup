<?php

namespace AbaLookup\Session;

use Zend\Session\Container;

class Session
{
	/**
	 * 3 months in seconds
	 */
	const SECONDS_3_MONTHS = 7884000;

	/**
	 * Namespace and keys for the user
	 */
	const USER_NAMESPACE = 'user';
	const USER_KEY_ID    = 'id';

	/**
	 * Sets the user ID for the session
	 *
	 * @param int $id The user ID.
	 * @param bool $remember Whether to set an explicit TTL for the user session.
	 * @return void
	 */
	public static function setUserId($id, $remember = FALSE)
	{
		$session = new Container(Session::USER_NAMESPACE);
		$session->getManager()
		        ->getConfig()
		        ->setCookieHttpOnly(TRUE) // As per issue #87
		        ->rememberMe((is_bool($remember) && $remember) ? Session::SECONDS_3_MONTHS : 0);
		$session->offsetSet(Session::USER_KEY_ID, $id);
	}

	/**
	 * @return int|NULL The ID of the user in session.
	 */
	public static function getUserId()
	{
		return (new Container(Session::USER_NAMESPACE))->offsetGet(Session::USER_KEY_ID);
	}

	/**
	 * Unsets the user ID for the session
	 *
	 * @return void
	 */
	public static function unsetUserId()
	{
		(new Container(Session::USER_NAMESPACE))->offsetUnset(Session::USER_KEY_ID);
	}
}

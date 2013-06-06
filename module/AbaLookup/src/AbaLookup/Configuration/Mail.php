<?php

namespace AbaLookup\Configuration;

/**
 * The mail message sent to verify and reset passwords
 */
class Mail
{
	/**
	 * The email address from which this email is coming
	 */
	protected $mailFrom;
	/**
	 * The sender of this message
	 */
	protected $mailFromName;
	/**
	 * The message signifying account verification
	 */
	protected $verificationMessage;
	/**
	 * The subject of the verification email
	 */
	protected $verificationSubject;
	/**
	 * The URL to the website
	 */
	protected $url;
	/**
	 * The message signifying a password reset
	 */
	protected $resetPasswordMessage;
	/**
	 * The subject of the password reset email
	 */
	protected $resetPasswordSubject;

	/**
	 * Constructor
	 */
	function __construct($mailFrom,
	                     $mailFromName,
	                     $url,
	                     $verificationMessage,
	                     $verificationSubject,
	                     $resetPasswordMessage,
	                     $resetPasswordSubject)
	{
		$this->mailFrom = $mailFrom;
		$this->mailFromName = $mailFromName;
		$this->url = $url;
		$this->verificationMessage = $verificationMessage;
		$this->verificationSubject = $verificationSubject;
		$this->resetPasswordMessage = $resetPasswordMessage;
		$this->resetPasswordSubject = $resetPasswordSubject;
	}

	public function getMailFrom()
	{
		return $this->mailFrom;
	}

	public function getMailFromName()
	{
		return $this->mailFromName;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getVerificationMessage()
	{
		return $this->verificationMessage;
	}

	public function getVerificationSubject()
	{
		return $this->verificationSubject;
	}

	public function getResetPasswordMessage()
	{
		return $this->resetPasswordMessage;
	}

	public function getResetPasswordSubject()
	{
		return $this->resetPasswordSubject;
	}
}

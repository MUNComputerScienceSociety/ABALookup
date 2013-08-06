<?php

namespace AbaLookupTest\View\Helper;

use
	AbaLookup\View\Helper\Script,
	PHPUnit_Framework_TestCase
;

class ScriptTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Script
	 */
	protected $helper;

	/**
	 * Reset for isolation
	 */
	public function setUp()
	{
		$this->helper = new Script();
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvokePassedEmptyString()
	{
		$this->helper->__invoke('');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvokePassedNull()
	{
		$this->helper->__invoke(NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvokePassedNonString()
	{
		$this->helper->__invoke(3);
	}

	public function testInvokeReturnsString()
	{
		$this->assertInternalType('string', $this->helper->__invoke('foo'));
	}
}

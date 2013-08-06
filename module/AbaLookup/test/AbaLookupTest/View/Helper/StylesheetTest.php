<?php

namespace AbaLookupTest\View\Helper;

use
	AbaLookup\View\Helper\Stylesheet,
	PHPUnit_Framework_TestCase
;

class StylesheetTest extends PHPUnit_Framework_TestCase
{
	protected $helper;

	public function setUp()
	{
		$this->helper = new Stylesheet();
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
		$this->helper->__invoke(2);
	}

	public function testInvokeReturnsString()
	{
		$this->assertInternalType('string', $this->helper->__invoke('foo'));
	}
}

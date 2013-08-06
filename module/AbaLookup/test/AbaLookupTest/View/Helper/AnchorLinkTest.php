<?php

namespace AbaLookupTest\View\Helper;

use
	AbaLookup\View\Helper\AnchorLink,
	PHPUnit_Framework_TestCase
;

class AnchorLinkTest extends PHPUnit_Framework_TestCase
{
	protected $helper;

	public function setUp()
	{
		$this->helper = new AnchorLink();
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvokePassedNullText()
	{
		$this->helper->__invoke(NULL, 'localhost');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvokePassedNullHref()
	{
		$this->helper->__invoke('foo', NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvokePassedEmptyText()
	{
		$this->helper->__invoke('', 'foo');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvokePassedEmptyHref()
	{
		$this->helper->__invoke('foo', '');
	}

	public function testInvokeReturnsString()
	{
		$this->assertInternalType('string', $this->helper->__invoke('foo', 'bar'));
		$this->assertInternalType('string', $this->helper->__invoke('foo', 'bar', ['baz', 'qux']));
	}
}

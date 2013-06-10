<?php

namespace SeleniumTest;

use
	PHPUnit_Extensions_Selenium2TestCase
;

class WebTest extends PHPUnit_Extensions_Selenium2TestCase
{
	protected function setUp()
	{
		$this->setBrowser('firefox');
		$this->setBrowserUrl('http://localhost/');
	}

	public function testPageTitle()
	{
		$this->url('http://localhost/');
		$this->assertEquals('MAMP', $this->title());
	}
}

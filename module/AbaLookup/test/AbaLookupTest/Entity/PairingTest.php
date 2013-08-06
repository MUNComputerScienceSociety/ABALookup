<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\Pairing,
	AbaLookup\Entity\User,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the Pairing entity
 */
class PairingTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Pairing
	 */
	protected $pairing;

	/**
	 * The pair of users
	 */
	protected $a;
	protected $b;

	/**
	 * The score.
	 */
	protected $score;

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->a = new User('John Smith', 'johns@email.com', 'password', FALSE, 'M', FALSE, TRUE);
		$this->b = new User('Jane Smith', 'janes@email.com', 'password', TRUE, 'F', TRUE, TRUE);
		$this->score = 3.0;
		$this->pairing = new Pairing($this->a, $this->b, $this->score);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullScorePassedToConstructor()
	{
		new Pairing($this->a, $this->b, NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonFloatScorePassedToConstructor()
	{
		new Pairing($this->a, $this->b, 'a');
	}

	public function testGetA()
	{
		$this->assertEquals($this->a, $this->pairing->getA());
	}

	public function testGetB()
	{
		$this->assertEquals($this->b, $this->pairing->getB());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetScorePassedNull()
	{
		$this->pairing->setScore(NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetScorePassedString()
	{
		$this->pairing->setScore('3.0');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetScorePassedInteger()
	{
		$this->pairing->setScore(3);
	}

	public function testGetScore()
	{
		$this->assertEquals(3.0, $this->pairing->getScore());
	}

	public function testSetScore()
	{
		$this->assertEquals(5.0, $this->pairing->setScore(5.0)->getScore());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetExcludedPassedNull()
	{
		$this->pairing->setExcluded(NULL);
	}

	public function testIsExcluded()
	{
		$this->assertFalse($this->pairing->isExcluded());
	}

	public function testSetExcluded()
	{
		$this->assertTrue($this->pairing->setExcluded(TRUE)->isExcluded());
	}
}

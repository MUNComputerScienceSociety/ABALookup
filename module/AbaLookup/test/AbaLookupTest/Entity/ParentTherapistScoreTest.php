<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\ParentTherapistScore,
	AbaLookup\Entity\User,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the ParentTherapistScore entity
 */
class ParentTherapistScoreTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Entity\User
	 */
	protected $parent;

	/**
	 * @var AbaLookup\Entity\User
	 */
	protected $therapist;

	/**
	 * The score for the parent and therapist match
	 */
	protected $score;

	/**
	 * @var AbaLookup\Entity\ParentTherapistScore
	 */
	protected $pts;

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->parent = new User(
			"Jane",
			"jane@email.com",
			"password",
			FALSE,
			NULL,
			FALSE,
			FALSE
		);
		$this->therapist = new User(
			"Jack",
			"jack@email.com",
			"password",
			TRUE,
			"M",
			TRUE,
			TRUE
		);
		$this->score = 2;
		$this->pts = new ParentTherapistScore($this->parent, $this->therapist, $this->score);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionThrownIfTherapistPassedAsParentInConstructor()
	{
		new ParentTherapistScore($this->therapist, $this->therapist, $this->score);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionThrownIfParentPassedAsTherapistInConstructor()
	{
		new ParentTherapistScore($this->parent, $this->parent, $this->score);
	}

	public function testGetParent()
	{
		$this->assertEquals($this->parent, $this->pts->getParent());
	}

	public function testGetTherapist()
	{
		$this->assertEquals($this->therapist, $this->pts->getTherapist());
	}

	public function testGetScore()
	{
		$this->assertEquals($this->score, $this->pts->getScore());
	}

	/**
	 * @depends testGetScore
	 */
	public function testSetScore()
	{
		$score = 5;
		$this->assertEquals($score, $this->pts->setScore($score)->getScore());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetScorePassedNull()
	{
		$this->pts->setScore(NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetScorePassedNonNumeric()
	{
		$score = "?";
		$this->pts->setScore($score);
	}
}

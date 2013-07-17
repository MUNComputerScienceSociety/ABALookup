<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\ParentTherapistExclusion,
	AbaLookup\Entity\User,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the ParentTherapistScore entity
 */
class ParentTherapistExclusionTest extends PHPUnit_Framework_TestCase
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
	 * @var AbaLookup\Entity\ParentTherapistExclusion
	 */
	protected $exclusion;

	/**
	 * Reset for isolation
	 */
	public function setUp()
	{
		$this->parent    = new User("Jane", "jane@email.com", "password", FALSE, NULL, FALSE, FALSE);
		$this->therapist = new User("Jack", "jack@email.com", "password", TRUE, "M", TRUE, TRUE);
		$this->exclusion = new ParentTherapistExclusion($this->parent, $this->therapist);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionThrownIfTherapistPassedAsParentInConstructor()
	{
		new ParentTherapistExclusion($this->therapist, $this->therapist);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionThrownIfParentPassedAsTherapistInConstructor()
	{
		new ParentTherapistExclusion($this->parent, $this->parent);
	}

	public function testGetParent()
	{
		$this->assertEquals($this->parent, $this->exclusion->getParent());
	}

	public function testGetTherapist()
	{
		$this->assertEquals($this->therapist, $this->exclusion->getTherapist());
	}

	public function testGetActive()
	{
		$this->assertTrue($this->exclusion->getActive());
	}

	/**
	 * @depends testGetActive
	 */
	public function testSetActive()
	{
		$this->assertFalse($this->exclusion->setActive(FALSE)->getActive());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrowIfSetActivePassedNull()
	{
		$this->exclusion->setActive(NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetActivePassedNonBool()
	{
		$this->exclusion->setActive('');
	}
}

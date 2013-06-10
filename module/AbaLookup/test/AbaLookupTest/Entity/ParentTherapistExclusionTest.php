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
	protected $pte;

	/**
	 * Reset for isolation
	 */
	public function setUp()
	{
		$this->parent = new User("Jane", "jane@email.com", "password", FALSE, NULL, FALSE, FALSE);
		$this->therapist = new User("Jack", "jack@email.com", "password", TRUE, "M", TRUE, TRUE);
		$this->pte = new ParentTherapistExclusion($this->parent, $this->therapist);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionThrownIfTherapistPassedAsParent()
	{
		new ParentTherapistExclusion($this->therapist, $this->therapist);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionThrownIfParentPassedAsTherapist()
	{
		new ParentTherapistExclusion($this->parent, $this->parent);
	}

	public function testGetParent()
	{
		$this->assertEquals($this->parent, $this->pte->getParent());
	}

	public function testGetTherapist()
	{
		$this->assertEquals($this->therapist, $this->pte->getTherapist());
	}

	public function testGetActive()
	{
		$this->assertTrue($this->pte->getActive());
	}

	/**
	 * @depends testGetActive
	 */
	public function testSetActive()
	{
		$this->assertFalse($this->pte->setActive(FALSE)->getActive());
	}
}

<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity @ORM\Table(
 *     name="parent_therapist_exclusion",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="parent_therapist_exclude_idx", columns={"therapist_id", "parent_id"})}
 * )
 */
class ParentTherapistExclusion
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\OneToOne(targetEntity="User")
	 */
	protected $parent;

	/**
	 * @ORM\OneToOne(targetEntity="User")
	 */
	protected $therapist;

	public function __construct($parent, $therapist)
	{
		$this->parent = $parent;
		$this->therapist = $therapist;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function getTherapist()
	{
		return $this->therapist;
	}
}

<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity as DoctrineEntity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\OneToOne,
	Doctrine\ORM\Mapping\Table,
	Doctrine\ORM\Mapping\UniqueConstraint
;

/**
 * @DoctrineEntity
 * @Table(name = "parent_therapist_exclusions", uniqueConstraints = {
 *     @UniqueConstraint(name = "parent_therapist_exclusions_idx", columns = {"therapist_id", "parent_id"})
 * })
 *
 * A parent and therapist pairing that has been flagged not to work
 */
class ParentTherapistExclusion
{
	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue
	 */
	protected $id;

	/**
	 * @OneToOne(targetEntity = "User")
	 */
	protected $parent;

	/**
	 * @OneToOne(targetEntity = "User")
	 */
	protected $therapist;

	/**
	 * @Column(type = "boolean")
	 */
	protected $active;

	/**
	 * Constructor
	 */
	public function __construct(User $parent, User $therapist, $active = TRUE)
	{
		if ($parent->getTherapist() || !$therapist->getTherapist()) {
			throw new \InvalidArgumentException();
		}
		$this->parent = $parent;
		$this->therapist = $therapist;
		$this->active = $active;
	}

	public function setActive($active)
	{
		$this->active = $active;
		return $this;
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

	public function getActive()
	{
		return $this->active;
	}
}

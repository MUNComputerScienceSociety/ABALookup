<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\OneToOne,
	Doctrine\ORM\Mapping\Table,
	Doctrine\ORM\Mapping\UniqueConstraint,
	InvalidArgumentException
;

/**
 * @Entity
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
	 *
	 * @param User $parent The parent in this pairing.
	 * @param User $therapist The therapist in this pairing.
	 * @param bool $active Is this exclusion active?
	 * @throws InvalidArgumentException
	 */
	public function __construct(User $parent, User $therapist, $active = TRUE)
	{
		if ($parent->isTherapist() || !$therapist->isTherapist()) {
			throw new InvalidArgumentException();
		}
		$this->parent = $parent;
		$this->therapist = $therapist;
		$this->active = $active;
	}

	/**
	 * Sets whether or not this exclusion is active.
	 *
	 * An active exclusion will remove the parent and therapist
	 * from their respective matches listings.
	 *
	 * @param bool $active Whether the exclusion is active.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setActive($active)
	{
		if (!isset($active) || !is_bool($active)) {
			throw new InvalidArgumentException();
		}
		$this->active = $active;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Returns the parent in the exclusion
	 *
	 * @return User
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Returns the therapist in the exclusion
	 *
	 * @return User
	 */
	public function getTherapist()
	{
		return $this->therapist;
	}

	/**
	 * @return bool
	 */
	public function isActive()
	{
		return $this->active;
	}
}

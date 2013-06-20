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
 * @Table(name = "parent_therapist_scores", uniqueConstraints = {
 *     @UniqueConstraint(name = "parent_therapist_scores_idx", columns = {"therapist_id", "parent_id"})
 * })
 *
 * A parent and therapist matching score
 */
class ParentTherapistScore
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
	 * @Column(type = "float", precision = 3)
	 */
	protected $score;

	/**
	 * Constructor
	 */
	public function __construct(User $parent, User $therapist, $score)
	{
		if ($parent->getTherapist() || !$therapist->getTherapist()) {
			throw new \InvalidArgumentException();
		}
		if (!is_numeric($score)) {
			throw new \InvalidArgumentException();
		}
		$this->parent = $parent;
		$this->therapist = $therapist;
		$this->score = $score;
	}

	public function setScore($score)
	{
		if (!is_numeric($score)) {
			throw new \InvalidArgumentException();
		}
		$this->score = $score;
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

	public function getScore()
	{
		return $this->score;
	}
}

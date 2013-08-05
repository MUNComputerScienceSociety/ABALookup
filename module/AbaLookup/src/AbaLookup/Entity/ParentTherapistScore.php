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
	 *
	 * @param User $parent The parent for this pairing.
	 * @param User $therapist The therapist for this pairing.
	 * @param numeric $score The score.
	 * @throws InvalidArgumentException
	 */
	public function __construct(User $parent, User $therapist, $score)
	{
		if ($parent->isTherapist() || !$therapist->isTherapist()) {
			throw new InvalidArgumentException();
		}
		if (!is_numeric($score)) {
			throw new InvalidArgumentException();
		}
		$this->parent = $parent;
		$this->therapist = $therapist;
		$this->score = $score;
	}

	/**
	 * Sets the match score for the parent and therapist pairing
	 *
	 * @param $score The score for the parent therapist pairing.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setScore($score)
	{
		if (!isset($score) || !is_numeric($score)) {
			throw new InvalidArgumentException();
		}
		$this->score = $score;
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
	 * @return User
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @return User
	 */
	public function getTherapist()
	{
		return $this->therapist;
	}

	/**
	 * @return float
	 */
	public function getScore()
	{
		return $this->score;
	}
}

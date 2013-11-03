<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\OneToOne,
	Doctrine\ORM\Mapping\Table,
	InvalidArgumentException
;

/**
 * @Entity
 * @Table(name = "pairings")
 *
 * A pairing of two users
 */
class Pairing
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
	protected $a;

	/**
	 * @OneToOne(targetEntity = "User")
	 */
	protected $b;

	/**
	 * @Column(type = "float", precision = 3)
	 */
	protected $score;

	/**
	 * Whether this pairing should be excluded from results
	 *
	 * @Column(type = "boolean")
	 */
	protected $excluded;

	/**
	 * Constructor
	 *
	 * @param User $a The 1st user in this pairing.
	 * @param User $a The 2nd user in this pairing.
	 * @param float $score The score for this pairing.
	 * @throws InvalidArgumentException
	 */
	public function __construct(User $a, User $b, $score)
	{
		if (!isset($score) || !is_float($score)) {
			throw new InvalidArgumentException(sprintf(
				'The score must be numeric.'
			));
		}
		$this->a = $a;
		$this->b = $b;
		$this->score = $score;
		$this->excluded = FALSE; // By default
	}

	/**
	 * Sets the score for the pairing
	 *
	 * @param float $score The score for the pairing.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setScore($score)
	{
		if (!isset($score) || !is_float($score)) {
			throw new InvalidArgumentException(sprintf(
				'The score must be numeric.'
			));
		}
		$this->score = $score;
		return $this;
	}

	/**
	 * Sets whether this pairing is to be excluded from results
	 *
	 * @param bool $excluded This pairing should be excluded from results.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setExcluded($excluded)
	{
		if (!isset($excluded) || !is_bool($excluded)) {
			throw new InvalidArgumentException();
		}
		$this->excluded = $excluded;
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
	public function getA()
	{
		return $this->a;
	}

	/**
	 * @return User
	 */
	public function getB()
	{
		return $this->b;
	}

	/**
	 * @return float
	 */
	public function getScore()
	{
		return $this->score;
	}

	/**
	 * Returns whether this pairing is being excluded from results
	 *
	 * @return bool
	 */
	public function isExcluded()
	{
		return $this->excluded;
	}
}

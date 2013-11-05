<?php

namespace Lookup\Entity;

class Score extends Entity
{
	/**
	 * User A of the pair
	 *
	 * @var User
	 */
	protected $a;

	/**
	 * User B of the pair
	 *
	 * @var User
	 */
	protected $b;

	/**
	 * The combined schedule of the two users
	 *
	 * Calculated via schedule intersection
	 * (i.e. user A's schedule ∩ user B's schedule)
	 *
	 * @var Schedule
	 */
	protected $schedule;

	/**
	 * The score for this user pair
	 *
	 * @var int
	 */
	protected $score;

	/**
	 * @param int $id The ID for this entity.
	 * @param User $a The 1st user of the user pair.
	 * @param User $a The 2nd user of the user pair.
	 * @param Schedule $schedule The combined schedule of the two users.
	 * @param int $score The score for this pair.
	 * @throws Exception\InvalidArgumentException
	 */
	public function __construct($id, User $a, User $b, Schedule $schedule, $score)
	{
		$this->setId($id);
		$this->a = $a;
		$this->b = $b;
		$this->schedule = $schedule;
		$this->setScore($score);
	}

	/**
	 * @param int $score The score for this user pair.
	 * @throws Exception\InvalidArgumentException If the score is not an integer.
	 * @return self
	 */
	public final function setScore($score)
	{
		if (!is_int($score)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'The score must be an integer.'
			));
		}
		$this->score = $score;
		return $this;
	}

	/**
	 * @return User User A in this pair.
	 */
	public function getA()
	{
		return $this->a;
	}

	/**
	 * @return User User B in this pair.
	 */
	public function getB()
	{
		return $this->b;
	}

	/**
	 * @return Schedule The schedule for this pair.
	 */
	public function getSchedule()
	{
		return $this->schedule;
	}

	/**
	 * @return int The score for the user pair.
	 */
	public function getScore()
	{
		return $this->score;
	}
}

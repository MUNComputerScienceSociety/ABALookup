<?php

namespace ABALookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity @ORM\Table(
 *     name="parent_therapist_score",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="parent_therapist_score_idx", columns={"therapist_id", "parent_id"})}
 * )
 **/
class ParentTherapistScore
{
	/**
	 * @ORM\Id @ORM\OneToOne(targetEntity="User")
	 **/
	protected $parent;
	/**
	 * @ORM\Id @ORM\OneToOne(targetEntity="User")
	 **/
	protected $therapist;
	/**
	 * @ORM\Column(type="float")
	 **/
	protected $score;
	
	public function __construct($parent, $therapist, $score)
	{
		
		$this->parent = $parent;
		$this->therapist = $therapist;
		$this->score = $score;
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
	
	public function setScore($score)
	{
		$this->score = $score;
	}
}

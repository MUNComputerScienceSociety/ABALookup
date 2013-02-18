<?php

namespace ABALookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity @ORM\Table(name="parent_therapist_score")
 **/
class ParentTherapistScore
{
	/**
	 * @ORM\Id @ORM\OneToOne(targetEntity="User")
	 **/
	protected $parent_id;
	/**
	 * @ORM\Id @ORM\OneToOne(targetEntity="User")
	 **/
	protected $therapist_id;
	/**
	 * @ORM\Column(type="float")
	 **/
	protected $score;
	
	public function __construct($parent_id, $therapist_id, $score)
	{
		$this->parent_id = $parent_id;
		$this->therapist_id = $therapist_id;
		$this->score = $score;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getParentId()
	{
		return $this->parent_id;
	}
	
	public function getTherapistId()
	{
		return $this->therapist_id;
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
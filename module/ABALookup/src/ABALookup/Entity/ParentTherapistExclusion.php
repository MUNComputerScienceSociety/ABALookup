<?php

namespace ABALookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity @ORM\Table(name="parent_therapist_exclusions")
 **/
class ParentTherapistExclusion
{

	/**
	 * @ORM\Id @ORM\OneToOne(targetEntity="User")
	 **/
	protected $parent_id
	/**
	 * @ORM\Id @ORM\OneToOne(targetEntity="User")
	 **/
	protected $therapist_id
	
	public function __construct($parent_id, $therapist_id)
	{
		$this->parent_id = $parent_id;
		$this->therapist_id = $therapist_id;
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
}
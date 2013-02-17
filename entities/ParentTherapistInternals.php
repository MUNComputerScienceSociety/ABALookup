<?php

#parent_id
#therapist_id
#score (double)

//ParentTherapistInternals.php
class ParentTherapistInternals
{
	protected $id;
	protected $parent_id;
	protected $therapist_id;
	protected $score;
	
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
<?php

#parent_id
#therapist_id

//ParentTherapistExlcusion.php
class ParentTherapistExclusion
{
	protected $id;
	protected $parent_id
	protected $therapist_id
	
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
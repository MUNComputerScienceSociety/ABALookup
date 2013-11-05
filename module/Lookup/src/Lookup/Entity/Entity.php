<?php

namespace Lookup\Entity;

abstract class Entity
{
	/**
	 * The ID for the entity
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * @param int $id The ID for the entity.
	 * @throws Exception\InvalidArgumentException If the ID is not an integer.
	 * @return self
	 */
	protected final function setId($id)
	{
		if (!is_int($id)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects an integer.',
				__METHOD__
			));
		}
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int The ID for the entity.
	 */
	public function getId()
	{
		return $this->id;
	}
}

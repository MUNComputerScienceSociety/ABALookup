<?php

namespace Lookup\Entity\Trait;

trait Uuid
{
	/**
	 * The UUID for the entity
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Set the UUID for the entity
	 *
	 * @param string $id The UUID for the entity.
	 * @throws Exception\InvalidArgumentException If the UUID is not a string.
	 * @return self
	 */
	private final function setId($id)
	{
		if (!is_string($id)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a string. The identifier must be an UUID.',
				__METHOD__
			));
		}
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string The UUID for the entity.
	 */
	public function getId()
	{
		return $this->id;
	}
}

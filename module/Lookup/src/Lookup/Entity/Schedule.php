<?php

namespace Lookup\Entity;

class Schedule
{
	use Trait\Id;

	/**
	 * The name of the schedule
	 *
	 * @var string|NULL
	 */
	private $name;

	/**
	 * Whether the schedule is active
	 *
	 * @var bool
	 */
	private $enabled;

	/**
	 * Constructor
	 *
	 * @param int $id The ID for this entity.
	 * @param string $name The name for the schedule.
	 * @param bool $enabled Is the schedule active?
	 * @throws Exception\InvalidArgumentException
	 */
	public function __construct($id, $name, $enabled)
	{
		$this->setId($id)
		     ->setEnabled($enabled)
		     ->setName($name);
	}

	/**
	 * @param string|NULL $name The name for the schedule.
	 * @throws Exception\InvalidArgumentException If the name is not a string.
	 * @return self
	 */
	public final function setName($name)
	{
		if (!is_string($name) && !is_null($name)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a string or NULL value.',
				__METHOD__
			));
		}
		$this->name = $name;
		return $this;
	}

	/**
	 * @param bool $enabled Whether the schedule should be enabled.
	 * @throws Exception\InvalidArgumentException If not passed a bool value.
	 * @return self
	 */
	public final function setEnabled($enabled)
	{
		if (!is_bool($enabled)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a bool value.',
				__METHOD__
			));
		}
		$this->enabled = $enabled;
		return $this;
	}

	/**
	 * @return string The name for this schedule.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return bool Whether the schedule is enabled.
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}
}

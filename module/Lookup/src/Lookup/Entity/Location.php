<?php

class Location
{
	use Trait\Id;

	/**
	 * The city name
	 *
	 * @var string
	 */
	private $city;

	/**
	 * The postal code
	 *
	 * @var string
	 */
	private $postalCode;

	/**
	 * Constructor
	 *
	 * @param int $id The ID for the entity.
	 * @param string $city The city name.
	 * @param string $postalCode The postal code.
	 */
	public function __construct($id, $city, $postalCode)
	{
		$this->setId($id)
		     ->setCity($city)
		     ->setPostalCode($postalCode);
	}

	/**
	 * @param string $city The city name.
	 * @throws Exception\InvalidArgumentException If the city name is not a string.
	 * @return self
	 */
	public final function setCity($city)
	{
		if (!is_string($city)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a string.',
				__METHOD__
			));
		}
		$this->city = $city;
		return $this;
	}

	/**
	 * @param string $postalCode The postal code.
	 * @throws Exception\InvalidArgumentException If the postal code is not a string.
	 * @return self
	 */
	public final function setPostalCode($postalCode)
	{
		if (!is_string($postalCode)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a string',
				__METHOD__
			));
		}
		$this->postalCode = $postalCode;
		return $this;
	}

	/**
	 * @return The location city.
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @return The location postal code.
	 */
	public function getPostalCode()
	{
		return $this->postalCode;
	}
}

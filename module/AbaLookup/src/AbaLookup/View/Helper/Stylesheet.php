<?php

namespace AbaLookup\View\Helper;

use
	InvalidArgumentException,
	Zend\View\Helper\AbstractHelper
;

/**
 * View helper that generates a stylesheet link
 */
class Stylesheet extends AbstractHelper
{
	/**
	 * Returns a HTML stylesheet link tag
	 *
	 * @param string $filename The filename
	 * @return string
	 */
	public function __invoke($filename)
	{
		if (!isset($filename) || !is_string($filename)) {
			throw new InvalidArgumentException(sprintf(
				'The filename must be a string.'
			));
		}
		return sprintf('<link rel="stylesheet" href="/css/%s">', $filename);
	}
}

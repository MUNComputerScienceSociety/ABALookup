<?php

namespace AbaLookup\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Stylesheet extends AbstractHelper
{
	/**
	 * @param string $filename The filename of the CSS stylesheet.
	 * @return string A HTML stylesheet link tag.
	 * @throws Exception\InvalidArgumentException If the filename is empty.
	 */
	public function __invoke($filename)
	{
		if (!isset($filename) || !is_string($filename) || !$filename) {
			throw new Exception\InvalidArgumentException(sprintf(
				'The filename must be a non-empty string.'
			));
		}
		return sprintf('<link rel="stylesheet" href="/css/%s">', $filename);
	}
}

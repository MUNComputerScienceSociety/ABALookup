<?php

namespace AbaLookup\View\Helper;

use
	InvalidArgumentException,
	Zend\View\Helper\AbstractHelper
;

/**
 * View helper that generates a <script> tag
 */
class Script extends AbstractHelper
{
	/**
	 * Returns a HTML script tag
	 *
	 * @param string $filename The script filename
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function __invoke($filename)
	{
		if (!isset($filename) || !is_string($filename) || !$filename) {
			throw new InvalidArgumentException(sprintf(
				'The filename must be a non-empty string.'
			));
		}
		return sprintf('<script src="/js/%s"></script>', $filename);
	}
}

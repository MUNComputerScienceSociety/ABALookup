<?php

namespace AbaLookup\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Script extends AbstractHelper
{
	/**
	 * @param string $filename The script filename.
	 * @return string A HTML script tag.
	 * @throws Exception\InvalidArgumentException If the filename is invalid.
	 */
	public function __invoke($filename)
	{
		if (!isset($filename) || !is_string($filename) || !$filename) {
			throw new Exception\InvalidArgumentException(sprintf(
				'The filename must be a non-empty string.'
			));
		}
		return sprintf('<script src="/js/%s"></script>', $filename);
	}
}

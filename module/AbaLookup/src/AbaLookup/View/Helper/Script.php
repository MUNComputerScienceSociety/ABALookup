<?php

namespace AbaLookup\View\Helper;

use
	InvalidArgumentException,
	Zend\View\Helper\AbstractHelper
;

/**
 * View helper that generates script tags
 */
class Script extends AbstractHelper
{
	/**
	 * Returns a HTML script tag with the given path
	 *
	 * @param array $parts The individual parts of the path to the JS file
	 */
	public function __invoke(array $parts)
	{
		array_unshift($parts, 'js');
		$path = implode('/', $parts);
		$view = $this->getView();
		return sprintf(
			'<script src="%s"></script>',
			$view->basePath($path)
		);
	}
}

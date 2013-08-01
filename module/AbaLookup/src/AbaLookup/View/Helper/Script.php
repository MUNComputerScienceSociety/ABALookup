<?php

namespace AbaLookup\View\Helper;

use
	InvalidArgumentException,
	Zend\View\Helper\AbstractHelper
;

class Script extends AbstractHelper
{
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

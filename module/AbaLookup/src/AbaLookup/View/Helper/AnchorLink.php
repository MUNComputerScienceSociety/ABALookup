<?php

namespace AbaLookup\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AnchorLink extends AbstractHelper
{
	/**
	 * @param string $text The link text.
	 * @param string $href The link href.
	 * @param array $class The class names for the link.
	 * @return string A HTML anchor link tag.
	 * @throws Exception\InvalidArgumentException
	 */
	public function __invoke($text, $href, array $class = NULL)
	{
		if (
			   !isset($href, $text)
			|| !is_string($text)
			|| !is_string($href)
			|| !$text // Empty string
			|| !$href // Empty string
		) {
			throw new Exception\InvalidArgumentException(sprintf(
				'Both the link text and href must be non-empty strings.'
			));
		}
		if (isset($class) && count($class) > 0) {
			return sprintf(
				'<a href="%s" class="%s">%s</a>',
				$href,
				implode(' ', $class),
				$text
			);
		}
		return sprintf('<a href="%s">%s</a>', $href, $text);
	}
}

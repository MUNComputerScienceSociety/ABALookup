<?php

namespace AbaLookup\Form\View\Helper;

use Zend\Form\FormInterface;
use Zend\Form\View\Helper\Form as ZendFormHelper;

/**
 * View helper that generates a form
 */
class Form extends ZendFormHelper
{
	/**
	 * Returns the HTML markup for the given form
	 *
	 * @param Zend\Form\FormInterface $f The form.
	 * @return string
	 */
	public function markup(FormInterface $f)
	{
		$v = $this->getView();
		$m = '';
		$m .= $this->openTag($f);
		foreach ($f as $e) {
			$t = $e->getAttribute('type');
			$l = $e->getLabel();
			// To style checkboxes and radio buttons, the label needs to come after the element
			// In all other cases, it is more semantic for the label to come before the element
			$content = ($l && ($t == 'checkbox' || $t == 'radio')) ?
			           ('<div class="checkbox">' . $v->formElement($e) . $v->formLabel($e, $l) . '</div>') :
			           (($l) ? ('' . $v->formLabel($e, $l) . $v->formElement($e)) : $v->formElement($e));
			$m .= sprintf(
				'<div class="row">
				   <div class="twelve columns">
				     %s
				   </div>
				 </div>',
				 $content
			);
		}
		$m .= $this->closeTag();
		return $m;
	}
}

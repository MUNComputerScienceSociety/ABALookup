<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use
	Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
	Doctrine\ORM\EntityManager
;

abstract class ApplicationController extends AbstractActionController {
	
	/**
	Ê* @var Doctrine\ORM\EntityManager
	Ê*/
	private $em;

	public function getEntityManager() {
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		}
		return $this->em;
	}
	
}

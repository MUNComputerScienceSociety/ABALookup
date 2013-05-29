<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace AbaLookup;

use
	Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
	Doctrine\ORM\EntityManager,
	Zend\Session\Container
;

abstract class AbaLookupController extends AbstractActionController {

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	private $em;

	public function getEntityManager() {
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		}
		return $this->em;
	}

	public function loggedIn() {
		$session = new Container();
		return $session->offsetExists("loggedIn");
	}

	public function currentUser() {
		$session = new Container();
		return $this->getEntityManager()
		            ->getRepository('AbaLookup\Entity\User')
		            ->findOneBy(array(
		            	'id' => $session->offsetGet("loggedIn")
		            ));
	}

}

<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use
	Zend\View\Model\ViewModel,
	Application\ApplicationController,
	Application\Entity\User
;

class IndexController extends ApplicationController
{
	public function indexAction()
	{
		
		$em = $this->getEntityManager();
		
		$user = new User(
			"example@example.com",
			"my_pass",
			true,
			"male",
			"19009342",
			true,
			true
		);
		
		$em->persist($user);
		$em->flush();
		
		
		var_dump($em->getRepository('Application\Entity\User')->findBy(array('email' => 'example@example.com')));
		
		$boo = "Hello there";
	
		return new ViewModel(array(
			"boo" => $boo
		));
	}
}

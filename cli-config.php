<?php
//cli-config.php
require_once "bootstrap.php"

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
	'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManager\Helper($entityManager)
));	
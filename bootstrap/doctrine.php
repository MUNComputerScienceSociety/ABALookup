<?php
//bootstrap_doctrine.php

//see :doc: Configuration <../reference/configuration> for up to deate autoloading details.
require_once "vendor/autoload.php";

//Create a simple "default" Doctrine ORM configuration for XML Mapping
$isDevMode = true;
$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);

###REPLACED THE FOLLOWING WITH FINAL DATABASE INFORMATION.####
//database configuration parameters
$conn = array(
	'driver' => 'pdo_sqlite',
	'path' => __DIR__ . '/db.sqlite',
	);
	
//obtaining the entity manager.
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);
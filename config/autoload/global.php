<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 */

return [
	'doctrine' => [
		'connection' => [
			// default connection name
			'orm_default' => [
				'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
				'params'      => [
					'path' => 'database/db.sqlite3',
				],
			],
		],
	],
];

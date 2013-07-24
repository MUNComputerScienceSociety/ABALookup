<?php

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

<?php

use Lookup\Api;

return [
	'factories' => [
		'Lookup\Api\UserAccount' => function ($sm) {
			return new Api\UserAccount();
		},
		'Lookup\Api\Schedule' => function ($sm) {
			return new Api\Schedule();
		}
	]
];

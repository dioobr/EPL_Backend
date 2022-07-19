<?php

$config = [
	'ignore_https_availability' => false,
	'sportsdb' => [
		'pms' => [
			'api_key' => "50130162",
			'league_id' => "4328"
		],
		'endpoints' => [
			'eventspastleague' => [
				'url' => "https://www.thesportsdb.com/api/v1/json/{api_key}/eventspastleague.php?id={league_id}"
			],
			'lookupteam' => [
				'url' => "https://www.thesportsdb.com/api/v1/json/{api_key}/lookupteam.php?id={team_id}"
			]
		]
	],
	'cache_dir' => basedir.ds.'cache'
];
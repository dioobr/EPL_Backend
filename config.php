<?php

$config = [
	'ignore_https_availability' => false, //if you want to accept requests without SSL, set TRUE on this parameter;
	'sportsdb' => [
		'pms' => [
			'api_key' => "50130162", //The API Key to process special requests on the TheSportsDB.com service;
			'league_id' => "4328" //The League ID to query data about events and teams;
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
	'cache_dir' => basedir.ds.'cache', //the cache dir is used to store teams badges;
	'allowed_origins' => [ //set here what origins are allowed to make requests to this API;
		'localhost',
		'epl.dioobr.com'
	]
];
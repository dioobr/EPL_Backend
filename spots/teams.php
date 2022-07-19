<?php

$se = ikex($uri, 1); //uses the second part of the URI to set what endpoint will be called;
if(empty($se)) cer_error("Resource name not provided or invalid.");

/*
 * This function process the endpoint "badge", what the main objective is to return a team badge (picture);
*/
function teams_se_badge(){
	global $uri, $api;
	$is_tiny = (ikex($uri, 2) == "tiny"); //detect if the third part of the URI is settled as "tiny";
	$team_id = ikex($uri, ($is_tiny ? 3 : 2)); //if the previous line is true, this line will use the next URI position to get the team ID;
	if(substr($team_id, -4) != ".png") cer_error("Invalid request.", "203", 400); //this endpoint address needs to be ended with ".png", if not, returns an error;
	
	$team_id = substr($team_id, 0, -4); //extract the team ID and check if is valid;
	if(empty($team_id) || !is_numeric($team_id))  cer_error("Team ID not provided or invalid.", "206", 400);
	
	/*
	 * This block of the code, check if badge/picture already exists in cache, if not, call TheSportsDB service to get the team badge, create a cache and return it as a picture;
	*/
	$cache_dir = $api->get_cache_dir();
	$cfi = $cache_dir.ds.'team_'.$team_id.($is_tiny?'_tiny':'').'.png';
	if(file_exists($cfi)){
		$img = @file_get_contents($cfi);
		if($img === false) cer_error("Failed to get the team badge.", "518", 500);
	} else {
		$service = new service();
		$gc = $service->get_team_badge((int) $team_id);
		if($gc['state'] == 'error') cer_error("Failed to get the team badge.", "508", 500);
	
		$img_url = ($is_tiny ? $gc['data']['tiny'] : $gc['data']['standard']);
		$img = @file_get_contents($img_url);
		if($img === false) cer_error("Failed to get the team badge.", "525", 500);
		@file_put_contents($cfi, $img);
	}
	
	header("Content-Type: image/png");
	exit($img);
}

/*
 * Here the code checks if a endpoint (function) exists, if yes, call it;
*/
if(!function_exists('teams_se_'.$se)) cer_error("Resource not found or not available.");
call_user_func('teams_se_'.$se);
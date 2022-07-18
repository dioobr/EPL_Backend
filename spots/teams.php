<?php

$se = ikex($uri, 1);
if(empty($se)) cer_error("Resource name not provided or invalid.");

function teams_se_badge(){
	global $uri;
	$is_tiny = (ikex($uri, 2) == "tiny");
	$team_id = ikex($uri, ($is_tiny ? 3 : 2));
	if(substr($team_id, -4) != ".png") cer_error("Invalid request.", "203", 400);
	
	$team_id = substr($team_id, 0, -4);	
	if(empty($team_id) || !is_numeric($team_id))  cer_error("Team ID not provided or invalid.", "206", 400);
	
	$service = new service();
	$gc = $service->get_team_badge((int) $team_id);
	if($gc['state'] == 'error') cer_error("Failed to get the team badge.", "508", 500);

	$img_url = ($is_tiny ? $gc['data']['tiny'] : $gc['data']['standard']);
	$img = @file_get_contents($img_url);
	if($img === false) cer_error("Failed to get the team badge.", "508", 500);
	
	header("Content-Type: image/png");
	exit($img);
}

if(!function_exists('teams_se_'.$se)) cer_error("Resource not found or not available.");
call_user_func('teams_se_'.$se);
<?php

$se = ikex($uri, 1); //uses the second part of the URI to set what endpoint will be called;
if(empty($se)) cer_error("Resource name not provided or invalid.");

/*
 * This function process the endpoint "past"
*/
function events_se_past(){
	$service = new service();
	$gc = $service->get_past_events();
	cer($gc);
}

/*
 * Here the code checks if a endpoint (function) exists, if yes, call it;
*/
if(!function_exists('events_se_'.$se)) cer_error("Resource not found or not available.");
call_user_func('events_se_'.$se);
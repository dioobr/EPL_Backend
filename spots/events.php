<?php

$se = ikex($uri, 1);
if(empty($se)) cer_error("Resource name not provided or invalid.");

function events_se_past(){
	$service = new service();
	$gc = $service->get_past_events();
	cer($gc);
}

if(!function_exists('events_se_'.$se)) cer_error("Resource not found or not available.");
call_user_func('events_se_'.$se);
<?php

require_once(basedir.ds.'config.php');

spl_autoload_register(function($class_name){
	if(class_exists($class_name)) return true;
	$cfn = basedir.ds.'classes'.ds.$class_name.'.class.php';
	if(!file_exists($cfn)) return false;
	require_once($cfn);
});

/*
 * This global variable is used by the "cer" function.
 * It stores a callback to be executed according to the API response.
*/
$gb_cer = [
	'on_done' => false,
	'on_success' => false,
	'on_error' => false
];

/*
 * This function is used to return the API response.
 * Is the last thing to be executed on the script.
*/
function cer($p = []){
	global $api, $gb_cer;
	if(array_key_exists('state', $p) && in_array($p['state'], ['ok', 'error'])) $p['error'] = $p['state'] == 'error';
	$p = array_merge([
		'error' => false,
		'code' => null,
		'message' => "",
		'http_code' => null,
		'data' => null
	], $p);

	http_response_code(empty($p['http_code']) ? 200 : $p['http_code']);
	
	if($p['error'] === true && ($gb_cer['on_error'] && is_callable($gb_cer['on_error']))) call_user_func($gb_cer['on_error']);
	if($p['error'] === false && ($gb_cer['on_success'] && is_callable($gb_cer['on_success']))) call_user_func($gb_cer['on_success']);
	if($gb_cer['on_done'] && is_callable($gb_cer['on_done'])) call_user_func($gb_cer['on_done']);

	/*
	 * By default, the responde code "Y001" means that everything was executed successfully.
	 * The response for requests without erros, always starts with "Y";
	 * The code "E009" is the default code for errors. If the response is a error, the code will start with "E";
	*/
	$arr['response'] = [
		'code' => ($p['error'] === true ? 'E' : 'Y').(is_null($p['code']) ? ($p['error'] === true ? '009' : '001') : $p['code']),
		'message' => $p['message']
	];	

	if(!is_null($p['data'])) $arr['data'] = $p['data'];
	if(!defined('STDIN')) echo json_encode($arr); //STDIN doesn't print a response if the code is being executed in command line
	exit();
}

/*
 * This function is designed to call the "cer" function with a error to be returned as response;
*/
function cer_error($msg, $code = null, $http_code = null){
	if(is_array($msg)){
		$code = ikex($msg, 'code');
		$http_code = ikex($msg, 'http_code');
		$msg = ikex($msg, 'message');
	}
	cer(['error' => true, 'code' => $code, 'message' => $msg, 'http_code' => $http_code]);
}

/*
 * This function check if a key exists in an array, if yes, return the key value, if not, return the default value set in the $default argument;
*/
function ikex(array $array, $key, $default = "", $ignore_if = []){
	if(!is_array($array)) $array = [];
	return (array_key_exists($key, $array) && !in_array($array[$key], $ignore_if)) ? $array[$key] : $default;
}
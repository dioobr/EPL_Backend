<?php

require_once(basedir.ds.'config.php');

spl_autoload_register(function($class_name){
	if(class_exists($class_name)) return true;
	$cfn = basedir.ds.'classes'.ds.$class_name.'.class.php';
	if(!file_exists($cfn)) return false;
	require_once($cfn);
});

$gb_cer = [
	'on_done' => false,
	'on_success' => false,
	'on_error' => false
];

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

	$arr['response'] = [
		'code' => ($p['error'] === true ? 'E' : 'Y').(is_null($p['code']) ? ($p['error'] === true ? '009' : '001') : $p['code']),
		'message' => $p['message']
	];	

	if(!is_null($p['data'])) $arr['data'] = $p['data'];
	if(!defined('STDIN')) echo json_encode($arr);
	exit();
}

function cer_error($msg, $code = null, $http_code = null){
	if(is_array($msg)){
		$code = ikex($msg, 'code');
		$http_code = ikex($msg, 'http_code');
		$msg = ikex($msg, 'message');
	}
	cer(['error' => true, 'code' => $code, 'message' => $msg, 'http_code' => $http_code]);
}

function ikex($array, $key, $default = "", $ignore_if = []){
	if(!is_array($array)) $array = [];
	return (array_key_exists($key, $array) && !in_array($array[$key], $ignore_if)) ? $array[$key] : $default;
}
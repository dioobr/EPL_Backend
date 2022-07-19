<?php

header("content-type: application/json; charset=utf-8");

define('basedir', dirname(__FILE__));
define('ds', DIRECTORY_SEPARATOR);

require_once(basedir.ds.'global.php');

$http_origin = rtrim(ikex($_SERVER, 'HTTP_ORIGIN', ""), '/');
if(!empty($http_origin)){
	$pur = parse_url($http_origin);
	$host = ikex($pur, 'host');
	if(!empty($host) && (!empty($config['allowed_origins']) && in_array($host, $config['allowed_origins']))){
		header("Access-Control-Allow-Origin: ".$http_origin);
	}
}
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: X-Requested-With");

$api = new api();
$api->require_https();

$uri = $_SERVER['REQUEST_URI'];
$uri = trim($uri, '/');
$uri = (!empty($uri) ? explode('/', $uri) : []);
	
$sp = ikex($uri, 0);
$spfi = basedir.ds.'spots'.ds.$sp.'.php';
if(empty($sp) || !file_exists($spfi)) cer_error("Resource not found.", '404');
include($spfi);

cer_error("No resource content!");
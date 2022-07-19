<?php

header("content-type: application/json; charset=utf-8");

define('basedir', dirname(__FILE__)); //set de base directory of the running project
define('ds', DIRECTORY_SEPARATOR);

require_once(basedir.ds.'global.php');

$http_origin = rtrim(ikex($_SERVER, 'HTTP_ORIGIN', ""), '/'); //get the origin of the frontend that is requesting.
if(!empty($http_origin)){
	$pur = parse_url($http_origin);
	$host = ikex($pur, 'host');
	if(!empty($host) && (!empty($config['allowed_origins']) && in_array($host, $config['allowed_origins']))){
		header("Access-Control-Allow-Origin: ".$http_origin);
	}
}
header("Access-Control-Allow-Methods: GET"); //this API is only for GET requests

$api = new api();
$api->require_https(); //check if the request is using the HTTPS protocol, if not, stop the execution and return a message

//this block of the script is extract the address URI, split it in an array to be used as route
$uri = $_SERVER['REQUEST_URI'];
$uri = trim($uri, '/');
$uri = (!empty($uri) ? explode('/', $uri) : []);
	
$sp = ikex($uri, 0); //get the first part of the URI and use it to set what spot will be used;
$spfi = basedir.ds.'spots'.ds.$sp.'.php';
if(empty($sp) || !file_exists($spfi)) cer_error("Resource not found.", '404');
include($spfi);

cer_error("No resource content!");
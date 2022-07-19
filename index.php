<?php

header("content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: X-Requested-With");

define('basedir', dirname(__FILE__));
define('ds', DIRECTORY_SEPARATOR);

require_once(basedir.ds.'global.php');

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
<?php

header("content-type: application/json; charset=utf-8");

define('basedir', dirname(__FILE__));
define('ds', DIRECTORY_SEPARATOR);

require_once(basedir.ds.'global.php');

$api = new api();


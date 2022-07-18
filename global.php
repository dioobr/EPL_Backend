<?php

require_once(basedir.ds.'config.php');

spl_autoload_register(function($class_name){
	if(class_exists($class_name)) return true;
	$cfn = basedir.ds.'classes'.ds.$class_name.'.class.php';
	if(!file_exists($cfn)) return false;
	require_once($cfn);
});


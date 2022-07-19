<?php

/*
 * This class is the center of the project and should be used to process the main API functions;
*/

class api {
	
	private $post_data = null;
	private $cache_dir; //to store the cache dir on the class instance;
	
	public function __construct(){
		global $config;
		$this->cache_dir = $config['cache_dir']; //get the cache dir of the config and stores it on the class instance;
	}
	
	/*
	 * This method is designed to return a default state for all class methods.
	*/
	static function state($state = 'ok', $message = "", $code = null, $data = null, $http_code = 200){
		if(is_array($state)) return $state;
		return [
			'state' => $state,
			'message' => $message,
			'data' => $data,
			'code' => $code,
			'http_code' => $http_code
		];
	}	
	
	/*
	 * Check if the HTTPS request is using SSL;
	*/	
	public function on_https(){
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');
	}	
	
	/*
	 * When called, this method will check if the API request is using SSL;
	 * It uses the "ignore_https_availability" config parameter;
	*/		
	public function require_https(){
		global $config;
		$ig = ikex($config, 'ignore_https_availability', true);
		if(!$ig && !$this->on_https()) cer_error("HTTPS is required for this request.");
	}
	
	public function get_cache_dir(){
		return $this->cache_dir;
	}
}
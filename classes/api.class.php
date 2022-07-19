<?php

class api {
	
	private $post_data = null;
	private $cache_dir;
	
	public function __construct(){
		global $config;
		$this->cache_dir = $config['cache_dir'];
	}
	
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
	
	public function on_https(){
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');
	}	
	
	public function require_https(){
		global $config;
		$ig = ikex($config, 'ignore_https_availability', true);
		if(!$ig && !$this->on_https()) cer_error("HTTPS is required for this request.");
	}
	
	public function get_post_data(){
		if(!is_null($this->post_data)) return $this->post_data;
		$inp = file_get_contents('php://input');
		$inp = (empty($inp) ? [] : @json_decode($inp, true));
		$this->post_data = ((empty($inp) || is_null($inp)) ? [] : $inp);	
	}
	
	public function get_cache_dir(){
		return $this->cache_dir;
	}
}
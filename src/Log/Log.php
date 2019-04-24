<?php

namespace Ethansmart\HttpBuilder\Log;

/**
 * Class Log
 * @package Ethansmart\HttpBuilder\Log
 * Support Method Overload
 */

class Log
{
	protected $log_path;

	// construct
	function __construct(){
		$this->log_path = __DIR__."/http_request_default_".date("Y-m-d").".log";
	}

	// call static method
	public static function __callStatic($name,$arguments){
		$instance = new static();
		$method = $name."Log";
		if(!is_array($arguments)) $arguments = (array)$arguments;
		$instance->$method(json_encode($arguments)."\n\n");
	}

	// debug
	public function debugLog($msg){
		if(!empty($msg)){
			file_put_contents($this->log_path, date("Y-m-d H:i:s")." debug:".$msg, FILE_APPEND);
		}

	}

	// info
	public function infoLog($msg){
		if(!empty($msg)){
			file_put_contents($this->log_path, date("Y-m-d H:i:s")." info:".$msg, FILE_APPEND);
		}

	}

	// error
	public function errorLog($msg){
		if(!empty($msg)){
			file_put_contents($this->log_path, date("Y-m-d H:i:s")." error:".$msg, FILE_APPEND);
		}
	}

}


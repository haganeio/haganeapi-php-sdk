<?php 
namespace Hagane;

class Hagane {
	private $apiurl;
	private $curl;

	public function __construct($url = null){
		$this->apiurl = $url;
		$this->curl = curl_exec($url);
	}

	public function hello(){
		return 'Hello World';
	}

	public get($url){
		return curl_exec($curl);		
	}
}

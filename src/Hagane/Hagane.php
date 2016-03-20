<?php 
namespace Hagane;

class Hagane {
	private $apiurl;
	
	public function __construct($url = null){
		$this->apiurl = $url;
	}

	public function hello(){
		return 'Hello World';
	}

	public function get($url){
		if(substr($url, 0) == '/'){
			$url = $this->apiurl . $url;
		} else {
			$url = $this->apiurl . '/' . $url;
		}
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,		
			CURLOPT_USERAGENT => 'Hagane PHP SDK'
		));
		return curl_exec($curl);
	}
}

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

	public function get($url, $accessToken = null, $getparams = null){
		if(substr($url, 0, 1) == '/'){
			$url = $this->apiurl . $url;
		} else {
			$url = $this->apiurl . '/' . $url;
		}

		if (!empty($accessToken)) {
			$url .= '?';
			if (!empty($getparams)) {
				foreach ($getparams as $key => $param) {
					$url .= $key.'='.$param.'&';
				}
			}
			$url .= 'accessToken='.$accessToken;
		} else {
			$url .= '?';
			if (!empty($getparams)) {
				foreach ($getparams as $key => $param) {
					$url .= $key.'='.$param.'&';
				}
			}
			$url = substr($url, 0, -1);
		}

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,		
			CURLOPT_USERAGENT => 'Hagane PHP SDK'
		));
		$response = curl_exec($curl);
		$json = json_decode($response, true);
		
		if(!empty($json['error'])){
			return json_encode($json['error']);
		} elseif(!empty($json['success'])){
			unset($json['success']);
			return $json['message'];
		}
	}
	
	public function post($url, $params, $accessToken = null){
		if(substr($url, 0, 1) == '/'){
			$url = $this->apiurl . $url;
		} else {
			$url = $this->apiurl . '/' . $url;
		}

		if (!empty($accessToken)) {
			$params['accessToken'] = $accessToken;
		}
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,		
			CURLOPT_USERAGENT => 'Hagane PHP SDK',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => json_encode($params)
		));
		$response = curl_exec($curl);
		$json = json_decode($response, true);
		
		if(!empty($json['error'])){
			return json_encode($json['error']);
		} elseif(!empty($json['success'])){
			unset($json['success']);
			return $json['message'];
		}
	}
}
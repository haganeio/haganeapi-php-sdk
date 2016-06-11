<?php 
namespace Hagane;

class Hagane {
	protected $apiurl;
	protected $debug = false;
	
	public function __construct($url = null, $debug = false){
		$this->apiurl = $url;
		$this->debug = $debug;
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
		if ($this->debug) {
			echo '<pre>';
			echo $url.'<br>';
			var_dump($response);
			echo '</pre>';
		}
		$json = json_decode($response, true);
		
		if(!empty($json['error'])){
			return json_encode($json['error']);
		} elseif(!empty($json['success'])){
			//unset($json['success']);
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
		if ($this->debug) {
			echo '<pre>';
			echo $url.'<br>';
			var_dump($response);
			echo '</pre>';
		}
		$json = json_decode($response, true);
		
		if(!empty($json['error'])){
			return json_encode($json['error']);
		} elseif(!empty($json['success'])){
			//unset($json['success']);
			return $json['message'];
		}
	}

	public function delete($url, $accessToken = null, $getparams = null){
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
			CURLOPT_CUSTOMREQUEST => 'DELETE',
			CURLOPT_USERAGENT => 'Hagane PHP SDK'
		));
		$response = curl_exec($curl);
		if ($this->debug) {
			echo '<pre>';
			echo $url.'<br>';
			var_dump($response);
			echo '</pre>';
		}
		$json = json_decode($response, true);
		
		if(!empty($json['error'])){
			return json_encode($json['error']);
		} elseif(!empty($json['success'])){
			//unset($json['success']);
			return $json['message'];
		}
	}
}

class HaganeAPI {
	private static $instance;
	protected $parentApiUrl;

	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	protected function  __construct(){
	}
	private function __clone(){
	}
	private function __wakeup(){
	}

	public function setApiUrl($parentApiUrl) {
		$this->parentApiUrl = $parentApiUrl;
	}

	public function getApiUrl() {
		return $this->parentApiUrl;
	}
}

class Post extends HaganeAPI {
	private $success;
	private $message;
	private $apiurl;

	public function __construct($url, $params, $accessToken = null, $debug = null) {
		//$this->apiurl = $apiurl;
		$parent = \Hagane\HaganeAPI::getInstance();
		$this->apiurl = $parent->getApiUrl();

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
		if ($debug) {
			echo '<pre>';
			echo $url.'<br>';
			var_dump($response);
			echo '</pre>';
		}
		$json = json_decode($response, true);
		
		if(!empty($json['error'])){
			//return json_encode($json['error']);
			$this->message = json_encode($json['error']);
			$this->success = false;
		} elseif(!empty($json['success'])){
			unset($json['success']);
			//return $json['message'];
			$this->message = $json['message'];
			$this->success = true;
		}
	}

	public function then($success, $failed = null) {
		if ($this->success) {
			$success($this->message);
		} else {
			$failed($this->message);
		}
	}
}
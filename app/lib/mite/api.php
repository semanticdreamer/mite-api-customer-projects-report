<?php
/**
* The mite. Api
* @package Mite
* @author Matthias Geisler
*/
class Mite_Api
{
	private $account;
	private $apiKey;
	
	const MITE_HTTP_SCHEMA = 'https';
	const MITE_DOMAIN = 'mite.yo.lk';
	const REQUEST_TIMEOUT = 5;
	
	/** Singleton instance */
	private static $_instance = null;
	
	/**
	* Act as singleton
	*/
	private function __construct() { }
	private function __clone() { }
	
	/**
	* Returns a singleton of the Api.
	* @return Mite_Api
	*/
	public static function getInstance()
	{
		if(!self::$_instance) { 
			self::$_instance = new self(); 
		}
		
		return self::$_instance; 
	}
	
	/**
	 * Initialize class with mite. API credentials
	 * @param string $account, i.e. subdomain
	 * @param string $apiKey
	 * @return void
	 **/
	public function init($account, $apiKey)
	{
		if (!$account || !$apiKey) {
			throw new Exception('Error: API key or account (i.e. subdomain) missing!');
			exit;
		}
		
		$this->account = $account;
		$this->apiKey = $apiKey;
	}
	
	/**
	 * Do HTTP GET request and return plain response
	 * @param string $resourcePath, e.g. '/customers.xml', '/projects.xml', '/projects/12345.xml'
	 * @return string $response
	 **/
	public function sendGetRequest($resourcePath)
	{
		$requestUrl = self::MITE_HTTP_SCHEMA.'://'.
			$this->account.'.'
			.self::MITE_DOMAIN.
			$resourcePath.
			'?api_key='.$this->apiKey;
		$curl = curl_init();
		curl_setopt ($curl, CURLOPT_URL,$requestUrl);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, self::REQUEST_TIMEOUT);
		$response = curl_exec($curl);
		curl_close($curl);
		
		return $response;
	}
	
	/**
	 * Filter resources array by key/ value,
	 * e.g. to find resource in collection.
	 * @param string $resource, e.g. 'customer'
	 * @param string $key
	 * @param string $value
	 * @param string $data, i.e. array to filter
	 * @return array $results
	 **/
	public static function getResourceByKeyValue($resource, $key, $value, $data)
	{
		$results = array_filter($data, 
			function ($result) use($resource, $key, $value)
			{
				return $result[$resource][$key] == $value;
			}
		);
		return $results[1][$resource];
	}
}
?>
<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/9/2015
 * Time: 8:38 PM
 */

namespace FaceSDK;


use FaceSDK\Exception\FaceAPIException;
use FaceSDK\FileUpload\FaceFile;
use GuzzleHttp\Client;

class FaceSDK {
	const VERSION = '1.0.0';
	/** @var  string */
	protected $apiKey;
	/** @var  string */
	protected $apiSecret;
	/** @var  string */
	protected $apiUrl;
	/** @var FaceClient  */
	protected $client;

	/**
	 * FaceSDK constructor.
	 *
	 * @param string $apiKey
	 * @param string $apiSecret
	 * @param string $apiUrl
	 */
	public function __construct( $apiKey, $apiSecret, $apiUrl ) {
		$this->apiKey    = $apiKey;
		$this->apiSecret = $apiSecret;
		$this->apiUrl    = $apiUrl;
		$this->client = new FaceClient($this->apiUrl);
	}
	/**
	 * Factory to create FacebookFile's.
	 *
	 * @param string $pathToFile
	 *
	 * @return FaceFile
	 *
	 * @throws FaceAPIException
	 */
	public function fileToUpload($pathToFile)
	{
		return new FaceFile($pathToFile);
	}
	/**
	 * @return string
	 */
	public function getApiKey() {
		return $this->apiKey;
	}

	/**
	 * @param string $apiKey
	 */
	public function setApiKey( $apiKey ) {
		$this->apiKey = $apiKey;
	}

	/**
	 * @return string
	 */
	public function getApiSecret() {
		return $this->apiSecret;
	}

	/**
	 * @param string $apiSecret
	 */
	public function setApiSecret( $apiSecret ) {
		$this->apiSecret = $apiSecret;
	}

	/**
	 * @return string
	 */
	public function getApiUrl() {
		return $this->apiUrl;
	}

	/**
	 * @param string $apiUrl
	 */
	public function setApiUrl( $apiUrl ) {
		$this->apiUrl = $apiUrl;
	}
	/**
	 * @return Client
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * Make a get request
	 *
	 * @param $endpoint
	 *
	 * @return FaceResponse
	 */
	public function get( $endpoint ) {
		return $this->sendRequest(
			'GET',
			$endpoint,
			$params = [ ]
		);
	}

	/**
	 * post a request
	 *
	 * @param string $endpoint
	 * @param array  $params
	 *
	 * @return FaceResponse
	 */
	public function post( $endpoint, array $params = [ ] ) {
		return $this->sendRequest(
			'POST',
			$endpoint,
			$params
		);
	}

	/**
	 * send a request
	 *
	 * @param string $method
	 * @param string $endpoint
	 * @param array  $params
	 *
	 * @return FaceResponse
	 */
	public function sendRequest( $method, $endpoint, array $params = [ ] ) {

		$request = $this->request($method, $endpoint, $params);

		return $this->client->sendRequest($request);
	}

	/**
	 * @param       $method
	 * @param       $endpoint
	 * @param array $params
	 *
	 * @return FaceRequest
	 */
	public function request($method, $endpoint, array $params = [ ]){
		return new FaceRequest(
			$this->getApiKey(),
			$this->getApiSecret(),
			$this->getApiUrl(),
			$method,
			$endpoint,
			$params
		);
	}
}
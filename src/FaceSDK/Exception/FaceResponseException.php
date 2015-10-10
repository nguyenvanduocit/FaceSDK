<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/9/2015
 * Time: 10:39 PM
 */

namespace FaceSDK\Exception;


use FaceSDK\FaceResponse;

class FaceResponseException extends FaceAPIException {
	/**
	 * @var FaceResponse The response that threw the exception.
	 */
	protected $response;

	/**
	 * @var array Decoded response.
	 */
	protected $responseData;

	/**
	 * Creates a FacebookResponseException.
	 *
	 * @param FaceResponse     $response          The response that threw the exception.
	 * @param FaceAPIException $previousException The more detailed exception.
	 */
	public function __construct( FaceResponse $response, FaceAPIException $previousException = null ) {
		$this->response     = $response;
		$this->responseData = $response->getDecodedBody();

		$errorMessage = $this->get( 'error', 'Unknown error from Server.' );
		$errorMessage = static::formatMessage($errorMessage);

		$errorCode = $this->get( 'error_code', - 1 );
		parent::__construct( $errorMessage, $errorCode, $previousException );
	}

	protected static function formatMessage( $errorMessage ) {
		$errorMessage = str_replace( '_', ' ', $errorMessage );
		return strtolower( $errorMessage );
	}

	public static function create( FaceResponse $response ) {
		$data    = $response->getDecodedBody();
		$code    = $data->error_code;
		$message = static::formatMessage($data->error);
		switch ( $code ) {
			case 1001:
				return new static( $response, new FaceInternalException( $message, $code ) );
			case 1003:
				return new static( $response, new FaceAuthorizationException( $message, $code ) );
		}

		// All others
		return new static( $response, new FaceOtherException( $message, $code ) );
	}

	/**
	 * Checks isset and returns that or a default value.
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	private function get( $key, $default = null ) {
		if (  property_exists($this->responseData, $key) ) {
			return $this->responseData->$key;
		}

		return $default;
	}
}
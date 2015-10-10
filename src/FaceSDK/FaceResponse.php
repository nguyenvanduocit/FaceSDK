<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/9/2015
 * Time: 10:27 PM
 */

namespace FaceSDK;


use FaceSDK\Exception\FaceAPIException;
use FaceSDK\Exception\FaceResponseException;
use FaceSDK\Node\Edge;
use FaceSDK\Node\NodeFactory;

class FaceResponse {
	/** @var  int */
	protected $httpStatusCode;
	/** @var  array */
	protected $headers;
	/** @var  string */
	protected $body;
	/** @var  array */
	protected $decodedBody;
	/** @var  FaceRequest */
	protected $request;
	protected $thrownException;

	/**
	 * FaceResponse constructor.
	 *
	 * @param int         $httpStatusCode
	 * @param array       $headers
	 * @param string      $body
	 * @param array       $decodeBody
	 * @param FaceRequest $request
	 */
	public function __construct(  FaceRequest $request,$body = null, $httpStatusCode = null, array $headers = []) {
		$this->httpStatusCode = $httpStatusCode;
		$this->headers        = $headers;
		$this->body           = $body;
		$this->request        = $request;
		$this->decodeBody();
	}

	/**
	 * Decode the body from string to json object
	 */
	public function decodeBody(){
		$this->decodedBody = json_decode($this->body);
		if ($this->decodedBody === null) {
			$this->decodedBody = [];
			parse_str($this->body, $this->decodedBody);
		}
		if (!is_object($this->decodedBody)) {
			$this->decodedBody = [];
		}

		if ($this->isError()) {
			$this->makeException();
		}
	}

	/**
	 * @param null $subclassName
	 *
	 * @return null
	 */
	public function getObject($subclassName = null){
		return $this->getNode($subclassName);
	}

	/**
	 * @param null $subclassName
	 *
	 * @return null
	 */
	public function getNode($subclassName = null)
	{
		$factory = new NodeFactory($this);

		return $factory->makeGraphNode($subclassName);
	}
	/**
	 * Instantiate a new GraphEdge from response.
	 *
	 * @param string|null $subclassName The GraphNode subclass to cast list items to.
	 * @param boolean     $auto_prefix  Toggle to auto-prefix the subclass name.
	 *
	 * @return Edge
	 *
	 * @throws FacebookSDKException
	 */
	public function getEdge($subclassName = null, $auto_prefix = true)
	{
		$factory = new GraphNodeFactory($this);

		return $factory->makeGraphEdge($subclassName, $auto_prefix);
	}
	/**
	 * @return Node\DetectedImage
	 * @throws FaceAPIException
	 */
	public function getGraphDetectedImage()
	{
		$factory = new NodeFactory($this);

		return $factory->makeGraphDetectedImage();
	}

	public function getGraphGroupPersonList(){
		$factory = new NodeFactory($this);
		return $factory->makeGraphGroupPersonList();
	}
	/**
	 * Returns true if server returned an error message.
	 *
	 * @return boolean
	 */
	public function isError()
	{
		return isset($this->decodedBody->error);
	}
	/**
	 * Instantiates an exception to be thrown later.
	 */
	public function makeException()
	{
		$this->thrownException = FaceResponseException::create($this);
	}

	/**
	 * @return int
	 */
	public function getHttpStatusCode() {
		return $this->httpStatusCode;
	}

	/**
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * @return \stdClass
	 */
	public function getDecodedBody() {
		return $this->decodedBody;
	}

	/**
	 * @return FaceRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @return mixed
	 */
	public function getThrownException() {
		return $this->thrownException;
	}
}
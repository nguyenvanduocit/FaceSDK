<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/9/2015
 * Time: 8:56 PM
 */

namespace FaceSDK\Node;


use FaceSDK\Exception\FaceAPIException;
use FaceSDK\FaceResponse;

class NodeFactory {
	/**
	 * @const string The base graph object class.
	 */
	const BASE_NODE_CLASS = '\FaceSDK\Node\Node';
	/**
	 * @const string The base graph edge class.
	 */
	const BASE_EDGE_CLASS = '\FaceSDK\Node\Edge';
	/**
	 * @const string The graph object prefix.
	 */
	const BASE_OBJECT_PREFIX = '\FaceSDK\Node\\';

	/**
	 * @var FaceResponse The response entity from Graph.
	 */
	protected $response;
	/**
	 * @var array The decoded body of the FaceResponse entity from Graph.
	 */
	protected $decodedBody;
	/**
	 * Init this Graph object.
	 *
	 * @param FaceResponse $response The response entity from Graph.
	 */
	public function __construct(FaceResponse $response)
	{
		$this->response = $response;
		$this->decodedBody = $response->getDecodedBody();
	}

	/**
	 * Tries to convert FaceResponse entity to Node
	 * @param null $subclassName
	 *
	 * @return null
	 * @throws FaceAPIException
	 */
	public function makeNode( $subclassName = null) {
		$this->validateResponseAsObject();
		$this->validateResponseCastableAsNode();
		return $this->castAsNodeOrEdge($this->decodedBody, $subclassName);
	}
	/**
	 * Convenience method for creating a GraphAlbum collection.
	 *
	 * @return \FaceSDK\Node\RecognizedFace
	 *
	 * @throws FaceAPIException
	 */
	public function makeRecognizedImage()
	{
		return $this->makeNode(static::BASE_OBJECT_PREFIX.'RecognizedImage');
	}

	/**
	 * @return Edge
	 */
	public function makeGroupPersonList(){
		return $this->makeEdge('Person');
	}

	/**
	 * @return Edge
	 */
	public function makeDetectedLandmark()
	{
		return $this->makeNode(static::BASE_OBJECT_PREFIX.'DetectedLandmark');
	}
	/**
	 * @throws FaceAPIException
	 */
	public function validateResponseAsObject(){
		if (!is_object($this->decodedBody)) {
			throw new FaceAPIException('Unable to get response from Server as object.', 620);
		}

	}

	/**
	 * Validate if object can castable as node of not
	 * @throws FaceAPIException
	 */
	private function validateResponseCastableAsNode() {
		/**
		 * Reponse can cast to node only if it contain more than 1 properties or 1 property and not is array
		 */
		if ($this->isCastableAsEdge($this->decodedBody)) {
			throw new FaceAPIException(
				'Unable to convert response from Graph to a Node because the response looks like a Edge. Try using NodeFactory::makeEdge() instead.',
				620
			);
		}
	}

	/**
	 * Determines whether or not the data should be cast as a Edge.
	 * @param $data
	 *
	 * @return bool
	 */
	private function isCastableAsEdge( &$data ) {
		if(is_array($data)){
			// Array can cast to edge
			return true;
		}else{
			/**
			 * If object have only one properties and this properties is array
			 */
			$dataArray = (array)$data;
			if( (count($dataArray) == 1 )){
				foreach($dataArray as $key=>$value){
					if(is_array($value)){
						$data = $data->$key;
						return true;
					}
				}
				return false;
			}else{
				foreach($dataArray as $key=>$value){
					if(!is_array($value)){
						return false;
					}
				}
				return true;
			}
		}
	}

	/**
	 * @param array $data
	 * @param null  $subclassName
	 *
	 * @return Node
	 * @throws FaceAPIException
	 */
	private function castAsNodeOrEdge($data, $subclassName = null) {
		if($this->isCastableAsEdge($data)){
			return $this->safelyMakeEdge($data, $subclassName);
		}
		return $this->safelyMakeNode($data, $subclassName);
	}

	/**
	 * @param array $data
	 * @param null  $subclassName
	 *
	 * @return mixed
	 * @throws FaceAPIException
	 */
	public function safelyMakeEdge(array $data, $subclassName = null){
		$dataList = [];
		foreach ($data as $node) {
			$dataList[] = $this->safelyMakeNode($node, $subclassName);
		}
		// We'll need to make an edge endpoint for this in case it's a Edge (for cursor pagination)
		$className = static::BASE_EDGE_CLASS;

		return new $className($this->response->getRequest(), $dataList, $subclassName);
	}
	/**
	 * Tries to convert a FacebookResponse entity into a Edge.
	 *
	 * @param string|null $subclassName The Node sub class to cast the list items to.
	 * @param boolean     $auto_prefix  Toggle to auto-prefix the subclass name.
	 *
	 * @return Edge
	 *
	 * @throws FaceAPIException
	 */
	public function makeEdge($subclassName = null, $auto_prefix = true)
	{
		$this->validateResponseAsObject();
		$this->validateResponseCastableAsEdge();

		if ($subclassName && $auto_prefix) {
			$subclassName = static::BASE_OBJECT_PREFIX . $subclassName;
		}

		return $this->castAsNodeOrEdge($this->decodedBody, $subclassName);
	}
	/**
	 * Safely instantiates a Node of $subclassName.
	 *
	 * @param array       $data         The array of data to iterate over.
	 * @param string|null $subclassName The subclass to cast this collection to.
	 *
	 * @return Node
	 *
	 * @throws FaceAPIException
	 */
	public function safelyMakeNode($data, $subclassName = null){
		$subclassName = $subclassName ?: static::BASE_NODE_CLASS;
		static::validateSubclass($subclassName);
		$items = [];
		foreach ($data as $k => $v) {
			// Array means could be recurable
			if (is_array($v) || is_object($v)) {
				// Detect any smart-casting from the $objectMap array.
				// This is always empty on the Node collection, but subclasses can define
				// their own array of smart-casting types.
				/** @var \FaceSDK\Node\Node $subclassName */
				$objectMap = $subclassName::getObjectMap();
				if( isset($objectMap[$k])){
					$objectSubClass = $objectMap[$k];
				}
				else{
					// May be string or null
					$objectSubClass =$subclassName::getDefaultObjectType();
				}
				// Could be a Edge or Node
				$items[$k] = $this->castAsNodeOrEdge($v, $objectSubClass);
			} else {
				$items[$k] = $v;
			}
		}

		return new $subclassName($items);
	}

	/**
	 * Ensures that the subclass in question is valid.
	 *
	 * @param string $subclassName The Node subclass to validate.
	 *
	 * @throws FaceAPIException
	 */
	public static function validateSubclass($subclassName)
	{
		if ($subclassName == static::BASE_NODE_CLASS || is_subclass_of($subclassName, static::BASE_NODE_CLASS)) {
			return;
		}

		throw new FaceAPIException('The given subclass "' . $subclassName . '" is not valid. Cannot cast to an object that is not a Node subclass.', 620);
	}

	private function validateResponseCastableAsEdge() {
		if (!static::isCastableAsEdge($this->decodedBody)) {
			throw new FaceAPIException(
				'Unable to convert response from Graph to a Edge because the response does not look like a Edge. Try using NodeFactory::makeNode() instead.',
				620
			);
		}
	}
}
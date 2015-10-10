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
	const BASE_GRAPH_NODE_CLASS = '\FaceSDK\Node\Node';
	/**
	 * @const string The base graph edge class.
	 */
	const BASE_GRAPH_EDGE_CLASS = '\FaceSDK\Node\Edge';
	/**
	 * @const string The graph object prefix.
	 */
	const BASE_GRAPH_OBJECT_PREFIX = '\FaceSDK\Node\\';

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
	public function makeGraphNode( $subclassName = null) {
		$this->validateResponseAsArray();
		$this->validateResponseCastableAsGraphNode();
		return $this->castAsGraphNodeOrGraphEdge($this->decodedBody, $subclassName);
	}
	/**
	 * Convenience method for creating a GraphAlbum collection.
	 *
	 * @return \FaceSDK\Node\RecognizedFace
	 *
	 * @throws FaceAPIException
	 */
	public function makeGraphDetectedImage()
	{
		return $this->makeGraphNode(static::BASE_GRAPH_OBJECT_PREFIX.'RecognizedImage');
	}

	public function makeGraphGroupPersonList(){
		return $this->makeGraphEdge('Person');
	}
	/**
	 * @throws FaceAPIException
	 */
	public function validateResponseAsArray(){
		if (!is_object($this->decodedBody)) {
			throw new FaceAPIException('Unable to get response from Server as object.', 620);
		}

	}

	private function validateResponseCastableAsGraphNode() {
		/**
		 * Reponse can cast to node only if it contain more than 1 properties or 1 property and not is array
		 */
		if ($this->isCastableAsGraphEdge($this->decodedBody)) {
			throw new FaceAPIException(
				'Unable to convert response from Graph to a GraphNode because the response looks like a GraphEdge. Try using NodeFactory::makeGraphEdge() instead.',
				620
			);
		}
	}

	/**
	 * Determines whether or not the data should be cast as a GraphEdge.
	 * @param $data
	 *
	 * @return bool
	 */
	private function isCastableAsGraphEdge( &$data ) {
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
	private function castAsGraphNodeOrGraphEdge($data, $subclassName = null) {
		if($this->isCastableAsGraphEdge($data)){
			return $this->safelyMakeGraphEdge($data, $subclassName);
		}
		return $this->safelyMakeGraphNode($data, $subclassName);
	}

	/**
	 * @param array $data
	 * @param null  $subclassName
	 *
	 * @return mixed
	 * @throws FaceAPIException
	 */
	public function safelyMakeGraphEdge(array $data, $subclassName = null){
		$dataList = [];
		foreach ($data as $graphNode) {
			$dataList[] = $this->safelyMakeGraphNode($graphNode, $subclassName);
		}
		// We'll need to make an edge endpoint for this in case it's a GraphEdge (for cursor pagination)
		$className = static::BASE_GRAPH_EDGE_CLASS;

		return new $className($this->response->getRequest(), $dataList, $subclassName);
	}
	/**
	 * Tries to convert a FacebookResponse entity into a GraphEdge.
	 *
	 * @param string|null $subclassName The GraphNode sub class to cast the list items to.
	 * @param boolean     $auto_prefix  Toggle to auto-prefix the subclass name.
	 *
	 * @return Edge
	 *
	 * @throws FaceAPIException
	 */
	public function makeGraphEdge($subclassName = null, $auto_prefix = true)
	{
		$this->validateResponseAsArray();
		$this->validateResponseCastableAsGraphEdge();

		if ($subclassName && $auto_prefix) {
			$subclassName = static::BASE_GRAPH_OBJECT_PREFIX . $subclassName;
		}

		return $this->castAsGraphNodeOrGraphEdge($this->decodedBody, $subclassName);
	}
	/**
	 * Safely instantiates a GraphNode of $subclassName.
	 *
	 * @param array       $data         The array of data to iterate over.
	 * @param string|null $subclassName The subclass to cast this collection to.
	 *
	 * @return Node
	 *
	 * @throws FaceAPIException
	 */
	public function safelyMakeGraphNode($data, $subclassName = null){
		$subclassName = $subclassName ?: static::BASE_GRAPH_NODE_CLASS;
		static::validateSubclass($subclassName);
		$items = [];
		foreach ($data as $k => $v) {
			// Array means could be recurable
			if (is_array($v) || is_object($v)) {
				// Detect any smart-casting from the $graphObjectMap array.
				// This is always empty on the GraphNode collection, but subclasses can define
				// their own array of smart-casting types.
				$graphObjectMap = $subclassName::getObjectMap();
				$objectSubClass = isset($graphObjectMap[$k]) ? $graphObjectMap[$k] : null;
				// Could be a GraphEdge or GraphNode
				$items[$k] = $this->castAsGraphNodeOrGraphEdge($v, $objectSubClass);
			} else {
				$items[$k] = $v;
			}
		}

		return new $subclassName($items);
	}

	/**
	 * Ensures that the subclass in question is valid.
	 *
	 * @param string $subclassName The GraphNode subclass to validate.
	 *
	 * @throws FaceAPIException
	 */
	public static function validateSubclass($subclassName)
	{
		if ($subclassName == static::BASE_GRAPH_NODE_CLASS || is_subclass_of($subclassName, static::BASE_GRAPH_NODE_CLASS)) {
			return;
		}

		throw new FaceAPIException('The given subclass "' . $subclassName . '" is not valid. Cannot cast to an object that is not a GraphNode subclass.', 620);
	}

	private function validateResponseCastableAsGraphEdge() {
		if (!static::isCastableAsGraphEdge($this->decodedBody)) {
			throw new FaceAPIException(
				'Unable to convert response from Graph to a GraphEdge because the response does not look like a GraphEdge. Try using GraphNodeFactory::makeGraphNode() instead.',
				620
			);
		}
	}
}
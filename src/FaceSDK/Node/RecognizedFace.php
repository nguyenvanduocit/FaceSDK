<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 12:35 AM
 */

namespace FaceSDK\Node;


class RecognizedFace extends Node{
	/**
	 * @var array Maps object key names to Graph object types.
	 */
	protected static $objectMap = [
		'attribute' => '\FaceSDK\Node\RecognizedFaceAttribute',
		'position'=>'\FaceSDK\Node\Type\FacePosition'
	];

	/**
	 * @return \FaceSDK\Node\RecognizedFaceAttribute
	 */
	public function getAttributes(){
		return $this->getField('attribute');
	}

	/**
	 * @return \FaceSDK\Node\Type\FacePosition
	 */
	public function getPosition(){
		return $this->getField('position');
	}

}
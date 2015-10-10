<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 11:18 AM
 */

namespace FaceSDK\Node\Type;


use FaceSDK\Node\Node;

class FacePosition extends Node {
	/**
	 * @var array Maps object key names to Graph object types.
	 */
	protected static $objectMap = [
		'center'      => '\FaceSDK\Node\Type\Point',
		'eye_left'    => '\FaceSDK\Node\Type\Point',
		'eye_right'   => '\FaceSDK\Node\Type\Point',
		'mouth_left'  => '\FaceSDK\Node\Type\Point',
		'mouth_right' => '\FaceSDK\Node\Type\Point',
		'nose'        => '\FaceSDK\Node\Type\Point',
	];

	/**
	 * @return \FaceSDK\Node\Type\Point
	 */
	public function getCenter() {
		return $this->getField( 'center' );
	}
}
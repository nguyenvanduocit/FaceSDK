<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 2:34 AM
 */

namespace FaceSDK\Node;


class RecognizedFaceAttribute extends Node {
	/**
	 * @var array Maps object key names to Graph object types.
	 */
	protected static $objectMap = [
		'pose'    => '\FaceSDK\Node\Type\Pose',
		'age'     => '\FaceSDK\Node\Type\Range',
		'gender'  => '\FaceSDK\Node\Type\Confidence',
		'glass'   => '\FaceSDK\Node\Type\Confidence',
		'race'    => '\FaceSDK\Node\Type\Confidence',
		'smiling' => '\FaceSDK\Node\Type\SingleValue'
	];

	/**
	 * @return \FaceSDK\Node\Type\Range
	 */
	public function getAge() {
		return $this->getField( 'age' );
	}

	/**
	 * @return \FaceSDK\Node\Type\Pose
	 */
	public function getPose() {
		return $this->getField( 'pose' );
	}
	/**
	 * @return \FaceSDK\Node\Type\Confidence
	 */
	public function getGender(){
		return $this->getField( 'gender' );
	}

	/**
	 * @return \FaceSDK\Node\Type\Confidence
	 */
	public function getGlass() {
		return $this->getField( 'glass' );
	}

	/**
	 * @return \FaceSDK\Node\Type\Confidence
	 */
	public function getRace() {
		return $this->getField( 'race' );
	}

	/**
	 * @return \FaceSDK\Node\Type\Confidence
	 */
	public function getSmiling() {
		return $this->getField( 'smiling' );
	}
}
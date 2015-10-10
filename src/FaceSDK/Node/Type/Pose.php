<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 10:44 AM
 */

namespace FaceSDK\Node\Type;


use FaceSDK\Node\Node;

class Pose extends Node {
	/**
	 * @var array Maps object key names to Graph object types.
	 */
	protected static $objectMap = [
		'pitch_angle' => '\FaceSDK\Node\Type\SingleValue',
		'roll_angle'  => '\FaceSDK\Node\Type\SingleValue',
		'yaw_angle'   => '\FaceSDK\Node\Type\SingleValue'
	];

	/**
	 * @return \FaceSDK\Node\Type\SingleValue
	 */
	public function getPitchAngle() {
		return $this->getField( 'pitch_angle' );
	}

	/**
	 * @return \FaceSDK\Node\Type\SingleValue
	 */
	public function getRollAngle() {
		return $this->getField( 'roll_angle' );
	}

	/**
	 * @return \FaceSDK\Node\Type\SingleValue
	 */
	public function getYawAngle() {
		return $this->getField( 'yaw_angle' );
	}
}
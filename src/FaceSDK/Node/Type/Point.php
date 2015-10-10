<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 11:17 AM
 */

namespace FaceSDK\Node\Type;


use FaceSDK\Node\Node;

class Point extends Node {
	/**
	 * @return float
	 */
	public function getX() {
		return $this->getField( 'x' );
	}

	/**
	 * @return float
	 */
	public function getY() {
		return $this->getField( 'y' );
	}
}
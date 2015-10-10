<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 11:02 AM
 */

namespace FaceSDK\Node\Type;
class Confidence extends SingleValue {
	/**
	 * @return float
	 */
	public function getConfidence() {
		return $this->getField( 'confidence' );
	}
}
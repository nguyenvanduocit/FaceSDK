<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 12:34 AM
 */

namespace FaceSDK\Node;


class RecognizedImage extends Node{
	/**
	 * @var array Maps object key names to Graph object types.
	 */
	protected static $graphObjectMap = [
		'face' => '\FaceSDK\Node\RecognizedFace',
	];

	/**
	 * @return \FaceSDK\Node\RecognizedFace[]
	 */
	public function getFaces(){
		return $this->getField('face');
	}

	/**
	 * @return int
	 */
	public function getHeight(){
		return $this->getField('img_height');
	}

	/**
	 * @return int
	 */
	public function getWidth(){
		return $this->getField('img_width');
	}

	/**
	 * @return string
	 */
	public function getUrl(){
		return $this->getField('url');
	}

	/**
	 * @return int
	 */
	public function getId(){
		return $this->getField('img_id');
	}
}
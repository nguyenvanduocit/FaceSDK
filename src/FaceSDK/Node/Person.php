<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 9:22 AM
 */

namespace FaceSDK\Node;


class Person extends Node{
	/**
	 * get person's name
	 * @return string
	 */
	public function getName(){
		return $this->getField('person_name');
	}

	/**
	 * get person's ID
	 * @return string
	 */
	public function getID(){
		return $this->getField('person_id');
	}

	/**
	 * get person's tag
	 * @return string
	 */
	public function getTag(){
		return $this->getField('tag');
	}
}
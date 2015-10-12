<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/12/2015
 * Time: 9:54 AM
 */

namespace FaceSDK\Node;


class Group extends Node
{
    /**
     * @var array Maps object key names to Graph object types.
     */
    protected static $objectMap = [
        'person' => '\FaceSDK\Node\Person'
    ];

    /**
     * @return \FaceSDK\Node\Person[]
     */
    public function getPersons(){
        return $this->getField('person');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/12/2015
 * Time: 9:38 AM
 */

namespace FaceSDK\Node\Type;


use FaceSDK\Node\Node;

class LandMark extends Node
{
    /**
     * @var array Maps object key names to Graph object types.
     */
    protected static $objectMap = [
        'landmark'    => '\FaceSDK\Node\Type\LandmarkLocation',
    ];

    /**
     * @return \FaceSDK\Node\Type\LandmarkLocation
     */
    public function getLandMarkLocation(){
        return $this->getField('landmark');
    }
    public function getFaceId(){
        return $this->getField('face_id');
    }
}
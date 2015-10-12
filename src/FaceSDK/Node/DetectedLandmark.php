<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/12/2015
 * Time: 9:28 AM
 */

namespace FaceSDK\Node;


class DetectedLandmark extends Node
{
    /**
     * @var array Maps object key names to Graph object types.
     */
    protected static $objectMap = [
        'result'    => '\FaceSDK\Node\Type\LandMark',
    ];

    /**
     * @return \FaceSDK\Node\Type\Landmark
     */
    public function getLandMarks(){
        return $this->getField('result');
    }
}
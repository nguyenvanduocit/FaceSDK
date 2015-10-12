<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/12/2015
 * Time: 9:14 AM
 */

namespace FaceSDK\Node\Type;


use FaceSDK\Node\Node;

class LandmarkLocation extends Node
{
    /**
     * @var array Maps object key names to Graph object types.
     */
    protected static $defaultObject = '\FaceSDK\Node\Type\Point';

}
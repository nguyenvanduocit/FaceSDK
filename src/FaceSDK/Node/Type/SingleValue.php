<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 11:10 AM
 */

namespace FaceSDK\Node\Type;


use FaceSDK\Node\Node;

class SingleValue extends Node
{
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->getField('value');
    }
}
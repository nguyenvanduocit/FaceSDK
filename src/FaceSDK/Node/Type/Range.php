<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 11:02 AM
 */

namespace FaceSDK\Node\Type;


use FaceSDK\Node\Node;

class Range extends SingleValue
{
    /**
     * @return float
     */
    public function getRange()
    {
        return $this->getField('range');
    }
}
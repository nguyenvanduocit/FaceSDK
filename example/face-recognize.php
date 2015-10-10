<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 11:32 AM
 */

use FaceSDK\Node\RecognizedImage;

require_once '../vendor/autoload.php';

$faceAPI = new \FaceSDK\FaceSDK( 'YOUR_API', 'YOUR_API', 'http://apicn.faceplusplus.com' );
$response  = $faceAPI->post( '/detection/detect', [
	'url' => 'http://2anhdep.vn/wp-content/uploads/2014/11/anh-ngoc-trinh-dep-trong-nhung-bo-noi-y-xuyen-thau-khien-nguoi-xem-do-mat-6.jpg',
	'attribute'=>'glass,gender,age,race,smiling,glass,pose'
] );

/** @var RecognizedImage $detectedImage */
$detectedImage = $response->getRecognizedImage();
var_dump('$detectedImage', $detectedImage);

/** @var \FaceSDK\Node\RecognizedFace[] $faces */
$faces = $detectedImage->getFaces();
var_dump('$faces', $faces);

/** @var \FaceSDK\Node\RecognizedFaceAttribute $attrs */
$attrs =  $faces[0]->getAttributes();
var_dump('$attrs', $attrs);

/** @var \FaceSDK\Node\Type\Pose $pose */
$pose = $attrs->getPose();
var_dump('$pose', $pose);

/** @var \FaceSDK\Node\Type\Range $age */
$age = $attrs->getAge();
var_dump('$age', $age);

/** @var \FaceSDK\Node\Type\FacePosition $position */
$position = $faces[0]->getPosition();
var_dump('$position', $position);

/** @var \FaceSDK\Node\Type\Point $center */
$center = $position->getCenter();
var_dump('$center', $center);
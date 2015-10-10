<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 5:07 PM
 */
use FaceSDK\Node\RecognizedImage;

require_once '../vendor/autoload.php';

$faceAPI = new \FaceSDK\FaceSDK( 'API', 'SECRET', 'http://apicn.faceplusplus.com' );
$filePath = __DIR__.'/Ngoc_Trinh_22.png';
$response = $faceAPI->post( '/detection/detect', [
	'img'       => $faceAPI->fileToUpload($filePath),
	'attribute' => 'glass,gender,age,race,smiling,glass,pose'
] );

/** @var \FaceSDK\Node\RecognizedImage $image */
$image = $response->getRecognizedImage();

$height = $image->getHeight();
$width = $image->getWidth();

/** @var \FaceSDK\Node\RecognizedFace[] $faces */
$faces = $image->getFaces();

/**
 * For simple example, I choose image with only one face
 */
if(count($faces) > 0){
	$mainPosition = $faces[0]->getPosition();
	$top = $mainPosition->getEyeLeft()->getY()*$height/100;
	$left = $mainPosition->getEyeLeft()->getX()*$width/100;
	$bottom = $mainPosition->getMoutLeft()->getY()*$height/100;
	$right = $mainPosition->getEyeRight()->getX()*$width/100;
	$age = $faces[0]->getAttributes()->getAge()->getValue();
	$padding = 50;
	$font = 'arial.ttf';
	// Load image
	$im = imagecreatefrompng($filePath );
	// Make color
	$green = imagecolorallocate($im, 230, 33, 23);
	// Write text
	imagettftext($im, 20, 0, ($left + $right)/2-10, $bottom+$padding+20, $green, $font, $age);
	// Draw rectangle
	imagerectangle($im, ($left-$padding), ($top-$padding), ($right+$padding), ($bottom+$padding), $green);
	//Write
	// Output and free from memory
	header('Content-Type: image/jpeg');
	imagejpeg($im);
	//Free from memory
	imagedestroy($im);
}
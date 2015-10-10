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
$filePath = 'img/Son_Tung_1.jpg';
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
	$padding = 90;
	$top = $mainPosition->getEyeLeft()->getY() * $height / 100 - $padding;
	$left = $mainPosition->getEyeLeft()->getX() * $width / 100 - $padding;
	$bottom = $mainPosition->getMoutLeft()->getY() * $height / 100 + $padding;
	$right = $mainPosition->getEyeRight()->getX() * $width / 100 + $padding;

	$attrs = $faces[0]->getAttributes();
	$age = $attrs->getAge()->getValue();
	$gender = $attrs->getGender()->getValue();
	$isSmiling = $attrs->getSmiling()->getValue()>30;
	$font = 'arial.ttf';
	// Load image
	$im = imagecreatefromjpeg($filePath );
	// Make color
	$green = imagecolorallocate($im, 230, 33, 23);
	// Write addition information
	$additionalText = '';
	if($gender =='Female'){
		$additionalText .= 'She';
	}
	else{
		$additionalText .= 'He';
	}
	if($isSmiling){
		$additionalText .= ' is smiling';
	}
	else{
		$additionalText .= ' is not smiling';
	}
	// Write text
	$bbox = imagettfbbox(20, 0, $font, $age.' year old');
	list($lower_left_x,$lower_left_Y, $lower_right_x, $lower_right_y, $upper_right_x, $upper_left_x, $upper_left_y ) =$bbox;
	imagettftext($im, 20, 0, ($left + $right)/2 - ($upper_right_x-$upper_left_x)/2, $bottom+abs( $upper_left_y - $lower_left_Y )+20, $green, $font, $age.' year old');

	$bbox = imagettfbbox(20, 0, $font, $additionalText);
	list($lower_left_x,$lower_left_Y, $lower_right_x, $lower_right_y, $upper_right_x, $upper_left_x, $upper_left_y ) =$bbox;
	imagettftext($im, 20, 0, ($left + $right)/2 - ($upper_right_x-$upper_left_x)/2, $bottom+abs( $upper_left_y - $lower_left_Y )+50, $green, $font, $additionalText);
	// Draw rectangle
	imagesetthickness ( $im , 2 );
	imagerectangle($im, $left, $top, $right, $bottom, $green);
	//Write
	// Output and free from memory
	header('Content-Type: image/jpeg');
	imagejpeg($im);
	//Free from memory
	imagedestroy($im);
}
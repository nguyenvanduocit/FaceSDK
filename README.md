# FaceSDK

This SDK wrap some method for faceplusplus.com's API. Provides modern ways to use the API in PHP.

# Install

It's very easy to install via composer 

```
composer require nguyenvanduocit/face-sdk
```

# Register API
This API is free to use, you can register your application at [FacePlusPlus](http://www.faceplusplus.com/) to get apiKey and apiSecretKey.

# Usage

```php
use FaceSDK\Node\RecognizedImage;

require_once '../vendor/autoload.php';

$faceAPI = new \FaceSDK\FaceSDK( 'YOUR_API', 'YOUR_API', 'http://apicn.faceplusplus.com' );
$response  = $faceAPI->post( '/detection/detect', [
	'url' => 'http://2anhdep.vn/wp-content/uploads/2014/11/anh-ngoc-trinh-dep-trong-nhung-bo-noi-y-xuyen-thau-khien-nguoi-xem-do-mat-6.jpg',
	'attribute'=>'glass,gender,age,race,smiling,glass,pose'
] );

/** @var RecognizedImage $detectedImage */
$detectedImage = $response->getGraphDetectedImage();
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
```

# TODO

- Implement all API from [FacePlusPlus Doc](http://www.faceplusplus.com/api-overview/)
- Improve Exception

# Contribute

Feel free to make PR

# Development
This SDK was shipped with many implement and lesson from Facebook PHP SDK.
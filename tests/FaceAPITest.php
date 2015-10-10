<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/10/2015
 * Time: 7:06 AM
 */

namespace FaceSDK\Tests;


use FaceSDK\FaceRequest;
use FaceSDK\FaceResponse;
use FaceSDK\Node\Type\Range;

class FaceAPITest extends \PHPUnit_Framework_TestCase {

	public function testGetDetectedImage() {
		$request       = new FaceRequest( 'key', 'secret', 'http://apicn.faceplusplus.com', 'POST', '/foo', [ ] );
		$body          = '{ "face": [ { "attribute": { "age": { "range": 5, "value": 2 }, "gender": { "confidence": 94.9647, "value": "Female" }, "glass": { "confidence": 99.9888, "value": "None" }, "pose": { "pitch_angle": { "value": -2.1e-05 }, "roll_angle": { "value": -11.0595 }, "yaw_angle": { "value": 22.414751 } }, "race": { "confidence": 95.16890000000001, "value": "White" }, "smiling": { "value": 5.92348 } }, "face_id": "88e50e26ea110a484644a6f28a94058a", "position": { "center": { "x": 55.875, "y": 22.416667 }, "eye_left": { "x": 51.13, "y": 20.264833 }, "eye_right": { "x": 59.58575, "y": 19.163 }, "height": 12.833333, "mouth_left": { "x": 53.2615, "y": 26.63 }, "mouth_right": { "x": 59.398, "y": 25.652167 }, "nose": { "x": 57.23, "y": 23.2635 }, "width": 19.25 }, "tag": "" } ], "img_height": 825, "img_id": "bdc0c414dcc5b77349c412b5de353d87", "img_width": 550, "session_id": "32512aa60de5490795cddeff96a635ce", "url": "http://bestsoccertips.com/wp-content/uploads/2014/11/Ngoc-Trinh-2.jpg" }';
		$response      = new FaceResponse( $request, $body, 200, [ ] );
		/** @var \FaceSDK\Node\RecognizedImage $detectedImage */
		$detectedImage = $response->getRecognizedImage();
		$this->assertInstanceOf( 'FaceSDK\Node\RecognizedImage', $detectedImage );

		/** @var \FaceSDK\Node\RecognizedFace[] $faces */
		$faces = $detectedImage->getFaces();
		$this->assertInstanceOf( 'FaceSDK\Node\Edge', $faces );
		$this->assertInstanceOf( 'FaceSDK\Node\RecognizedFace', $faces[0] );

		/** @var \FaceSDK\Node\RecognizedFaceAttribute $attrs */
		$attrs =  $faces[0]->getAttributes();
		$this->assertInstanceOf( '\FaceSDK\Node\RecognizedFaceAttribute', $attrs );

		/** @var \FaceSDK\Node\Type\Pose $pose */
		$pose = $attrs->getPose();
		$this->assertInstanceOf( '\FaceSDK\Node\Type\Pose',  $pose);

		/** @var \FaceSDK\Node\Type\Range $age */
		$age = $attrs->getAge();
		$this->assertInstanceOf( '\FaceSDK\Node\Type\Range', $age );

		/** @var \FaceSDK\Node\Type\FacePosition $position */
		$position = $faces[0]->getPosition();
		$this->assertInstanceOf( '\FaceSDK\Node\Type\FacePosition',  $position);

		$center = $position->getCenter();
		$this->assertInstanceOf( '\FaceSDK\Node\Type\Point',  $center);
		$this->assertEquals( 55.875,  $center->getX());

	}

	public function testGetGroupInfo() {
		$request     = new FaceRequest( 'key', 'secret', 'http://apicn.faceplusplus.com', 'POST', '/foo', [ ] );
		$body        = '{ "person": [ { "person_id": "c90cc7f5b7b1f09d0c4f5eef11f9f2d8", "tag": null, "person_name": "Alice" }, { "person_id": "f8ec506d7024c09dc3453ae7cb36f487", "tag": null, "person_name": "Bob" }, { "person_id": "a65a95d465bec754cecbdbd5733c617a", "tag": null, "person_name": "Carl" }, { "person_id": "25076b486aec1bfb183c653e452b987e", "tag": null, "person_name": "Daniel" }, { "person_id": "d55a335824ff5481583f689c771419da", "tag": null, "person_name": "Ethan" }, { "person_id": "d770ab718caeaa76ca06ca3f169829c1", "tag": null, "person_name": "Mike" }, { "person_id": "bd1e44d90f0949a0e8a2383548deadec", "tag": null, "person_name": "Steve" } ] }';
		$response    = new FaceResponse( $request, $body, 200, [ ] );
		$persionList = $response->getGroupPersonList();
		$this->assertInstanceOf( 'FaceSDK\Node\Edge', $persionList );
		$this->assertInstanceOf( '\FaceSDK\Node\Person', $persionList[0] );
		$this->assertEquals( 'Alice', $persionList[0]->getName() );
	}
}

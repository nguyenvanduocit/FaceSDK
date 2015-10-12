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

	public function testLandmark(){
		$request     = new FaceRequest( 'key', 'secret', 'http://apicn.faceplusplus.com', 'POST', '/foo', [ ] );
		$body        = '{ "result": [ { "face_id": "a4bae295cc205bbc5b65e3522164c6d9", "landmark": { "contour_chin": { "x": 46.414878, "y": 57.787805 }, "contour_left1": { "x": 29.173415, "y": 34.049268 }, "contour_left2": { "x": 29.64878, "y": 37.368293 }, "contour_left3": { "x": 30.460244, "y": 40.832195 }, "contour_left4": { "x": 31.672683, "y": 44.194146 }, "contour_left5": { "x": 32.764878, "y": 47.36122 }, "contour_left6": { "x": 34.497073, "y": 50.051951 }, "contour_left7": { "x": 36.669024, "y": 52.966341 }, "contour_left8": { "x": 39.232195, "y": 55.297805 }, "contour_left9": { "x": 42.444634, "y": 57.212927 }, "contour_right1": { "x": 62.625366, "y": 29.352195 }, "contour_right2": { "x": 63.308049, "y": 33.364146 }, "contour_right3": { "x": 63.534878, "y": 37.223659 }, "contour_right4": { "x": 63.783171, "y": 41.228537 }, "contour_right5": { "x": 63.134146, "y": 45.31561 }, "contour_right6": { "x": 61.216585, "y": 48.963171 }, "contour_right7": { "x": 58.397317, "y": 51.92878 }, "contour_right8": { "x": 55.259024, "y": 54.796341 }, "contour_right9": { "x": 51.476098, "y": 56.856341 }, "left_eye_bottom": { "x": 35.253171, "y": 34.098293 }, "left_eye_center": { "x": 35.399512, "y": 33.256585 }, "left_eye_left_corner": { "x": 32.066098, "y": 33.522927 }, "left_eye_lower_left_quarter": { "x": 33.68878, "y": 33.95878 }, "left_eye_lower_right_quarter": { "x": 37.243171, "y": 33.611707 }, "left_eye_pupil": { "x": 36.033902, "y": 32.567317 }, "left_eye_right_corner": { "x": 39.17878, "y": 33.430732 }, "left_eye_top": { "x": 35.139024, "y": 31.793171 }, "left_eye_upper_left_quarter": { "x": 33.525122, "y": 32.384146 }, "left_eye_upper_right_quarter": { "x": 36.961707, "y": 32.17122 }, "left_eyebrow_left_corner": { "x": 29.242439, "y": 30.029268 }, "left_eyebrow_lower_left_quarter": { "x": 31.008537, "y": 29.34561 }, "left_eyebrow_lower_middle": { "x": 33.326341, "y": 29.742439 }, "left_eyebrow_lower_right_quarter": { "x": 35.683659, "y": 30.21439 }, "left_eyebrow_right_corner": { "x": 38.099756, "y": 30.421463 }, "left_eyebrow_upper_left_quarter": { "x": 30.958049, "y": 28.006585 }, "left_eyebrow_upper_middle": { "x": 33.423902, "y": 28.449024 }, "left_eyebrow_upper_right_quarter": { "x": 35.770244, "y": 29.02439 }, "mouth_left_corner": { "x": 39.00878, "y": 48.307073 }, "mouth_lower_lip_bottom": { "x": 44.716341, "y": 51.013415 }, "mouth_lower_lip_left_contour1": { "x": 41.912683, "y": 48.465366 }, "mouth_lower_lip_left_contour2": { "x": 40.593171, "y": 49.719756 }, "mouth_lower_lip_left_contour3": { "x": 42.557561, "y": 50.880488 }, "mouth_lower_lip_right_contour1": { "x": 47.794146, "y": 47.52561 }, "mouth_lower_lip_right_contour2": { "x": 49.760732, "y": 48.358293 }, "mouth_lower_lip_right_contour3": { "x": 47.286098, "y": 49.999024 }, "mouth_lower_lip_top": { "x": 44.623902, "y": 48.608049 }, "mouth_right_corner": { "x": 51.676829, "y": 46.061951 }, "mouth_upper_lip_bottom": { "x": 44.450732, "y": 47.92122 }, "mouth_upper_lip_left_contour1": { "x": 42.67439, "y": 46.402683 }, "mouth_upper_lip_left_contour2": { "x": 40.511463, "y": 47.345366 }, "mouth_upper_lip_left_contour3": { "x": 42.039512, "y": 48.049268 }, "mouth_upper_lip_right_contour1": { "x": 45.329512, "y": 46.045854 }, "mouth_upper_lip_right_contour2": { "x": 48.429024, "y": 46.085854 }, "mouth_upper_lip_right_contour3": { "x": 47.760976, "y": 47.101707 }, "mouth_upper_lip_top": { "x": 44.161707, "y": 46.606098 }, "nose_contour_left1": { "x": 40.480488, "y": 32.890244 }, "nose_contour_left2": { "x": 39.550244, "y": 39.207561 }, "nose_contour_left3": { "x": 41.082439, "y": 43.290976 }, "nose_contour_lower_middle": { "x": 42.997073, "y": 43.409756 }, "nose_contour_right1": { "x": 44.614146, "y": 32.492683 }, "nose_contour_right2": { "x": 46.087317, "y": 38.35 }, "nose_contour_right3": { "x": 45.39878, "y": 42.754634 }, "nose_left": { "x": 39.161951, "y": 42.39878 }, "nose_right": { "x": 47.571951, "y": 41.289756 }, "nose_tip": { "x": 41.650244, "y": 40.774146 }, "right_eye_bottom": { "x": 51.453415, "y": 31.899024 }, "right_eye_center": { "x": 50.970244, "y": 31.204634 }, "right_eye_left_corner": { "x": 47.382439, "y": 32.167317 }, "right_eye_lower_left_quarter": { "x": 49.356341, "y": 31.962927 }, "right_eye_lower_right_quarter": { "x": 53.292439, "y": 31.13561 }, "right_eye_pupil": { "x": 51.460732, "y": 30.57561 }, "right_eye_right_corner": { "x": 55.089756, "y": 30.516829 }, "right_eye_top": { "x": 50.842927, "y": 29.673415 }, "right_eye_upper_left_quarter": { "x": 49.186585, "y": 30.526341 }, "right_eye_upper_right_quarter": { "x": 52.834146, "y": 29.780732 }, "right_eyebrow_left_corner": { "x": 45.671707, "y": 29.460732 }, "right_eyebrow_lower_left_quarter": { "x": 48.697805, "y": 28.473171 }, "right_eyebrow_lower_middle": { "x": 51.362439, "y": 27.44439 }, "right_eyebrow_lower_right_quarter": { "x": 54.367317, "y": 26.657805 }, "right_eyebrow_right_corner": { "x": 57.113902, "y": 26.924878 }, "right_eyebrow_upper_left_quarter": { "x": 48.366585, "y": 27.347073 }, "right_eyebrow_upper_middle": { "x": 50.99439, "y": 26.087805 }, "right_eyebrow_upper_right_quarter": { "x": 54.373659, "y": 25.471951 } } } ], "session_id": "12596ee954d843829240f58486c7e3a2" }';
		$response    = new FaceResponse( $request, $body, 200, [ ] );

		/** @var \FaceSDK\Node\DetectedLandmark $detectedLandmark */
		$detectedLandmark = $response->getDetectedLandmark();
		$this->assertInstanceOf('\FaceSDK\Node\DetectedLandmark', $detectedLandmark);
		/** @var \FaceSDK\Node\Type\LandMark[] $landmark */
		$landmark = $detectedLandmark->getLandMarks();
		$this->assertInstanceOf('\FaceSDK\Node\Edge', $landmark);
	}

	public function testGroup(){
		$request     = new FaceRequest( 'key', 'secret', 'http://apicn.faceplusplus.com', 'POST', '/foo', [ ] );
		$body        = '{ "person": [ { "person_id": "98b0fe01f6f212e19a3e659e324e37ab", "tag": "", "person_name": "Alice" }, { "person_id": "9d1598f2831eadfc5817008859865cbd", "tag": "", "person_name": "Bob" } ], "group_id": "f539fa9f6e4689397c76c57f2cfb4edc", "tag": "created_by_Alice", "group_name": "Family" }';
		$response    = new FaceResponse( $request, $body, 200, [ ] );
		/** @var \FaceSDK\Node\Group $groupInfo */
		$groupInfo = $response->getGroupInfo();
		$this->assertInstanceOf('\FaceSDK\Node\Group', $groupInfo);
		$personList = $groupInfo->getPersons();
		$this->assertEquals('Alice', $personList[0]->getName());
	}

}

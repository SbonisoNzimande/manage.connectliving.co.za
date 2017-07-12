<?php
/**
 * Form Submissions Controller
 * 
 * @package 
 * @author  
 */
class FormSubmissionsController
{
	static public $app_controller;
	static public $form_id;
	static public $unit_no;
	static public $full_name;
	static public $cellphone;


	public function __construct() {
		self::$app_controller 	= new AppController();
		self::$form_id   		= (isset($_REQUEST['FormID'])) ? self::$app_controller->sanitise_string($_REQUEST['FormID']) : '';
		self::$unit_no   		= (isset($_REQUEST['unitNo'])) ? self::$app_controller->sanitise_string($_REQUEST['unitNo']) : '';
		self::$full_name   		= (isset($_REQUEST['userFullname'])) ? self::$app_controller->sanitise_string($_REQUEST['userFullname']) : '';
		self::$cellphone   		= (isset($_REQUEST['unitNo'])) ? self::$app_controller->sanitise_string($_REQUEST['userCellphone']) : '';
		// self::$cellphone   		= (isset($_REQUEST['form_instruction'])) ? self::$app_controller->sanitise_string($_REQUEST['form_instruction']) : '';
		
	}

	/**
	 * GET Request
	 *
	 * @param
	 * @return
	 */
	public function get($request) {
		$subRequest		= (isset($request->url_elements[1])) ? 
							$request->url_elements[1] : '';

		switch ($subRequest) {
			
			default:

				$findform  = self::$app_controller->get_form_by_id (self::$form_id);

				foreach ($findform as $f) {
					$form_instruction = $f['form_instruction'];
				}

				
				$pass 		= array(
								'full_name'  => self::$full_name, 
								'unit_no' 	 => self::$unit_no,
								'form_id' 	 => self::$form_id,
								'form_instruction' 	 => $form_instruction,
								'cellphone'  => self::$cellphone
							);
				self::$app_controller->get_view   ('FormsSubmissions', $pass);
				// self::$app_controller->get_footer (array('page' => 'forms'));
				
			break;
		}
	}


	/**
	 * Post Request
	 *
	 * @param
	 * @return
	 */
	public function post($request) {
		$subRequest		= (isset($request->url_elements[1])) ? $request->url_elements[1] : '';

		self::$app_controller->set_session_start();
		$email 			= $_SESSION['email'];

		switch ($subRequest) {
			

			case 'SubmitForm':
				$form_data 	= array_map(self::$app_controller->sanitise_string, $request->parameters);
				$image_data = array_map(self::$app_controller->sanitise_string, $_FILES);
				$survey 	= self::submit_survey ($form_data, $image_data);
			return json_encode($survey);

			case 'SaveImage':
				$form_data 	= array_map(self::$app_controller->sanitise_string, $request->parameters);
				$image_data = array_map(self::$app_controller->sanitise_string, $_FILES);
				$survey 	= self::submit_image ($form_data, $image_data);
			return json_encode($survey);
			break;
			case 'SubmitSurvey':
				$form_data 	= array_map(self::$app_controller->sanitise_string, $request->parameters);
				$image_data = array_map(self::$app_controller->sanitise_string, $_FILES);
				$survey 	= self::submit_survey ($form_data, $image_data);
			return json_encode($survey);
			case 'GetImage':
					$id 	= $request->parameters['id'];
					$image 	= self::$app_controller->get_image_by_id ($id);
					$ret 	= array();

					if(!empty($image[0]['image'])) {
						foreach ($image as $k) {

							$img  = (string)$k['image'];

							$image = str_replace('[', '', $img);
							$image = str_replace(']', '', $image);
							$image = str_replace('"', '', $image);
							$ret   = array ('images'=> 'data:image/png;base64,'.$image);
						}
					}else{
						$ret = array ('images'=> self::$app_controller->get_noimage_base64());
						}

					return json_encode($ret);
					
				break;
		}
	}


	static public function submit_image ($form_data, $image_data) {

		if (empty($form_data['FormID'])) {
			return array('status'  => false, 'text' => 'No from selected');
		}


		$form_id 	= $form_data['FormID'];
		$unit_no 	= $form_data['unit_no'];
		$full_name 	= $form_data['full_name'];
		$cellphone 	= $form_data['cellphone'];
		$res_id 	= (isset($form_data['res_id']))?
						$form_data['res_id'] : 0;

		$forms 		= self::$app_controller->get_form_by_id ($form_id);

		if (empty($forms)) {
			return array ('status'  => false, 'text' => 'Invalid form');
		}

		array_walk ( $forms, function (&$key) { $key["questions"] = json_decode($key['questions'], true); } );
		foreach ($forms as $f) {
			$comp_id 	= $f['companyID'];
			$prop_id 	= $f['propertyID'];
			$questions 	= $f['questions'];

			array_walk ( $questions, function (&$key) { $key['q_option'] = explode( ',', $key['q_option']); } );
		}

		$resp_id 	= dechex(time());

	
		$errors 	= '';
		$save_array = array();
		
		$directory 	= '../companies/' . $comp_id .'/properties/' . $prop_id . '/form_submission_files/';

		
		self::$app_controller->created_directory ($directory);

		foreach ($questions as $s) {
			$q_type 	= $s['q_type'];

			if ($q_type == 'file_upload') {
				$q_num 	    = $s['q_num'];
				$q_name	    = 'question' . $q_num;
				$q_name	    = $from_data['survey-form'];

				$filename 	= $image_data['survey-form']["name"];
				$tmp_name 	= $image_data['survey-form']["tmp_name"];
				$ext 		= pathinfo($filename, PATHINFO_EXTENSION);

				$image_name = $q_num   . '_' .
							  $prop_id . '_' .
							  $comp_id . '.' .
							  $ext;

				move_uploaded_file($image_data['survey-form']["tmp_name"], $directory.$filename);

				$save_array[] = array(
					'form_id'	=> $form_id,
					'prop_id'	=> $prop_id,
					'unit_no'	=> $unit_no,
					'full_name'	=> $full_name,
					'cellphone'	=> $cellphone,
					'q_num'		=> $q_num,
					'resp_id'	=> $resp_id,
					'responce'	=> $filename
					);
			}
			
		}

		
		$return_array 	= array('status' => true, 'question_name' => 'question'.$q_num, 'file_name' => $filename );

		return $return_array;
	}






	static public function submit_survey ($formData, $imageData) {
		
		$return_array 	=  array();
		/*** merge form data with image data ***/
		$form_data 		= array_merge($formData, $imageData);

		



		if (empty($form_data['FormID'])) {
			return array('status'  => false, 'text' => 'No from selected');
		}

		$form_id 	= $form_data['FormID'];
		$unit_no 	= $form_data['unit_no'];
		$full_name 	= $form_data['full_name'];
		$cellphone 	= $form_data['cellphone'];
		$res_id 	= (isset($form_data['res_id']))?
						$form_data['res_id'] : 0;

		// Get form info
		$forms 		= self::$app_controller->get_form_by_id ($form_id);

		if (empty($forms)) {
			return array('status'  => false, 'text' => 'Invalid form');
		}


		array_walk ( $forms, function (&$key) { $key["questions"] = json_decode($key['questions'], true); } );
		foreach ($forms as $f) {
			$comp_id 	= $f['companyID'];
			$prop_id 	= $f['propertyID'];
			$questions 	= $f['questions'];

			array_walk ( $questions, function (&$key) { $key['q_option'] = explode( ',', $key['q_option']); } );
		}

		$resp_id 	= dechex(time());
	
		$errors 	= '';
		$save_array = array();
		
		$directory 	= '../companies/' . $comp_id .'/properties/' . $prop_id . '/form_submission_files/';
		
		self::$app_controller->created_directory($directory);		

		foreach ($questions as $s) {
			$q_num 		 = $s['q_num'];

			$q_num2 	 = str_replace('.', '_', $s['q_num']);

			$q_id 		 = $s['id'];
			$q_name		 = 'question' . $q_num2;
			$q_type 	 = $s['q_type'];
			$q_mandatory = $s['q_mandatory'];


			if (	
					(empty($form_data[$q_name]) OR  !isset($form_data[$q_name]))
					AND ($q_mandatory == 'true')
				) {
				$errors   .= 'Please answer question ' .$q_num. '<br />';
			}else{

				if (is_array($form_data[$q_name]) AND !($q_type == 'file_upload')) {// Checkbox or Multiple fields
					// die(var_dump($form_data[$q_name]));
					$responce  = implode(',', $form_data[$q_name]);
				}elseif ($q_type  	== 'signature') {
					/* Check if signature */

					$base_str 	   = str_replace('image/png;base64,', '', $form_data[$q_name]);
					$responce  	   = 'signature_' . uniqid().'.png';// siniture file name
					$basestr   	   = base64_decode($base_str);// get singiture string

					$filename_path = $directory . $responce;// image path

					file_put_contents ($filename_path, $basestr);// Save sigature to path

				}elseif ($q_type == 'file_upload') {

					// $q_num 	    = $s['q_num'];
					// $q_name	    = 'question' . $q_num;
					// $q_name	    = $form_data['survey-form'];

					$responce 		= self::$app_controller->upload_file ($form_data[$q_name], $directory);

					// $filename 	= $form_data[$q_name]["name"];
					// $tmp_name 	= $form_data[$q_name]["tmp_name"];
					// $ext 		= pathinfo($filename, PATHINFO_EXTENSION);

					// $responce 	= 'image_'  .
					// 			  $q_num    . '_' .
					// 			  $prop_id  . '_' .
					// 			  $comp_id  . '.' .
					// 			  uniqid()  .
					// 			  $ext;

					// move_uploaded_file ($file["tmp_name"], getcwd(). '/' .$dir .'/'. $name);
					// move_uploaded_file($form_data[$q_name]["tmp_name"], $directory. '/' . $filename);

					
				}else{
					$responce  = $form_data[$q_name];
				}

				// /* Check if signature */
				// if ($q_type == 'signature') {
				// 	# code...
				// }

				$save_array[] = array(
					'form_id'	=> $form_id,
					'prop_id'	=> $prop_id,
					'unit_no'	=> $unit_no,
					'full_name'	=> $full_name,
					'cellphone'	=> $cellphone,
					'q_num'		=> $q_num,
					'q_name'	=> $q_name,
					'resp_id'	=> $resp_id,
					'responce'	=> $responce
					);
			}
			
		}

		// die(var_dump($save_array));

		if (!empty($errors)) {
			$return_array = array('status' => false, 'text' => $errors);
		}else{

			// die(var_dump($save_array));

			foreach ($save_array as $sa) {
				$form_id 	= $sa['form_id'];
				$prop_id 	= $sa['prop_id'];
				$unit_no 	= $sa['unit_no'];
				$full_name 	= $sa['full_name'];
				$cellphone 	= $sa['cellphone'];
				$q_num 		= $sa['q_num'];
				$resp_id 	= $sa['resp_id'];
				$responce 	= $sa['responce'];

				// Test if already answered
				$responces 	= self::$app_controller->get_responces ($form_id, $prop_id, $unit_no, $resp_id, $q_num);

				$save 		= self::$app_controller->insert_survey_responce (
											$form_id,
											$resp_id,
											$prop_id,
											$unit_no,
											$full_name,
											$cellphone,
											$q_num,
											$responce
										);

				// if (count($responces) === 0) {// not answered yet
				// 	$save 	= self::$app_controller->insert_survey_responce (
				// 								$form_id,
				// 								$resp_id,
				// 								$prop_id,
				// 								$unit_no,
				// 								$full_name,
				// 								$cellphone,
				// 								$q_num,
				// 								$responce
				// 							);
				// }else{
				// 	$save 	= self::$app_controller->update_survey_responce (
				// 											$form_id,
				// 											$resp_id,
				// 											$prop_id,
				// 											$unit_no,
				// 											$full_name,
				// 											$cellphone,
				// 											$q_num,
				// 											$responce
				// 										);
				// }
				
			}

			$return_array 	= array('status' => true);
		}

		return $return_array;
	}

}
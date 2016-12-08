<?php
/**
 * Forms Controller
 * 
 * @package 
 * @author  
 */
class FormsController
{
	static public $app_controller;
	static public $property_id;
	static public $property_name;
	static public $prop_array;
	static public $company_id;


	public function __construct() {
		self::$app_controller 	= new AppController();
		self::$property_id   	= (isset($_REQUEST['prop_id'])) ? self::$app_controller->sanitise_string($_REQUEST['prop_id']) : '';
		self::$property_name 	= (isset($_REQUEST['prop_name'])) ? self::$app_controller->sanitise_string($_REQUEST['prop_name']) : '';

		self::$app_controller->set_session_start();
		self::$company_id = $_SESSION['company_id'];

		// die(self::$app_controller->hash_password('janey3'));
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

		self::$app_controller->set_session_start();
		$email 			= $_SESSION['email'];
		$company_id 	= $_SESSION['company_id'];

		switch ($subRequest) {
			case 'GetTable':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_property_table ($prop_id);

				return json_encode($table);
			break;

			case 'GetFormByID':
				$form_id 		= self::$app_controller->sanitise_string($request->parameters['FormID']);
				$form 			= self::set_up_form ($form_id);
				return json_encode($form);
			break;

			case 'GetTableArchived':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_archived_resident_table ($prop_id, $company_id);

				return json_encode($table);
			break;

			case 'GetResidents':
				
				$table 			= self::$app_controller->get_residents ();

				return json_encode($table);
			break;
			case 'GetPropResidents':
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);

				$table 			= self::$app_controller->get_prop_residents ($prop_id);

				return json_encode($table);
			break;

			case 'GetTableTrustees':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_trustees_table ($prop_id, $company_id);

				return json_encode($table);
			break;
			case 'GetResidentByID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$res 			= self::$app_controller->get_resident_byid ($ID);

				return json_encode($res[0]);

			case 'GetResponcesByID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$res 			= self::$app_controller->get_responses_byid ($ID);

				return json_encode($res);
			break;

			case 'GetResponcesByFormID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$responces 		= self::$app_controller->get_responses_byid ($ID);
				$group_resp  	= self::$app_controller->array_remove_dublicates ($responces, 'submit_id');

				return json_encode($group_resp);
			break;

			case 'GetResponcesBySubmissionID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$company_id 	= $_SESSION['company_id'];

				$responces 		= self::setup_all_responce_table ($ID, $company_id);

				return json_encode($responces);
			break;


			case 'GetResponcesByGroup':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$res 			= self::setup_responce_table ($ID);

				return json_encode($res);
			break;

			case 'GetTimeline':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$ResID 			= self::$app_controller->sanitise_string ($request->parameters['ResID']);
				$graph 			= self::set_up_timeline ($prop_array, $company_id, $ResID);

			return json_encode ($graph);
			case 'UploadEmailImage':

				$upload 	= self::upload_image($_FILES);
			return stripslashes(json_encode($upload));

			case 'PrintResponces':
				$SubmitID 		= self::$app_controller->sanitise_string ($request->parameters['SubmitID']);
				$company_id 	= $_SESSION['company_id'];
			return json_encode(self::print_responces($SubmitID, $company_id));
			break;

			case 'DownloadFile':
				$file_name 		= self::$app_controller->sanitise_string ($request->parameters['file_name']);
				$prop_id 		= self::$app_controller->sanitise_string ($request->parameters['prop_id']);
				

				$download  		= self::dowload_file ($file_name, $company_id, $prop_id);
			return $download;
			exit();
			break;
			case 'GetApprovalEmail':
				$submission_id    	= self::$app_controller->sanitise_string ($request->parameters['submission_id']);
				$prop_id    		= self::$app_controller->sanitise_string ($request->parameters['prop_id']);

			    $html  				= self::set_up_approval_email ($submission_id, $company_id, $prop_id);

			    return json_encode(array('html' => $html));
			    // echo $html;
			    // die();
			    break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 				= $_SESSION['email'];
					$first_name			= $_SESSION['first_name'];
					$last_name			= $_SESSION['last_name'];
					$modules			= $_SESSION['modules'];

					$this_page 			= 'property' . self::$property_id;
					$current 			= 'forms' 	 . self::$property_id;

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, $current);
					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Forms',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);

						// die(var_dump($pass));
						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view   ('Asidemenu', $pass);
						self::$app_controller->get_view   ('Forms', $pass);
						self::$app_controller->get_footer (array('page' => 'forms'));
						exit();
					}else{
						self::$app_controller->redirect_to ('/Login');
					}
				}else{
					self::$app_controller->redirect_to('/Login');
				}
				
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
			

			case 'SaveForm':
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$FormName 			= self::$app_controller->sanitise_string($request->parameters['FormName']);
				$FormInstructions	= self::$app_controller->sanitise_string($request->parameters['FormInstructions']);
				$QuestionNumber 	= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionNumber']);
				$QuestionOptions 	= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionOptions']);
				$QuestionText 		= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionText']);
				$QuestionType 		= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionType']);		
				$QuestionMandatory 	= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionMandatory']);		

				$save 				= self::save_form ($prop_id, $FormName, $QuestionNumber, $QuestionOptions, $QuestionText, $QuestionType, $QuestionMandatory, $FormInstructions);
				return json_encode($save);
				break;

			case 'EditForm':
				$FormID 			= self::$app_controller->sanitise_string($request->parameters['EditFormID']);
				$FormName 			= self::$app_controller->sanitise_string($request->parameters['FormName']);
				$FormInstructions	= self::$app_controller->sanitise_string($request->parameters['FormInstructions']);
				$QuestionNumber 	= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionNumber']);
				$QuestionOptions 	= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionOptions']);
				$QuestionText 		= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionText']);
				$QuestionType 		= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionType']);	
				$QuestionMandatories= array_map(self::$app_controller->sanitise_string, $request->parameters['QuestionMandatory']);		

				$save 				= self::edit_form ($FormID, $FormName, $QuestionNumber, $QuestionOptions, $QuestionText, $QuestionType, $QuestionMandatories, $FormInstructions);



				return json_encode($save);
				break;

				case 'DeleteForm':
				$FormID 			= self::$app_controller->sanitise_string($request->parameters['FormID']);
				

				$save 				= self::delete_form ($FormID);

				return json_encode($save);
				break;

			case 'LinkResident':
				$UnitNumber 			= self::$app_controller->sanitise_string($request->parameters['UnitNumber']);
				$ResidentList 			= self::$app_controller->sanitise_string($request->parameters['ResidentList']);
				$SubmissionID 			= self::$app_controller->sanitise_string($request->parameters['SubmissionID']);

				$save 					= self::link_resident ($UnitNumber, $ResidentList, $SubmissionID);
				return json_encode($save);
				break;

			case 'EditQuery':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['EditID']);
				$QueryType 	= self::$app_controller->sanitise_string($request->parameters['QueryTypeedt']);
				$UserID 	= self::$app_controller->sanitise_string($request->parameters['UsersListedt']);
				
				$Query 		= self::$app_controller->sanitise_string($request->parameters['Queryedt']);
				$Property	= self::$app_controller->sanitise_string($request->parameters['PropertyListedt']);
				$Unit 		= self::$app_controller->sanitise_string($request->parameters['Unitedt']);
				
				$edit 		= self::edit_query ($ID, $QueryType, $UserID, $Query, $Property, $Unit);
				return json_encode($edit);
				break;
			case 'DuplicateForm':
				$FormID 		= self::$app_controller->sanitise_string($request->parameters['DuplicateFormID']);
				$PropertyName 	= self::$app_controller->sanitise_string($request->parameters['PropertyName']);
				
				$edit 			= self::duplicate_form ($FormID, $PropertyName);
				return json_encode($edit);
				break;
			

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


	static public function get_responses_object_html ($SubmitID, $company_id) {

		$responces 		= self::$app_controller->get_responce_by_subid ($SubmitID);

		$form_name 		= '';
		$res_name 		= '';
		$res_cell 		= '';
		$complex_name 	= '';
		$directory 	= '';
		foreach ($responces as $c) {
			$questions 		= json_decode ($c['questions'], true);

			$resp_q_num 	= $c['q_num'];
			$resp_responce 	= $c['responce'];
			$resp_created 	= $c['created'];
			$prop_id 		= $c['prop_id'];
			$form_name 		= $c['name'];
			$res_name 		= $c['res_name'];
			$res_cell 		= $c['res_cell'];
			$complex_name 	= $c['complex_name'];
			$prop_id 		= $c['prop_id'];

			// loop trough questions
			foreach ($questions as $q) {
				$q_num 		= $q['q_num'];
				$q_type 	= $q['q_type'];
				$q_text 	= $q['q_text'];
				$responce 	= $q['responce'];

				$directory 	= '../companies/' . $company_id .'/properties/' . $prop_id . '/form_submission_files/';

				if ($q_type == 'file_upload' OR $q_type == 'signature') {
					$file 			= $directory  . $resp_responce;
					// $resp_responce  = '<a href="Forms/DownloadFile?file_name=' .$resp_responce. '&prop_id=' .$prop_id. '" >' . $resp_responce . '</a>';

					// die(var_dump($file));

					$resp_responce  = '<img src="'.$file.'" />';
				}

				// Star

				// if ($q_type == 'star_rating') {
				// 	$stars 			= '<div class="stars">';
					
				// 	$stars 		   .= '<a class="star fullStar" title="1"></a>';

				// 	$stars 		   .= '</div>';
				// 	$resp_responce  = $stars;
				// }

				// die(var_dump($stars));




				if ($q_num === $resp_q_num) {
					$return[] 		= array(
						'q_num'  	=> $resp_q_num,
						'q_text'  	=> $q_text,
						'responce'  => $resp_responce,
						'created'  	=> $resp_created
					);
				}

			}
			
		}

		$return_data = array(
				'form_name' => $form_name,
				'res_name'  => $res_name,
				'res_cell'  => $res_cell,
				'complex_name'  => $complex_name,
				'Responses' => $return
				);


		$html 			= self::$app_controller->get_responce_html ($return_data);

		return $html;

	}

	static public function set_up_approval_email ($submission_id, $company_id, $prop_id) {

		$return;
		$onwers = self::$app_controller->get_resident_owners_bypropid ($prop_id);

		foreach ($onwers as $o) {
			$owner_email = $o['residentNotifyEmail'];
			$owner_name  = $o['residentName'];
		}

		$responce_html 	= self::get_responses_object_html ($submission_id, $company_id);
		$email 			= self::$app_controller->get_approval_email ($owner_name, $responce_html, $submission_id);

						
		return $email;
	}

	static public function print_responces ($SubmitID, $company_id) {

		$return 	 	= array();
		$return_data 	= array();
		
		$html 			= self::get_responses_object_html($SubmitID, $company_id);
		$title 		= "ConnectLiving - Form Responses";
		$author 	= "ConnectLiving";
		self::$app_controller->get_report_pdf ($html, $author, $title);
		exit;
	}


	static public function dowload_file ($file_name, $company_id, $prop_id) {
		$file_url 		= '../companies/' . $company_id .'/properties/' . $prop_id . '/form_submission_files/'.$file_name;

		// die(var_dump(file_exists ($file_url)));

		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"".$file_name."\""); 
		readfile($file_url);
	}


	/*** save comment ***/
	static public function save_form ($prop_id, $FormName, $QuestionNumbers, $QuestionOptions, $QuestionTexts, $QuestionTypes, $QuestionMandatories, $FormInstructions){

		// die(var_dump($QuestionMandatories));

		if (!self::$app_controller->validate_variables ($FormName, 3)) {
			return array('status'  => false, 'text' => 'Invalid Form Name');
		}

		$count   = 1;
		$cq_nums = 0;
		foreach ($QuestionNumbers as $QuestionNumber) {
			if (!is_numeric($QuestionNumber)) {
				return array('status'  => false, 'text' => 'Invalid Question number in form ' . $count);
				exit();
			}

			$QuestionObject['QuestionNumber'][]  = $QuestionNumber;
			$count++;
			$cq_nums++;
		}

		$count = 1;
		foreach ($QuestionTexts as $QuestionText) {
			if (!self::$app_controller->validate_variables ($QuestionText, 3)) {
				return array('status'  => false, 'text' => 'Invalid Question Text in form ' . $count);
				exit();
			}

			$QuestionObject['QuestionText'][]  = $QuestionText;
			$count++;
		}

		$count = 1;
		foreach ($QuestionTypes as $QuestionType) {
			if (!self::$app_controller->validate_variables ($QuestionType, 3)) {
				return array('status'  => false, 'text' => 'Invalid Question Type in form ' . $count);
				exit();
			}

			$QuestionObject['QuestionType'][]  = $QuestionType;
			$count++;
		}

		$count = 1;
		foreach ($QuestionOptions as $QuestionOption) {
			if (!self::$app_controller->validate_variables ($QuestionOption, 3)) {
				return array('status'  => false, 'text' => 'Invalid Question Option in form ' . $count);
				exit();
			}

			$QuestionObject['QuestionOption'][]  = $QuestionOption;
			$count++;
		}

		

		$count = 1;
		
		foreach ($QuestionMandatories as $QuestionMandatory) {
			$QuestionObject['QuestionMandatory'][]   = $QuestionMandatory;
			$count++;
		}

		for ($i=0; $i < count($QuestionObject['QuestionNumber']); $i++) { 
			$q_num  	  = $QuestionObject['QuestionNumber'][$i];
			$q_text 	  = $QuestionObject['QuestionText'][$i];
			$q_type 	  = $QuestionObject['QuestionType'][$i];
			$q_option 	  = $QuestionObject['QuestionOption'][$i];
			$q_mandatory  = $QuestionObject['QuestionMandatory'][$i];

			$form_array[] = array(
						'q_num' 		=> $q_num,
						'q_text' 		=> $q_text,
						'q_type' 		=> $q_type,
						'q_option' 		=> $q_option,
						'q_mandatory' 	=> $q_mandatory
					);
		}


		// die(print_r($form_array));

		$save = self::$app_controller->insert_new_form ($prop_id, $FormName, $FormInstructions, json_encode($form_array));
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** edit form ***/
	static public function edit_form ($FormID, $FormName, $QuestionNumbers, $QuestionOptions, $QuestionTexts, $QuestionTypes, $QuestionMandatories, $FormInstructions){

		
		if (!self::$app_controller->validate_variables ($FormName, 3)) {
			return array('status'  => false, 'text' => 'Invalid Form Name');
		}

		$count = 1;
		foreach ($QuestionNumbers as $QuestionNumber) {
			if (!is_numeric($QuestionNumber)) {
				return array('status'  => false, 'text' => 'Invalid Question number in form ' . $count);
				exit();
			}

			$QuestionObject['QuestionNumber'][]  = $QuestionNumber;
			$count++;
		}

		$count = 1;
		foreach ($QuestionTexts as $QuestionText) {
			if (!self::$app_controller->validate_variables ($QuestionText, 3)) {
				return array('status'  => false, 'text' => 'Invalid Question Text in form ' . $count);
				exit();
			}

			$QuestionObject['QuestionText'][]  = $QuestionText;
			$count++;
		}

		$count = 1;
		foreach ($QuestionTypes as $QuestionType) {
			if (!self::$app_controller->validate_variables ($QuestionType, 3)) {
				return array('status'  => false, 'text' => 'Invalid Question Type in form ' . $count);
				exit();
			}

			$QuestionObject['QuestionType'][]  = $QuestionType;
			$count++;
		}

		$count = 1;
		foreach ($QuestionOptions as $QuestionOption) {
			if (!self::$app_controller->validate_variables ($QuestionOption, 3)) {
				return array('status'  => false, 'text' => 'Invalid Question Option in form ' . $count);
				exit();
			}

			$QuestionObject['QuestionOption'][]  = $QuestionOption;
			$count++;
		}

		$count = 1;
		
		foreach ($QuestionMandatories as $QuestionMandatory) {
			$QuestionObject['QuestionMandatory'][]   = $QuestionMandatory;

			$count++;
		}

		
		for ($i=0; $i < count($QuestionObject['QuestionNumber']); $i++) { 
			$q_num  	  = $QuestionObject['QuestionNumber'][$i];
			$q_text 	  = $QuestionObject['QuestionText'][$i];
			$q_type 	  = $QuestionObject['QuestionType'][$i];
			$q_option 	  = $QuestionObject['QuestionOption'][$i];
			$q_mandatory  = $QuestionObject['QuestionMandatory'][$i];

			$form_array[] = array(
						'q_num' 		=> $q_num,
						'q_text' 		=> $q_text,
						'q_type' 		=> $q_type,
						'q_option' 		=> $q_option,
						'q_mandatory' 	=> $q_mandatory
					);
		}

		$save = self::$app_controller->update_form ($FormID, $FormName, $FormInstructions, json_encode($form_array));
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Updated');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** delete form ***/
	static public function delete_form ($FormID){

		$findform = self::$app_controller->get_form_by_id ($FormID);


		if (!is_numeric ($FormID) OR count($findform) == 0) {
			return array('status'  => false, 'text' => 'Invalid Form ID');
		}

		$save = self::$app_controller->delete_this_form ($FormID);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Deleted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** link resident ***/
	static public function link_resident ($UnitNumber, $ResID, $SubmissionID) {
		
		if (!is_numeric($ResID)) {
			return array('status'  => false, 'text' => 'Invalid Resident');
		}

		if (!is_numeric($SubmissionID)) {
			return array('status'  => false, 'text' => 'Invalid Submission ID');
		}

		
		$save = self::$app_controller->update_submission ($UnitNumber, $ResID, $SubmissionID);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Updated');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** link resident ***/
	static public function duplicate_form ($FormID, $PropertyName) {
		
		if (!is_numeric($PropertyName)) {
			return array('status'  => false, 'text' => 'Invalid Property');
		}

		if (!is_numeric($FormID)) {
			return array('status'  => false, 'text' => 'Invalid Form ID');
		}

		
		$save = self::$app_controller->copy_form ($FormID, $PropertyName);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Copied');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}



	/**
	 * @param
	 * @return
	 */
	static public function set_up_form ($form_id) {

		$return_array 	=  array();
		$forms 			= self::$app_controller->get_form_by_id ($form_id);

		// decode questions object
		array_walk ( $forms, function (&$key) { $key["questions"] = json_decode($key['questions'], true); } );
		
		foreach ($forms as $s) {

			$questions = $s['questions'];

			// Format option to an array
			array_walk ( $questions, function (&$key) { $key['q_option'] = explode( ',', $key['q_option']); } );

			//Format
			// print_r($questions);
			// die();


			$return_array[] = array(
				'id' 			=> $s['id'],
				'prop_id' 		=> $s['prop_id'],
				'prop_name' 	=> $s['propertyName'],
				'name' 			=> $s['name'],
				'form_instruction' => $s['form_instruction'],
				'questions'		=> $questions
				);

		}
		
		return $return_array;
	}

	static public function get_property_table ($property_id) {

		$return 	 = array();

		$residents   = self::$app_controller->get_all_forms ($property_id);

		

		foreach ($residents as $c) {

			$from_id 	= $c['id'];
			$from_name  = $c['name'];
			$unit_num 	= '';
			$res_name 	= '';
			$res_cell 	= '';


			$but 	= '<button ';
			$but 	.= '	rel="tooltip" '; 
			$but 	.= '	data-original-title="Edit Form" ';
			$but 	.= '	data-toggle 	= "modal" ';
			$but 	.= '	data-target 	= "#EditFormModal" ';
			$but 	.= '	data-form-id 	= "'.$from_id.'"';
			
			$but 	.= '	class="btn btn-info btn-sm" '; 
			$but 	.= '	>';
			$but 	.= '	<span class="glyphicon glyphicon-pencil"></span> ';
			$but 	.= '</button>';
			
			$but 	.= '<button ';
			$but 	.= '	rel="tooltip" '; 
			$but 	.= '	data-original-title="Fill Form" ';
			$but 	.= '	data-toggle 	= "modal" ';
			$but 	.= '	data-target 	= "#FillFormModal" ';
			$but 	.= '	data-form-id 	= "'.$from_id.'"';
			$but 	.= '	data-form-name 	= "'.$from_name.'"';
			$but 	.= '	data-unit-num 	= "'.$unit_num.'"';
			$but 	.= '	data-res-name 	= "'.$res_name.'"';
			$but 	.= '	data-res-cell 	= "'.$res_cell.'"';
			$but 	.= '	class="btn btn-primary btn-sm" '; 
			$but 	.= '	>';
			$but 	.= '	<span class="glyphicon glyphicon-play"></span> ';
			$but 	.= '</button>';

			$but 	.= '<button ';
			$but 	.= '	rel="tooltip" '; 
			$but 	.= '	data-original-title="Duplicate Form" ';
			$but 	.= '	data-toggle 	= "modal" ';
			$but 	.= '	data-target 	= "#DuplicateFormModal" ';
			$but 	.= '	data-form-id 	= "'.$from_id.'"';
			$but 	.= '	data-form-name 	= "'.$from_name.'"';
			$but 	.= '	data-unit-num 	= "'.$unit_num.'"';
			$but 	.= '	data-res-name 	= "'.$res_name.'"';
			$but 	.= '	data-res-cell 	= "'.$res_cell.'"';
			$but 	.= '	class="btn btn-warning btn-sm" '; 
			$but 	.= '	>';
			$but 	.= '	<span class="glyphicon glyphicon-copy"></span> ';
			$but 	.= '</button>';

			$but 	.= '<button ';
			$but 	.= '	rel="tooltip" '; 
			$but 	.= '	data-original-title="Delete Form" ';
			$but 	.= '	data-toggle 	= "modal" ';
			$but 	.= '	data-target 	= "#DeleteFormModal" ';
			$but 	.= '	data-form-id 	= "'.$from_id.'"';
		
			$but 	.= '	class="btn btn-danger btn-sm" '; 
			$but 	.= '	>';
			$but 	.= '	<span class="glyphicon glyphicon-remove"></span> ';
			$but 	.= '</button>';


			$return[] 	= array(

				'id'  				=> $from_id,
				'name'  			=> $c['name'],
				'questions'  		=> $c['questions'],
				'created'  			=> $c['created'],

				'buttons' 			=> $but
				);
		}
		
		return $return;

	}

	static public function setup_responce_table ($property_id) {

		$return 	 = array();

		$residents   = self::$app_controller->get_responce_byprop ($property_id);

		// Goup by form id
		$group_resp  = self::$app_controller->array_remove_dublicates ($residents, 'form_id');

		foreach ($group_resp as $c) {
			$return[] 	= array(
				'submit_id'  		=> $c['submit_id'],
				'res_id'  			=> $c['res_id'],
				'form_id'  			=> $c['form_id'],
				'form_name'  		=> $c['name'],
				'prop_id'  			=> $c['prop_id'],
				'unit_no'  			=> $c['unit_no'],
				'res_name'  		=> $c['res_name'],
				'res_cell'  		=> $c['res_cell'],
				'created'  			=> $c['created']
				);
		}
		
		return $return;

	}

	static public function setup_all_responce_table ($ID, $company_id) {

		$return 	 	= array();
		$responces 		= self::$app_controller->get_responce_by_subid ($ID);

		foreach ($responces as $c) {

			$questions 		= json_decode ($c['questions'], true);

			$resp_q_num 	= $c['q_num'];
			$resp_responce 	= $c['responce'];
			$resp_created 	= $c['created'];
			$prop_id 		= $c['prop_id'];

			

			// loop trough questions
			foreach ($questions as $q) {
				$q_num 		= $q['q_num'];
				$q_type 	= $q['q_type'];
				$q_text 	= $q['q_text'];
				$responce 	= $q['responce'];

				if ($q_type == 'file_upload' OR $q_type == 'signature') {
					$file 			= $directory  . $resp_responce;
					$resp_responce  = '<a href="Forms/DownloadFile?file_name=' .$resp_responce. '&prop_id=' .$prop_id. '" >' . $resp_responce . '</a>';
				}

				if ($q_num === $resp_q_num) {
					$return[] 		= array(
						'q_num'  	=> $resp_q_num,
						'q_text'  	=> $q_text,
						'responce'  => $resp_responce,
						'created'  	=> $resp_created
					);
				}

			}

			
		}
		
		return $return;

	}


}
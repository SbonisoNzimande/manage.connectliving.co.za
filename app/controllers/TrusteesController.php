<?php
/**
 * Trustees Controller
 * 
 * @package 
 * @author  
 */
class TrusteesController
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

		switch ($subRequest) {
			case 'GetMinutes':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_minutes_table ($prop_id);

				return json_encode($table);
			break;

			case 'GetAllEvents':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::set_up_events ($prop_id);

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
				$company_id 	= $_SESSION['company_id'];

				$download  		= self::dowload_file ($file_name, $company_id, $prop_id);
			return $download;
			exit();
			break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 				= $_SESSION['email'];
					$first_name			= $_SESSION['first_name'];
					$last_name			= $_SESSION['last_name'];
					$modules			= $_SESSION['modules'];

					$this_page 			= 'property' . self::$property_id;
					$current 			= 'trustees' 	 . self::$property_id;

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, $current);
					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Trustees',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);

						// die(var_dump($pass));
						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view   ('Asidemenu', $pass);
						self::$app_controller->get_view   ('Trustees', $pass);
						self::$app_controller->get_footer (array('page' => 'trustees'));
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
			
			case 'AddEvent':
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$title 				= self::$app_controller->sanitise_string($request->parameters['title']);
				$start 				= self::$app_controller->sanitise_string($request->parameters['start']);
				$end 				= self::$app_controller->sanitise_string($request->parameters['end']);

				$save 				= self::add_event ($prop_id, $title, $start, $end);
				return json_encode($save);
			break;
			case 'EditEvent':
				$id 				= self::$app_controller->sanitise_string($request->parameters['id']);
				$title 				= self::$app_controller->sanitise_string($request->parameters['title']);
				$start 				= self::$app_controller->sanitise_string($request->parameters['start']);
				$end 				= self::$app_controller->sanitise_string($request->parameters['end']);

				$save 				= self::edit_event ($id, $title, $start, $end);
				return json_encode($save);
			break;
			case 'UploadDocument':
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$MeetingDate 		= self::$app_controller->sanitise_string($request->parameters['MeetingDate']);
				$UploadFile 		= $_FILES['UploadFile'];
				$company_id 		= $_SESSION['company_id'];

				$save 				= self::upload_document ($company_id, $prop_id, $MeetingDate, $UploadFile);
				return json_encode($save);
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


	static public function upload_document ($company_id, $prop_id, $MeetingDate, $UploadFile) {

		if (empty($MeetingDate)) {
			return array('status'  => false, 'text' => 'Please select Meeting Date');
		}

		if (!isset($UploadFile)) {
			return array('status'  => false, 'text' => 'Please select file');
		}

		$temp 		= explode(".", $UploadFile["name"]);
		$ext 		= end($temp);

		// if ($ext !== 'jpg') {
		// 	return array('status'  => false, 'text' => 'Please upload only jpg file');
		// }

		$dir 		= '../companies/' . $company_id .'/properties/' . $prop_id .'/minutes';

		self::$app_controller->created_directory($dir);

		$file_name  = self::$app_controller->upload_file ($UploadFile, $dir);

		if ($file_name) {
			$save 	= self::$app_controller->insert_minutes (
										$prop_id,
										$file_name,
										$MeetingDate
									);

			if ($save) {
				return array('status'  => true, 'text' => 'Document uploaded');
			}else{
				return array('status'  => false, 'text' => 'Failed to insert ' . $save);
			}

		}else{
			return array('status'  => false, 'text' => 'File not uploaded: ' .$upload);
		}

		
	}

	static public function add_event ($prop_id, $title, $start, $end) {

		$save 	= self::$app_controller->insert_trustee_event (
										$prop_id,
										$title,
										$start,
										$end
									);

		if ($save) {
			return array('status'  => true, 'text' => 'Event added');
		}else{
			return array('status'  => false, 'text' => 'Failed to insert ' . $save);
		}

		
	}

	static public function edit_event ($id, $title, $start, $end) {

		$save 	= self::$app_controller->update_trustee_event (
										$id,
										$title,
										$start,
										$end
									);

		if ($save) {
			return array('status'  => true, 'text' => 'Event added');
		}else{
			return array('status'  => false, 'text' => 'Failed to insert ' . $save);
		}

		
	}


	static public function dowload_file ($file_name, $company_id, $prop_id) {
		$file_url 		= '/companies/' . $company_id .'/properties/' . $prop_id . '/minutes';

		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"".$file_name."\""); 
		readfile($file_url);
	}


	/*** save comment ***/
	static public function save_form ($prop_id, $FormName, $QuestionNumbers, $QuestionOptions, $QuestionTexts, $QuestionTypes){


		
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


		for ($i=0; $i < count($QuestionObject['QuestionNumber']); $i++) { 
			$q_num  	= $QuestionObject['QuestionNumber'][$i];
			$q_text 	= $QuestionObject['QuestionText'][$i];
			$q_type 	= $QuestionObject['QuestionType'][$i];
			$q_option 	= $QuestionObject['QuestionOption'][$i];

			$form_array[] = array(
						'q_num' 	=> $q_num,
						'q_text' 	=> $q_text,
						'q_type' 	=> $q_type,
						'q_option' 	=> $q_option
						);
		}


		// die(print_r($form_array));

		$save = self::$app_controller->insert_new_form ($prop_id, $FormName, json_encode($form_array));
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
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



	/**
	 * @param
	 * @return
	 */
	static public function set_up_events ($prop_id) {

		$return_array 	=  array();
		$events			= self::$app_controller->get_all_events ($prop_id);
		
		foreach ($events as $e) {

			$return_array[] = array(
				'id' 			=> $e['id'],
				'title' 		=> $e['title'],
				'start' 		=> $e['start'],
				'end' 			=> $e['end']
				);

		}
		
		return $return_array;
	}

	static public function get_minutes_table ($property_id) {

		$return 	 = array();

		$residents   = self::$app_controller->get_all_minutes ($property_id);

		foreach ($residents as $c) {

			$id 	= $c['id'];
			

			$button 	= '<button class="btn btn-info btn-xs " data-title="Edit" data-toggle="modal" data-target="#editDocumentModal" data-trust-id="'.$c['id'].'" aria-expanded="false"><span class="fa fa-pencil-square-o"></span></button>';

			$button 	.= '<a href="Trustees/DownloadFile?file_name=' .$c['doc_name']. '&prop_id=' .$c['prop_id']. '" class="btn btn-warning btn-xs " aria-expanded="false"><span class="fa fa-cloud-download"></span></a>';

			$button 	.= '<button class="btn btn-danger btn-xs " data-title="Delete" data-toggle="modal" data-target="#deleteDocumentModal" data-trust-id="'.$c['id'].'" aria-expanded="false"><span class="fa fa-times"></span></button>';


			$return[] 	= array(

				'id'  				=> $id,
				'doc_name'  		=> $c['doc_name'],
				'meeting_date'  	=> $c['meeting_date'],
				'created'  			=> $c['created'],
				'buttons' 			=> $button
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

				if ($q_type == 'file_upload') {
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
<?php
/**
 * Manage Controller
 * 
 * @package 
 * @author  
 */
class ManageController
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
			case 'GetTable':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_resident_table ($prop_id, $company_id);

				return json_encode($table);
			break;

			case 'GetTableArchived':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_archived_resident_table ($prop_id, $company_id);

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
			break;

			case 'GetTimeline':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$ResID 			= self::$app_controller->sanitise_string ($request->parameters['ResID']);
				$graph 			= self::set_up_timeline ($prop_array, $company_id, $ResID);

			return json_encode ($graph);
			
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];



					$this_page 		= 'property' . self::$property_id;
					$current 		= 'manage' 	 . self::$property_id;

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, $current);
					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Manage',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);

						// die(var_dump($pass));
						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view   ('Asidemenu', $pass);
						self::$app_controller->get_view   ('Manage', $pass);
						self::$app_controller->get_footer (array('page' => 'manage'));
						exit();
					}else{
						self::$app_controller->redirect_to ('/Login');
					}
				}else{
					self::$app_controller->redirect_to('Login');
				}
				
			break;
		}
	}


	/**
	 * POST Request
	 *
	 * @param  
	 * @return 
	 */
	
	public function post ($request) {
		$subRequest = (isset($request->url_elements[1])) ? $request->url_elements[1] : '';

		switch ($subRequest) {

			case 'UploadEmailImage':

				$upload 	= self::upload_image($_FILES);
			return stripslashes(json_encode($upload));
			case 'CreateResident':
				$UnitNumber   			= self::$app_controller->sanitise_string($request->parameters['UnitNumber']);
				$PropertyID   			= self::$app_controller->sanitise_string($request->parameters['PropertyID']);
				$ResidentName   		= self::$app_controller->sanitise_string($request->parameters['ResidentName']);
				$ResidentPhone   		= self::$app_controller->sanitise_string($request->parameters['ResidentPhone']);
				$ResidentCellphone   	= self::$app_controller->sanitise_string($request->parameters['ResidentCellphone']);
				$ResidentNotifyEmail   	= self::$app_controller->sanitise_string($request->parameters['ResidentNotifyEmail']);
				$ResidentType   		= self::$app_controller->sanitise_string($request->parameters['ResidentType']);
				$ResidentTrustee 		= self::$app_controller->sanitise_string($request->parameters['ResidentTrustee']);
				
				


				$save 				= self::create_resident (
											$UnitNumber,
											$PropertyID,
											$ResidentName,
											$ResidentPhone,
											$ResidentCellphone,
											$ResidentNotifyEmail,
											$ResidentType,
											$ResidentTrustee
										);

				
				return json_encode($save);

			break;

			case 'UpdateResident':
				$ID   					= self::$app_controller->sanitise_string($request->parameters['ResidentID']);
				$UnitNumber   			= self::$app_controller->sanitise_string($request->parameters['UnitNumber']);
				$ResidentName   		= self::$app_controller->sanitise_string($request->parameters['ResidentName']);
				$ResidentPhone   		= self::$app_controller->sanitise_string($request->parameters['ResidentPhone']);
				$ResidentCellphone   	= self::$app_controller->sanitise_string($request->parameters['ResidentCellphone']);
				$ResidentNotifyEmail   	= self::$app_controller->sanitise_string($request->parameters['ResidentNotifyEmail']);
				$ResidentType   		= self::$app_controller->sanitise_string($request->parameters['ResidentType']);
				$ResidentTrustee 		= self::$app_controller->sanitise_string($request->parameters['ResidentTrustee']);
				
				


				$save 				= self::edit_resident (
											$ID,
											$UnitNumber,
											$ResidentName,
											$ResidentPhone,
											$ResidentCellphone,
											$ResidentNotifyEmail,
											$ResidentType,
											$ResidentTrustee
										);

				
				return json_encode($save);

			break;


			case 'ArchiveUnit':	
				$ID   					= self::$app_controller->sanitise_string($request->parameters['ID']);
				$save 					= self::archive_unit ($ID);

				return json_encode($save);
			break;

			case 'SaveComment': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentText']);
				$ResID 		= self::$app_controller->sanitise_string($request->parameters['ResID']);
				$UserID 	= $_SESSION['user_id'];
				$CompanyID 	= $_SESSION['company_id'];
				$FileData 	= array_map(self::$app_controller->sanitise_string, $_FILES);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyID']);

				
				$send    	= self::save_comment ($Message, $ResID, $FileData, $UserID, $CompanyID, $PropertyID);
				return json_encode($send);
			break;

			case 'SaveEmail': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['EmailText']);
				$ResID 		= self::$app_controller->sanitise_string($request->parameters['ResID']);
				$UserID 	= $_SESSION['user_id'];
				$CompanyID 	= $_SESSION['company_id'];
				$FileData 	= array_map(self::$app_controller->sanitise_string, $_FILES);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyID']);


				
				$send    	= self::save_email ($Message, $ResID, $FileData, $UserID, $CompanyID, $PropertyID);
				return json_encode($send);
			break;

			case 'SendSMS': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentSMS']);
				$ResID 		= self::$app_controller->sanitise_string($request->parameters['ResID']);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyID']);
				$UserID 	= $_SESSION['user_id'];

				$send    	= self::send_sms (
								$Message,
								$ResID,
								$PropertyID,
								$UserID
							  );
				return json_encode($send);
			break;

			


			// UpdateSaveLease
		}
	}


	static public function upload_image ($file) {

		// die(var_dump($file));
		// Allowed extentions.
		$allowedExts 	= array("gif", "jpeg", "jpg", "png");

		// Get filename.
		$temp 		 	= explode(".", $file["file"]["name"]);

		// Get extension.
		$extension 	 	= end($temp);

		// Generate new random name.
	    $name 			= sha1(microtime()) . "." . $extension;

	    // Save file in the uploads folder.
	    move_uploaded_file($file["file"]["tmp_name"], getcwd() . "/public/images/email/" . $name);

	    // Generate response.
	    $response 		= new StdClass;
	    $response->link = "http://manage.connectliving.co.za/public/images/email/" . $name;

		return $response;
	}

	/*** send sms ***/
	static public function send_sms (
						$Message,
						$ResID,
						$PropertyID,
						$UserID
					) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid SMS Message');
		}

		$query = self::$app_controller->get_resident_byid ($ResID);

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Invalid Resident ID');
		}

		$cell_phone = '27' . $query[0]['residentCellphone'];


		if (!empty($cell_phone)) {
			$save 		= self::$app_controller->save_res_sms_coms ($Message, $ResID, $cell_phone, $UserID, $PropertyID);
			self::$app_controller->send_sms ($Message, $cell_phone);
		}else{
			$save 		= -1;
		}
		
		if ($save === true) {
			return array('status' => true, 'text' => 'SMS sent');
		}elseif($save === -1){
			return array('status' => false, 'text' => 'Sorry, no message was sent because no cellphone number found');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}


	/*** save comment ***/
	static public function save_comment (
							$Message, 
							$ResID, 
							$FileData, 
							$UserID, 
							$CompanyID, 
							$PropertyID
						) {


		
		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Comment');
		}

		$query = self::$app_controller->get_resident_byid ($ResID);

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Invalid Resident ID');
		}

		$name 	= '';
		if (!empty($FileData)) {
			$dir 					= '../companies/' .$CompanyID. '/residents/' .$ResID;
			$create 				= self::$app_controller->created_directory ($dir);
			$name 					= self::$app_controller->upload_file ($FileData['UploadFile'], $dir);
			// die(var_dump($name));
		}

		$save = self::$app_controller->save_resident_comment (
										$Message, 
										$ResID, 
										$name, 
										$UserID, 
										$PropertyID
									);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** save comment ***/
	static public function save_email (
							$Message, 
							$ResID, 
							$FileData, 
							$UserID, 
							$CompanyID, 
							$PropertyID
						){


		if (empty($Message)) {
			return array('status'  => false, 'text' => 'Invalid Email Text');
		}

		$query = self::$app_controller->get_resident_byid ($ResID);

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Invalid Resident ID');
		}

		$name 			= '';
		$attachment 	= array();

		if (!empty($FileData)) {
			$dir 					= '../companies/' .$CompanyID. '/residents/' .$ResID;
			$create 				= self::$app_controller->created_directory ($dir);
			$name 					= self::$app_controller->upload_file ($FileData['AttachementFile'], $dir);
			// die(var_dump($name));

			$attachment 			= array(
										'filename' => $name, 
										'path' => $dir . '/' . $name,
										'encoding' => 'base64',
										'type' => $FileData['AttachementFile']['type']
										);
		}

		$emails 		= explode(';', $query[0]['residentNotifyEmail']);

		// die(print_r($attachment));

		$email_detail 	= array();
		foreach ($emails as $e) {
			$email_detail[] = array(
				'email' => $e, 'full_name' => $query[0]['residentName']);
		}


		$email 	= self::$app_controller->send_emails ($Message, 'ConnectLiving - Resident email', '', $email_detail, $attachment);

		// die(print_r($email));
		$save 	= self::$app_controller->save_resident_email (
										$Message, 
										$ResID, 
										$name, 
										$UserID, 
										$PropertyID
									);

		
		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted, Email sent: ' . $email);
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** save query ***/
	static public function create_resident (
										$UnitNumber,
										$PropertyID,
										$ResidentName,
										$ResidentPhone,
										$ResidentCellphone,
										$ResidentNotifyEmail,
										$ResidentType,
										$ResidentTrustee
									) {

		if (!is_numeric($PropertyID)) {
			return array('status'  => false, 'text' => 'Invalid Property ID');
		}

		if (!self::$app_controller->validate_variables ($UnitNumber, 3)) {
			return array('status'  => false, 'text' => 'Invalid Unit Number');
		}

		if (!self::$app_controller->validate_variables ($ResidentName, 3)) {
			return array('status'  => false, 'text' => 'Invalid Resident Name');
		}

		// if (!self::$app_controller->validate_variables ($ResidentPhone, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Resident Phone');
		// }

		// if (!self::$app_controller->validate_variables ($ResidentCellphone, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Cellphone Phone');
		// }

		if (!self::$app_controller->validate_variables ($ResidentNotifyEmail, 18)) {
			return array('status'  => false, 'text' => 'Invalid Email(s)');
		}


		if (!self::$app_controller->validate_variables ($ResidentType, 3)) {
			return array('status'  => false, 'text' => 'Invalid Resident Type');
		}

		if (!self::$app_controller->validate_variables ($ResidentTrustee, 3)) {
			return array('status'  => false, 'text' => 'Invalid Resident Trustee');
		}

		
		$save 		= self::$app_controller->insert_resident (
								$UnitNumber,
								$PropertyID,
								$ResidentName,
								$ResidentPhone,
								$ResidentCellphone,
								$ResidentNotifyEmail,
								$ResidentType,
								$ResidentTrustee
							);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** edit query ***/
	static public function edit_resident (
										$ID,
										$UnitNumber,
										$ResidentName,
										$ResidentPhone,
										$ResidentCellphone,
										$ResidentNotifyEmail,
										$ResidentType,
										$ResidentTrustee
									) {

		$get_ress = self::$app_controller->get_resident_byid ($ID);


		if (count($get_ress) === 0) {
			return array('status'  => false, 'text' => 'Invalid Resident ID');
		}

		if (!self::$app_controller->validate_variables ($UnitNumber, 3)) {
			return array('status'  => false, 'text' => 'Invalid Unit Number');
		}

		if (!self::$app_controller->validate_variables ($ResidentName, 3)) {
			return array('status'  => false, 'text' => 'Invalid Resident Name');
		}

		// if (!self::$app_controller->validate_variables ($ResidentPhone, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Resident Phone');
		// }

		// if (!self::$app_controller->validate_variables ($ResidentCellphone, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Cellphone Phone');
		// }

		if (!self::$app_controller->validate_variables ($ResidentNotifyEmail, 18)) {
			return array('status'  => false, 'text' => 'Invalid Email(s)');
		}


		if (!self::$app_controller->validate_variables ($ResidentType, 3)) {
			return array('status'  => false, 'text' => 'Invalid Resident Type');
		}

		if (!self::$app_controller->validate_variables ($ResidentTrustee, 3)) {
			return array('status'  => false, 'text' => 'Invalid Resident Trustee');
		}

		
		$save 		= self::$app_controller->update_resident (
								$ID,
								$UnitNumber,
								$ResidentName,
								$ResidentPhone,
								$ResidentCellphone,
								$ResidentNotifyEmail,
								$ResidentType,
								$ResidentTrustee
							);

		if ($save === true) {
			return array('status' => true, 'text' => 'Updated');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}


	static public function get_resident_table ($property_id, $company_id) {

		$return 	 = array();

		$residents   = self::$app_controller->get_all_residents ($property_id);

		

		foreach ($residents as $c) {
			$return[] 	= array(
				'unitNumber'  			=> $c['unitNumber'],
				'residentName'  		=> $c['residentName'],
				'residentPhone'  		=> $c['residentPhone'],
				'residentCellphone' 	=> $c['residentCellphone'],
				'residentNotifyEmail' 	=> $c['residentNotifyEmail'],
				'residentType' 			=> $c['residentType'],
				'residentTrustee' 		=> $c['residentTrustee'],
				'residentStatus' 		=> $c['residentStatus'],
				
				'buttons' 				=> '<button class="btn btn-info btn-sm " data-title="Edit" data-toggle="modal" data-target="#EditResidentModal" data-res-id="'. $c['id'].'" aria-expanded="false"><span class="glyphicon glyphicon-pencil"></span></button>
											<button class="btn btn-danger btn-sm " data-title="Delete" data-toggle="modal" data-target="#ArchiveResidentModal" data-res-id="'.$c['id'].'" aria-expanded="false"><span class="glyphicon glyphicon-download-alt"></span></button>

											<button class="btn btn-default btn-sm" data-title="Communicate" rel="tooltip" data-original-title="Comment & Communicate" data-toggle="modal" data-target="#CommunicateModal" data-res-id="'.$c['id'].'" data-res-name="'.$c['residentName'].'" aria-expanded="false"><span class="fa fa-comment-o"></span></button>'
				);
		}
		
		return $return;

	}

	static public function set_up_timeline ($prop_array, $company_id, $ResID) {
		$ret_array 		= array();

		$sms_list 		= self::$app_controller->get_res_sms_coms ($ResID);
		$comm_list 		= self::$app_controller->get_res_comment_coms ($ResID);
		$email_list 	= self::$app_controller->get_res_email_coms ($ResID);
		$dir 			= '../companies/' .$company_id. '/residents/' .$ResID;

		// work out return array
		if (!empty($sms_list)) {// notifications

			foreach ($sms_list as $n) {
				$ret_array[] = array(
					'sort' 	  		=> $n['created'],
					'id' 	  		=> $n['id'],
					'subject' 		=> $n['full_name'] . ' sent an SMS',
					'message' 		=> $n['sms_text'],
					'type' 	  		=> 'SMS',
					'file' 	  		=> '',
					'resident_id'	=> $ResID,
					'date'    		=> self::$app_controller->human_timing($n['created']) . ' ago'
					);
			}

		}

		if (!empty($comm_list)) {// notifications

			foreach ($comm_list as $c) {

				if (!empty($c['file'])) {
					$file 		 = $c['file'];
				}else{
					$file 		 = '';
				}

				
				$ret_array[] = array(
					'sort' 	  		=> $c['date_created'],
					'id' 	  		=> $c['id'],
					'subject' 		=> $c['full_name'] . ' commented: ',
					'message' 		=> $c['comment_text'],
					'type' 	  		=> 'Comment',
					'file' 	  		=> $file,
					'resident_id'	=> $ResID,
					'date'    		=> self::$app_controller->human_timing($c['date_created']) . ' ago'
					);
			}

		}

		if (!empty($email_list)) {// notifications

			foreach ($email_list as $c) {

				if (!empty($c['file'])) {
					$file 		 = $c['file'];
				}else{
					$file 		 = '';
				}
				
				$ret_array[] = array(
					'sort' 	  		=> $c['created'],
					'id' 	  		=> $c['id'],
					'subject' 		=> $c['full_name'] . ' sent an email: ',
					'message' 		=> $c['email_text'],
					'type' 	  		=> 'Email',
					'file' 	  		=> $file,
					'resident_id'	=> $ResID,
					'date'    		=> self::$app_controller->human_timing($c['created']) . ' ago'
					);
			}

		}

		$sorted = self::$app_controller->array_orderby ($ret_array, 'sort', SORT_DESC);

		return $sorted;

	}
	
	static public function get_archived_resident_table ($property_id, $company_id) {

		$return 	 = array();

		$residents   = self::$app_controller->get_archived_residents ($property_id);

		foreach ($residents as $c) {
			$return[] 	= array(
				'unitNumber'  			=> $c['unitNumber'],
				'residentName'  		=> $c['residentName'],
				'residentPhone'  		=> $c['residentPhone'],
				'residentCellphone' 	=> $c['residentCellphone'],
				'residentNotifyEmail' 	=> $c['residentNotifyEmail'],
				'residentType' 			=> $c['residentType'],
				'residentTrustee' 		=> $c['residentTrustee'],
				'residentStatus' 		=> $c['residentStatus'],
				'buttons' 				=> '<button class="btn btn-default btn-sm" data-title="Communicate" data-toggle="modal" data-target="#ArchivedComHistoryModal" data-res-id="'.$c['id'].'" data-res-name="'.$c['residentName'].'" aria-expanded="false"><span class="fa fa-comment-o"></span></button>'
				);
		}
		
		return $return;

	}

	static public function get_trustees_table ($property_id, $company_id) {

		$return 	 = array();

		// echo 1;

		$residents   = self::$app_controller->get_trustees_residents ($property_id);

		foreach ($residents as $c) {
			$return[] 	= array(
				'unitNumber'  			=> $c['unitNumber'],
				'residentName'  		=> $c['residentName'],
				'residentPhone'  		=> $c['residentPhone'],
				'residentCellphone' 	=> $c['residentCellphone'],
				'residentNotifyEmail' 	=> $c['residentNotifyEmail'],
				'residentType' 			=> $c['residentType'],
				'residentTrustee' 		=> $c['residentTrustee'],
				'residentStatus' 		=> $c['residentStatus']
				);
		}
		
		return $return;

	}


	/*** archive unit ***/
	static public function archive_unit ($ID) {
		$check_unit 	= self::$app_controller->get_resident_byid ($ID);

		if (count($check_unit) === 0) {
			return array('status'  => false, 'text' => 'Resident doesnt exist');
		}

		$archive 		= self::$app_controller->archive_record ($ID);

		if ($archive === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

}
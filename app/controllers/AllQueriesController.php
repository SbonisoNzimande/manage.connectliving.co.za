<?php
/**
 * All Queries Controller
 * 
 * @package 
 * @author  
 */
class AllQueriesController
{
	static public $app_controller;
	static public $property_id;
	static public $property_name;
	static public $prop_array;
	static public $company_id;


	public function __construct() {
		self::$app_controller = new AppController();
		self::$property_id = self::$app_controller->sanitise_string($_REQUEST['prop_id']);
		self::$property_name = self::$app_controller->sanitise_string($_REQUEST['prop_name']);

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
			case 'GetAllBilling':
			
				$billing 	= self::set_up_billing ();
				return json_encode($billing);
			break;
			case 'GetAllMaintanance':
				$maintenance 	= self::set_up_maintenance ();
				return json_encode($maintenance);
			break;

			case 'GetAllSuppliers':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string ($request->parameters['prop_id']);
				$suppliers		= self::$app_controller->get_all_suppliers($company_id, $prop_id);
				return json_encode($suppliers);
			break;


			case 'GetQueryByID':
				$QueryID 	= self::$app_controller->sanitise_string ($request->parameters['QueryID']);
				$query 		= self::$app_controller->get_queries_byid ($QueryID);
				return json_encode($query[0]);
			break;

			case 'GetAllQueries':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$query_type		= self::$app_controller->sanitise_string ($request->parameters['query_type']);
				$queries 		= self::set_up_all_queries ($prop_array, $company_id, $query_type);
				return json_encode($queries);
			case 'GetAllCards':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$graph 			= self::set_up_cards ($prop_array, $company_id);

			return json_encode ($graph);
			break;

			case 'GetPageCards':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$current_page	= self::$app_controller->sanitise_string ($request->parameters['current_page']);
				$company_id 	= $_SESSION['company_id'];
				$graph 			= self::set_up_page_cards ($prop_array, $company_id, $current_page);

			return json_encode ($graph);
			break;

			case 'FilterCards':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$Status 		= self::$app_controller->sanitise_string ($request->parameters['Status']);
				$QueryType 		= self::$app_controller->sanitise_string ($request->parameters['QueryType']);
				$DateRage 		= self::$app_controller->sanitise_string ($request->parameters['DateRage']);
				$graph 			= self::set_up_filter_cards ($prop_array, $company_id, $Status, $QueryType, $DateRage);

			return json_encode ($graph);
			break;
			case 'GetTimeline':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$QueryID 		= self::$app_controller->sanitise_string ($request->parameters['QueryID']);
				$graph 			= self::set_up_timeline ($prop_array, $company_id, $QueryID);

			return json_encode ($graph);
			break;
			case 'DownloadFile':
				$file_name 		= self::$app_controller->sanitise_string ($request->parameters['file_name']);
				$query_id 		= self::$app_controller->sanitise_string ($request->parameters['query_id']);
				$company_id 	= $_SESSION['company_id'];

				$download  		= self::dowload_file ($file_name, $company_id, $query_id);
			return $download;
			exit();
			break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 				= $_SESSION['email'];
					$first_name			= $_SESSION['first_name'];
					$last_name			= $_SESSION['last_name'];
					$modules			= $_SESSION['modules'];

					$this_page 			= 'all_queries';
					$current 			= 'all_queries';

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, $current);
					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'All Queries',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);

						// die(var_dump($pass));
						
						self::$app_controller->get_header 	($pass);
						self::$app_controller->get_view		('Asidemenu', $pass);
						self::$app_controller->get_view		('AllQueries', $pass);
						self::$app_controller->get_footer 	($pass );
						exit();
					}else{
						self::$app_controller->redirect_to ('Login');
					}
				}else{
					self::$app_controller->redirect_to ('Login');
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
			case 'MarkDone':
				$ID 	 = self::$app_controller->sanitise_string($request->parameters['ID']);
				$done 	 = self::mark_done ($ID);
			return json_encode($done);

			case 'MaterialsRequired':
				$ID 	 = self::$app_controller->sanitise_string($request->parameters['ID']);
				$done 	 = self::mark_materials ($ID);
			return json_encode($done);


			case 'MarkInsuranceClaim':
				$ID 	 = self::$app_controller->sanitise_string($request->parameters['ID']);
				$done 	 = self::mark_insurance_claim ($ID);
			return json_encode($done);

			case 'SendSMS': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentSMS']);
				$QueryID 	= self::$app_controller->sanitise_string($request->parameters['QueryID']);
				$user_id 	= $_SESSION['user_id'];


				
				$send    	= self::send_sms ($Message, $QueryID, $user_id);
				return json_encode($send);
			break;

			case 'SendNotification': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['NotificationMessage']);
				$QueryID 	= self::$app_controller->sanitise_string($request->parameters['QueryID']);
				$user_id 	= $_SESSION['user_id'];


				
				$send    	= self::send_notification ($Message, $QueryID, $user_id);
				return json_encode($send);
			break;

			case 'SaveComment': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentSMS']);
				$QueryID 	= self::$app_controller->sanitise_string($request->parameters['QueryID']);
				$user_id 	= $_SESSION['user_id'];
				$company_id = $_SESSION['company_id'];
				$file_data 	= array_map(self::$app_controller->sanitise_string, $_FILES);


				
				$send    	= self::save_comment ($Message, $QueryID, $user_id, $company_id, $file_data);
				return json_encode($send);
			break;

			case 'SaveQuery':
				$QueryType 	= self::$app_controller->sanitise_string($request->parameters['QueryType']);
				$UserID 	= self::$app_controller->sanitise_string($request->parameters['UsersList']);
				$AssineeID 	= self::$app_controller->sanitise_string($request->parameters['AssignTo']);
				$Query 		= self::$app_controller->sanitise_string($request->parameters['Query']);
				$Property	= self::$app_controller->sanitise_string($request->parameters['PropertyList']);
				$Unit 		= self::$app_controller->sanitise_string($request->parameters['Unit']);
				

				$save 		= self::submit_query ($QueryType, $UserID, $AssineeID, $Query, $Property, $Unit);
				return json_encode($save);
				break;

			case 'CreateJob':
				$JobQueryID 		= self::$app_controller->sanitise_string($request->parameters['JobQueryID']);
				$UserID 		= self::$app_controller->sanitise_string($request->parameters['UserID']);
				$JobProperty 		= self::$app_controller->sanitise_string($request->parameters['JobProperty']);
				$JobSupplier 		= self::$app_controller->sanitise_string($request->parameters['JobSupplier']);
				$JobUnitNo 			= self::$app_controller->sanitise_string($request->parameters['JobUnitNo']);
				$JobStatus 			= self::$app_controller->sanitise_string($request->parameters['JobStatus']);
				$JobDescription 	= self::$app_controller->sanitise_string($request->parameters['JobDescription']);
				$JobAssignee 		= self::$app_controller->sanitise_string($request->parameters['JobAssignee']);
				$JobPriority 		= self::$app_controller->sanitise_string($request->parameters['JobPriority']);
				$AuthorisedBy 		= self::$app_controller->sanitise_string($request->parameters['AuthorisedBy']);
				$DateToBeCompleted 	= self::$app_controller->sanitise_string($request->parameters['DateToBeCompleted']);
				$JobImageName 		= self::$app_controller->sanitise_string($request->parameters['JobImageName']);

				$save 				= self::submit_job (
											$JobQueryID,
											$UserID,
											$JobProperty,
											$JobSupplier,
											$JobUnitNo,
											$JobStatus,
											$JobDescription,
											$JobAssignee,
											$JobPriority,
											$AuthorisedBy,
											$DateToBeCompleted,
											$JobImageName
										);
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

			case 'DeleteQuery':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['deleteID']);
				
				$delete 	= self::delete_query ($ID);
				return json_encode($delete);
				break;

			case 'AssignUser': 
				$adminID 	= self::$app_controller->sanitise_string ($request->parameters['adminID']);
				$queryID 	= self::$app_controller->sanitise_string ($request->parameters['queryID']);

				// die($adminID);

				$assign 	= self::$app_controller->assign_queries_user ($queryID, $adminID);
			return json_encode ($assign);
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
							$ret   = array ('images'=> $image);
						}
					}else{
						$ret = array ('images'=> self::$app_controller->get_noimage_base64());
						}

					return json_encode($ret);
					
				break;
		}
	}

	/*** edit query ***/
	static public function delete_query ($ID) {

		$queryinfo 		= self::$app_controller->get_just_queries_byid ($ID);

		if (!is_numeric($ID) OR count($queryinfo) == 0) {
			return array('status'  => false, 'text' => 'Invalid Query ID');
		}

		$delete 		= self::$app_controller->delete_this_query ($ID);

		if ($delete === true) {
			return array('status' => true, 'text' => 'Deleted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $edit);
		}
	}


	static public function dowload_file ($file_name, $company_id, $query_id) {
		$file_url 			= '../companies/' .$company_id. '/queries/' . $query_id .'/'. $file_name;

		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"".$file_name."\""); 
		readfile($file_url);
	}

	/*** send sms ***/
	static public function send_sms ($Message, $QueryID, $user_id) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}

		$query = self::$app_controller->get_queries_byid ($QueryID);

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Invalid Query ID');
		}

		

		$cell_phone = '27' . $query[0]['queryCellphone'];


		// die(var_dump($cell_phone));

		if (!empty($cell_phone)) {
			$save 		= self::$app_controller->save_sms_coms ($Message, $QueryID, $cell_phone, $user_id);
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

	/*** send notification ***/
	static public function send_notification ($Message, $QueryID, $user_id) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}

		$query = self::$app_controller->get_queries_byid ($QueryID);

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Invalid Query ID');
		}

		

		$device_token 		= $query[0]['deviceID'];
		$unit_no 			= $query[0]['unitNo'];
		$query_id 			= $query[0]['queryID'];
		$property_id 		= $query[0]['propertyID'];

		if (empty($device_token)) {
			return array('status'  => false, 'text' => 'Sorry, Device Token Found');
		}

		$user_deatails 		= self::$app_controller->get_app_registration_by_property ($device_token, $property_id);

		if (count($user_deatails) == 0) {
			return array('status'  => false, 'text' => 'Sorry, No Player ID Found');
		}

		$player_id 			= array ($user_deatails[0]['userPlayerID']);
		$property_name 		= $user_deatails[0]['property_name'];

		$message_send 		= $property_name."\n\r".' Feedback on Query: ' . $Message;
		
		$send 				= self::$app_controller->send_push_notification ($message_send, $player_id);

		if ($send) {
			$save 		= self::$app_controller->save_notification_coms ($Message, $query_id, $user_id);
			return array('status' => true, 'text' => 'Message Sent');
		}else{
			return array('status' => true, 'text' => 'Failed to send, ' . $send);
		}

	}

	/*** save comment ***/
	static public function save_comment ($Message, $QueryID, $user_id, $company_id, $file_data) {

		
		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}

		$query = self::$app_controller->get_query_byid ($QueryID);

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Invalid Query ID');
		}

		$name 	= '';
		if (!empty($file_data)) {
			$dir 					= '../companies/' .$company_id. '/queries/' .$QueryID;
			$create 				= self::$app_controller->created_directory ($dir);
			$name 					= self::$app_controller->upload_file ($file_data['UploadFile'], $dir);
			// die(var_dump($name));
		}

		$save = self::$app_controller->save_new_comment ($Message, $QueryID, $user_id, $name);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** save comment ***/
	static public function submit_job (
									$JobQueryID,
									$UserID,
									$JobProperty,
									$JobSupplier,
									$JobUnitNo,
									$JobStatus,
									$JobDescription,
									$JobAssignee,
									$JobPriority,
									$AuthorisedBy,
									$DateToBeCompleted,
									$JobImageName
								) {


		$save = self::$app_controller->convert_query_to_job (
									$JobQueryID,
									$UserID,
									$JobProperty,
									$JobSupplier,
									$JobUnitNo,
									$JobStatus,
									$JobDescription,
									$JobAssignee,
									$JobPriority,
									$AuthorisedBy,
									$DateToBeCompleted,
									$JobImageName
								);
		
		if ($save === true) {
			$delete 	= self::delete_query ($JobQueryID);
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}


	/*** save comment ***/
	static public function mark_done  ($ID) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$ID 			= self::$app_controller->sanitise_string($ID);

		$save 			= self::$app_controller->mark_query_done ($ID);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** save comment ***/
	static public function mark_materials ($ID) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$ID 			= self::$app_controller->sanitise_string($ID);

		$save 			= self::$app_controller->mark_query_materials ($ID);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}


	static public function mark_insurance_claim ($ID) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$ID 			= self::$app_controller->sanitise_string($ID);

		$save 			= self::$app_controller->mark_query_insurance_claim ($ID);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}



	static public function set_up_billing () {
		$return_arr =  array();
		$properties = self::$app_controller->get_all_billing();

		foreach ($properties as $p) {

			$status  	  = ucwords(strtolower($p['status']));
			$comment 	  = $p['comment'];
			$id 	  	  = $p['id'];
			$assignee_name= $p['assignee_name'];
			$admin_user	  = self::$app_controller->get_all_admin_users ();

			$selected 	  = 'Assign To';
			$sel_class 	  = '';
			if (!empty($assignee_name)) {
				$selected = $assignee_name;
				$sel_class= 'amber-200';
			}

			$action 	  = '<div class="btn-toolbar">';
			
			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-success btn-sm " data-title="Edit" data-toggle="modal" data-target="#MarkDone" onclick="SetID('.$id.')" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></button>';

			$action 	.= '</div>';

			$action 	 .= '<div class="btn-group">';
			$action 	 .= '	<button class="btn btn-info btn-sm " data-title="Edit" data-toggle="modal" data-target="#EditModal" onclick="GetBillingEdit('.$id.')" aria-expanded="false"><span class="glyphicon glyphicon-pencil"></span></button>';
			$action 	.= '</div>';
			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<select class="assign_bill_user" id="' .$id. '" onchange="assign_billing_user(this.value, ' .$id. ')">';
			$action 	 .= '<option selected="true">' .$selected. '</option>';
			foreach ($admin_user as $adm) {
				$action  .= '<option value="' .$adm['user_id']. '">' .$adm['full_name']. '</option>';
			}
			$action 	 .= '</select>';
			$action 	.= '</div>';

			$action 	.= '</div>';
			$return_arr[] =  array(
				ucwords(strtolower($p['queryType'])),
				$p['full_name'],
				$p['unitId'],
				$p['query'],
				self::$app_controller->format_date($p['date']),
				$status,
				$comment,
				$action
				);
		}

		return array('data' => $return_arr);
	}


	static public function set_up_maintenance () {
		$return_arr =  array();
		$properties = self::$app_controller->get_all_maintenance();

		foreach ($properties as $p) {

			$status  	  	= ucwords(strtolower($p['status']));
			$comment 	  	= $p['comment'];
			$id 	  	  	= $p['id'];
			$admin_user	  	= self::$app_controller->get_all_admin_managers ();

			$assignee_name	= $p['assignee_name'];
			$selected 	  	= 'Assign To';
			$sel_class 	  	= '';
			if (!empty($assignee_name)) {
				$selected 	= $assignee_name;
				$sel_class	= 'amber-200';
			}

			$action 	  = '<div class="btn-toolbar">';
			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-primary btn-sm " data-title="Edit" data-toggle="modal" data-target="#ImageArea" onclick="getImage('.$id.')" aria-expanded="false" title="View Image"><span class="glyphicon glyphicon-search"></span></button>';
			$action 	 .= '</div>';
			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-success btn-sm " data-title="Edit" data-toggle="modal" data-target="#MarkDone" onclick="SetID('.$id.')" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></button>';

			$action 	 .= '</div>';

			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-info btn-sm " data-title="Edit" data-toggle="modal" data-target="#EditModal" onclick="GetEdit('.$id.')" aria-expanded="false"><span class="glyphicon glyphicon-pencil"></span></button>';

			$action 	.= '</div>';

			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<select class="assign_bill_user" id="' .$id. '" onchange="assign_maintanance_user(this.value, ' .$id. ')">';
			$action 	 .= '<option selected="true">' .$selected. '</option>';
			foreach ($admin_user as $adm) {
				$action  .= '<option value="' .$adm['adminID']. '">' .$adm['full_name']. '</option>';
			}
			$action 	 .= '</select>';
			$action 	.= '</div>';


			$action 	.= '</div>';
			$return_arr[] =  array(
				ucwords(strtolower($p['queryType'])),
				$p['full_name'],
				$p['unitId'],
				$p['query'],
				self::$app_controller->format_date($p['date']),
				$status,
				$comment,
				$action
				
				);


		}

		// $sorted = self::$app_controller->array_orderby ($return_arr, '4', SORT_DESC);

		return array('data' => $return_arr);
	}

	static public function set_up_all_queries ($prop_array, $company_id, $query_type) {
		$return_arr 	=  array();

		// die(var_dump($company_id));//get_all_queries_company ($company_id, $prop_array)

		$properties 	= self::$app_controller->get_all_queries_company ($company_id, $prop_array, $query_type);
		$admin_user	  	= self::$app_controller->get_all_admin_managers ($company_id);

		foreach ($properties as $p) {

			$status  	  	= ucwords(strtolower($p['queryStatus']));
			$comment 	  	= $p['queryComments'];
			$id 	  	  	= $p['queryID'];
			

			$assignee_name	= $p['assignee_name'];
			$selected 	  	= 'Assign To';
			$sel_class 	  	= '';

			if (!empty($assignee_name)) {
				$selected 	= $assignee_name;
				$sel_class	= 'amber-200';
			}

			$action 	  = '<div class="btn-toolbar">';
			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-primary btn-sm " data-title="Edit" data-toggle="modal" data-target="#ImageArea" onclick="getImage('.$id.')" aria-expanded="false" title="View Image"><span class="glyphicon glyphicon-search"></span></button>';
			$action 	 .= '</div>';
			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-success btn-sm " data-title="Edit" data-toggle="modal" data-target="#MarkDone" onclick="SetID('.$id.')" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></button>';

			$action 	 .= '</div>';

			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-info btn-sm " data-title="Edit" data-toggle="modal" data-target="#EditModal" onclick="GetEdit('.$id.')" aria-expanded="false"><span class="glyphicon glyphicon-pencil"></span></button>';

			$action 	.= '</div>';

			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<select class="assign_bill_user" id="' .$id. '" onchange="assign_user(this.value, ' .$id. ')">';
			$action 	 .= '<option selected="true">' .$selected. '</option>';
			foreach ($admin_user as $adm) {
				$action  .= '<option value="' .$adm['adminID']. '">' .$adm['full_name']. '</option>';
			}
			$action 	 .= '</select>';
			$action 	.= '</div>';


			$action 	.= '</div>';
			$return_arr[] =  array(
				'queryType' => ucwords(strtolower($p['queryType'])),
				'queryUsername' => $p['queryUsername'],
				'unitNo' => $p['unitNo'],
				'queryInput' => $p['queryInput'],
				'queryDate' => $p['queryDate'],
				'queryDoneTime' => $p['queryDoneTime'],
				'status' => $status,
				'comment' => $comment,
				'buttons' => $buttons
				);


		}

		// $sorted = self::$app_controller->array_orderby ($return_arr, '4', SORT_DESC);

		return $return_arr;
	}


	static public function set_up_timeline ($prop_array, $company_id, $QueryID) {
		$ret_array 		= array();

		$sms_list 		= self::$app_controller->get_sms_coms ($QueryID);
		$comm_list 		= self::$app_controller->get_comment_coms ($QueryID);
		$note_list 		= self::$app_controller->get_notification_coms ($QueryID);
		$dir 			= '../companies/' .$company_id. '/queries/' .$QueryID;

		// work out return array
		if (!empty($sms_list)) {// notifications

			foreach ($sms_list as $n) {
				$ret_array[] = array(
					'sort' 	  => $n['created'],
					'id' 	  => $n['id'],
					'subject' => $n['full_name'] . ' sent an SMS',
					'message' => $n['sms_text'],
					'type' 	  => 'SMS',
					'file' 	  => '',
					'query_id'=> $QueryID,
					'date'    => self::$app_controller->human_timing ($n['created']) . ' ago'
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
					'sort' 	  => $c['date_created'],
					'id' 	  => $c['id'],
					'subject' => $c['full_name'] . ' commented: ',
					'message' => $c['comment_text'],
					'type' 	  => 'Comment',
					'file' 	  => $file,
					'query_id'=> $QueryID,
					'date'    => self::$app_controller->human_timing ($c['date_created']) . ' ago'
					);
			}

		}

		if (!empty($note_list)) {// notifications

			foreach ($note_list as $c) {

				
				
				$ret_array[] = array(
					'sort' 	  => $c['created'],
					'id' 	  => $c['id'],
					'subject' => $c['full_name'] . ' sent a notification: ',
					'message' => $c['text'],
					'type' 	  => 'Notification',
					'query_id'=> $QueryID,
					'date'    => self::$app_controller->human_timing ($c['created']) . ' ago'
					);
			}

		}

		$sorted = self::$app_controller->array_orderby ($ret_array, 'sort', SORT_DESC);

		return $sorted;

	}


	static public function set_up_cards($prop_array, $company_id) {
		$arry 			= array();
// prop_array
		$queries 		= self::$app_controller->get_all_queries ($prop_array);
		$properties 	= self::$app_controller->get_property_list_permission ($prop_array);
		$admin_user	  	= self::$app_controller->get_all_admin_managers ($company_id);

		$company_id 	= $_SESSION['company_id'];

		$counter 		= 0;
		foreach ($queries as $q) {

			$id 		 = $q['queryID'];
			$queryType 	 = $q['queryType'];
			$userId 	 = $q['queryUsername'];
			$assignee_id = $q['queryAssignee'];
			$unitId 	 = $q['unitNo'];
			$propId 	 = $q['propertyID'];
			$query 		 = $q['queryInput'];
			$image 		 = $q['queryImage'];
			$status 	 = $q['queryStatus'];
			$comment 	 = $q['queryComments'];
			$date 		 = self::$app_controller->format_date($q['queryDate']);
			$full_name   = $q['queryUsername'];

			$prop_det 	 = self::$app_controller->filter_by_value($properties, 'propertyID', $propId);
			foreach ($prop_det as $p) {
				$PropertyName = preg_replace('/\s+/', ' ', $p['propertyName']);
			}

			$img 		= '';
			$dir 		= '../companies/' .$company_id. '/properties/'.$propId.'/queries/';
			if(!empty($image)) {
				if (strpos($image, '.jpg') !== false)  {// if it's image name
				// die($dir.$image);
					$img 	= 'data:image/png;base64,'.base64_encode(file_get_contents($dir.$image));
				}else{
					$img 	= 'data:image/png;base64,'.$image;
				}
			}else{
				$img 	= null;
			}

			$close = false;
			if ($counter % 4 == 0) {

				$open 	= true;
				
			}else{
				
				$open 	= false;
				$close  = true;
			}

			$arry[] = array(
				'query_id'     	 => $id,
				'query_type'     => ucwords(strtolower($queryType)),
				'unit_number'    => $unitId,
				'status'    	 => $status,
				'id'     		 => $id,
				'user_name'      => $full_name,
				'property_name'  => $PropertyName,
				'query' 	     => $query,
				'image' 	     => $img,
				'admin_users' 	 => $admin_user,
				'full_name' 	 => (isset($full_name)) ? $full_name :'Administrator',
				'date' 	     	 => $date,
				'open' 			 => $open,
				'close' 		 => $close
			);


			$counter ++;
			
		}

		return $arry;
	}

	static public function set_up_page_cards ($prop_array, $company_id, $current_page) {
		$arry 			= array();

		

		$all_queries	= self::$app_controller->get_all_queries_company ($company_id, $prop_array);
		$count_pages 	= count($all_queries);
		

		$limit 			= 16;
		/*** iterms in the table ***/
		$total 			= $count_pages;
		/*** number of pages ***/
		$pages 			= ceil($total / $limit);
		/*** calculate the offset for the query ***/
		$offset 		= ($current_page - 1)  * $limit;

		$start 			= $offset + 1;
		$end 			= min(($offset + $limit), $total);
		// die(var_dump($total));

		$queries 		= self::$app_controller->get_page_queries ($prop_array, $limit, $offset);
		$properties 	= self::$app_controller->get_property_list_permission ($prop_array);
		$admin_user	  	= self::$app_controller->get_all_admin_managers ($company_id);

		$company_id 	= $_SESSION['company_id'];

		$counter 		= 0;
		foreach ($queries as $q) {

			$id 		 = $q['queryID'];
			$queryType 	 = $q['queryType'];
			$userId 	 = $q['queryUsername'];
			$assignee_id = $q['queryAssignee'];
			$unitId 	 = $q['unitNo'];
			$propId 	 = $q['propertyID'];
			$query 		 = $q['queryInput'];
			$image 		 = $q['queryImage'];
			$status 	 = $q['queryStatus'];
			$comment 	 = $q['queryComments'];
			$date 		 = self::$app_controller->format_date ($q['queryDate']);
			$full_name   = $q['queryUsername'];

			$prop_det 	 = self::$app_controller->filter_by_value($properties, 'propertyID', $propId);
			foreach ($prop_det as $p) {
				$PropertyName = preg_replace('/\s+/', ' ', $p['propertyName']);
			}

			$img 		= '';
			$dir 		= '../companies/' .$company_id. '/properties/'.$propId.'/queries/';
			if(!empty($image)) {
				if (strpos($image, '.jpg') !== false)  {// if it's image name
				// die($dir.$image);
					$img 	= 'data:image/png;base64,'.base64_encode(file_get_contents($dir.$image));
				}else{
					$img 	= 'data:image/png;base64,'.$image;
				}
			}else{
				$img 	= null;
			}

			$close = false;
			if ($counter % 4 == 0) {

				$open 	= true;
				
			}else{
				
				$open 	= false;
				$close  = true;
			}

			$arry[] = array(
				'query_id'     	 => $id,
				'query_type'     => ucwords(strtolower($queryType)),
				'unit_number'    => $unitId,
				'status'    	 => $status,
				'id'     		 => $id,
				'user_name'      => $full_name,
				'property_name'  => $PropertyName,
				'query' 	     => $query,
				'image' 	     => $img,
				'full_name' 	 => (isset($full_name)) ? $full_name :'Administrator',
				'admin_users' 	 => $admin_user,
				'date' 	     	 => $date,
				'open' 			 => $open,
				'count_pages' 	 => $pages,
				'close' 		 => $close
			);
			$counter ++;
		}

		return $arry;
	}

	static public function set_up_filter_cards ($prop_array, $company_id, $status, $query_type, $daterage) {
		$arry 			= array();

		$datarr 		= explode('-', $daterage);
		$date_from  	= trim($datarr[0]);
		$date_to  		= trim($datarr[1]);

		$queries 		= self::$app_controller->get_filtered_queries ($company_id, $prop_array, $status, $query_type, $date_from, $date_to);
		$properties 	= self::$app_controller->get_property_list_permission ($prop_array);
		$admin_user	  	= self::$app_controller->get_all_admin_managers ($company_id);
	

		$counter 		 = 0;
		$pending_queries = self::$app_controller->filter_by_value ($queries, 'queryStatus', 'pending');
		$done_queries 	 = self::$app_controller->filter_by_value ($queries, 'queryStatus', 'done');

		// die(var_dump($pending_queries));
		foreach ($queries as $q) {

			$id 		 = $q['queryID'];
			$queryType 	 = $q['queryType'];
			$userId 	 = $q['queryUsername'];
			$assignee_id = $q['queryAssignee'];
			$unitId 	 = $q['unitNo'];
			$propId 	 = $q['propertyID'];
			$query 		 = $q['queryInput'];
			$image 		 = $q['queryImage'];
			$status 	 = $q['queryStatus'];
			$comment 	 = $q['queryComments'];
			$date 		 = self::$app_controller->format_date($q['queryDate']);
			$full_name   = $q['queryUsername'];

			$prop_det 	 = self::$app_controller->filter_by_value ($properties, 'propertyID', $propId);
			foreach ($prop_det as $p) {
				$PropertyName = preg_replace('/\s+/', ' ', $p['propertyName']);
			}

			$img 		= '';
			$dir 		= '../companies/' .$company_id. '/properties/'.$propId.'/queries/';
			if(!empty($image)) {
				if (strpos($image, '.jpg') !== false)  {// if it's image name
				// die($dir.$image);
					$img 	= 'data:image/png;base64,'.base64_encode(file_get_contents($dir.$image));
				}else{
					$img 	= 'data:image/png;base64,'.$image;
				}
			}else{
				$img 	= null;
			}

			$close = false;
			if ($counter % 4 == 0) {

				$open 	= true;
				
			}else{
				
				$open 	= false;
				$close  = true;
			}

			$arry[] = array(
				'query_id'     	 => $id,
				'query_type'     => ucwords(strtolower($queryType)),
				'unit_number'    => $unitId,
				'status'    	 => $status,
				'id'     		 => $id,
				'user_name'      => $full_name,
				'property_name'  => $PropertyName,
				'query' 	     => $query,
				'image' 	     => $img,
				'full_name' 	 => (isset($full_name)) ? $full_name :'Administrator',
				'admin_users' 	 => $admin_user,
				'date' 	     	 => $date,
				'open' 			 => $open,
				'close' 		 => $close
			);


			$counter ++;
			
		}

		

		// $arry[]['pending_queries'] = $pending_queries;

		// array_push($arry, array('pending_queries' => $pending_queries));

		// $return_obj 	 = array_merge($arry, array('pending_queries' => $pending_queries, 'done_queries' => $done_queries));
		// array_push($arry, array('pending_queries' => $pending_queries, 'done_queries' => $done_queries));

		return array('filter_data' => $arry, 'pending_queries' => $pending_queries, 'done_queries' => $done_queries);
	}


}
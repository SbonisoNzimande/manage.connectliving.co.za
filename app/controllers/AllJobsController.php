<?php
/**
 * All Jobs Controller
 * 
 * @package 
 * @author  
 */
class AllJobsController
{
	static public $app_controller;
	static public $property_id;
	static public $property_name;

	public function __construct() {
		self::$app_controller = new AppController();
		self::$property_id = self::$app_controller->sanitise_string($_REQUEST['prop_id']);
		self::$property_name = self::$app_controller->sanitise_string($_REQUEST['prop_name']);
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
			case 'GetAllQueries':
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$prop_name 		= self::$app_controller->sanitise_string($request->parameters['prop_name']);
				$company_id 	= $_SESSION['company_id'];
				$queries 		= self::set_up_queries ($prop_id, $prop_name, $company_id);
				return json_encode($queries);
				break;
			case 'GetAllJobs':
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$prop_name 		= self::$app_controller->sanitise_string($request->parameters['prop_name']);
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$queries 		= self::set_up_jobs($prop_array, $company_id);
				return json_encode($queries);
				break;
			case 'GetJobStatusEnums':
				$enums 		= self::$app_controller->get_enum_values ('query_jobs', 'status');

			return json_encode ($enums);
			break;

			case 'GetAllCards':
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$graph 			= self::set_up_cards ($prop_array, $company_id);

			return json_encode ($graph);
			break;
			case 'GetFilterCards':
				$JobStatus 		= self::$app_controller->sanitise_string   ($request->parameters['JobStatus']);
				$prop_id 		= self::$app_controller->sanitise_string   ($request->parameters['prop_id']);
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$graph 			= self::filter_cards ($prop_id, $company_id, $JobStatus);

			return json_encode ($graph);
			break;

			case 'GetJobByID':
				$job_id 		= self::$app_controller->sanitise_string($request->parameters['JobID']);
				$graph 			= self::$app_controller->get_job_by_id ($job_id);

			return json_encode ($graph[0]);
			break;

			case 'GetAllUsers':
				
				$get_users 	= self::$app_controller->get_all_users ();
				return json_encode($get_users);
				break;

			case 'GetAllAdminUsers':
				
				$get_users 	= self::$app_controller->get_all_admin_users ();
				return json_encode($get_users);
				break;

			case 'GetQueriesInfo':

				$id 		= self::$app_controller->sanitise_string  ($request->parameters['id']);
				$get 		= self::$app_controller->get_queries_byid ($id);
				return json_encode($get[0]);
				break;

			case 'GetSupplierEmail':

				$job_id 							= self::$app_controller->sanitise_string  ($request->parameters['job_id']);
				$supplier_id 						= self::$app_controller->sanitise_string  ($request->parameters['supplier_id']);
				$property_name 						= self::$app_controller->sanitise_string  ($request->parameters['property_name']);
				$supplier_name 						= self::$app_controller->sanitise_string  ($request->parameters['supplier_name']);
				$supplier_email 					= self::$app_controller->sanitise_string  ($request->parameters['supplier_email']);
				$supplier_unit_number 				= self::$app_controller->sanitise_string  ($request->parameters['supplier_unit_number']);
				$supplier_priority 					= self::$app_controller->sanitise_string  ($request->parameters['supplier_priority']);
				$supplier_description 				= self::$app_controller->sanitise_string  ($request->parameters['supplier_description']);
				$supplier_authorised_by 			= self::$app_controller->sanitise_string  ($request->parameters['supplier_authorised_by']);
				$supplier_date_tobe_completed 		= self::$app_controller->sanitise_string  ($request->parameters['supplier_date_tobe_completed']);
				$supplier_job_status 				= self::$app_controller->sanitise_string  ($request->parameters['supplier_job_status']);


				$table  		= self::$app_controller->get_supplier_email (
										$job_id, 
										$supplier_name, 
										$property_name, 
										$supplier_email,
										$supplier_unit_number,
										$supplier_priority,
										$supplier_description,
										$supplier_authorised_by,
										$supplier_date_tobe_completed,
										$supplier_job_status
									);
				return json_encode(array('html' => $table));
				break;
			case 'GetTimeline':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$JobID 			= self::$app_controller->sanitise_string ($request->parameters['JobID']);
				$graph 			= self::set_up_timeline ($prop_array, $company_id, $JobID);

			return json_encode ($graph);
			break;

			case 'DownloadFile':
				$file_name 		= self::$app_controller->sanitise_string ($request->parameters['file_name']);
				$JobID 		= self::$app_controller->sanitise_string ($request->parameters['JobID']);
				$company_id 	= $_SESSION['company_id'];

				$download  		= self::dowload_file ($file_name, $company_id, $JobID);
			return $download;
			exit();
			break;

			case 'GetPrintJob':
				$job_id    			= self::$app_controller->sanitise_string ($request->parameters['job_id']);
				$prop_id    		= self::$app_controller->sanitise_string ($request->parameters['prop_id']);

			    $html  				= self::set_up_print_job ($job_id, $prop_id, $company_id);

			    return json_encode(array('html' => $html));
			break;
			
			default:
				if (self::$app_controller->check_if_logged ($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];

					$this_page 		= 'all_jobs';
					$current 		= 'all_jobs';

					$aside_menu 	= self::$app_controller->get_aside_menu ($modules, $current);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'All Jobs ',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);
						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view ('Asidemenu', $pass);
						self::$app_controller->get_view ('AllJobs', $pass);
						self::$app_controller->get_footer ($pass);
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
			
			case 'DeleteJob':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['deleteID']);
				
				$delete 	= self::delete_job ($ID);
				return json_encode($delete);
				break;
			case 'MarkDone':
				$ID 	 = self::$app_controller->sanitise_string($request->parameters['ID']);
				$done 	 = self::mark_done ($ID);
			return json_encode($done);

			case 'UpdateJobStatus':
				$JobID 	 		= self::$app_controller->sanitise_string ($request->parameters['jobID']);
				$MarkJobAs 		= self::$app_controller->sanitise_string ($request->parameters['MarkJobAs']);
				$udpate 	 	= self::update_job_status ($JobID, $MarkJobAs);
			return json_encode($udpate);

			case 'SendSMS': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentSMS']);
				$JobID 		= self::$app_controller->sanitise_string($request->parameters['JobID']);
				$user_id 	= $_SESSION['user_id'];


				
				$send    	= self::send_sms ($Message, $JobID, $user_id);
				return json_encode($send);
			break;

			case 'SendNotification': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['NotificationMessage']);
				$JobID 		= self::$app_controller->sanitise_string($request->parameters['JobID']);
				$user_id 	= $_SESSION['user_id'];


				
				$send    	= self::send_notification ($Message, $JobID, $user_id);
				return json_encode($send);
			break;

			case 'SendSupplierSMS': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentSMS']);
				$JobID 		= self::$app_controller->sanitise_string($request->parameters['JobID']);
				$user_id 	= $_SESSION['user_id'];
				
				$send    	= self::send_supplier_sms ($Message, $JobID, $user_id);
				return json_encode($send);
			break;

			case 'SaveComment': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentSMS']);
				$JobID 		= self::$app_controller->sanitise_string($request->parameters['JobID']);
				$user_id 	= $_SESSION['user_id'];
				$company_id = $_SESSION['company_id'];
				$file_data 	= array_map(self::$app_controller->sanitise_string, $_FILES);

				$send    	= self::save_comment ($Message, $JobID, $user_id, $company_id, $file_data);
				return json_encode($send);
			break;

			case 'AssignUser': 
				$ID 		= self::$app_controller->sanitise_string ($request->parameters['id']);
				$AssineeID 	= self::$app_controller->sanitise_string ($request->parameters['adminID']);

				$assign 	= self::$app_controller->assign_job_user ($ID, $AssineeID);
				return json_encode ($assign);
				break;

			case 'GetImage':
					$id 	= $request->parameters['id'];
					$image 	= self::$app_controller->get_image_by_id ($id);
					$ret 	= array();


					if(!empty($image[0]['queryImage'])) {
						foreach ($image as $k) {

							$img  = (string)$k['queryImage'];

							$image = str_replace('[', '', $img);
							$image = str_replace(']', '', $image);
							$image = str_replace('"', '', $image);
							$ret   = array ('images'=> 'data:image/gif;base64,' . $image);
						}
					}else{
						$ret = array ('images'=> self::$app_controller->get_noimage_base64());
						}

					return json_encode($ret);
					
				break;

				case 'SendSupplierEmail':
					$job_id 			= self::$app_controller->sanitise_string  ($request->parameters['job_id']);
					$supplier_id 		= self::$app_controller->sanitise_string  ($request->parameters['supplier_id']);
					$property_name 		= self::$app_controller->sanitise_string  ($request->parameters['property_name']);
					$supplier_name 		= self::$app_controller->sanitise_string  ($request->parameters['supplier_name']);
					$supplier_email 		= self::$app_controller->sanitise_string  ($request->parameters['supplier_email']);
					$supplier_unit_number 	= self::$app_controller->sanitise_string  ($request->parameters['supplier_unit_number']);
					$supplier_priority 		= self::$app_controller->sanitise_string  ($request->parameters['supplier_priority']);
					$supplier_description 	= self::$app_controller->sanitise_string  ($request->parameters['supplier_description']);
					$supplier_authorised_by = self::$app_controller->sanitise_string  ($request->parameters['supplier_authorised_by']);
					$supplier_date_tobe_completed 		= self::$app_controller->sanitise_string  ($request->parameters['supplier_date_tobe_completed']);
					$supplier_job_status 		= self::$app_controller->sanitise_string  ($request->parameters['supplier_job_status']);


					$user 		= self::send_supplier_email (
										$job_id, 
										$supplier_name, 
										$property_name, 
										$supplier_email,
										$supplier_unit_number,
										$supplier_priority,
										$supplier_description,
										$supplier_authorised_by,
										$supplier_date_tobe_completed,
										$supplier_job_status
									);
				return json_encode($user);

				case 'UpdateJob':
				$JobID 				= self::$app_controller->sanitise_string($request->parameters['JobID']);
				$JobProperty 		= self::$app_controller->sanitise_string($request->parameters['JobProperty']);
				$JobSupplier 		= self::$app_controller->sanitise_string($request->parameters['JobSupplier']);
				$JobUnitNo 			= self::$app_controller->sanitise_string($request->parameters['JobUnitNo']);
				$JobStatus 			= self::$app_controller->sanitise_string($request->parameters['JobStatus']);
				$JobDescription 	= self::$app_controller->sanitise_string($request->parameters['JobDescription']);
				$JobAssignee 		= self::$app_controller->sanitise_string($request->parameters['JobAssignee']);
				$JobPriority 		= self::$app_controller->sanitise_string($request->parameters['JobPriority']);
				$AuthorisedBy 		= self::$app_controller->sanitise_string($request->parameters['AuthorisedBy']);
				$DateToBeCompleted 	= self::$app_controller->sanitise_string($request->parameters['DateToBeCompleted']);

				$save 				= self::edit_job (
											$JobID,
											$JobProperty,
											$JobSupplier,
											$JobUnitNo,
											$JobStatus,
											$JobDescription,
											$JobAssignee,
											$JobPriority,
											$AuthorisedBy,
											$DateToBeCompleted
										);
				return json_encode($save);
				break;
		}
	}


	/*** send notification ***/
	static public function send_notification ($Message, $JobID, $user_id) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}

		$query = self::$app_controller->get_get_appreg_byid ($JobID);

		// die(var_dump($query));

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Invalid Job ID');
		}

		

		// $device_token 		= $query[0]['deviceID'];
		// $unit_no 			= $query[0]['unitNo'];
		// $query_id 			= $query[0]['queryID'];
		// $property_id 		= $query[0]['propertyID'];

		// if (empty($device_token)) {
		// 	return array('status'  => false, 'text' => 'Sorry, Device Token Found');
		// }

		// $user_deatails 		= self::$app_controller->get_app_registration_by_property ($device_token, $property_id);

		// if (count($user_deatails) == 0) {
		// 	return array('status'  => false, 'text' => 'Sorry, No Player ID Found');
		// }

		$player_id 			= array ($query[0]['userPlayerID']);
		// die(var_dump($player_id));
		$property_name 		= $query[0]['property_name'];

		$message_send 		= $property_name."\n\r".' Feedback on Job: ' . $Message;
		
		$send 				= self::$app_controller->send_push_notification ($message_send, $player_id);

		if ($send) {
			$save 		= self::$app_controller->save_notification_coms ($Message, $query_id, $user_id);
			return array('status' => true, 'text' => 'Message Sent');
		}else{
			return array('status' => true, 'text' => 'Failed to send, ' . $send);
		}

	}


	static public function delete_job ($ID) {

		$queryinfo 		= self::$app_controller->get_job_byid ($ID);

		if (!is_numeric($ID) OR count($queryinfo) == 0) {
			return array('status'  => false, 'text' => 'Invalid Job ID');
		}

		$delete 		= self::$app_controller->delete_this_job ($ID);

		if ($delete === true) {
			return array('status' => true, 'text' => 'Deleted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $edit);
		}
	}

	static public function set_up_print_job ($job_id, $prop_id, $company_id) {

		$return;
		$jobs = self::$app_controller->get_job_byid ($job_id);

		foreach ($jobs as $j) {
			$job_id 				= $j['job_id'];
			$property_id			= $j['property_id'];
			$property_name 			= $j['propertyName'];
			$unit_number 			= $j['unit_number'];
			$supplier_name 			= $j['company_name'];
			$priority 				= $j['priority'];
			$job_status 			= $j['status'];
			$description 			= $j['description'];
			$image 					= $j['job_image'];
			$authorised_by 			= $j['authorised_by'];
			$date_tobe_completed 	= $j['date_tobe_completed'];
			$created_date 			= $j['created_date'];

			$dir 					= '../companies/' .$company_id. '/properties/'.$property_id.'/queries/';



			if (strpos($image, '.jpg') !== false)  {// if it's image name
			// die($dir.$image);
				$img 	= 'data:image/png;base64,'.base64_encode(file_get_contents($dir.$image));
			}else{
				// die(var_dump($image));
				$img 	= 'data:image/png;base64,'.$image;
			}
		}

		$email 			= self::$app_controller->get_print_job_template (
										$job_id, 
										$property_name, 
										$unit_number, 
										$supplier_name,
										$priority,
										$job_status,
										$description,
										$img,
										$authorised_by,
										$date_tobe_completed,
										$created_date
									);

						
		return $email;
	}


	static public function dowload_file ($file_name, $company_id, $JobID) {
		$file_url 			= '../companies/' .$company_id. '/jobs/' . $JobID .'/'. $file_name;

		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"".$file_name."\""); 
		readfile($file_url);
	}


	/*** save permissions ***/
	static public function set_up_jobs ($prop_array, $company_id) {
		$return_arr = array();
		$properties = self::$app_controller->get_company_job_list ($prop_array, $company_id);

		//btn btn-success dropdown-toggle

		foreach ($properties as $p) {

			$query  	  	= $p['queryInput'];
			$id  	  		= $p['job_id'];			

			$supplier 	  	= $p['company_name'];
			$unit_number 	= $p['unit_number'];
			$status 		= $p['status'];
			$description 	= $p['description'];
			$priority 		= $p['priority'];
			$job_done_date 	= $p['job_done_date'];
			$date_tobe_completed 		= $p['date_tobe_completed'];
			
			$admin_user	  	= self::$app_controller->get_all_admin_managers ($company_id);

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
			$action 	 .= '<button class="btn btn-info btn-sm " data-title="Edit" data-toggle="modal" data-target="#EditJobModal" data-query-id="'.$id.'" aria-expanded="false"><span class="glyphicon glyphicon-pencil"></span></button>';

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
				'job_id' => $id,
				'query' => $query,
				'supplier' => $supplier,
				'unit_number' => $unit_number,
				'job_done_date' => $job_done_date,
				'status' => $status,
				'description' => $description,
				'priority' => $priority,
				'date_tobe_completed' => $date_tobe_completed,				
				'buttons'=>$action
			);
		}

		return array('data' => $return_arr);
	}


	/*** send sms ***/
	static public function send_sms ($Message, $JobID, $user_id) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}

		$query = self::$app_controller->get_query_details_by_jobid ($JobID);

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Can not Send an SMS on this Job');
		}

		

		$cell_phone = '27' . $query[0]['phone_number'];
		// die(var_dump($cell_phone));


		if (!empty($cell_phone)) {
			$save 		= self::$app_controller->save_job_sms_coms ($Message, $JobID, $cell_phone, $user_id);
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




	static public function set_up_timeline ($prop_array, $company_id, $JobID) {
		$ret_array 		= array();

		$sms_list 		= self::$app_controller->get_job_sms_coms ($JobID);
		$comm_list 		= self::$app_controller->get_job_comment_coms ($JobID);
		$dir 			= '../companies/' .$company_id. '/jobs/' .$JobID;

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
					'date'    => self::$app_controller->human_timing($n['created']) . ' ago'
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
					'date'    => self::$app_controller->human_timing($c['date_created']) . ' ago'
					);
			}

		}

		$sorted = self::$app_controller->array_orderby ($ret_array, 'sort', SORT_DESC);

		return $sorted;

	}

	/*** send sms ***/
	static public function send_supplier_sms ($Message, $JobID, $user_id) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}

		$query = self::$app_controller->get_job_byid ($JobID);

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Invalid Job ID');
		}

		

		$cell_phone = '27' . $query[0]['phone_number'];




		if (!empty($cell_phone)) {
			$save 		= self::$app_controller->save_job_sms_coms ($Message, $JobID, $cell_phone, $user_id);
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
	static public function mark_done  ($Comment, $ID) {

		if (!self::$app_controller->validate_variables ($Comment, 3)) {
			return array('status'  => false, 'text' => 'Invalid Comment');
		}

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$Comment 		= self::$app_controller->sanitise_string($Comment);
		$ID 			= self::$app_controller->sanitise_string($ID);

		$save 			= self::$app_controller->save_queries_comment ($Comment, $ID);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	static public function edit_job (
									$JobID,
									$JobProperty,
									$JobSupplier,
									$JobUnitNo,
									$JobStatus,
									$JobDescription,
									$JobAssignee,
									$JobPriority,
									$AuthorisedBy,
									$DateToBeCompleted
								) {


		$save = self::$app_controller->update_job (
									$JobID,
									$JobProperty,
									$JobSupplier,
									$JobUnitNo,
									$JobStatus,
									$JobDescription,
									$JobAssignee,
									$JobPriority,
									$AuthorisedBy,
									$DateToBeCompleted
								);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	static public function update_job_status ($JobID, $MarkJobAs) {

		$job = self::$app_controller->get_job_byid ($JobID);
		// var_dump($job);
		// die();

		if (count($job) === 0) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		if (empty($MarkJobAs)) {
			return array('status'  => false, 'text' => 'Please select job status');
		}


		$save = self::$app_controller->save_job_status (
									$JobID,
									$MarkJobAs
								);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Job Status Updated', 'prop_id' => $job[0]['propertyID']);
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	static public function send_supplier_email (
								$job_id, 
								$supplier_name, 
								$property_name, 
								$supplier_email,
								$supplier_unit_number,
								$supplier_priority,
								$supplier_description,
								$supplier_authorised_by,
								$supplier_date_tobe_completed,
								$supplier_job_status
							) {

		$html  = self::$app_controller->get_supplier_email (
								$job_id, 
								$supplier_name, 
								$property_name, 
								$supplier_email,
								$supplier_unit_number,
								$supplier_priority,
								$supplier_description,
								$supplier_authorised_by,
								$supplier_date_tobe_completed,
								$supplier_job_status
							);

		$send  = self::$app_controller->send_email ($html, 'connectLiving Job Details', $supplier_email, $supplier_name);

		if ($send == true) {
			return array('status' => true, 'text'  => 'Email sent');
		}else{
			return array('status' => false, 'text' => 'Failed to send, ' . $send);
		}

	}


	/*** save comment ***/
	static public function save_comment ($Message, $JobID, $user_id, $company_id, $file_data) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}

		$query = self::$app_controller->get_job_byid ($JobID);

		if (count($query) == 0) {
			return array('status'  => false, 'text' => 'Invalid Job ID');
		}

		$name 	= '';
		if (!empty($file_data)) {
			$dir 					= '../companies/' .$company_id. '/jobs/' .$JobID;
			$create 				= self::$app_controller->created_directory ($dir);
			$name 					= self::$app_controller->upload_file ($file_data['UploadFile'], $dir);
		}

		$save = self::$app_controller->insert_job_comment ($Message, $JobID, $user_id, $name);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** save query ***/
	static public function submit_query ($QueryType, $AssineeID, $Query, $Property, $Unit, $image_data) {

		if (!self::$app_controller->validate_variables ($QueryType, 3)) {
			return array('status'  => false, 'text' => 'Invalid Query Type');
		}

		if (!self::$app_controller->validate_variables ($Property, 3)) {
			return array('status'  => false, 'text' => 'Invalid Property');
		}

		if (!self::$app_controller->validate_variables ($Unit, 3)) {
			return array('status'  => false, 'text' => 'Invalid Unit');
		}


		if (!is_numeric($AssineeID)) {
			return array('status'  => false, 'text' => 'Invalid Assinee ID');
		}

		if (!self::$app_controller->validate_variables ($Query, 3)) {
			return array('status'  => false, 'text' => 'Invalid Query');
		}

		$bin_string = file_get_contents($image_data["file"]["tmp_name"]);
		$hex_image  = base64_encode($bin_string);

		$save 		= self::$app_controller->save_query ($QueryType, $AssineeID, $Query, $Property, $Unit, $hex_image);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** edit query ***/
	static public function edit_query ($ID, $QueryType, $UserID, $Query, $Property, $Unit) {

		$queryinfo 		= self::$app_controller->get_queries_byid ($ID);

		// if (!is_numeric($ID) OR count($queryinfo) == 0) {
		// 	return array('status'  => false, 'text' => 'Invalid Query ID');
		// }

		// if (!self::$app_controller->validate_variables ($QueryType, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Query Type');
		// }

		// if (!self::$app_controller->validate_variables ($Property, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Property');
		// }

		// if (!self::$app_controller->validate_variables ($Unit, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Unit');
		// }

		// // if (!is_numeric($UserID)) {
		// // 	return array('status'  => false, 'text' => 'Invalid User ID');
		// // }

		
		// if (!self::$app_controller->validate_variables ($Query, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Query');
		// }


		$edit 		= self::$app_controller->update_query ($ID, $QueryType, $UserID, $Query, $Property, $Unit);

		if ($edit === true) {
			return array('status' => true, 'text' => 'Edited');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $edit);
		}
	}


	static public function filter_cards ($prop_id, $company_id, $JobStatus) {
		$arry 			= array();
		// die(var_dump($JobStatus));
		$queries 		= self::$app_controller->get_all_job_list_per_status ($prop_id, $JobStatus);
		$admin_user	  	= self::$app_controller->get_all_admin_managers ($company_id);

		// die(var_dump($queries));

		$counter 		= 0;
		foreach ($queries as $q) {

			$job_id		 			= $q['job_id'];
			$supplier_id 			= $q['supplier_id'];
			$supplier_email 		= $q['email'];
			$supplier_phone_number 	= $q['phone_number'];
			$query_id 	 			= $q['query_id'];
			$supplier 	 			= $q['company_name'];
			$unit_number 			= $q['unit_number'];
			$property_name 			= $q['propertyName'];
			$priority 	 			= $q['priority'];
			$status 	 			= $q['status'];
			$job_status 	 		= $q['job_status'];
			$queryType 	 			= $q['queryType'];
			$userId 	 			= $q['queryUsername'];
			$assignee_id 			= $q['queryAssignee'];
			$unitId 	 			= $q['unitNo'];
			$propId 	 			= $q['propertyID'];
			$query 		 			= $q['queryInput'];
			$image 		 			= $q['job_image'];
			$status 	 			= $q['queryStatus'];
			$comment 	 			= $q['queryComments'];
			$description 			= $q['description'];
			$authorised_by 			= $q['authorised_by'];
			$date_tobe_completed 	= $q['date_tobe_completed'];
			$job_assignee 			= $q['job_assignee'];
			$date 		 			= self::$app_controller->format_date($q['job_date']);
			$full_name   			= $q['queryUsername'];


			$img 		= '';
			$dir 		= '../companies/' .$company_id. '/properties/'.$prop_id.'/queries/';
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
				'job_id'     	 => $job_id,
				'supplier_id'    => $supplier_id,
				'supplier_email' => $supplier_email,
				'supplier_phone_number' => $supplier_phone_number,
				'query_id'     	 => $query_id,
				'supplier'     	 => $supplier,
				'unit_number'    => $unit_number,
				'priority'     	 => $priority,
				'description'    => $description,
				'authorised_by'  => $authorised_by,
				'date_tobe_completed'  => $date_tobe_completed,
				'job_assignee'   => $job_assignee,
				'status'     	 => $status,
				'job_status'     => $job_status,
				'query_type'     => ucwords(strtolower($queryType)),
				// 'unit_number'    => $unitId,
				'status'    	 => $status,
				'id'     		 => $job_id,
				'user_name'      => $full_name,
				'property_name'  => $property_name,
				'query' 	     => $query,
				'image' 	     => $img,
				'admin_users' 	 => $admin_user,
				'date' 	     	 => $date,
				'open' 			 => $open,
				'close' 		 => $close
			);


			$counter ++;
			
		}

		return $arry;
	}

	static public function set_up_cards($prop_array, $company_id) {
		$arry 			= array();

		$queries 		= self::$app_controller->get_company_job_list ($prop_array, $company_id);
		$admin_user	  	= self::$app_controller->get_all_admin_managers ($company_id);

		// die(var_dump($queries));

		$counter 		= 0;
		foreach ($queries as $q) {

			$job_id		 			= $q['job_id'];
			$property_id		 		= $q['property_id'];
			$supplier_id 			= $q['supplier_id'];
			$supplier_email 		= $q['email'];
			$supplier_phone_number 	= $q['phone_number'];
			$query_id 	 			= $q['query_id'];
			$supplier 	 			= $q['company_name'];
			$unit_number 			= $q['unit_number'];
			$property_name 			= $q['propertyName'];
			$priority 	 			= $q['priority'];
			$status 	 			= $q['status'];
			$job_status 	 		= $q['job_status'];
			$queryType 	 			= $q['queryType'];
			$userId 	 			= $q['queryUsername'];
			$assignee_id 			= $q['queryAssignee'];
			$unitId 	 			= $q['unitNo'];
			$propId 	 			= $q['propertyID'];
			$query 		 			= $q['queryInput'];
			$image 		 			= $q['job_image'];
			$status 	 			= $q['queryStatus'];
			$comment 	 			= $q['queryComments'];
			$description 			= $q['description'];
			$authorised_by 			= $q['authorised_by'];
			$date_tobe_completed 	= $q['date_tobe_completed'];
			$job_assignee 			= $q['job_assignee'];
			$date 		 			= self::$app_controller->format_date($q['job_date']);
			$full_name   			= $q['queryUsername'];


			$img 		= null;
			$dir 		= '../companies/' .$company_id. '/properties/'.$property_id.'/queries/';

			
			if(!empty($image)) {
				// if($job_id = 284){
				// 	die(var_dump($q));
				// }
				if (strpos($image, '.jpg') !== false)  {// if it's image name
					$img 	= 'data:image/png;base64,'.base64_encode(file_get_contents($dir.$image));
				}else{
					// die($image);
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
				'job_id'     	 => $job_id,
				'supplier_id'    => $supplier_id,
				'supplier_email' => $supplier_email,
				'supplier_phone_number' => $supplier_phone_number,
				'query_id'     	 => $query_id,
				'supplier'     	 => $supplier,
				'unit_number'    => $unit_number,
				'priority'     	 => $priority,
				'description'    => $description,
				'authorised_by'  => $authorised_by,
				'date_tobe_completed'  => $date_tobe_completed,
				'job_assignee'   => $job_assignee,
				'status'     	 => $status,
				'job_status'     => $job_status,
				'query_type'     => ucwords(strtolower($queryType)),
				// 'unit_number'    => $unitId,
				'status'    	 => $status,
				'id'     		 => $job_id,
				'user_name'      => $full_name,
				'property_name'  => $property_name,
				'query' 	     => $query,
				'image' 	     => $img,
				'admin_users' 	 => $admin_user,
				'date' 	     	 => $date,

				'open' 			 => $open,
				'close' 		 => $close
			);


			$counter ++;
			
		}

		return $arry;
	}

}
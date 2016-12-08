<?php
/**
 * Assets Controller
 * 
 * @package 
 * @author  
 */
class AppApiController
{
	static public $app_controller;
	static public $property_id;
	static public $property_name;
	static public $prop_array;
	static public $company_id;


	public function __construct() {
		self::$app_controller 	= new AppController();
		
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
			case 'GetCountries':
				$data 			= self::set_up_areas ('country');

				return json_encode ($data);
			break;

			case 'GetProvincies':
				$country_name 	= self::$app_controller->sanitise_string($request->parameters['country_name']);
				$table 			=  self::set_up_areas ('province', $country_name);

				return json_encode($table);
			break;

			case 'GetCities':
				$province_name 	= self::$app_controller->sanitise_string($request->parameters['province_name']);
				$table 			=  self::set_up_areas ('city', $province_name);

				return json_encode($table);
			break;

			case 'GetProperties':
				$city_name 		= self::$app_controller->sanitise_string($request->parameters['city_name']);
				$table 			= self::$app_controller->get_properties_by_city ($city_name);

				return json_encode($table);
			break;

			case 'GetRulesDocument':
				$property_id 		= self::$app_controller->sanitise_string($request->parameters['property_id']);
				$table 			= self::$app_controller->get_properties_by_city ($property_id);

				return json_encode($table);
			break;

			case 'GetDashbordData':
				$company_id 		= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$property_id 		= self::$app_controller->sanitise_string ($request->parameters['property_id']);

				$return 			= self::set_up_dashbord_data ($company_id, $property_id);
				

				return json_encode($return);
			break;

			case 'GetImages':
				$company_id 		= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$prop_id 			= self::$app_controller->sanitise_string ($request->parameters['prop_id']);

				$responces 			= self::set_get_image ($company_id, $prop_id);

				return json_encode($responces);
			break;

			case 'GetAllForms':
				$company_id 		= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$prop_id 			= self::$app_controller->sanitise_string ($request->parameters['prop_id']);

				$forms   			= self::$app_controller->get_all_forms ($prop_id);
				$forms_obj   		= self::set_up_form ($forms);

				return json_encode($forms_obj);
			break;

			case 'GetAllProperties':
				$device_token 		= self::$app_controller->sanitise_string ($request->parameters['device_token']);

				$properties   		= self::get_devices_properties ($device_token);

				return json_encode ($properties);
			break;

			case 'GetAllQuery':
				$company_id 		= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$property_id 		= self::$app_controller->sanitise_string ($request->parameters['property_id']);
				$device_id 			= self::$app_controller->sanitise_string ($request->parameters['device_id']);

				$query   			= self::set_up_all_query ($company_id, $property_id, $device_id);

				return json_encode ($query);
			break;

			case 'GetQueryTypes':
				$category 		= self::$app_controller->sanitise_string ($request->parameters['category']);
				$q_types   		= self::$app_controller->get_query_types ($category);

				return json_encode ($q_types);
			break;

			case 'GetSupplierList':
				$prop_id 		= self::$app_controller->sanitise_string ($request->parameters['prop_id']);
				$company_id 	= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$suppliers   	= self::set_supplier_list ($prop_id, $company_id);

				return json_encode ($suppliers);
			break;

			case 'GetJobList':
				$prop_id 		= self::$app_controller->sanitise_string ($request->parameters['prop_id']);
				$company_id 	= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$suppliers   	= self::set_job_list ($prop_id, $company_id);

				return json_encode ($suppliers);
			break;

			case 'GetJobByID':
				$prop_id 		= self::$app_controller->sanitise_string ($request->parameters['property_id']);
				$company_id 	= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$id 			= self::$app_controller->sanitise_string ($request->parameters['id']);
				$jobs   		= self::setup_job_list_byid ($id, $prop_id, $company_id);

				return json_encode ($jobs[0]);
			break;

			case 'GetAllDocuments':
				$prop_id 		= self::$app_controller->sanitise_string ($request->parameters['property_id']);
				$company_id 	= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$docs   		= self::set_up_documents ($prop_id, $company_id);

				return json_encode ($docs);
			break;
			case 'GetRulesDocuments':
				$prop_id 		= self::$app_controller->sanitise_string ($request->parameters['property_id']);
				$company_id 	= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$docs   		= self::set_up_documents_rules ($prop_id, $company_id);

				return json_encode ($docs);
			break;
			case 'GetAllEmergencyContact':
				$prop_id 		= self::$app_controller->sanitise_string ($request->parameters['property_id']);
				$company_id 	= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$conts   		= self::$app_controller->get_all_emergency_contacts ($prop_id);;

				return json_encode ($conts);
			break;

			case 'GetQoutationPDF':
				$url 		= self::$app_controller->sanitise_string ($request->parameters['url']);

				$send   		= self::setup_qoutation ($url);

				return $send;
			
			break;
			case 'SendNotificationToUser':
				$user_id 		= self::$app_controller->sanitise_string ($request->parameters['user_id']);
				$message 		= self::$app_controller->sanitise_string ($request->parameters['message']);
				$send   		= self::send_notification ($user_id, $message);

				return json_encode ($send);
			break;
			case 'SendNotificationToSupplier':
				//die(('supplier_id' + $supplier_id));
				$supplier_id 	= self::$app_controller->sanitise_string ($request->parameters['supplier_id']);
				$message 		= self::$app_controller->sanitise_string ($request->parameters['message']);
				$send   		= self::send_notification_supplier ($supplier_id, $message);
				return json_encode ($send);

			break;

			case 'GetQueryNotifications':
				//die(('supplier_id' + $supplier_id));
				$query_id 		= self::$app_controller->sanitise_string ($request->parameters['query_id']);
				$send   		= self::get_notifications ($query_id);
				return json_encode ($send);

			break;

			case 'ValidateDeviceToken':
				$device_token 	= self::$app_controller->sanitise_string($request->parameters['device_token']);
				$device			= self::$app_controller->get_app_registration_by_device ($device_token);

				if (count($device) > 0) {
					$return 	= array_merge(array('status' => true), $device[0]);
					return json_encode($return);
				}else{
					return json_encode(array('status' => false));
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
			case 'SaveFormData':
				$form_data 	= array_map(self::$app_controller->sanitise_string, $request->parameters);
				$form 		= self::submit_form ($form_data);
			return json_encode($form);
			break;

			case 'RegisterAppUser':
				$company_id 	= self::$app_controller->sanitise_string($request->parameters['company_id']);
				$property_id 	= self::$app_controller->sanitise_string($request->parameters['property_id']);
				$unit_number 	= self::$app_controller->sanitise_string($request->parameters['unit_number']);
				$first_name 	= self::$app_controller->sanitise_string($request->parameters['first_name']);
				$surname 		= self::$app_controller->sanitise_string($request->parameters['surname']);
				$cellphone 		= self::$app_controller->sanitise_string($request->parameters['cellphone']);
				$user_type 		= self::$app_controller->sanitise_string($request->parameters['user_type']);
				$device_token	= self::$app_controller->sanitise_string($request->parameters['device_token']);				
				$player_id		= self::$app_controller->sanitise_string($request->parameters['player_id']);				

				$save 			= self::submit_user (
									$company_id,
									$property_id,
									$unit_number,
									$first_name,
									$surname,
									$cellphone,
									$user_type,
									$device_token,
									$player_id
								);
				return json_encode($save);
				break;

				case 'SaveMaintanceQuery':
					$device_token 	= self::$app_controller->sanitise_string($request->parameters['device_token']);
					$company_id 	= self::$app_controller->sanitise_string($request->parameters['company_id']);
					$property_id 	= self::$app_controller->sanitise_string($request->parameters['property_id']);
					$unit_number 	= self::$app_controller->sanitise_string($request->parameters['unit_number']);
					$query_type 	= self::$app_controller->sanitise_string($request->parameters['query_type']);
					$query_detail 	= self::$app_controller->sanitise_string($request->parameters['query_detail']);
					$user_name 		= self::$app_controller->sanitise_string($request->parameters['user_name']);
					$cell_phone 	= self::$app_controller->sanitise_string($request->parameters['cell_phone']);

					$file_upload 	= false;

					if (isset($_FILES['file'])) {// Test if file upload
						$file_upload = $_FILES['file'];
					}

					// var_dump($_FILE['file']);
								

					$save 			= self::submit_query (
											$device_token,
											$company_id,
											$property_id,
											$unit_number,
											$query_type,
											$query_detail,
											$user_name,
											$cell_phone,
											$file_upload
										);

				return json_encode($save);
				break;

				case 'DeleteProperty':
					
					$property_id 	= self::$app_controller->sanitise_string($request->parameters['property_id']);
					
					$save 			= self::remove_property (
											$property_id
										);

				return json_encode($save);
				break;

				case 'SaveServiceOnDemand':
					$user_id 				= self::$app_controller->sanitise_string($request->parameters['user_id']);
					$device_token 			= self::$app_controller->sanitise_string($request->parameters['device_token']);
					$company_id 			= self::$app_controller->sanitise_string($request->parameters['company_id']);
					$property_id 			= self::$app_controller->sanitise_string($request->parameters['property_id']);
					$request_detail 		= self::$app_controller->sanitise_string($request->parameters['request_detail']);
					$service_id 			= self::$app_controller->sanitise_string($request->parameters['service_id']);
					$service_type_id 		= self::$app_controller->sanitise_string($request->parameters['service_type_id']);
					$service_company_name 	= self::$app_controller->sanitise_string($request->parameters['service_company_name']);
					$service_type_name 		= self::$app_controller->sanitise_string($request->parameters['service_type_name']);
					$user_name 				= self::$app_controller->sanitise_string($request->parameters['user_name']);
					$cell_phone 			= self::$app_controller->sanitise_string($request->parameters['cell_phone']);

					$file_upload 	= false;

					if (isset($_FILES['file'])) {// Test if file upload
						$file_upload = $_FILES['file'];
					}

					// var_dump($_FILE['file']);
								

					$save 			= self::submit_service_on_demand (
											$device_token,
											$user_id,
											$company_id,
											$property_id,
											$request_detail,
											$service_id,
											$service_type_id,
											$service_company_name,
											$service_type_name,
											$user_name,
											$cell_phone,
											$file_upload
										);

				return json_encode($save);
				break;

			case 'LogToFile':
					$content 	= self::$app_controller->sanitise_string ($request->parameters['content']);
					$rite 		= self::$app_controller->write_to_log_file ($content);

				return json_encode ($rite);
				break;

			

			
		}
	}

	static public function remove_property (
											$property_id
										) {


		$property = self::$app_controller->get_app_registration_by_property ($property_id);

		if (count($property) === 0) {
			return array('status'  => false, 'text' => 'Invalid Property');
		}


		$save 		= self::$app_controller->delete_app_reg (
								$property_id
							);

		if ($save === true) {
			return array('status' => true, 'text' => 'Property Deleted'. $save);
		}else{
			return array('status' => false, 'text' => 'Failed to delete, ' . $save);
		}
	}


	static public function set_up_all_query ($company_id, $property_id, $device_id) {
		$arry 			= array();

		$queries 		= self::$app_controller->get_all_company_queries ($company_id, $property_id, $device_id);


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
			$hasimage 	= false;

			$dir 		= '../companies/' .$company_id. '/properties/'.$propId.'/queries/';
			if(!empty($image)) {
				
				if (strpos($image, '.jpg') !== false)  {// if it's image name
				// die($dir.$image);
					$hasimage 	= true;
					$img 		= 'data:image/png;base64,'.base64_encode(file_get_contents($dir.$image));
				}else{
					$hasimage 	= true;
					$img 		= 'data:image/png;base64,'.$image;
				}
			}else{
				$img 	= null;
				$hasimage 	= false;
			}

			$notifications = array();
			$note_list 		= self::$app_controller->get_notification_coms ($id);

			foreach ($note_list as $c) {

				$notifications[] 	= array(
					'sort' 	  	=> $c['created'],
					'id' 	  	=> $c['id'],
					'subject' 	=> $c['full_name'] . ' sent a notification: ',
					'name' 		=> $c['full_name'],
					'message' 	=> $c['text'],
					'type' 	  	=> 'Notification',
					'query_id'	=> $QueryID,
					'date'    	=> self::$app_controller->human_timing ($c['created']) . ' ago'
					);
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
				'full_name' 	 => $full_name,
				'date' 	     	 => $date,
				'open' 			 => $open,
				'notifications'  => $notifications,
				'has_image' 	 => $hasimage,
				'close' 		 => $close
			);


			$counter ++;
			
		}

		return $arry;
	}


	


	/**
	 * @param
	 * @return
	 */
	static public function set_up_documents ($prop_id, $company_id) {
		$docs 	= self::$app_controller->get_all_documents ($prop_id);

		$return_obj = array();

		
		foreach ($docs as $c) {
			$file_url 			= 'http://connectliving.co.za/companies/' .$company_id. '/properties/' . $prop_id .'/'. $c['doc_name'];
			$return_obj[] 		= array(
				'document_type'  	=> $c['name'],
				'property_name'  	=> $c['propertyName'],
				'doc_name'  		=> $c['doc_name'],
				'created'  			=> $c['created'],
				'file_url' 			=> $file_url
				);
		}

		return $return_obj;
	}

	/**
	 * @param
	 * @return
	 */
	static public function send_notification ($user_id, $message) {
		$app_users  	= self::$app_controller->get_app_users_byid ($user_id); 


		$player_id[] 	= $app_users[0]['userPlayerID'];
		$property_name  = $app_users[0]['property_name'];
		// die(var_dump($player_id));

		$message_tosend	= 'Notification for ' .$property_name. ': ' .$message;

		$send 			= self::$app_controller->send_push_notification ($message, $player_id);

		return $send;
	}

	/**
	 * @param
	 * @return
	 */
	static public function send_notification_supplier ($supplier_id, $message) {
		$app_users  	= self::$app_controller->get_suppliers_byid ($supplier_id); 

		$player_id[] 	= $app_users[0]['userPlayerID'];
		$company_name  	= $app_users[0]['company_name'];
		//die(print_r('supplier id ' + $app_users);

		$message_tosend	= 'Notification for ' .$company_name. ': ' .$message;

		$send 			= self::$app_controller->send_push_notification ($message, $player_id);

		//die(var_dump($message_tosend));
		return $send;
	}//dc45cafd-4580-4f6e-951e-bb114cbba07d

	/**
	 * @param
	 * @return
	 */
	static public function get_notifications ($query_id) {
		$note_list 		= self::$app_controller->get_notification_coms ($query_id);

		if (!empty($note_list)) {// notifications

			foreach ($note_list as $c) {

				$ret_array[] 	= array(
					'sort' 	  	=> $c['created'],
					'id' 	  	=> $c['id'],
					'subject' 	=> $c['full_name'] . ' sent a notification: ',
					'message' 	=> $c['text'],
					'type' 	  	=> 'Notification',
					'query_id'	=> $QueryID,
					'date'    	=> self::$app_controller->human_timing ($c['created']) . ' ago'
					);
			}

		}

		$sorted 		= self::$app_controller->array_orderby ($ret_array, 'sort', SORT_DESC);

		return $sorted;
	}

	/**
	 * @param
	 * @return
	 */
	static public function setup_qoutation ($url) {
		$html  		= self::$app_controller->get_pdf_from_image ($url); 

		$title 		= "Quotation";
		$author 	= "connectLiving";
		self::$app_controller->get_report_pdf($html, $author, $title);
		exit;


		
	}

	/**
	 * @param
	 * @return
	 */
	static public function set_up_documents_rules ($prop_id, $company_id) {
		$docs 	= self::$app_controller->get_rules_documents ($prop_id);

		$return_obj = array();

		
		foreach ($docs as $c) {
			$file_url 			= 'http://connectliving.co.za/companies/' .$company_id. '/properties/' . $prop_id .'/'. $c['doc_name'];
			$return_obj 		= array(
				'document_type'  	=> $c['name'],
				'property_name'  	=> $c['propertyName'],
				'doc_name'  		=> $c['doc_name'],
				'created'  			=> $c['created'],
				'file_url' 			=> $file_url
				);
		}

		return $return_obj;
	}


	/**
	 * @param
	 * @return
	 */
	static public function set_supplier_list ($prop_id, $company_id) {
		$suppliers   	= self::$app_controller->get_service_supplier ($prop_id);

		$return_array 	= array();
		$dir 			= 'http://connectliving.co.za/companies/' . $company_id .'/properties/' . $prop_id .'/suppliers/';

		foreach ($suppliers as $s) {

			if (!empty($s['thumbnailPic'])) {
				$file 		= $dir.$s['thumbnailPic'];
			}else{
				$file 		= 'img/placeholder.png';
			}

			// $file 	= $dir.$s['thumbnailPic'];

			$return_array[] = array(
					'id' => $s['id'],
					'service_id' => $s['service_id'],
					'service_name' => $s['service_name'],
					'company_name' => $s['company_name'],
					'address' => $s['address'],
					'address' => $s['address'],
					'phone_number' => $s['phone_number'],
					'phone_number' => $s['phone_number'],
					'email' => $s['email'],
					'thumbnail' => $file
				);
		}

		return $return_array;
	}

	/**
	 * @param
	 * @return
	 */
	static public function setup_job_list_byid ($id, $prop_id, $company_id) {
		$suppliers   	= self::$app_controller->get_service_jobs_byid ($id);

		$return_array 		= array();
		$job_status_text 	= '';
		$dir 			= 'http://connectliving.co.za/companies/' . $company_id .'/properties/' . $prop_id .'/jobs/';

		foreach ($suppliers as $s) {

			if (!empty($s['job_photo'])) {
				$file 		= $dir.$s['job_photo'];
			}else{
				$file 		= 'img/placeholder.png';
			}

			if ($s['job_approved'] == 'yes') {
				$job_status_text = $s['supplier_name']. ' has received your request.';
			}elseif ($s['job_status'] == 'waiting for payment'){
				$job_status_text = 'The Job is Complete!';
			}elseif ($s['job_status'] == 'open'){
				$job_status_text = 'Waiting For Supplier To Action Your Job!';
			}


			$return_array[] = array(
					'id' => $s['id'],
					'supplier_id' => $s['supplier_id'],
					'job_description' => $s['job_description'],
					'job_approved' => $s['job_approved'],
					'job_status' => $s['job_status'],
					'supplier_name' => $s['supplier_name'],
					'job_quote_total' => self::$app_controller->get_money_value($s['job_quote_total']),
					'job_status_text' => $job_status_text,
					'job_quote_upload' => $s['job_quote_upload'],
					'job_photo' => $file
				);
		}

		return $return_array;
	}

	/**
	 * @param
	 * @return
	 */
	static public function set_job_list ($prop_id, $company_id) {
		$suppliers   	= self::$app_controller->get_service_jobs ($prop_id, $company_id);

		$return_array 	= array();
		$dir 			= 'http://connectliving.co.za/companies/' . $company_id .'/properties/' . $prop_id .'/jobs/';

		foreach ($suppliers as $s) {

			if (!empty($s['job_photo'])) {
				$file 		= $dir.$s['job_photo'];
			}else{
				$file 		= 'img/placeholder.png';
			}


			$return_array[] = array(
					'id' => $s['id'],
					'supplier_id' => $s['supplier_id'],
					'job_description' => $s['job_description'],
					'job_approved' => $s['job_approved'],
					'job_status' => $s['job_status'],
					'supplier_name' => $s['supplier_name'],
					'job_photo' => $file
				);
		}

		return $return_array;
	}

	

	/**
	 * @param
	 * @return
	 */
	static public function set_up_form ($forms) {

		$return_array 	=  array();
		// $forms 			= self::$app_controller->get_form_by_id ($form_id);

		// decode questions object
		array_walk ( $forms, function (&$key) { $key["questions"] = json_decode($key['questions'], true); } );
		
		foreach ($forms as $s) {

			$questions = $s['questions'];

			// Format option to an array
			array_walk ( $questions, function (&$key) { $key['q_option'] = explode( ',', $key['q_option']); } );
			// array_walk ( $questions, function (&$key) { $key['q_name'] = 'question' . $key['q_num'] } );

			$q_array = array();

			foreach ($questions as $q) {

				// die(var_dump($q));
				$q_array[] = array(
					'q_name' => 'question'.str_replace('.', '_', $q['q_num']), 
					'q_num' => $q['q_num'], 
					'q_text' => $q['q_text'], 
					'q_type' => $q['q_type'], 
					'q_option' => $q['q_option'], 
					'q_mandatory' => $q['q_mandatory']
					);
			}

		


			$return_array[] = array(
				'id' 				=> $s['id'],
				'prop_id' 			=> $s['prop_id'],
				'prop_name' 		=> $s['propertyName'],
				'name' 				=> $s['name'],
				'form_instruction' 	=> $s['form_instruction'],
				'questions'			=> $q_array
				);

		}
		
		return $return_array;
	}


	static protected function get_devices_properties ($device_token) {

		$devices 			= self::$app_controller->get_app_registration_by_device ($device_token);
		return $devices;
	}

	static protected function set_up_dashbord_data ($company_id, $property_id) {
		$notices 			= self::$app_controller->get_property_notification ($company_id, $property_id);
		
		$image 				= self::set_get_image ($company_id, $property_id);

		$return_array 		= array();
		foreach ($notices as $n) {
			$message 		= $n['message'];
			$showDateFrom 	= date("l jS \of\ F", strtotime ($n['showDateFrom']));
			$showDateTo 	= $n['showDateTo'];
			$mood 			= $n['mood'];
		}
		$return_array  		= array(
								'message' => $message,
								'date' => $showDateFrom,
								'showDateTo' => $showDateTo,
								'mood' => $mood,
								'image' => $image
								);

		return $return_array;
	}

	static protected function set_up_areas ($area_type, $area_name = '') {

		$return_arr = array();
		$areas 		= self::$app_controller->get_all_properties ();

		switch ($area_type) {
			case 'country':

				foreach ($areas as $a) {
					$name 			= $a['propertyCountry'];
					$image 			= str_replace('img/', 'http://manage.connectliving.co.za/public/images/app_images/', $a['countryImg']);
					$return_arr[] 	= array('name' => $name, 'image' => $image );
				}

				break;

			case 'province':
				$areas_filter = self::$app_controller->filter_by_value ($areas, 'propertyCountry', $area_name);

				// die(var_dump($areas_filter));
				foreach ($areas_filter as $a) {
					$name 			= $a['propertyProvince'];
					$image 			= str_replace('img/', 'http://manage.connectliving.co.za/public/images/app_images/', $a['provinceImg']);
					$return_arr[] 	= array('name' => $name, 'image' => $image );
				}

				break;
			case 'city':
				$areas_filter = self::$app_controller->filter_by_value ($areas, 'propertyProvince', $area_name);

				// die(var_dump($areas_filter));
				foreach ($areas_filter as $a) {
					$name 			= $a['propertyCity'];
					$image 			= str_replace('img/', 'http://manage.connectliving.co.za/public/images/app_images/', $a['cityImg']);
					$return_arr[] 	= array('name' => $name, 'image' => $image );
				}

				break;
			
			default:
				# code...
				break;
		}

		return self::$app_controller->array_remove_dublicates ($return_arr, 'name');

	}


	static public function set_get_image ($company_id, $prop_id){

		$homeimage 	= 'http://connectliving.co.za/companies/' . $company_id .'/properties/' . $prop_id .'/homeImage.jpg';

		// $cpant 		= $dir.'/logo.jpg';
		// $cdata 		= file_get_contents($cpant);
		// $epant 		= $dir.'/homeImage.jpg';
		// $edata 		= file_get_contents($epant);

		// $cbase64 	= false;
		// if ($cdata) {
		// 	$cbase64 	= 'data:image/jpg;base64,' . base64_encode($cdata);
		// }


		// $ebase64 	= false;
		// if ($edata) {
		// 	$ebase64 	= 'data:image/jpg;base64,' . base64_encode($edata);
		// }
	

		return $homeimage;
	}


	/*** save query ***/
	static public function submit_user (
								$company_id,
								$property_id,
								$unit_number,
								$first_name,
								$surname,
								$cellphone,
								$user_type,
								$device_token,
								$player_id
							) {

		$company_info 	= self::$app_controller->get_property_for_company ($company_id);
		$property_info 	= self::$app_controller->get_property_info_byid ($property_id);
		

		$full_name 		= $first_name . ' ' . $surname;


		if (count($company_info) === 0) {
			return array('status'  => false, 'text' => 'Invalid Company Info');
		}

		if (count($property_info) === 0) {
			return array('status'  => false, 'text' => 'Invalid Property Info');
		}

		if (!self::$app_controller->validate_variables ($device_token, 3)) {
			return array('status'  => false, 'text' => 'Invalid Device Token');
		}

		if (!self::$app_controller->validate_variables ($user_type, 3)) {
			return array('status'  => false, 'text' => 'Invalid User Type');
		}

		$device_info 	= self::$app_controller->get_app_registration_by_device ($device_token);

		// if (count($device_info) === 1) {
		// 	return array('status'  => false, 'text' => 'Device already registered');
		// }

		
		$save 		= self::$app_controller->insert_app_reg (
								$company_id,
								$property_id,
								$unit_number,
								$full_name,
								$cellphone,
								$user_type,
								$device_token,
								$player_id
							);

		if ($save['status'] === true) {
			return array('status' => true, 'text' => 'User Registered', $save);
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** save query ***/
	static public function submit_query (
									$device_token,
									$company_id,
									$property_id,
									$unit_number,
									$query_type,
									$query_detail,
									$user_name,
									$cell_phone,
									$file_upload
								) {

		$device_info 	= self::$app_controller->get_app_registration_by_device ($device_token);

		if (count($device_info) < 1) {
			return array('status'  => false, 'text' => 'Device Not Registered');
		}

		$property_info 	= self::$app_controller->get_property_info_byid ($property_id);
		

		if (count($property_info) === 0) {
			return array('status'  => false, 'text' => 'Invalid Property Info');
		}

		if (!self::$app_controller->validate_variables ($device_token, 3)) {
			return array('status'  => false, 'text' => 'Invalid Device Token');
		}

		if (empty($query_type)) {
			return array('status'  => false, 'text' => 'Invalid Query Type');
		}

		if (empty($query_detail)) {
			return array('status'  => false, 'text' => 'Invalid Query Detail');
		}

		$file_name 		= '';
		$query_image 	= false;
		if (!empty($file_upload)) {
			$dir 						= '../companies/' .$company_id. '/properties/'.$property_id.'/queries';
			$create 					= self::$app_controller->created_directory ($dir);
			$file_name 					= self::$app_controller->upload_file ($file_upload, $dir);
			// die(var_dump($name));
			$query_image 					= 'http://connectliving.co.za/companies/'.$company_id. '/properties/'.$property_id.'/queries/'.$file_name;
		}

		$property_name 					= $property_info[0]['propertyName'];
		$building_manager_name 			= $property_info[0]['buildingManagerName'];
		$building_manager_email1		= $property_info[0]['buildingManagerEmail'];
		$building_manager_email2		= $property_info[0]['buildingManagerEmail2'];
		$query_date 					= date("Y-m-d H:i:s");  
		

		$get_email						= self::$app_controller->get_query_email (
											$building_manager_name, 
											$query_date, 
											$user_name, 
											$property_name,
											$unit_number,
											$query_type,
											$query_detail,
											$query_image
										);

		// echo $get_email;
		// die();
		$save 		= self::$app_controller->insert_app_query (
								$device_token,
								$property_id,
								$unit_number,
								$query_type,
								$query_detail,
								$user_name,
								$cell_phone,
								$file_name
							);

		



		if ($save === true) {
			$send  = self::$app_controller->send_email ($get_email, 'connectLIVING Query Details', $building_manager_email1, $building_manager_name);

			$send2  = self::$app_controller->send_email ($get_email, 'connectLIVING Query Details', $building_manager_email2, $building_manager_name);
			return array ('status' => true, 'text' => 'Query Submitted');
		}else{
			return array ('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** save service on demand ***/
	static public function submit_service_on_demand (
									$device_token,
									$user_id,
									$company_id,
									$property_id,
									$request_detail,
									$service_id,
									$service_type_id,
									$service_company_name,
									$service_type_name,
									$user_name,
									$cell_phone,
									$file_upload
								) {

		$device_info 	= self::$app_controller->get_app_registration_by_device ($device_token);

		if (count($device_info) < 1) {
			return array('status'  => false, 'text' => 'Device Not Registered');
		}

		$property_info 	= self::$app_controller->get_property_info_byid ($property_id);
		

		if (count($property_info) === 0) {
			return array('status'  => false, 'text' => 'Invalid Property Info');
		}

		if (!self::$app_controller->validate_variables ($device_token, 3)) {
			return array('status'  => false, 'text' => 'Invalid Device Token');
		}

		if (empty($request_detail)) {
			return array('status'  => false, 'text' => 'Invalid Job Description');
		}

		

		$file_name 	= '';
		if (!empty($file_upload)) {
			$dir 						= '../companies/' .$company_id. '/properties/'.$property_id.'/jobs';
			$create 					= self::$app_controller->created_directory ($dir);
			$file_name 					= self::$app_controller->upload_file ($file_upload, $dir);
		}

		
		$save 		= self::$app_controller->insert_service_on_demand (
								$property_id,
								$company_id,
								$service_id,
								$user_id,
								$request_detail,
								$file_name
							);

		if ($save === true) {
			return array('status' => true, 'text' => 'Query Submitted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	static public function submit_form ($form_data) {
		
		$return_array 	=  array();
		/*** merge form data with image data ***/
		

		if (empty($form_data['form_id'])) {
			return array('status'  => false, 'text' => 'No from selected');
		}

		$form_id 	= $form_data['form_id'];
		$unit_no 	= $form_data['unit_no'];
		$full_name 	= $form_data['full_name'];
		$cellphone 	= $form_data['cellphone'];
		$res_id 	= (isset($form_data['res_id']))?
						$form_data['res_id'] : 0;

		// Get form info
		$forms 		= self::$app_controller->get_form_by_id ($form_id);

		if (empty($forms)) {
			return array('status'  => false, 'text' => 'Invalid Form ID');
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

				if (is_array($form_data[$q_name])) {// Checkbox or Multiple fields
					$responce  = implode(',', $form_data[$q_name]);
				}elseif ($q_type  	== 'signature') {
					/* Check if signature */

					$base_str 	   = str_replace('data:image/png;base64,', '', $form_data[$q_name]);
					$responce  	   = 'singiture_' . uniqid().'.png';// siniture file name
					$basestr   	   = base64_decode ($base_str);// get singiture string

					// echo $basestr;
					// die($basestr);

					$filename_path = $directory . $responce;// image path

					file_put_contents ($filename_path, $basestr);// Save sigature to path

				}elseif ($q_type == 'file_upload') {
					/* Check if signature */

					$base_str 	   = str_replace('data:image/jpeg;base64,', '', $form_data[$q_name]);
					$responce  	   = 'fileupload_' . uniqid().'.jpeg';// image file name
					$basestr   	   = base64_decode ($base_str);// get image string

					$filename_path = $directory . $responce;// image path

					file_put_contents ($filename_path, $basestr);// Save sigature to path

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
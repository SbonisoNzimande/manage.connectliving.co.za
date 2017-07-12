<?php
/**
 * App controller
 * 
 * @package 
 * @author  
 */
class AppController extends AppModel
{

	static public $server_name;
	static public $pw_auth 			= 'q6DlAJFTq6DfORe0Llld2bpp8qYkDmBUQkhayaLpcEMxhaebFBETwetvhaN8EE0dznjZWnaI86aldnK1eWBj';
	static public $pw_application 	= 'E6FD7-88B1C';
	static public $devices 			= array('5162ec865dbcf3d636546c5d422c099d89fa99b05b8a0a3ce2820195edf98b1e');// test

	static public $sms_gateway		= 'http://bulksms.2way.co.za/eapi/submission/send_sms/2/2.0';
	static public $sms_username		= 'warrencanning';
	static public $sms_password		= 'sandpiper121';
	static public $sms_headers		= 'Content-type:application/x-www-form-urlencoded';

	// -- email -- //
	static public $email_username	= 'manage@connectliving.co.za';
	static public $email_password	= 'S@ndpiper121';
	static public $email_port		= '587';
	static public $email_server		= 'dedi484.jnb2.host-h.net';
	static public $app_id			= 'a800c1c9-a2d0-460f-b7e0-893bcae8c870';
	static public $company_id;
	static public $emailer;

	// public function __construct() {
	// 	self::$emailer = new PHPMailer();
	// }
	// public function __construct() {
	// 	self::set_session_start();
	// 	// self::$company_id = $_SESSION['company_id'];
	// }


	/**
	 * Post Request
	 *
	 * @param
	 * @return
	 */
	public function post($request) {
		$subRequest		= (isset($request->url_elements[1])) ? $request->url_elements[1] : '';

		self::set_session_start();
		$email 			= $_SESSION['email'];

		switch ($subRequest) {
			case 'SaveActivity':
				$user_id 				= self::sanitise_string($request->parameters['user_id']);
				$prop_id 				= self::sanitise_string($request->parameters['prop_id']);
				$user_type 				= self::sanitise_string($request->parameters['user_type']);
				$activity_description 	= self::sanitise_string($request->parameters['activity_description']);

				$save 					= self::save_activity ($user_id, $prop_id, $user_type, $activity_description);
				return json_encode($save);
				break;

		}
	}

	/**
	 * Get Request
	 *
	 * @param
	 * @return
	 */
	public function get($request) {
		$subRequest		= (isset($request->url_elements[1])) ? $request->url_elements[1] : '';

		self::set_session_start();
		$email 			= $_SESSION['email'];

		switch ($subRequest) {
			case 'GetOwnerProperties':
				$email 				= self::sanitise_string($request->parameters['email']);

				$prop 				= self::get_owner_properties ($email);
				return json_encode($prop);
				break;

		}
	}

	static public function get_owner_properties ($email) {

		$ret_array = array();
		// validate email
		if (!self::validate_variables ($email, 10)) {
			return array('status' => false, 'text' => 'invalid email');
		}

		$devels = parent::get_all_developments_db();
		$pre 			= '';
		$pro_list 		= '';
		foreach ($devels as $d) {
			$table 		= $d['developmentSlug'];
			$pro_list 	.= $pre . $table;
			$pre  	 	 = ',';
		}

		$pro_info = parent::get_all_pro_bylist_db($pro_list);

		return array('status' => true, 'data' => $pro_info);
	}

	
	static public function human_timing ($time) {

		$ftime 	= strtotime($time);
	    $ftime 	= time() - $ftime; // to get the time since that moment

	    $tokens = array (
	        31536000 => 'year',
	        2592000 => 'month',
	        604800 => 'week',
	        86400 => 'day',
	        3600 => 'hour',
	        60 => 'minute',
	        1 => 'second'
	    );

	    foreach ($tokens as $unit => $text) {
	        if ($ftime < $unit) continue;
	        $numberOfUnits = floor($ftime / $unit);
	        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	    }

	}

	static public function save_activity ($user_id, $prop_id, $user_type, $activity_description) {

		if (!is_numeric($user_id)) {
			return array('status'  => false, 'text' => 'Invalid user id'.$user_id);
		}


		if (!is_numeric($prop_id)) {
			return array('status'  => false, 'text' => 'Invalid prop_id'.$prop_id);
		}

		if (!self::validate_variables ($user_type, 3)) {
			return array('status'  => false, 'text' => 'Invalid user type');
		}

		if (!self::validate_variables ($activity_description, 3)) {
			return array('status'  => false, 'text' => 'Invalid Activity Description');
		}

		$save 		= parent::save_activity_db ($user_id, $prop_id, $user_type, $activity_description);

		if ($save === true) {
			return array('status'  => true, 'text' => 'Saved');
		}else{
			return array('status'  => false, 'text' => 'Failed to insert, ' . $update);
		}
	}

	static public function get_all_doc_types () {
		return parent::get_all_doc_types_db ();
	}

	static public function get_all_prop_venues ($prop_id) {
		return parent::get_all_prop_venues_db ($prop_id);
	}

	static public function get_venue_details ($venue_id) {
		return parent::get_venue_details_db ($venue_id);
	}

	static public function get_venue_bookings ($venue_id) {
		return parent::get_venue_bookings_db ($venue_id);
	}

	static public function get_venue_booking_date ($VenueID, $BookingDate, $BookingTimeFrom, $BookingTimeTo) {
		return parent::get_venue_booking_date_db ($VenueID, $BookingDate, $BookingTimeFrom, $BookingTimeTo);
	}

	static public function get_venue_days_open ($VenueID, $day_week) {
		return parent::get_venue_days_open_db ($VenueID, $day_week);
	}

	static public function get_all_service_types () {
		return parent::get_all_service_types_db ();
	}


	static public function get_logins_email ($fullname, $username, $password) {

		$html  	  = file_get_contents('public/emails/logins_email.html'); // external css

		$ret_html = preg_replace('/[\[{\(]name+[\]}\)]/' , $fullname, $html);
		$ret_html = preg_replace('/[\[{\(]username+[\]}\)]/' , $username, $ret_html);
		$ret_html = preg_replace('/[\[{\(]password+[\]}\)]/' , $password, $ret_html);
		return $ret_html;
	}
	
	static public function get_email_document_email(
								$email_to,
								$email_from
							) {

		$html  	  = file_get_contents('public/emails/document_email.html'); // external css

		$ret_html = preg_replace('/[\[{\(]email_to+[\]}\)]/' , $email_to, $html);
		$ret_html = preg_replace('/[\[{\(]email_from+[\]}\)]/' , $email_from, $ret_html);
		return $ret_html;
	}

	static public function copy_form ($FormID, $PropertyName) {
		return parent::copy_form_db ($FormID, $PropertyName);
	}

	static public function get_property_byid ($ID) {
		return parent::get_property_byid_db ($ID);
	}

	static public function copy_contractor ($ContractorID, $PropertyName) {
		return parent::copy_contractor_db ($ContractorID, $PropertyName);
	}
	static public function duplicate_emergency_contact ($ContactID, $PropertyName) {
		return parent::duplicate_emergency_contact_db ($ContactID, $PropertyName);
	}

	static public function get_property_notification ($company_id, $property_id) {
		return parent::get_property_notification_db ($company_id, $property_id);
	}

	static public function copy_document ($DocumentID, $PropertyName) {
		return parent::copy_document_db ($DocumentID, $PropertyName);
	}

	static public function edit_query ($ID, $QueryType, $UserID, $Query, $Property, $Unit) {
		return parent::edit_query_db ($ID, $QueryType, $UserID, $Query, $Property, $Unit);
	}

	static public function update_query ($ID, $QueryType, $UserID, $Query, $Property, $Unit) {
		return parent::update_query_db ($ID, $QueryType, $UserID, $Query, $Property, $Unit);
	}

	static public function update_markting_url ($prop_id, $marketing_url) {
		return parent::update_markting_url_db ($prop_id, $marketing_url);
	}


	static public function edit_billing_query ($ID, $QueryType, $UserID, $Query, $Property, $Unit) {
		return parent::edit_billing_query_db ($ID, $QueryType, $UserID, $Query, $Property, $Unit);
	}

	static public function get_property_list($company_id = NULL) {
		return parent::get_property_list_db($company_id);
	}

	static public function get_property_list_permission($properties = array()) {
		return parent::get_property_list_permission_db(implode(",", $properties));
	}

	static public function get_all_activities() {
		return parent::get_all_activities_db();
	}

	static public function get_all_logs($company_id = false) {
		return parent::get_all_logs_db($company_id);
	}

	static public function get_all_maintenance() {
		return parent::get_all_maintenance_db();
	}

	static public function get_all_queries_company ($company_id = false, $prop_array = false, $query_type = false, $date_from = false, $date_to = false) {
		return parent::get_all_queries_company_db ($company_id, implode(",", $prop_array), $query_type, $date_from, $date_to);
	}

	static public function get_all_queries ($prop_array, $query_type = false) {
		return parent::get_all_queries_db  (implode(",", $prop_array), $query_type);
	}

	// get_all_company_queries_db  ($company_id, $property_id, $device_id)
	static public function get_all_company_queries ($company_id, $property_id, $device_id) {
		return parent::get_all_company_queries_db  ($company_id, $property_id, $device_id);
	}
	//
	static public function get_property_assets ($prop_array) {
		return parent::get_property_assets_db  (implode(",", $prop_array));
	}

	static public function get_all_calendar_reminders ($company_id) {
		return parent::get_all_calendar_reminders_db($company_id);
	}


	static public function get_page_queries ($prop_array, $limit, $offset) {
		return parent::get_page_queries_db(implode(",", $prop_array), $limit, $offset);
	}

	static public function get_filtered_queries ($company_id = false, $prop_array = false, $status = false, $query_type = false, $date_from = false, $date_to = false) {

		return parent::get_filtered_queries_db ($company_id, implode(",", $prop_array), $status, $query_type, $date_from, $date_to);
	}

	static public function get_notifications($company_id) {
		return parent::get_notifications_db($company_id);
	}

	static public function get_four_queries($prop_array) {
		return parent::get_four_queries_db(implode(",", $prop_array));
	}

	static public function get_property_for_company ($ID) {
		return parent::get_property_for_company_db ($ID);
	}

	static public function get_property_info_byid ($property_id) {
		return parent::get_property_info_byid_bd ($property_id);
	}

	static public function vote_up ($user_id, $qoute_id) {
		return parent::vote_up_db ($user_id, $qoute_id);
	}
	static public function vote_down ($user_id, $qoute_id) {
		return parent::vote_down_db ($user_id, $qoute_id);
	}

	static public function get_permission_by_type($permission_type){
		return parent::get_permission_by_type_db ($permission_type);
	}

	static public function get_job_by_id ($job_id){
		return parent::get_job_by_id_db ($job_id);
	}

	static public function get_qoute_byid ($qoute_id) {
		return parent::get_qoute_byid_db ($qoute_id);
	}

	static public function get_property_trustees ($prop_id) {
		return parent::get_property_trustees_db ($prop_id);
	}

	static public function get_job_quotes_by_id ($job_id){
		return parent::get_job_quotes_by_id_db ($job_id);
	}

	static public function get_properties_by_city ($city_name) {
		return parent::get_properties_by_city_db ($city_name);
	}

	static public function save_job_status (
									$JobID,
									$JobStatus
								){
		$datedone = '';

		if ($JobStatus == 'Done') {
			$datedone = date("Y-m-d H:i:");
		}
		// die($JobStatus);
		
		return parent::save_job_status_db (
									$JobID,
									$JobStatus,
									$datedone
								);
	}

	static public function update_job (
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
								){
		
		return parent::update_job_db (
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
	}



	static public function format_date($date) {
		return date("D jS \of M Y h:i A", strtotime($date));
	}

	static public function get_maintenance_list ($prop_id) {
		return parent::get_maintenance_list_db($prop_id);
	}

	static public function get_asset_by_id ($ID) {
		return parent::get_asset_by_id_db($ID);
	}

	

	static public function get_qr_code ($text) {
		return QRcode::png($text);
	}

	static public function encode_base64 ($edata) {
		return  'data:image/jpg;base64,' . base64_encode($edata);
	}



	static public function get_tenant_list ($prop_id) {
		return parent::get_tenant_list_db ($prop_id);
	}

	static public function get_all_residents ($prop_id) {
		return parent::get_all_residents_db ($prop_id);
	}

	static public function get_all_contructors ($prop_id) {
		return parent::get_all_contructors_db ($prop_id);
	}

	static public function get_all_minutes ($prop_id) {
		return parent::get_all_minutes_db ($prop_id);
	}

	static public function get_all_assets ($prop_id) {
		return parent::get_all_assets_db ($prop_id);
	}
	
	static public function get_all_forms ($prop_id) {
		parent::set_utf8();
		return parent::get_all_forms_db ($prop_id);
	}

	static public function get_archived_residents ($prop_id) {
		return parent::get_archived_residents_db ($prop_id);
	}

	static public function get_trustees_residents ($prop_id) {

		return parent::get_trustees_residents_db ($prop_id);
	}

	static public function get_users_by_pro ($ids) {
		return parent::get_users_by_pro_db ($ids);
	}

	static public function get_responces ($form_id, $prop_id, $unit_no, $resp_id, $q_num) {
		return parent::get_responces_db ($form_id, $prop_id, $unit_no, $resp_id, $q_num);
	}

	static public function get_tasks_byemail ($email) {
		return parent::get_tasks_byemail_db($email);
	}
	
	static public function get_notifications_by_id ($id) {
		return parent::get_notifications_by_id_db($id);
	}

	static public function get_form_by_id  ($form_id) {
		return parent::get_form_by_id_db ($form_id);
	}

	static public function get_billing_byid ($id) {
		return parent::get_billing_byid_db($id);
	}

	static public function get_resident_byid ($ID) {
		return parent::get_resident_byid_db($ID);
	}

	static public function get_asset_byid ($ID) {
		return parent::get_asset_byid_db($ID);
	}

	static public function get_asset_info_byid ($ID) {
		return parent::get_asset_info_byid_db($ID);
	}

	static public function get_contractor_byid ($ID) {
		return parent::get_contractor_byid_db($ID);
	}

	static public function get_all_countries () {
		return parent::get_all_countries_db();
	}

	static public function get_all_properties () {
		return parent::get_all_properties_db ();
	}
	
	static public function get_cities_by_country ($country_id) {
		return parent::get_cities_by_country_db ($country_id);
	}

	static public function get_supplier_email (
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

		$html  	  = file_get_contents('public/emails/supplier_email.html'); // external css

		$ret_html = preg_replace('/[\[{\(]job_id+[\]}\)]/' , $job_id, $html);
		$ret_html = preg_replace('/[\[{\(]supplier_name+[\]}\)]/' , $supplier_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]supplier_email+[\]}\)]/' , $supplier_email, $ret_html);
		$ret_html = preg_replace('/[\[{\(]property_name+[\]}\)]/' , $property_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]unit_number+[\]}\)]/' , $supplier_unit_number, $ret_html);
		$ret_html = preg_replace('/[\[{\(]priority+[\]}\)]/' , $supplier_priority, $ret_html);
		$ret_html = preg_replace('/[\[{\(]description+[\]}\)]/' , $supplier_description, $ret_html);
		$ret_html = preg_replace('/[\[{\(]job_status+[\]}\)]/' , $supplier_job_status, $ret_html);
		$ret_html = preg_replace('/[\[{\(]date_tobe_completed+[\]}\)]/' , $supplier_date_tobe_completed, $ret_html);
		return $ret_html;
	}

	static public function get_qoutation_email (
									$trustee_name, 
									$trustee_link
								) {

		$html  	  = file_get_contents('public/emails/quotation_email.html'); // external css

		$ret_html = preg_replace('/[\[{\(]trustee_name+[\]}\)]/' , $trustee_name, $html);
		$ret_html = preg_replace('/[\[{\(]vote_link+[\]}\)]/' , $trustee_link, $ret_html);
		
		return $ret_html;
	}

	static public function get_property_request_email (
							$property_name, 
							$address, 
							$full_name, 
							$user_email
						) {

		$html  	  = file_get_contents('public/emails/property_request_email.html'); // external css

		$ret_html = preg_replace('/[\[{\(]property_name+[\]}\)]/' , $property_name, $html);
		$ret_html = preg_replace('/[\[{\(]address+[\]}\)]/' , $address, $ret_html);
		$ret_html = preg_replace('/[\[{\(]full_name+[\]}\)]/' , $full_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]email+[\]}\)]/' , $user_email, $ret_html);
		
		return $ret_html;
	}

	static public function get_query_email (
							$manager_name, 
							$query_date, 
							$username, 
							$property_name,
							$unit_number,
							$query_type,
							$query_details,
							$query_image
						) {

		$html  	  = file_get_contents('public/emails/query_email.html'); // external css

		$ret_html = preg_replace('/[\[{\(]manager_name+[\]}\)]/' , $manager_name, $html);
		$ret_html = preg_replace('/[\[{\(]query_date+[\]}\)]/' , $query_date, $ret_html);
		$ret_html = preg_replace('/[\[{\(]username+[\]}\)]/' , $username, $ret_html);
		$ret_html = preg_replace('/[\[{\(]property_name+[\]}\)]/' , $property_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]unit_number+[\]}\)]/' , $unit_number, $ret_html);
		$ret_html = preg_replace('/[\[{\(]query_type+[\]}\)]/' , $query_type, $ret_html);
		$ret_html = preg_replace('/[\[{\(]query_details+[\]}\)]/' , $query_details, $ret_html);
		$ret_html = preg_replace('/[\[{\(]query_image+[\]}\)]/' , $query_image, $ret_html);
		return $ret_html;
	}

	static public function get_print_job_template (
							$job_id, 
							$property_name, 
							$unit_number, 
							$supplier_name,
							$priority,
							$job_status,
							$description,
							$image,
							$authorised_by,
							$date_tobe_completed,
							$created_date
						) {

		$html  	  = file_get_contents('public/emails/print_job_template.html'); // external css

		$ret_html = preg_replace('/[\[{\(]job_id+[\]}\)]/' , $job_id, $html);
		$ret_html = preg_replace('/[\[{\(]property_name+[\]}\)]/' , $property_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]unit_number+[\]}\)]/' , $unit_number, $ret_html);
		$ret_html = preg_replace('/[\[{\(]supplier_name+[\]}\)]/' , $supplier_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]priority+[\]}\)]/' , $priority, $ret_html);
		$ret_html = preg_replace('/[\[{\(]job_status+[\]}\)]/' , $job_status, $ret_html);
		$ret_html = preg_replace('/[\[{\(]description+[\]}\)]/' , $description, $ret_html);
		$ret_html = preg_replace('/[\[{\(]image+[\]}\)]/' , $image, $ret_html);
		$ret_html = preg_replace('/[\[{\(]authorised_by+[\]}\)]/' , $authorised_by, $ret_html);
		$ret_html = preg_replace('/[\[{\(]date_tobe_completed+[\]}\)]/' , $date_tobe_completed, $ret_html);
		$ret_html = preg_replace('/[\[{\(]created_date+[\]}\)]/' , $created_date, $ret_html);
		return $ret_html;
	}

	static public function get_approval_email (
							$name, 
							$form_response,
							$resp_id
						) {

		$html  	  = file_get_contents('public/emails/form_approval_email.html'); // external css

		$ret_html = preg_replace('/[\[{\(]name+[\]}\)]/' , $name, $html);
		$ret_html = preg_replace('/[\[{\(]form_response+[\]}\)]/' , $form_response, $ret_html);
		$ret_html = preg_replace('/[\[{\(]resp_id+[\]}\)]/' , $resp_id, $ret_html);
		return $ret_html;
	}


	
	static public function get_admin_credit_notification_email (
							$full_name, 
							$company_id, 
							$company_name, 
							$number_credits,
							$transaction_id
						) {

		$html  	  = file_get_contents('public/emails/admin_email.html'); // external css

		$ret_html = preg_replace('/[\[{\(]name+[\]}\)]/' , $full_name, $html);
		$ret_html = preg_replace('/[\[{\(]company_id+[\]}\)]/' , $company_id, $html);
		$ret_html = preg_replace('/[\[{\(]company_name+[\]}\)]/' , $company_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]number_credits+[\]}\)]/' , $number_credits, $ret_html);
		$ret_html = preg_replace('/[\[{\(]transaction_id+[\]}\)]/' , $transaction_id, $ret_html);
		return $ret_html;
	}


	static public function get_money_value_cents ($num) {

		return 'R' . number_format((double)$num, 2);
	}

	static public function get_residents () {
		return parent::get_residents_db();
	}

	static public function get_prop_residents ($prop_id) {
		return parent::get_prop_residents_db ($prop_id);
	}

	static public function get_get_appreg_byid ($id) {
		return parent::get_get_appreg_byid_db ($id);
	}

	static public function get_resident_owners_bypropid ($prop_id) {
		return parent::get_resident_owners_bypropid_db ($prop_id);
	}

	static public function get_queries_byid ($id) {
		return parent::get_queries_byid_db($id);
	}
	
	static public function get_just_queries_byid ($id) {
		return parent::get_just_queries_byid_db($id);
	}

	static public function get_query_byid ($id) {
		return parent::get_query_byid_db($id);
	}

	static public function get_job_byid ($id) {
		return parent::get_job_byid_db($id);
	}

	static public function get_query_details_by_jobid ($id) {
		return parent::get_query_details_by_jobid_db ($id);
	}

	static public function get_job_list ($prop_id) {
		return parent::get_job_list_db ($prop_id);
	}

	static public function json_clean_decode ($json, $assoc = false, $depth = 512, $options = 0) {
		// search and remove comments like /* */ and //
	   $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
	  
	   if(version_compare(phpversion(), '5.4.0', '>=')) {
	       $json = json_decode($json, $assoc, $depth, $options);
	   }
	   elseif(version_compare(phpversion(), '5.3.0', '>=')) {
	       $json = json_decode($json, $assoc, $depth);
	   }
	   else {
	       $json = json_decode($json, $assoc);
	   }

	   return $json;
	}

	static public function get_all_job_list ($prop_id) {
		return parent::get_all_job_list_db ($prop_id);
	}

	static public function get_company_job_list ($prop_array = false, $company_id = false) {
		return parent::get_company_job_list_db (implode(",", $prop_array), $company_id);
	}

	static public function get_job_list_per_status ($prop_id, $JobStatus) {
		return parent::get_job_list_per_status_db ($prop_id, $JobStatus);
	}

	static public function get_all_job_list_per_status ($prop_id, $JobStatus) {
		return parent::get_all_job_list_per_status_db ($prop_id, $JobStatus);
	}

	static public function get_contact_table ($ID) {
		return parent::get_contact_table_db ($ID);
	}

	static public function get_communication_list ($prop_id) {
		return parent::get_communication_list_db ($prop_id);
	}

	static public function get_notification_coms ($query_id) {
		return parent::get_notification_coms_db ($query_id);
	}

	static public function get_notification_byid ($prop_id, $id){
		return parent::get_notification_byid_db ($prop_id, $id);
	}
	
	static public function delete_sms_info ($id) {
		return parent::delete_sms_info_db ($id);
	}

	static public function delete_sms_property_info ($id) {
		return parent::delete_sms_property_info_db ($id);
	}

	static public function delete_this_query ($id) {
		return parent::delete_this_query_db ($id);
	}

	static public function delete_this_job ($id) {
		return parent::delete_this_job_db ($id);
	}

	static public function get_sms_coms ($QueryID) {
		return parent::get_sms_coms_db ($QueryID);
	}

	static public function get_job_sms_coms ($QueryID) {
		return parent::get_job_sms_coms_db ($QueryID);
	}

	static public function get_property_sms_coms ($prop) {
		return parent::get_property_sms_coms_db ($prop);
	}
	
	static public function get_property_email_coms ($prop) {
		return parent::get_property_email_coms_db ($prop);
	}

	static public function get_property_email_byid ($prop_id, $id){
		return parent::get_property_email_byid_db ($prop_id, $id);
	}

	static public function get_res_sms_coms ($ResID) {
		return parent::get_res_sms_coms_db ($ResID);
	}

	static public function get_comment_coms ($QueryID) {
		return parent::get_comment_coms_db ($QueryID);
	}

	static public function get_job_comment_coms ($JobsID) {
		return parent::get_job_comment_coms_db ($JobsID);
	}

	static public function get_res_email_coms ($ResID) {
		return parent::get_res_email_coms_db ($ResID);
	}

	static public function get_res_comment_coms ($ResID) {
		return parent::get_res_comment_coms_db ($ResID);
	}

	static public function get_sms_byid ($id) {
		return parent::get_sms_byid_db ($id);
	}

	static public function get_sms_property_byid ($id) {
		return parent::get_sms_property_byid_db ($id);
	}

	static public function get_property_sms_byid ($prop_id, $id) {
		return parent::get_property_sms_byid_db ($prop_id, $id);
	}

	static public function get_email_coms ($prop_id) {
		return parent::get_email_coms_db ($prop_id);
	}

	static public function get_email_property_byid ($id) {
		return parent::get_email_property_byid_db ($id);
	}

	static public function get_email_byid ($id) {
		return parent::get_email_byid_db ($id);
	}

	static public function save_new_notification ($PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood) {
		return parent::save_new_notification_db($PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood);
	}

	static public function save_notification_coms ($message, $query_id, $user_id) {
		return parent::save_notification_coms_db ($message, $query_id, $user_id);
	}

	static public function save_sms_coms ($Message, $QueryID, $cell_phone, $user_id){
		return parent::save_sms_coms_db ($Message, $QueryID, $cell_phone, $user_id);
	}

	static public function save_job_sms_coms($Message, $JobID, $cell_phone, $user_id) {
		return parent::save_job_sms_coms_db ($Message, $JobID, $cell_phone, $user_id);
	}

	static public function save_property_com_sms ($Cells, $Message, $SendTo, $Mood, $PropertyID, $UserID){
		return parent::save_property_com_sms_db ($Cells, $Message, $SendTo, $Mood, $PropertyID, $UserID);
	}

	static public function save_res_sms_coms ($Message, $ResID, $cell_phone, $UserID, $PropertyID){
		return parent::save_res_sms_coms_db ($Message, $ResID, $cell_phone, $UserID, $PropertyID);
	}
	
	static public function update_submission ($UnitNumber, $ResID, $SubmissionID){
		return parent::update_submission_db ($UnitNumber, $ResID, $SubmissionID);
	}

	static public function update_app_user_type ($ID, $UserType){
		return parent::update_app_user_type_db ($ID, $UserType);
	}

	static public function save_email_coms ($Message, $Subject, $Email, $Mood, $Property_ID) {
		return parent::save_email_coms_db (self::sanitise_string($Message), $Subject, $Email, $Mood, $Property_ID);
	}

	static public function get_report_pdf ($html, $author, $title) {
		// create new PDF document
		$mpdf 		=	new mPDF('c', 'A4-L', '', '', 10, 10, 48, 25, 10, 10); 

		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle($title);
		$mpdf->SetAuthor($author);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->ignore_invalid_utf8 = true;
		$mpdf->WriteHTML($html);
		$mpdf->SetJS('print(alert(4));');
		$mpdf->Output(); 
	}

	static public function get_maitenace_byid ($id) {
		return parent::get_maitenace_byid_db($id);
	}

	static public function assign_maintain_user ($ID, $AssineeID) {

		$assign 		 = parent::assign_maintain_user_db ($ID, $AssineeID);
		$send_email 	 = self::set_up_query_email ($AssineeID, 'Maintanance Query Assigned');

		return array('email_sent' => $send_email, 'record_saved' => $assign);
	}

	static public function get_assign_email () {

	}

	static public function assign_billing_user ($ID, $AssineeID) {

		// get user info
		
		$save_bill 		 = parent::assign_billing_user_db ($ID, $AssineeID);
		$send_email 	 = self::set_up_query_email ($AssineeID, 'Billing Query Assigned');

		return array('email_sent' => $send_email, 'record_saved' => $save_bill);
	}

	static public function assign_queries_user ($ID, $AssineeID) {

		// get user info
		$send_email 	 = self::set_up_query_email ($AssineeID, 'Query Assigned');

		$save_bill 		 = parent::assign_queries_user_db ($ID, $AssineeID);

		return array('status' => $save_bill, 'email_sent' => $send_email, 'record_saved' => $save_bill);
	}

	static public function assign_job_user ($ID, $AssineeID) {

		// get user info
		$send_email 	 = self::set_up_query_email ($AssineeID, 'Query Assigned');
		$save_bill 		 = parent::assign_job_user_db ($ID, $AssineeID);

		return array('status' => $save_bill, 'email_sent' => $send_email, 'record_saved' => $save_bill);
	}



	static public function set_up_query_email ($user_id, $subject) {

		$u  	 		 = self::get_user_byid ($user_id);

		// die(var_dump($u));

		$username 	 	 = $u['contactEmail'];
		$password  	 	 = $u['password'];

		$first_name 	 = $u['firstName'];
		$last_name  	 = $u['lastName'];
		$email  		 = $u['contactEmail'];

		$full_name 		 = $first_name .' '. $last_name;

		$html  	  = file_get_contents('public/emails/assign_email.html'); // external css

		$ret_html = preg_replace('/[\[{\(]first_name+[\]}\)]/' , $full_name, $html);
		$ret_html = preg_replace('/[\[{\(]username+[\]}\)]/' , $username, $ret_html);
		$ret_html = preg_replace('/[\[{\(]password+[\]}\)]/' , $password, $ret_html);

		


		$send_email 	 = self::send_email ($ret_html, $subject, $email, $full_name);

		return $send_email;
	}

	static public function save_comment ($Comment, $ID) {
		return parent::save_comment_db ($Comment, $ID);
	}

	static public function save_resident_comment (
										$Message, 
										$ResID, 
										$FileData, 
										$UserID, 
										$PropertyID
									) {
		return parent::save_resident_comment_db (
							$Message, 
							$ResID, 
							$FileData, 
							$UserID, 
							$PropertyID
						);
	}

	static public function save_resident_email (
										$Message, 
										$ResID, 
										$FileData, 
										$UserID, 
										$PropertyID
									) {
		return parent::save_resident_email_db (
							$Message, 
							$ResID, 
							$FileData, 
							$UserID, 
							$PropertyID
						);
	}

	static public function save_properties_email (
									$Message, 
									$SendTo, 
									$Subject, 
									$EmailMood, 
									$FileName, 
									$UserID, 
									$PropertyID
								) {
		return parent::save_properties_email_db (
									$Message, 
									$SendTo, 
									$Subject, 
									$EmailMood, 
									$FileName, 
									$UserID, 
									$PropertyID
								);
	}

	static public function save_billing_comment ($Comment, $ID) {
		return parent::save_billing_comment_db ($Comment, $ID);
	}

	static public function save_queries_comment ($Comment, $ID) {
		return parent::save_queries_comment_db ($Comment, $ID);
	}

	static public function get_billing_list ($prop_id) {
		return parent::get_billing_list_db($prop_id);
	}

	static public function get_queries_list ($prop_id) {
		return parent::get_queries_list_db($prop_id);
	}
	
	static public function get_all_billing () {
		return parent::get_all_billing_db();
	}

	static public function get_recent_maintenance_list () {
		return parent::get_recent_maintenance_list_db();
	}

	static public function get_recent_billing_list () {
		return parent::get_recent_billing_list_db();
	}

	static public function get_most_active_rep() {
		return parent::get_most_active_rep_db();
	}

	static public function get_all_device_tokens () {
		$users 			= self::get_all_users ();

		$device_token 	= array();
		foreach ($users as $u) {
			$device_token[] = $u['deviceToken'];
		}

		return $device_token;
	}

	static public function get_survey_list_level ($level_num, $store_id) {
		return parent::get_survey_list_level_db ($level_num, $store_id);
	}

	static public function array_sort($array, $on, $order=SORT_ASC){

	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	                break;
	            case SORT_DESC:
	                arsort($sortable_array);
	                break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }

	    return $new_array;
	}

	public function array_orderby () {
		$args = func_get_args ();
		$data = array_shift ($args);

		foreach($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach($data as $key => $row) $tmp[$key] = $row[$field];
				$args[$n] = $tmp;
			}
		}

		$args[] = & $data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}

	static public function send_smses ($message, $cells) {
		$data 	 = 'username='  . self::$sms_username;
		$data 	.= '&password=' . self::$sms_password;
		$data 	.= '&message='  . urlencode($message);
		$data 	.= '&msisdn='   . $cells;

		$params = array('http'      => array(
			    'method'       => 'POST',
			    'content'      => $data,
			    ));


		if (self::$sms_headers !== null) {
		    $params['http']['header'] = self::$sms_headers;
		}

		$ctx 	= stream_context_create($params);


		$response = @file_get_contents(self::$sms_gateway, false, $ctx);
		// if ($response === false) {
		//     print "Problem reading data from $url, No status returned\n";
		// }

		return $response;
	}

	static public function upload_file ($file, $dir) {

		// die(var_dump($file));

		// Get filename.
		$temp 		 	= explode(".", $file["name"]);
		// Generate new random name.
	    $name 			= $temp[0] .'_'.time().'.'.$temp[1];
	    // Save file in the uploads folder.
	    move_uploaded_file ($file["tmp_name"], getcwd(). '/' .$dir .'/'. $name);

		return $name;
	}


	static public function upload_file_extention ($file, $dir, $extentions) {

		// die(var_dump($file));

		// Get filename.
		$temp 		 	= explode(".", $file["name"]);
		// Generate new random name.
	    $name 			= $temp[0] .'_'.time().'.'.$temp[1];

	    $file_ext 		= $temp[1];

	    // die(var_dump(in_array ($file_ext, $extentions)));

	    if (!in_array ($file_ext, $extentions)) {
	    	return false;
	    }


	    // Save file in the uploads folder.
	    move_uploaded_file ($file["tmp_name"], getcwd(). '/' .$dir .'/'. $name);

		return $name;
	}

	static public function upload_form_file ($file, $dir) {

		$tmp_name 		= $file["tmp_name"];
		$file_name 		= $file["name"];

		if (!file_exists($tmp_name)) {
			return false;
		}

		// Get filename.
		$temp 		 	= explode(".", $file_name);
		// Generate new random name.
	    $name 			= $temp[0] .'_'.time().'.'.$temp[1];
	    // Save file in the uploads folder.
	    move_uploaded_file ($tmp_name, getcwd(). '/' .$dir .'/'. $name);

		return $name;
	}

	static public function upload_file_name ($file, $name, $dir) {

		// Get filename.
		$temp 		 	= explode(".", $file["name"]);
		// Generate new random name.
	    // Save file in the uploads folder.
	    
	    $upload 		= move_uploaded_file ($file["tmp_name"], getcwd(). '/' .$dir .'/'. $name);

	    if($upload){
	    	return true;
	    }else{
	    	return $upload;
	    }

	}



	static public function send_sms ($message, $cell, $company_id = 0) {
		$sendsms = new SendSMS("warrencanning", "sandpiper121", "3573616");

		if  (  ($sendsms->login()) == 0  ) {
		   return array('status' => false, 'text' => 'Login failed');
		}

		$send 		= $sendsms->send($cell, $message);
		$number_sms = substr_count($cell, ",");

		if ($send === true) {
			// update smses
			parent::update_credit_number_db ($number_sms, $company_id);
			return array('status' => true, 'text' => 'Send status: ' . $send);
		}else{
			return array('status' => false, 'text' => 'Error Sending SMS:  ' . $send);
		}

		
	}

	static public function send_emails ($Message, $Subject, $From, $emails, $attachment = array()) {

		$mail 				= new PHPMailer;

		// die(var_dump(stripslashes($Message)));

		$mail->isSMTP(); // Set mailer to use SMTP
		$mail->Host 		= self::$email_server;  // Specify main and backup SMTP servers
		$mail->SMTPAuth 	= true; // Enable SMTP authentication
		$mail->SMTPKeepAlive= true;  
		$mail->Username 	= self::$email_username; // SMTP username
		$mail->Password 	= self::$email_password; // SMTP password
		// $mail->SMTPSecure 	= 'ssl'; // Enable TLS encryption, `ssl` also accepted
		$mail->Port 		= self::$email_port; // TCP port to connect to
		$mail->Encoding 	= 'base64';
		$mail->CharSet 		= 'utf-8';

		$mail->From 		= self::$email_username;
		$mail->FromName 	= 'ConnectLIVING';
		
		$mail->addReplyTo(self::$email_username, 'ConnectLIVING');
		$mail->addCC(self::$email_username);

		$mail->isHTML(true); // Set email format to HTML

		$mail->Subject 		= $Subject;

		$mail->msgHTML(stripslashes($Message));
		// $mail->AltBody = $mail->html2text($Message);
		if(!empty($attachment)){

			$mail->AddAttachment (
						$attachment['path'], 
						$attachment['filename'],
						$attachment['encoding'],
						$attachment['type']
					);
		}

		$errors = '';



		foreach ($emails as $e) {
			$email 			= $e['email'];
			$full_name 		= $e['full_name'];
			// die(var_dump($email));


			
			$mail->addAddress ($email, $full_name); // Add a recipient

			if(!$mail->Send()) {
				$errors .=  " Mailer Error: " . $mail->ErrorInfo;
			}

			$mail->ClearAllRecipients();
			// $mail->clearAttachments();
		}

		return $errors;
	}


	static public function send_email ($Message, $Subject, $To, $ToName) {

		// die(print_r(self::get_process_html ($Message)));

		$mail = new PHPMailer;

		$mail->isSMTP(); // Set mailer to use SMTP
		$mail->Host 		= self::$email_server;  // Specify main and backup SMTP servers
		$mail->SMTPAuth 	= true; // Enable SMTP authentication
		// $mail->SMTPDebug 	= 1;
		$mail->Username 	= self::$email_username; // SMTP username
		$mail->Password 	= self::$email_password; // SMTP password
		// $mail->SMTPSecure 	= 'ssl'; // Enable TLS encryption, `ssl` also accepted
		$mail->Port 		= self::$email_port; // TCP port to connect to
		$mail->Encoding 	= 'base64';
		$mail->CharSet 		= 'utf-8';

		$mail->From 		= 'manage@connectliving.co.za';
		$mail->FromName 	= 'ConnectLIVING';

		$mail->addAddress ($To, $ToName); // Add a recipient
		
		$mail->addReplyTo('manage@connectliving.co.za', 'ConnectLIVING - Assigned Task');

		$mail->isHTML(true); // Set email format to HTML

		$mail->Subject    	= $Subject;
		$mail->Body       	= $Message;

		$mail->ClearAllRecipients();
		$mail->addAddress ($To, $ToName); // Add a recipient

		if(!$mail->send()) {
		    return 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
		    return 'Message has been sent';
		}
	}

	static public function get_process_html ($html) {

		$return_data 	= array();
		$doc 			= new DOMDocument();

		@$doc->loadHTML($html);

		$tags 	= $doc->getElementsByTagName('img');

		$base 	= array();
		$cids 	= array();
		$count 	= 1;
		foreach ($tags as $tag) {

		   $src_data = $tag->getAttribute('src');

	       $src 	= 'cid:image' . $count;
	       $base[] 	= $src_data; // get all data url
	       $cids[] 	= $src;

	       // change data url
	       $tag->setAttribute('src', $src);
	       $newhtml = $doc->saveHTML();

	       $count++;
		}

		$return_data = array(
			'html' => $newhtml,
			'cids' => $cids,
			'base' => $base
			);

		return $return_data;

	}

	// One signal message //
	static public function send_push_notification ($message, $device_tokens) {
		self::$app_id;
		$content = array(
					"en" => $message
					);

		$fields = array(
					'app_id' => self::$app_id,
					'include_player_ids' => $device_tokens,
					'contents' => $content
				);

		$fields = json_encode($fields);
	    // print ("\nJSON sent:\n");
	    // print ($fields);
		
		$ch = curl_init ();
		curl_setopt ($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic YTZiMmRjYjQtNzJjMC00YzA1LTkxMGMtZmVjMThlOWVmYzYy'));
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt ($ch, CURLOPT_HEADER, FALSE);
		curl_setopt ($ch, CURLOPT_POST, TRUE);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close ($ch);
		
		return $response;
	}
	
	static public function push_notification ($message, $device_token, $method) {
		
		define('PW_DEBUG', true);

		$data 		=  array(
					    'application' => self::$pw_application,
					    'auth' => self::$pw_auth,
					    'notifications' => array(
					            array(
					                'send_date' => 'now',
					                'content' => $message,
					                'devices' => $device_token,
					                'ios_badges' => 1
					            )
					        )
					    );

		$url 		= 'https://cp.pushwoosh.com/json/1.3/' . $method;
		$request 	= json_encode(['request' => $data]);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		
		$response 	= curl_exec($ch);
		$info 		= curl_getinfo($ch);
		curl_close($ch);

		// $responce   = '3';
		
		// if (defined('PW_DEBUG') && PW_DEBUG) {
		//     $responce .= "[PW] request: $request\n";
		//     $responce .= "[PW] response: $response\n";
		//     $responce .= "[PW] info: " . print_r($info, true);
		// }

		return $response;
	}

	static public function get_today_checkin() {
		return parent::get_today_checkin_db();
	}

	static public function get_promotion_info() {
		return parent::get_promotion_info_db();
	}

	static public function get_promos() {
		return parent::get_promos_db();
	}

	static public function get_compliance_promos() {
		return parent::get_compliance_promos_db();
	}

	static public function get_all_stores() {
		return parent::get_all_stores_db();
	}

	static public function get_level_3_stores() {
		return parent::get_level_3_stores_db();
	}

	static public function get_query_types($category) {
		return parent::get_query_types_db ($category);
	}

	static public function get_perm_all() {
		return parent::get_perm_all_db();
	}

	static public function get_all_users() {
		return parent::get_all_users_db();
	}

	static public function get_all_admin_users() {
		return parent::get_all_admin_users_db();
	}

	static public function get_property_app_users ($property_id) {
		return parent::get_property_app_users_db ($property_id);
	}

	static public function get_app_users_byid ($user_id) {
		return parent::get_app_users_byid_db ($user_id);
	}

	static public function bock_user ($ID) {
		return parent::bock_user_db ($ID);
	}
	
	static public function unbock_user ($ID) {
		return parent::unbock_user_db ($ID);
	}

	static public function get_suppliers_byid ($supplier_id) {
		return parent::get_suppliers_byid_db ($supplier_id);
	}

	static public function get_all_admin_managers($company_id = NULL) {
		return parent::get_all_admin_managers_db($company_id);
	}

	static public function save_query ($QueryType, $AssineeID, $Query, $Property, $Unit, $Image) {
		return parent::save_query_db ($QueryType, $AssineeID, $Query, $Property, $Unit, $Image);
	}

	static public function save_new_comment ($Comment, $QueryID, $UserID, $FileName) {
		return parent::save_new_comment_db ($Comment, $QueryID, $UserID, $FileName);
	}

	static public function do_convert_user ($user_id,
											$company_id,
											$property_id,
											$unit_no,
											$fullname,
											$cellphone,
											$email) {
		return parent::do_convert_user_db ($user_id,
											$company_id,
											$property_id,
											$unit_no,
											$fullname,
											$cellphone,
											$email);
	}

	static public function insert_job_comment($Comment, $JobID, $UserID, $FileName) {
		return parent::insert_job_comment_db ($Comment, $JobID, $UserID, $FileName);
	}

	static public function insert_job_quote ($JobID, $FileName) {
		return parent::insert_job_quote_db ($JobID, $FileName);
	}

	static public function insert_job_chart_url ($job_id, $chat_url) {
		return parent::insert_job_chart_url_db ($job_id, $chat_url);
	}

	static public function get_app_registration_by_device ($device_token) {
		return parent::get_app_registration_by_device_db ($device_token);
	}

	static public function job_quotes_by_jobid ($job_id) {
		return parent::job_quotes_by_jobid_db ($job_id);
	}

	static public function get_app_registration_by_company ($company_id, $property_id, $unit_number, $device_token) {
		return parent::get_app_registration_by_company_db ($company_id, $property_id, $unit_number, $device_token);
	}

	static public function get_app_registration_by_property ($device_token, $property_id) {
		return parent::get_app_registration_by_property_db ($device_token, $property_id);
	}

	static public function get_app_registration_by_id ($device_token, $property_id) {
		return parent::get_app_registration_by_id_db ($device_token, $property_id);
	}

	static public function get_app_registration_by_userid ($user_id) {
		return parent::get_app_registration_by_userid_db ($user_id);
	}

	static public function updated_app_user (
								$user_id,
								$full_name,
								$email,
								$mobile_number,
								$unit_number
							) {
		return parent::updated_app_user_db (
								$user_id,
								$full_name,
								$email,
								$mobile_number,
								$unit_number
							);
	}

	static public function get_money_value ($num) {

		return 'R' . number_format((double)$num, 0);
	}

	static public function get_service_jobs ($prop_id, $company_id) {
		return parent::get_service_jobs_db ($prop_id, $company_id);
	}

	static public function get_service_jobs_byid ($id) {
		return parent::get_service_jobs_byid_db ($id);
	}

	static public function insert_app_reg (
								$company_id,
								$property_id,
								$unit_number,
								$full_name,
								$cellphone,
								$email,
								$user_type,
								$device_token,
								$player_id
								) {

		return parent::insert_app_reg_db (
								$company_id,
								$property_id,
								$unit_number,
								$full_name,
								$cellphone,
								$email,
								$user_type,
								$device_token,
								$player_id);
	}

	static public function delete_app_reg (
								$device_token,
								$property_id
							) {

		return parent::delete_app_reg_db (
								$device_token,
								$property_id);
	}


	static public function get_enum_values ($table, $field) {
		$type = parent::get_enum_values_db ($table, $field);

		return  explode("','", preg_replace("/(enum|set)\('(.+?)'\)/", "\\2", $type));
	}

	static public function insert_service_on_demand (
								$property_id,
								$company_id,
								$service_id,
								$user_id,
								$request_detail,
								$file_name
							) {

		return parent::insert_service_on_demand_db (
								$property_id,
								$company_id,
								$service_id,
								$user_id,
								$request_detail,
								$file_name);
	}

	static public function insert_booking_on_demand (
								$VenueID,
								$NumberOfAttendees,
								$BookingDate,
								$BookingTimeFrom,
								$BookingTimeTo,
								$Requirements
							) {

		return parent::insert_booking_on_demand_db (
								$VenueID,
								$NumberOfAttendees,
								$BookingDate,
								$BookingTimeFrom,
								$BookingTimeTo,
								$Requirements);
	}

	static public function insert_service_types ($SupplierTypeName) {
		return parent::insert_service_types_db ($SupplierTypeName);
	}

	static public function write_to_log_file ($content, $dir = SERVER_ROOT, $file_name = 'app_log.txt') {

		$text 	= '[' . date('Y-m-d H:i:s') . '] ' . $content . PHP_EOL;

		$fp 	= fopen ($dir . "/" . $file_name, "a");
		$right 	= fwrite ($fp, $text);
		fclose ($fp);

		return $right;
	}

	static public function insert_credits ($company_id, $CreditNumber, $transaction_id) {
		return parent::insert_credits_db ($company_id, $CreditNumber, $transaction_id);
	}

	static public function get_service_types_by_id ($ID) {
		return parent::get_service_types_by_id_db ($ID);
	}

	static public function save_billing_query ($QueryType, $UserID, $AssineeID, $Query, $Property, $Unit) {
		return parent::save_billing_query_db($QueryType, $UserID, $AssineeID, $Query, $Property, $Unit);
	}

	static public function save_queries_query ($QueryType, $AssineeID, $Query, $Property, $Unit) {
		return parent::save_queries_query_db($QueryType, $AssineeID, $Query, $Property, $Unit);
	}

	static public function get_strike_rates_by_storeid ($id) {
		return parent::get_strike_rates_by_storeid_db ($id);
	}

	static public function get_paf_compliance ($id) {
		return parent::get_paf_compliance_db ($id);
	}

	static public function get_image_by_id ($id) {
		return parent::get_image_by_id_bd ($id);
	}

	static public function get_comp_image_by_id ($id) {
		return parent::get_comp_image_by_id_bd ($id);
	}

	static public function get_strike_rates ($promo_id) {
		return parent::get_strike_rates_db ($promo_id);
	}

	static public function get_strike_rates_province ($promo_id, $province) {
		return parent::get_strike_rates_province_db ($promo_id, $province);
	}

	static public function get_strike_rates_byreason ($promo_id, $reason) {
		return parent::get_strike_rates_byreason_db ($promo_id, $reason);
	}

	static public function get_questions_bysurveyid ($survey_id) {
		return parent::get_questions_bysurveyid_db ($survey_id);
	}

	static public function get_permission_byid ($ID) {
		return parent::get_permission_byid_db ($ID);
	}

	static public function get_store_by_levelid ($level_id, $level_name) {
		return parent::get_store_by_levelid_db($level_id, $level_name);
	}

	static public function get_last_bin ($promo_id, $level_name, $level_id) {
		return parent::get_last_bin_bd ($promo_id, $level_name, $level_id);
	}

	static public function get_hierarchy ($store_id) {
		return parent::get_hierarchy_db ($store_id);
	}

	static public function get_promotions ($ids) {
		return parent::get_promotions_db ($ids);
	}

	static public function get_survey_list ($ids) {
		return parent::get_survey_list_db ($ids);
	}

	static public function get_responces_bysurveyid ($id) {
		return parent::get_responces_bysurveyid_db ($id);
	}

	static public function get_responces_byrepid($id, $rep_id) {
		return parent::get_responces_byrepid_db ($id, $rep_id);
	}

	static public function get_stores_byid ($id) {
		return parent::get_stores_byid_db ($id);
	}


	static public function get_survey_byid ($id) {
		return parent::get_survey_byid_db ($id);
	}

	static public function get_all_events ($prop_id) {
		return parent::get_all_events_db ($prop_id);
	}

	static public function get_locations_byid ($store_id) {
		return parent::get_locations_byid_db ($store_id);
	}

	static public function get_locations_byuserid ($user_id) {
		return parent::get_locations_byuserid_db ($user_id);
	}

	static public function get_repinfo_byid ($store_id) {
		return parent::get_repinfo_byid_db ($store_id);
	}

	static public function get_rep_byid ($user_id) {
		return parent::get_rep_byid_db ($user_id);
	}

	static public function insert_survey_responce(
												$form_id,
												$resp_id,
												$prop_id,
												$unit_no,
												$full_name,
												$cellphone,
												$q_num,
												$responce
										) {
		return parent::insert_survey_responce_db (
										$form_id,
										$resp_id,
										$prop_id,
										$unit_no,
										$full_name,
										$cellphone,
										$q_num,
										$responce
									);
	}

	static public function insert_emergency_contact ($PropID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor) {


		return parent::insert_emergency_contact_db ($PropID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor);
	}

	static public function update_emergency_contact ($ID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor) {


		return parent::update_emergency_contact_db ($ID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor);
	}

	static public function delete_emergency_contact ($ID) {


		return parent::delete_emergency_contact_db ($ID);
	}

	static public function delete_credits ($transaction_id) {
		return parent::delete_credits_db ($transaction_id);
	}

	static public function activate_credits ($transaction_id) {
		return parent::activate_credits_db ($transaction_id);
	}

	static public function insert_new_document (
										$prop_id,
										$file_name,
										$DocumentType
									) {

		return parent::insert_new_document_db (
										$prop_id,
										$file_name,
										$DocumentType
									);
	}

	static public function insert_new_venue (
										$prop_id,
										$file_name,
										$VenueName
									) {

		// 

		return parent::insert_new_venue_db (
										$prop_id,
										$file_name,
										$VenueName
									);
	}

	static public function insert_new_venue_times (
												$venue_id,
												$day,
												$timeopen,
												$timeclose
											) {

		// 

		return parent::insert_new_venue_times_db (
										$venue_id,
										$day,
										$timeopen,
										$timeclose
									);
	}

	static public function insert_new_document_category ($DocumentTypeName) {

		return parent::insert_new_document_category_db ($DocumentTypeName);
	}

	static public function update_document_category (
									$ID,
									$DocumentTypeName
								) {

		return parent::update_document_category_db ($ID, $DocumentTypeName);
	}

	static public function update_sms_use ($number_cells, $company_id) {

		return parent::update_sms_use_db ($number_cells, $company_id);
	}

	static public function update_supplier_category (
									$ID,
									$SupplierTypeName
								) {

		return parent::update_supplier_category_db ($ID, $SupplierTypeName);
	}

	static public function insert_minutes (
										$prop_id,
										$file_name,
										$MeetingDate
									) {

		return parent::insert_minutes_db (
										$prop_id,
										$file_name,
										$MeetingDate
									);
	}

	static public function insert_trustee_event (
										$prop_id,
										$title,
										$start,
										$end
									) {

		return parent::insert_trustee_event_db (
										$prop_id,
										$title,
										$start,
										$end
									);
	}

	static public function insert_reminder_event (
										$company_id,
										$user_id,
										$title,
										$start,
										$end
									) {

		return parent::insert_reminder_event_db (
										$company_id,
										$user_id,
										$title,
										$start,
										$end
									);
	}

	static public function update_trustee_event (
										$id,
										$title,
										$start,
										$end
									) {

		return parent::update_trustee_event_db (
										$id,
										$title,
										$start,
										$end
									);
	}

	static public function update_document_file (
											$ID,
											$prop_id,
											$file_name,
											$DocumentType
										) {
		return parent::update_document_file_db (
											$ID,
											$prop_id,
											$file_name,
											$DocumentType
										);
	}

	static public function update_document (
										$ID,
										$prop_id,
										$DocumentType
									) {
		return parent::update_document_db (
										$ID,
										$prop_id,
										$DocumentType
									);
	}

	
	static public function get_all_documents ($prop_id) {
		return parent::get_all_documents_db ($prop_id);
	}

	static public function get_rules_documents ($prop_id) {
		return parent::get_rules_documents_db ($prop_id);
	}


	static public function get_images_by_storeid ($store_id) {
		return parent::get_images_by_storeid_bd ($store_id);
	}

	static public function get_document_by_id ($ID) {
		return parent::get_document_by_id_bd  ($ID);
	}

	static public function get_document_type_by_id ($ID) {
		return parent::get_document_type_by_id_bd  ($ID);
	}

	static public function get_images_by_userid ($user_id) {
		return parent::get_images_by_userid_bd ($user_id);
	}

	static public function get_images_per_page ($user_id, $limit, $offset) {
		return parent::get_images_per_page_bd ($user_id, $limit, $offset);
	}

	static public function get_images_per_store_page ($store_id, $limit, $offset) {
		return parent::get_images_per_store_page_bd ($store_id, $limit, $offset);
	}

	static public function get_cyledetails ($user_id) {
		return parent::get_cyledetails_db ($user_id);
	}

	static public function count_images_by_userid ($user_id) {
		return parent::count_images_by_userid_db ($user_id);
	}

	static public function count_images_by_storeid ($user_id) {
		return parent::count_images_by_storeid_db ($user_id);
	}

	static public function get_rep_all () {
		return parent::get_rep_all_db ();
	}

	static public function get_users_all ($company_id = NULL) {
		return parent::get_users_all_db ($company_id);
	}

	static public function get_user_types() {
		return parent::get_user_types_db ();
	}

	static public function get_provinces () {
		return parent::get_provinces_db ();
	}


	static public function get_propery_array ($module) {



		$html 		= '';
		$return 	= array();
		$properties = self::get_property_list();

		foreach ($module as $key => $value) {
			switch ($value) {
				case is_numeric($value) :
					$property 	= self::filter_by_value($properties, 'propertyID', $value);

					foreach ($property as $p) {
						$prop_name 	= $p['propertyName'];
					}

					$return[] = $value;
				break;
			}
		}

		return $return;
	}


	/*** Build aside menu ***/
	static public function get_aside_menu ($module, $page) {

		$html 		= '';
		$validate 	= array();
		$properties = self::get_property_list();

		foreach ($module as $key => $value) {
			switch ($value) {
				case 'Dashboard':

					$html .= '<li>';
					$html .= '<a md-ink-ripple href="Dashboard" ';

					if ($page == 'dashboard') {
						$html .= 'class="current name"';
					}else{
						$html .= 'class="name"';
					}

					$html .= ' >';
					
					$html .= '<i class="icon mdi-action-settings-input-svideo i-20"></i>';
					$html .= '<span class="font-normal">Dashboard</span>';
					$html .= '</a>';
					$html .= '</li>';

					array_push($validate, 'dashboard');
					break;
					case 'Calendar':

					$html .= '<li>';
					$html .= '<a md-ink-ripple href="Calendar" ';

					if ($page == 'calendar') {
						$html .= 'class="current name"';
					}else{
						$html .= 'class="name"';
					}

					$html .= ' >';
					
					$html .= '<i class="icon mdi-action-event i-20"></i>';
					$html .= '<span class="font-normal">Calendar</span>';
					$html .= '</a>';
					$html .= '</li>';

					array_push($validate, 'calendar');
					break;

				
				case 'EmergencyLog':
					$html .= '<li>';
					$html .= '<a md-ink-ripple href="EmergencyLog" ';

					if ($page == 'emergency_log') {
						$html .= 'class="current name"';
					}else{
						$html .= 'class="name"';
					}

					$html .= ' >';
					
					$html .= '<i class="icon mdi-content-sort i-20"></i>';
					$html .= '<span class="font-normal">Emergency Log</span>';
					$html .= '</a>';
					$html .= '</li>';

					array_push($validate, 'emergency_log');
					break;

				case 'AllQueries':
					$html .= '<li>';
					$html .= '<a md-ink-ripple href="AllQueries" ';

					if ($page == 'all_queries') {
						$html .= 'class="current name"';
					}else{
						$html .= 'class="name"';
					}

					$html .= ' >';
					
					$html .= '<i class="icon mdi-device-multitrack-audio i-20"></i>';
					$html .= '<span class="font-normal">All Queries</span>';
					$html .= '</a>';
					$html .= '</li>';

					array_push($validate, 'all_queries');
				break;

				case 'AllJobs':
					$html .= '<li>';
					$html .= '<a md-ink-ripple href="AllJobs" ';

					if ($page == 'all_jobs') {
						$html .= 'class="current name"';
					}else{
						$html .= 'class="name"';
					}

					$html .= ' >';
					
					$html .= '<i class="icon mdi-device-multitrack-audio i-20"></i>';
					$html .= '<span class="font-normal">All Jobs</span>';
					$html .= '</a>';
					$html .= '</li>';

					array_push($validate, 'all_jobs');
				break;
				case 'Notifications':
					$html .= '<li>';
					$html .= '<a md-ink-ripple href="Notifications" ';

					if ($page == 'notifications') {
						$html .= 'class="current name"';
					}else{
						$html .= 'class="name"';
					}

					$html .= ' >';
					
					$html .= '<i class="icon mdi-toggle-radio-button-on i-20"></i>';
					$html .= '<span class="font-normal">Notifications</span>';
					$html .= '</a>';
					$html .= '</li>';

					array_push($validate, 'notifications');
					break;

				case 'Admin':
					$html .= '<li ';
					if ($page == 'admin_users') {
						$html .= ' class="active" ';
					}
					$html .= ' >';
					$html .= '<a md-ink-ripple class="name" >';
					$html .= '<span class="pull-right text-muted">';
					$html .= '<i class="fa fa-caret-down"></i>';
					$html .= '</span>';
					$html .= '<i class="icon mdi-social-group i-20"></i>';
					$html .= '<span class="font-normal">Admin</span>';
					$html .= '</a>';
					$html .= '<ul class="nav nav-sub">';
					$html .= '<li>';
					$html .= '<a md-ink-ripple href="AdminUsers" ';

					if ($page == 'admin_users') {
						$html .= 'class="current name"';
					}else{
						$html .= 'class="name"';
					}

					$html .= ' >Users</a>';
					$html .= '</li>';

					
					
					$html .= '</ul>';
					array_push($validate, 'admin');
					break;
				default:
					if (is_numeric($value)) {
						/*** get property info ***/

						// die(var_dump($properties));
						
						$property 	= self::filter_by_value($properties, 'propertyID', $value);

						
						foreach ($property as $p) {
							$prop_name 	= $p['propertyName'];
							$prop_id 	    = $value;

							$prop_array[]   = array('prop_name' => $prop_name, 'prop_id' => $prop_id );
						}
						
					}
					break;
			}

			
		}

		// Property
		$prop 			= self::array_sort ($prop_array, 'prop_name', SORT_ASC);

		// die(var_dump($prop));
		foreach ($prop as $p) {
			$prop_name 	= $p['prop_name'];
			$prop_id 	= $p['prop_id'];

			$html 		.= '<li ';
			if (
				($page == 'queries' 				. $prop_id) OR 
				($page == 'jobs' 					. $prop_id) OR
				($page == 'communicate' 			. $prop_id) OR
				($page == 'manage' 					. $prop_id) OR
				($page == 'forms' 					. $prop_id) OR
				($page == 'branding' 				. $prop_id) OR
				($page == 'documentation' 			. $prop_id) OR
				($page == 'contractorsandsuppliers' . $prop_id) OR
				($page == 'assets' 					. $prop_id) OR
				($page == 'trustees' 				. $prop_id) OR
				($page == 'app_registrations' 		. $prop_id) OR
				($page == 'property_venues' 		. $prop_id) OR
				($page == 'emergency_contacts' 		. $prop_id) 
			) {
				$html .= ' class="active" ';
			}
			$html .= ' >';
			$html .= '<a md-ink-ripple class="name" >';
			$html .= '<span class="pull-right text-muted">';
			$html .= '<i class="fa fa-caret-down"></i>';
			$html .= '</span>';
			$html .= '<i class="icon mdi-action-store i-20"></i>';
			$html .= '<span class="font-normal">' .$prop_name. '</span>';
			$html .= '</a>';
			$html .= '<ul class="nav nav-sub">';
			$html .= '<li>';
			$html .= '<a md-ink-ripple href="Queries?prop_id='.$prop_id.'&prop_name='.$prop_name.'"';

			if ($page == 'queries' . $prop_id) {
				$html .= 'class="current"';
			}

			$html .= ' ><i class="fa fa-comments"></i> Queries</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a md-ink-ripple href="Jobs?prop_id='.$prop_id.'&prop_name='.$prop_name.'"';

			if ($page == 'jobs' . $prop_id) {
				$html .= 'class="current"';
			}

			$html .= ' ><i class="fa fa-suitcase"></i> Jobs</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a md-ink-ripple href="Manage?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'manage' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-users"></i> Manage</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a md-ink-ripple href="Communicate?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'communicate' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-bullhorn"></i> Communicate</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a md-ink-ripple href="Forms?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'forms' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-check-square-o"></i> Forms</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a md-ink-ripple href="Branding?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'branding' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-heart"></i> Branding</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a md-ink-ripple href="Documentation?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'documentation' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-file-text-o"></i> Documentation</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a md-ink-ripple href="ContractorsAndSuppliers?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'contractorsandsuppliers' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-wrench"></i> Suppliers</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a md-ink-ripple href="Assets?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'assets' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-qrcode"></i> Assets</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a md-ink-ripple href="Trustees?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'trustees' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-child"></i> Trustees</a>';
			$html .= '</li>';

			//Emergency Contacts
			$html .= '<li>';
			$html .= '<a md-ink-ripple href="EmergencyContacts?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'emergency_contacts' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-ambulance"></i> Emergency Contacts</a>';
			$html .= '</li>';

			//App Registrations
			$html .= '<li>';
			$html .= '<a md-ink-ripple href="AppRegistrations?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'app_registrations' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-android"></i> App Registrations</a>';
			$html .= '</li>';
			
			// Property Venues
			$html .= '<li>';
			$html .= '<a md-ink-ripple href="PropertyVenues?prop_id='.$prop_id.'&prop_name='.$prop_name.'" ';
			if ($page == 'property_venues' . $prop_id) {
				$html .= 'class="current"';
			}
			$html .= ' ><i class="fa fa-university"></i> Property Venues</a>';
			$html .= '</li>';

			$html .= '</ul>';
			array_push($validate, 'property' . $prop_id);
		}

		return array('html'=>$html, 'validate' => $validate);
	}


	/**
	 * Logout the user
	 *
	 */
	static public function log_out_user () {
		// Destroy all the sessions
		session_start();
		session_unset();
		session_destroy();
		
		return true;
	}

	static public function update_admin_user_info ($ID, $FirstName, $Surname, $Email, $CellNumber, $Password, $UserType) {
		return parent::update_admin_user_info_db ($ID, $FirstName, $Surname, $Email, $CellNumber, $Password, $UserType);
	}

	static public function update_promo_info ($ID, $PromoName, $BinNumber) {
		return parent::update_promo_info_db ($ID, $PromoName, $BinNumber);
	}

	

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
		return parent::update_user_permission_db (
				$permission_id,
				$admin_id
			);
	}

	static public function update_user_permission (
							$permission_id,
							$admin_id
							) {
		return parent::update_user_permission_db (
				$permission_id,
				$admin_id
			);
	}

	static public function update_user_perm (
								$admin_id,
								$company_id,
								$permissiontype,
								$modules
							) {
		return parent::update_user_perm_db (
				$admin_id,
				$company_id,
				$permissiontype,
				$modules
			);
	}

	static public function update_user_perm_type (
								$company_id,
								$permissiontype,
								$modules
							) {
		return parent::update_user_perm_type_db (
				$company_id,
				$permissiontype,
				$modules
			);
	}

	static public function add_new_permission (
								$company_id,
								$permission_type,
								$modules
							) {
		return parent::add_new_permission_db (
				$company_id,
				$permission_type,
				$modules
			);
	}

	static public function update_survey_info ($ID, $Title, $Description, $StartDate, $EndDate, $AssignTo, $AssignID) {
		return parent::update_survey_info_db ($ID, $Title, $Description, $StartDate, $EndDate, $AssignTo, $AssignID);
	}

	static public function update_question_info ($ID, $SurveyID, $QNumber, $QText, $Options, $QType) {
		return parent::update_question_info_db ($ID, $SurveyID, $QNumber, $QText, $Options, $QType);
	}

	static public function insert_contractor (
								$PropertyID,
								$ServiceType,
								$CompanyName,
								$Address,
								$PhoneNumbzer,
								$Email
							) {


		return parent::insert_contractor_db (
							$PropertyID,
							$ServiceType,
							$CompanyName,
							$Address,
							$PhoneNumbzer,
							$Email
						);
	}

	static public function insert_asset (
								$PropertyID,
								$ContructorID,
								$AssetName,
								$Description,
								$Make,
								$Location,
								$SerialNumber,
								$CostOfAsset,
								$LastInspected,
								$InspectionDueDate
							) {


		return parent::insert_asset_db (
							$PropertyID,
							$ContructorID,
							$AssetName,
							$Description,
							$Make,
							$Location,
							$SerialNumber,
							$CostOfAsset,
							$LastInspected,
							$InspectionDueDate
						);
	}

	static public function insert_resident (
								$UnitNumber,
								$PropertyID,
								$ResidentName,
								$ResidentPhone,
								$ResidentCellphone,
								$ResidentNotifyEmail,
								$ResidentType,
								$ResidentTrustee
							) {


		return parent::insert_resident_db (
							$UnitNumber,
							$PropertyID,
							$ResidentName,
							$ResidentPhone,
							$ResidentCellphone,
							$ResidentNotifyEmail,
							$ResidentType,
							$ResidentTrustee
						);
	}

	static public function insert_new_form (
								$prop_id,
								$FormName,
								$FormInstructions,
								$form_array
							) {


		return parent::insert_new_form_db (
							$prop_id,
							$FormName,
							$FormInstructions,
							$form_array
						);
	}

	static public function insert_app_query (
								$device_token,
								$property_id,
								$user_id,
								$unit_number,
								$query_type,
								$query_detail,
								$user_name,
								$cell_phone,
								$file_name
							) {


		return parent::insert_app_query_db (
							$device_token,
							$property_id,
							$user_id,
							$unit_number,
							$query_type,
							$query_detail,
							$user_name,
							$cell_phone,
							$file_name
						);
	}

	static public function update_form (
								$FormID,
								$FormName,
								$FormInstructions,
								$form_array
							) {


		return parent::update_form_db (
							$FormID,
							$FormName,
							$FormInstructions,
							$form_array
						);
	}

	static public function update_resident (
								$ID,
								$UnitNumber,
								$ResidentName,
								$ResidentPhone,
								$ResidentCellphone,
								$ResidentNotifyEmail,
								$ResidentType,
								$ResidentTrustee
							) {


		return parent::update_resident_db (
							$ID,
							$UnitNumber,
							$ResidentName,
							$ResidentPhone,
							$ResidentCellphone,
							$ResidentNotifyEmail,
							$ResidentType,
							$ResidentTrustee
						);
	}

	static public function update_asset (
								$AssetID,
								$ContructorID,
								$AssetName,
								$Description,
								$Make,
								$Location,
								$SerialNumber,
								$CostOfAsset,
								$LastInspected,
								$InspectionDueDate
							) {


		return parent::update_asset_db (
							$AssetID,
							$ContructorID,
							$AssetName,
							$Description,
							$Make,
							$Location,
							$SerialNumber,
							$CostOfAsset,
							$LastInspected,
							$InspectionDueDate
						);
	}

	static public function update_contractor (
								$ContractorID,
								$ServiceType,
								$CompanyName,
								$Address,
								$PhoneNumber,
								$Email
							) {

		return parent::update_contractor_db (
							$ContractorID,
							$ServiceType,
							$CompanyName,
							$Address,
							$PhoneNumber,
							$Email
						);
	}


	static public function archive_record ($id) {
		$archive 	= parent::copy_resident_record_db ($id);
		$delete 	= parent::delete_resident_info_db ($id);

		return $archive;
	}

	static public function get_constructors_by_propid ($prop_id) {
		return parent::get_constructors_by_propid_db ($prop_id);
	}

	static public function get_all_emergency_contacts ($prop_id) {
		return parent::get_all_emergency_contacts_db ($prop_id);
	}

	static public function insert_promo_info ($PromoName, $BinNumber){
		return parent::insert_promo_info_db ($PromoName, $BinNumber);
	}

	static public function save_admin_user ($FirstName, $Surname, $Email, $CellNumber, $Password, $UserType) {
		return parent::save_admin_user_db ($FirstName, $Surname, $Email, $CellNumber, $Password, $UserType);
	}

	static public function save_user_perm ($permission_type, $modules) {
		return parent::save_user_perm_db ($permission_type, $modules);
	}

	static public function mark_query_done ($id) {
		return parent::mark_query_done_db ($id);
	}

	static public function mark_query_materials ($id) {
		return parent::mark_query_materials_db ($id);
	}

	static public function mark_query_insurance_claim ($id) {
		return parent::mark_query_insurance_claim_db ($id);
	}

	static public function delete_user_info ($ID) {
		return parent::delete_user_info_db ($ID);
	}
	
	static public function delete_delete_venue ($ID) {
		return parent::delete_delete_venue_db ($ID);
	}



	static public function delete_service_type ($ID) {
		return parent::delete_service_type_db ($ID);
	}

	static public function delete_this_form ($ID) {
		return parent::delete_this_form_db ($ID);
	}

	static public function delete_response_bysubmitid ($ID) {
		return parent::delete_response_bysubmitid_db ($ID);
	}

	static public function delete_document ($ID) {
		return parent::delete_document_db ($ID);
	}

	static public function delete_document_type ($ID) {
		return parent::delete_document_type_db ($ID);
	}

	static public function delete_this_contractor ($ID) {
		return parent::delete_this_contractor_db ($ID);
	}

	static public function convert_query_to_job (
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
		return parent::convert_query_to_job_db (
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
	}

	static public function get_all_suppliers($company_id = false, $prop_id = false) {
		return parent::get_all_suppliers_db ($company_id, $prop_id);
	}

	static public function calculate_credit_balance ($company_id = false) {

		$comm 		= self::get_sms_balance_bycompany ($company_id);

		$balance    = 0;
		foreach ($comm as $c) {
			$balance += $c['credits'];
		}
		return $balance;
	}

	static public function get_sms_balance_bycompany ($company_id)  {
		return parent::get_sms_balance_bycompany_db ($company_id);
	}

	static public function get_sms_credit_bycompany ($company_id)  {
		return parent::get_sms_credit_bycompany_db ($company_id);
	}

	static public function get_prop_suppliers($prop_id) {
		return parent::get_prop_suppliers_db ($prop_id);
	}

	static public function delete_this_asset ($ID) {
		return parent::delete_this_asset_db ($ID);
	}

	static public function delete_notification_info ($ID) {
		return parent::delete_notification_info_db ($ID);
	}

	static public function delete_admin_user_info ($ID) {
		return parent::delete_admin_user_info_db ($ID);
	}

	static public function delete_admin_perm_info ($ID) {
		return parent::delete_admin_perm_info_db ($ID);
	}

	static public function delete_promo_info ($ID) {
		return parent::delete_promo_info_db ($ID);
	}

	static public function get_responses_byid ($ID) {
		return parent::get_responses_byid_db ($ID);
	}

	static public function get_responce_group_byprop ($property_id) {
		return parent::get_responce_group_byprop_db ($property_id);
	}

	static public function get_responce_byprop ($property_id) {
		return parent::get_responce_byprop_db ($property_id);
	}
	
	static public function get_responce_by_subid ($id) {
		return parent::get_responce_by_subid_db ($id);
	}

	static public function get_all_survey_info ($survey_id) {
		return parent::get_all_survey_info_db ($survey_id);
	}

	static public function delete_survey_info ($ID) {
		return parent::delete_survey_info_db ($ID);
	}

	static public function get_service_supplier ($prop_id) {
		return parent::get_service_supplier_db ($prop_id);
	}

	static public function update_supplier_thum ($supplier_id, $thum_name) {
		return parent::update_supplier_thum_db ($supplier_id, $thum_name);
	}


	static public function get_responce_html ($data) {

		$stylesheet  = file_get_contents('public/styles/report_styles.css'); // external css




		$html 	= '<html>';
		$html 	.= '	<head>';
		$html 	.= '		<style>';
		$html 	.= 				$stylesheet;
		$html 	.= '		</style>';
		$html 	.= '	</head>';
		$html 	.= '<body>';


		$html  .= '
		
					<!--mpdf
					<htmlpageheader name="myheader">
					<table width="100%">
						<tr>
							<td width="100%" style="color:#888;">
								<span style="font-weight: lighter; font-size: 18pt;">
									Form Responses <br />
								</span>
							</td>
						</tr>
					</table>
					</htmlpageheader>

					<htmlpagefooter name="myfooter">
						<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
						Page {PAGENO} of {nb}
						</div>
					</htmlpagefooter>

					<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
					<sethtmlpagefooter name="myfooter" value="on" />
					mpdf-->

					<div style="text-align: right">Date: '.date('jS F Y').'</div>

				';


		$html .= '		<table class="ExcelTable2007"  width="100%">';
		$html .= '			<tr>';
		$html .= '				<th colspan="2">' .$data['form_name']. '</th>';
		$html .= '			</tr>';
		$html .= '			<tr>';
		$html .= '				<td class="thed">Complex Name</td>';
		$html .= '				<td >' .$data['complex_name']. '</td>';
		$html .= '			</tr>';
		$html .= '			<tr>';
		$html .= '				<td class="thed">Resident Name</td>';
		$html .= '				<td >' .$data['res_name']. '</td>';
		$html .= '			</tr>';

		$html .= '			<tr>';
		$html .= '				<td class="thed">Cell Number</td>';
		$html .= '				<td >' .$data['res_cell']. '</td>';
		$html .= '			</tr>';
		$html .= '		</table>';


		

		


		$html .= '		<table class="ExcelTable2007"  width="100%">';
		$html .= '			<thead>';
		$html .= '				<tr>';
		$html .= '					<th align="center">#</th>';
		$html .= '					<th >Question</th>';
		$html .= '					<th >Response</th>';
		$html .= '				</tr>';

		$html .= '			</thead>';

		$html .= '			<tbody>';

		foreach ($data['Responses'] as $d) {
			$html .= '			<tr>';
			$html .= '				<td >' .$d['q_num']. '</td>';
			$html .= '				<td >' .$d['q_text']. '</td>';
			$html .= '				<td >' .$d['responce']. '</td>';
			$html .= '			</tr>';
		}

		$html .= '			</tbody>';

		$html .= '			</table>';






		$html 	.= '<body>';
		$html 	.= '</html>';

		return $html;
	}


	static public function get_pdf_from_image ($url) {

		$stylesheet  = file_get_contents('public/styles/report_styles.css'); // external css




		$html 	= '<html>';
		$html 	.= '	<head>';
		$html 	.= '		<style>';
		$html 	.= 				$stylesheet;
		$html 	.= '		</style>';
		$html 	.= '	</head>';
		$html 	.= '<body>';


		$html  .= '
		
					<!--mpdf
					<htmlpageheader name="myheader">
					<table width="100%">
						<tr>
							<td width="100%" style="color:#888;">
								<span style="font-weight: lighter; font-size: 18pt;">
									Quotation <br />
								</span>
							</td>
						</tr>
					</table>
					</htmlpageheader>

					<htmlpagefooter name="myfooter">
						<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
						Page {PAGENO} of {nb}
						</div>
					</htmlpagefooter>

					<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
					<sethtmlpagefooter name="myfooter" value="on" />
					mpdf-->

					

				';


		$html .= '		<img src="'.$url.'" />';





		$html 	.= '<body>';
		$html 	.= '</html>';

		return $html;
	}

	

	static public function update_survey_responce (
												$form_id,
												$resp_id,
												$prop_id,
												$unit_no,
												$full_name,
												$cellphone,
												$q_num,
												$responce
										) {

		return parent::update_survey_responce_db (
											$form_id,
											$resp_id,
											$prop_id,
											$unit_no,
											$full_name,
											$cellphone,
											$q_num,
											$responce
										);
	}

	static public function delete_question_info ($ID) {
		return parent::delete_question_info_db ($ID);
	}

	static public function get_promo_byid ($ID) {
		return parent::get_promo_byid_db ($ID);
	}

	static public function get_question_byid ($ID) {
		return parent::get_question_byid_db ($ID);
	}

	static public function get_stores_by_province ($province) {
		return parent::get_stores_by_province_db ($province);
	}

	static public function get_user_byid ($user_id) {
		$user = parent::get_user_byid_db ($user_id);
		return $user[0];
	}

	static public function get_user_by_email ($email) {
		$user = parent::get_user_by_email_db ($email);
		return $user[0];
	}


	static public function get_surveys() {
		return parent::get_surveys_db();
	}

	static public function get_questions() {
		return parent::get_questions_db();
	}

	static public function insert_survey_info ($Title, $Description, $StartDate, $EndDate, $AssignTo, $AssignID, $LevelName){
		return parent::insert_survey_info_db ($Title, $Description, $StartDate, $EndDate, $AssignTo, $AssignID, $LevelName);
	}

	static public function insert_question_info ($SurveyID, $QNumber, $QText, $Options, $QType){
		return parent::insert_question_info_db ($SurveyID, $QNumber, $QText, $Options, $QType);
	}


	static public function get_store_by_level ($level_number) {
		$return_array 	= array ();
		$stores 		= self::get_all_stores();

		switch ($level_number) {
			case 3:// Level 3
					$level_array = self::array_remove_dublicates ($stores, 'level3_id');

					foreach ($level_array as $l) {
						$return_array[] = array(
							'level_id' => $l['level3_id'],
							'level_description' => $l['level3_description']
							);
					}
				break;

			case 4:// Level 4
					$level_array = self::array_remove_dublicates ($stores, 'level4_id');

					foreach ($level_array as $l) {
						$return_array[] = array(
							'level_id' => $l['level4_id'],
							'level_description' => $l['level4_description']
							);
					}
				break;

			case 5:// Level 5
					$level_array = self::array_remove_dublicates ($stores, 'level5_id');

					foreach ($level_array as $l) {
						$return_array[] = array(
							'level_id' => $l['level5_id'],
							'level_description' => $l['level5_description']
							);
					}
				break;

			case 6:// Level 6
					$level_array = self::array_remove_dublicates ($stores, 'level6_id');

					foreach ($level_array as $l) {
						$return_array[] = array(
							'level_id' => $l['level6_id'],
							'level_description' => $l['level6_description']
							);
					}
				break;
		}

		return $return_array;
	}

	
	/**
	 * Get view
	 */
	static public function get_view($view, $assigns = array()) {
		$page = new View_Model($view);
		
		// Anything needs to be passed
		if (count($assigns) > 0) {
			// Loop throught the list
			foreach ($assigns as $assignName => $assignValue) {
				// Pass to view
				$page->assign($assignName, $assignValue);
			}
		}
		
		// return $page;
	}

	static public function get_server_name () {
		return $_SERVER['SERVER_NAME'];
	}

	/**
	 * hash password
	 */
	static public function hash_password ($password) {
		
		$hash = hash_hmac('ripemd160', 'The quick brown fox jumped over the lazy dog.', $password);
		
		return $hash;
	}

	/**
	 * Header view
	 */
	static public function get_header ($assigns = array()) {
		$view = self::get_view('Header', $assigns);
	}
	/**
	 * Footer view
	 */
	static public function get_footer ($assigns = array()) {
		return self::get_view('Footer', $assigns);
	}

	/**
	 * session start
	 */
	static public function set_session_start () {
		if (!isset($_SESSION))
			session_start();
		
	}

	/**
	 * redirect
	 *
	 */
	static public function redirect_to($url, $permanent = false) {
		if (headers_sent() === false) {
			header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
		}
		exit();
	}

	/**
	 * redirect
	 *
	 */
	static public function sanitise_string ($string) {
		return parent::clean_string ($string);
	}

	/**
	 * Check if logged in
	 *
	 */
	static public function check_if_logged ($email) {
		
		$validate_return = array();
		$userinfo        = parent::get_userdetails($email);
		
		if (count($userinfo) == 1) { // Email exist
			
			foreach ($userinfo as $user) { // Get user details
				$dbEmail     = $user['email'];
				$dbPassword  = $user['password'];
				$permissions = $user['permission_id'];
				$first_name  = $user['first_name'];
				$last_name   = $user['last_name'];
			}
			
			self::set_session_start();
			
			$login_strg   	 = $_SESSION['login_strg'];
			$check_string 	 = md5 ($dbEmail . '+' . $dbPassword . '+' . $first_name . '+' . $last_name);
			
			if ($login_strg === $check_string) {
				$validate_return = true;
			} else {
				
				$validate_return = false;
			}
		} else {
			$validate_return = false;
		}
		
		return $validate_return;
	}


	static public function array_remove_dublicates ($data, $key) {
		$_data 	= array();

		foreach ($data as $v) {
			if (isset($_data[$v[$key]])) {
            // found duplicate
				continue;
			}
          // remember unique item
			$_data[$v[$key]] = $v;
		}
        // if you need a zero-based array, otheriwse work with $_data
        $data = array_values ($_data);
		return $data;
	}
	public function created_directory ($dir) { 
		if (!file_exists($dir)) {
			$ret = mkdir($dir, 0777, true);
		}else{
			$ret = false;
		}

		return $ret;
	}

	public static function filter_by_value ($array, $index, $value){ 
		$newarray = array();
        if(is_array($array) && count($array)>0) { 
            foreach(array_keys($array) as $key){ 
                $temp[$key] = $array[$key][$index]; 
                 
                if ($temp[$key] == $value){ 
                    $newarray[$key] = $array[$key]; 
                } 
            } 
          } 
      return $newarray; 
    }

    public function sort_by_value ($array, $key) {
    	$return = array();
    	foreach($array as $val) {
    		$return[$val[$key]][] = $val;
    	}
    	return $return;
    }

    public function compare_dates ($a, $b) {

        $a = strtotime($a[0]);
        $b = strtotime($b[0]);

        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }



    public function prettyPrint( $json ) {

	    $result 			= '';
	    $level 				= 0;
	    $in_quotes 			= false;
	    $in_escape 			= false;
	    $ends_line_level 	= NULL;
	    $json_length 		= strlen( $json );

	    for( $i = 0; $i < $json_length; $i++ ) {
	        $char = $json[$i];
	        $new_line_level = NULL;
	        $post = "";
	        if( $ends_line_level !== NULL ) {
	            $new_line_level = $ends_line_level;
	            $ends_line_level = NULL;
	        }
	        if ( $in_escape ) {
	            $in_escape = false;
	        } else if( $char === '"' ) {
	            $in_quotes = !$in_quotes;
	        } else if( ! $in_quotes ) {
	            switch( $char ) {
	                case '}': case ']':
	                    $level--;
	                    $ends_line_level = NULL;
	                    $new_line_level = $level;
	                    break;

	                case '{': case '[':
	                    $level++;
	                case ',':
	                    $ends_line_level = $level;
	                    break;

	                case ':':
	                    $post = " ";
	                    break;

	                case " ": case "\t": case "\n": case "\r":
	                    $char = "";
	                    $ends_line_level = $new_line_level;
	                    $new_line_level = NULL;
	                    break;
	            }
	        } else if ( $char === '\\' ) {
	            $in_escape = true;
	        }
	        if( $new_line_level !== NULL ) {
	            $result .= "\n".str_repeat( "\t", $new_line_level );
	        }
	        $result .= $char.$post;
	    }

	    return $result;
	}

    /**
     * Validate fields
     * 
     * @param  int $valNum -  What to validate
     * @param  string $value -  Form valuee
     * @return array
     */
    static public function validate_variables ($value, $valNum) {
	    	$return_array = array();
	    	$partten      = '';
	    	
	    	switch ($valNum) {
	    		case 0: // validate cell number
	    			$partten = '/^0[0-9]{9}$/'; // Cellphone parten
	    			
	    			break;
	    		case 1: // validate name surname
	    			$partten = '/^[aA-zZ ]{2,31}$/'; //
	    			
	    			break;
	    		
	    		case 2: // Street number
	    			$partten = '/^[0-9]{1,5}$/'; // Street number
	    			
	    			break;
	    		case 3: // Store name
	    			$partten = '/^[A-Za-z0-9 _.,\'~\-!@#\$%\^&\*\(\)]+/u';
	    			
	    			break;
	    		
	    		case 4: // Postal code
	    			$partten = '/^[0-9]{4}$/'; // Postal code
	    			
	    			break;
	    		case 5: // One Time Pin
	    			$partten = '/^0[0-9]{4,6}$/'; // One Time Pin
	    			
	    			break;
	    		case 6: // Block number
	    			$partten = '/^0[0-9]{1,5}$/';
	    			
	    			break;
	    		case 7: // Target
	    			$partten = '/^[0-9]{1-10}$/';
	    			
	    			break;
	    		case 8: // Username
	    			$partten = '/^[aA-zZ0-9]{2,40}$/'; //
	    			
	    			break;
	    		case 9: // Building number
	    			$partten = '/^[aA-zZ0-9 ]{2,30}$/'; //
	    			
	    			break;
	    		
	    		case 10: // Email
	    			$partten = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
	    			break;
	    		case 11: // Address
	    			$partten = '/[aA-zZ0-9,. ]{2,40}$/'; //
	    			break;
	    		case 12: // Date (03/03/2014)
	    			$partten = '/^(0[1-9]|[12][0-9]|3[01])[\-\/.](0[1-9]|1[012])[\-\/.](19|20)\d\d$/'; //
	    			break;
	    		case 13: // GPS coordinates
	    			$partten = '/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/'; //
	    			break;
	    		case 'date': // vate
	    			$partten = '/^[0-9]{4}[-/][0-9]{1,2}[-/][0-9]{1,2}\$/'; //
	    			break;
	    		
	    		case 14: // yes/no
	    			$partten = '/^[aA-zZ]{2,3}$/'; //
	    			
	    			break;
	    		case 15: // Word
	    			$partten = '/^[\w-]+$/'; //
	    			
	    			break;
	    		case 16: // Word
	    			$partten = '/^[\w-]+$/'; //
	    			
	    			break;
	    		case 17: // Commer separate
	    			$partten = '/^([aA-zZ0-9 ]+)(,\s*[aA-zZ0-9 ]+)*$/'; //
	    			break;

	    		case 18: // mulitiple emails
	    			$partten = '/([_a-z0-9\-]+)(\.[_a-z0-9\-]+)*@([a-z0-9\-]{2,}\.)*([a-z]{2,4})(\s*,\s*([_a-z0-9\-]+)(\.[_a-z0-9\-]+)*@([a-z0-9\-]{2,}\.)*([a-z]{2,4}))*$/'; //
	    			break;
	    		default: //
	    			break;
	    	}
	    	// echo $partten;
	    	
	    	
	    	if (preg_match($partten, $value)) { // Test cell if valid
	    		$return_array = true;
	    	} else {
	    		$return_array = false;
	    	}
	    	
	    	
	    	return $return_array;
	    }
    


    public function get_noimage_base64 () {

    	$image = "data:image/gif;base64,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AAAQAAAAAQAAAAAAEnByaW50T3V0cHV0T3B0aW9ucwAAABcAAAAAQ3B0bmJvb2wAAAAAAENsYnJib29sAAAAAABSZ3NNYm9vbAAAAAAAQ3JuQ2Jvb2wAAAAAAENudENib29sAAAAAABMYmxzYm9vbAAAAAAATmd0dmJvb2wAAAAAAEVtbERib29sAAAAAABJbnRyYm9vbAAAAAAAQmNrZ09iamMAAAABAAAAAAAAUkdCQwAAAAMAAAAAUmQgIGRvdWJAb+AAAAAAAAAAAABHcm4gZG91YkBv4AAAAAAAAAAAAEJsICBkb3ViQG/gAAAAAAAAAAAAQnJkVFVudEYjUmx0AAAAAAAAAAAAAAAAQmxkIFVudEYjUmx0AAAAAAAAAAAAAAAAUnNsdFVudEYjUHhsQFIAAAAAAAAAAAAKdmVjdG9yRGF0YWJvb2wBAAAAAFBnUHNlbnVtAAAAAFBnUHMAAAAAUGdQQwAAAABMZWZ0VW50RiNSbHQAAAAAAAAAAAAAAABUb3AgVW50RiNSbHQAAAAAAAAAAAAAAABTY2wgVW50RiNQcmNAWQAAAAAAAAAAABBjcm9wV2hlblByaW50aW5nYm9vbAAAAAAOY3JvcFJlY3RCb3R0b21sb25nAAAAAAAAAAxjcm9wUmVjdExlZnRsb25nAAAAAAAAAA1jcm9wUmVjdFJpZ2h0bG9uZwAAAAAAAAALY3JvcFJlY3RUb3Bsb25nAAAAAAA4QklNA+0AAAAAABAASAAAAAEAAQBIAAAAAQABOEJJTQQmAAAAAAAOAAAAAAAAAAAAAD+AAAA4QklNBA0AAAAAAAQAAAB4OEJJTQQZAAAAAAAEAAAAHjhCSU0D8wAAAAAACQAAAAAAAAAAAQA4QklNJxAAAAAAAAoAAQAAAAAAAAABOEJJTQP1AAAAAABIAC9mZgABAGxmZgAGAAAAAAABAC9mZgABAKGZmgAGAAAAAAABADIAAAABAFoAAAAGAAAAAAABADUAAAABAC0AAAAGAAAAAAABOEJJTQP4AAAAAABwAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAADhCSU0E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bnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKE1hY2ludG9zaCkiIHhtcDpDcmVhdGVEYXRlPSIyMDE2LTEyLTE0VDA5OjE3OjMyKzAyOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE2LTEyLTE0VDA5OjE3OjMyKzAyOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAxNi0xMi0xNFQwOToxNzozMiswMjowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpjMWU4ZmM4NS04MzNlLTRmYzQtYTJlNS0yMWIyY2M0MWMzYjQiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDowMzljOGU2MC1mMzY3LTExNzktOTVjNy1kYzc1MTY0YWI4NmUiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDozM2Y2YWJkMi01NjQ5LTRiMmUtYTkxMC1iZjczMjhhODlkODAiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIGRjOmZvcm1hdD0iaW1hZ2UvanBlZyI+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MzNmNmFiZDItNTY0OS00YjJlLWE5MTAtYmY3MzI4YTg5ZDgwIiBzdEV2dDp3aGVuPSIyMDE2LTEyLTE0VDA5OjE3OjMyKzAyOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxNCAoTWFjaW50b3NoKSIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6YzFlOGZjODUtODMzZS00ZmM0LWEyZTUtMjFiMmNjNDFjM2I0IiBzdEV2dDp3aGVuPSIyMDE2LTEyLTE0VDA5OjE3OjMyKzAyOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxNCAoTWFjaW50b3NoKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPHBob3Rvc2hvcDpUZXh0TGF5ZXJzPiA8cmRmOkJhZz4gPHJkZjpsaSBwaG90b3Nob3A6TGF5ZXJOYW1lPSJObyBJbWFnZSBBdmFpbGFibGUiIHBob3Rvc2hvcDpMYXllclRleHQ9Ik5vIEltYWdlIEF2YWlsYWJsZSIvPiA8L3JkZjpCYWc+IDwvcGhvdG9zaG9wOlRleHRMYXllcnM+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDw/eHBhY2tldCBlbmQ9InciPz7/7gAOQWRvYmUAZEAAAAAB/9sAhAABAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAgICAgICAgICAgIDAwMDAwMDAwMDAQEBAQEBAQEBAQECAgECAgMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwP/wAARCABoAZADAREAAhEBAxEB/90ABAAy/8QBogAAAAYCAwEAAAAAAAAAAAAABwgGBQQJAwoCAQALAQAABgMBAQEAAAAAAAAAAAAGBQQDBwIIAQkACgsQAAIBAwQBAwMCAwMDAgYJdQECAwQRBRIGIQcTIgAIMRRBMiMVCVFCFmEkMxdScYEYYpElQ6Gx8CY0cgoZwdE1J+FTNoLxkqJEVHNFRjdHYyhVVlcassLS4vJkg3SThGWjs8PT4yk4ZvN1Kjk6SElKWFlaZ2hpanZ3eHl6hYaHiImKlJWWl5iZmqSlpqeoqaq0tba3uLm6xMXGx8jJytTV1tfY2drk5ebn6Onq9PX29/j5+hEAAgEDAgQEAwUEBAQGBgVtAQIDEQQhEgUxBgAiE0FRBzJhFHEIQoEjkRVSoWIWMwmxJMHRQ3LwF+GCNCWSUxhjRPGisiY1GVQ2RWQnCnODk0Z0wtLi8lVldVY3hIWjs8PT4/MpGpSktMTU5PSVpbXF1eX1KEdXZjh2hpamtsbW5vZnd4eXp7fH1+f3SFhoeIiYqLjI2Oj4OUlZaXmJmam5ydnp+So6SlpqeoqaqrrK2ur6/9oADAMBAAIRAxEAPwDZQ9/O710p697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r/0NlD387vXSnr3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuv/R2UPfzu9dKeve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de6/9LZQ9/O710p697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r/09lD387vXSnr3v3Xuve/de697917r3v3Xuve/de697917r3v3XulXtzY+7d3R1Uu2sDXZiOheKOrejVGEDzK7RK+t0sXWNiP9b2OuUPbPnzn6G9uOTuWbi/htmVZTHpojOCVB1MvEAkU9OiHeeaOX+Xngj3rdYrd5QSgevcBQGlAeFR0z5nDZXb+RqcRmqGoxuSpDGKmjqV0TReaKOeLUASLSQyqwIJBB9h/mHl3e+U93u9g5j22W03iDT4kUgAZdSq61oSKMrKwIJBB6MNu3Kw3ezh3DbbpJrKSul1NQaEqf2EEH5jp2oNk7ryeCrNzUGDrarA0AqTWZSNY/tacUcay1PkZpAw8Mbgnj8+z7a/bbnneuWL/AJz2vlu4m5XtRIZbhdPhoIVDSVqwbsUgmgPyr0guuZthsd0t9ku9zjTdZdOiI11NrNFpimSKDPSW9gfo9697917r3v3XunLEYfJ57I0uIw9HLkMlWs6UtHAFMs7RxPO6oGZVJWKJm+v0Hs45f5f3nmnd7LYOX9ve63i4LCOJKanKqzsBqIGFVjx8vXpFuG42W1Wc+4bjcLFZRgFnbgoJCitK+ZA6lbg21ndq1y4zcOMqcTXvTx1S0tUEEpp5XkjjlsjOArvCwHN+Pa3mvk7mfkfc02bmzZ5bHc2hWURyadRjYsqt2lhQlGAzXHTG071te+2pvdovUuLUOV1LWmoAEjIHAEft6Y/Ya6NOve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de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wqOyhVlltmiMhq/hq5JKW25c5g2S3TbuXN+gTalFES4gaZoQePhyJNEWUEkokofSKLqKgAJrdm65NzSY2CDH02FweCozj8Fg6OSWaCgpnlaeoklqZz562vrahjJPO9mka3AAAAN5756l5yl2e1tdph23lnbLfwLKziZnSCMtrkZpH75ppnOuaZqM7UwAAOjrYdhTZUvZZbt7ndLqTxJ5nABkYDSoCr2oiKNKIMKPMkk9RNxZ7+PyYd/tPtP4Tt3D4G3n8/3H8Jpvt/u7+GHxfcfXx+rR9NR+vtBzfzV/WuTl2T6DwP3ftFpY/Hr8T6VNHi/AmnXx0d2nhrbj0o2fav3Su4r9R4n1F5NP8OnT4ratHE108NWK+g6V9VvTbG6Fp599baydZnYKaCmn3JtrN0uHrsulKohp3zNFX4fL0NTWLTgK1TGIpHCLqDHkD+99xuSudltbr3N5PvJ+Zo4UjfcNuu47Wa6EYCRm6hntbmF5QlFadNDuEUMG4gPQctb5sZli5W3qCPa2dmW3uYWlSIsasIXjmidU1VIjbUo1HSRwKQ3BX7crHpU25t+pwdPTrKs0ldmpMzXZAuY/HJUSCjx9HB4ghssMKj1m5awPsA82bryfuL2UfJ/Kc22WsIcO01213NPXTpaQ+FDFHpANFiiUd5DM1FIEO0Wu82yztvO7JdTORQJCIUjpWoUa5HatRUu54YAqek77CHRx1737r3Xvfuvde9+691737r3Xvfuvde9+691737r3X//X2UPfzu9dKeve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917pQbY3Rm9nZmlz236w0WSpCwSTQkscsUg0TU9RDKrRzQTJwykf4ghgCBVyXzrzH7f8w2XM/K1+bfdoKgGgZXRsPHIjAhkcYYHPBlKsFYFO97HtnMW2z7Vu1sJbOSlRUggjIZSMhgcgj7DUEgmCHys3mY0eXbWz5chGmhK1qTJgDlrERDKeRRpY3CyAEkkWBt7ypX78HuCYI3m5O2J91VaCYx3FOJodH1GoChyBKKmpFAaCIz7D8t62VN63FbQmpTVF/h8KnlglTinHj0DO+eyt29iVUFRuTILLDSavssdSRClx1GZABI8NOpYvK4FjJI0kluNVgB7x79y/eHnv3YvLa55u3QPawVMVvEvh28RIoWVKklz5vIzvTtDBcdSPyvyXy/yhBLFs1oVkkprkc6pHpwBbyA/hUKtc0rnpB+4w6FfXvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvdf/Q2UPfzu9dKeve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de6/9HZQ9/O710p697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r/0tlD387vXSnr3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuv/T2UPfzu9dKeve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de6/9k=";

    	return $image;
    }

    public function get_blank_base64 () {
    	$image = "data:image/gif;base64,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";

    	return $image;
    }
}
<?php
/**
 * Communicate Controller
 * 
 * @package 
 * @author  
 */
class CommunicateController
{
	static public $app_controller;
	static public $payfast;
	static public $property_id;
	static public $property_name;

	public function __construct() {
		self::$app_controller 	= new AppController();
		self::$payfast 			= new PayFastController();
		self::$property_id 		= self::$app_controller->sanitise_string($_REQUEST['prop_id']);
		self::$property_name 	= self::$app_controller->sanitise_string($_REQUEST['prop_name']);
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

		// die(var_dump($_SESSION));

		switch ($subRequest) {
			case 'GetAllCommunication':
				$comm 	= self::set_up_all_communicate (self::$property_id);
				return json_encode($comm);
				break;
			case 'GetSMSBalance':
				$company_id = self::$app_controller->sanitise_string($request->parameters['company_id']);

				$balance    = self::$app_controller->calculate_credit_balance ($company_id);
				
				return json_encode(array('credits' => $balance));
				break;
			case 'GetCommunicationByid':
				$ID    		= self::$app_controller->sanitise_string($request->parameters['ID']);
				$Type    	= self::$app_controller->sanitise_string($request->parameters['Type']);
				$comm 		= self::set_up_communicate_by_id (self::$property_id, $ID, $Type);
			return json_encode($comm[0]);
			break;
			case 'BuySMSCredits':
				$callback   		= self::$app_controller->sanitise_string ($request->parameters['callback']);
				$CreditNumber 		= self::$app_controller->sanitise_string($request->parameters['CreditNumber']);
				$AmountDue 			= self::$app_controller->sanitise_string($request->parameters['AmountDue']);
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$prop_name 			= self::$app_controller->sanitise_string($request->parameters['prop_name']);
				$PostVars			= $request->parameters;

				$save 				= self::buy_sms_credits ($CreditNumber, $AmountDue, $company_id, $prop_id, $prop_name);
			return $callback.' ('.json_encode($save).')';
			break;

			


			
			
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 			= $_SESSION['email'];
					$company_id 	= $_SESSION['company_id'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];

					$this_page 		= 'property' . self::$property_id;
					$current 		= 'communicate' . self::$property_id;

					$aside_menu 	= self::$app_controller->get_aside_menu ($modules, $current);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Communicate ' .self::$property_name,
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'company_id' => $company_id,
										'aside_menu' => $aside_menu['html']
										);
						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view ('Asidemenu', $pass);
						self::$app_controller->get_view ('Communicate', $pass);
						self::$app_controller->get_footer (array('page'=>'communicate'));
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
		self::$app_controller->set_session_start();

		$company_id 	= $_SESSION['company_id'];

		switch ($subRequest) {
			case 'SendNotification': 
				$Message    	= self::$app_controller->sanitise_string($request->parameters['Message']);
				$Mood 			= self::$app_controller->sanitise_string($request->parameters['Mood']);
				$Property_ID 	= self::$app_controller->sanitise_string($request->parameters['Property_ID']);

				$save    		= self::send_notification ($Message, $Mood, $Property_ID);
				
				return json_encode($save);

			break;
			case 'SendSMS': 
				$Message    	= self::$app_controller->sanitise_string($request->parameters['SMSMessage']);
				$SendTo    		= self::$app_controller->sanitise_string($request->parameters['SendTo']);
				$Mood 			= self::$app_controller->sanitise_string($request->parameters['SMSMood']);
				$PropertyID		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$user_id 		= $_SESSION['user_id'];
				
				$send    		= self::send_sms ($Message, $SendTo, $Mood, $PropertyID, $user_id, $company_id);
				return json_encode($send);
			break;

			case 'SaveEmail': 
				$SendTo    	= self::$app_controller->sanitise_string($request->parameters['SendTo']);
				$Subject    = self::$app_controller->sanitise_string($request->parameters['Subject']);
				$EmailMood   = self::$app_controller->sanitise_string($request->parameters['EmailMood']);
				$Message    = self::$app_controller->sanitise_string($request->parameters['EmailText']);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$UserID 	= $_SESSION['user_id'];
				$CompanyID 	= $_SESSION['company_id'];
				$FileData 	= array_map(self::$app_controller->sanitise_string, $_FILES);
				
				$send    	= self::save_email (
									$SendTo,
									$Subject,
									$EmailMood,
									$Message,
									$PropertyID,
									$UserID,
									$CompanyID,
									$FileData
								);

				return json_encode($send);
			break;
			case 'DeleteNote':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['ID']);
				$delete 	= self::delete_notification ($ID);
			return json_encode($delete);

			case 'DeleteMsg':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['ID']);
				$Type 		= self::$app_controller->sanitise_string($request->parameters['Type']);

				$delete 	= self::delete_msg ($ID, $Type);
			return json_encode($delete);

			case 'CancelTransaction':
				$transaction_id 	= self::$app_controller->sanitise_string($request->parameters['transaction_id']);
				

				$save 				= self::cancel_sms_credits ($transaction_id);
			return json_encode($save);
			break;

			case 'SucessTransaction':
				$transaction_id 	= self::$app_controller->sanitise_string($request->parameters['transaction_id']);

				$save 				= self::sucess_sms_credits ($transaction_id);
			return json_encode($save);
			break;

			case 'SendAdminEmail':
				$company_id 		= self::$app_controller->sanitise_string($request->parameters['company_id']);
				$save 				= self::send_admin_email ($company_id);
				return json_encode($save);
			break;


		}
	}


	/*** save email ***/
	static public function save_email (
							$SendTo,
							$Subject,
							$EmailMood,
							$Message,
							$PropertyID,
							$UserID,
							$CompanyID,
							$FileData
						){


		if (empty($Message)) {
			return array('status'  => false, 'text' => 'Invalid Email Text');
		}

		if (!self::$app_controller->validate_variables ($SendTo, 3)) {
			return array('status'  => false, 'text' => 'Please select Send To');
		}
		

		if (!self::$app_controller->validate_variables ($Subject, 3)) {
			return array('status'  => false, 'text' => 'Please enter Subject');
		}


		$name 			= '';
		$attachment 	= array();

		if (!empty($FileData)) {
			$dir 					= '../companies/' .$CompanyID. '/properties/' .$PropertyID;
			$create 				= self::$app_controller->created_directory ($dir);
			$name 					= self::$app_controller->upload_file ($FileData['AttachementFile'], $dir);

			$attachment 			= array(
										'filename' => $name, 
										'path' => $dir . '/' . $name,
										'encoding' => 'base64',
										'type' => $FileData['AttachementFile']['type']
										);
		}

		$properties 	= self::$app_controller->get_all_residents ($PropertyID);
		$properties_list = array();
		if ($SendTo == 'All Residents') {
			$properties_list = $properties;
		}elseif ($SendTo == 'All Trustees') {
			$properties_list = self::$app_controller->filter_by_value ($properties, 'residentTrustee', 'yes');
		}

		$emails 		= '';
		$prefix 		= '';
		$email_detail 	= array();

		if (!empty($properties_list)) {
			foreach ($properties_list as $h) {

				$oemail 	= $h['residentNotifyEmail'];
				$ores_name 	= $h['residentName'];

				if (!empty($oemail)) {
					$emails 		= explode(';', $oemail);

					foreach ($emails as $e) {
						$email_detail[] = array(
											'email' 	=> $e, 
											'full_name' => $ores_name
										);
					}
				}
			    
			}
		}


		$email 	= self::$app_controller->send_emails ($Message, 
										'ConnectLiving - ' .$Subject, // subject
										'', // From
										$email_detail, // email text
										$attachment // attachement
									);


		$save 	= self::$app_controller->save_properties_email (
										$Message, 
										$SendTo, 
										$Subject, 
										$EmailMood, 
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


	static public function send_notification ($Message, $Mood, $Property_ID) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}
		
		if (!self::$app_controller->validate_variables ($Mood, 3)) {
			return array('status'  => false, 'text' => 'Invalid Mood');
		}

		if (!is_numeric($Property_ID)) {
			return array('status'  => false, 'text' => 'Invalid Property ID');
		}

		$properties = self::$app_controller->get_tenant_list ($Property_ID);
		$ids 		= '';
		$prefix 	= '';

		if (!empty($properties)) {
			foreach ($properties as $h) {
			    $ids    .= $prefix . $h['TenantID'];
			    $prefix  = ', ';
			}
		}

		$users 			= self::$app_controller->get_users_by_pro ($ids);
		$device_token 	= array();
		foreach ($users as $u) {
			$device_token[] = $u['deviceToken'];
		}

		if (!empty($device_token)) {
			$save 		= self::$app_controller->save_notification_coms ($Message, $Mood, $Property_ID);
			self::$app_controller->push_notification ($Message, $device_token, 'createMessage');
		}else{
			$save 		= -1;
		}
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}elseif($save === -1){
			return array('status' => false, 'text' => 'Sorry, no message was sent because no users are linked to this property');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	static public function delete_msg ($ID, $Type) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid id');
		}

		// Validate if id exists
		if ($Type == 'SMS') {

			$msg 	= self::$app_controller->get_sms_property_byid ($ID);

			if (count($msg) == 0) {
				return array('status'  => false, 'text' => 'Message don\'t exists');
			}

			$delete 	= self::$app_controller->delete_sms_property_info ($ID);
		
		}elseif($Type == 'Email'){

			$msg = self::$app_controller->get_email_property_byid ($ID);

			if (count($msg) == 0) {
				return array('status'  => false, 'text' => 'Message don\'t exists');
			}

			$delete 	= self::$app_controller->delete_email_property_info ($ID);

		}elseif($Type == 'Notification'){

			$msg = self::$app_controller->get_notification_byid (self::$property_id, $ID);

			if (count($msg) == 0) {
				return array('status'  => false, 'text' => 'Message don\'t exists');
			}

			$delete 	= self::$app_controller->delete_notification_info ($ID);
		}


		if ($delete === true) {
			return array('status'  => true, 'text' => 'Deleted');
		}else{
			return array('status'  => false, 'text' => 'Failed to delete, ' . $delete);
		}
	}

	/*** send sms ***/
	static public function send_sms ($Message, $SendTo, $Mood, $PropertyID, $user_id, $company_id) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}
		
		if (!self::$app_controller->validate_variables ($SendTo, 3)) {
			return array('status'  => false, 'text' => 'Invalid Send To');
		}

		if (!self::$app_controller->validate_variables ($Mood, 3)) {
			return array('status'  => false, 'text' => 'Invalid Mood');
		}

		if (!is_numeric($PropertyID)) {
			return array('status'  => false, 'text' => 'Invalid Property ID');
		}

		$balance 		 	= count(self::$app_controller->calculate_credit_balance ($company_id));
		$properties 	 	= self::$app_controller->get_all_residents ($PropertyID);
		$properties_list 	= array();

		if ($SendTo 		== 'All Residents') {
			$properties_list = $properties;
		}elseif ($SendTo == 'All Trustees') {
			$properties_list = self::$app_controller->filter_by_value ($properties, 'residentTrustee', 'yes');
		}

		$cells 		= '';
		$prefix 	= '';

		$number_cells = 0;

		if (!empty($properties_list)) {
			foreach ($properties_list as $h) {

				if (!empty($h['residentCellphone'])) {
					$cells  .= $prefix . '27' . $h['residentCellphone'];
					$prefix  = ', ';

					$number_cells++;
				}
			    
			}
		}

		
		if (!empty($cells)) {

			if ($balance == 0 OR $number_cells < $balance) {
				return array('status' => false, 'text' => 'Sorry, you have run out of SMS credit, please purchase more');
			}

			$send 		= self::$app_controller->send_sms ($Message, $cells, $company_id);

			if (!$send['status']) {
				return array('status' => false, 'text' => 'SMS Not Sent: ' . $send['text']);
			}else{

				$save 	= self::$app_controller->update_sms_use ($number_cells, $company_id);
				$save 	= self::$app_controller->save_property_com_sms ($cells, $Message, $SendTo, $Mood, $PropertyID, $user_id);
			}

		}else{
			return array('status' => false, 'text' => 'Sorry, no message was sent because no users are linked to this property');
		}
		
		if ($save === true) {
			return array('status' => true, 'text' => 'SMS sent');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}



	/*** send email ***/
	static public function send_admin_email ($company_id) {
		$company 			= self::$app_controller->get_sms_credit_bycompany ($company_id);

		// $admin_email 		= 'sbonder@outlook.com';
		$admin_email 		= 'warren@bauhaus.co.za';
		$subject 			= 'connectLIVING SMS Purchase Payment Notification';
		$full_name 			= 'Warren Canning';


		foreach ($company as $c) {
			$number_credits	= $c['credits'];
			$transaction_id = $c['transaction_id'];
			$company_name 	= $c['companyName'];
			$company_email 	= $c['mainContactEmail'];
		}

		$email 		= self::$app_controller->get_admin_credit_notification_email ($full_name, $company_id, $company_name, $number_credits, $transaction_id);

		// ($email_text, $subject, $email, $full_name)
		$send 		= self::$app_controller->send_email ($email, $subject, $admin_email, $full_name);

	}


	/*** send email ***/
	static public function send_email ($Message, $Subject, $Email, $Mood, $Property_ID) {

		if (empty($Message)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}
		
		if (!self::$app_controller->validate_variables ($Subject, 3)) {
			return array('status'  => false, 'text' => 'Invalid Subject');
		}

		if (!self::$app_controller->validate_variables ($Email, 10)) {
			return array('status'  => false, 'text' => 'Invalid Email');
		}

		if (!self::$app_controller->validate_variables ($Mood, 3)) {
			return array('status'  => false, 'text' => 'Invalid Mood');
		}

		if (!is_numeric($Property_ID)) {
			return array('status'  => false, 'text' => 'Invalid Property ID');
		}

		$properties = self::$app_controller->get_tenant_list ($Property_ID);
		$ids 		= '';
		$prefix 	= '';

		if (!empty($properties)) {
			foreach ($properties as $h) {
			    $ids    .= $prefix . $h['TenantID'];
			    $prefix  = ', ';
			}
		}

		$users 			= self::$app_controller->get_users_by_pro ($ids);
		$emails			= array();
		$pre 			= '';
		foreach ($users as $u) {
			$emails[]	= array('email' => $u['emailAddress'], 'full_name' => $u['firstName'] .' '. $u['lastName']);
		}

		if (!empty($emails)) {
			$save 		= self::$app_controller->save_email_coms ($Message, $Subject, $Email, $Mood, $Property_ID);
			$send 		= self::$app_controller->send_emails ($Message, $Subject, $Email, $emails);
		}else{
			$save 		= -1;
		}
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Email sent ' . $send);
		}elseif($save === -1){
			return array('status' => false, 'text' => 'Sorry, no message was sent because no users are linked to this property');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $send);
		}
	}

	/*** send email ***/
	static public function buy_sms_credits ($CreditNumber, $AmountDue, $company_id, $prop_id, $prop_name) {

		if (empty($CreditNumber)) {
			return array('status'  => false, 'text' => 'Invalid Credit Number');
		}
		
		if (empty($AmountDue)) {
			return array('status'  => false, 'text' => 'Invalid Amount Due');
		}

		// die(var_dump($CreditNumber));

		$AmountDue   	 		 = preg_replace("/[^0-9.]/", '', $AmountDue);
		// $payfast_url   	 	 = 'https://sandbox.payfast.co.za/eng/process';

		// Live
		$merchant_id   	 		 = '11629829';
		$merchant_key   		 = 'v1fd9niz2ww92';

		$transaction_id   		 = uniqid();
		//Test
		// $merchant_id   	 		 = '10003240';
		// $merchant_key   		 = '08l5nk6l5iso8';
		$return_url   			 = "http://manage.connectliving.co.za/Communicate?prop_id=".urlencode($prop_id)."&payment_processed=true&company_id=".urlencode($company_id)."&CreditNumber=".urlencode($CreditNumber)."&transaction_id=".urlencode($transaction_id);
		$cancel_url   			 = "http://manage.connectliving.co.za/Communicate?prop_id=".urlencode($prop_id)."&payment_processed=cancelled&company_id=".urlencode($company_id)."&transaction_id=".urlencode($transaction_id);
		$notify_url   			 = "http://manage.connectliving.co.za/Communicate?prop_id=".urlencode($prop_id)."&payment_processed=notify&prop_name=".urlencode($prop_name)."&transaction_id=".urlencode($transaction_id);

		$amount   				 = $AmountDue;
		$item_name   			 = 'SMS Bundle';
		$item_description		 = 'Bought SMS ' .$CreditNumber. ' credits';
		$email_confirmation		 = 1;
		$confirmation_address	 = 'sboniso@mvmedia.co.za';

		$security_string 		 = 'merchant_id='.$merchant_id;
		$security_string 		.= '&merchant_key='.$merchant_key;
		$security_string 		.= '&amount='.$amount;
		$security_string 		.= '&return_url='.$return_url;
		$security_string 		.= '&cancel_url='.$cancel_url;
		$security_string 		.= '&notify_url='.$notify_url;
		$security_string 		.= '&item_name='.$item_name;
		$security_string 		.= '&item_description='.$item_description;
		$security_string 		.= '&email_confirmation='.$email_confirmation;
		$security_string 		.= '&confirmation_address='.$confirmation_address;
		$security_string 		.= '&passphrase=XXXXX';

		$signature 				= md5( $security_string );

		$post_vars 				= array(
									'merchant_id' 			=> $merchant_id,
									'merchant_key' 			=> $merchant_key,
									'amount' 				=> $amount,
									'return_url' 			=> $return_url,
									'cancel_url' 			=> $cancel_url,
									'notify_url' 			=> $notify_url,
									'item_name' 			=> $item_name,
									'item_description' 		=> $item_description,
									'email_confirmation' 	=> $email_confirmation,
									'confirmation_address' 	=> $confirmation_address,
									'signature' 			=> $signature
								);

		$pay 					= self::$payfast->capture_payment ($post_vars);

		if ($pay['status'] === true) {
			// Save payment
			$save 				= self::$app_controller->insert_credits ($company_id, $CreditNumber, $transaction_id);

			if ($save === true) {
				return array('status' => true, 'text' => $pay['text']);
			}else{
				return array('status' => false, 'text' => 'Failed to insert, ' . $save);
			}


		}else{
			return array('status' => false, 'text' => 'Failed to process payment, ' . $pay['text']);
		}

		// return $pay;


	}

	/*** send email ***/
	static public function cancel_sms_credits ($transaction_id) {

		$save 				= self::$app_controller->delete_credits ($transaction_id);

		if ($save === true) {
			return array('status' => true, 'text' => 'Cancelled');
		}else{
			return array('status' => false, 'text' => 'Failed to delete, ' . $save);
		}


	}

	/*** send email ***/
	static public function sucess_sms_credits ($transaction_id) {

		$save 				= self::$app_controller->activate_credits ($transaction_id);

		if ($save === true) {
			return array('status' => true, 'text' => 'Activated');
		}else{
			return array('status' => false, 'text' => 'Failed to upadte, ' . $save);
		}


	}


	static public function set_up_all_communicate ($prop_id) {

		$ret_array 	= array();

		$sms_list 	= self::$app_controller->get_property_sms_coms ($prop_id);
		$email_list = self::$app_controller->get_property_email_coms ($prop_id);



		// work out return array
		

		if (!empty($sms_list)){// sms

			foreach ($sms_list as $s) {
				$ret_array[] = array(
					'sort' 	  => $s['created'],
					'id' 	  => $s['id'],
					'subject' => 'SMS',
					'message' => $s['sms_text'],
					'type' 	  => 'SMS',
					'mood' 	  => $s['mood'],
					'send_to' => $s['send_to'],
					'date'    => self::$app_controller->human_timing($s['created']) . ' ago'
					);
			}
		}

		if (!empty($email_list)){// email

			foreach ($email_list as $e) {

				$message 	 = strip_tags($e['email_text']);

				$ret_array[] = array(
					'sort' 	  	 => $e['created'],
					'id' 	  	 => $e['id'],
					'subject' 	 => $e['subject'],
					'message' => $message,
					'type' 	  => 'Email',
					'mood' 	  => $e['mood'],
					'send_to' => $s['send_to'],
					'date'    => self::$app_controller->human_timing($e['created']) . ' ago',

					);
			}
		}
		$sorted = self::$app_controller->array_orderby ($ret_array, 'sort', SORT_DESC);

		return $sorted;
	}

	static public function set_up_communicate_by_id ($prop_id, $ID, $Type) {

		$ret_array 	= array();

		$sms_list 	= self::$app_controller->get_property_sms_byid ($prop_id, $ID);
		$email_list = self::$app_controller->get_property_email_byid ($prop_id, $ID);
		// work out return array
		
		if ($Type === 'SMS'){// sms

			foreach ($sms_list as $s) {
				$ret_array[] = array(
					'sort' 	  => $s['created'],
					'id' 	  => $s['id'],
					'subject' => 'SMS',
					'message' => $s['sms_text'],
					'type' 	  => 'SMS',
					'mood' 	  => $s['mood'],
					'send_to' => $s['send_to'],
					'date'    => $s['created']
					);
			}
		}

		if ($Type === 'Email'){// email

			foreach ($email_list as $e) {
				$ret_array[] = array(
					'sort' 	  	 => $e['created'],
					'id' 	  	 => $e['id'],
					'subject' 	 => $e['subject'],
					'message' 	 => $message,
					'type' 	  	 => 'Email',
					'mood' 	  	 => $e['mood'],
					'send_to' 	 => $s['send_to'],
					'date'    	 => $e['created']

					);
			}
		}

		return $ret_array;
	}
}
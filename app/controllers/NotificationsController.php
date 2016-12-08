<?php
/**
 * Notifications Controller
 * 
 * @package 
 * @author  
 */
class NotificationsController
{
	static public $app_controller;

	public function __construct() {
		self::$app_controller = new AppController();
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

		switch ($subRequest) {
			case 'EditNotification': 
				$company_id = $_SESSION['company_id'];
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyList']);
				$Message    = self::$app_controller->sanitise_string($request->parameters['Message']);
				$StartDate  = self::$app_controller->sanitise_string($request->parameters['StartDate']);
				$EndDate 	= self::$app_controller->sanitise_string($request->parameters['EndDate']);
				$Mood 		= self::$app_controller->sanitise_string($request->parameters['Mood']);
				$ID 		= self::$app_controller->sanitise_string($request->parameters['ID']);

				$edit    	= self::edit_notification ($ID, $PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood);
				return json_encode($edit);
			break;
			case 'SaveNotification': 
				$company_id = $_SESSION['company_id'];
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyList']);
				$Message    = self::$app_controller->sanitise_string($request->parameters['Message']);
				$StartDate  = self::$app_controller->sanitise_string($request->parameters['StartDate']);
				$EndDate 	= self::$app_controller->sanitise_string($request->parameters['EndDate']);
				$Mood 		= self::$app_controller->sanitise_string($request->parameters['Mood']);
				$save    	= self::save_notification ($PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood);
				
				return json_encode($save);

			break;
			case 'DeleteNote':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['ID']);
				$delete 	= self::delete_notification ($ID);
			return json_encode($delete);
		}
	}

	/**
	 * GET Request
	 *
	 * @param
	 * @return
	 */
	public function get($request) {
		$subRequest		= (isset($request->url_elements[1])) ? $request->url_elements[1] : '';

		self::$app_controller->set_session_start();
		$email 			= $_SESSION['email'];

		switch ($subRequest) {
			case 'GetAllNotifications':
					$company_id = $_SESSION['company_id'];
					return json_encode(self::$app_controller->get_notifications($company_id));
				break;

			case 'GetNoteByID':
					$ID     = self::$app_controller->sanitise_string($request->parameters['id']);
					$note 	= self::$app_controller->get_notifications_by_id($ID);
					return json_encode($note[0]);
				break;
			case 'SendNotification':
					$ID     	= self::$app_controller->sanitise_string($request->parameters['id']);
					$message    = self::$app_controller->sanitise_string($request->parameters['message']);
					$note 		= self::set_up_message ($ID, $message);
					return json_encode($note);
				break;
			default:
				if (self::$app_controller->check_if_logged($email)) {

					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];

					$aside_menu 	= self::$app_controller->get_aside_menu ($modules, 'notifications');

					/*** validate if assigned for this module ***/
					if (in_array('notifications', $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name .' '. $last_name, 
										'email' 	 => $email,
										'page_title' => 'Notifications',
										'page'		 => 'notifications',
										'aside_menu' => $aside_menu['html']
										);
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view ('Asidemenu', $pass);
						self::$app_controller->get_view ('Notifications', $pass);
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

	/*** save notification ***/
	static public function set_up_message ($ID, $Message){
		// $message
		$app_users  = self::$app_controller->get_property_app_users ($ID); 

		$message 	= '';
		$payer_ids 	= array();

		foreach ($app_users as $pu) {
			$property_name 	= $pu['property_name'];
			$player_id 		= $pu['userPlayerID'];

			if ($player_id) {
				$payer_ids[]	= $player_id;
			}
			
		}

		$message .= 'Notification for ' .$property_name. ': ' .$Message;

		if (!empty($payer_ids)) {
			$save 		= self::$app_controller->send_push_notification ($message, $payer_ids);
		}else{
			$save 		= -1;
		}

		if (!empty($save)) {
			return array('status' => true, 'text' => 'Message sent');
		}elseif($save === -1){
			return array('status' => false, 'text' => 'Sorry, no message was sent because no users are linked to this property');
		}else{
			return array('status' => false, 'text' => 'Unknown Error, ' .$save);
		}

	}

	static public function save_notification ($PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood) {

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}

		if (!self::$app_controller->validate_variables ($StartDate, 3)) {
			return array('status'  => false, 'text' => 'Invalid Start Date');
		}

		if (!self::$app_controller->validate_variables ($EndDate, 3)) {
			return array('status'  => false, 'text' => 'Invalid End Date');
		}

		if (!self::$app_controller->validate_variables ($Mood, 3)) {
			return array('status'  => false, 'text' => 'Invalid Mood');
		}
		
		$save 		= self::$app_controller->save_new_notification ($PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood);
		

		// die(var_dump($message));

		if ($save === true) {
			
			return array('status' => true, 'text' => 'Inserted'.$not_sent);
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** edit notification ***/
	static public function edit_notification ($ID, $PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}
		// Validate if id exists
		$note 			= self::$app_controller->get_notifications_by_id ($ID);

		if (count($note) == 0) {
			return array('status'  => false, 'text' => 'Iterm don\'t exists');
		}

		if (!self::$app_controller->validate_variables ($Message, 3)) {
			return array('status'  => false, 'text' => 'Invalid Message');
		}

		if (!self::$app_controller->validate_variables ($StartDate, 3)) {
			return array('status'  => false, 'text' => 'Invalid Start Date');
		}

		if (!self::$app_controller->validate_variables ($EndDate, 3)) {
			return array('status'  => false, 'text' => 'Invalid End Date');
		}

		if (!self::$app_controller->validate_variables ($Mood, 3)) {
			return array('status'  => false, 'text' => 'Invalid Mood');
		}
		
		$save 		= self::$app_controller->edit_notification ($ID, $PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood);

		if ($save === true) {
			return array('status' => true, 'text' => 'Edited');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** delete notification ***/
	static public function delete_notification ($ID) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid notification id');
		}

		// Validate if id exists
		$note 		= self::$app_controller->get_notifications_by_id ($ID);
		if (count($note) == 0) {
			return array('status'  => false, 'text' => 'Iterm don\'t exists');
		}

		$delete 	= self::$app_controller->delete_notification_info ($ID);
		if ($delete === true) {
			return array('status'  => true, 'text' => 'Deleted');
		}else{
			return array('status'  => false, 'text' => 'Failed to delete, ' . $delete);
		}
	}
}
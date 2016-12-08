<?php
/**
 * Emergency Log Controller
 * 
 * @package 
 * @author  
 */
class EmergencyLogController
{
	static public $app_controller;

	public function __construct() {
		self::$app_controller = new AppController();
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
			case 'GetLogsTable':
				$company_id 	= $_SESSION['company_id'];
				$logs 			= self::get_logs_table ($company_id);
				return json_encode($logs);
				break;

			case 'GetUserTypes':
				$user_types 	= self::$app_controller->get_user_types ();
				return json_encode($user_types);
				break;
			case 'GetUserByID':
				$user_id 		= self::$app_controller->sanitise_string($request->parameters['user_id']);
				$user 			= self::$app_controller->get_user_byid ($user_id);
				return json_encode($user);
				break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];

					$aside_menu 	= self::$app_controller->get_aside_menu ($modules, 'emergency_log');


					/*** validate if assigned for this module ***/
					if (in_array('emergency_log', $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Emergency Log',
										'page'		 => 'emergency_log',
										'aside_menu' => $aside_menu['html']
										);
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view ('Asidemenu', $pass);
						self::$app_controller->get_view ('EmergencyLog', $pass);
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

	//get_logs_table

	static public function get_logs_table ($company_id) {
		$logs 			= self::$app_controller->get_all_logs($company_id);
		$return_data 	= array();

		foreach ($logs as $l) {

			$return_data[] = array(
					$l['id'],
					$l['companyName'],
					$l['phoneNumber'],
					$l['emergencyType'],
					self::$app_controller->format_date($l['date'])
					);
		}

		return array('data' => $return_data);
	}


}
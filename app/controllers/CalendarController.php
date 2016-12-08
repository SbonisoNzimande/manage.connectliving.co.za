<?php
/**
 * Calendar Controller
 * 
 * @package 
 * @author  
 */
class CalendarController
{
	static public $app_controller;
	static public $prop_array;
	static public $company_id;



	public function __construct() {
		self::$app_controller = new AppController();

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
		// die(var_dump($email));
		switch ($subRequest) {
			
			case 'GetAllEvents':
				$events = self::set_up_events();
				
			return json_encode($events);
			break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 				= $_SESSION['email'];
					$first_name			= $_SESSION['first_name'];
					$last_name			= $_SESSION['last_name'];
					$modules			= $_SESSION['modules'];
					$user_id			= $_SESSION['user_id'];

					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);
					// die(var_dump(self::$app_controller->get_all_queries(self::$prop_array)));

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, 'calendar');

					
					$pass 				= array(
											'full_name'  => $first_name.' '.$last_name, 
											'email' 	 => $email,
											'page_title' => 'Calendar',
											'page'		 => 'calendar',
											'user_id'	 => $user_id,
											'aside_menu' => $aside_menu['html']
										);

					self::$app_controller->get_header ($pass);
					self::$app_controller->get_view ('Asidemenu', $pass);
					self::$app_controller->get_view ('Calendar', $pass);
					self::$app_controller->get_footer ($pass);
					exit();
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
		$user_id		= $_SESSION['user_id'];
		$company_id		= $_SESSION['company_id'];

		switch ($subRequest) {
			

			case 'AddEvent':

				$title 				= self::$app_controller->sanitise_string($request->parameters['title']);
				$start 				= self::$app_controller->sanitise_string($request->parameters['start']);
				$end 				= self::$app_controller->sanitise_string($request->parameters['end']);

				$save 				= self::add_event ($company_id, $user_id, $title, $start, $end);
				return json_encode($save);
			break;

			


				
		}
	}


	/**
	 * @param
	 * @return
	 */
	static public function set_up_events () {

		$return_array 	= array();

		$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
		$company_id 	= $_SESSION['company_id'];

		$queries 		= self::$app_controller->get_all_queries($prop_array);
		$assets 		= self::$app_controller->get_property_assets($prop_array);
		$reminders 		= self::$app_controller->get_all_calendar_reminders($company_id);

		
		foreach ($queries as $q) {

			$return_array[] = array(
					'title' 		=> '<b>' . $q['queryType'] . ' Query</b>',
					'description' 	=> 'Property: ' . $q['propertyName'],
					'start' 		=> $q['queryDate'],
					'allday' 		=> true
				);
		}

		foreach ($reminders as $r) {

			$return_array[] = array(
					'title' 		=> '<b>Reminder - ' . $r['firstName'] . '</b>',
					'description' 	=> $r['description'],
					'start' 		=> $r['start'],
					'allday' 		=> true,
					'className' 	=> ["reminders"]
				);
		}

		foreach ($assets as $a) {

			$return_array[] = array(
					'title' 		=> '<b>Asset Inspection: ' .$a['asset_name'].'</b>',
					'description' 	=> 'Property: ' . $a['propertyName'],
					'start' 		=> $a['inspection_due_date'],
					'allday' 		=> true,
					'className' 	=> ["assets"]
				);
		}

		
		return $return_array;
	}


	static public function add_event ($company_id, $user_id, $title, $start, $end) {

		$save 	= self::$app_controller->insert_reminder_event (
										$company_id,
										$user_id,
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

}
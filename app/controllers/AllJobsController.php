<?php
/**
 * Jobs Controller
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
		self::$app_controller 	= new AppController();
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

		switch ($subRequest) {
			case 'GetEnums':
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$prop_name 		= self::$app_controller->sanitise_string($request->parameters['prop_name']);
				$company_id 	= $_SESSION['company_id'];
				$queries 		= self::set_up_queries ($prop_id, $prop_name, $company_id);
			return json_encode($queries);
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
}
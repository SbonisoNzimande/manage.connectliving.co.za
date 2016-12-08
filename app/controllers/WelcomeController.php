<?php
/**
 * Welcome Controller
 * 
 * @package 
 * @author  
 */
class WelcomeController
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
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];

					$aside_menu 	= self::$app_controller->get_aside_menu ($modules, 'welcome');

					$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Welcome',
										'page'		 => 'welcome',
										'aside_menu' => $aside_menu['html']
										);
					self::$app_controller->get_header ($pass);
					self::$app_controller->get_view ('Asidemenu', $pass);
					self::$app_controller->get_view ('Welcome', $pass);
					self::$app_controller->get_footer ($pass);
					exit();
				}else{
					self::$app_controller->redirect_to('Login');
				}
			break;
		}

	}
}
<?php
/**
 * User Management Controller
 * 
 * @package 
 * @author  
 */
class UserManagementController
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
		$subRequest		= (isset($request->url_elements[1])) ? $request->url_elements[1] : '';

		self::$app_controller->set_session_start();
		$email 			= $_SESSION['email'];

		switch ($subRequest) {
			case 'GetAllUsers':
				$users 	= self::get_users_table ();
				return json_encode($users);
				break;
			case 'GetUserByID':
				$user_id 		= self::$app_controller->sanitise_string($request->parameters['user_id']);
				$user 			= self::$app_controller->get_rep_byid ($user_id);
				return json_encode($user);
				break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];

					$aside_menu 	= self::$app_controller->get_aside_menu ($modules, 'user_management');

					/*** validate if assigned for this module ***/
					if (in_array('user_management', $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'User Management',
										'page'		 => 'user_management',
										'aside_menu' => $aside_menu['html']
										);
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view ('Asidemenu', $pass);
						self::$app_controller->get_view ('UserManagement', $pass);
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
			case 'EditUser':
				$RepName 	= self::$app_controller->sanitise_string($request->parameters['RepName']);
				$Password 	= self::$app_controller->sanitise_string($request->parameters['Password']);
				$Agency 	= self::$app_controller->sanitise_string($request->parameters['Agency']);
				$Status 	= self::$app_controller->sanitise_string($request->parameters['Status']);
				$ID 		= self::$app_controller->sanitise_string($request->parameters['ID']);

				$user 		= self::edit_user ($ID, $RepName, $Password, $Agency, $Status);
			return json_encode($user);
			break;

			case 'DeleteUser':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['ID']);

				$user 		= self::delete_user ($ID);
			return json_encode($user);
			break;
		}
	}

	static public function edit_user ($ID, $RepName, $Password, $Agency, $Status) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid user id');
		}

		// Validate if id exists
		$user 			= self::$app_controller->get_rep_byid ($ID);

		if (count($user) == 0) {
			return array('status'  => false, 'text' => 'User don\'t exists');
		}

		if (!self::$app_controller->validate_variables ($RepName, 3)) {
			return array('status'  => false, 'text' => 'Invalid rep name');
		}

		if (!self::$app_controller->validate_variables ($Password, 3)) {
			return array('status'  => false, 'text' => 'Invalid password');
		}

		if (!self::$app_controller->validate_variables ($Agency, 3)) {
			return array('status'  => false, 'text' => 'Invalid Agency');
		}

		if (!self::$app_controller->validate_variables ($Status, 3)) {
			return array('status'  => false, 'text' => 'Invalid Status');
		}

		$update 	= self::$app_controller->update_user_info ($ID, $RepName, $Password, $Agency, $Status);

		if ($update === true) {
			return array('status'  => true, 'text' => 'Inserted');
		}else{
			return array('status'  => false, 'text' => 'Failed to insert, ' . $update);
		}
	}

	static public function delete_user ($ID) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid user id');
		}

		// Validate if id exists
		$user 			= self::$app_controller->get_rep_byid ($ID);

		if (count($user) == 0) {
			return array('status'  => false, 'text' => 'User don\'t exists');
		}

		$delete 	= self::$app_controller->delete_user_info ($ID);

		if ($delete === true) {
			return array('status'  => true, 'text' => 'Deleted');
		}else{
			return array('status'  => false, 'text' => 'Failed to delete, ' . $delete);
		}
	}

	static public function get_users_table () {
		$users 			= self::$app_controller->get_rep_all();
		$return_data 	= array();

		foreach ($users as $u) {
			$return_data[] = array(
					$u['rep_id'],
					'<a href="RepProfile?user_id='.$u['rep_id'].'" >' .ucwords(strtolower($u['repName'])). '</a>',
					$u['password'],
					$u['agency'],
					$u['status'],
					'<button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#EditModal" onclick="getUserEdit('.$u['rep_id'].');"><span class="glyphicon glyphicon-pencil"></span></button>
        			<button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#Delete" onclick="getUserDelete('.$u['rep_id'].');"><span class="glyphicon glyphicon-trash"></span></button>'
					);
		}

		return array('data' => $return_data);
	}
}
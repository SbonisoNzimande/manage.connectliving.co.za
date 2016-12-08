<?php
/**
 * Admin Users Controller
 * 
 * @package 
 * @author  
 */
class AdminUsersController
{
	static public $app_controller;
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

		switch ($subRequest) {
			case 'GetAllUsers':
				$users 	= self::get_users_table ();
				return json_encode($users);
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
			case 'GetPropertyForCompany':
				$ID    			= self::$app_controller->sanitise_string   ($request->parameters['ID']);
				$prop 			= self::$app_controller->get_property_for_company ($ID);
				return json_encode($prop);
				break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];

					$aside_menu 	= self::$app_controller->get_aside_menu ($modules, 'admin_users');


					/*** validate if assigned for this module ***/
					if (in_array('admin', $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Admin Users',
										'page'		 => 'admin_users',
										'aside_menu' => $aside_menu['html']
										);
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view ('Asidemenu', $pass);
						self::$app_controller->get_view ('AdminUsers', $pass);
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
			case 'SaveUser':
				$FirstName 		= self::$app_controller->sanitise_string($request->parameters['FirstName']);
				$Surname 		= self::$app_controller->sanitise_string($request->parameters['Surname']);
				$Email 			= self::$app_controller->sanitise_string($request->parameters['Email']);
				$CellNumber 			= self::$app_controller->sanitise_string($request->parameters['CellNumber']);
				$Password1 		= self::$app_controller->sanitise_string($request->parameters['Password1']);
				$Password2 		= self::$app_controller->sanitise_string($request->parameters['Password2']);
				$UserType 		= self::$app_controller->sanitise_string($request->parameters['UserType']);
				
				$user 		= self::save_user ($FirstName, $Surname, $Email, $CellNumber, $Password1, $Password2, $UserType);
			return json_encode($user);

			case 'UpdatePerm':
				$admin_id 		= self::$app_controller->sanitise_string($request->parameters['admin_id']);
				$company_id 	= self::$app_controller->sanitise_string($request->parameters['company_id']);
				// $email 			= self::$app_controller->sanitise_string($request->parameters['email']);
				$PermissionType = self::$app_controller->sanitise_string($request->parameters['PermissionType']);
				$modules 		= $request->parameters['modules'];
				
				$edit 			= self::edit_perm (
									$admin_id,
									$company_id,
									$PermissionType,
									$modules
								);

			return json_encode($edit);
			break;


			break;

			case 'DeleteUser':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['ID']);

				$user 		= self::delete_user ($ID);
			return json_encode($user);
			break;
		}
	}


	/*** edit permissions ***/
	static public function edit_perm (
						$admin_id,
						$company_id,
						$PermissionType,
						$modules
						) {

		if (!is_numeric($admin_id)) {
			return array('status'  => false, 'text' => 'Invalid admin id');
		}

		if (!is_numeric($company_id)) {
			return array('status'  => false, 'text' => 'Invalid company id');
		}

		if (!self::$app_controller->validate_variables ($PermissionType, 10)) {
			return array('status'  => false, 'text' => 'Invalid Permission Type');
		}
		
		if (!is_array($modules)) {
			return array('status'  => false, 'text' => 'Please select atleast one module');
		}

		$modules 		= array_map(self::$app_controller->sanitise_string, $modules);
		$modules_obj 	= json_encode($modules);

		$get_perm 		= self::$app_controller->get_permission_by_type ($PermissionType);

		if (count($get_perm) === 1) {
			// update
			$edit 		= self::$app_controller->update_user_perm_type (
								$company_id,
								$PermissionType,
								$modules_obj
							);

			$perm_details 	= $get_perm[0];
			$permission_id  = $perm_details['permission_id'];


			self::$app_controller->update_user_permission (
									$permission_id,
									$admin_id
								);
		}else{
			// Add new
			$edit 		= self::$app_controller->add_new_permission (
								$company_id,
								$PermissionType,
								$modules_obj
							);

			$get_perm2 		= self::$app_controller->get_permission_by_type ($PermissionType);

			$perm_details 	= $get_perm2[0];
			$permission_id  = $perm_details['permission_id'];


			self::$app_controller->update_user_permission (
									$permission_id,
									$admin_id
								);


		}

		

		if ($edit === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	static public function save_user ($FirstName, $Surname, $Email, $CellNumber, $Password1, $Password2, $UserType) {

		if (!self::$app_controller->validate_variables ($FirstName, 3)) {
			return array('status'  => false, 'text' => 'Invalid First Name');
		}

		if (!self::$app_controller->validate_variables ($Surname, 3)) {
			return array('status'  => false, 'text' => 'Invalid Surname');
		}

		if (!self::$app_controller->validate_variables ($CellNumber, 0)) {
			return array('status'  => false, 'text' => 'Invalid Cell Number');
		}

		if (!self::$app_controller->validate_variables ($Email, 10)) {
			return array('status'  => false, 'text' => 'Invalid Email');
		}

		$check = self::$app_controller->get_user_by_email ($Email);
		// Check if email exists
		if (count($check)>0) {
			return array('status'  => false, 'text' => 'Email Exists');
		}

		if (!self::$app_controller->validate_variables ($Password1, 3)) {
			return array('status'  => false, 'text' => 'Invalid Password1');
		}

		if (!self::$app_controller->validate_variables ($Password2, 3)) {
			return array('status'  => false, 'text' => 'Invalid Password2');
		}

		// Check if password match
		if ($Password1 !== $Password2) {
			return array('status'  => false, 'text' => 'Invalid Passwords Do Not Match');
		}

		if (!is_numeric($UserType)) {
			return array('status'  => false, 'text' => 'Invalid User Type');
		}

		

		// hash password
		$Password 	= self::$app_controller->hash_password($Password1);
		$save 		= self::$app_controller->save_admin_user ($FirstName, $Surname, $Email, $CellNumber, $Password, $UserType);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $update);
		}
	}

	static public function edit_user ($ID, $FirstName, $Surname, $Email, $CellNumber, $Password1, $Password2, $UserType) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid user id');
		}

		// Validate if id exists
		$user 			= self::$app_controller->get_user_byid ($ID);

		if (count($user) == 0) {
			return array('status'  => false, 'text' => 'User don\'t exists');
		}

		$OldPassword 	= $user['password'];

		if (!self::$app_controller->validate_variables ($FirstName, 3)) {
			return array('status'  => false, 'text' => 'Invalid First Name');
		}

		if (!self::$app_controller->validate_variables ($Surname, 3)) {
			return array('status'  => false, 'text' => 'Invalid Surname');
		}

		if (!self::$app_controller->validate_variables ($CellNumber, 0)) {
			return array('status'  => false, 'text' => 'Invalid Cell Number');
		}

		if (!self::$app_controller->validate_variables ($Email, 10)) {
			return array('status'  => false, 'text' => 'Invalid Email');
		}

		if (!self::$app_controller->validate_variables ($Password1, 3)) {
			return array('status'  => false, 'text' => 'Invalid Password1');
		}

		if (!self::$app_controller->validate_variables ($Password2, 3)) {
			return array('status'  => false, 'text' => 'Invalid Password2');
		}

		// Check if password match
		if ($Password1 !== $Password2) {
			return array('status'  => false, 'text' => 'Invalid Passwords Do Not Match');
		}

		// Check password was changed
		if ($Password1 == $OldPassword) {
			$Password 	= $Password1;// Keep hashed passowrd
		}else{
			// hash password
			$Password = self::$app_controller->hash_password($Password1);
		}

		if (!is_numeric($UserType)) {
			return array('status'  => false, 'text' => 'Invalid User Type');
		}

		

		$update 	= self::$app_controller->update_admin_user_info ($ID, $FirstName, $Surname, $Email, $CellNumber, $Password, $UserType);

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
		$user 			= self::$app_controller->get_user_byid ($ID);

		if (count($user) == 0) {
			return array('status'  => false, 'text' => 'User don\'t exists');
		}

		$delete 	= self::$app_controller->delete_admin_user_info ($ID);

		if ($delete === true) {
			return array('status'  => true, 'text' => 'Deleted');
		}else{
			return array('status'  => false, 'text' => 'Failed to delete, ' . $delete);
		}
	}

	static public function get_users_table () {

		$users 			= self::$app_controller->get_users_all (self::$company_id);
		$return_data 	= array();

		foreach ($users as $u) {
			$return_data[] = array(
					$u['adminID'],
					$u['permission_type'],
					$u['firstName'],
					$u['lastName'],
					$u['contactEmail'],
					$u['contactNumber'],
					'<button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#EditModal" data-admin-id="'.$u['adminID'].'" data-email="'.$u['contactEmail'].'" data-company-id="'.self::$company_id.'"><span class="glyphicon glyphicon-pencil"></span></button>'
					);
		}

		return array('data' => $return_data);
	}
}
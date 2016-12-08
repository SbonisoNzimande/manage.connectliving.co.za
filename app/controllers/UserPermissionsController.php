<?php
/**
 * User Permissions Controller
 * 
 * @package 
 * @author  
 */
class UserPermissionsController
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
			case 'GetAllPermissions':
				$users 	= self::get_perm_table ();
				return json_encode($users);
				break;
			case 'GetPropertyList':
				$CompanyID 		= $_SESSION['company_id'];
				$properties 	= self::$app_controller->get_property_list ($CompanyID);
				return json_encode($properties);
				break;

			case 'GetUserTypes':
				$user_types 	= self::$app_controller->get_user_types ();
				return json_encode($user_types);
				break;
			case 'GetPermissionByID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$permi 			= self::$app_controller->get_permission_byid ($ID);
				return json_encode($permi[0]);
				break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];

					$aside_menu 	= self::$app_controller->get_aside_menu ($modules, 'user_permissions');


					/*** validate if assigned for this module ***/
					if (in_array('admin', $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'User Permissions',
										'page'		 => 'user_permissions',
										'aside_menu' => $aside_menu['html']
										);
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view ('Asidemenu', $pass);
						self::$app_controller->get_view ('UserPermissions', $pass);
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
			case 'SavePerm':
				$PermissionType = self::$app_controller->sanitise_string($request->parameters['PermissionType']);

				$modules 		= $request->parameters['modules'];
				$perm 			= self::save_perm ($PermissionType, $modules);
			return json_encode($perm);

			case 'EditPerm':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['edtID']);
				$PermissionType = self::$app_controller->sanitise_string($request->parameters['PermissionTypeedt']);
				$modules 		= $request->parameters['modulesedt'];
				
				$edit 			= self::edit_perm ($ID, $PermissionType, $modules);
			return json_encode($edit);
			break;

			case 'DeletePerm':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['ID']);

				$delete 	= self::delete_perm ($ID);
			return json_encode($delete);
			break;
		}
	}

	/*** save permissions ***/
	static public function save_perm  ($permission_type, $modules) {

		if (!self::$app_controller->validate_variables ($permission_type, 3)) {
			return array('status'  => false, 'text' => 'Invalid Permission Type');
		}

		if (!is_array($modules)) {
			return array('status'  => false, 'text' => 'Please select atleast one module');
		}

		$modules 		= array_map(self::$app_controller->sanitise_string, $modules);
		$modules_obj 	= json_encode($modules);

		$save 			= self::$app_controller->save_user_perm ($permission_type, $modules_obj);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** edit permissions ***/
	static public function edit_perm ($ID, $permission_type, $modules) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid Permission ID');
		}

		// Validate if id exists
		$perm 			  = self::$app_controller->get_permission_byid ($ID);

		if (count($perm) == 0) {
			return array('status'  => false, 'text' => 'Permission don\'t exists');
		}

		if (!self::$app_controller->validate_variables ($permission_type, 3)) {
			return array('status'  => false, 'text' => 'Invalid Permission Type');
		}

		if (!is_array($modules)) {
			return array('status'  => false, 'text' => 'Please select atleast one module');
		}

		$modules 		= array_map(self::$app_controller->sanitise_string, $modules);
		$modules_obj 	= json_encode($modules);

		$edit 			= self::$app_controller->update_user_perm ($ID, $permission_type, $modules_obj);

		if ($edit === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	

	static public function delete_perm ($ID) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid permission id');
		}

		// Validate if id exists
		$perm 			= self::$app_controller->get_permission_byid ($ID);

		if (count($perm) == 0) {
			return array('status'  => false, 'text' => 'Permission don\'t exists');
		}

		$delete 	= self::$app_controller->delete_admin_perm_info ($ID);

		if ($delete === true) {
			return array('status'  => true, 'text' => 'Deleted');
		}else{
			return array('status'  => false, 'text' => 'Failed to delete, ' . $delete);
		}
	}

	static public function get_perm_table () {
		$users 			= self::$app_controller->get_perm_all();
		$return_data 	= array();

		foreach ($users as $u) {
			$return_data[] = array(
					$u['permission_id'],
					$u['permission_type'],
					self::$app_controller->prettyPrint($u['modules']),
					self::$app_controller->format_date($u['date_created']),
					'<button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#EditModal" onclick="getPermEdit('.$u['permission_id'].');"><span class="glyphicon glyphicon-pencil"></span></button>
        			<button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#Delete" onclick="getPermDelete('.$u['permission_id'].');"><span class="glyphicon glyphicon-trash"></span></button>'
					);
		}

		return array('data' => $return_data);
	}
}
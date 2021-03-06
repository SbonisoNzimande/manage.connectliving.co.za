<?php
/**
 * Queries Controller
 * 
 * @package 
 * @author  
 */
class QueriesController
{
	static public $app_controller;
	static public $property_id;
	static public $property_name;

	public function __construct() {
		self::$app_controller = new AppController();
		self::$property_id = self::$app_controller->sanitise_string($_REQUEST['prop_id']);
		self::$property_name = self::$app_controller->sanitise_string($_REQUEST['prop_name']);
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
			case 'GetAllQueries':
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$prop_name 		= self::$app_controller->sanitise_string($request->parameters['prop_name']);
				$company_id 	= $_SESSION['company_id'];
				$queries 		= self::set_up_queries ($prop_id, $prop_name, $company_id);
				return json_encode($queries);
				break;

			case 'GetAllUsers':
				
				$get_users 	= self::$app_controller->get_all_users ();
				return json_encode($get_users);
				break;

			case 'GetAllAdminUsers':
				
				$get_users 	= self::$app_controller->get_all_admin_users ();
				return json_encode($get_users);
				break;

			case 'GetQueriesInfo':

				$id 		= self::$app_controller->sanitise_string  ($request->parameters['id']);
				$get 		= self::$app_controller->get_queries_byid ($id);
				return json_encode($get[0]);
				break;
			case 'AssignUser': 
				$ID 		= self::$app_controller->sanitise_string ($request->parameters['id']);
				$AssineeID 	= self::$app_controller->sanitise_string ($request->parameters['assinee_id']);

				$assign 	= self::$app_controller->assign_queries_user ($ID, $AssineeID);
				return json_encode ($assign);
				break;
			default:
				if (self::$app_controller->check_if_logged ($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];

					$this_page 		= 'property' . self::$property_id;
					$current 		= 'queries' . self::$property_id;

					$aside_menu 	= self::$app_controller->get_aside_menu ($modules, $current);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Queries ' .self::$property_name,
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);
						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view ('Asidemenu', $pass);
						self::$app_controller->get_view ('Queries', $pass);
						self::$app_controller->get_footer (array('page'=>'queries'));
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

	/*** save permissions ***/
	static public function set_up_queries ($prop_id, $prop_name, $company_id) {
		$return_arr =  array();
		$properties = self::$app_controller->get_queries_list($prop_id);

		//btn btn-success dropdown-toggle

		foreach ($properties as $p) {

			$status  	  	= ucwords(strtolower($p['queryStatus']));
			$comment 	  	= $p['queryComments'];
			$id 	  	  	= $p['queryID'];
			
			$admin_user	  	= self::$app_controller->get_all_admin_managers ($company_id);

			$assignee_name	= $p['assignee_name'];
			$selected 	  	= 'Assign To';
			$sel_class 	  	= '';

			if (!empty($assignee_name)) {
				$selected 	= $assignee_name;
				$sel_class	= 'amber-200';
			}

			$action 	  = '<div class="btn-toolbar">';
			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-primary btn-sm " data-title="Edit" data-toggle="modal" data-target="#ImageArea" onclick="getImage('.$id.')" aria-expanded="false" title="View Image"><span class="glyphicon glyphicon-search"></span></button>';
			$action 	 .= '</div>';
			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-success btn-sm " data-title="Edit" data-toggle="modal" data-target="#MarkDone" onclick="SetID('.$id.')" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></button>';

			$action 	 .= '</div>';

			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<button class="btn btn-info btn-sm " data-title="Edit" data-toggle="modal" data-target="#EditModal" onclick="GetEdit('.$id.')" aria-expanded="false"><span class="glyphicon glyphicon-pencil"></span></button>';

			$action 	.= '</div>';

			$action 	 .= '<div class="btn-group">';
			$action 	 .= '<select class="assign_bill_user" id="' .$id. '" onchange="assign_user(this.value, ' .$id. ')">';
			$action 	 .= '<option selected="true">' .$selected. '</option>';
			foreach ($admin_user as $adm) {
				$action  .= '<option value="' .$adm['adminID']. '">' .$adm['full_name']. '</option>';
			}
			$action 	 .= '</select>';
			$action 	.= '</div>';


			$action 	.= '</div>';

			$return_arr[] =  array(
				'queryType' => ucwords(strtolower($p['queryType'])),
				'queryUsername' => $p['queryUsername'],
				'unitNo' => $p['unitNo'],
				'queryInput' => $p['queryInput'],
				'queryDate' => $p['queryDate'],
				'status' => $status,
				'comment' => $comment,
				'action' => $action
				
				);
		}

		return array('data' => $return_arr);
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
			case 'MarkDone':
				$Comment = self::$app_controller->sanitise_string($request->parameters['Comment']);
				$ID = self::$app_controller->sanitise_string($request->parameters['ID']);

				$done 	 = self::mark_done ($Comment, $ID);
			return json_encode($done);

			case 'SaveQuery':
				$QueryType 	= self::$app_controller->sanitise_string($request->parameters['QueryType']);
				// $UserID 	= self::$app_controller->sanitise_string($request->parameters['UsersList']);
				$AssineeID 	= self::$app_controller->sanitise_string($request->parameters['AssignTo']);
				$Query 		= self::$app_controller->sanitise_string($request->parameters['Query']);
				$Property	= self::$app_controller->sanitise_string($request->parameters['PropertyList']);
				$Unit 		= self::$app_controller->sanitise_string($request->parameters['Unit']);
				

				$image_data = array_map (self::$app_controller->sanitise_string, $_FILES);

				$save 		= self::submit_query ($QueryType, $AssineeID, $Query, $Property, $Unit, $image_data);
				return json_encode($save);
				break;

			case 'EditQuery':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['EditID']);
				$QueryType 	= self::$app_controller->sanitise_string($request->parameters['QueryTypeedt']);
				$UserID 	= self::$app_controller->sanitise_string($request->parameters['UsersListedt']);
				$Query 		= self::$app_controller->sanitise_string($request->parameters['Queryedt']);
				$Property	= self::$app_controller->sanitise_string($request->parameters['PropertyListedt']);
				$Unit 		= self::$app_controller->sanitise_string($request->parameters['Unitedt']);
				
				$edit 		= self::edit_query ($ID, $QueryType, $UserID, $Query, $Property, $Unit);
				return json_encode($edit);
				break;

			case 'GetImage':
					$id 	= $request->parameters['id'];
					$image 	= self::$app_controller->get_image_by_id ($id);
					$ret 	= array();


					if(!empty($image[0]['queryImage'])) {
						foreach ($image as $k) {

							$img  = (string)$k['queryImage'];

							$image = str_replace('[', '', $img);
							$image = str_replace(']', '', $image);
							$image = str_replace('"', '', $image);
							$ret   = array ('images'=> 'data:image/gif;base64,' . $image);
						}
					}else{
						$ret = array ('images'=> self::$app_controller->get_noimage_base64());
						}

					return json_encode($ret);
					
				break;
		}
	}

	/*** save comment ***/
	static public function mark_done  ($Comment, $ID) {

		if (!self::$app_controller->validate_variables ($Comment, 3)) {
			return array('status'  => false, 'text' => 'Invalid Comment');
		}

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$Comment 		= self::$app_controller->sanitise_string($Comment);
		$ID 			= self::$app_controller->sanitise_string($ID);

		$save 			= self::$app_controller->save_queries_comment ($Comment, $ID);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** save query ***/
	static public function submit_query ($QueryType, $AssineeID, $Query, $Property, $Unit, $image_data) {

		if (!self::$app_controller->validate_variables ($QueryType, 3)) {
			return array('status'  => false, 'text' => 'Invalid Query Type');
		}

		if (!self::$app_controller->validate_variables ($Property, 3)) {
			return array('status'  => false, 'text' => 'Invalid Property');
		}

		if (!self::$app_controller->validate_variables ($Unit, 3)) {
			return array('status'  => false, 'text' => 'Invalid Unit');
		}


		if (!is_numeric($AssineeID)) {
			return array('status'  => false, 'text' => 'Invalid Assinee ID');
		}

		if (!self::$app_controller->validate_variables ($Query, 3)) {
			return array('status'  => false, 'text' => 'Invalid Query');
		}

		$bin_string = file_get_contents($image_data["file"]["tmp_name"]);
		$hex_image  = base64_encode($bin_string);

		$save 		= self::$app_controller->save_query ($QueryType, $AssineeID, $Query, $Property, $Unit, $hex_image);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** edit query ***/
	static public function edit_query ($ID, $QueryType, $UserID, $Query, $Property, $Unit) {

		$queryinfo 		= self::$app_controller->get_queries_byid ($ID);

		// if (!is_numeric($ID) OR count($queryinfo) == 0) {
		// 	return array('status'  => false, 'text' => 'Invalid Query ID');
		// }

		// if (!self::$app_controller->validate_variables ($QueryType, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Query Type');
		// }

		// if (!self::$app_controller->validate_variables ($Property, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Property');
		// }

		// if (!self::$app_controller->validate_variables ($Unit, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Unit');
		// }

		// // if (!is_numeric($UserID)) {
		// // 	return array('status'  => false, 'text' => 'Invalid User ID');
		// // }

		
		// if (!self::$app_controller->validate_variables ($Query, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Query');
		// }


		$edit 		= self::$app_controller->update_query ($ID, $QueryType, $UserID, $Query, $Property, $Unit);

		if ($edit === true) {
			return array('status' => true, 'text' => 'Edited');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $edit);
		}
	}

}
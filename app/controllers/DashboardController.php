<?php
/**
 * Dashboard Controller
 * 
 * @package 
 * @author  
 */
class DashboardController
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
		$company_id 	= $_SESSION['company_id'];
		// die(var_dump($email));
		switch ($subRequest) {
			case 'GetMaintanaceList':
				$list = self::$app_controller->get_recent_maintenance_list();
			return json_encode(self::set_up_dashboard($list));

			case 'GetPropertyList':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$properties 	= self::$app_controller->get_property_list_permission ($prop_array);
				return json_encode($properties);
				break;
			break;
			case 'GetBillingList':
				$list = self::$app_controller->get_recent_billing_list();
			return json_encode (self::set_up_dashboard($list));
			break;
			case 'GetMyTaskList':
				$list = self::$app_controller->get_tasks_byemail($email);
			return json_encode (self::set_up_dashboard($list));
			break;

			case 'GetBarGraph':
				$graph = self::set_up_graph();
			return json_encode ($graph);
			break;

			case 'GetMaintenanceCards':
				$graph = self::set_up_cards();
			return json_encode ($graph);
			break;

			case 'GetAllActivities':
				$activities = self::$app_controller->get_all_activities();
				
			return self::set_timeline($activities);
			break;

			case 'GetAllEvents':
				$events = self::set_up_events($company_id);
				
			return json_encode($events);
			break;

			case 'GetAllAdminUsers':
				$company_id   = self::$app_controller->sanitise_string($request->parameters['company_id']);
				$admin_user	  = self::$app_controller->get_all_admin_managers ($company_id);
				
			return json_encode($admin_user);
			break;


			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 				= $_SESSION['email'];
					$first_name			= $_SESSION['first_name'];
					$last_name			= $_SESSION['last_name'];
					$modules			= $_SESSION['modules'];
					$user_id			= $_SESSION['user_id'];
					$company_id			= $_SESSION['company_id'];

					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);
					// die(var_dump(self::$app_controller->get_all_queries(self::$prop_array)));

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, 'dashboard');

					
					$pass 				= array(
											'full_name'  	 => $first_name.' '.$last_name, 
											'email' 	 	 => $email,
											'page_title' 	 => 'Dashboard',
											'page'		 	 => 'dashboard',
											'user_id'	 	 => $user_id,
											'company_id'	 => $company_id,
											'aside_menu' 	 => $aside_menu['html']
										);

					self::$app_controller->get_header ($pass);
					self::$app_controller->get_view ('Asidemenu', $pass);
					self::$app_controller->get_view ('Dashboard', $pass);
					self::$app_controller->get_footer ($pass);
					exit();
				}else{
					self::$app_controller->redirect_to('Login');
				}
			break;
		}

	}


	/**
	 * @param
	 * @return
	 */
	static public function set_up_events ($company_id) {

		$return_array 	= array();
		$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
		
		$queries 		= self::$app_controller->get_all_queries_company ($company_id, $prop_array, false);

		
		foreach ($queries as $q) {

			$return_array[] = array(
				
					'title' 		=> '<b>' . $q['queryType'] . ' Query</b>',
					'description' 	=> $q['developmentName'] ."<br />" .'Unit Number: ' . $q['unit_number'],
					'start' 		=> $q['queryDate'],
					'allday' 		=> true

					);

		}

		
		return $return_array;
	}


	static public function set_up_cards() {
		$arry 			= array();

		$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
		$queries 		= self::$app_controller->get_four_queries ($prop_array);
		$properties 	= self::$app_controller->get_property_list_permission ($prop_array);

		$company_id 	= $_SESSION['company_id'];

		

		foreach ($queries as $q) {

			$id 		 = $q['queryID'];
			$queryType 	 = $q['queryType'];
			$userId 	 = $q['queryUsername'];
			$assignee_id = $q['queryAssignee'];
			$unitId 	 = $q['unitNo'];
			$propId 	 = $q['propertyID'];
			$query 		 = $q['queryInput'];
			$image 		 = $q['queryImage'];
			$status 	 = $q['queryStatus'];
			$comment 	 = $q['queryComments'];
			$date 		 = self::$app_controller->format_date($q['queryDate']);
			$full_name   = $q['queryUsername'];

			$prop_det 	 = self::$app_controller->filter_by_value($properties, 'propertyID', $propId);
			foreach ($prop_det as $p) {
				$PropertyName = preg_replace('/\s+/', ' ', $p['propertyName']);
			}

			$img 		= '';
			$dir 		= '../companies/' .$company_id. '/properties/'.$propId.'/queries/';

			if(!empty($image)) {
				if (strpos($image, '.jpg') !== false)  {// if it's image name
				// die($dir.$image);
					$img 	= 'data:image/png;base64,' . base64_encode(file_get_contents($dir.$image));
				}else{
					$img 	= 'data:image/png;base64,' . $image;
				}

			}else{
				$img 	= self::$app_controller->get_noimage_base64();
			}

			$arry[] = array(
				'query_type'     => $queryType,
				'id'     		 => $id,
				'user_name'      => $full_name,
				'property_name'  => $PropertyName,
				'query' 	     => $query,
				'image' 	     => $img,
				'date' 	     	 => $date
				);

		}

		return $arry;
	}
	static public function set_up_graph() {

		$arry 			= array();
		$data 			= array();

		$properties 	= self::$app_controller->get_property_list(self::$company_id);


		// $maintenances 	= self::$app_controller->get_all_maintenance();
		$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
		$company_id 	= $_SESSION['company_id'];
		$maintenances 	= self::$app_controller->get_all_queries_company ($company_id, $prop_array, false);
		// echo json_encode($maintenances);
		$count = 0;

		foreach ($properties as $p) {
			$PropertyID		= $p['propertyID'];
			$PropertyName	= $p['propertyName'];
		

			$maintenance 	= self::$app_controller->filter_by_value($maintenances, 'propertyID', $PropertyID);
			$count_main 	= count($maintenance);

			if ($count_main > 0) {
				$data[] 	=  array(
									$count_main,
									preg_replace('/\s+/', ' ', $PropertyName)
								);
			}
			
			$count ++;
		}

		$arry	= $data;

		return $arry;

	}

	static public function set_timeline($arr) {

		$tenants 	= self::$app_controller->get_all_users_db();
		$adminusers = self::$app_controller->get_all_admin_users_db();

		$ret_arr =  '';
		foreach ($arr as $a) {

			$user_type = $a['user_type'];

			if ($user_type == 'tenant') {
				$user_id 	= $a['user_id'];
				$user 		= self::$app_controller->filter_by_value($tenants, 'id', $user_id);


				foreach ($user as $u) {
					$user_name 	= $u['full_name'];
				}

			}else{
				$user_id 	= $a['user_id'];
				$user 		= self::$app_controller->filter_by_value($adminusers, 'user_id', $user_id);

				foreach ($user as $u) {
					$user_name 	= $u['full_name'];
				}
			}

			
			$ret_arr .= '<div class="sl-item">';
			$ret_arr .= '	<div class="sl-content">';
			$ret_arr .= ' 		<div class="text-muted-dk">';
			$ret_arr .= 			self::$app_controller->format_date($a['date']);
			$ret_arr .= ' 		</div>';
			$ret_arr .= '		<p><a href class="text-info">'.$user_name.'</a> ' .$a['activity_description']. '</p>';
			$ret_arr .= ' 	</div>';
			$ret_arr .= '</div>';
		}

		return $ret_arr;
	}
	static public function set_up_dashboard($arr) {
		$ret_arr =  array();

		foreach ($arr as $a) {
			$ret_arr[] = array(
				ucwords(strtolower($a['queryType'])),
				$a['query'],
				$a['status'],
				$a['date']
				);
		}

		return array('data' => $ret_arr);
	}

	static public function set_up_mytasks($email){
		$ret_arr = array();

		$arr 	 = self::$app_controller->get_tasks_byemail($email);

		foreach ($arr as $a) {
			$ret_arr[] = array(
				ucwords(strtolower($a['queryType'])),
				$a['query'],
				ucwords(strtolower($a['status'])),
				$a['date']
				);
		}

		return array('data' => $ret_arr);

	}


	
}
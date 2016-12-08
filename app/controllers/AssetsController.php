<?php
/**
 * Assets Controller
 * 
 * @package 
 * @author  
 */
class AssetsController
{
	static public $app_controller;
	static public $property_id;
	static public $property_name;
	static public $prop_array;
	static public $company_id;


	public function __construct() {
		self::$app_controller 	= new AppController();
		self::$property_id   	= (isset($_REQUEST['prop_id'])) ? self::$app_controller->sanitise_string($_REQUEST['prop_id']) : '';
		self::$property_name 	= (isset($_REQUEST['prop_name'])) ? self::$app_controller->sanitise_string($_REQUEST['prop_name']) : '';

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
			case 'GetTable':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_asset_table ($prop_id, $company_id);

				return json_encode($table);
			break;

			case 'GetQRByID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$qr_code		= self::setup_qr_code ($ID);

				return $qr_code;
			break;

			case 'GetQRURL':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);

				$qr_deatails 	= self::get_qr_details ($ID);
				

				return json_encode($qr_deatails);
			break;

			case 'GetTableTrustees':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_trustees_table ($prop_id, $company_id);

				return json_encode($table);
			break;
			case 'GetAssetByID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$res 			= self::$app_controller->get_asset_byid ($ID);

				return json_encode($res[0]);
			break;
			case 'GetAssetInfo':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$asset 			= self::$app_controller->get_asset_info_byid ($ID);
				$page 	    	= self::get_supplier_landing_page($asset);

				echo $page;

				exit();
			break;

			case 'GetTimeline':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$ResID 			= self::$app_controller->sanitise_string ($request->parameters['ResID']);
				$graph 			= self::set_up_timeline ($prop_array, $company_id, $ResID);

			return json_encode ($graph);
			case 'UploadEmailImage':

				$upload 	= self::upload_image($_FILES);
			return stripslashes(json_encode($upload));
			case 'GetConstructorByPropID':
				$prop_id 	= self::$app_controller->sanitise_string ($request->parameters['prop_id']);
				$types 		= self::$app_controller->get_constructors_by_propid ($prop_id);
				return json_encode($types);
			break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];



					$this_page 		= 'property' . self::$property_id;
					$current 		= 'assets' 	 . self::$property_id;

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, $current);
					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Assets',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);

						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view   ('Asidemenu', $pass);
						self::$app_controller->get_view   ('Assets', $pass);
						self::$app_controller->get_footer (array('page' => 'assets'));
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
	 * POST Request
	 *
	 * @param  
	 * @return 
	 */
	
	public function post ($request) {
		$subRequest = (isset($request->url_elements[1])) ? $request->url_elements[1] : '';

		switch ($subRequest) {

			
			case 'CreateAsset':
				$PropertyID   	= self::$app_controller->sanitise_string($request->parameters['PropertyID']);
				$ContructorID   = self::$app_controller->sanitise_string($request->parameters['ContructorID']);
				$AssetName   	= self::$app_controller->sanitise_string($request->parameters['AssetName']);
				$Description   	= self::$app_controller->sanitise_string($request->parameters['Description']);
				$Make   		= self::$app_controller->sanitise_string($request->parameters['Make']);
				$Location   	= self::$app_controller->sanitise_string($request->parameters['Location']);
				$SerialNumber   = self::$app_controller->sanitise_string($request->parameters['SerialNumber']);
				$CostOfAsset   	= self::$app_controller->sanitise_string($request->parameters['CostOfAsset']);
				$LastInspected  = self::$app_controller->sanitise_string($request->parameters['LastInspected']);
				$InspectionDueDate  = self::$app_controller->sanitise_string($request->parameters['InspectionDueDate']);
				
				

				$save 			= self::create_asset (
											$PropertyID,
											$ContructorID,
											$AssetName,
											$Description,
											$Make,
											$Location,
											$SerialNumber,
											$CostOfAsset,
											$LastInspected,
											$InspectionDueDate
										);

				
				return json_encode($save);

			break;

			case 'UpdateAsset':
				$AssetID			= self::$app_controller->sanitise_string($request->parameters['AssetID']);
				$ContructorID		= self::$app_controller->sanitise_string($request->parameters['ContructorID']);
				$AssetName			= self::$app_controller->sanitise_string($request->parameters['AssetName']);
				$Description		= self::$app_controller->sanitise_string($request->parameters['Description']);
				$Make				= self::$app_controller->sanitise_string($request->parameters['Make']);
				$Location			= self::$app_controller->sanitise_string($request->parameters['Location']);
				$SerialNumber		= self::$app_controller->sanitise_string($request->parameters['SerialNumber']);
				$CostOfAsset		= self::$app_controller->sanitise_string($request->parameters['CostOfAsset']);
				$LastInspected		= self::$app_controller->sanitise_string($request->parameters['LastInspected']);
				$InspectionDueDate	= self::$app_controller->sanitise_string($request->parameters['InspectionDueDate']);

				$save 				= self::edit_asset (
											$AssetID,
											$ContructorID,
											$AssetName,
											$Description,
											$Make,
											$Location,
											$SerialNumber,
											$CostOfAsset,
											$LastInspected,
											$InspectionDueDate
										);

				
				return json_encode($save);

			break;

			case 'DeleteAsset': 
				$ID    				= self::$app_controller->sanitise_string($request->parameters['id']);
				$delete    			= self::delete_asset ($ID);

				return json_encode($delete);
			break;


			case 'ArchiveUnit':	
				$ID   					= self::$app_controller->sanitise_string($request->parameters['ID']);
				$save 					= self::archive_unit ($ID);

				return json_encode($save);
			break;

			case 'SaveComment': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentText']);
				$ResID 		= self::$app_controller->sanitise_string($request->parameters['ResID']);
				$UserID 	= $_SESSION['user_id'];
				$CompanyID 	= $_SESSION['company_id'];
				$FileData 	= array_map(self::$app_controller->sanitise_string, $_FILES);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyID']);

				
				$send    	= self::save_comment ($Message, $ResID, $FileData, $UserID, $CompanyID, $PropertyID);
				return json_encode($send);
			break;

			case 'SaveEmail': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['EmailText']);
				$ResID 		= self::$app_controller->sanitise_string($request->parameters['ResID']);
				$UserID 	= $_SESSION['user_id'];
				$CompanyID 	= $_SESSION['company_id'];
				$FileData 	= array_map(self::$app_controller->sanitise_string, $_FILES);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyID']);


				
				$send    	= self::save_email ($Message, $ResID, $FileData, $UserID, $CompanyID, $PropertyID);
				return json_encode($send);
			break;

			case 'SendSMS': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentSMS']);
				$ResID 		= self::$app_controller->sanitise_string($request->parameters['ResID']);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyID']);
				$UserID 	= $_SESSION['user_id'];

				$send    	= self::send_sms (
								$Message,
								$ResID,
								$PropertyID,
								$UserID
							  );
				return json_encode($send);
			break;

			


			// UpdateSaveLease
		}
	}


	
	/*** save query ***/
	static public function create_asset (
										$PropertyID,
										$ContructorID,
										$AssetName,
										$Description,
										$Make,
										$Location,
										$SerialNumber,
										$CostOfAsset,
										$LastInspected,
										$InspectionDueDate
									) {

		if (!is_numeric($PropertyID)) {
			return array('status'  => false, 'text' => 'Invalid Property ID');
		}

		if (!is_numeric($ContructorID)) {
			return array('status'  => false, 'text' => 'Invalid Property ID');
		}

		if (!self::$app_controller->validate_variables ($AssetName, 3)) {
			return array('status'  => false, 'text' => 'Invalid Asset Name');
		}

		if (!self::$app_controller->validate_variables ($Description, 3)) {
			return array('status'  => false, 'text' => 'Invalid Description');
		}


		
		$save 		= self::$app_controller->insert_asset (
								$PropertyID,
								$ContructorID,
								$AssetName,
								$Description,
								$Make,
								$Location,
								$SerialNumber,
								$CostOfAsset,
								$LastInspected,
								$InspectionDueDate
							);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	/*** edit query ***/
	static public function edit_asset (
								$AssetID,
								$ContructorID,
								$AssetName,
								$Description,
								$Make,
								$Location,
								$SerialNumber,
								$CostOfAsset,
								$LastInspected,
								$InspectionDueDate
							) {

		$get_ress = self::$app_controller->get_asset_byid ($AssetID);


		if (count($get_ress) === 0) {
			return array('status'  => false, 'text' => 'Invalid Asset ID');
		}

		if (!self::$app_controller->validate_variables ($ContructorID, 3)) {
			return array('status'  => false, 'text' => 'Invalid Contructor ID');
		}

		if (!self::$app_controller->validate_variables ($AssetName, 3)) {
			return array('status'  => false, 'text' => 'Invalid Asset Name');
		}

		if (!self::$app_controller->validate_variables ($Description, 3)) {
			return array('status'  => false, 'text' => 'Invalid Description');
		}

		
		$save 		= self::$app_controller->update_asset (
								$AssetID,
								$ContructorID,
								$AssetName,
								$Description,
								$Make,
								$Location,
								$SerialNumber,
								$CostOfAsset,
								$LastInspected,
								$InspectionDueDate
							);

		if ($save === true) {
			return array('status' => true, 'text' => 'Updated');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	static public function delete_asset ($ID) {

		$get_contr  = self::$app_controller->get_asset_byid ($ID);

		if (!is_numeric ($ID) OR count($get_contr) == 0) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$save 		= self::$app_controller->delete_this_asset ($ID);

		if ($save === true) {
			return array('status' => true, 'text'  => 'Deleted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $edit);
		}

	}


	static public function get_supplier_landing_page ($asset) { 

		foreach ($asset as $a) {
			$company_name 	= $a['companyName'];
			$property_name 	= $a['propertyName'];
			$supplier_name 	= $a['supplier_name'];
			$asset_name 	= $a['asset_name'];
			$description 	= $a['description'];
			$make 			= $a['make'];
			$location 		= $a['location'];
			$serial_number	= $a['serial_number'];
			$cost_of_asset	= $a['cost_of_asset'];
			$last_inspected	= $a['last_inspected'];
			$inspection_due_date	= $a['inspection_due_date'];
		}

		$html  	  = file_get_contents('public/emails/asset_information.html'); // external css

		$ret_html = preg_replace('/[\[{\(]company_name+[\]}\)]/' , $company_name, $html);
		$ret_html = preg_replace('/[\[{\(]property_name+[\]}\)]/' , $property_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]supplier_name+[\]}\)]/' , $supplier_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]asset_name+[\]}\)]/' , $asset_name, $ret_html);
		$ret_html = preg_replace('/[\[{\(]description+[\]}\)]/' , $description, $ret_html);
		$ret_html = preg_replace('/[\[{\(]make+[\]}\)]/' , $make, $ret_html);
		$ret_html = preg_replace('/[\[{\(]location+[\]}\)]/' , $location, $ret_html);
		$ret_html = preg_replace('/[\[{\(]serial_number+[\]}\)]/' , $serial_number, $ret_html);
		$ret_html = preg_replace('/[\[{\(]cost_of_asset+[\]}\)]/' , self::$app_controller->get_money_value_cents($cost_of_asset), $ret_html);
		$ret_html = preg_replace('/[\[{\(]last_inspected+[\]}\)]/' , $last_inspected, $ret_html);
		$ret_html = preg_replace('/[\[{\(]inspection_due_date+[\]}\)]/' , $inspection_due_date, $ret_html);
		return $ret_html;
	}

	static public function get_asset_table ($property_id, $company_id) {

		$return 	 = array();

		$residents   = self::$app_controller->get_all_assets ($property_id);

		

		foreach ($residents as $c) {

			$buttons 	= '<button class="btn btn-primary btn-sm" data-title="Get QRCode" data-toggle="modal" data-target="#QRCodeModal" data-res-id="'. $c['id'].'" aria-expanded="false"><span class="glyphicon glyphicon-qrcode"></span></button>';
			$buttons 	.= '<button class="btn btn-info btn-sm" data-title="Edit" data-toggle="modal" data-target="#EditAssetModal" data-res-id="'. $c['id'].'" aria-expanded="false"><span class="glyphicon glyphicon-pencil"></span></button>';
			$buttons 	.= '<button class="btn btn-danger btn-sm" data-title="Delete" data-toggle="modal" data-target="#DeleteAssetModal" data-res-id="'.$c['id'].'" aria-expanded="false"><span class="glyphicon glyphicon-trash"></span></button>';


			$return[] 	= array(
				'supplier_name'  	=> $c['company_name'],
				'asset_name'  		=> $c['asset_name'],
				'description'  		=> $c['description'],
				'make' 				=> $c['make'],
				'location' 			=> $c['location'],
				'serial_number' 	=> $c['serial_number'],
				'cost_of_asset' 	=> $c['cost_of_asset'],
				'last_inspected' 	=> $c['last_inspected'],
				'buttons' 			=> $buttons
				);
		}
		
		return $return;

	}

	static public function get_qr_details ($ID) {

		$return = array();

		$url	= 'Assets/GetQRByID?ID=' .$ID;

		$assets = self::$app_controller->get_asset_by_id($ID);

		foreach ($assets as $a) {
			$supplier_id	= $a['supplier_id'];
			$prop_id		= $a['prop_id'];
			$asset_name		= $a['asset_name'];
			$description	= $a['description'];
			$make			= $a['make'];
			$location		= $a['location'];
			$serial_number	= $a['serial_number'];
			$cost_of_asset	= $a['cost_of_asset'];
			$last_inspected	= $a['last_inspected'];
			$created		= $a['created'];

			$return 		= array(
					'asset_name' 	=> $asset_name,
					'url' 			=> $url,
					'location' 		=> $location,
					'serial_number' => $serial_number
				);
			
		}


		return $return;
	}
	static public function set_up_timeline ($prop_array, $company_id, $ResID) {
		$ret_array 		= array();

		$sms_list 		= self::$app_controller->get_res_sms_coms ($ResID);
		$comm_list 		= self::$app_controller->get_res_comment_coms ($ResID);
		$email_list 	= self::$app_controller->get_res_email_coms ($ResID);
		$dir 			= '../companies/' .$company_id. '/residents/' .$ResID;

		// work out return array
		if (!empty($sms_list)) {// notifications

			foreach ($sms_list as $n) {
				$ret_array[] = array(
					'sort' 	  		=> $n['created'],
					'id' 	  		=> $n['id'],
					'subject' 		=> $n['full_name'] . ' sent an SMS',
					'message' 		=> $n['sms_text'],
					'type' 	  		=> 'SMS',
					'file' 	  		=> '',
					'resident_id'	=> $ResID,
					'date'    		=> self::$app_controller->human_timing($n['created']) . ' ago'
					);
			}

		}

		if (!empty($comm_list)) {// notifications

			foreach ($comm_list as $c) {

				if (!empty($c['file'])) {
					$file 		 = $c['file'];
				}else{
					$file 		 = '';
				}

				
				$ret_array[] = array(
					'sort' 	  		=> $c['date_created'],
					'id' 	  		=> $c['id'],
					'subject' 		=> $c['full_name'] . ' commented: ',
					'message' 		=> $c['comment_text'],
					'type' 	  		=> 'Comment',
					'file' 	  		=> $file,
					'resident_id'	=> $ResID,
					'date'    		=> self::$app_controller->human_timing($c['date_created']) . ' ago'
					);
			}

		}

		if (!empty($email_list)) {// notifications

			foreach ($email_list as $c) {

				if (!empty($c['file'])) {
					$file 		 = $c['file'];
				}else{
					$file 		 = '';
				}
				
				$ret_array[] = array(
					'sort' 	  		=> $c['created'],
					'id' 	  		=> $c['id'],
					'subject' 		=> $c['full_name'] . ' sent an email: ',
					'message' 		=> $c['email_text'],
					'type' 	  		=> 'Email',
					'file' 	  		=> $file,
					'resident_id'	=> $ResID,
					'date'    		=> self::$app_controller->human_timing($c['created']) . ' ago'
					);
			}

		}

		$sorted = self::$app_controller->array_orderby ($ret_array, 'sort', SORT_DESC);

		return $sorted;

	}
	
	static public function get_archived_resident_table ($property_id, $company_id) {

		$return 	 = array();

		$residents   = self::$app_controller->get_archived_residents ($property_id);

		foreach ($residents as $c) {
			$return[] 	= array(
				'unitNumber'  			=> $c['unitNumber'],
				'residentName'  		=> $c['residentName'],
				'residentPhone'  		=> $c['residentPhone'],
				'residentCellphone' 	=> $c['residentCellphone'],
				'residentNotifyEmail' 	=> $c['residentNotifyEmail'],
				'residentType' 			=> $c['residentType'],
				'residentTrustee' 		=> $c['residentTrustee'],
				'residentStatus' 		=> $c['residentStatus'],
				'buttons' 				=> '<button class="btn btn-default btn-sm" data-title="Communicate" data-toggle="modal" data-target="#ArchivedComHistoryModal" data-res-id="'.$c['id'].'" data-res-name="'.$c['residentName'].'" aria-expanded="false"><span class="fa fa-comment-o"></span></button>'
				);
		}
		
		return $return;

	}

	static public function setup_qr_code ($ID) {
		$url		= 'http://manage.connectliving.co.za/Assets/GetAssetInfo?ID=' .$ID;
		$return   	= self::$app_controller->get_qr_code ($url);

		return  $return;

	}


	/*** archive unit ***/
	static public function archive_unit ($ID) {
		$check_unit 	= self::$app_controller->get_resident_byid ($ID);

		if (count($check_unit) === 0) {
			return array('status'  => false, 'text' => 'Resident doesnt exist');
		}

		$archive 		= self::$app_controller->archive_record ($ID);

		if ($archive === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

}
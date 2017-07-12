<?php
/**
 * @package  
 * @author   Sboniso Nzimande
 * @abstract
 */
abstract class AppModel 
{
	/* Connection variable */
	static public $mysqli;
	static public $mssql;

	public function __construct() {
		self::$mysqli      = new MySqlDB;
		// self::$mssql       = new MsSqlDB;// Connect to a MsSql database

		self::change_timezone();
	}

	/**
	 * Escape string
	 * 
	 * @param  $string
	 * @return string
	 */
	static public function clean_string ($string) {
		return self::$mysqli->escape_string ($string);
		// return $string;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_list_db ($property) {


		if ($property) {
		    $sql[]  = " p.companyID =  '".$property."'";
		}

		$query 		= "SELECT 	
							*
						FROM properties AS p ";

		if (!empty($sql)) {
		    $query .= ' WHERE ' . implode(' AND ', $sql);
		}

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_list_permission_db ($properties) {


		if ($properties) {
		    $sql[]  = " p.propertyID IN(".$properties.") ";
		}

		$query 		= "SELECT 	
							*
						FROM properties AS p ";

		if (!empty($sql)) {
		    $query .= ' WHERE ' . implode(' AND ', $sql);
		}


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_tenant_list_db ($prop_id) {

		$query 		= "SELECT TenantID 
							FROM dbo.Tenants 
						WHERE PropertyID = '" .$prop_id. "';";

		$stmt   	= self::$mssql->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_array_mssql ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_residents_db ($prop_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `residents` AS r 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = r.propertyID 
						WHERE r.propertyID = '" .$prop_id. "'
						ORDER BY r.created DESC;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_contructors_db ($prop_id) {

		$query 		= "SELECT 
						  con.*,
						  st.`service_name`,
						  p.`propertyName` 
						FROM
						  `constructors` AS con 
						  INNER JOIN `service_types` AS st 
						    ON st.`id` = con.`service_id` 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = con.prop_id 
						  INNER JOIN companies AS c 
						    ON c.companyID = p.companyID 
						WHERE con.`prop_id` = '" .$prop_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_suppliers_db ($company_id = false, $prop_id = false) {

		if ($company_id) {
			$sql[]  = " co.companyID = '".$company_id."' ";
		}

		if ($prop_id) {
			$sql[]  = " c.prop_id = '".$prop_id."' ";
		}

		$query 		= "SELECT 
						  c.*,
						  st.`service_name`,
						  p.`propertyName`,
						  co.`companyName` 
						FROM
						  `constructors` AS c 
						  INNER JOIN `service_types` AS st 
						    ON st.`id` = c.`service_id` 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = c.prop_id 
						  INNER JOIN companies AS co 
						    ON co.companyID = p.companyID ";

		if (!empty($sql)) {
			$query .= ' WHERE ' . implode(' AND ', $sql);
		}

		$query 			.= "	GROUP BY c.prop_id,
								  c.service_id,
								  c.company_name ";

		// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_prop_suppliers_db ($prop_id) {

		$query 		= "SELECT 
						  c.*,
						  st.`service_name`,
						  p.`propertyName` 
						FROM
						  `constructors` AS c 
						  INNER JOIN `service_types` AS st 
						    ON st.`id` = c.`service_id` 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = c.prop_id
						INNER JOIN companies AS co 
						    ON co.companyID = p.companyID 
						WHERE c.prop_id = '".$prop_id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_sms_balance_bycompany_db ($company_id) {

		$query 		= "SELECT 
						  *
						FROM
						  `sms_credits` AS s
						WHERE s.`company_id` 	 = '".$company_id."'
						AND s.`purchase_status`  = 'active';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_sms_credit_bycompany_db ($company_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `sms_credits` AS s 
						  INNER JOIN `companies` AS c 
						    ON c.`companyID` = s.`company_id` 
						WHERE s.`company_id` = '".$company_id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_minutes_db ($prop_id) {

		$query 		= "SELECT 
						  *
						FROM
						  `meeting_minutes` AS m
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = m.prop_id 
						  INNER JOIN companies AS co 
						    ON co.companyID = p.companyID 
						WHERE m.`prop_id` = '" .$prop_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_events_db ($prop_id) {

		$query 		= "SELECT 
						  *
						FROM
						  `trustree_calender_events` AS e
						WHERE e.`prop_id` = '" .$prop_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_assets_db ($prop_id) {

		$query 		= "SELECT 
						  a.*,
						  c.`company_name`,
						  p.`propertyName` 
						FROM
						  `assets` AS a 
						  INNER JOIN `constructors` AS c 
						    ON c.`id` = a.`supplier_id` 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = a.prop_id 
						  INNER JOIN companies AS co 
						    ON co.companyID = p.companyID 
						WHERE a.`prop_id` = '" .$prop_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_doc_types_db () {

		$query 		= "SELECT 
						  *
						FROM
						  `document_types`;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_prop_venues_db ($prop_id) {

		$query 		= "SELECT 
						  *
						FROM
						  `property_venues`

						WHERE prop_id = '".$prop_id."';";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_venue_details_db ($venue_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `property_venues` AS pv 
						  INNER JOIN `venues_days_open` AS vdo 
						    ON vdo.`venue_id` = pv.`id` 
						WHERE pv.`id` = '".$venue_id."';";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_venue_bookings_db ($venue_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `venue_bookings` AS vb 
						WHERE vb.`venue_id` = '".$venue_id."';";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_venue_booking_date_db ($VenueID, $BookingDate, $BookingTimeFrom, $BookingTimeTo) {

		$query 		= "SELECT 
						  * 
						FROM
						  `venue_bookings` AS vb 
						WHERE vb.`venue_id` = '".$VenueID."'
							AND vb.`booking_date` = '".$BookingDate."' 
							AND (vb.`booking_time_from` = '".$BookingTimeFrom."' OR  vb.`booking_time_to` = '".$BookingTimeTo."') ;";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_venue_days_open_db ($VenueID, $day_week) {

		$query 		= "SELECT 
						  * 
						FROM
						  `venues_days_open` AS dop 
						WHERE dop.`venue_id` = '".$VenueID."'
							AND dop.`day` = '".$day_week."' ;";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_service_types_db () {

		$query 		= "SELECT 
						  *
						FROM
						  `service_types`;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_service_types_by_id_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  `service_types`
						WHERE id = '".$id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_forms_db ($prop_id) {

		$query 		= "SELECT 
						  r.*,
						  p.`propertyName` 
						FROM
						  `resident_forms` AS r 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = r.`prop_id` 
						WHERE r.prop_id = '" .$prop_id. "'
						ORDER BY r.created DESC;";

						// echo $query;


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_trustees_residents_db ($prop_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `residents` AS r 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID     = r.propertyID 
						WHERE r.propertyID 		= '" .$prop_id. "'
						 	AND r.residentTrustee 	= 'yes'
						ORDER BY r.created DESC;";



		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_archived_residents_db ($prop_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `residents_archive` AS r 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = r.propertyID 
						WHERE r.propertyID = '" .$prop_id. "'
						ORDER BY r.created DESC;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_users_db () {

		$query 		= "SELECT 
						  u.*,
						  CONCAT_WS(' ',u.firstName,u.lastName) AS full_name
						FROM
						  users AS u";
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_admin_users_db () {

		$query 		= "SELECT 
						  u.*,
						  CONCAT_WS(' ',u.first_name,u.last_name) AS full_name
						FROM
						  admin_users AS u";
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_admin_managers_db ($company_id) {

		if ($company_id) {
			$sql[]  = " u.companyID = '".$company_id."' ";
		}

		$query 		= " SELECT 
						  u.*,
						  CONCAT_WS(' ', u.firstName, u.lastName) AS full_name
						FROM
						  admin_managers AS u ";

		
		if (!empty($sql)) {
			$query .= ' WHERE ' . implode(' AND ', $sql);
		}

		// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_app_users_db ($property_id) {

		$query 		= " SELECT 
						  ar.*,
						  pr.`propertyName` AS property_name 
						FROM
						  app_registrations AS ar 
						  INNER JOIN properties AS pr 
						    ON pr.`propertyID` = ar.`propertyID` 
						WHERE ar.`propertyID`  = '" .stripslashes($property_id). "'
						

						ORDER BY `registeredDate` DESC ;";

		
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_app_users_byid_db ($user_id) {

		$query 		= " SELECT 
						  ar.*,
						  pr.`propertyName` AS property_name 
						FROM
						  app_registrations AS ar 
						  INNER JOIN properties AS pr 
						    ON pr.`propertyID` = ar.`propertyID` 
						WHERE ar.`id`  = '" .stripslashes($user_id). "'
						ORDER BY `registeredDate` DESC ;";

						// echo $query ;

		
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_suppliers_byid_db ($supplier_id) {

		$query 		= "SELECT 
						  co.*,
						  sd.`supplier_id` AS supplier_id 
						FROM
						  constructors AS co
						  INNER JOIN serviceondemand_jobs AS sd
						    ON co.`id` = sd.`supplier_id` 
						WHERE co.`id`  = '" .$supplier_id. "'
						ORDER BY `created` DESC ;";

						// echo $query ;

		
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}



	/**
	 * @param  
	 * @return 
	 */
	static public function get_billing_list_db ($prop_id) {

		$query 		= "SELECT 
						  m.*,
						  CONCAT_WS(' ',u.firstName,u.lastName) AS full_name
						FROM
						  billing AS m
						  LEFT OUTER JOIN users AS u
						  ON u.id = m.userId
						WHERE m.propId = '" .$prop_id. "'
						ORDER BY m.date desc;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_queries_list_db ($prop_id) {

		$query 		= "SELECT
						  q.*,
						  CONCAT_WS(' ',a.firstName,a.lastName) AS assignee_name
						FROM queries AS q
						  LEFT OUTER JOIN admin_managers AS a
						    ON q.queryAssignee = a.adminID
						WHERE q.propertyID = '" .$prop_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}
	

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_billing_db () {

		$query 		= "SELECT
						  m.*,
						  CONCAT_WS(' ',u.firstName,u.lastName) AS full_name,
						  CONCAT_WS(' ',au.first_name,au.last_name) AS assignee_name
						FROM billing AS m
						  LEFT OUTER JOIN users AS u
						    ON u.id = m.userId
						  LEFT OUTER JOIN admin_users AS au
						    ON au.user_id = m.assignee_id
						ORDER BY m.date DESC;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function get_provinces_db () {

		$query 		= "SELECT 
						  rep_id, region
						FROM
						  `reps` 
						WHERE region IS NOT NULL 
						GROUP BY region ;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}



	/**
	 * @param  
	 * @return 
	 */
	static public function get_stores_by_province_db ($province) {

		$query 		= "SELECT 
						  * 
						FROM
						  reps AS r 
						  INNER JOIN `rep_store` AS rs 
						    ON r.`rep_id` = rs.`rep_id` 
						  INNER JOIN `stores` AS s 
						    ON rs.`store_id` = s.`store_id` 
						WHERE r.region = '" .$province. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_image_by_id_bd ($id) {

		$query 		= "SELECT 
						  queryImage 
						FROM
						  queries 
						WHERE queryID = '" .$id. "';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_app_registration_by_device_db ($device_token) {

		$query 		= "SELECT 
						  ar.id AS a_user_id,
						  ar.*,
						  pr.`propertyName` AS property_name,
						  pr.`buildingManagerName`,
						  pr.`buildingManagerEmail`,
						  pr.`buildingManagerPhone`,
						  pr.`propertyAddress`,
						  pr.`modules`
						FROM
						  app_registrations AS ar 
						  INNER JOIN properties AS pr 
						    ON pr.`propertyID` = ar.`propertyID` 
						WHERE ar.userDeviceToken = '" .stripslashes($device_token). "'
						AND  ar.userStatus = 'active'

						ORDER BY `registeredDate` DESC ;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function job_quotes_by_jobid_db ($job_id) {

		$query 		= "SELECT 
						  *
						FROM
						  job_quotes
						WHERE  job_id = '" .stripslashes($job_id). "';";


		$stmt   	= self::$mysqli->query ($query) or die ('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_app_registration_by_company_db ($company_id, $property_id, $unit_number, $device_token) {

		$query 		= "SELECT 
						  ar.id AS a_user_id,
						  ar.*,
						  pr.`propertyName` AS property_name,
						  pr.`buildingManagerName`,
						  pr.`buildingManagerEmail`,
						  pr.`buildingManagerPhone`,
						  pr.`propertyAddress`,
						  pr.`modules` 
						FROM
						  app_registrations AS ar 
						  INNER JOIN properties AS pr 
						    ON pr.`propertyID` = ar.`propertyID` 
						WHERE ar.userDeviceToken = '" .stripslashes($device_token). "' 
						  AND ar.`userStatus` = 'active' 
						  AND ar.`companyID`  = '".stripslashes($company_id)."' 
						  AND ar.`propertyID` = '".$property_id."' 
						  AND ar.`unitNo` 	  = '".$unit_number."' 
						ORDER BY `registeredDate` DESC ;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_app_registration_by_property_db ($device_token, $property_id) {

		$query 		= "SELECT 
						  ar.*,
						  pr.`propertyName` AS property_name,
						  pr.`buildingManagerName`,
						  pr.`buildingManagerEmail`,
						  pr.`buildingManagerPhone`,
						  pr.`propertyAddress`,
						  pr.`modules`
						FROM
						  app_registrations AS ar 
						  INNER JOIN properties AS pr 
						    ON pr.`propertyID` = ar.`propertyID` 
						WHERE ar.userDeviceToken = '" .stripslashes($device_token). "' 
							AND ar.`propertyID` = '".$property_id."'
						ORDER BY `registeredDate` DESC ;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_app_registration_by_id_db ($device_token, $property_id) {

		$query 		= "SELECT 
						  ar.*,
						  pr.`propertyName` AS property_name,
						  pr.`buildingManagerName`,
						  pr.`buildingManagerEmail`,
						  pr.`buildingManagerPhone`,
						  pr.`propertyAddress`,
						  pr.`modules`
						FROM
						  app_registrations AS ar 
						  INNER JOIN properties AS pr 
						    ON pr.`propertyID` = ar.`propertyID` 
						WHERE ar.userDeviceToken = '" .stripslashes($device_token). "' 
							AND ar.`id` = '".$property_id."'
						ORDER BY `registeredDate` DESC ;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_app_registration_by_userid_db ($user_id) {

		$query 		= "SELECT 
						  ar.*,
						  pr.`propertyName` AS property_name,
						  pr.`buildingManagerName`,
						  pr.`buildingManagerEmail`,
						  pr.`buildingManagerPhone`,
						  pr.`propertyAddress`,
						  pr.`modules`
						FROM
						  app_registrations AS ar 
						  INNER JOIN properties AS pr 
						    ON pr.`propertyID` = ar.`propertyID` 
						WHERE ar.`id` = '".$user_id."'
						ORDER BY `registeredDate` DESC ;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	

	/**
	 * @param  
	 * @return 
	 */
	static public function get_service_jobs_db ($prop_id, $company_id) {

		$query 		= "SELECT 
						  s.*,
						  c.`company_name` AS supplier_name 
						FROM
						  serviceondemand_jobs AS s 
						  INNER JOIN `constructors` AS c 
						    ON s.`supplier_id` = c.`id` 
						WHERE s.property_id  = '" .stripslashes ($prop_id). "'
							AND s.company_id = '" .stripslashes ($company_id). "'
							GROUP BY s.id
							ORDER BY s.created DESC;";

							// echo $query;


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_service_jobs_byid_db ($id) {

		$query 		= "SELECT 
						  s.*,
						  c.`id` AS service_type_id,
						  c.`company_name` AS supplier_name 
						FROM
						  serviceondemand_jobs AS s 
						  INNER JOIN `constructors` AS c 
						    ON s.`supplier_id` = c.`id` 
						WHERE s.id  = '" .stripslashes ($id). "'
							ORDER BY s.created DESC;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_logs_db ($company_id) {

		if ($company_id) {
		    $sql[]  = " l.companyId = '".$company_id."' ";
		}

		$query 		= "SELECT 
						  l.*,
						  c.`companyName` 
						FROM
						  emergency_log AS l 
						  INNER JOIN `companies` AS c 
						    ON l.companyId = c.companyID  ";


		if (!empty($sql)) {
			$query .= ' WHERE ' . implode(' AND ', $sql);
		}

		// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_tasks_byemail_db ($email) {

		$query 		= "SELECT *
						FROM maintenance AS m
						  INNER JOIN billing AS b
						    ON b.assignee_id = m.assignee_id
						  INNER JOIN admin_users AS u
						    ON b.assignee_id = u.user_id
						WHERE u.email = '".$email."';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_comp_image_by_id_bd ($id) {

		$query 		= "SELECT 
						  images 
						FROM
						  promotion_compliance
						WHERE compliance_id = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_promotion_info_db () {

		$query 		= "SELECT 
						  p.*,
						  s.`store_id`,
						  s.`date`,
						  st.`storeName3` AS store_name,
						  s.`no_of_bins` AS bin_placed 
						FROM
						  `store_promotions` AS p 
						  INNER JOIN `promotion_strikerate` AS s 
						    ON s.promo_id = p.`promo_id` 
						  INNER JOIN `stores` AS st 
						    ON st.store_id = s.`store_id` 
						ORDER BY p.`date_created` DESC,
						  s.`date` DESC;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * @param  
	 * @return 
	 **/
	static public function get_today_checkin_db () {

		$query 		= "SELECT 
						  l.*,
						  st.`storeName3` AS store_name,
						  st.`storeName` AS store_name2
						FROM
						  `locations` AS l 
						  INNER JOIN `stores` AS st 
						    ON st.`store_id` = l.`store_id` 
						WHERE FROM_UNIXTIME(l.`checkIn`, '%Y-%m-%d') = CURDATE() 
						ORDER BY checkIn DESC ";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_most_active_rep_db () {

		$query 		= "SELECT 
						  * 
						FROM
						  `promotion_strikerate` AS ps 
						  INNER JOIN `promotion_compliance` AS pc 
						    ON pc.`user_id` = ps.`user_id` 
						    INNER JOIN `reps` AS r
						    ON r.`rep_id` = ps.`user_id`
						HAVING MAX(pc.user_id) 
						  AND MAX(ps.user_id) ";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_perm_all_db () {

		$query 		= "SELECT 
						  * 
						FROM
						  admin_permissions;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_notification_db ($company_id, $property_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `notifications` AS n 
						WHERE n.`companyID` = '".$company_id."' 
						  AND n.`propertyID` = '".$property_id."' 
						ORDER BY n.`showDateFrom` DESC ;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_activities_db () {

		$query 		= "SELECT
						  a.*
						FROM user_activities AS a
						  INNER JOIN admin_managers AS m
						    ON m.adminID = a.user_id
						ORDER BY a.date DESC ;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_notifications_db ($company_id) {

		$query 		= "SELECT 
						  *
						FROM
						  notifications AS n
						WHERE n.companyID = '".$company_id."'
						ORDER BY n.showDateFrom DESC ;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_calendar_reminders_db ($company_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `calendar_reminders` AS r 
						  INNER JOIN `admin_managers` AS m 
						    ON m.`adminID` = r.`user_id` 						
						WHERE r.company_id = '".$company_id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * update_user_info_db
	 * @param  
	 * @return 
	 */
	static public function update_user_info_db ($ID, $RepName, $Password, $Agency, $Status) {

		$query 		= "UPDATE 
						    `reps` 
						  SET
						    repName = '".$RepName."',
						    password = '".$Password."',
						    agency = '".$Agency."',
						    status = '".$Status."' 
						  WHERE rep_id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function updated_app_user_db (
								$user_id,
								$full_name,
								$email,
								$mobile_number,
								$unit_number
							) {

		$query 		= "UPDATE 
						    `app_registrations` 
						  SET
						    userFullname 	= '".$full_name."',
						    userEmail 		= '".$email."',
						    userCellphone	= '".$mobile_number."',
						    unitNo			= '".$unit_number."'
						  WHERE id = '" .$user_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function bock_user_db ($ID) {

		$query 		= "UPDATE 
						    `app_registrations` 
						  SET
						    userStatus = 'blocked'
						  WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function unbock_user_db ($ID) {

		$query 		= "UPDATE 
						    `app_registrations` 
						  SET
						    userStatus = 'active'
						  WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * update_user_info_db
	 * @param  
	 * @return 
	 */
	static public function update_job_db (
									$JobID,
									$JobProperty,
									$JobSupplier,
									$JobUnitNo,
									$JobStatus,
									$JobDescription,
									$JobAssignee,
									$JobPriority,
									$AuthorisedBy,
									$DateToBeCompleted
								) {

		$query 		= "UPDATE 
						    `query_jobs` 
						  SET
						    supplier_id = '".$JobSupplier."',
						    unit_number = '".$JobUnitNo."',
						    status = '".$JobStatus."',
						    description = '".$JobDescription."',
						    job_assignee = '".$JobAssignee."',
						    priority = '".$JobPriority."',
						    authorised_by = '".$AuthorisedBy."',
						    date_tobe_completed = '".$DateToBeCompleted."'
						  WHERE id = '" .$JobID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_job_status_db (
									$JobID,
									$JobStatus,
									$datedone = ''
								) {

		

		$query 		= "UPDATE 
						    `query_jobs` 
						  SET
						    status = '".$JobStatus."',
						    job_done_date = '".$datedone."'
						  WHERE id = '" .$JobID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_submission_db ($UnitNumber, $ResID, $SubmissionID) {

		$query 		= "UPDATE 
						    `form_submissions` 
						  SET
						    `res_id` 		  = '".$ResID."',
						    `unit_no` 		  = '".$UnitNumber."'
						  WHERE `submit_id`   = '" .$SubmissionID. "';";

						  // echo $query ;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_app_user_type_db ($ID, $UserType) {

		$query 		= "UPDATE 
						    `app_registrations` 
						  SET
						    `userType` 	= '".$UserType."'
						  WHERE `id`   	= '" .$ID. "';";

						  // echo $query ;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_resident_db (
							$ID,
							$UnitNumber,
							$ResidentName,
							$ResidentPhone,
							$ResidentCellphone,
							$ResidentNotifyEmail,
							$ResidentType,
							$ResidentTrustee
						) {

		$query 		= "UPDATE 
						    `residents` 
						  SET
						    unitNumber 				= '".$UnitNumber."',
						    residentName 			= '".$ResidentName."',
						    residentPhone 			= '".$ResidentPhone."',
						    residentCellphone 		= '".$ResidentCellphone."',
						    residentNotifyEmail 	= '".$ResidentNotifyEmail."',
						    residentType 			= '".$ResidentType."',
						    residentTrustee 		= '".$ResidentTrustee."'
						    
						  WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_contractor_db (
							$ContractorID,
							$ServiceType,
							$CompanyName,
							$Address,
							$PhoneNumber,
							$Email
						) {

		$query 		= "UPDATE 
						    `constructors` 
						  SET
						    service_id 		= '".$ServiceType."',
						    company_name 	= '".$CompanyName."',
						    address 		= '".$Address."',
						    phone_number 	= '".$PhoneNumber."',
						    email 			= '".$Email."'
						    
						  WHERE id 			= '" .$ContractorID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_asset_db (
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

		$query 		= "UPDATE 
						    `assets` 
						  SET
						    supplier_id 	= '".$ContructorID."',
						    asset_name 		= '".$AssetName."',
						    description 	= '".$Description."',
						    make 			= '".$Make."',
						    location 		= '".$Location."',
						    serial_number 	= '".$SerialNumber."',
						    cost_of_asset 	= '".$CostOfAsset."',
						    last_inspected 	= '".$LastInspected."',
						    inspection_due_date 	= '".$InspectionDueDate."'
						    
						    
						  WHERE id 			= '" .$AssetID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function copy_resident_record_db (
							$ID
						) {

		$query 		= "INSERT INTO `residents_archive` 
							SELECT 
							  * 
							FROM
							  `residents` 
							WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function copy_form_db ($FormID, $PropertyName) {

		$query 		= "INSERT INTO `resident_forms` (
						  `prop_id`,
						  `res_id`,
						  `name`,
						  `questions`
						)
						SELECT 
							  ".$PropertyName.",
							  `res_id`,
							  `name`,
							  `questions`
							FROM
							  `resident_forms` 
							WHERE id = '" .$FormID. "';";

							
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function copy_contractor_db ($ContractorID, $PropertyName) {

		$query 		= "INSERT INTO `constructors` (
						  `prop_id`,
						  `service_id`,
						  `company_name`,
						  `address`,
						  `phone_number`,
						  `email`
						)
						SELECT 
							  ".$PropertyName.",
							  `service_id`,
							  `company_name`,
							  `address`,
							  `phone_number`,
							  `email`
							FROM
							  `constructors` 
							WHERE id = '" .$ContractorID. "';";

							
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function duplicate_emergency_contact_db ($ContactID, $PropertyName) {

		$query 		= "INSERT INTO `emergency_contacts` (
						  `propertyId`,
						  `contact_name`,
						  `contact_type`,
						  `contact_phone`,
						  `contact_icon`,
						  `contact_color`
						)
						SELECT 
							  ".$PropertyName.",
							  `contact_name`,
							  `contact_type`,
							  `contact_phone`,
							  `contact_icon`,
							  `contact_color` 
							FROM
							  `emergency_contacts` 
							WHERE id = '" .$ContactID. "';";

							
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function copy_document_db ($DocumentID, $PropertyName) {

		$query 		= "INSERT INTO `documents` (
						  `prop_id`,
						  `type_id`,
						  `doc_name`
						)
						SELECT 
							  ".$PropertyName.",
							  `type_id`,
							  `doc_name`
							FROM
							  `documents` 
							WHERE id = '" .$DocumentID. "';";

							
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_resident_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `residents` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_this_form_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `resident_forms` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_response_bysubmitid_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `form_submissions` 
						WHERE submit_id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function change_timezone () {

		$query 		= "SET @@session.time_zone = '+02:00';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * update_user_info_db
	 * @param  
	 * @return 
	 */
	static public function edit_query_db ($ID, $QueryType, $UserID, $Query, $Property, $Unit) {

		$query 		= "UPDATE 
						    `maintenance` 
						  SET
						    queryType  = '".$QueryType."',
						    userId 	   = '".$UserID."',
						    unitId 	   = '".$Unit."',
						    propId 	   = '".$Property."',
						    query 	   = '".$Query."' 
						  WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_query_db ($ID, $QueryType, $UserID, $Query, $Property, $Unit) {

		$query 		= "UPDATE 
						    `queries` 
						  SET
						    queryType  		= '".$QueryType."',
						    queryAssignee	= '".$UserID."',
						    unitNo 	   		= '".$Unit."',
						    propertyID 	   	= '".$Property."',
						    queryInput 	   	= '".$Query."' 
						  WHERE queryID = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function edit_notification ($ID, $PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood) {

		$query 		= "UPDATE 
						    `notifications` 
						  SET
						    companyID    = '".$company_id."',
						    propertyID   = '".$PropertyID."',
						    message      = '".$Message."',
						    showDateFrom = '".$StartDate."',
						    showDateTo 	 = '".$EndDate."',
						    mood 	     = '".$Mood."'
						  WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function assign_maintain_user_db ($ID, $AssineeID) {

		$query 		= "UPDATE 
						    `maintenance` 
						  SET
						    assignee_id= '".$AssineeID."'
						  WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param
	 * @return 
	 */
	static public function assign_billing_user_db ($ID, $AssineeID) {

		$query 		= "UPDATE 
						    `billing` 
						  SET
						    assignee_id = '".$AssineeID."'
						  WHERE id = '" .$ID. "';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param
	 * @return 
	 */
	static public function assign_queries_user_db ($ID, $AssineeID) {

		$query 		= "UPDATE 
						    `queries` 
						  SET
						    queryAssignee = '".$AssineeID."'
						  WHERE queryID = '" .$ID. "';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param
	 * @return 
	 */
	static public function assign_job_user_db ($ID, $AssineeID) {

		$query 		= "UPDATE 
						    `query_jobs` 
						  SET
						    job_assignee = '".$AssineeID."'
						  WHERE id = '" .$ID. "';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * update_user_info_db
	 * @param  
	 * @return 
	 */
	static public function edit_billing_query_db ($ID, $QueryType, $UserID, $Query, $Property, $Unit) {

		$query 		= "UPDATE 
						    `billing` 
						  SET
						    queryType  = '".$QueryType."',
						    userId 	   = '".$UserID."',
						    unitId 	   = '".$Unit."',
						    propId 	   = '".$Property."',
						    query 	   = '".$Query."' 
						  WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * update_user_info_db
	 * @param  
	 * @return 
	 */
	static public function update_user_perm_db (
				$admin_id,
				$company_id,
				$permission_type,
				$modules
				){

		$query 		= "UPDATE 
						    `admin_permissions` 
						  SET
						    permission_type 	= '".$permission_type."',
						    company_id 			= '".$company_id."',
						    modules 			= '".$modules."'
						  WHERE permission_id 	= '" .$admin_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * update_user_info_db
	 * @param  
	 * @return 
	 */
	static public function update_user_perm_type_db (
				$company_id,
				$permission_type,
				$modules
				){

		$query 		= "UPDATE 
						    `admin_permissions` 
						  SET
						    company_id 			= '".$company_id."',
						    modules 			= '".$modules."'
						  WHERE permission_type = '" .$permission_type. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_user_permission_db (
				$permission_id,
				$admin_id
				){

		$query 		= "UPDATE 
						    `admin_managers` 
						  SET
						    permission_ID 			= '".$permission_id."'
						  WHERE adminID 			= '" .$admin_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_admin_user_info_db ($ID, $FirstName, $Surname, $Email, $CellNumber, $Password, $UserType) {

		$query 		= "UPDATE 
						    `admin_users` 
						  SET
						    permission_id = '".$UserType."',
						    first_name 	= '".$FirstName."',
						    last_name 	= '".$Surname."',
						    email 		= '".$Email."',
						    cellphone 	= '".$CellNumber."',
						    password 	= '".$Password."'
						  WHERE user_id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_promo_info_db ($ID, $PromoName, $BinNumber) {

		$query 		= "UPDATE 
						    `store_promotions` 
						  SET
						    promo_description = '".$PromoName."',
						    number_of_bins 	= '".$BinNumber."'
						  WHERE promo_id 	= '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_survey_info_db ($ID, $Title, $Description, $StartDate, $EndDate, $AssignTo, $AssignID ){

		$query 		= "UPDATE 
						    `survey_list` 
						  SET
						    title 		= '".$Title."',
						    description = '".$Description."',
						    start_date 	= '".$StartDate."',
						    end_date 	= '".$EndDate."',
						    assign_to 	= '".$AssignTo."',
						    assignee_id = '".$AssignID."'
						  WHERE id 	= '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_question_info_db ($ID, $SurveyID, $QNumber, $QText, $Options, $QType){

		$query 		= "UPDATE 
						    `survey_questions` 
						  SET
						    survey_id 	= '".$SurveyID."',
						    q_num 		= '".$QNumber."',
						    q_text 		= '".$QText."',
						    q_options 	= '".$Options."',
						    q_type 		= '".$QType."'
						  WHERE id 	= '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function convert_query_to_job_db (
							$JobQueryID,
							$UserID,
							$JobProperty,
							$JobSupplier,
							$JobUnitNo,
							$JobStatus,
							$JobDescription,
							$JobAssignee,
							$JobPriority,
							$AuthorisedBy,
							$DateToBeCompleted,
							$JobImageName
						) {

		$query 		= "INSERT INTO `query_jobs` (
						  `query_id`,
						  `user_id`,
						  `prop_id`,
						  `supplier_id`,
						  `unit_number`,
						  `status`,
						  `description`,
						  `job_assignee`,
						  `priority`,
						  `authorised_by`,
						  `date_tobe_completed`,
						  `job_image`
						) 
						VALUES
						  (
						    '".$JobQueryID."',
						    '".$UserID."',
						    '".$JobProperty."',
						    '".$JobSupplier."',
						    '".$JobUnitNo."',
						    '".$JobStatus."',
						    '".$JobDescription."',
						    '".$JobAssignee."',
						    '".$JobPriority."',
						    '".$AuthorisedBy."',
						    '".$DateToBeCompleted."',
						    '".$JobImageName."'
						  );";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function do_convert_user_db ($user_id,
											$company_id,
											$property_id,
											$unit_no,
											$fullname,
											$cellphone,
											$email) {

		$query 		= "INSERT INTO `residents` (
						  `propertyID`,
						  `unitNumber`,
						  `residentName`,
						  `residentCellphone`,
						  `residentNotifyEmail`)
						VALUES
						  (
						    '".$property_id."',
						    '".$unit_no."',
						    '".$fullname."',
						    '".$cellphone."',
						    '".$email."'
						  );";
						  // echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function add_new_permission_db (
						$company_id,
						$permission_type,
						$modules
					) {

		$query 		= "INSERT INTO `admin_permissions` (
						  `company_id`,
						  `permission_type`,
						  `modules`
						) 
						VALUES
						  (
						    '".$company_id."',
						    '".$permission_type."',
						    '".$modules."'
						  );";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_activity_db ($user_id, $prop_id, $user_type, $activity_description) {

		$query 		= "INSERT INTO `user_activities` (
						  `user_id`,
						  `prop_id`,
						  `user_type`,
						  `activity_description`
						) 
						VALUES
						  (
						    '".$user_id."',
						    '".$prop_id."',
						    '".$user_type."',
						    '".$activity_description."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_new_notification_db($PropertyID, $company_id, $Message, $StartDate, $EndDate, $Mood) {

		$query 		= "INSERT INTO `notifications` (
						  `companyID`,
						  `propertyID`,
						  `message`,
						  `showDateFrom`,
						  `showDateTo`,
						  `mood`
						) 
						VALUES
						  (
						    '".$company_id."',
						    '".$PropertyID."',
						    '".$Message."',
						    '".$StartDate."',
						    '".$EndDate."',
						    '".$Mood."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_notification_coms_db ($message, $query_id, $user_id) {

		$query 		= "INSERT INTO `notification_coms` (
						  `query_id`,
						  `user_id`,
						  `text`
						) 
						VALUES
						  (
						    '".$query_id."',
						    '".$user_id."',
						    '".$message."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_sms_coms_db($Message, $QueryID, $cell_phone, $user_id) {

		$query 		= "INSERT INTO `sms_coms` (
						  `query_id`,
						  `sms_text`,
						  `user_id`
						) 
						VALUES
						  (
						    '".$QueryID."',
						    '".$Message."',
						    '".$user_id."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_job_sms_coms_db($Message, $JobID, $cell_phone, $user_id) {

		$query 		= "INSERT INTO `jobs_sms_coms` (
						  `job_id`,
						  `sms_text`,
						  `user_id`
						) 
						VALUES
						  (
						    '".$JobID."',
						    '".$Message."',
						    '".$user_id."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function save_property_com_sms_db ($Cells, $Message, $SendTo, $Mood, $PropertyID, $UserID) {

		$query 		= "INSERT INTO `sms_properties_coms` (
						  `prop_id`,
						  `user_id`,
						  `cell_phones`,
						  `sms_text`,
						  `mood`,
						  `send_to`
						) 
						VALUES
						  (
						    '".$PropertyID."',
						    '".$UserID."',
						    '".$Cells."',
						    '".$Message."',
						    '".$Mood."',
						    '".$SendTo."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_res_sms_coms_db ($Message, $ResID, $cell_phone, $UserID, $PropertyID) {

		$query 		= "INSERT INTO `res_sms_coms` (
						  `resident_id`,
						  `property_id`,
						  `user_id`,
						  `sms_text`
						) 
						VALUES
						  (
						    '".$ResID."',
						    '".$PropertyID."',
						    '".$UserID."',
						    '".$Message."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_email_coms_db ($Message, $Subject, $Email, $Mood, $Property_ID) {

		$query 		= "INSERT INTO `email_coms` (
						  `property_id`,
						  `from`,
						  `subject`,
						  `mood`,
						  `email_text`
						) 
						VALUES
						  (
						    '".$Property_ID."',
						    '".$Email."',
						    '".$Subject."',
						    '".$Mood."',
						    '".$Message."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_promo_info_db ($PromoName, $BinNumber){

		$query 		= "INSERT INTO `store_promotions` (
						  `promo_description`,
						  `number_of_bins`
						) 
						VALUES
						  (
						    '".$PromoName."',
						    '".$BinNumber."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_resident_db (
							$UnitNumber,
							$PropertyID,
							$ResidentName,
							$ResidentPhone,
							$ResidentCellphone,
							$ResidentNotifyEmail,
							$ResidentType,
							$ResidentTrustee
						) {

		$query 		= "INSERT INTO `residents` (
						  `propertyID`,
						  `unitNumber`,
						  `residentName`,
						  `residentPhone`,
						  `residentCellphone`,
						  `residentNotifyEmail`,
						  `residentType`,
						  `residentTrustee`,
						  `residentStatus`
						) 
						VALUES
						  (
						    '".$PropertyID."',
						    '".$UnitNumber."',
						    '".$ResidentName."',
						    '".$ResidentCellphone."',
						    '".$ResidentCellphone."',
						    '".$ResidentNotifyEmail."',
						    '".$ResidentType."',
						    '".$ResidentTrustee."',
						    'c'
						    
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_contractor_db (
							$PropertyID,
							$ServiceType,
							$CompanyName,
							$Address,
							$PhoneNumber,
							$Email
						) {

		$query 		= "INSERT INTO `constructors` (
						  `prop_id`,
						  `service_id`,
						  `company_name`,
						  `address`,
						  `phone_number`,
						  `email`
						) 
						VALUES
						  (
						    '".$PropertyID."',
						    '".$ServiceType."',
						    '".$CompanyName."',
						    '".$Address."',
						    '".$PhoneNumber."',
						    '".$Email."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_asset_db (
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

		$query 		= "INSERT INTO `assets` (
						  `prop_id`,
						  `supplier_id`,
						  `asset_name`,
						  `description`,
						  `make`,
						  `location`,
						  `serial_number`,
						  `cost_of_asset`,
						  `last_inspected`,
						  `inspection_due_date`
						) 
						VALUES
						  (
						    '".$PropertyID."',
						    '".$ContructorID."',
						    '".$AssetName."',
						    '".$Description."',
						    '".$Make."',
						    '".$Location."',
						    '".$SerialNumber."',
						    '".$CostOfAsset."',
						    '".$LastInspected."',
						    '".$InspectionDueDate."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_new_form_db (
							$prop_id,
							$FormName,
							$FormInstructions,
							$form_array
						) {

		$query 		= "INSERT INTO `resident_forms` (
						  `prop_id`,
						  `name`,
						  `form_instruction`,
						  `questions`
						) 
						VALUES
						  (
						    '".addslashes ($prop_id)."',
						    '".addslashes ($FormName)."',
						    '".addslashes ($FormInstructions)."',
						    '".addslashes ($form_array)."'
						  );";

						  // echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function insert_app_query_db (
							$device_token,
							$property_id,
							$user_id,
							$unit_number,
							$query_type,
							$query_detail,
							$user_name,
							$cell_phone,
							$file_name
						) {

		$query 		= "INSERT INTO `queries` (
						  `deviceID`,
						  `propertyID`,
						  `userID`,
						  `unitNo`,
						  `queryType`,
						  `queryInput`,
						  `queryUsername`,
						  `queryCellphone`,
						  `queryImage`,
						  `queryDate`
						) 
						VALUES
						  (
						    '".$device_token."',
						    '".$property_id."',
						    '".$user_id."',
						    '".$unit_number."',
						    '".$query_type."',
						    '".$query_detail."',
						    '".$user_name."',
						    '".$cell_phone."',
						    '".$file_name."',
						    NOW()
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_form_db (
							$FormID,
							$FormName,
							$FormInstructions,
							$form_array
						) {

		$query 		= "UPDATE 
						  `resident_forms`
						  SET
						  name 		= '".$FormName."',
						  form_instruction 		= '".$FormInstructions."',
						  questions = '".$form_array."'
						WHERE id = " .$FormID. ";";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_query_db ($QueryType, $AssineeID, $Query, $Property, $Unit, $Image){

		$query 		= "INSERT INTO `queries` (
						  `queryType`,
						  `queryAssignee`,
						  `queryInput`,
						  `propertyID`,
						  `unitNo`,
						  `queryImage`,
						  `queryDate`
						)
						VALUES
						  (
						    '".$QueryType."',
						    '".$AssineeID."',
						    '".$Query."',
						    '".$Property."',
						    '".$Unit."',
						    '".$Image."',
							  NOW()
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_billing_query_db ($QueryType, $UserID, $AssineeID, $Query, $Property, $Unit){

		$query 		= "INSERT INTO `billing` (
						  `queryType`,
						  `userId`,
						  `assignee_id`,
						  `unitId`,
						  `propId`,
						  `query`
						) 
						VALUES
						  (
						    '".$QueryType."',
						    '".$UserID."',
						    '".$AssineeID."',
						    '".$Unit."',
						    '".$Property."',
						    '".$Query."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_new_comment_db ($Comment, $QueryID, $UserID, $FileName){

		$query 		= "INSERT INTO `query_comments` (
						  `query_id`,
						  `user_id`,
						  `comment_text`,
						  `file`
						) 
						VALUES
						  (
						    '".$QueryID."',
						    '".$UserID."',
						    '".$Comment."',
						    '".$FileName."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  insert_job_quote ($JobID, $FileName)
	 * @return 
	 */
	static public function insert_job_quote_db ($job_id, $filename) {

		$query 		= "INSERT INTO `job_qoutes_details` (
						  `job_id`,
						  `file_name`
						) 
						VALUES
						  (
						    '".$job_id."',
						    '".$filename."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  insert_job_chart_url_db ($job_id, $chat_url)
	 * @return 
	 */
	static public function insert_job_chart_url_db ($job_id, $chat_url) {

		$query 		= "INSERT INTO `job_quotes` (
						  `job_id`,
						  `chat_url`
						) 
						VALUES
						  (
						    '".$job_id."',
						    '".$chat_url."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_app_reg_db (
								$company_id,
								$property_id,
								$unit_number,
								$full_name,
								$cellphone,
								$email,
								$user_type,
								$device_token,
								$player_id){

		$query 		= "INSERT INTO `app_registrations` (
						  `companyID`,
						  `propertyID`,
						  `unitNo`,
						  `userFullname`,
						  `userCellphone`,
						  `userEmail`,
						  `userDeviceToken`,
						  `userType`,
						  `userPlayerID`
						) 
						VALUES
						  (
						    '".$company_id."',
						    '".$property_id."',
						    '".$unit_number."',
						    '".$full_name."',
						    '".$cellphone."',
						    '".$email."',
						    '".$device_token."',
						    '".$user_type."',
						    '".$player_id."'
						  );";

		$stmt   	= self::$mysqli->query ($query) or die ('Failed to prepare: ' . self::$mysqli->error());

		$id 		= self::$mysqli->insert_id();

		$data 		= self::get_app_registration_by_id_db($id);

		return array('status' => $stmt, $data);


		// return $stmt;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function get_enum_values_db ($table, $field) {

		$query 		= "SHOW COLUMNS 	
						FROM ".$table."
						WHERE Field = '" .$field. "';";

		$stmt   	= self::$mysqli->query ($query) or die('Failed to prepare: ' . self::$mysqli->error());
		while($row  = self::$mysqli->fetch_array ($stmt, MYSQL_ASSOC)) {
		    $Type   = $row["Type"];
		}

		return $Type;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_booking_on_demand_db (
								$VenueID,
								$NumberOfAttendees,
								$BookingDate,
								$BookingTimeFrom,
								$BookingTimeTo,
								$Requirements
							){

		$query 		= "INSERT INTO `venue_bookings` (
						  `venue_id`,
						  `number_attendees`,
						  `booking_date`,
						  `booking_time_from`,
						  `booking_time_to`,
						  `requirements`
						) 
						VALUES
						  (
						    '".$VenueID."',
						    '".$NumberOfAttendees."',
						    '".$BookingDate."',
						    '".$BookingTimeFrom."',
						    '".$BookingTimeTo."',
						    '".$Requirements."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_service_on_demand_db (
								$property_id,
								$company_id,
								$service_id,
								$user_id,
								$request_detail,
								$file_name){

		$query 		= "INSERT INTO `serviceondemand_jobs` (
						  `property_id`,
						  `company_id`,
						  `supplier_id`,
						  `user_id`,
						  `job_description`,
						  `job_photo`,
						  `job_status`
						) 
						VALUES
						  (
						    '".$property_id."',
						    '".$company_id."',
						    '".$service_id."',
						    '".$user_id."',
						    '".$request_detail."',
						    '".$file_name."',
						    'open'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_service_types_db ($SupplierTypeName){

		$query 		= "INSERT INTO `service_types` (
						  `service_name`
						) 
						VALUES
						  (
						    '".$SupplierTypeName."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_credits_db ($company_id, $credit_number, $transaction_id){

		$query 		= "INSERT INTO `sms_credits` (
						  `company_id`,
						  `credits`,
						  `transaction_id`
						) 
						VALUES
						  (
						    '".$company_id."',
						    '".$credit_number."',
						    '".$transaction_id."'
						  )

						ON DUPLICATE KEY UPDATE
						`credits` = credits + '".$credit_number."'
						;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function vote_up_db ($user_id, $qoute_id){

		$query 		= "UPDATE 
						  `job_qoutes_details` 
						SET
						  `vote_up` 		= vote_up + 1,
						  `last_vote_date` 	= NOW(),
						  `vote_up_by` 		= CONCAT_WS(',',vote_up_by, '$user_id')
						  
						WHERE `id` 			= '".$qoute_id."';";


						

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function vote_down_db ($user_id, $qoute_id){

		$query 		= "UPDATE 
						  `job_qoutes_details` 
						SET
						  `vote_down` 		= vote_down + 1,
						  `last_vote_date` 	= NOW(),
						  `vote_down_by` 	= CONCAT_WS(',',vote_down_by, '$user_id')
						  
						WHERE `id` 			= '".$qoute_id."';";


						

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_resident_comment_db (
							$Message, 
							$ResID, 
							$FileData, 
							$UserID, 
							$PropertyID
						){

		$query 		= "INSERT INTO `resident_comments` (
						  `resident_id`,
						  `property_id`,
						  `user_id`,
						  `comment_text`,
						  `file`
						) 
						VALUES
						  (
						    '".$ResID."',
						    '".$PropertyID."',
						    '".$UserID."',
						    '".$Message."',
						    '".$FileData."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_resident_email_db (
							$Message, 
							$ResID, 
							$FileData, 
							$UserID, 
							$PropertyID
						){

		$query 		= "INSERT INTO `res_email_coms` (
						  `resident_id`,
						  `property_id`,
						  `user_id`,
						  `email_text`,
						  `file`
						) 
						VALUES
						  (
						    '".$ResID."',
						    '".$PropertyID."',
						    '".$UserID."',
						    '".$Message."',
						    '".$FileData."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_properties_email_db (
									$Message, 
									$SendTo, 
									$Subject, 
									$EmailMood, 
									$FileName, 
									$UserID, 
									$PropertyID
								){

		$query 		= "INSERT INTO `email_properties_coms` (
						  `property_id`,
						  `user_id`,
						  `subject`,
						  `email_text`,
						  `file`,
						  `send_to`,
						  `mood`
						) 
						VALUES
						  (
						    '".$PropertyID."',
						    '".$UserID."',
						    '".$Subject."',
						    '".$Message."',
						    '".$FileName."',
						    '".$SendTo."',
						    '".$EmailMood."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 				
	 */
	static public function save_queries_query_db ($QueryType, $AssineeID, $Query, $Property, $Unit){

		$query 		= "INSERT INTO `queries` (
						  `queryType`,
						  `queryAssignee`,
						  `unitNo`,
						  `propertyID`,
						  `queryInput`,
						  `queryDate`
						) 
						VALUES
						  (
						    '".$QueryType."',
						    ".$AssineeID.",
						    '".$Unit."',
						    ".$Property.",
						    '".$Query."',
						    NOW()
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_comment_db ($Comment, $ID) {

		$query 		= "UPDATE 
						  `maintenance`
						  SET
						  comment = '".$Comment."',
						  status = 'done'
						WHERE id = " .$ID. ";";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_billing_comment_db ($Comment, $ID) {

		$query 		= "UPDATE 
						  `billing`
						  SET
						  comment = '".$Comment."',
						  status = 'done'
						WHERE id = " .$ID. ";";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_queries_comment_db ($Comment, $ID) {

		$query 		= "UPDATE 
						  `queries`
						  SET
						  queryComments = '".$Comment."',
						  queryStatus = 'done'
						WHERE queryID = " .$ID. ";";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function set_utf8 () {

		$query 		= "SET CHARACTER SET utf8;";

		$stmt   	= self::$mysqli->query ($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function mark_query_done_db ($ID) {

		$query 		= "UPDATE 
						  `queries`
						  SET
						  queryStatus = 'done',
						  queryDoneTime = NOW()
						WHERE queryID = " .$ID. ";";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function mark_query_materials_db ($ID) {

		$query 		= "UPDATE 
						  `queries`
						  SET
						  queryStatus = 'materials required'
						WHERE queryID = " .$ID. ";";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function mark_query_insurance_claim_db ($ID) {

		$query 		= "UPDATE 
						  `queries`
						  SET
						  queryStatus = 'insurance claim'
						WHERE queryID = " .$ID. ";";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_user_perm_db ($permission_type, $modules) {

		$query 		= "INSERT INTO `admin_permissions` (
						  `permission_type`,
						  `modules`
						) 
						VALUES
						  (
						    '".$permission_type."',
						    '".$modules."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_user_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `reps` 
						WHERE rep_id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_delete_venue_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `property_venues` 
						WHERE id = '" .$ID. "';";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_app_reg_db ($device_token, $property_id) {

		$query 		= "DELETE 
						FROM
						  `app_registrations` 
						WHERE id = '" .$property_id. "'
						AND userDeviceToken = '" .$device_token. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_email_property_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `email_properties_coms` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_document_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `documents` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_document_type_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `document_types` 
						WHERE id = '" .$ID. "';";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_service_type_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `service_types` 
						WHERE id = '" .$ID. "';";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_this_contractor_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `constructors` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_this_asset_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `assets` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}
	

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_admin_user_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `admin_users` 
						WHERE user_id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_admin_perm_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `admin_permissions` 
						WHERE permission_id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * delete_user_info_db
	 * @param  
	 * @return 
	 */
	static public function delete_question_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `survey_questions` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * delete_survey_info_db
	 * @param  
	 * @return 
	 */
	static public function delete_survey_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `survey_list` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_promo_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `store_promotions` 
						WHERE promo_id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_responses_byid_db ($ID) {

		$query 		= "SELECT * 
						FROM
						  `form_submissions` 
						WHERE form_id = '" .$ID. "'

						ORDER BY created DESC;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_query_types_db ($category) {

		$query 		= "SELECT * 
						FROM
						  `query_types`
						 WHERE category = '" .$category. "'
						ORDER BY name ASC;";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_constructors_by_propid_db ($ID) {

		$query 		= "SELECT 
						  con.* 
						FROM
						  `constructors` AS con 
						  INNER JOIN properties AS p 
						    ON p.propertyID = con.prop_id 
						  INNER JOIN companies AS c 
						    ON c.companyID = p.companyID  
						WHERE con.prop_id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_service_supplier_db ($prop_id) {

		$query 		= "SELECT 
						  con.*,
						  st.`service_name` 
						FROM
						  `constructors` AS con 
						  INNER JOIN `service_types` AS st 
						    ON st.`id` = con.`service_id` 
						  INNER JOIN properties AS p 
						    ON p.propertyID = con.prop_id 
						  INNER JOIN companies AS c 
						    ON c.companyID = p.companyID 
						WHERE con.prop_id = '" .$prop_id. "' 
						AND con.`serviceOnDemand` = 'yes' 
						ORDER BY st.`service_name` ;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_responce_group_byprop_db ($ID) {

		$query 		= "SELECT 
						  * 
						FROM
						  `form_submissions` 
						WHERE `prop_id` = '" .$ID. "'
						GROUP BY `submit_id`;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_responce_byprop_db ($ID) {

		$query 		= "SELECT 
						  * 
						FROM
						  `form_submissions` AS s
						  INNER JOIN `resident_forms` AS f
						  ON s.form_id = f.id
						WHERE s.`prop_id` = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_responce_by_subid_db ($ID) {

		$query 		= "SELECT 
						  p.`propertyName` AS complex_name,
						  s.created AS date_submited,
						  s.*,
						  f.*
						FROM
						  `form_submissions` AS s 
						  INNER JOIN `resident_forms` AS f 
						    ON s.form_id = f.id 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = f.`prop_id` 
						WHERE s.`submit_id` = '" .$ID. "';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_images_by_storeid_bd ($id) {

		$query 		= "SELECT 
						  p.images,
						  p.no_of_bins,
						  p.`date`,
						  sp.`promo_description`, 
						  sp.`promo_id` 
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN `store_promotions` sp 
						    ON p.`promo_id` = sp.`promo_id` 
						WHERE p.`store_id` = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function get_document_by_id_bd ($id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `documents` AS d 
						  INNER JOIN document_types AS dt 
						    ON dt.id = d.type_id 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = d.prop_id  
						WHERE d.`id` = '" .$id. "';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_info_byid_bd ($property_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `properties` AS p 
						WHERE p.`propertyID` = '" .$property_id. "';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_document_type_by_id_bd ($id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `document_types` AS d
						WHERE d.`id` = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_notifications_by_id_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  notifications
						 WHERE `id` = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_responces_db ($form_id, $prop_id, $unit_no, $resp_id, $q_num) {

		$query 		= "SELECT 
						  * 
						FROM
						  `form_submissions` 
						WHERE form_id 	= '" .$form_id. "'
						  AND prop_id 	= '" .$prop_id. "'
						  AND unit_no 	= '" .$unit_no. "'
						  AND submit_id = '" .$resp_id. "'
						  AND q_num 	= '" .$q_num. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function insert_survey_responce_db (
											$form_id,
											$resp_id,
											$prop_id,
											$unit_no,
											$full_name,
											$cellphone,
											$q_num,
											$responce
										) {

		$query 		= "INSERT INTO `form_submissions` (
						  `form_id`,
						  `submit_id`,
						  `prop_id`,
						  `unit_no`,
						  `res_name`,
						  `res_cell`,
						  `q_num`,
						  `responce`
						) 
						VALUES
						  (
						    '".$form_id."',
						    '".$resp_id."',
						    '".$prop_id."',
						    '".$unit_no."',
						    '".$full_name."',
						    '".$cellphone."',
						    '".$q_num."',
						    '".$responce."'
						  );";

						  // echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function insert_emergency_contact_db ($PropID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor) {

		$query 		= "INSERT INTO `emergency_contacts` (
						  `propertyId`,
						  `contact_name`,
						  `contact_type`,
						  `contact_phone`,
						  `contact_icon`,
						  `contact_color`
						) 
						VALUES
						  (
						    '".$PropID."',
						    '".$ContactName."',
						    '".$ContactType."',
						    '".$ContactPhone."',
						    '".$ContactIcon."',
						    '".$ContactColor."'
						  );";



		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_emergency_contact_db ($ID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor) {

		$query 		= "UPDATE 
						  `emergency_contacts`
						  SET
						  `contact_name`    	= '".$ContactName."',
						  `contact_type`    	= '".$ContactType."',
						  `contact_phone`    	= '".$ContactPhone."',
						  `contact_icon`    	= '".$ContactIcon."',
						  `contact_color`    	= '".$ContactColor."'
						WHERE `id` 	= '" .$ID. "';";



		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_supplier_thum_db ($supplier_id,
										$thum_name) {

		$query 		= "UPDATE 
						  `constructors`
						  SET
						  `thumbnailPic`    	= '".$thum_name."'
						WHERE `id` 	= '" .$supplier_id. "';";



		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_document_category_db ($ID, $DocumentTypeName) {

		$query 		= "UPDATE 
						  `document_types`
						  SET
						  `name`    = '".$DocumentTypeName."'
						WHERE `id` 	= '" .$ID. "';";



		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_sms_use_db ($number_cells, $company_id) {

		$query 		= "UPDATE 
						  `sms_credits`
						  SET
						  `credits`    		= '".$number_cells."'
						WHERE `company_id` 	= '" .$company_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_supplier_category_db ($ID, $SupplierTypeName) {

		$query 		= "UPDATE 
						  `service_types`
						  SET
						  `service_name`    = '".$SupplierTypeName."'
						WHERE `id` 			= '" .$ID. "';";



		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_new_document_db (
										$prop_id,
										$file_name,
										$DocumentType
									) {

		$query 		= "INSERT INTO `documents` (
						  `type_id`,
						  `prop_id`,
						  `doc_name`
						) 
						VALUES
						  (
						    '".$DocumentType."',
						    '".$prop_id."',
						    '".$file_name."'
						  );";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_new_venue_times_db (
										$venue_id,
										$day,
										$timeopen,
										$timeclose
									) {

		$query 		= "INSERT INTO `venues_days_open` (
						  `venue_id`,
						  `day`,
						  `time_open`,
						  `time_close`
						) 
						VALUES
						  (
						    '".$venue_id."',
						    '".$day."',
						    '".$timeopen."',
						    '".$timeclose."'
						  );";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_new_venue_db (
										$prop_id,
										$file_name,
										$VenueName
									) {

		$query 		= "INSERT INTO `property_venues` (
						  `prop_id`,
						  `name`,
						  `image`
						) 
						VALUES
						  (
						    '".$prop_id."',
						    '".$VenueName."',
						    '".$file_name."'
						  );";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$id 		= self::$mysqli->insert_id();

		return $id;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_new_document_category_db ($DocumentTypeName) {

		$query 		= "INSERT INTO `document_types` (
						  `name`
						) 
						VALUES
						  (
						    '".$DocumentTypeName."'
						  );";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_minutes_db (
										$prop_id,
										$file_name,
										$MeetingDate
									) {

		$query 		= "INSERT INTO `meeting_minutes` (
						  `meeting_date`,
						  `prop_id`,
						  `doc_name`
						) 
						VALUES
						  (
						    '".$MeetingDate."',
						    '".$prop_id."',
						    '".$file_name."'
						  );";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_trustee_event_db (
										$prop_id,
										$title,
										$start,
										$end
									) {

		$query 		= "INSERT INTO `trustree_calender_events` (
						  `prop_id`,
						  `title`,
						  `start`,
						  `end`
						) 
						VALUES
						  (
						    '".$prop_id."',
						    '".$title."',
						    '".$start."',
						    '".$end."'
						  );";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function insert_reminder_event_db (
										$company_id,
										$user_id,
										$title,
										$start,
										$end
									) {

		$query 		= "INSERT INTO `calendar_reminders` (
						  `company_id`,
						  `user_id`,
						  `description`,
						  `start`,
						  `end`
						) 
						VALUES
						  (
						    '".$company_id."',
						    '".$user_id."',
						    '".$title."',
						    '".$start."',
						    '".$end."'
						  );";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_trustee_event_db (
										$id,
										$title,
										$start,
										$end
									) {

		$query 		= "UPDATE 
						  `trustree_calender_events`
						  SET
						  `title`    	= '".$title."',
						  `start`    	= '".$start."',
						  `end`    		= '".$end."'
						WHERE `id` 	= '" .$id. "';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_form_by_id_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  resident_forms AS rf
						INNER JOIN properties AS p
						  ON p.propertyID = rf.prop_id
						INNER JOIN companies AS c
						 ON c.companyID = p.companyID
						WHERE `id` = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_documents_db ($prop_id) {

		$query 		= "SELECT 
						  d.*,
						  p.propertyName,
						  dt.name
						FROM
						  `documents` AS d 
						  INNER JOIN document_types AS dt 
						    ON dt.id = d.type_id 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = d.prop_id 
						WHERE d.prop_id = '".$prop_id."';";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_rules_documents_db ($prop_id) {

		$query 		= "SELECT 
						  d.*,
						  p.propertyName,
						  dt.name
						FROM
						  `documents` AS d 
						  INNER JOIN document_types AS dt 
						    ON dt.id = d.type_id 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = d.prop_id 
						WHERE d.prop_id = '".$prop_id."' AND dt.name = 'Conduct Rules';";

						// echo $query;

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function count_images_by_storeid_db ($id) {

		$query 		= "SELECT 
						  COUNT(*) as count
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN `store_promotions` sp 
						    ON p.`promo_id` = sp.`promo_id` 
						    WHERE p.`store_id` = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_images_by_userid_bd ($id) {

		$query 		= "SELECT 
						  p.images,
						  p.no_of_bins,
						  p.store_id,
						  p.`date`,
						  sp.`promo_description`, 
						  sp.`promo_id`
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN `store_promotions` sp 
						    ON p.`promo_id` = sp.`promo_id` 
						    WHERE p.`user_id` = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_images_per_page_bd ($user_id, $limit, $offset) {

		$query 		= "SELECT 
						  p.images,
						  p.no_of_bins,
						  p.store_id,
						  p.`date`,
						  sp.`promo_description`, 
						  sp.`promo_id`
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN `store_promotions` sp 
						    ON p.`promo_id` = sp.`promo_id` 
						WHERE p.`user_id` = '" .$user_id. "'
							AND p.images != ''
						ORDER BY p.date DESC
						LIMIT " .$limit. " 
						OFFSET " .$offset. ";";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_images_per_store_page_bd ($store_id, $limit, $offset) {

		$query 		= "SELECT 
						  p.images,
						  p.no_of_bins,
						  p.`date`,
						  sp.`promo_description`, 
						  sp.`promo_id` 
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN `store_promotions` sp 
						    ON p.`promo_id` = sp.`promo_id` 
						    WHERE p.`store_id` = '" .$store_id. "'
							AND p.images != ''
						ORDER BY p.date DESC
						LIMIT " .$limit. " 
						OFFSET " .$offset. ";";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function count_images_by_userid_db ($id) {

		$query 		= "SELECT 
						  COUNT(*) as count
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN `store_promotions` sp 
						    ON p.`promo_id` = sp.`promo_id` 
						    WHERE p.`user_id` = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_promotions_db ($ids) {

		$query 		= "SELECT 
						  * 
						FROM
						  promotions 
						WHERE customer_id IN (".$ids.") 
						GROUP BY promo_id  ;";
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_survey_list_level_db ($level_num, $store_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  survey_list
						WHERE assign_to = '".$level_num."' 
							AND assignee_id = '".$store_id."';";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	

	/**
	 * @param  
	 * @return 
	 */
	static public function get_responces_bysurveyid_db ($survey_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `survey_responces` 
						WHERE survey_id = " .$survey_id. ";";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_survey_info_db ($survey_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `survey_list` AS sl 
						  INNER JOIN `survey_questions` AS q 
						    ON q.`survey_id` = sl.`id` 
						  INNER JOIN `survey_responces` AS r 
						    ON q.id = r.`q_id` 
						  INNER JOIN `reps` AS rp 
						    ON rp.rep_id = r.`rep_id` 
						WHERE sl.id  = " .$survey_id. "
						ORDER BY q.`q_num`;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_maintenance_list_db ($prop_id) {

		$query 		= "SELECT 
						  m.*,
						  CONCAT_WS(' ',u.firstName,u.lastName) AS full_name
						FROM
						  maintenance AS m
						  LEFT OUTER JOIN users AS u
						  ON u.id = m.userId
						WHERE m.propId = '" .$prop_id. "'
						ORDER BY m.date DESC;";
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_asset_by_id_db ($ID) {

		$query 		= "SELECT 
						  *
						FROM
						  assets 
						WHERE id = '" .$ID. "';";
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_maintenance_db () {

		$query 		= "SELECT 
						  m.*,
						  CONCAT_WS(' ',u.firstName,u.lastName) AS full_name,
						  CONCAT_WS(' ',au.first_name,au.last_name) AS assignee_name
						FROM
						  maintenance AS m
						  LEFT OUTER JOIN users AS u
						  ON u.id = m.userId
						LEFT OUTER JOIN admin_users AS au
						    ON au.user_id = m.assignee_id
						ORDER BY m.date DESC;";
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_queries_db ($properties, $query_type) {


		// if ($company_id) {
		//     $sql[]  = " p.companyID = '".$company_id."' ";
		// }

		if ($properties) {
		    $sql[]  = " q.propertyID IN(".$properties.") ";
		}


		if ($query_type) {
		    $sql[]  = " q.queryStatus = '".$query_type."' ";
		}

		// die(var_dump($properties));

		$query 		= " SELECT 
						  q.*,
						  p.`propertyName`,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS assignee_name 
						FROM
						  queries AS q 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = q.`propertyID` 
						  LEFT OUTER JOIN admin_managers AS a 
						    ON a.adminID = q.queryAssignee ";

		if (!empty($sql)) {
		    $query .= ' WHERE ' . implode(' AND ', $sql);
		}

		$query 		.=" ORDER BY q.queryDate DESC ";

		// echo $query;
		// die();


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_company_queries_db  ($company_id, $property_id, $device_id) {


		// if ($company_id) {
		//     $sql[]  = " p.companyID = '".$company_id."' ";
		// }

		if ($property_id) {
		    $sql[]  = " q.propertyID = '".$property_id."'";
		}


		if ($company_id) {
		    $sql[]  = " p.companyID = '".$company_id."' ";
		}

		if ($device_id) {
		    $sql[]  = " q.deviceID = '".$device_id."' ";
		}


		$query 		= " SELECT 
						  q.*,
						  p.`propertyName`
						FROM
						  queries AS q 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = q.`propertyID` ";

		if (!empty($sql)) {
		    $query .= ' WHERE ' . implode(' AND ', $sql);
		}

		$query 		.=" ORDER BY q.queryDate DESC ";

		// echo $query;
		// die();


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_queries_company_db ($company_id, $properties, $query_type, $date_from, $date_to) {

		if ($company_id) {
		    $sql[]  = " p.companyID = '".$company_id."' ";
		}


		if (!empty($date_from) AND !empty($date_to)) {
		    $sql[]  = " q.queryDate BETWEEN '".$date_from."' AND '".$date_to."'";
		}
// queryDate BETWEEN '2016-03-01 00:00:00' AND '2016-03-02 00:00:00' 

		if ($properties) {
		    $sql[]  = " q.propertyID IN (".$properties.") ";
		}

		if ($query_type) {
		    $sql[]  = " q.queryStatus = '".$query_type."' ";
		}

		$query 		= " SELECT 
						  q.*,
						  p.`propertyName`,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS assignee_name 
						FROM
						  queries AS q 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = q.`propertyID` 
						  LEFT OUTER JOIN admin_managers AS a 
						    ON a.adminID = q.queryAssignee ";

		if (!empty($sql)) {
		    $query .= ' WHERE ' . implode(' AND ', $sql);
		}

		$query 		.=" ORDER BY q.queryDate DESC ";

		// die($query);


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_assets_db ($properties = NULL) {

		if ($properties) {
		    $sql[]  = " a.prop_id IN(".$properties.") ";
		}


		$query 		= " SELECT 
						  a.*,
						  c.`company_name`,
						  p.`propertyName` 
						FROM
						  `assets` AS a 
						  INNER JOIN `constructors` AS c 
						    ON c.`id` = a.`supplier_id` 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = a.prop_id  ";

		if (!empty($sql)) {
		    $query .= ' WHERE ' . implode(' AND ', $sql);
		}


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_page_queries_db ($properties = NULL, $limit, $offset) {


		if ($properties) {
		    $sql[]  = " q.propertyID IN(".$properties.") ";
		}

		$query 		= " SELECT
						  q.*,
						  CONCAT_WS(' ',a.firstName,a.lastName) AS assignee_name
						FROM queries AS q
						  LEFT OUTER JOIN admin_managers AS a
						    ON a.adminID = q.queryAssignee ";

		if (!empty($sql)) {
		    $query .= ' WHERE ' . implode(' AND ', $sql);
		}

		$query 		.=" ORDER BY q.queryDate DESC ";
		$query 		.=" LIMIT  " .$limit;
		$query 		.=" OFFSET " .$offset;

		// echo $query;


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_filtered_queries_db ($company_id, $properties, $status, $query_type, $date_from, $date_to) {

		if (!empty($date_from) AND !empty($date_to)) {
		    $sql[]  = " q.queryDate BETWEEN '".$date_from."' AND '".$date_to."'";
		}


		if ($company_id) {
		    $sql[]  = " p.companyID = '".$company_id."' ";
		}

		if ($properties) {
		    $sql[]  = " q.propertyID IN(".$properties.") ";
		}

		if (!empty($status)) {
		    $sql[]  = " q.queryStatus = '".$status."' ";
		}

		if (!empty($query_type)) {
		    $sql[]  = " q.queryType = '".$query_type."' ";
		}

		$query 		= " SELECT 
						  q.*,
						  p.`propertyName`,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS assignee_name 
						FROM
						  queries AS q 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = q.`propertyID` 
						  LEFT OUTER JOIN admin_managers AS a 
						    ON a.adminID = q.queryAssignee  ";

		if (!empty($sql)) {
		    $query  .= ' WHERE ' . implode(' AND ', $sql);
		}

		$query 		.= " ORDER BY q.queryDate DESC ";

		// echo $query;
		// die();


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_for_company_db ($ID){

		$query 		= "SELECT 
						  *
						FROM
						  properties
						WHERE companyID = '".$ID."';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		// Bind
		// Test excute
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_properties_by_city_db ($city_name){

		$query 		= "SELECT 
						  *
						FROM
						  properties
						WHERE `propertyCity` = '".$city_name."'
						ORDER BY `propertyName` ;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		// Bind
		// Test excute
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_properties_db (){

		$query 		= "SELECT 
						  *
						FROM
						  properties";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		// Bind
		// Test excute
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_permission_by_type_db ($type){

		$query 		= "SELECT 
						  *
						FROM
						  admin_permissions
						WHERE permission_type = '".$type."';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		// Bind
		// Test excute
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_communication_list_db ($prop_id) {

		$query 		= "SELECT
						  n.id          AS n_id,
						  n.property_id AS n_property_id,
						  n.message     AS n_message,
						  n.mood        AS n_mood,
						  n.created     AS n_created,
						  s.id          AS s_id,
						  s.property_id AS s_property_id,
						  s.sms_text    AS s_sms_text,
						  s.mood        AS s_mood,
						  s.created     AS s_created,
						  e.id          AS e_id,
						  e.property_id AS e_property_id,
						  e.from        AS e_from,
						  e.subject     AS e_subject,
						  e.email_text  AS e_email_text,
						  e.created     AS e_created
						FROM notification_coms AS n
						  LEFT OUTER JOIN sms_coms AS s
						    ON n.property_id = s.property_id
						  LEFT OUTER JOIN email_coms AS e
						    ON s.property_id = e.property_id
						WHERE n.property_id = '" .$prop_id. "';";
						
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_notification_coms_db ($query_id) {

		$query 		= "SELECT 
						  *,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  notification_coms AS s 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`  = s.`user_id` 
						WHERE s.query_id 	= '" .$query_id. "'
						ORDER BY s.created DESC;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_notification_byid_db ($prop_id, $id) {

		$query 		= "SELECT
						  *
						FROM notification_coms AS n
						WHERE n.property_id = '" .$prop_id. "'
						AND n.id = '".$id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_email_coms_db ($prop_id) {

		$query 		= "SELECT
						  *
						FROM email_coms AS e
						WHERE e.property_id = '" .$prop_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_email_byid_db ($id) {

		$query 		= "SELECT
						  *
						FROM email_coms AS e
						WHERE e.id = '".$id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_email_property_byid_db ($id) {

		$query 		= "SELECT
						  *
						FROM email_properties_coms AS e
						WHERE e.id = '".$id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_byid_db ($id) {

		$query 		= "SELECT
						  *
						FROM `properties` AS e
						WHERE e.propertyID = '".$id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_email_byid_db ($prop_id, $id) {

		$query 		= "SELECT
						 *
						FROM email_properties_coms AS e
						WHERE e.property_id = '" .$prop_id. "'
						AND e.id = '".$id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_sms_coms_db ($QueryID) {

		$query 		= "SELECT 
						  *,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  sms_coms AS s 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`  = s.`user_id` 
						WHERE s.query_id 	= '" .$QueryID. "'
						ORDER BY s.created DESC;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_job_sms_coms_db ($QueryID) {

		$query 		= "SELECT 
						  *,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  jobs_sms_coms AS s 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`  = s.`user_id` 
						WHERE s.job_id 	= '" .$QueryID. "'
						ORDER BY s.created DESC;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_sms_coms_db ($prop_id) {

		$query 		= "SELECT 
						  *,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  sms_properties_coms AS s 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`  = s.`user_id` 
						WHERE s.prop_id 	= '" .$prop_id. "'
						ORDER BY s.created DESC;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_email_coms_db ($prop_id) {

		$query 		= "SELECT 
						  s.*,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  email_properties_coms AS s 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`  = s.`user_id` 
						WHERE s.property_id 	= '" .$prop_id. "'
						ORDER BY s.created DESC;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_res_sms_coms_db ($ResID) {

		$query 		= "SELECT 
						  *,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  res_sms_coms AS s 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`   = s.`user_id` 
						WHERE s.`resident_id`= '" .$ResID. "'
						ORDER BY s.created DESC;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_comment_coms_db ($QueryID) {

		$query 		= "SELECT 
						  *,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  query_comments AS q 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`  = q.`user_id` 
						WHERE q.query_id 	= '" .$QueryID. "'
						ORDER BY q.date_created DESC;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_job_comment_coms_db ($JobID) {

		$query 		= "SELECT 
						  *,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  job_comments AS q 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`  = q.`user_id` 
						WHERE q.job_id 	= '" .$JobID. "'
						ORDER BY q.date_created DESC;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_res_email_coms_db ($ResID) {

		$query 		= "SELECT 
						  *,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  res_email_coms AS q 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`  	= q.`user_id` 
						WHERE q.resident_id 	= '" .$ResID. "'
						ORDER BY q.created DESC;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_res_comment_coms_db ($ResID) {

		$query 		= "SELECT 
						  *,
						  CONCAT_WS(' ', a.firstName, a.lastName) AS full_name 
						FROM
						  resident_comments AS q 
						  INNER JOIN `admin_managers` a 
						    ON a.`adminID`  	= q.`user_id` 
						WHERE q.`resident_id` 	= '" .$ResID. "'
						ORDER BY q.date_created DESC;";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_sms_byid_db ($id) {

		$query 		= "SELECT
						  *
						FROM sms_coms AS s
						WHERE s.id = '".$id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_sms_property_byid_db ($id) {

		$query 		= "SELECT
						  *
						FROM sms_properties_coms AS s
						WHERE s.id = '".$id."';";

						// echo $query;


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_sms_byid_db ($prop_id, $id) {

		$query 		= "SELECT
						  *
						FROM sms_properties_coms AS s
						WHERE s.prop_id = '" .$prop_id. "'
						AND s.id = '".$id."';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function delete_sms_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `sms_coms` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_sms_property_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `sms_properties_coms` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_emergency_contact_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `emergency_contacts` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_credits_db ($transaction_id) {

		$query 		= "DELETE 
						FROM
						  `sms_credits` 
						WHERE transaction_id = '" .$transaction_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function activate_credits_db ($transaction_id) {

		$query 		= "UPDATE 
						  `sms_credits`
						  SET
						  `purchase_status` 	= 'active'
						WHERE transaction_id = '" .$transaction_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_markting_url_db ($prop_id, $marketing_url) {

		$query 		= "UPDATE 
						  `properties`
						  SET
						  `marketing_link` 	= '".$marketing_url."'
						WHERE propertyID = '" .$prop_id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_credit_number_db ($number_sms, $company_id) {
		
		$query 		= "UPDATE 
						  `sms_credits`
						  SET
						  `credits` 	 = credits - '".$number_sms."'
						WHERE company_id = '" .$transaction_id. "'
						 AND `purchase_status` = 'active';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_this_query_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `queries`
						WHERE `queryID` = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_this_job_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `query_jobs`
						WHERE `id` = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function delete_email_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `email_coms` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function delete_notification_info_db ($ID) {

		$query 		= "DELETE 
						FROM
						  `notifications` 
						WHERE id = '" .$ID. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}
	

	/**
	 * @param  
	 * @return 
	 */
	static public function get_recent_maintenance_list_db () {

		$query 		= "SELECT 
						  m.*,
						  CONCAT_WS(' ',u.firstName,u.lastName) AS full_name
						FROM
						  maintenance AS m
						LEFT OUTER JOIN users AS u
						  ON u.id = m.userId
						WHERE (m.date >= DATE(NOW()) - INTERVAL 7 DAY)
						ORDER BY m.date DESC;";
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	
	/**
	 * @param  
	 * @return 
	 */
	static public function get_four_queries_db ($properties) {

		if ($properties) {
		    $sql[]  = " q.propertyID IN(".$properties.") ";
		}


		$query 		= "SELECT
							*
						  FROM queries AS q
						 ";

		if (!empty($sql)) {
		    $query .= ' WHERE ' . implode(' AND ', $sql);
		}

		$query 		.= " ORDER BY q.queryDate DESC LIMIT 4";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_recent_billing_list_db () {

		$query 		= "SELECT 
						  m.*,
						  CONCAT_WS(' ',u.firstName,u.lastName) AS full_name
						FROM
						  billing AS m
						LEFT OUTER JOIN users AS u
						  ON u.id = m.userId
						WHERE (m.date >= DATE(NOW()) - INTERVAL 7 DAY)
						ORDER BY m.date DESC;";
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_responces_byrepid_db ($survey_id, $rep_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  `survey_responces` 
						WHERE survey_id = " .$survey_id. "
							AND rep_id  = " .$rep_id. ";";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_developments_db () {

		$query 		= "SELECT 
						  * 
						FROM
						  `developments`;";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_pro_bylist_db ($list) {

		$query 		= "SELECT *
						FROM ".$list.";";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_survey_responce_db (
											$form_id,
											$resp_id,
											$prop_id,
											$unit_no,
											$full_name,
											$cellphone,
											$q_num,
											$responce
										) {

		$query 		= "UPDATE 
						  `form_submissions`
						  SET
						  `responce`    	= '".$responce."'
						WHERE `form_id` 	= '" .$form_id. "'
						  AND `prop_id` 	= '" .$prop_id. "'
						  AND `unit_no` 	= '" .$unit_no. "'
						  AND `submit_id` 	= '" .$resp_id. "'
						  AND `q_num` 		= '" .$q_num. 	"';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_document_file_db (
											$ID,
											$prop_id,
											$file_name,
											$DocumentType
										) {

		$query 		= "UPDATE 
						  `documents`
						  SET
						  `type_id`    		= '".$DocumentType."',
						  `prop_id`    		= '".$prop_id."',
						  `doc_name`    	= '".$file_name."'
						WHERE `id` 			= '" .$ID. 	"';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function update_document_db (
											$ID,
											$prop_id,
											$DocumentType
										) {

		$query 		= "UPDATE 
						  `documents`
						  SET
						  `type_id`    		= '".$DocumentType."',
						  `prop_id`    		= '".$prop_id."'
						WHERE `id` 			= '" .$ID. 	"';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_survey_list_db ($ids) {

		$query 		= "SELECT 
						  * 
						FROM
						  survey_list 
						WHERE assignee_id IN (".$ids.") ;";
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_strike_rates_by_storeid_db ($id) {

		$query 		= "SELECT 
						  p.strikerate_id,
						  p.promo_id,
						  p.store_id,
						  p.user_id,
						  p.no_of_bins,
						  p.reason_for_no,
						  p.`date`,
						  s.storeName3 
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN stores AS s 
						    ON p.`store_id` = s.`store_id` 
						WHERE p.store_id = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_paf_compliance_db ($id) {

		$query 		= "SELECT 
						  p.*,
						  s.storeName3,
						  r.repName
						FROM
						  promotion_compliance AS p 
						  INNER JOIN stores as s
						  ON p.store_id = s.store_id
						  INNER JOIN reps as r
						  ON p.user_id = r.rep_id
						WHERE p.promo_id = '" .$id. "';";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_strike_rates_db ($promo_id) {

		$query 		= "SELECT 
						  p.strikerate_id,
						  p.promo_id,
						  p.store_id,
						  p.user_id,
						  p.no_of_bins,
						  p.reason_for_no,
						  p.`date`,
						  s.storeName3 AS store_name,
						  sp.promo_description,
						  sp.`number_of_bins` ,
						  r.repName AS rep_name,
						  r.agency 
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN stores AS s 
						    ON p.`store_id` = s.`store_id` 
						  INNER JOIN `store_promotions` AS sp 
						    ON sp.`promo_id` = p.`promo_id`
						  INNER JOIN reps AS r 
						    ON r.`rep_id` = p.`user_id`  
						WHERE sp.`promo_id`= '" .$promo_id. "'
						ORDER BY p.date DESC ";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_strike_rates_province_db ($promo_id, $province) {

		$query 		= "SELECT 
						  p.strikerate_id,
						  p.promo_id,
						  p.store_id,
						  p.user_id,
						  p.no_of_bins,
						  p.reason_for_no,
						  p.`date`,
						  s.storeName3 AS store_name,
						  sp.promo_description,
						  sp.`number_of_bins`,
						  r.repName AS rep_name,
						  r.agency 
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN stores AS s 
						    ON p.`store_id` = s.`store_id` 
						  INNER JOIN `store_promotions` AS sp 
						    ON sp.`promo_id` = p.`promo_id` 
						  INNER JOIN reps AS r 
						    ON r.`rep_id` = p.`user_id` 
						WHERE r.region = '" .$province. "' AND sp.promo_id = '" .$promo_id. "'
						ORDER BY p.date DESC ";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get striker rates by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_strike_rates_byreason_db ($promo_id, $reason) {

		$query 		= "SELECT 
						  p.strikerate_id,
						  p.promo_id,
						  p.store_id,
						  p.user_id,
						  p.no_of_bins,
						  p.reason_for_no,
						  p.`date`,
						  s.storeName3 AS store_name,
						  sp.promo_description,
						  sp.`number_of_bins` ,
						  r.repName AS rep_name,
						  r.agency 
						FROM
						  promotion_strikerate AS p 
						  INNER JOIN stores AS s 
						    ON p.`store_id` = s.`store_id` 
						  INNER JOIN `store_promotions` AS sp 
						    ON sp.`promo_id` = p.`promo_id`
						  INNER JOIN reps AS r 
						    ON r.`rep_id` = p.`user_id`
						WHERE sp.`promo_id`= '" .$promo_id. "' 
							AND p.reason_for_no = '" .$reason. "'
						ORDER BY p.date DESC ";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	static public function get_last_bin_bd ($promo_id, $level_name, $level_id) {

		$query 		= "SELECT 
						    p.strikerate_id,
						    p.promo_id,
						    p.store_id,
						    p.user_id,
						    p.no_of_bins,
						    p.reason_for_no,
						    p.`date`,
						    s.storeName3 AS store_name,
						    sp.promo_description,
						    sp.`number_of_bins`,
						    MAX(p.`date`) AS last_date
						  FROM
						    promotion_strikerate AS p 
						    INNER JOIN stores AS s 
						      ON p.`store_id` = s.`store_id` 
						    INNER JOIN `store_promotions` AS sp 
						      ON sp.`promo_id` = p.`promo_id` 
						  WHERE p.no_of_bins > 0 AND p.`promo_id` = '" .$promo_id. "' AND s." .$level_name. " = '" .$level_id. "'
						  ORDER BY p.date DESC";


		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' .$level_name. self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get number of bins
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_number_of_bins_db () {

		$query 		= "SELECT 
						  SUM(no_of_bins) AS total_bins 
						FROM
						  promotion_strikerate;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get promos
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_promos_db () {

		$query 		= "SELECT 
						  *
						FROM
						  store_promotions;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get 
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_compliance_promos_db () {

		$query 		= "SELECT 
						  *
						FROM
						  promotions;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get promos
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_promo_byid_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  store_promotions
						WHERE promo_id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_maitenace_byid_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  maintenance
						WHERE id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_billing_byid_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  billing
						WHERE id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_resident_byid_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  residents
						WHERE id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_asset_byid_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  assets
						WHERE id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_asset_info_byid_db ($id) {

		$query 		= "SELECT 
						  a.*,
						  c.`company_name` AS supplier_name,
						  p.`propertyName`,
						  co.`companyName`
						FROM
						  `assets` AS a 
						  INNER JOIN `constructors` AS c 
						    ON c.`id` = a.`supplier_id` 
						  INNER JOIN `properties` AS p 
						    ON p.propertyID = a.prop_id 
						  INNER JOIN companies AS co 
						    ON co.companyID = p.companyID
						WHERE a.id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_contractor_byid_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  constructors
						WHERE id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_residents_db () {

		$query 		= "SELECT 
						  *
						FROM
						  residents;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_prop_residents_db ($prop_id) {

		$query 		= "SELECT 
						  *
						FROM
						  residents
					   WHERE propertyID = '".$prop_id."';";
					   // echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_resident_owners_bypropid_db ($prop_id) {

		$query 		= "SELECT 
						  *
						FROM
						  residents
					   WHERE propertyID = '".$prop_id."' AND residentType = 'tenant';";
					   // echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_emergency_contacts_db ($prop_id) {

		$query 		= "SELECT 
						  *
						FROM
						  emergency_contacts
					   WHERE propertyId = '".$prop_id."';";
					   // echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_queries_byid_db ($id) {

		$query 		= "SELECT 
						  r.id AS user_id,
						  q.* 
						FROM
						  queries AS q 
						  INNER JOIN app_registrations AS r 
						    ON q.`deviceID` = r.`userDeviceToken` 
						    AND r.`propertyID` = q.`propertyID` 
						WHERE queryID = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_just_queries_byid_db ($id) {

		$query 		= "SELECT 
						  q.* 
						FROM
						  queries AS q
						WHERE queryID = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_query_byid_db ($id) {

		$query 		= "SELECT 
						  q.* 
						FROM
						  queries AS q
						WHERE queryID = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_get_appreg_byid_db ($id) {

		$query 		= "SELECT 
						  j.*,
						  r.*,
						  p.`propertyName` 
						FROM
						  query_jobs AS j 
						  INNER JOIN app_registrations AS r 
						    ON r.`id` = j.`user_id` 
						  INNER JOIN properties AS p 
						    ON p.`propertyID` = r.`propertyID`  
						WHERE j.id =  '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function get_job_byid_db ($id) {

		$query 		= "SELECT 
						  *,
						  j.status AS job_status, 
						  j.id AS job_id,
						  c.id AS supplier_id,
						  j.`prop_id` AS property_id
						FROM
						  `query_jobs` AS j 
						  LEFT OUTER JOIN queries AS q 
						    ON j.query_id = q.`queryID` 
						  LEFT OUTER JOIN `properties` AS p 
						    ON p.`propertyID` = j.`prop_id` 
						  LEFT OUTER JOIN constructors AS c 
						    ON c.id = j.`supplier_id` 
						WHERE j.`id` = '" .$id. "';";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_query_details_by_jobid_db ($id) {

		$query 		= "SELECT 
						  j.*,
						  r.`userCellphone` AS phone_number
						FROM
						  `query_jobs` AS j 
						  INNER JOIN `app_registrations` AS r
						    ON r.`id` = j.`user_id` 
						WHERE j.`id` =  '" .$id. "';";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_job_list_db ($prop_id) {

		$query 		= "SELECT 
						  *,
							j.`created_date` AS job_date,
						  j.status AS job_status, 
						  j.id AS job_id,
						  c.id AS supplier_id
						FROM
						  `query_jobs` AS j 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = j.`prop_id` 
						  LEFT OUTER JOIN constructors AS c 
						    ON c.id = j.`supplier_id` 
						WHERE j.`prop_id` = '" .$prop_id. "'
						GROUP BY j.id
						ORDER BY j.created_date DESC;";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_job_list_db ($prop_id) {

		$query 		= "SELECT 
						  *,
						  j.`created_date` AS job_date,
						  j.status AS job_status,
						  j.id AS job_id,
						  c.id AS supplier_id,
						  j.`prop_id` AS property_id
						FROM
						  `query_jobs` AS j 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = j.`prop_id` 
						  LEFT OUTER JOIN constructors AS c 
						    ON c.id = j.`supplier_id` 
						GROUP BY j.id 
						ORDER BY j.created_date DESC ;";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_company_job_list_db ($prop_array, $company_id) {

		if ($company_id) {
		    $sql[]  = " p.companyID = '".$company_id."' ";
		}

		if ($prop_array) {
		    $sql[]  = " j.`prop_id` IN(".$prop_array.") ";
		}

		$query 		= " SELECT 
						  *,
						  j.`created_date` AS job_date,
						  j.status AS job_status,
						  j.id AS job_id,
						  c.id AS supplier_id,
						  j.`prop_id` AS property_id
						FROM
						  `query_jobs` AS j 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = j.`prop_id` 
						  LEFT OUTER JOIN constructors AS c 
						    ON c.id = j.`supplier_id` 
						
						 ";

		if (!empty($sql)) {
		    $query  .= ' WHERE ' . implode(' AND ', $sql);
		}

		$query 		.= " GROUP BY j.id  ";
		$query 		.= " ORDER BY j.created_date DESC  ";

		// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_job_list_per_status_db ($prop_id, $JobStatus) {

		$query 		= "SELECT 
						  *,
							j.`created_date` AS job_date,
						  j.status AS job_status, 
						  j.id AS job_id,
						  c.id AS supplier_id
						FROM
						  `query_jobs` AS j 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = j.`prop_id` 
						  LEFT OUTER JOIN constructors AS c 
						    ON c.id = j.`supplier_id` 
						WHERE j.`prop_id` = '" .$prop_id. "' 
						AND j.status ='".$JobStatus."';";

						// echo $JobStatus;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}
	
	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_job_list_per_status_db ($prop_id, $JobStatus) {

		$query 		= "SELECT 
						  *,
							j.`created_date` AS job_date,
						  j.status AS job_status, 
						  j.id AS job_id,
						  c.id AS supplier_id
						FROM
						  `query_jobs` AS j 
						  INNER JOIN `properties` AS p 
						    ON p.`propertyID` = j.`prop_id` 
						  LEFT OUTER JOIN constructors AS c 
						    ON c.id = j.`supplier_id` 
						WHERE j.status ='".$JobStatus."';";

						// echo $JobStatus;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_job_by_id_db ($job_id) {

		$query 		= "SELECT 
						  *
						FROM
						  `query_jobs` AS j
						WHERE j.`id` = '" .$job_id. "';";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_qoute_byid_db ($qoute_id) {

		$query 		= "SELECT 
						  *
						FROM
						  `job_qoutes_details` AS j
						WHERE j.`id` = '" .$qoute_id. "';";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_property_trustees_db ($prop_id) {

		$query 		= "SELECT 
						  *
						FROM
						  `residents` AS r
						WHERE r.`propertyID`  = '" .$prop_id. "' 
						AND r.residentTrustee = 'yes';";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_job_quotes_by_id_db ($job_id) {

		$query 		= "SELECT 
						  *
						FROM
						  `job_qoutes_details` AS j
						WHERE j.`job_id` = '" .$job_id. "';";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_cities_by_country_db ($country_id) {

		$query 		= "SELECT 
						  *
						FROM
						  `cities` AS c
						WHERE c.`country_id` = '" .$country_id. "';";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_all_countries_db () {

		$query 		= "SELECT 
						  *
						FROM
						  `countries` ;";

						// echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_permission_byid_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  admin_permissions
						WHERE permission_id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_contact_table_db ($ID) {

		$query 		= "SELECT 
						  *
						FROM
						  emergency_contacts
						WHERE id = '" .$ID. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_question_byid_db ($id) {

		$query 		= "SELECT 
						  q.*,
						  s.`title` AS survey_name 
						FROM
						  survey_questions AS q 
						  INNER JOIN survey_list s 
						    ON s.id = q.`survey_id`
						WHERE q.id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get promos
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_all_stores_db () {

		$query 		= "SELECT 
						  *
						FROM
						  stores;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get promos
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_level_3_stores_db () {

		$query 		= "SELECT 
						  *,
						  COUNT(level3_id) AS contribution
						FROM
						  stores 
						GROUP BY level3_id ;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get store by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_store_by_levelid_db ($level_id, $level_name) {

		$query 		= "SELECT 
						  *
						FROM
						  stores
						  WHERE " .$level_name. " = '" .$level_id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_questions_bysurveyid_db ($survey_id) {

		$query 		= "SELECT 
						  q.*,
						  s.`id` AS s_id,
						  s.`title`,
						  s.`description`,
						  s.`end_date`,
						  s.`start_date`,
						  s.`assignee_id`,
						  s.`assign_to`,
						  s.`level_name`,
						  s.`status`,
						  s.`created` 
						FROM
						  `survey_questions` AS q 
						  INNER JOIN `survey_list` AS s 
						    ON q.`survey_id` = s.`id` 
						WHERE q.survey_id = '" .$survey_id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get store by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_stores_byid_db ($store_id) {

		$query 		= "SELECT 
						  * 
						FROM
						  stores AS s 
					    WHERE s.`store_id` = '" .$store_id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get rep by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_rep_byid_db ($user_id) {

		$query 		= "SELECT 
						  rep_id, 
						  repName,
						  agency,
						  password,
						  manager,
						  region,
						  status
						FROM
						  reps AS r
					    WHERE r.`rep_id` = '" .$user_id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get rep by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_user_byid_db ($user_id) {

		$query 		= "SELECT 
						  *
						FROM
						  admin_managers AS u
					    WHERE u.`adminID` = '" .$user_id. "';";

					    // echo $query;
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_users_by_pro_db ($ids) {

		$query 		= "SELECT *
						FROM user_prop AS up
						  INNER JOIN users AS u
						    ON u.id = up.user_id
						WHERE up.tenant_id IN(" .$ids. ");";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_user_by_email_db ($email) {

		$query 		= "SELECT 
						  user_id, 
						  permission_id,
						  first_name,
						  last_name,
						  email
						FROM
						  admin_users AS u
					    WHERE u.`email` = '" .$email. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_user_types_db () {

		$query 		= "SELECT 
						  *
						FROM
						  admin_permissions AS p;";

		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get rep by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_rep_all_db () {

		$query 		= "SELECT 
						  *
						FROM
						  reps AS r;";

		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_users_all_db ($company_id) {


		if ($company_id) {
		    $sql[]  = " u.companyID =  '".$company_id."'";
		}

		$query 		= " SELECT 
						  * 
						FROM
						    `admin_managers` AS u 
							LEFT OUTER JOIN `admin_permissions` AS p 
						ON p.`permission_id` = u.`permission_id` ";

		if (!empty($sql)) {
		    $query .= ' WHERE ' . implode(' AND ', $sql);
		}

		// echo $query;

		

		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);
		return $results;
	}

	/**
	 * Get call cycle
	 * For: Admin
	 * @param  
	 * @return LEFT OUTER JOIN
	 */
	static public function get_cyledetails_db ($user_id) {

		$query 		= "SELECT 
						    *,
							DATE_FORMAT(
						      FROM_UNIXTIME(l.`checkIn`),
						      '%W %e %M %Y'
						    ) AS lastcalled
						  FROM
						    rep_store AS rs 
						    LEFT OUTER JOIN `stores` AS s 
						      ON rs.store_id = s.store_id 
						    LEFT OUTER JOIN `locations` AS l 
						      ON l.store_id = s.store_id
						  WHERE rs.`rep_id` = '" .$user_id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;

	}

	/**
	 * Get Locations
	 * @param  
	 * @return 
	 */
	static public function get_locations_byuserid_db ($user_id) {

		$query 		= "SELECT 
						  *,
						    DATE_FORMAT(
						      FROM_UNIXTIME(`checkIn`),
						      '%W %e %M %Y'
						    ) AS lastcalled
						FROM
						  `locations` 
					    WHERE `user_id` = '" .$user_id. "'
					    ORDER BY `checkIn` DESC;";

		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get Locations
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_locations_byid_db ($store_id) {

		$query 		= "SELECT 
						  *,
						    DATE_FORMAT(
						      FROM_UNIXTIME(`checkIn`),
						      '%W %e %M %Y'
						    ) AS lastcalled
						FROM
						  `locations` 
					    WHERE `store_id` = '" .$store_id. "'
					    ORDER BY `checkIn` DESC;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get Locations
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_repinfo_byid_db ($store_id) {

		$query 		= "SELECT 
					    * 
					  FROM
					    `rep_store` AS r 
					    INNER JOIN `reps` AS rp 
					      ON r.rep_id = rp.rep_id 
					    WHERE r.`store_id` = '" .$store_id. "'
					    LIMIT 1;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get store by id
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_hierarchy_db ($store_id) {

		$query 		= "SELECT 
					      level3_id,
					      level4_id,
					      level5_id,
					      level6_id,
					      store_id 
					    FROM
					      stores 
					    WHERE store_id = '" .$store_id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Get user details
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_userdetails ($email) {

		$query 		= "SELECT
						  u.adminID      AS user_id,
						  u.firstName    AS first_name,
						  u.lastName     AS last_name,
						  u.contactEmail AS email,
						  u.password     AS password,
						  u.passwordText AS passwordText,
						  p.*
						FROM admin_managers AS u
						  INNER JOIN `admin_permissions` AS p
						    ON u.`permission_id` = p.`permission_id`
						WHERE u.contactEmail = '".$email."';";


		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		// Bind
		// Test excute
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_questions_db () {

		$query 		= "SELECT 
						  q.*,
						  s.`title` AS survey_name 
						FROM
						  survey_questions AS q 
						  INNER JOIN survey_list s 
						    ON s.id = q.`survey_id`
						ORDER BY s.`title` ;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}


	/**
	 * @param  
	 * @return 
	 */
	static public function insert_survey_info_db ($Title, $Description, $StartDate, $EndDate, $AssignTo, $AssignID, $LevelName) {

		$query 		= "INSERT INTO `survey_list` (
						  `title`,
						  `description`,
						  `start_date`,
						  `end_date`,
						  `assign_to`,
						  `assignee_id`,
						  `level_name`
						) 
						VALUES
						  (
						    '".$Title."',
						    '".$Description."',
						    '".$StartDate."',
						    '".$EndDate."',
						    '".$AssignTo."',
						    '".$AssignID."',
						    '".$LevelName."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function save_admin_user_db ($FirstName, $Surname, $Email, $CellNumber, $Password, $UserType) {

		$query 		= "INSERT INTO `admin_users` (
						  `permission_id`,
						  `first_name`,
						  `last_name`,
						  `email`,
						  `cellphone`,
						  `password`
						) 
						VALUES
						  (
						    '".$UserType."',
						    '".$FirstName."',
						    '".$Surname."',
						    '".$Email."',
						    '".$CellNumber."',
						    '".$Password."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}



	/**
	 * @param  
	 * @return 
	 */
	static public function insert_question_info_db ($SurveyID, $QNumber, $QText, $Options, $QType) {

		$query 		= "INSERT INTO `survey_questions` (
						  `survey_id`,
						  `q_num`,
						  `q_text`,
						  `q_options`,
						  `q_type`
						) 
						VALUES
						  (
						    '".$SurveyID."',
						    '".$QNumber."',
						    '".$QText."',
						    '".$Options."',
						    '".$QType."'
						  );";

		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());

		return $stmt;
	}


	/**
	 * Get surveys
	 * For: Admin
	 * @param  
	 * @return 
	 */
	static public function get_surveys_db () {

		$query 		= "SELECT 
						  *
						FROM
						  survey_list
						ORDER BY created;";
		// Prepare
		$stmt   	= self::$mysqli->query($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * @param  
	 * @return 
	 */
	static public function get_survey_byid_db ($id) {

		$query 		= "SELECT 
						  *
						FROM
						  survey_list
						WHERE id = '" .$id. "';";
		// Prepare
		$stmt   	= self::$mysqli->query ($query) or die('Failed to prepare: ' . self::$mysqli->error());
		$results    = self::fetch_assoc_arr ($stmt);

		return $results;
	}

	/**
	 * Fetch Assoc
	 * 
	 * @param  $salesCode 
	 * @return boolen
	 */
	static public function fetch_assoc_arr ($result) {
		$results = array();
		$count   = 0;

		if (!$result) {
			printf("Error: %s\n", self::$mysqli->error());
			exit();
		} else {
			/* fetch value */
			while($row = $result->fetch_array (MYSQLI_ASSOC)){
				// Push results in array
				array_push ($results, $row);
				$count++;
			}
			return $results;
		}

	}

	/**
	 * Fomat query results to an array
	 * 
	 * @param  $salesCode 
	 * @return boolen
	 */
	static public function fetch_array_mssql ($result) {
		$results = array();
		$count   = 0;
		if (!$result) {
			return 'Error: ' . self::$mysqli->error();
		}else{
			/* fetch value */
			while($row = self::$mssql->fetch_array($result)) {
				// Push results in array
				array_push($results, $row);
				$count++;
			}
			return $results;
		}

	}

}
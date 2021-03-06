<?php
	require_once dirname(__FILE__)."../nzcf-unit-administration.php";
	require_once dirname(__FILE__)."defines.inc";
	
	class WP_NZCF_UA
	{
		
		public function __construct()
		{
			// Add permissions to the WordPress roles
			$admins = get_role('administrator');
			$admins->add_cap(
		}
		
		public function add_term( $startdate, $enddate )
		{
			global $wbpd; // Our WordPress DB object
			if(!$this->user_has_permission( NZCF_PERMISSION_ATTENDANCE_EDIT ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = "
				INSERT INTO `term` (
					`startdate`,
					`enddate` 
				) VALUES ( 
					'".date("Y-m-d",$startdate)."',
					'".date("Y-m-d",$enddate)."' 
				);";
			if ($result = self::$mysqli->query($query))
			{
				self::log_action( 'term', $query, self::$mysqli->insert_id );
				return true;
			}
			else throw new NZCFExceptionDBError(self::$mysqli->error);
		}
		
		public function add_promotion( $rank_id, $personnel_id, $date )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_PERSONNEL_EDIT, $personnel_id ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = "
				INSERT INTO `personnel_rank` (
					`rank_id`, 
					`personnel_id`, 
					`date_achieved` 
				) VALUES ( 
					".(int)$rank_id.", 
					".(int)$personnel_id.", 
					'".date("Y-m-d",strtotime($date))."' 
				);";

			if ($result = self::$mysqli->query($query))
			{
				self::log_action( 'personnel_rank', $query, self::$mysqli->insert_id );
				self::$mysqli->query("UPDATE `personnel` SET `rank_id` = ".(int)$rank_id." WHERE `personnel_id` = ".(int)$personnel_id." LIMIT 1;");
				return self::$mysqli->insert_id;
			}
			else throw new NZCFExceptionDBError(self::$mysqli->error);
		}
		
		public function become_user_from_session( $sessid )
		{
			$details = $this->check_user_session($sessid);
			if($details)
			{
				$this->currentuser = $details->personnel_id;
				$units = $this->get_units_for_personnel( $details->personnel_id );
				$this->currentunit = $units[0]->unit_id;
				$this->currentpermissions = $units[0]->access_rights;
			}
		}

		public function check_user_session( $session, $useragent=null )
		{
			$query = "
				SELECT 
					* 
				FROM 
					`user_session` 
					INNER JOIN `personnel` 
						ON `user_session`.`personnel_id` = `personnel`.`personnel_id` 
				WHERE 
					`session_code` = '".self::$mysqli->real_escape_string($session)."' 
					".(is_null($useragent)?'':' AND `user_agent` = "'.self::$mysqli->real_escape_string($useragent).'"')." 
				LIMIT 1;";
			if ($result = self::$mysqli->query($query))	
			{
				if ( $obj = $result->fetch_object() )
				{
					return $obj;
				} else throw new NZCFExceptionInvalidUserSession('Unknown session');
			}
			else throw new NZCFExceptionDBError(self::$mysqli->error);
			return false;
		}

/*
		public function __destruct()
		{
			//self::$mysqli->close();
		}
*/
		
		public function current_user_id()
		{
			return $this->currentuser;
		}
		
		public function find_current_user_session( $useragent )
		{
			$query = "
				SELECT 
					* 
				FROM 
					`user_session` 
				WHERE 
					`personnel_id` = ".$this->currentuser."
					AND `user_agent` = '".self::$mysqli->real_escape_string($useragent)."' 
				LIMIT 1;";
			if ($result = self::$mysqli->query($query))	
			{
				if ( $obj = $result->fetch_object() )
				{
					return $obj;
				} else throw new NZCFExceptionInvalidUserSession('Unknown session');
			}
			else throw new NZCFExceptionDBError(self::$mysqli->error);
			return false;
		}
		
		public function delete_activity( $id )
		{
			// Also don't allow deletes of default values
			if(!$this->user_has_permission( NZCF_PERMISSION_ACTIVITIES_EDIT ) || !(int)$id )
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = 'DELETE FROM `activity` WHERE `activity`.`activity_id` = '.(int)$id.' LIMIT 1;';

			if ($result = self::$mysqli->query($query))
			{
				self::log_action( 'activity', $query, (int)$id );
				return true;
			} else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return false;
		}
		
		public function get_activities( $date=null, $days=365 )
		{
			if( is_null($date) ) $startdate = strtotime(date("Y")."-01-01");
			else $startdate = strtotime($date);
			$enddate = $startdate + ((int)$days*24*60*60);
			
			if(!$this->user_has_permission( NZCF_PERMISSION_ACTIVITIES_VIEW ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = '
				SELECT
					`activity`.*,
					`activity_type`.*,
					`location`.`name` AS `location_name`,
					`location`.`address`,
					`personnel`.`personnel_id`,
					`2ic_personnel`.`personnel_id` AS `twoic_personnel_id`,
					CASE WHEN `activity`.`enddate` < now() THEN 1 ELSE 0 END AS `sortorder`, 
					(
						SELECT
							COUNT(`personnel`.`personnel_id`)
						FROM
							`activity_register`
							INNER JOIN `personnel`
								ON `personnel`.`personnel_id` = `activity_register`.`personnel_id`
							INNER JOIN `rank`
								ON `rank`.`rank_id` = `personnel`.`rank_id`
						WHERE
							`activity_register`.`activity_id` = `activity`.`activity_id`
							AND `rank`.`nzcf20_order` > '.NZCF_RANK_HIGHEST_NCO.'
							-- AND `personnel`.`access_rights` IN ('.NZCF_USER_GROUP_OFFICERS.')
					) AS `officers_attending`,
					(
						SELECT	
							COUNT(`personnel`.`personnel_id`)
						FROM
							`activity_register`
							INNER JOIN `personnel`
								ON `personnel`.`personnel_id` = `activity_register`.`personnel_id`
							INNER JOIN `rank`
								ON `rank`.`rank_id` = `personnel`.`rank_id`
						WHERE	
							`activity_register`.`activity_id` = `activity`.`activity_id`
							AND `rank`.`nzcf20_order` <= '.NZCF_RANK_HIGHEST_NCO.'
							-- AND `personnel`.`access_rights` IN ('.NZCF_USER_GROUP_CADETS.')
					) AS `cadets_attending`,
					(
						SELECT	GROUP_CONCAT(DISTINCT `personnel_id` SEPARATOR ",")
						FROM 	`activity_register`
						GROUP BY `activity_id`
						HAVING	`activity_id` = `activity`.`activity_id`
					) AS `attendees`
				FROM 	`activity` 
					INNER JOIN `activity_type`
						ON `activity`.`activity_type_id` = `activity_type`.`activity_type_id`
					INNER JOIN `personnel`
						ON `activity`.`personnel_id` = `personnel`.`personnel_id`
					INNER JOIN `personnel` `2ic_personnel`
						ON `activity`.`2ic_personnel_id` = `2ic_personnel`.`personnel_id`
					INNER JOIN `location`
						ON `activity`.`location_id` = `location`.`location_id`
				WHERE 	`activity`.`startdate` BETWEEN "'.date('Y-m-d', $startdate).'" AND "'.date('Y-m-d', $enddate).'" 
					AND `activity`.`activity_id` > 0
				ORDER BY `sortorder`, `startdate` ASC;';

			$activities = array();
			require_once "nzcf_personnel.class.php";
			if ($result = self::$mysqli->query($query))
			{
				while ( $obj = $result->fetch_object() )
				{
					$activities[] = $obj;
					$activities[count($activities)-1]->personnel = new NZCFMember($obj->personnel_id);
					$activities[count($activities)-1]->twoic_personnel = new NZCFMember($obj->twoic_personnel_id);
				}
			}	
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);
			
			return $activities;
		}
		
		public function get_activity( $id )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_ACTIVITIES_VIEW ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = '
				SELECT	`activity`.*,
					`activity_type`.*,
					`location`.*,
					'.NZCF_SETTING_DISPLAY_NAME.' AS `display_name`,
					`personnel`.`mobile_phone`,
					`personnel`.`personnel_id`,
					'.str_replace("personnel","2ic_personnel",NZCF_SETTING_DISPLAY_NAME).' AS `twoic_display_name`,
					`2ic_personnel`.`mobile_phone` AS `twoic_mobile_phone`,
					`2ic_personnel`.`personnel_id` AS `twoic_personnel_id`,
					(
						SELECT	GROUP_CONCAT(DISTINCT `personnel_id` SEPARATOR ",")
						FROM 	`activity_register`
						GROUP BY `activity_id`
						HAVING	`activity_id` = `activity`.`activity_id`
					) AS `attendees`
				FROM 	`activity` 
					INNER JOIN `activity_type`
						ON `activity`.`activity_type_id` = `activity_type`.`activity_type_id`
					INNER JOIN `personnel`
						ON `activity`.`personnel_id` = `personnel`.`personnel_id`
					INNER JOIN `personnel` `2ic_personnel`
						ON `activity`.`2ic_personnel_id` = `2ic_personnel`.`personnel_id`
					INNER JOIN `location`
						ON `activity`.`location_id` = `location`.`location_id`
				WHERE 	`activity`.`activity_id` = '.(int)$id.' 
				LIMIT 1;';

			$activities = array();
			if ($result = self::$mysqli->query($query))
			{
				while ( $obj = $result->fetch_object() )
				{
					$obj->startdate = date(NZCF_SETTING_DATETIME_INPUT, strtotime($obj->startdate));
					$obj->enddate = date(NZCF_SETTING_DATETIME_INPUT, strtotime($obj->enddate));
					$obj->attendees = explode(',', $obj->attendees);
					$activities[] = $obj;
				}
			}	
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return $activities;
		}
		
		public function get_activity_attendance( $id )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_ACTIVITIES_VIEW ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
			if( !$this->user_has_permission(NZCF_PERMISSION_PERSONNEL_VIEW) )
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
			
			$query = '
				SELECT	`activity_register`.*,
					'.NZCF_SETTING_DISPLAY_NAME.' AS `display_name`,
					'.NZCF_SETTING_DISPLAY_RANK_SHORTNAME.' AS `rank`,
					`personnel`.`mobile_phone`,
					`personnel`.`allergies`,
					`personnel`.`access_rights`,
					`personnel`.`medical_conditions`,
					`personnel`.`medicinal_reactions`,
					`personnel`.`dietary_requirements`,
					`personnel`.`other_notes`,
					`personnel`.`social_media_approved`
				FROM 	`activity_register`
					INNER JOIN `personnel`
						ON `activity_register`.`personnel_id` = `personnel`.`personnel_id`
				WHERE 	`activity_register`.`activity_id` = '.(int)$id.'
				ORDER BY `personnel`.`lastname`, `personnel`.`firstname`;';

			$attendees = array();
			if ($result = self::$mysqli->query($query))
				while ( $obj = $result->fetch_object() )
				{
					if(!is_null($obj->presence))
						$obj->presence = (int)$obj->presence;
					$attendees[] = $obj;
				}
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return $attendees;
		}
		
		public function get_activity_names()
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_ACTIVITIES_VIEW ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = 'SELECT DISTINCT `title` FROM 	`activity` WHERE `activity`.`activity_id` > 0 ORDER BY LOWER(`title`) ASC;';

			$activities = array();
			if ($result = self::$mysqli->query($query))
				while ( $obj = $result->fetch_object() )
					$activities[] = $obj->title;
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);
			return $activities;
		}
		
		public function get_activity_types()
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_ACTIVITIES_VIEW ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = 'SELECT * FROM `activity_type` ORDER BY LOWER(`type`) ASC;';

			$activities = array();
			if ($result = self::$mysqli->query($query))
				while ( $obj = $result->fetch_object() )
					$activities[] = $obj;
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);
			return $activities;
		}
		
		public function get_attendance_register( $startdate, $enddate )
		{
			$startdate = strtotime($startdate);
			$enddate = strtotime($enddate);
				
			$query = '
			SELECT	`attendance_register`.*,
				`personnel`.`access_rights`
			FROM 	`attendance_register` 
				INNER JOIN `personnel` 
					ON `attendance_register`.`personnel_id` = `personnel`.`personnel_id` 
			WHERE 	`attendance_register`.`date` BETWEEN "'.date('Y-m-d', $startdate).'" AND "'.date('Y-m-d', $enddate).'" 
				AND `personnel`.`access_rights` IN ('.NZCF_USER_GROUP_PERSONNEL.') 
				AND `personnel`.`enabled` = -1
				AND `personnel`.`left_date` IS NULL 
			ORDER BY `personnel`.`personnel_id`, `attendance_register`.`date` ASC;';

			$attendance = array();
			if ($result = self::$mysqli->query($query))
			{
				while ( $obj = $result->fetch_object() )
					$attendance[] = $obj;
			}	
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return $attendance;
		}
		
		public function get_awol( $startdate, $enddate )
		{
			$startdate = strtotime($startdate);
			$enddate = strtotime($enddate);
			
			$query = '
			SELECT	`attendance_register`.*,
				`personnel`.*,
				'.NZCF_SETTING_DISPLAY_NAME.' AS `display_name`
			FROM 	`attendance_register` 
				INNER JOIN `personnel` 
					ON `attendance_register`.`personnel_id` = `personnel`.`personnel_id` 
			WHERE 	`attendance_register`.`date` BETWEEN "'.date('Y-m-d', $startdate).'" AND "'.date('Y-m-d', $enddate).'"  
				AND `attendance_register`.`presence` = '.NZCF_ATTENDANCE_ABSENT_WITHOUT_LEAVE.'
				-- AND ( LENGTH(`attendance_register`.`comment`) = 0 OR LENGTH(`attendance_register`.`comment`) IS NULL )
				AND `personnel`.`access_rights` IN ('.NZCF_USER_GROUP_PERSONNEL.') 
			ORDER BY `date` DESC, `display_name` ASC;';

			$awollers = array();
			if ($result = self::$mysqli->query($query))
			{
				while ( $obj = $result->fetch_object() )
				{
					$obj->nok = $this->get_nok($obj->personnel_id);
					$awollers[] = $obj;
				}
			}	
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);
			return $awollers;
		}
		
		public function get_cadets_risking_sign_off()
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_ATTENDANCE_VIEW ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
			
			$query = '
				SELECT 
					`T3`.*, 
					`personnel`.`personnel_id`, 
					'.NZCF_SETTING_DISPLAY_NAME.' AS `display_name`,
					'.NZCF_SETTING_DISPLAY_RANK_SHORTNAME.' AS `rank`,
					COUNT(*) AS `missed_nights`
				FROM
					(
						SELECT 
							*
						FROM
							(
								SELECT
									`T1`.`date`,
									`T1`.`presence`, 
									`T1`.personnel_id,
									(
										SELECT 
											MAX(`date`) 
										FROM 
											`attendance_register` `T` 
										WHERE 
											`T`.`date` < `T1`.`date` 
											AND `T`.`presence` <> `T1`.`presence`
									) AS `MaxDate`
								FROM 
									`attendance_register` `T1`
								ORDER BY 
									`date` DESC
							) `T2`
						WHERE 
							`presence` = '.NZCF_ATTENDANCE_ABSENT_WITHOUT_LEAVE.'
					) `T3`
					INNER JOIN `personnel` 
						ON `T3`.`personnel_id` = `personnel`.`personnel_id`
				GROUP BY
					`T3`.`personnel_id`
				HAVING
					`date` = (SELECT MAX(`date`) FROM `attendance_register`) 
					AND COUNT(*) >= 2
				ORDER BY
					`display_name`;';
					
			$results = array();
			if ($result = self::$mysqli->query($query))
				while ( $obj = $result->fetch_object() )
					$results[] = $obj;
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);
			return $results;
		}
		
		public function get_currentuser_id() { return $this->currentuser; }
		
		public function get_flights()
		{
				
			$query = 'SELECT DISTINCT `flight` FROM `personnel` WHERE LENGTH(TRIM(`flight`)) > 0 ORDER BY LOWER(`flight`);';

			$flights = array();
			if ($result = self::$mysqli->query($query))
				while ( $obj = $result->fetch_object() )
					$flights[] = $obj->flight;
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);
			return $flights;
		}
		
		public function get_location( $id=0 )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_LOCATIONS_VIEW ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = 'SELECT * FROM `location` WHERE `location_id` = '.(int)$id.' LIMIT 1;';

			if ($result = self::$mysqli->query($query))
				return $result->fetch_object();
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);
		}
		
		public function get_locations()
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_LOCATIONS_VIEW ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = 'SELECT * FROM `location` ORDER BY LOWER(`name`) ASC;';

			$activities = array();
			if ($result = self::$mysqli->query($query))
				while ( $obj = $result->fetch_object() )
					$activities[] = $obj;
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);
			return $activities;
		}
		
		public function get_nok( $for_personnel_id, $nok_id=null )
		{				
			$query = '
			SELECT	*
			FROM 	`next_of_kin`
			WHERE 	`personnel_id` = '.(int)$for_personnel_id.'
			ORDER BY `sort_order`, `lastname`,`firstname` ASC;';

			$nok = array();
			if ($result = self::$mysqli->query($query))
			{
				while ( $obj = $result->fetch_object() )
					$nok[] = $obj;
			}	
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return $nok;
		}
		
		
		public function get_personnel( $id, $orderby = "ASC", $access_rights=null, $showall=false )
		{
			$personnel = new stdClass();

			switch( $id )
			{
				// only loose casting, so work it out properly here
				case null:
				case 0:
					if( is_null($id) )
					{
						$personnel = array();
						$query = "
							SELECT
								*, 
								".NZCF_SETTING_DISPLAY_NAME." AS `display_name`,
								".NZCF_SETTING_DISPLAY_RANK_SHORTNAME." AS `rank`
							FROM 
								`personnel`
								INNER JOIN `unit_personnel`
									ON `unit_personnel`.`personnel_id` = `personnel`.`personnel_id` 
									AND `unit_personnel`.`unit_id` IN ( (
										SELECT 
											GROUP_CONCAT(',', `unit_id`)
										FROM
											`unit_personnel`
										WHERE
											`personnel_id` = ".$this->currentuser."
									) )
							WHERE 
								`personnel`.`personnel_id` > 0 ";
						if( !is_null($access_rights) )
							$query .= ' 
								AND `access_rights` IN ('.self::$mysqli->real_escape_string($access_rights).') ';
						
						if( !(bool)$showall )
							$query .= " 
								AND `enabled` = -1 
								AND `access_rights` IN (".NZCF_USER_GROUP_PERSONNEL.") 
								AND `left_date` IS NULL";
						$query .= " 
							ORDER BY 
								`enabled` ASC, 
								`lastname` ".self::$mysqli->real_escape_string($orderby).", 
								`firstname` ".self::$mysqli->real_escape_string($orderby).", 
								`personnel`.`personnel_id` ".self::$mysqli->real_escape_string($orderby).";";

						if ($result = self::$mysqli->query($query))
						{
							while ( $obj = $result->fetch_object() )
								$personnel[] = $obj;
						}	
						else
							throw new NZCFExceptionDBError(self::$mysqli->error);
					} else {
						$personnel->personnel_id = 0;
						$personnel->firstname = null;
						$personnel->lastname = null;
						$personnel->email = null;
						$personnel->access_rights = 0;
						$personnel->joined_date = null;
						$personnel->left_date = null;
						$personnel->is_female = 0;
						$personnel->dob = null;
						$personnel->rank = null;
						$personnel->enabled = -1;
						$personnel->created = date("d/m/Y h:i a", time());
						$personnel->display_name = "New user";
					}
					break;
				default:
					if(!$this->user_has_permission( NZCF_PERMISSION_PERSONNEL_VIEW, $id ))
					    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this user");
				
					$query = "SELECT *,
						".NZCF_SETTING_DISPLAY_NAME." AS `display_name`,
						".NZCF_SETTING_DISPLAY_RANK_SHORTNAME." AS `rank` 
					FROM `personnel` 
					WHERE `personnel_id` = ".(int)$id." 
					LIMIT 1;";
					
					if ($result = self::$mysqli->query($query)) 
						$personnel = $result->fetch_object();
					else
						throw new NZCFExceptionDBError(self::$mysqli->error);
					$personnel->created = date("Y-m-d\TH:i", strtotime($personnel->created));
					
					break;
			}
			return $personnel;
		}
		
		public function get_promotion_history( $userid )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_PERSONNEL_VIEW, $userid ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");
				
			$query = '
				SELECT * 
				FROM `personnel_rank`
					INNER JOIN `rank`
						ON `rank`.`rank_id` = `personnel_rank`.`rank_id` 
				WHERE `personnel_id` = '.(int)$userid.' 
				ORDER BY `date_achieved` DESC;';

			$promotions = array();
			if ($result = self::$mysqli->query($query))
			{
				while ( $obj = $result->fetch_object() )
					$promotions[] = $obj;
			}	
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return $promotions;
		}
		
		public function get_ranks( )
		{
			$query = '
				SELECT * 
				FROM `rank`
				ORDER BY `ordering` ASC;';

			$ranks = array();
			if ($result = self::$mysqli->query($query))
			{
				while ( $obj = $result->fetch_object() )
					$ranks[] = $obj;
			}	
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return $ranks;
		}
		
		public function get_terms( $startdate=null, $enddate=null )
		{
			$startdate = (is_null($startdate)?null:strtotime($startdate));
			$enddate = (is_null($enddate)?null:strtotime($enddate));
			
			$query = '
				SELECT 
					* 
				FROM 
					`term`
				WHERE 
					1=1
					'. (is_null($startdate)?'':' AND `startdate` >= "'.date('Y-m-d',$startdate).'"').'
					'. (is_null($enddate)?'':' AND `enddate` <= "'.date('Y-m-d',$enddate).'"').'
				ORDER BY 
					`startdate` ASC;';

			$terms = array();
			if ($result = self::$mysqli->query($query))
				while ( $obj = $result->fetch_object() )
				{
					$obj->startdate = strtotime($obj->startdate);
					$obj->enddate = strtotime($obj->enddate);
					$terms[] = $obj;
				}
			
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return $terms;
		}
		
		public function get_units_for_personnel( $id )
		{
			$query = '
				SELECT 
					*
				FROM 
					`unit_personnel`
					INNER JOIN `unit`
						ON `unit_personnel`.`unit_id` = `unit`.`unit_id`
				WHERE 
					`personnel_id` = '.(int)$id.';';

			$results = array();
			if ($result = self::$mysqli->query($query))
				while ( $obj = $result->fetch_object() )
					$results[] = $obj;			
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return $results;
		}
		
		public function get_usernames( $id )
		{
			$query = '
				SELECT 
					`username`,
					`primary`
				FROM 
					`username`
				WHERE 
					`personnel_id` = '.(int)$id.';';

			$results = array();
			if ($result = self::$mysqli->query($query))
				while ( $obj = $result->fetch_object() )
					$results[] = $obj;			
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);

			return $results;
		}
		
		// Keep a track of who's doing what, for later auditing.
		protected function log_action( $table_name, $sql_run, $idrow )
		{
			$query = "INSERT INTO `log_changes` (`personnel_id`, `sql_executed`, `table_updated`, `row_updated` ) VALUES ( ".$this->currentuser.', "'.self::$mysqli->real_escape_string($sql_run).'", "'.self::$mysqli->real_escape_string($table_name).'", '.(int)$idrow.' );';
			if ($result = self::$mysqli->query($query))	return true;
			else throw new NZCFExceptionDBError(self::$mysqli->error);
		}
		
		public function login( $username, $password )
		{
			$query = "
				SELECT 
					`personnel`.`password` AS `correct_hash`, 
					`personnel`.`personnel_id` 
				FROM 
					`personnel` 
					INNER JOIN `username`
						ON `personnel`.`personnel_id` = `username`.`personnel_id`
				WHERE 
					`username`.`username` = '".self::$mysqli->real_escape_string($username)."' 
					AND `personnel`.`enabled` = -1 
				LIMIT 
					1;";
			if ($result = self::$mysqli->query($query))	
			{
				if ( $obj = $result->fetch_object() )
				{
					if( validate_password($password, $obj->correct_hash) )
					{
						// TODO - catch unlikely key conflict to existing user
						if ( $uniqueid = $this->store_session_key( $obj->personnel_id ) )
						{
							setcookie( 'sessid', $uniqueid, time()+60*60*24*30 );
							return true;
						} else throw new NZCFExceptionDBError(self::$mysqli->error);
					} else throw new NZCFExceptionInsufficientPermissions('Unknown username or password');
				} else throw new NZCFExceptionInsufficientPermissions('Unknown username or password');
			}
			else throw new NZCFExceptionDBError(self::$mysqli->error);
			return false;
		}
		
		public function generate_session_key($bytes=32){ return bin2hex(openssl_random_pseudo_bytes($bytes)); }
		public function store_session_key( $personnel_id, $session_code=null, $user_agent=null, $ip_address=null )
		{
			if( is_null($session_code) )
				$session_code = $this->generate_session_key();
			if( is_null($user_agent) )
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if( is_null($ip_address) )
				$ip_address = $_SERVER['REMOTE_ADDR'];
				
			$query = "
				INSERT INTO 
					`user_session` 
				(
					`personnel_id`, 
					`session_code`, 
					`user_agent`, 
					`ip_address` 
				) VALUES ( 
					".(int)$personnel_id.", 
					'".self::$mysqli->real_escape_string($session_code)."', 
					'".self::$mysqli->real_escape_string($user_agent)."', 
					".ip2long($ip_address)." 
				);";
			if ($result = self::$mysqli->query($query))
				return $session_code;
			else throw new NZCFExceptionDBError(self::$mysqli->error);
		}
		
		public function logout( $sessid=null )
		{
			if( is_null($sessid) )
				$sessid = $_COOKIE['sessid'];
				
			$query = "DELETE FROM `user_session` WHERE `session_code` =  '".self::$mysqli->real_escape_string($sessid)."'";
			// Only allow non user editors to log out their own user. People with this permission can log out anyone else
			if($this->user_has_permission( NZCF_PERMISSION_PERSONNEL_EDIT ))
				$query .= " AND `personnel_id` = ".(int)$this->currentuser;
			$query .= " LIMIT 1;";

			if ($result = self::$mysqli->query($query))	
				self::log_action( 'user_session', $query, 0 );
			else throw new NZCFExceptionDBError(self::$mysqli->error);
			return true;
		}
		
		
		public function set_activity( $activity_id, $startdate, $enddate, $title, $location_id, $personnel_id, $twoic_personnel_id, $activity_type_id, $dress_code, $attendees, $cost )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_ACTIVITIES_EDIT, $activity_id ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");

			if( !strtotime($startdate) )
				throw new NZCFExceptionBadData('Invalid startdate');
			if( !strtotime($enddate) )
				throw new NZCFExceptionBadData('Invalid enddate');
			if( $dress_code != NZCF_DRESS_CODE_BLUES && $dress_code != NZCF_DRESS_CODE_DPM && $dress_code != NZCF_DRESS_CODE_BLUES_AND_DPM && $dress_code != NZCF_DRESS_CODE_MUFTI )
				throw new NZCFExceptionBadData('Unknown dress code value');

			$officers = $this->get_personnel(null,'ASC',NZCF_USER_GROUP_OFFICERS);
			$isofficer = false;
			foreach( $officers as $officer )
			{
				if( $officer->personnel_id == $personnel_id )
				{
					$isofficer = true;
					break;
				}
			}
			if( !$isofficer )
				throw new NZCFExceptionBadData('OIC needs to be an officer');
			$isofficer = false;
			foreach( $officers as $officer )
			{
				if( $officer->personnel_id == $twoic_personnel_id )
				{
					$isofficer = true;
					break;
				}
			}
			// Allow 2IC to be an emergency contact too.
			if( !$isofficer )
			{
				$officers = $this->get_personnel(null,'ASC',NZCF_USER_LEVEL_EMRG_CONTACT);
				foreach( $officers as $officer )
				{
					if( $officer->personnel_id == $personnel_id )
					{
						$isofficer = true;
						break;
					}
				}
			}
			if( !$isofficer )
				throw new NZCFExceptionBadData('Alternate OIC needs to be an officer or emergency contact');
			
			if( !(int)$activity_id )
			{
				$query = "
					INSERT INTO `activity` (
						`startdate`,
						`enddate`,
						`personnel_id`,
						`2ic_personnel_id`,
						`title`,
						`location_id`,
						`activity_type_id`,
						`dress_code`,
						`cost`
					) VALUES ( 
						'".date("Y-m-d H:i",strtotime($startdate))."',
						'".date("Y-m-d H:i",strtotime($enddate))."',
						".(int)$personnel_id.",
						".(int)$twoic_personnel_id.",
						'".self::$mysqli->real_escape_string($title)."', 
						".(int)$location_id.",
						".(int)$activity_type_id.",
						".(int)$dress_code.",
						".(float)$cost."
					);";
				if ($result = self::$mysqli->query($query))
				{
					$activity_id = self::$mysqli->insert_id;
					self::log_action( 'activity', $query, $activity_id );
					$attendees = explode(',', $attendees);
					foreach($attendees as $personnel_id)
						if( $personnel_id )
						{
							self::$mysqli->query("INSERT INTO `activity_register` (`activity_id`, `personnel_id`) VALUES (".(int)$activity_id.", ".(int)$personnel_id.");");
							if( (float)$cost )
								self::$mysqli->query("
									INSERT INTO `payment` (
										`personnel_id`, 
										`amount`, 
										`reference`,
										`payment_type`,
										`related_to_id`,
										`created_by`
									) VALUES (
										".(int)$personnel_id.",
										".(float)$cost.",
										'".self::$mysqli->real_escape_string($title)."',
										".NZCF_PAYMENT_TYPE_INVOICE_ACTIVITY_FEE." 
										".(int)$activity_id.", 
										".$this->currentuser."
									);");
						}
					return $activity_id;
				}
				else throw new NZCFExceptionDBError(self::$mysqli->error);
			} else {
				$query = "
					UPDATE `activity` SET 
						`startdate` = '".date("Y-m-d H:i",strtotime($startdate))."',
						`enddate` = '".date("Y-m-d H:i",strtotime($enddate))."',
						`personnel_id` = ".(int)$personnel_id.",
						`2ic_personnel_id` = ".(int)$twoic_personnel_id.",
						`title` = '".self::$mysqli->real_escape_string($title)."', 
						`location_id` = ".(int)$location_id.",
						`activity_type_id` = ".(int)$activity_type_id.",
						`dress_code` = ".(int)$dress_code.",
						`cost` = ".(float)$cost."
					WHERE `activity_id` = ".(int)$activity_id."
					LIMIT 1;";
				if ($result = self::$mysqli->query($query))
				{
					self::log_action( 'activity', $query, (int)$activity_id );
					$attendees = explode(",", $attendees);
					
					// Find out if our attendees have changed
					$activitydetails = self::get_activity($activity_id);
					
					foreach($attendees as $personnel_id)
					{
						// We didn't previously know about this person, so let's add them
						if( array_search( $personnel_id, $activitydetails[0]->attendees ) === false && $personnel_id)
						{
							// Attendance register
							if( !self::$mysqli->query("INSERT INTO `activity_register` (`activity_id`, `personnel_id`) VALUES (".(int)$activity_id.", ".(int)$personnel_id.");") )
								throw new NZCFExceptionDBError(self::$mysqli->error);
							// And an invoice, if necessary
							if( (float)$cost )
							{
								echo "
									INSERT INTO `payment` (
										`personnel_id`, 
										`amount`, 
										`reference`,
										`payment_type`,
										`related_to_id`,
										`created_by`
									) VALUES (
										".(int)$personnel_id.",
										".(float)$cost.",
										'".self::$mysqli->real_escape_string($title)."',
										".NZCF_PAYMENT_TYPE_INVOICE_ACTIVITY_FEE." 
										".(int)$activity_id.", 
										".$this->currentuser."
									);";
								if( !self::$mysqli->query("
									INSERT INTO `payment` (
										`personnel_id`, 
										`amount`, 
										`reference`,
										`payment_type`,
										`related_to_id`,
										`created_by`
									) VALUES (
										".(int)$personnel_id.",
										".(float)$cost.",
										'".self::$mysqli->real_escape_string($title)."',
										".NZCF_PAYMENT_TYPE_INVOICE_ACTIVITY_FEE.", 
										".(int)$activity_id.", 
										".$this->currentuser."
									);") )
									throw new NZCFExceptionDBError(self::$mysqli->error);
							}
						}
					}
					// Now look for people who were attending, who now aren't.
					foreach($activitydetails[0]->attendees as $personnel_id)
					{
						// We can't find the attendee in the new list, so delete them
						if( array_search( $personnel_id, $attendees ) === false && $personnel_id)
						{
							// Attendance register
							if( !self::$mysqli->query("DELETE FROM `activity_register` WHERE `activity_id` = ".(int)$activity_id." AND `personnel_id` = ".(int)$personnel_id." LIMIT 1;") )
								throw new NZCFExceptionDBError(self::$mysqli->error);
							// And delete the invoice. Will make them show up as overpayment if they've paid anything. This is deliberate
							if( !self::$mysqli->query("DELETE FROM `payment` WHERE `related_to_id` = ".(int)$activity_id." AND `personnel_id` = ".(int)$personnel_id." AND `payment_type` = ".NZCF_PAYMENT_TYPE_INVOICE_ACTIVITY_FEE." LIMIT 1;") )
								throw new NZCFExceptionDBError(self::$mysqli->error);
						}
					}
					
					return (int)$activity_id;
				}
				else throw new NZCFExceptionDBError(self::$mysqli->error);
			
			}
		}

		public function set_activity_attendance( $activity_id, $register )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_ACTIVITIES_EDIT ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to view this page");

			if( !is_array($register) )
				throw new NZCFExceptionBadData('Invalid registration details');

			if( !(int)$activity_id )
				throw new NZCFExceptionBadData('Invalid activity identifier');
			
			foreach( $register as $key => $value )
			{
				$personnel_id = (int)$value['personnel_id'];
				$presence = $value['attendance'];
				if( $presence == '' )
					$presence = 'NULL';
				$note = $value['note'];
				$updatenote = strlen(trim($note));
				//$amount_paid = (float)$value['amount_paid'];
				
				if( $presence != NZCF_ATTENDANCE_PRESENT && $presence != NZCF_ATTENDANCE_ON_LEAVE && $presence != NZCF_ATTENDANCE_ABSENT_WITHOUT_LEAVE )
					throw new NZCFExceptionBadData('Unknown presence value');

				$query = "
					INSERT INTO `activity_register` (
						`personnel_id`, 
						`activity_id`, 
						`presence`
						".($updatenote?', `note`':'')."
					) VALUES ( 
						".(int)$personnel_id.", 
						".(int)$activity_id.", 
						".$presence.	
						($updatenote?", '".self::$mysqli->real_escape_string($note)."'":'')."
					) 
					ON DUPLICATE KEY UPDATE 
						`presence` = ".$presence.
						($updatenote?", `note` = '".self::$mysqli->real_escape_string($note)."'":'').";";
				
				if ($result = self::$mysqli->query($query))
					self::log_action( 'activity_register', $query, $activity_id );
				else throw new NZCFExceptionDBError(self::$mysqli->error);
			}
			return $activity_id;
		}

		public function set_activity_type( $activity_type_id, $type, $status=null )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_ACTIVITY_TYPE_EDIT ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to edit this activity_type");
			if( !strlen(trim($type)) )
				throw new NZCFExceptionBadData('Invalid activity type');

			if( !$activity_type_id )
			{
				$query = 'INSERT INTO `activity_type` (`type`, `nzcf_status` ) VALUES ( "'.self::$mysqli->real_escape_string($type).'", '.(is_null($status)?NZCF_ACTIVITY_RECOGNISED:(int)$status).' );';
				if ($result = self::$mysqli->query($query))
				{
					$activity_type_id = self::$mysqli->insert_id;
					self::log_action( 'activity_type', $query, $activity_type_id );
					return $activity_type_id;
				} else 
					throw new NZCFExceptionDBError(self::$mysqli->error);
			} else {
				$query = 'UPDATE `activity_type` SET `type` = "'.self::$mysqli->real_escape_string($type).'"'.(is_null($status)?'':',`nzcf_status` = '.(int)$status).' WHERE activity_type_id = '.(int)$activity_type_id.' LIMIT 1;';

				if ($result = self::$mysqli->query($query))
				{
					self::log_action( 'activity_type', $query, $activity_type_id );
					return $activity_type_id;
				} else
					throw new NZCFExceptionDBError(self::$mysqli->error);
				
			}
			return false;
		}
		
		public function set_attendance_register( $personnel_id, $date, $presence, $comment=null )
		{
			if( !(int)$personnel_id ) 
				throw new NZCFExceptionBadData('Invalid personnel ID');
			if( !strtotime($date) )
				throw new NZCFExceptionBadData('Invalid date');
			if( trim($presence) == "" )
			{
				$query = "DELETE FROM `attendance_register` WHERE `personnel_id` = ".(int)$personnel_id." AND `date` = '".date("Y-m-d",strtotime($date))."';";
				if ($result = self::$mysqli->query($query))
					self::log_action( 'attendance_register', $query, $personnel_id );
				else
					throw new NZCFExceptionDBError(self::$mysqli->error);
				return true;
			}
			if( $presence != NZCF_ATTENDANCE_PRESENT && $presence != NZCF_ATTENDANCE_ON_LEAVE && $presence != NZCF_ATTENDANCE_ABSENT_WITHOUT_LEAVE )
				throw new NZCFExceptionBadData('Unknown presence value');

			$query = "INSERT INTO `attendance_register` (`personnel_id`, `date`, `presence`".(is_null($comment)?'':', `comment`').") VALUES ( ".(int)$personnel_id.", '".date("Y-m-d",strtotime($date))."', ".$presence.(is_null($comment)?'':', "'.self::$mysqli->real_escape_string($comment).'"').") ON DUPLICATE KEY UPDATE `presence` = VALUES(`presence`)".(is_null($comment)?'':', `comment` = VALUES(`comment`)');
			if ($result = self::$mysqli->query($query))
				self::log_action( 'attendance_register', $query, $personnel_id );
			else
				throw new NZCFExceptionDBError(self::$mysqli->error);
			return true;
		}
		
		public function set_location( $location_id, $name, $address )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_LOCATIONS_EDIT ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to edit this location");
			if( !strlen(trim($name)) )
				throw new NZCFExceptionBadData('Invalid name');

			if( !$location_id )
			{
				$query = 'INSERT INTO `location` (`name`, `address` ) VALUES ( "'.self::$mysqli->real_escape_string($name).'", "'.self::$mysqli->real_escape_string($address).'" );';
				if ($result = self::$mysqli->query($query))
				{
					$location_id = self::$mysqli->insert_id;
					self::log_action( 'location', $query, $location_id );
					return $location_id;
				} else 
					throw new NZCFExceptionDBError(self::$mysqli->error);
			} else {
				$query = 'UPDATE `location` SET `name` = "'.self::$mysqli->real_escape_string($name).'", `address` = "'.self::$mysqli->real_escape_string($address).'" WHERE location_id = '.(int)$location_id.' LIMIT 1;';

				if ($result = self::$mysqli->query($query))
				{
					self::log_action( 'location', $query, $location_id );
					return $location_id;
				} else
					throw new NZCFExceptionDBError(self::$mysqli->error);
				
			}
			return false;
		}
		
		public function set_next_of_kin( $nokid, $personnel_id, $firstname, $lastname, $relationship, $email, $mobile, $home, $address1, $address2, $city, $postcode, $sortorder=0 )
		{
			if(!$this->user_has_permission( NZCF_PERMISSION_PERSONNEL_EDIT, $personnel_id ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to edit this user");
			
			if( !strlen(trim($firstname)) )
				throw new NZCFExceptionBadData('Invalid first name');
			if( !strlen(trim($lastname)) )
				throw new NZCFExceptionBadData('Invalid last name');
			if( !strlen(trim($email)) )
				throw new NZCFExceptionBadData('Invalid email');
			if( !strlen(trim($mobile)) )
				throw new NZCFExceptionBadData('Invalid mobile');
			if( !strlen(trim($address1)) )
				throw new NZCFExceptionBadData('Invalid address line 1');
			if( !strlen(trim($city)) )
				throw new NZCFExceptionBadData('Invalid city');

			if( !$nokid )
			{
				$query = '
					INSERT INTO `next_of_kin` (
						`personnel_id`, 
						`firstname`, 
						`lastname`, 
						`email`, 
						`relationship`, 
						`mobile_number`, 
						`home_number`, 
						`address1`, 
						`address2`, 
						`city`, 
						`postcode`, 
						`sort_order`
					) VALUES (
						'.(int)$personnel_id.',
						"'.self::$mysqli->real_escape_string($firstname).'", 
						"'.self::$mysqli->real_escape_string($lastname).'", 
						"'.self::$mysqli->real_escape_string($email).'", 
						'.(int)$relationship.', 
						"'.self::$mysqli->real_escape_string($mobile).'", 
						"'.self::$mysqli->real_escape_string($home).'", 
						"'.self::$mysqli->real_escape_string($address1).'", 
						"'.self::$mysqli->real_escape_string($address2).'", 
						"'.self::$mysqli->real_escape_string($city).'", 
						'.(int)$postcode.', 
						'.(int)$sortorder.'
					);';
				if ($result = self::$mysqli->query($query))
				{
					$nok_id = self::$mysqli->insert_id;
					self::log_action( 'location', $query, $nok_id );
					return $nok_id;
				} else 
					throw new NZCFExceptionDBError(self::$mysqli->error);
			} else {
				$query = '
					UPDATE `next_of_kin` SET 
						`personnel_id` = '.(int)$personnel_id.',
						`firstname` = "'.self::$mysqli->real_escape_string($firstname).'", 
						`lastname` = "'.self::$mysqli->real_escape_string($lastname).'", 
						`email` = "'.self::$mysqli->real_escape_string($email).'", 
						`relationship` = '.(int)$relationship.', 
						`mobile_number` = "'.self::$mysqli->real_escape_string($mobile).'", 
						`home_number` = "'.self::$mysqli->real_escape_string($home).'", 
						`address1` = "'.self::$mysqli->real_escape_string($address1).'", 
						`address2` = "'.self::$mysqli->real_escape_string($address2).'", 
						`city` = "'.self::$mysqli->real_escape_string($city).'", 
						`postcode` = '.(int)$postcode.',
						`sort_order` = '.(int)$sortorder.'
					WHERE `kin_id` = '.(int)$nokid.'
					LIMIT 1;';
				if ($result = self::$mysqli->query($query))
				{
					self::log_action( 'location', $query, (int)$nokid );
					return $nokid;
				} else
					throw new NZCFExceptionDBError(self::$mysqli->error);
				
			}
			return false;
		}
		
		public function set_personnel( &$user )
		{
			$query = "";
			if(!$this->user_has_permission( NZCF_PERMISSION_PERSONNEL_EDIT, $user->personnel_id ))
			    throw new NZCFExceptionInsufficientPermissions("Insufficient rights to edit this user");
				
			if( !$user->personnel_id )
			{
				$query = "INSERT INTO `personnel` (`firstname`, `lastname`, `email`, `mobile_phone`, `allergies`, `medical_conditions`, `medicinal_reactions`, `dietary_requirements`, `other_notes`, `dob`, `password`, `joined_date`, `left_date`, `access_rights`, `is_female`, `enabled`, `flight`, `social_media_approved` ) VALUES ( ";
				$query .= '"'.self::$mysqli->real_escape_string($user->firstname).'", "'.self::$mysqli->real_escape_string($user->lastname).'", "'.self::$mysqli->real_escape_string($user->email).'", "'.self::$mysqli->real_escape_string($user->mobile_phone).'", "'.self::$mysqli->real_escape_string($user->allergies).'", "'.self::$mysqli->real_escape_string($user->medical_conditions).'", "'.self::$mysqli->real_escape_string($user->medicinal_reactions).'", "'.self::$mysqli->real_escape_string($user->dietary_requirements).'", "'.self::$mysqli->real_escape_string($user->other_notes).'", "'.date('Y-m-d',strtotime($user->dob)).'", ';
				$query .= '"'.self::$mysqli->real_escape_string(create_hash($user->password)).'", "'.date('Y-m-d',strtotime($user->joined_date)).'", '.(strtotime($user->left_date)?'"'.date('Y-m-d',strtotime($user->left_date)).'"':'NULL').', '.(int)$user->access_rights.', ';
				$query .= (int)$user->is_female.', '.(isset($user->enabled)&&$user->enabled==-1?-1:0).', "'.self::$mysqli->real_escape_string($user->flight).'", '.(isset($user->social_media_approved)&&$user->social_media_approved==-1?-1:0).' );';
				if ($result = self::$mysqli->query($query))
				{
					$user->personnel_id = self::$mysqli->insert_id;
					self::log_action( 'personnel', $query, $user->personnel_id );
					return true;
				} else 
					throw new NZCFExceptionDBError(self::$mysqli->error);
			} else {
				$query = 'UPDATE `personnel` SET `firstname` = "'.self::$mysqli->real_escape_string($user->firstname).'", `lastname` = "'.self::$mysqli->real_escape_string($user->lastname).'", `email` = "'.self::$mysqli->real_escape_string($user->email).'", `mobile_phone` = "'.self::$mysqli->real_escape_string($user->mobile_phone).'", `allergies` = "'.self::$mysqli->real_escape_string($user->allergies).'", `medical_conditions` = "'.self::$mysqli->real_escape_string($user->medical_conditions).'", `medicinal_reactions` = "'.self::$mysqli->real_escape_string($user->medicinal_reactions).'", `dietary_requirements` = "'.self::$mysqli->real_escape_string($user->dietary_requirements).'",  `other_notes` = "'.self::$mysqli->real_escape_string($user->other_notes).'", `dob` = "'.date('Y-m-d',strtotime($user->dob)).'", ';
				if( strlen(trim($user->password)) ) 
					 $query .= '`password` = "'.self::$mysqli->real_escape_string(create_hash($user->password)).'", ';
				$query .= '`joined_date` = "'.date('Y-m-d',strtotime($user->joined_date)).'", ';
				if( strtotime($user->left_date) )
					$query .= '`left_date` = "'.date('Y-m-d',strtotime($user->left_date)).'", ';
				else 
					$query .= '`left_date` = NULL, ';
				$query .= '`access_rights` = '.(int)$user->access_rights.', `enabled` = '.(isset($user->enabled)&&$user->enabled==-1?-1:0).', `is_female` = '.(int)$user->is_female.', `flight` = "'.self::$mysqli->real_escape_string($user->flight).'", `social_media_approved` = '.(isset($user->social_media_approved)&&$user->social_media_approved==-1?-1:0).'';
				$query .= ' WHERE personnel_id = '.(int)$user->personnel_id.' LIMIT 1;';

				if ($result = self::$mysqli->query($query))
				{
					self::log_action( 'personnel', $query, $user->personnel_id );
					return true;
				} else
					throw new NZCFExceptionDBError(self::$mysqli->error);
				
			}
			return false;
		}	
		
		public function gui_output_page_footer( $title )
		{
			echo '
		<script>
			$("thead th").button().removeClass("ui-corner-all").css({ display: "table-cell" });
			$("tbody tr:odd").not(".ui-state-highlight, .ui-state-error").addClass("evenrow");
			$("table.tablesorter").tablesorter().on("sortStart", function(){ $("tbody tr").removeClass("evenrow"); }).on("sortEnd", function(){ $("tbody tr:odd").not(".ui-state-highlight, .ui-state-error").addClass("evenrow"); });
			$("a.button.edit").button({ icons: { primary: "ui-icon-pencil" }, text: false });
			$("a.button.new").button({ icons: { primary: "ui-icon-plusthick" }, text: false });
			$("button.update").button({ icons: { primary: "ui-icon-refresh" } });
		</script>';
			if( strlen(trim($title)) )
			{
				echo '
		<footer>
			<p> Built on the ATC system code available at <a target="blank" href="https://github.com/PhilTanner/NZCF_system">https://github.com/PhilTanner/NZCF_system</a> &ndash; Version '.NZCF_VERSION.' </p>
			'.(NZCF_DEBUG?'<p style="font-size:75%;">DEBUG INFO: Logged in as user: '.$this->currentuser.' - access rights: '.$this->currentpermissions.'</p>':'').'
			'.(NZCF_DEBUG?'<!--':'').'<img src="49squadron.png" style="position:absolute; bottom: 1em; right: 1em; z-index: -1;" />'.(NZCF_DEBUG?'-->':'').'
		</footer>
		'.(NZCF_DEBUG?'<style>body { color:red; }</style>':'').'
	</body>
</html>';
			}
		}
		
		public function gui_output_page_header( $title )
		{
			if( isset($_COOKIE['sessid']) )
			{
				try {
					self::become_user_from_session($_COOKIE['sessid']);
				} catch (NZCFExceptionInvalidUserSession $e) {
					if(substr($_SERVER['SCRIPT_NAME'], -9, 9) != "login.php" )
						header('Location: login.php', true, 302);
				}
			} else 
				if(substr($_SERVER['SCRIPT_NAME'], -9, 9) != "login.php" )
					header('Location: login.php', true, 302);

			if(!$this->currentuser && substr($_SERVER['SCRIPT_NAME'], -9, 9) != "login.php" )
				header('Location: login.php', true, 302);
				
			echo '<!doctype html>
<html lang="us">
	<head>
		<meta charset="utf-8">
		<title>'.(NZCF_DEBUG?'DEV':'ATC').' '.$title.'</title>
		<link href="jquery-ui-1.9.2.custom/css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet">
		<link href="nzcf.css" rel="stylesheet">
		
		<script type="text/javascript" src="jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
		<script type="text/javascript" src="jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js"></script>
		<script type="text/javascript" src="touchpunch.furf.com_jqueryui-touch.js"></script>
		<script type="text/javascript" src="jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="tablesorter/jquery.tablesorter.js"></script> 
		
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		
		<script type="text/javascript">
			$.tablesorter.addParser({
				// set a unique id
				id: "NZCF_SETTING_DATETIME_OUTPUT",
				is: function(s) {
					// return false so this parser is not auto detected
					return  /^\d{1,2}[\ ][A-Za-z]{3}[\,][\ ]\d{2}[\:]\d{2}$/.test(s);;
				},
				format: function(s) {
					// format your data for normalization
					return Date.parse(s);
				},
				// set type, either numeric or text
				type: "numeric"
			});
			$.tablesorter.addParser({
				// set a unique id
				id: "NZCF_SETTING_DATE_OUTPUT",
				is: function(s) {
					// return false so this parser is not auto detected
					return  /^\d{1,2}[\ ][A-Za-z]{3}$/.test(s);;
				},
				format: function(s) {
					// format your data for normalization
					return Date.parse(s);
				},
				// set type, either numeric or text
				type: "numeric"
			});
	
		
			$(function(){
				//$(".navoptions ul li a").button().addClass("ui-state-disabled");
				$(".navoptions ul li a.home").button({ icons: { primary: "ui-icon-home" } })'.($this->currentuser?'.removeClass("ui-state-disabled")':'').($title=='Home'?'.addClass("ui-state-active")':'').';
				$(".navoptions ul li a.personnel").button({ icons: { primary: "ui-icon-contact" } })'.($this->currentuser?'.removeClass("ui-state-disabled")':'').($title=='Personnel'?'.addClass("ui-state-active")':'').';
				$(".navoptions ul li a.attendance").button({ icons: { primary: "ui-icon-clipboard" } })'.($this->currentuser?'.removeClass("ui-state-disabled")':'').($title=='Attendance'?'.addClass("ui-state-active")':'').';
				$(".navoptions ul li a.activities").button({ icons: { primary: "ui-icon-image" } })'.($this->currentuser?'.removeClass("ui-state-disabled")':'').($title=='Activities'?'.addClass("ui-state-active")':'').';
				$(".navoptions ul li a.documents").button({ icons: { primary: "ui-icon-folder-open" } })'.($this->currentuser?'.removeClass("ui-state-disabled")':'').($title=='Documentation'?'.addClass("ui-state-active")':'').';
				$(".navoptions ul li a.system").button({ icons: { primary: "ui-icon-gear" } }).removeClass("ui-state-disabled")'.($title=='System'?'.addClass("ui-state-active")':'').';
				
				$(".navoptions ul li a.finance").button({ icons: { primary: "ui-icon-cart" } });
				$(".navoptions ul li a.stores").button({ icons: { primary: "ui-icon-tag" } });
				$(".navoptions ul li a.training").button({ icons: { primary: "ui-icon-calendar" } });
				
				
				$(".navoptions ul li a.logout").button({ icons: { primary: "ui-icon-unlocked" } }).removeClass("ui-state-disabled");
				$(".navoptions ul li a.login").button({ icons: { primary: "ui-icon-locked" } })'.($this->currentuser?'':'.removeClass("ui-state-disabled")').($title=='Login'?'.addClass("ui-state-active")':'').';

				$(".navoptions ul li a.training").button({ icons: { primary: "ui-icon-calendar" } }).removeClass("ui-state-disabled")'.($title=='Training'?'.addClass("ui-state-active")':'').';
			});
			
		</script>
		
	</head>
	<body>
		<div id="dialog"></div>
		<nav class="navoptions">
			<ul>
				<li> <a href="./" class="home">Home</a> </li>
				'.($this->currentuser && $this->user_has_permission(NZCF_PERMISSION_PERSONNEL_VIEW, $this->currentuser)?'<li> <a href="./personnel.php" class="personnel">Personnel</a> </li>':'').'
				'.($this->currentuser && $this->user_has_permission(NZCF_PERMISSION_ATTENDANCE_VIEW)?'<li> <a href="./attendance.php" class="attendance">Attendance</a> </li>':'').'
				'.($this->currentuser && $this->user_has_permission(NZCF_PERMISSION_ACTIVITIES_VIEW)?'<li> <a href="./activities.php" class="activities">Activities</a> </li>':'').'
				'.($this->currentuser && $this->user_has_permission(NZCF_PERMISSION_FINANCE_VIEW)?'<li> <a href="./finance.php" class="finance">Finance</a> </li>':'').'
				<!--'.($this->currentuser && $this->user_has_permission(NZCF_PERMISSION_STORES_VIEW)?'<li> <a href="./" class="stores">Stores</a> </li>':'').'-->
				'.($this->currentuser && $this->user_has_permission(NZCF_PERMISSION_TRAINING_VIEW)?'<li> <a href="./training.php" class="training">Training</a> </li>':'').'
				'.($this->currentuser && $this->user_has_permission(NZCF_USER_LEVEL_ADJUTANT)?'<li> <a href="./documents.php" class="documents">Documentation</a> </li>':'').'
				'.($this->currentuser && $this->user_has_permission(NZCF_PERMISSION_SYSTEM_VIEW)?'<li> <a href="./system.php" class="system">System</a> </li>':'').'
				
				'.($this->currentuser?'<li> <a href="./logout.php" class="logout">Logout</a> </li>':'<li> <a href="./login.php" class="login">Login</a> </li>').'
				
				<li>
						<select name="unitselector" id="unitselector">';
			$units = $this->get_units_for_personnel( $this->currentuser );
			foreach( $units as $unit )
				echo '		<option value="'.htmlentities($unit->unit_id).'">'.htmlentities($unit->name).'</option>';
			echo '
						</select>
				</li>
			</ul>
		</nav>
		<h1> '.(NZCF_DEBUG?'<span style="color:Red;">DEV</span>':'ATC').' - '.$title.' </h1>
';
		}
		
		public function user_has_permission( $permission, $target=null )
		{
			if( is_null($target) )
			{				
				if( ($this->currentpermissions & $permission) == $permission ) 
					return true;
			} else {
								
				// If we have the global permission, we're good anyway
				if( ($this->currentpermissions & $permission) == $permission ) 
					return true;
				switch($permission)
				{
					case NZCF_PERMISSION_ATTENDANCE_VIEW:
					case NZCF_PERMISSION_PERSONNEL_VIEW:
					case NZCF_PERMISSION_PERSONNEL_EDIT:
						// If we're wanting to view/edit our own user, we're all good.
						if( $target == $this->currentuser )
							return true;
						break;
					case NZCF_PERMISSION_ACTIVITIES_EDIT:
						// If we're the OIC, we should be able to edit it.
						$query = 'SELECT `personnel_id` FROM `activity` WHERE `activity_id` = '.(int)$target.' LIMIT 1;';
						
						if ($result = self::$mysqli->query($query))
						{
							while ( $obj = $result->fetch_object() )
								// Make sure we're the OIC, and that we're logged in (otherwise anon users can edit new/misconfigured activities)
								if( $obj->personnel_id == $this->currentuser && $this->currentuser )
									return true;
						} else
							throw new NZCFExceptionDBError(self::$mysqli->error);
						
						break;
					default:
						return 0;
				}
			}
			return 0;
		}
		
	}
	
	

	/*
	 * Password Hashing With PBKDF2 (http://crackstation.net/hashing-security.htm).
	 * Copyright (c) 2013, Taylor Hornby
	 * All rights reserved.
	 *
	 * Redistribution and use in source and binary forms, with or without 
	 * modification, are permitted provided that the following conditions are met:
	 *
	 * 1. Redistributions of source code must retain the above copyright notice, 
	 * this list of conditions and the following disclaimer.
	 *
	 * 2. Redistributions in binary form must reproduce the above copyright notice,
	 * this list of conditions and the following disclaimer in the documentation 
	 * and/or other materials provided with the distribution.
	 *
	 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
	 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
	 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
	 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE 
	 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
	 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
	 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
	 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
	 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
	 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
	 * POSSIBILITY OF SUCH DAMAGE.
	 */
	
	// These constants may be changed without breaking existing hashes.
	define("PBKDF2_HASH_ALGORITHM", "sha256");
	define("PBKDF2_ITERATIONS", 1000);
	define("PBKDF2_SALT_BYTE_SIZE", 24);
	define("PBKDF2_HASH_BYTE_SIZE", 24);
	
	define("HASH_SECTIONS", 4);
	define("HASH_ALGORITHM_INDEX", 0);
	define("HASH_ITERATION_INDEX", 1);
	define("HASH_SALT_INDEX", 2);
	define("HASH_PBKDF2_INDEX", 3);
	
	function create_hash($password)
	{
		// format: algorithm:iterations:salt:hash
		$salt = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM));
		return PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" .  $salt . ":" .
			base64_encode(pbkdf2(
				PBKDF2_HASH_ALGORITHM,
				$password,
				$salt,
				PBKDF2_ITERATIONS,
				PBKDF2_HASH_BYTE_SIZE,
				true
			));
	}
	
	function validate_password($password, $correct_hash)
	{
		$params = explode(":", $correct_hash);
		if(count($params) < HASH_SECTIONS)
		   return false;
		$pbkdf2 = base64_decode($params[HASH_PBKDF2_INDEX]);
		return slow_equals(
			$pbkdf2,
			pbkdf2(
				$params[HASH_ALGORITHM_INDEX],
				$password,
				$params[HASH_SALT_INDEX],
				(int)$params[HASH_ITERATION_INDEX],
				strlen($pbkdf2),
				true
			)
		);
	}
	
	// Compares two strings $a and $b in length-constant time.
	function slow_equals($a, $b)
	{
		$diff = strlen($a) ^ strlen($b);
		for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
		{
			$diff |= ord($a[$i]) ^ ord($b[$i]);
		}
		return $diff === 0;
	}
	
	/*
	 * PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
	 * $algorithm - The hash algorithm to use. Recommended: SHA256
	 * $password - The password.
	 * $salt - A salt that is unique to the password.
	 * $count - Iteration count. Higher is better, but slower. Recommended: At least 1000.
	 * $key_length - The length of the derived key in bytes.
	 * $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
	 * Returns: A $key_length-byte key derived from the password and salt.
	 *
	 * Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
	 *
	 * This implementation of PBKDF2 was originally created by https://defuse.ca
	 * With improvements by http://www.variations-of-shadow.com
	 */
	function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
	{
		$algorithm = strtolower($algorithm);
		if(!in_array($algorithm, hash_algos(), true))
			trigger_error('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);
		if($count <= 0 || $key_length <= 0)
			trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);
	
		if (function_exists("hash_pbkdf2")) {
			// The output length is in NIBBLES (4-bits) if $raw_output is false!
			if (!$raw_output) {
				$key_length = $key_length * 2;
			}
			return hash_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output);
		}
	
		$hash_length = strlen(hash($algorithm, "", true));
		$block_count = ceil($key_length / $hash_length);
	
		$output = "";
		for($i = 1; $i <= $block_count; $i++) {
			// $i encoded as 4 bytes, big endian.
			$last = $salt . pack("N", $i);
			// first iteration
			$last = $xorsum = hash_hmac($algorithm, $last, $password, true);
			// perform the other $count - 1 iterations
			for ($j = 1; $j < $count; $j++) {
				$xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
			}
			$output .= $xorsum;
		}
	
		if($raw_output)
			return substr($output, 0, $key_length);
		else
			return bin2hex(substr($output, 0, $key_length));
	}	
?>
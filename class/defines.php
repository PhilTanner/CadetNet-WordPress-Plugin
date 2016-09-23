<?php
	/*			
		Plugin Name: NZCF Cadet Net 
		Plugin URI:  https://github.com/PhilTanner/CadetNet-WordPress-Plugin.git
        
        Copyright (C) 2016 Phil Tanner

    	This program is free software: you can redistribute it and/or modify
    	it under the terms of the GNU General Public License as published by
    	the Free Software Foundation, either version 3 of the License, or
    	(at your option) any later version.

    	This program is distributed in the hope that it will be useful,
    	but WITHOUT ANY WARRANTY; without even the implied warranty of
    	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    	GNU General Public License for more details.

    	You should have received a copy of the GNU General Public License
    	along with this program.  If not, see <http://www.gnu.org/licenses/>.
	*/
	
	// Stop direct URL access
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );	
	
	
	// Custom exceptions for error handling
	class WPNZCFCNException extends Exception {
		/**
		 * Pretty prints the exception for the user to see
		 */
		public function toString() {
			$t = $this->getTrace();
			if (empty($t)) {
				$class = "Unknown";
			}
			else {
				$first = reset($t);
				$class = $first["class"];
			}
			return htmlentities(sprintf("EXCEPTION:%s",  $this->getMessage()));
		}
	}
	class WPNZCFCNExceptionBadData				extends WPNZCFCNException {}
	class WPNZCFCNExceptionDBConn				extends WPNZCFCNException {}
	class WPNZCFCNExceptionDBError				extends WPNZCFCNExceptionDBConn {}
	class WPNZCFCNExceptionInsufficientPermissions		extends WPNZCFCNException {}
	class WPNZCFCNExceptionInvalidUserSession		extends WPNZCFCNExceptionInsufficientPermissions {}
	class WPNZCFCNExceptionWordPressInteraction		extends WPNZCFCNException {}
	class WPNZCFCNExceptionWordPressInteractionInstall	extends WPNZCFCNExceptionWordPressInteraction {}
	class WPNZCFCNExceptionWordPressInteractionCreateRole	extends WPNZCFCNExceptionWordPressInteractionInstall {}
	
	if ( is_admin() ) {
		// We are in admin mode
		require_once( dirname(__FILE__).'/../admin/admin.php' );
	}
	
	if( !function_exists('current_user_has_role') ){
		function current_user_has_role( $role ){
			$current_user = new WP_User(wp_get_current_user()->ID);
			$user_roles = $current_user->roles;
			return in_array( $role, $user_roles );
		}
	}
	
	/* Define our CONSTANT variables */
	
	// New Zealand Cadet Forces, as a bitmask 
	define( 'WPNZCFCN_CADETS_ATC',				1	);
	define( 'WPNZCFCN_CADETS_NZCC',				1 << 1	);
	define( 'WPNZCFCN_CADETS_SCC',				1 << 2	);
	// These need to continue, as they're used in rank calculations
	define( 'WPNZCFCN_REGULAR_FORCE_NAVY',			1 << 3	);
	define( 'WPNZCFCN_REGULAR_FORCE_ARMY',			1 << 4	);
	define( 'WPNZCFCN_REGULAR_FORCE_RNZAF',			1 << 5	);
	// These also need to continue, as used in rank calculations
	define( 'WPNZCFCN_RANK_OFFICER',			1 << 6	);
	define( 'WPNZCFCN_RANK_CADET',				1 << 7	);
	define( 'WPNZCFCN_RANK_CIVILIAN',			1 << 8	);
	
	//define( 'WPNZCFCN_CADETS_CIVILIAN',			1 << 3	);
	//define( 'WPNZCFCN_CADETS_REGULAR_SERVICE',		1 << 4	);
	
	// New Zealand Cadet Forces Areas, as a bitmask
	define( 'WPNZCFCN_AREA_NORTHERN',			1	);
	define( 'WPNZCFCN_AREA_CENTRAL',			1 << 1	);
	define( 'WPNZCFCN_AREA_SOUTHERN',			1 << 2	);
	
	// Traing groups
	define( 'WPNZCFCN_PERSONNEL_GROUP_OFFICER',		1 	);
	define( 'WPNZCFCN_PERSONNEL_GROUP_UNDER_OFFICER',	1 << 1	);
	define( 'WPNZCFCN_PERSONNEL_GROUP_NCO',			1 << 2	);
	define( 'WPNZCFCN_PERSONNEL_GROUP_CADET',		1 << 3	);
	define( 'WPNZCFCN_PERSONNEL_GROUP_CIVILIAN',		1 << 4	);
	define( 'WPNZCFCN_PERSONNEL_GROUP_LEVEL_JUNIOR',	1 << 5	);
	define( 'WPNZCFCN_PERSONNEL_GROUP_LEVEL_SENIOR',	1 << 6	);
	
	// Days stored as BitMask, so we can have multiple days per week stored
	define( 'WPNZCFCN_DAY_SUNDAY',				1 	);
	define( 'WPNZCFCN_DAY_MONDAY',				1 << 1	);
	define( 'WPNZCFCN_DAY_TUESDAY',				1 << 2	);
	define( 'WPNZCFCN_DAY_WEDNESDAY',			1 << 3	);
	define( 'WPNZCFCN_DAY_THURSDAY',			1 << 4	);
	define( 'WPNZCFCN_DAY_FRIDAY',				1 << 5	);
	define( 'WPNZCFCN_DAY_SATURDAY',			1 << 6	);
	
	// Statuses
	define( 'WPNZCFCN_STATUS_DISBANDED',			 -2	);
	define( 'WPNZCFCN_STATUS_RECESS',			 -1	);
	define( 'WPNZCFCN_STATUS_UNDEFINED',			  0	);
	define( 'WPNZCFCN_STATUS_ACTIVE',			  1	);
	define( 'WPNZCFCN_STATUS_PENDING',			  2	);
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
	
	define('WP_DEBUG', true); 
	
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
			return sprintf("EXCEPTION:%s", $class, self::$mysqli->real_escape_string($this->getMessage()));
		}
	}
	class WPNZCFCNExceptionBadData							extends WPNZCFCNException {}
	class WPNZCFCNExceptionDBConn							extends WPNZCFCNException {}
	class WPNZCFCNExceptionDBError							extends WPNZCFCNExceptionDBConn {}
	class WPNZCFCNExceptionInsufficientPermissions			extends WPNZCFCNException {}
	class WPNZCFCNExceptionInvalidUserSession				extends WPNZCFCNExceptionInsufficientPermissions {}
	class WPNZCFCNExceptionWordPressInteraction				extends WPNZCFCNException {}
	class WPNZCFCNExceptionWordPressInteractionInstall		extends WPNZCFCNExceptionWordPressInteraction {}
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
	define( 'WPNZCFCN_CADETS_ATC',			1 );
	define( 'WPNZCFCN_CADETS_CORPS',			1 << 1 );
	define( 'WPNZCFCN_CADETS_SEA',			1 << 2 );
	
	// New Zealand Cadet Forces Areas, as a bitmask
	define( 'WPNZCFCN_AREA_NORTHERN',			1 );
	define( 'WPNZCFCN_AREA_CENTRAL',			1 << 1 );
	define( 'WPNZCFCN_AREA_SOUTHERN',			1 << 2 );
	
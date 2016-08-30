<?php
	
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );	
	
	
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
	
	// New Zealand Cadet Forces, as a bitmask 
	define( 'WPNZCFCN_CADETS_ATC',			1 );
	define( 'WPNZCFCN_CADETS_CORPS',			1 << 1 );
	define( 'WPNZCFCN_CADETS_SEA',			1 << 2 );
	
	// New Zealand Cadet Forces Areas, as a bitmask
	define( 'WPNZCFCN_AREA_NORTHERN',			1 );
	define( 'WPNZCFCN_AREA_CENTRAL',			1 << 1 );
	define( 'WPNZCFCN_AREA_SOUTHERN',			1 << 2 );
	
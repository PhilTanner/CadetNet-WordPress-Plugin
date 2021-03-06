<?php
	/*			
		Plugin Name: NZCF Unit Administration
		Plugin URI:  https://github.com/PhilTanner/nzcf-unit-administration.git
		Description: WordPress NZCF Unit Administration Plugin
		Version:     0.01
		Author:      Phil Tanner
		Author URI:  https://github.com/PhilTanner
		License:     GPL3
		License URI: http://www.gnu.org/licenses/gpl.html
		Domain Path: /languages
		Text Domain: nzcf-unit-administration
        
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

	class WPNZCFUAException extends Exception {
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
	class WPNZCFUAExceptionBadData							extends WPNZCFUAException {}
	class WPNZCFUAExceptionDBConn							extends WPNZCFUAException {}
	class WPNZCFUAExceptionDBError							extends WPNZCFUAExceptionDBConn {}
	class WPNZCFUAExceptionInsufficientPermissions			extends WPNZCFUAException {}
	class WPNZCFUAExceptionInvalidUserSession				extends WPNZCFUAExceptionInsufficientPermissions {}
	class WPNZCFUAExceptionWordPressInteraction				extends WPNZCFUAException {}
	class WPNZCFUAExceptionWordPressInteractionInstall		extends WPNZCFUAExceptionWordPressInteraction {}
	class WPNZCFUAExceptionWordPressInteractionCreateRole	extends WPNZCFUAExceptionWordPressInteractionInstall {}

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );	
	
	$version = "0.01";
	$db_version = "0.03";
	
	add_option( "wpnzcfua_version", $version );
	add_option( "wpnzcfua_db_version", $db_version );
	
	if ( is_admin() ) {
    	// We are in admin mode
     	require_once( dirname(__FILE__).'/admin/nzcfadmin_admin.php' );
	}	
	
	require_once( dirname(__FILE__).'/defines.php' );
	
	// Function to be called when this application is installed:
	register_activation_hook( __FILE__, 'nzcfadmin_install' );
	// Create our DB tables to hold our data
	register_activation_hook( __FILE__, 'nzcfadmin_db_install' );
	//Populate our data
	register_activation_hook( __FILE__, 'nzcfadmin_db_init' );
	
	// Function to be called when this application is uninstalled:
	register_uninstall_hook( __FILE__, 'nzcfadmin_uninstall' );
	// Function to be alled when this application is loaded
	add_action('init', 'register_nzcfadmin');
	
	
	// JSON API calls
	// Taken from: http://wordpress.stackexchange.com/questions/217103/how-to-use-wordpress-php-functions-in-angularjs-partials-files/218912#218912
	/*
	So we're going to turn a front-end request for:
	example.com/api/angular/partial/custom
	into something we can use internally as:
	example.com/index.php?__api_angular=1&partial=custom
	*/
	if ( ! class_exists( 'AngularEndpoint' ) ):

	    class AngularEndpoint {
 	       const ENDPOINT_QUERY_NAME  = 'api/wpnzcfua/partial';
  	      const ENDPOINT_QUERY_PARAM = '__api_angular';

	        // WordPress hooks
	        public function init() {
	            add_filter( 'query_vars', array ( $this, 'add_query_vars' ), 0 );
	            add_action( 'parse_request', array ( $this, 'sniff_requests' ), 0 );
	            add_action( 'init', array ( $this, 'add_endpoint' ), 0 );
	        }

	        // Add public query vars
	        public function add_query_vars( $vars ) {

	            // add all the things we know we'll use
	            $vars[] = static::ENDPOINT_QUERY_PARAM;
 	           $vars[] = 'partial';
  	          $vars[] = 'filter';
   	         $vars[] = 'type';

	            return $vars;
        	}

	        // Add API Endpoint
	        public function add_endpoint() {
				//	http://localhost:8080/wordpress/api/wpnzcfua/partial/ping
 	           //add_rewrite_rule( '.*' . static::ENDPOINT_QUERY_NAME . '/partial/([^/]*)/?', '/wordpress/index.php?' . static::ENDPOINT_QUERY_PARAM . '=1&partial=$matches[1]', 'top' );
 				add_rewrite_rule( '^/?api/wpnzcfua/partial/([^/]*)/?', '/wordpress/wp-content/plugins/nzcf-unit-administration/eoi/eoi.php', 'top' );
 				
 				add_rewrite_rule( '^/?wordpress/api/wpnzcfua/eoi/?', '/wordpress/wp-content/plugins/nzcf-unit-administration/eoi/eoi.php', 'top' );
 				

	            //////////////////////////////////
 	           flush_rewrite_rules( false ); //// REMOVE THIS WHEN DONE
  	          //////////////////////////////////
   	     }

	        // Sniff Requests
	        public function sniff_requests( $wp_query ) {
	            global $wp;

	            if ( isset(
 	               $wp->query_vars[ static::ENDPOINT_QUERY_PARAM ],
  	              $wp->query_vars[ 'partial' ] ) ) {
   	             $this->handle_partial_request(); // handle it
    	        }
     	   }

	        // Handle Requests
	        protected function handle_partial_request() {
 	           global $wp;

	            $partial_requested = $wp->query_vars[ 'partial' ];

	            switch ( $partial_requested ) {

	                // example.com/api/angular/partial/ping
 	               case 'ping':
  	                  wp_send_json_success( array (
   	                     'message' => 'Enjoy your partial', 'partial' => $partial_requested,
    	                ) );
     	               break;

	                // example.com/api/angular/partial/custom
 	               case 'custom':
  	                  add_filter( 'template_include', function( $original_template ) {
   	                     return __DIR__ . '/custom.php';
    	                } );
     	               break;

	                // example.com/api/angular/partial/search
 	               case 'search':
  	                  add_filter( 'template_include', function( $original_template ) {
      	                  return get_template_directory() . '/search.php';
   	                 } );
    	                break;
    
     	           default:
       	             wp_send_json_error( array ( 'message' => 'Invalid Request' ) );
        	    }
        	}
    	}

	endif; // AngularEndpoint
	
	
	function nzcfadmin_install(){
		global $wp_roles, $version;
		
		add_site_option( "wpnzcfua_version", $version );
		
		// First off, we're going to create the role types for NZCF to allow new roles and permissions for various user types
		
		// Based off code at https://codex.wordpress.org/Function_Reference/add_role
		remove_role('nzcf-ua_cmdt');
		$result = add_role(
			'nzcf-ua_cmdt',
			_('Commandant'),
			array(
				'view_personnel_detail_global'					=> true,
				'edit_personnel_detail_global'					=> false,
				'view_personnel_detail_own_area'					=> true,
				'edit_personnel_detail_own_area'					=> false,
				'view_personnel_detail_own_unit'					=> true,
				'edit_personnel_detail_own_unit'					=> false,
				'view_personnel_detail_own_flight'					=> true,
				'edit_personnel_detail_own_flight'					=> false,
				'view_personnel_detail_own_record'					=> true,
				'edit_personnel_detail_own_record'					=> false,
				
				'view_attendance_global'					=> true,
				'edit_attendance_global'					=> false,
				'view_attendance_own_area'					=> true,
				'edit_attendance_own_area'					=> false,
				'view_attendance_own_unit'					=> true,
				'edit_attendance_own_unit'					=> false,
				
				'view_activity_global'					=> true,
				'edit_activity_global'					=> false,
				'view_activity_own_area'					=> true,
				'edit_activity_own_area'					=> false,
				'view_activity_own_unit'					=> true,
				'edit_activity_own_unit'					=> false,
				
				'view_finance_global'					=> true,
				'edit_finance_global'					=> false,
				'view_finance_own_area'					=> true,
				'edit_finance_own_area'					=> false,
				'view_finance_own_unit'					=> true,
				'edit_finance_own_unit'					=> false,
				
				'view_logistics_global'					=> true,
				'edit_logistics_global'					=> false,
				'view_logistics_own_area'					=> true,
				'edit_logistics_own_area'					=> false,
				'view_logistics_own_unit'					=> true,
				'edit_logistics_own_unit'					=> false,
				
				'view_training_global'					=> true,
				'edit_training_global'					=> false,
				'view_training_own_area'					=> true,
				'edit_training_own_area'					=> false,
				'view_training_own_unit'					=> true,
				'edit_training_own_unit'					=> false
			)
		);
		if( is_null($result) ) {
			throw new WPNZCFUAExceptionWordPressInteractionCreateRole( 'Unable to create Commandant role' );
		}
		
	}
	
	// Create our database 
	function nzcfadmin_db_install(){
		
		// Access & use the WP database
		global $wpdb, $db_version;
		
		add_site_option( "wpnzcfua_db_version", $db_version );
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		// Create our database tables, to hold the WordPress NZCF Unit Admin data
		// Modified from https://codex.wordpress.org/Creating_Tables_with_Plugins

		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfua_rank (
  rank_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  rank varchar(70) NOT NULL,
  rank_shortname varchar(10) NOT NULL,
  ordering mediumint(8) unsigned NOT NULL,
  nzcf20_order mediumint(8) unsigned NOT NULL,  
  nzcf_corps tinyint(3) unsigned NOT NULL,
  UNIQUE KEY rank_id (rank_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfua_vacancy (
  vacancy_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  short_desc varchar(255) NOT NULL,
  long_desc text NOT NULL,
  closing_date datetime NOT NULL,
  posted_by_user_id mediumint(9) unsigned NOT NULL,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  nzcf_area tinyint(3) unsigned NOT NULL,
  nzcf_corps tinyint(3) unsigned NOT NULL,
  UNIQUE KEY vacancy_id (vacancy_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfua_vacancy_application (
  application_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  vacancy_id mediumint(9) unsigned NOT NULL,
  user_id bigint(20) unsigned NOT NULL COMMENT 'WordPress user ID',
  rank_id mediumint(9) unsigned NOT NULL COMMENT 'Applicant rank at time of application',
  name varchar(255) NOT NULL COMMENT 'Applicant name',
  service_number varchar(20) NOT NULL,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  reasons_for_applying TEXT NOT NULL,
  cv TEXT NOT NULL,
  cucdr_recommendation tinyint(3) unsigned,
  cucdr_comment TEXT,
  cucdr_rank_id mediumint(9) unsigned,
  cucdr_name varchar(255),
  cucdr_date datetime,
  aso_recommendation tinyint(3) unsigned,
  aso_comment TEXT,
  aso_rank_id mediumint(9) unsigned,
  aso_name varchar(255),
  aso_date datetime,
  ac_recommendation tinyint(3) unsigned,
  ac_comment TEXT,
  ac_rank_id mediumint(9) unsigned,
  ac_name varchar(255),
  ac_date datetime,
  comdt_recommendation tinyint(3) unsigned,
  comdt_comment TEXT,
  comdt_rank_id mediumint(9) unsigned,
  comdt_name varchar(255),
  comdt_date datetime,
  UNIQUE KEY application_id (application_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfua_vacancy_application_service (
  vacancy_service_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  application_id mediumint(9) unsigned NOT NULL,
  cadet_unit_id mediumint(9) unsigned NOT NULL,
  start_date date NOT NULL,
  end_date date,
  appointments_held varchar(255) NOT NULL,
  UNIQUE KEY vacancy_service_id (vacancy_service_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfua_vacancy_application_course (
  vacancy_course_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  application_id mediumint(9) unsigned NOT NULL,
  course_id mediumint(9) unsigned NOT NULL,
  attended_date date,
  times_staffed tinyint(1) unsigned DEFAULT 0,
  UNIQUE KEY vacancy_course_id (vacancy_course_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		
		update_option( "wpnzcfua_db_version", $db_version );
		update_site_option( "wpnzcfua_db_version", $db_version );
		
	}

	// Prepopulate our database
	function nzcfadmin_db_init(){
		
		// Access & use the WP database
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$table = $wpdb->prefix."wpnzcfua_rank";
		if( $wpdb->get_var( "SELECT rank_id FROM $table WHERE rank_shortname = 'WNGCDR'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'rank' => 'Wing Commander', 
					'rank_shortname' => 'WNGCDR', 
					'ordering' => 20, 
					'nzcf20_order' => 50,
					'nzcf_corps' => WPNZCFUA_CADETS_ATC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT rank_id FROM $table WHERE rank_shortname = 'SQNLDR'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'rank' => 'Squadron Leader', 
					'rank_shortname' => 'SQNLDR', 
					'ordering' => 30, 
					'nzcf20_order' => 50,
					'nzcf_corps' => WPNZCFUA_CADETS_ATC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT rank_id FROM $table WHERE rank_shortname = 'FLTLT'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'rank' => 'Flight Lieutenant', 
					'rank_shortname' => 'FLTLT', 
					'ordering' => 30, 
					'nzcf20_order' => 50,
					'nzcf_corps' => WPNZCFUA_CADETS_ATC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT rank_id FROM $table WHERE rank_shortname = 'PLTOFF'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'rank' => 'Pilot Officer', 
					'rank_shortname' => 'PLTOFF', 
					'ordering' => 40, 
					'nzcf20_order' => 50,
					'nzcf_corps' => WPNZCFUA_CADETS_ATC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT rank_id FROM $table WHERE rank_shortname = 'A/PLTOFF'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'rank' => 'Acting Pilot Officer', 
					'rank_shortname' => 'A/PLTOFF', 
					'ordering' => 41, 
					'nzcf20_order' => 50,
					'nzcf_corps' => WPNZCFUA_CADETS_ATC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT rank_id FROM $table WHERE rank_shortname = 'CIVILIAN'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'rank' => 'Civilian', 
					'rank_shortname' => 'CIVILIAN', 
					'ordering' => 99, 
					'nzcf20_order' => 99,
					'nzcf_corps' => WPNZCFUA_CADETS_ATC | WPNZCFUA_CADETS_CORPS | WPNZCFUA_CADETS_SEA 
				) 
			);
		}
		
	    $wpAngularEndpoint = new AngularEndpoint();
 	   $wpAngularEndpoint->init();
	}

	function register_nzcfadmin(){
		// We've got an updated plugin version installed, which needs updates to the DB
	    if ( get_site_option( 'wpnzcfua_db_version' ) != get_option('wpnzcfua_db_version') ) {
     	   nzcfadmin_db_install();
    	}
	}

	function nzcfadmin_uninstall(){
      	// If uninstall is not called from WordPress (i.e. is called via URL or command line)
		if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
          exit();
		}	
 
		// Delete our saved options
		delete_option( 'pluginoptionname' );
 
		// For site options in Multisite
		delete_site_option( $option_name );  
 
		// Drop a custom db table
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mytable" );
		
		// Remove our roles
		remove_role('nzcf-ua_cmdt');
    }
    
    if( !function_exists('current_user_has_role') ){
        function current_user_has_role( $role ){
            $current_user = new WP_User(wp_get_current_user()->ID);
            $user_roles = $current_user->roles;
            return in_array( $role, $user_roles );
		}
	}
	

	
	
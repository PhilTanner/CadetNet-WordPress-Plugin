<?php
	/*			
		Plugin Name: NZCF Cadet Net 
		Plugin URI:  https://github.com/PhilTanner/CadetNet-WordPress-Plugin.git
		Description: WordPress NZCF Cadet Net
		Version:     0.01
		Author:      Phil Tanner
		Author URI:  https://github.com/PhilTanner
		License:     GPL3
		License URI: http://www.gnu.org/licenses/gpl.html
		Domain Path: /languages
		Text Domain: nzcf-cadet-net
        
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
	require_once( dirname(__FILE__).'/class/defines.php' );
	// All our JSON outputter functions
	require_once( dirname(__FILE__).'/json/json.php' );

	
	$version = "0.01";
	$db_version = "0.01";
	
	add_option( "wpnzcfcn_version", $version );
	add_option( "wpnzcfcn_db_version", $db_version );
	
	
	// Function to be called when this application is installed (activated):
	register_activation_hook( __FILE__, 'wpnzcfcn_install' );
	// Create our DB tables to hold our data
	register_activation_hook( __FILE__, 'wpnzcfcn_db_install' );
	//Populate our data
	register_activation_hook( __FILE__, 'wpnzcfcn_db_init' );
	
	// Function to be called when this application is uninstalled:
	register_uninstall_hook( __FILE__, 'wpnzcfcn_uninstall' );
	// Function to be called when this application is loaded
	add_action('init', 'wpnzcfcn_register');
	
	
	
	// Prepopulate our database
	function wpnzcfcn_db_init(){
		
		// Access & use the WP database
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$table = $wpdb->prefix."wpnzcfcn_rank";
		if( $wpdb->get_var( "SELECT rank_id FROM $table WHERE rank_shortname = 'WNGCDR'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'rank' => 'Wing Commander', 
					'rank_shortname' => 'WNGCDR', 
					'ordering' => 20, 
					'nzcf20_order' => 50,
					'nzcf_corps' => WPNZCFCN_CADETS_ATC
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
					'nzcf_corps' => WPNZCFCN_CADETS_ATC
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
					'nzcf_corps' => WPNZCFCN_CADETS_ATC
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
					'nzcf_corps' => WPNZCFCN_CADETS_ATC
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
					'nzcf_corps' => WPNZCFCN_CADETS_ATC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT rank_id FROM $table WHERE rank_shortname = 'CIVILIAN'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'rank' => 'Civilian', 
					'rank_shortname' => 'CIV', 
					'ordering' => 99, 
					'nzcf20_order' => 99,
					'nzcf_corps' => WPNZCFCN_CADETS_ATC | WPNZCFCN_CADETS_CORPS | WPNZCFCN_CADETS_SEA 
				) 
			);
		}
		
	}
   	
	// Create our database on plugin Activation/Installation 
	function wpnzcfcn_db_install(){
		
		// Access & use the WP database
		global $wpdb, $db_version;
		
		add_site_option( "wpnzcfcn_db_version", $db_version );
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		// Create our database tables, to hold the WordPress NZCF Unit Admin data
		// Modified from https://codex.wordpress.org/Creating_Tables_with_Plugins

		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_rank (
  rank_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  rank varchar(70) NOT NULL,
  rank_shortname varchar(10) NOT NULL,
  ordering mediumint(8) unsigned NOT NULL,
  nzcf20_order mediumint(8) unsigned NOT NULL,  
  nzcf_corps tinyint(3) unsigned NOT NULL,
  UNIQUE KEY rank_id (rank_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_vacancy (
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
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_vacancy_application (
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
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_vacancy_application_service (
  vacancy_service_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  application_id mediumint(9) unsigned NOT NULL,
  cadet_unit_id mediumint(9) unsigned NOT NULL,
  start_date date NOT NULL,
  end_date date,
  appointments_held varchar(255) NOT NULL,
  UNIQUE KEY vacancy_service_id (vacancy_service_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_vacancy_application_course (
  vacancy_course_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  application_id mediumint(9) unsigned NOT NULL,
  course_id mediumint(9) unsigned NOT NULL,
  attended_date date,
  times_staffed tinyint(1) unsigned DEFAULT 0,
  UNIQUE KEY vacancy_course_id (vacancy_course_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		update_option( "wpnzcfcn_db_version", $db_version );
		update_site_option( "wpnzcfcn_db_version", $db_version );
	}

	// Plugin install/activation, core processes
	function wpnzcfcn_install(){
		global $wp_roles, $version;
		
		add_site_option( "wpnzcfcn_version", $version );
		
	}
	 
	// Plugin initialisation (loading)
	function wpnzcfcn_register(){
		// We've got an updated plugin version installed, which needs updates to the DB
	    if ( get_site_option( 'wpnzcfcn_db_version' ) != get_option('wpnzcfcn_db_version') ) {
     	   wpnzcfcn_db_install();
    	}
    
        // Register our JSON callbacks
    	// http://bordoni.me/ajax-wordpress/
    	add_action( 'wp_ajax_rank', 			'wpnzcfcn_json_callback_rank' );
		add_action( 'wp_ajax_nopriv_rank', 	'wpnzcfcn_json_callback_rank' );  
	}
	
	// Plugin uninstall/deactivations
	function wpnzcfcn_uninstall(){
      	// If uninstall is not called from WordPress (i.e. is called via URL or command line)
		if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
          exit();
		}	
 
		// Delete our saved options
		delete_option( 'wpnzcfcn_version' );
		delete_option( 'wpnzcfcn_db_version' );
		delete_site_option( 'wpnzcfcn_version' );
		delete_site_option( 'wpnzcfcn_db_version' );
 
		// Drop a custom db table
		global $wpdb;
		//$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mytable" );
		
    }
    
	

	
	
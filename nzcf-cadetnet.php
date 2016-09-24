<?php
	/*			
		Plugin Name: NZCF CadetNet 
		Plugin URI:  https://github.com/PhilTanner/CadetNet-WordPress-Plugin.git
		Description: WordPress NZCF CadetNet
		Version:     0.05
		Author:      Phil Tanner
		Author URI:  https://github.com/PhilTanner
		License:     GPL3
		License URI: http://www.gnu.org/licenses/gpl.html
		Domain Path: /languages
		Text Domain: nzcf-cadetnet
        
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
    
    
    
    	Useful links:
    	Done:
    	https://codex.wordpress.org/Writing_a_Plugin
	https://codex.wordpress.org/Adding_Administration_Menus
    	https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	https://codex.wordpress.org/Creating_Tables_with_Plugins
	http://blog.frontendfactory.com/how-to-create-front-end-page-from-your-wordpress-plugin/
	http://bordoni.me/ajax-wordpress/
	http://stackoverflow.com/a/17400906 
    
    	To do:    
    	https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/
    	http://bradsknutson.com/blog/custom-wordpress-page-template-custom-header-footer/
    	http://wordpress.stackexchange.com/questions/47265/google-apps-login-in-wordpress
	*/	
	
	define('WP_DEBUG', true); 
	
	$version = "0.05";
	$db_version = "0.02";
	
	add_option( "wpnzcfcn_version", $version );
	add_option( "wpnzcfcn_db_version", $db_version );
	
	
	require_once( dirname(__FILE__).'/class/defines.php' );
	
	// All our JSON outputter functions
	require_once( dirname(__FILE__).'/json/json.php' );
	
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
		
		$table = $wpdb->prefix."wpnzcfcn_course";
		if( $wpdb->get_var( "SELECT course_id FROM $table WHERE course_name = 'Commissioning Course'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'course_name' => 'Commissioning Course', 
					'personnel' => WPNZCFCN_PERSONNEL_GROUP_OFFICER | WPNZCFCN_PERSONNEL_GROUP_UNDER_OFFICER | WPNZCFCN_PERSONNEL_GROUP_CIVILIAN,
					'nzcf_corps' => WPNZCFCN_CADETS_ATC | WPNZCFCN_CADETS_NZCC | WPNZCFCN_CADETS_SCC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT course_id FROM $table WHERE course_name = 'ITTM Course'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'course_name' => 'ITTM Course', 
					'personnel' => WPNZCFCN_PERSONNEL_GROUP_OFFICER,
					'nzcf_corps' => WPNZCFCN_CADETS_ATC | WPNZCFCN_CADETS_NZCC | WPNZCFCN_CADETS_SCC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT course_id FROM $table WHERE course_name = 'Command Course'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'course_name' => 'Command Course', 
					'personnel' => WPNZCFCN_PERSONNEL_GROUP_OFFICER,
					'nzcf_corps' => WPNZCFCN_CADETS_ATC | WPNZCFCN_CADETS_NZCC | WPNZCFCN_CADETS_SCC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT course_id FROM $table WHERE course_name = 'Range Conducting Officer Course'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'course_name' => 'Range Conducting Officer Course', 
					'personnel' => WPNZCFCN_PERSONNEL_GROUP_OFFICER,
					'nzcf_corps' => WPNZCFCN_CADETS_ATC | WPNZCFCN_CADETS_NZCC | WPNZCFCN_CADETS_SCC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT course_id FROM $table WHERE course_name = 'Officer Bushcraft Course'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'course_name' => 'Officer Bushcraft Course', 
					'personnel' => WPNZCFCN_PERSONNEL_GROUP_OFFICER,
					'nzcf_corps' => WPNZCFCN_CADETS_ATC | WPNZCFCN_CADETS_NZCC | WPNZCFCN_CADETS_SCC
				) 
			);
		}
		if( $wpdb->get_var( "SELECT course_id FROM $table WHERE course_name = 'Marine Safety Officer Course'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'course_name' => 'Marine Safety Officer Course', 
					'personnel' => WPNZCFCN_PERSONNEL_GROUP_OFFICER,
					'nzcf_corps' => WPNZCFCN_CADETS_ATC | WPNZCFCN_CADETS_NZCC | WPNZCFCN_CADETS_SCC
				) 
			);
		}
				
		$table = $wpdb->prefix."wpnzcfcn_unit";
		if( $wpdb->get_var( "SELECT unit_id FROM $table WHERE unit_name = 'No 49 (District of Kāpiti) Squadron, Air Training Corps'" ) === null ){
			$wpdb->insert( 
				$table, 
				array( 
					'unit_name' => 'No 49 (District of Kāpiti) Squadron, Air Training Corps', 
					'address' => 'Old Crash Fire Building, 227 Kāpiti Road, Paraparaumu, Kāpiti Coast 5032.', 
					'website' => 'http://www.49squadron.org.nz', 
					'nzcf_corps' => WPNZCFCN_CADETS_ATC,
					'parade_night' => WPNZCFCN_DAY_WEDNESDAY
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

		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_course (
  course_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  course_name varchar(70) NOT NULL,
  personnel tinyint(5) NOT NULL,
  nzcf_corps tinyint(5) unsigned NOT NULL,
  UNIQUE KEY course_id (course_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_rank (
  rank_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  rank_sort smallint(6) NOT NULL,
  rank_eqv smallint(5) NOT NULL,
  rank_short varchar(10) NOT NULL,
  rank_long varchar(64) NOT NULL,
  rank_applies_to smallint(5) unsigned NOT NULL,
  rank_status tinyint(5) NOT NULL,
  UNIQUE KEY rank_id (rank_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );												

		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_relationship (
  relationship_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  relationship_sort smallint(6) NOT NULL,
  relationship_relationship varchar(20) NOT NULL,
  relationship_status tinyint(5) NOT NULL,
  UNIQUE KEY relationship_id (relationship_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_unit (
  unit_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  unit_name varchar(255) NOT NULL,
  address varchar(255) NOT NULL,
  phone varchar(255),
  email varchar(255),
  latitude float(10,6),
  longitude float(10,6),
  website varchar(150),
  nzcf_corps tinyint(5) unsigned NOT NULL,
  parade_night tinyint(7) unsigned,
  UNIQUE KEY unit_id (unit_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_vacancy (
  vacancy_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  short_desc varchar(255) NOT NULL,
  min_rank_id mediumint(9) unsigned NOT NULL,
  closing_date datetime NOT NULL,
  posted_by_user_id mediumint(9) unsigned NOT NULL,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  nzcf_area tinyint(3) unsigned NOT NULL,
  nzcf_corps tinyint(5) unsigned NOT NULL,
  UNIQUE KEY vacancy_id (vacancy_id)
) ".$wpdb->get_charset_collate().";";
		dbDelta( $sql );
		
		$sql = "CREATE TABLE ".$wpdb->prefix."wpnzcfcn_vacancy_application (
  application_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  vacancy_service_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
		
		delete_option("wpnzcfcn_version");
		add_site_option( "wpnzcfcn_version", $version );
		
		// Create an Expression of Interest page holder
		// Taken from: http://blog.frontendfactory.com/how-to-create-front-end-page-from-your-wordpress-plugin/
	



		// the menu entry...
		delete_option("wpnzcfcn_eoi_page_title");
		add_option("wpnzcfcn_eoi_page_title", 'Expressions of Interest', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_eoi_page_name");
		add_option("wpnzcfcn_eoi_page_name", 'eoi', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_eoi_page_id");
		add_option("wpnzcfcn_eoi_page_id", '0', '', 'yes');
		
		$eoi_page = get_page_by_title( get_option("wpnzcfcn_eoi_page_title") );
		if ( !$eoi_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_eoi_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			// Insert the post into the database
			$eoi_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$eoi_page_id = $eoi_page->ID;
			//make sure the page is not trashed...
			$eoi_page->post_status = 'private';
			$eoi_page_id = wp_update_post( $eoi_page );
		}
		delete_option( 'wpnzcfcn_eoi_page_id' );
		add_option( 'wpnzcfcn_eoi_page_id', $eoi_page_id );





		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_page_title");
		add_option("wpnzcfcn_cadetnet_page_title", 'CadetNET', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_page_name");
		add_option("wpnzcfcn_cadetnet_page_name", 'cadetnet', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_page_id");
		add_option("wpnzcfcn_cadetnet_page_id", '0', '', 'yes');
		
		$cadetnet_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_page_title") );
		if ( !$cadetnet_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			// Insert the post into the database
			$cadetnet_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_page_id = $cadetnet_page->ID;
			//make sure the page is not trashed...
			$cadetnet_page->post_status = 'private';
			$cadetnet_page_id = wp_update_post( $cadetnet_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_page_id' );
		add_option( 'wpnzcfcn_cadetnet_page_id', $cadetnet_page_id );

		


		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_admin_page_title");
		add_option("wpnzcfcn_cadetnet_admin_page_title", 'Admin', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_admin_page_name");
		add_option("wpnzcfcn_cadetnet_admin_page_name", 'admin', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_admin_page_id");
		add_option("wpnzcfcn_cadetnet_admin_page_id", '0', '', 'yes');
		
		$cadetnet_admin_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_admin_page_title") );
		if ( !$cadetnet_admin_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_admin_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_page_id;
			// Insert the post into the database
			$cadetnet_admin_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_admin_page_id = $cadetnet_admin_page->ID;
			//make sure the page is not trashed...
			$cadetnet_admin_page->post_status = 'private';
			$cadetnet_admin_page_id = wp_update_post( $cadetnet_admin_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_admin_page_id' );
		add_option( 'wpnzcfcn_cadetnet_admin_page_id', $cadetnet_admin_page_id );
		



		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_camps_page_title");
		add_option("wpnzcfcn_cadetnet_camps_page_title", 'Camps', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_camps_page_name");
		add_option("wpnzcfcn_cadetnet_camps_page_name", 'camps', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_camps_page_id");
		add_option("wpnzcfcn_cadetnet_camps_page_id", '0', '', 'yes');
		
		$cadetnet_camps_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_camps_page_title") );
		if ( !$cadetnet_camps_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_camps_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_page_id;
			// Insert the post into the database
			$cadetnet_camps_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_camps_page_id = $cadetnet_camps_page->ID;
			//make sure the page is not trashed...
			$cadetnet_camps_page->post_status = 'private';
			$cadetnet_camps_page_id = wp_update_post( $cadetnet_camps_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_camps_page_id' );
		add_option( 'wpnzcfcn_cadetnet_camps_page_id', $cadetnet_camps_page_id );



		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_profile_page_title");
		add_option("wpnzcfcn_cadetnet_profile_page_title", 'Profile', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_profile_page_name");
		add_option("wpnzcfcn_cadetnet_profile_page_name", 'profile', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_profile_page_id");
		add_option("wpnzcfcn_cadetnet_profile_page_id", '0', '', 'yes');
		
		$cadetnet_profile_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_profile_page_title") );
		if ( !$cadetnet_profile_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_profile_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_page_id;
			// Insert the post into the database
			$cadetnet_profile_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_profile_page_id = $cadetnet_profile_page->ID;
			//make sure the page is not trashed...
			$cadetnet_profile_page->post_status = 'private';
			$cadetnet_profile_page_id = wp_update_post( $cadetnet_profile_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_profile_page_id' );
		add_option( 'wpnzcfcn_cadetnet_profile_page_id', $cadetnet_profile_page_id );
		


		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_profile_awards_page_title");
		add_option("wpnzcfcn_cadetnet_profile_awards_page_title", 'Awards', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_profile_awards_page_name");
		add_option("wpnzcfcn_cadetnet_profile_awards_page_name", 'awards', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_profile_awards_page_id");
		add_option("wpnzcfcn_cadetnet_profile_awards_page_id", '0', '', 'yes');
		
		$cadetnet_profile_awards_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_profile_awards_page_title") );
		if ( !$cadetnet_profile_awards_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_profile_awards_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_profile_page_id;
			// Insert the post into the database
			$cadetnet_profile_awards_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_profile_awards_page_id = $cadetnet_profile_awards_page->ID;
			//make sure the page is not trashed...
			$cadetnet_profile_awards_page->post_status = 'private';
			$cadetnet_profile_awards_page_id = wp_update_post( $cadetnet_profile_awards_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_profile_awards_page_id' );
		add_option( 'wpnzcfcn_cadetnet_profile_awards_page_id', $cadetnet_profile_awards_page_id );


		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_profile_contact_page_title");
		add_option("wpnzcfcn_cadetnet_profile_contact_page_title", 'Contact', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_profile_contact_page_name");
		add_option("wpnzcfcn_cadetnet_profile_contact_page_name", 'contact', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_profile_contact_page_id");
		add_option("wpnzcfcn_cadetnet_profile_contact_page_id", '0', '', 'yes');
		
		$cadetnet_profile_contact_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_profile_contact_page_title") );
		if ( !$cadetnet_profile_contact_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_profile_contact_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_profile_page_id;
			// Insert the post into the database
			$cadetnet_profile_contact_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_profile_contact_page_id = $cadetnet_profile_contact_page->ID;
			//make sure the page is not trashed...
			$cadetnet_profile_contact_page->post_status = 'private';
			$cadetnet_profile_contact_page_id = wp_update_post( $cadetnet_profile_contact_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_profile_contact_page_id' );
		add_option( 'wpnzcfcn_cadetnet_profile_contact_page_id', $cadetnet_profile_contact_page_id );

		

		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_profile_courses_page_title");
		add_option("wpnzcfcn_cadetnet_profile_courses_page_title", 'Courses', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_profile_courses_page_name");
		add_option("wpnzcfcn_cadetnet_profile_courses_page_name", 'courses', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_profile_courses_page_id");
		add_option("wpnzcfcn_cadetnet_profile_courses_page_id", '0', '', 'yes');
		
		$cadetnet_profile_courses_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_profile_courses_page_title") );
		if ( !$cadetnet_profile_courses_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_profile_courses_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_profile_page_id;
			// Insert the post into the database
			$cadetnet_profile_courses_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_profile_courses_page_id = $cadetnet_profile_courses_page->ID;
			//make sure the page is not trashed...
			$cadetnet_profile_courses_page->post_status = 'private';
			$cadetnet_profile_courses_page_id = wp_update_post( $cadetnet_profile_courses_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_profile_courses_page_id' );
		add_option( 'wpnzcfcn_cadetnet_profile_courses_page_id', $cadetnet_profile_courses_page_id );
		
		

		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_profile_medical_page_title");
		add_option("wpnzcfcn_cadetnet_profile_medical_page_title", 'Medical', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_profile_medical_page_name");
		add_option("wpnzcfcn_cadetnet_profile_medical_page_name", 'medical', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_profile_medical_page_id");
		add_option("wpnzcfcn_cadetnet_profile_medical_page_id", '0', '', 'yes');
		
		$cadetnet_profile_medical_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_profile_medical_page_title") );
		if ( !$cadetnet_profile_medical_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_profile_medical_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_profile_page_id;
			// Insert the post into the database
			$cadetnet_profile_medical_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_profile_medical_page_id = $cadetnet_profile_medical_page->ID;
			//make sure the page is not trashed...
			$cadetnet_profile_medical_page->post_status = 'private';
			$cadetnet_profile_medical_page_id = wp_update_post( $cadetnet_profile_medical_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_profile_medical_page_id' );
		add_option( 'wpnzcfcn_cadetnet_profile_medical_page_id', $cadetnet_profile_medical_page_id );
		
		

		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_title");
		add_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_title", 'Next of kin', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_name");
		add_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_name", 'next of kin', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_id");
		add_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_id", '0', '', 'yes');
		
		$cadetnet_profile_next_of_kin_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_title") );
		if ( !$cadetnet_profile_next_of_kin_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_profile_page_id;
			// Insert the post into the database
			$cadetnet_profile_next_of_kin_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_profile_next_of_kin_page_id = $cadetnet_profile_next_of_kin_page->ID;
			//make sure the page is not trashed...
			$cadetnet_profile_next_of_kin_page->post_status = 'private';
			$cadetnet_profile_next_of_kin_page_id = wp_update_post( $cadetnet_profile_next_of_kin_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_profile_next_of_kin_page_id' );
		add_option( 'wpnzcfcn_cadetnet_profile_next_of_kin_page_id', $cadetnet_profile_next_of_kin_page_id );
		
		

		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_profile_promotions_page_title");
		add_option("wpnzcfcn_cadetnet_profile_promotions_page_title", 'Promotions', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_profile_promotions_page_name");
		add_option("wpnzcfcn_cadetnet_profile_promotions_page_name", 'promotions', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_profile_promotions_page_id");
		add_option("wpnzcfcn_cadetnet_profile_promotions_page_id", '0', '', 'yes');
		
		$cadetnet_profile_promotions_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_profile_promotions_page_title") );
		if ( !$cadetnet_profile_promotions_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_profile_promotions_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_profile_page_id;
			// Insert the post into the database
			$cadetnet_profile_promotions_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_profile_promotions_page_id = $cadetnet_profile_promotions_page->ID;
			//make sure the page is not trashed...
			$cadetnet_profile_promotions_page->post_status = 'private';
			$cadetnet_profile_promotions_page_id = wp_update_post( $cadetnet_profile_promotions_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_profile_promotions_page_id' );
		add_option( 'wpnzcfcn_cadetnet_profile_promotions_page_id', $cadetnet_profile_promotions_page_id );
		
		

		// the menu entry...
		delete_option("wpnzcfcn_cadetnet_profile_school_page_title");
		add_option("wpnzcfcn_cadetnet_profile_school_page_title", 'school', '', 'yes');
		// the slug...
		delete_option("wpnzcfcn_cadetnet_profile_school_page_name");
		add_option("wpnzcfcn_cadetnet_profile_school_page_name", 'school', '', 'yes');
		// the id...
		delete_option("wpnzcfcn_cadetnet_profile_school_page_id");
		add_option("wpnzcfcn_cadetnet_profile_school_page_id", '0', '', 'yes');
		
		$cadetnet_profile_school_page = get_page_by_title( get_option("wpnzcfcn_cadetnet_profile_school_page_title") );
		if ( !$cadetnet_profile_school_page ) {
			// Create post object
			$_p = array();
			$_p['post_title'] = get_option("wpnzcfcn_cadetnet_profile_school_page_title");
			$_p['post_content'] = "This text is just a placeholder text, it does not show, and will be replaced with the Expression of Interest form. It was created by the NZCF CadetNet plugin.";
			$_p['post_status'] = 'private'; // Need to be logged in
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			$_p['post_parent'] = $cadetnet_profile_page_id;
			// Insert the post into the database
			$cadetnet_profile_school_page_id = wp_insert_post( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			$cadetnet_profile_school_page_id = $cadetnet_profile_school_page->ID;
			//make sure the page is not trashed...
			$cadetnet_profile_school_page->post_status = 'private';
			$cadetnet_profile_school_page_id = wp_update_post( $cadetnet_profile_school_page );
		}
		delete_option( 'wpnzcfcn_cadetnet_profile_school_page_id' );
		add_option( 'wpnzcfcn_cadetnet_profile_school_page_id', $cadetnet_profile_school_page_id );
	}
	
	// Allow users to access the EOI submission form
	// Taken from http://blog.frontendfactory.com/how-to-create-front-end-page-from-your-wordpress-plugin/
	function eoi_form()
	{
		if(is_page(get_option("wpnzcfcn_eoi_page_title"))) {
			
			wp_enqueue_script( 
				'cadetnet_eoi_js', 
				plugins_url( '/js/eoi.js', __FILE__ ), 
				array('jquery','jquery-ui-core'), 
				date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/eoi.js' )), 
				true 
			);
			require_once( dirname(__FILE__)."/eoi/eoi.php");
			die();
		}
	}
	
	function cadetnet_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/cadetnet.php");
			die();
		}
	}
	
	function cadetnet_admin_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_admin_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/admin.php");
			die();
		}
	}
	function cadetnet_camps_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_camps_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/camps.php");
			die();
		}
	}
	function cadetnet_profile_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_profile_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/profile.php");
			die();
		}
	}
	function cadetnet_profile_awards_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_profile_awards_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/profile/awards.php");
			die();
		}
	}
	function cadetnet_profile_contact_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_profile_contact_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/profile/contact.php");
			die();
		}
	}
	function cadetnet_profile_courses_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_profile_courses_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/profile/courses.php");
			die();
		}
	}
	function cadetnet_profile_medical_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_profile_medical_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/profile/medical.php");
			die();
		}
	}
	function cadetnet_profile_next_of_kin_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/profile/next_of_kin.php");
			die();
		}
	}
	function cadetnet_profile_promotions_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_profile_promotions_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/profile/promotions.php");
			die();
		}
	}
	function cadetnet_profile_school_form()
	{
		if(is_page(get_option("wpnzcfcn_cadetnet_profile_school_page_title"))) {
			
			// wp_enqueue_script( 
			// 	'cadetnet_cadetnet_js', 
			// 	plugins_url( '/js/cadetnet.js', __FILE__ ), 
			// 	array('jquery','jquery-ui-core'), 
			// 	date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/cadetnet.js' )), 
			// 	true 
			// );
			require_once( dirname(__FILE__)."/cadetnet/partials/profile/school.php");
			die();
		}
	}
	// Load our JS scripts
	// Taken from https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	function wpnzcfcn_load_scripts($hook) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'jquery-ui-button' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		
		wp_enqueue_script( 
			'jquery_touchscreen', 
			plugins_url( '/js/touchpunch.furf.com_jqueryui-touch.js', __FILE__ ), 
			array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-mouse'), 
			"0.2.3", 
			true 
		);
		
		// Pass in PHP vars to JS:
		// https://codex.wordpress.org/Function_Reference/wp_localize_script
		wp_register_script( 
			'nzcf-cadetnet', 
			plugins_url( '/js/nzcf-cadetnet.js', __FILE__ ), 
			array('jquery','jquery-ui-core','jquery-ui-button','jquery-ui-autocomplete','jquery-ui-dialog'), 
			date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/nzcf-cadetnet.js' )), 
			true 
		);
		// Data we want to pass
		// TODO i18n of the below
		$translation_array = array(
			'site_url' => get_site_url(),
			'debug' => (int)WP_DEBUG
		);
		wp_localize_script( 'nzcf-cadetnet', 'WPURLs', $translation_array );
		wp_enqueue_script( 'nzcf-cadetnet' );
		
	}
	add_action('wp_enqueue_scripts', 'wpnzcfcn_load_scripts');
	
	function wpnzcfcn_load_admin_scripts($hook) {
		wpnzcfcn_load_scripts($hook);
		
		// Pass in PHP vars to JS:
		// https://codex.wordpress.org/Function_Reference/wp_localize_script
		wp_register_script( 
			'cadetnet_admin_js', 
			plugins_url( '/js/admin.js', __FILE__ ), 
			array('jquery','jquery-ui-core','jquery-ui-button','jquery-ui-autocomplete','jquery-ui-dialog'), 
			date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/js/admin.js' )), 
			true 
		);
		// Data we want to pass
		// TODO i18n of the below
		$translation_array = array(
			'eoi_address' => site_url( '/?page_id='.get_option( 'wpnzcfcn_eoi_page_id' ), __FILE__ )
		);
		wp_localize_script( 'cadetnet_admin_js', 'URLs', $translation_array );
		wp_enqueue_script( 'cadetnet_admin_js' );

	}
	add_action('admin_enqueue_scripts', 'wpnzcfcn_load_admin_scripts');
	
	// Load our style sheets
	function wpnzcfcn_load_styles($hook) {
		global $wp_scripts;
		
		wp_enqueue_style(
			'jquery-ui-redmond',
			'http://ajax.googleapis.com/ajax/libs/jqueryui/'.$wp_scripts->registered['jquery-ui-core']->ver.'/themes/redmond/jquery-ui.min.css');
		
		wp_register_style( 
			'eoi-css',    
			plugins_url( '/css/eoi.css', __FILE__ ), 
			false,   
			date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) .'/css/eoi.css' )  )
		);
		wp_enqueue_style ( 'eoi-css' ); 
		
		wp_register_style( 
			'nzcf-cadetnet',    
			plugins_url( '/css/nzcf-cadetnet.css', __FILE__ ), 
			false,   
			date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) .'/css/nzcf-cadetnet.css' )  )
		);
		wp_enqueue_style ( 'nzcf-cadetnet' ); 
	}
	add_action('wp_enqueue_scripts', 'wpnzcfcn_load_styles');
	add_action('admin_enqueue_scripts', 'wpnzcfcn_load_styles');
	
	// Plugin initialisation (loading)
	function wpnzcfcn_register(){
		// We've got an updated plugin version installed, which needs updates to the DB
		if ( get_site_option( 'wpnzcfcn_db_version' ) != get_option('wpnzcfcn_db_version') ) {
			wpnzcfcn_db_install();
		}
    
		// Stop WordPress's Magic Quotes re-enabling madness - http://stackoverflow.com/a/17400906
		// TODO - make sure this only affects our plugin, not ALL plugis while we're enabled.
		$_POST = array_map( 'stripslashes_deep', $_POST);
    
		// Register our JSON callbacks
		// http://bordoni.me/ajax-wordpress/
		// Logged in JSON queries
		add_action( 'wp_ajax_course_type', 		'wpnzcfcn_json_callback_course_type' );
		add_action( 'wp_ajax_eoi_application_list', 	'wpnzcfcn_json_callback_eoi_application_list' );
		add_action( 'wp_ajax_eoi_application', 		'wpnzcfcn_json_callback_eoi_application' ); 
		add_action( 'wp_ajax_eoi_positions', 		'wpnzcfcn_json_callback_eoi_positions' ); 
		add_action( 'wp_ajax_rank', 			'wpnzcfcn_json_callback_rank' );
		add_action( 'wp_ajax_unit', 			'wpnzcfcn_json_callback_unit' );
		
		// Logged out/anonymous JSON queries
		add_action( 'wp_ajax_nopriv_rank', 		'wpnzcfcn_json_callback_rank' ); 
		add_action( 'wp_ajax_nopriv_unit', 		'wpnzcfcn_json_callback_unit' ); 
		
		add_action( 'wp', 'eoi_form' );
		add_action( 'wp', 'cadetnet_form' );
		add_action( 'wp', 'cadetnet_admin_form');
		add_action( 'wp', 'cadetnet_camps_form');
		add_action( 'wp', 'cadetnet_profile_form');
		add_action( 'wp', 'cadetnet_profile_awards_form');
		add_action( 'wp', 'cadetnet_profile_contact_form');
		add_action( 'wp', 'cadetnet_profile_courses_form');
		add_action( 'wp', 'cadetnet_profile_medical_form');
		add_action( 'wp', 'cadetnet_profile_next_of_kin_form');
		add_action( 'wp', 'cadetnet_profile_promotions_form');
		add_action( 'wp', 'cadetnet_profile_school_form');
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
		
		// Clear the EOI page
		$the_page_id = get_option( 'wpnzcfcn_eoi_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_eoi_page_title");
		delete_option("wpnzcfcn_eoi_page_name");
		delete_option("wpnzcfcn_eoi_page_id");
		
		$the_page_id = get_option( 'wpnzcfcn_cadetnet_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_page_title");
		delete_option("wpnzcfcn_cadetnet_page_name");
		delete_option("wpnzcfcn_cadetnet_page_id");
		
		$the_page_id = get_option( 'wpnzcfcn_cadetnet_admin_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_admin_page_title");
		delete_option("wpnzcfcn_cadetnet_admin_page_name");
		delete_option("wpnzcfcn_cadetnet_admin_page_id");

		$the_page_id = get_option( 'wpnzcfcn_cadetnet_camps_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_camps_page_title");
		delete_option("wpnzcfcn_cadetnet_camps_page_name");
		delete_option("wpnzcfcn_cadetnet_camps_page_id");

		$the_page_id = get_option( 'wpnzcfcn_cadetnet_profile_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_profile_page_title");
		delete_option("wpnzcfcn_cadetnet_profile_page_name");
		delete_option("wpnzcfcn_cadetnet_profile_page_id");
		
		$the_page_id = get_option( 'wpnzcfcn_cadetnet_profile_awards_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_profile_awards_page_title");
		delete_option("wpnzcfcn_cadetnet_profile_awards_page_name");
		delete_option("wpnzcfcn_cadetnet_profile_awards_page_id");
		
		$the_page_id = get_option( 'wpnzcfcn_cadetnet_profile_contact_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_profile_contact_page_title");
		delete_option("wpnzcfcn_cadetnet_profile_contact_page_name");
		delete_option("wpnzcfcn_cadetnet_profile_contact_page_id");
		
		$the_page_id = get_option( 'wpnzcfcn_cadetnet_profile_courses_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_profile_courses_page_title");
		delete_option("wpnzcfcn_cadetnet_profile_courses_page_name");
		delete_option("wpnzcfcn_cadetnet_profile_courses_page_id");
		
		$the_page_id = get_option( 'wpnzcfcn_cadetnet_profile_medical_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_profile_medical_page_title");
		delete_option("wpnzcfcn_cadetnet_profile_medical_page_name");
		delete_option("wpnzcfcn_cadetnet_profile_medical_page_id");
		
		$the_page_id = get_option( 'wpnzcfcn_cadetnet_profile_next_of_kin_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_title");
		delete_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_name");
		delete_option("wpnzcfcn_cadetnet_profile_next_of_kin_page_id");
		
		$the_page_id = get_option( 'wpnzcfcn_cadetnet_profile_promotions_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_profile_promotions_page_title");
		delete_option("wpnzcfcn_cadetnet_profile_promotions_page_name");
		delete_option("wpnzcfcn_cadetnet_profile_promotions_page_id");
		
		$the_page_id = get_option( 'wpnzcfcn_cadetnet_profile_school_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); // this will trash, not delete
		}
		delete_option("wpnzcfcn_cadetnet_profile_school_page_title");
		delete_option("wpnzcfcn_cadetnet_profile_school_page_name");
		delete_option("wpnzcfcn_cadetnet_profile_school_page_id");
	}
    
	function wpnzcfcn_footer() {
		// Add jQueryUI dialog box element placeholder to text pages
		echo '<div id="dialog"></div>';
	}
	add_action( 'wp_footer', 'wpnzcfcn_footer' );

	
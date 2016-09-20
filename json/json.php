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
	require_once( dirname(__FILE__).'/../class/defines.php' );
	require_once( dirname(__FILE__).'/../eoi/json.php' );
	
	// Based on code located at:
	// http://bordoni.me/ajax-wordpress/
	// JSON URIs defined in ../nzcf-cadet-net.php -> wpnzcfcn_register()
	
	// $_GET['term'] is the partial text entered into a jQueryUI AutoComplete field & passed to us
	
	// List the types of courses
	function wpnzcfcn_json_callback_course_type() {
		global $wpdb;
	    $response = array();

		$keywords = (isset($_GET['term'])?$_GET['term']:'');
		// Never trust input from a user!
		$keywords = wp_kses( strtolower($keywords), array() );
		$response = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT 
				*
			FROM 
				".$wpdb->prefix."wpnzcfcn_course
			WHERE
				LOWER(".$wpdb->prefix."wpnzcfcn_course.course_name) LIKE %s
			ORDER BY
				LOWER(".$wpdb->prefix."wpnzcfcn_course.course_name) ASC;",
			'%'.$wpdb->esc_like($keywords).'%'
        ) );
        // For our autocomplete jQueryUI boxes, simplify our value/label options
		foreach( $response as $row ) {
			$row->value = $row->course_id;
			$row->label = $row->course_name;
			unset($row->course_id);
		}
 	   // Never forget to exit or die on the end of a WordPress AJAX action!
	    exit( json_encode( $response ) ); 
	}
	
	// List the NZCF ranks
	function wpnzcfcn_json_callback_rank() {
		global $wpdb;
	    $response = array();

		$keywords = (isset($_GET['term'])?$_GET['term']:'');
		// Never trust input from a user!
		$keywords = wp_kses( strtolower($keywords), array() );
		$response = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT 
				* 
			FROM 
				".$wpdb->prefix."wpnzcfcn_rank 
			WHERE 
				LOWER(rank_shortname) LIKE %s 
				OR LOWER(rank) LIKE %s
			ORDER BY 
				ordering ASC,
				rank ASC;",
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%'
        ) );
        // For our autocomplete jQueryUI boxes, simplify our value/label options
		foreach( $response as $rank ) {
			$rank->value = $rank->rank_id;
			if( $rank->rank != $rank->rank_shortname ) {
				$rank->label = $rank->rank." (".$rank->rank_shortname.")";
			} else {
				$rank->label = $rank->rank;
			}
		}
 	   // Never forget to exit or die on the end of a WordPress AJAX action!
	    exit( json_encode( $response ) ); 
	}

	// List the units
	function wpnzcfcn_json_callback_unit() {
		global $wpdb;
	    $response = array();

		$keywords = (isset($_GET['term'])?$_GET['term']:'');
		// Never trust input from a user!
		$keywords = wp_kses( strtolower($keywords), array() );
		$response = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT 
				*
			FROM 
				".$wpdb->prefix."wpnzcfcn_unit
			WHERE
				LOWER(".$wpdb->prefix."wpnzcfcn_unit.unit_name) LIKE %s
				OR LOWER(".$wpdb->prefix."wpnzcfcn_unit.address) LIKE %s
				OR LOWER(".$wpdb->prefix."wpnzcfcn_unit.website) LIKE %s
				OR LOWER(".$wpdb->prefix."wpnzcfcn_unit.phone) LIKE %s
				OR LOWER(".$wpdb->prefix."wpnzcfcn_unit.email) LIKE %s
			ORDER BY
				LOWER(".$wpdb->prefix."wpnzcfcn_unit.unit_name) ASC;",
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%'
        ) );
        // For our autocomplete jQueryUI boxes, simplify our value/label options
		foreach( $response as $row ) {
			$row->value = $row->unit_id;
			$row->label = $row->unit_name;
			unset($row->unit_id);
		}
 	   // Never forget to exit or die on the end of a WordPress AJAX action!
	    exit( json_encode( $response ) ); 
	}

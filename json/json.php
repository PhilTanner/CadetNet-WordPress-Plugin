<?php
	/*			
		Plugin Name: NZCF CadetNet 
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
	// JSON URIs defined in ../nzcf-cadetnet.php -> wpnzcfcn_register()
	
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
				LOWER(".$wpdb->prefix."wpnzcfcn_course.course_long) LIKE %s
				OR LOWER(".$wpdb->prefix."wpnzcfcn_course.course_short) LIKE %s
			ORDER BY
				".$wpdb->prefix."wpnzcfcn_course.course_sort ASC;",
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%'
		) );
		// For our autocomplete jQueryUI boxes, simplify our value/label options
		foreach( $response as $row ) {
			$row->value = $row->course_id;
			$row->label = $row->course_long;
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
					(
						LOWER(rank_short) LIKE %s 
						OR LOWER(rank_long) LIKE %s
					) 
					AND rank_status > 0
				ORDER BY 
					rank_sort ASC;",
				'%'.$wpdb->esc_like($keywords).'%',
				'%'.$wpdb->esc_like($keywords).'%'
			) );
		// For our autocomplete jQueryUI boxes, simplify our value/label options
		foreach( $response as $rank ) {
			$rank->value = $rank->rank_id;
			if( $rank->rank != $rank->rank_short ) {
				$rank->label = $rank->rank_long." (".$rank->rank_short.")";
			} else {
				$rank->label = $rank->rank_long;
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
				LOWER(".$wpdb->prefix."wpnzcfcn_unit.unit_short) LIKE %s
				OR LOWER(".$wpdb->prefix."wpnzcfcn_unit.unit_medium) LIKE %s
				OR LOWER(".$wpdb->prefix."wpnzcfcn_unit.unit_long) LIKE %s
			ORDER BY
				LOWER(".$wpdb->prefix."wpnzcfcn_unit.unit_sort) ASC;",
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%'
		) );
		// For our autocomplete jQueryUI boxes, simplify our value/label options
		foreach( $response as $row ) {
			$row->value = $row->unit_id;
			$row->label = $row->unit_medium;
			unset($row->unit_id);
		}
 	   // Never forget to exit or die on the end of a WordPress AJAX action!
	    exit( json_encode( $response ) ); 
	}

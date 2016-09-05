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
	
	// Based on code located at:
	// http://bordoni.me/ajax-wordpress/
	// JSON URIs defined in ../nzcf-cadet-net.php -> wpnzcfcn_register()
	
	// List EOI vacancy positions currently available
	function wpnzcfcn_json_callback_eoi_positions() {
		global $wpdb;
	    $response = array();

		// term is the partial text entered into a jQueryUI AutoComplete field & passed to us
		$keywords = (isset($_GET['term'])?$_GET['term']:'');
		// Never trust input from a user!
		$keywords = wp_kses( strtolower($keywords), array() );
		$response = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT 
				".$wpdb->prefix."users.display_name as listed_by,
				IF( ".$wpdb->prefix."wpnzcfcn_rank.nzcf_corps = ".$wpdb->prefix."wpnzcfcn_vacancy.nzcf_corps, ".$wpdb->prefix."wpnzcfcn_rank.rank, CONCAT( ".$wpdb->prefix."wpnzcfcn_rank.rank, ' (E)') ) AS ranks,
				".$wpdb->prefix."wpnzcfcn_vacancy.*
			FROM 
				".$wpdb->prefix."wpnzcfcn_vacancy
				INNER JOIN ".$wpdb->prefix."users
					ON ".$wpdb->prefix."users.ID = ".$wpdb->prefix."wpnzcfcn_vacancy.posted_by_user_id
				INNER JOIN ".$wpdb->prefix."wpnzcfcn_rank
					ON ".$wpdb->prefix."wpnzcfcn_rank.rank_id = ".$wpdb->prefix."wpnzcfcn_vacancy.min_rank_id
				LEFT JOIN ".$wpdb->prefix."wpnzcfcn_vacancy_application
					ON ".$wpdb->prefix."wpnzcfcn_vacancy.vacancy_id = ".$wpdb->prefix."wpnzcfcn_vacancy_application.vacancy_id
			WHERE
				LOWER(".$wpdb->prefix."wpnzcfcn_vacancy.short_desc) LIKE %s
			ORDER BY
				LOWER(".$wpdb->prefix."wpnzcfcn_vacancy.short_desc) ASC;",
			'%'.$wpdb->esc_like($keywords).'%'
        ) );
        // For our autocomplete jQueryUI boxes, simplify our value/label options
		foreach( $response as $row ) {
			$row->value = $row->vacancy_id;
			$row->label = $row->short_desc;
			$row->closing_date = date('Y-m-d',strtotime($row->closing_date))."";
			unset($row->vacancy_id);
		}
 	   // Never forget to exit or die on the end of a WordPress AJAX action!
	    exit( json_encode( $response ) ); 
	}

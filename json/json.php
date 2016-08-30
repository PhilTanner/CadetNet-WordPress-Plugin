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
	
	// List the NZCF ranks
	function wpnzcfcn_json_callback_rank() {
		global $wpdb;
	    $response = array();

		// term is the partial text entered into a jQueryUI AutoComplete field & passed to us
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
				ordering ASC;",
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%'
        ) );
        // For our autocomplete jQueryUI boxes, simplify our value/label options
		foreach( $response as $rank ) {
			$rank->value = $rank->rank_id;
			$rank->label = $rank->rank." (".$rank->rank_shortname.")";
		}
 	   // Never forget to exit or die on the end of a WordPress AJAX action!
	    exit( json_encode( $response ) ); 
	}

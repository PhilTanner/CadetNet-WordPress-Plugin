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
				IF( ".$wpdb->prefix."wpnzcfcn_rank.nzcf_corps = ".$wpdb->prefix."wpnzcfcn_vacancy.nzcf_corps, ".$wpdb->prefix."wpnzcfcn_rank.rank, CONCAT( ".$wpdb->prefix."wpnzcfcn_rank.rank, ' (E)') ) AS ranks,
				".$wpdb->prefix."wpnzcfcn_vacancy.*
			FROM 
				".$wpdb->prefix."wpnzcfcn_vacancy
				INNER JOIN ".$wpdb->prefix."wpnzcfcn_rank
					ON ".$wpdb->prefix."wpnzcfcn_rank.rank_id = ".$wpdb->prefix."wpnzcfcn_vacancy.min_rank_id
			WHERE
				LOWER(".$wpdb->prefix."wpnzcfcn_vacancy.short_desc) LIKE %s
				AND ".$wpdb->prefix."wpnzcfcn_vacancy.closing_date > now()
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

	// Return details for EOI id passed in
	function wpnzcfcn_json_callback_eoi_application() {
		global $wpdb;
		$eoi_id = (isset($_GET['eoi_id'])?(int)$_GET['eoi_id']:0);
		
		// This is a POST method, so we're updating data
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$displaymsg = "";
			
			try {
				// TODO - check for capability for the relevnt parts
				$wpdb->replace( 
					$wpdb->prefix."wpnzcfcn_vacancy_application", 
					array( 
						'application_id' => $eoi_id, 
						'vacancy_id' => (int)$_POST['vacancy_id'], 
						'user_id' => get_current_user_id(), 
						'rank_id' => (int)$_POST['applicant_rank_id'], 
						'name' => $_POST['applicant_name'],
						'service_number' => $_POST['service_number'],
						'reasons_for_applying' => $_POST['best_candidate_response'],
						'cv' => $_POST['cv'],
							
						'cucdr_recommendation' => $_POST['cucdr_recommendation'],
						'cucdr_comment' =>  $_POST['cucdr_comment'],
						'cucdr_rank_id' =>  $_POST['cucdr_rank_id'],
						'cucdr_name' =>  $_POST['cucdr_name'],
						'cucdr_date' =>  $_POST['cucdr_date'],
						
						'aso_recommendation' =>  $_POST['aso_recommendation'],
						'aso_comment' =>  $_POST['aso_comment'],
						'aso_rank_id' =>  $_POST['aso_rank_id'],
						'aso_name' =>  $_POST['aso_name'],
						'aso_date' =>  $_POST['aso_date'],
							
						'ac_recommendation' =>  $_POST['ac_recommendation'],
						'ac_comment' =>  $_POST['ac_comment'],
						'ac_rank_id' =>  $_POST['ac_rank_id'],
						'ac_name' =>  $_POST['ac_name'],
						'ac_date' =>  $_POST['ac_date'],
							
						'comdt_recommendation' =>  $_POST['comdt_recommendation'],
						'comdt_comment' =>  $_POST['comdt_comment'],
						'comdt_rank_id' =>  $_POST['comdt_rank_id'],
						'comdt_name' =>  $_POST['comdt_name'],
						'comdt_date' =>  $_POST['comdt_date']
					) 
				);
				
			} catch( Exception $ex ) {
				header("HTTP/1.0 500 Unhandled error");
			}
			header("HTTP/1.0 200 ".__('Saved','nzcf-cadet-net'));
			echo '<p>';
			echo __('Application saved. You can now close this window','nzcf-cadet-net');
			echo '</p>';
			exit();
		
		}
		
		// Our default structure for new applications
		// There is no reason for the part# array elements, other than to add readability to the output. 
		// As the eoi form page recursively loops thru the arrays and uses the ID to update the value,
		// anything could go here at any depth. 
	    $application = array( 
			'part1' => array( 
				'vacancy_id' => $eoi_id, 
				'vacancy_description' => "",
				'rank' => "",
				'application_closes' => ""
			),
			'part2' => array(
				'applicant_name' => "",
				'applicant_rank' => "",
				'applicant_rank_id' => "",
				'service_number' => ""
			),
			'part3' => array(
				'service' => array(),
				'course' => array(),
				'course_staffed' => array()
			),
			'part4' => array( 'best_candidate_response' => ""),
			'part5' => array( 'cv' => ""),
			'part6' => array(
				'cucdr_recommendation' => '',
				'cucdr_comment' => '',
				'cucdr_rank' => '',
				'cucdr_name' => '',
				'cucdr_date' => ''
			),
			'part7' => array(
				'aso_recommendation' => '',
				'aso_comment' => '',
				'aso_rank' => '',
				'aso_name' => '',
				'aso_date' => ''
			),
			'part8' => array(
				'ac_recommendation' => '',
				'ac_comment' => '',
				'ac_rank' => '',
				'ac_name' => '',
				'ac_date' => ''
			),
			'part9' => array(
				'comdt_recommendation' => '',
				'comdt_comment' => '',
				'comdt_rank' => '',
				'comdt_name' => '',
				'comdt_date' => ''
			)
		);

		// Populate the structure from the DB
		$response = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT 
				*
			FROM 
				".$wpdb->prefix."wpnzcfcn_vacancy_application
				INNER JOIN ".$wpdb->prefix."wpnzcfcn_vacancy
					ON ".$wpdb->prefix."wpnzcfcn_vacancy_application.vacancy_id = ".$wpdb->prefix."wpnzcfcn_vacancy.vacancy_id
				INNER JOIN ".$wpdb->prefix."wpnzcfcn_rank
					ON ".$wpdb->prefix."wpnzcfcn_vacancy.min_rank_id = ".$wpdb->prefix."wpnzcfcn_rank.rank_id
			WHERE
				application_id = %d;",
			$eoi_id
        ) );
        
		foreach( $response as $row ) {
			$application['part1']['vacancy_id'] = $row->vacancy_id;
			$application['part1']['vacancy_description'] = $row->short_desc;
			$application['part1']['rank'] = $row->rank;
			$application['part1']['application_closes'] = date('Y-m-d',strtotime($row->closing_date))."";
			$application['part2']['applicant_name'] = $row->name;
			$application['part2']['service_number'] = $row->service_number;
			
			$subquery = $wpdb->get_results( $wpdb->prepare(
				"
				SELECT 
					*
				FROM 
					".$wpdb->prefix."wpnzcfcn_rank
				WHERE
					rank_id = %d;",
				$row->rank_id
   	     ) );
			foreach( $subquery as $subrow ) {
				$application['part2']['applicant_rank'] = $subrow->rank;
				$application['part2']['applicant_rank_id'] = $subrow->rank_id;
			}
			
			// Service history
			$subquery = $wpdb->get_results( $wpdb->prepare(
				"
				SELECT 
					*
				FROM 
					".$wpdb->prefix."wpnzcfcn_vacancy_application_service
				WHERE
					application_id = %d;",
				$eoi_id
   	     ) );
			$i=0;
			foreach( $subquery as $subrow ) {
				$i++;
				$application['part3']['service'][] = array(
					'service_cadet_unit_'.$i => $subrow->cadet_unit_id,
					'service_start_date_'.$i => $subrow->start_date,
					'service_end_date_'.$i => $subrow->end_date,
					'service_appointments_held_'.$i => $subrow->appointments_held
				);
			}
			
			// Course history
			$subquery = $wpdb->get_results( $wpdb->prepare(
				"
				SELECT 
					*
				FROM 
					".$wpdb->prefix."wpnzcfcn_vacancy_application_course
				WHERE
					application_id = %d
					AND times_staffed=0;",
				$eoi_id
   	     ) );
			$i=0;
			foreach( $subquery as $subrow ) {
				$i++;
				$application['part3']['course'][] = array(
					'course_qual_id_'.$i => $subrow->course_id,
					'course_qual_'.$i => "TODO",
					'course_date_'.$i => $subrow->attended_date
				);
			}
			
			// Course staffed history
			$subquery = $wpdb->get_results( $wpdb->prepare(
				"
				SELECT 
					*
				FROM 
					".$wpdb->prefix."wpnzcfcn_vacancy_application_course
				WHERE
					application_id = %d
					AND times_staffed>0;",
				$eoi_id
   	     ) );
			$i=0;
			foreach( $subquery as $subrow ) {
				$i++;
				$application['part3']['course_staffed'][] = array(
					'course_staffed_id_'.$i => $subrow->course_id,
					'course_staffed_'.$i => "TODO",
					'course_staffed_qty_'.$i => $subrow->times_staffed
				);
			}
			
			$application['part4']['best_candidate_response'] = $row->reasons_for_applying;
			$application['part5']['cv'] = $row->cv;
			
			$application['part6'] = array(
				'cucdr_recommendation' => $row->cucdr_recommendation,
				'cucdr_comment' => $row->cucdr_comment,
				'cucdr_name' => $row->cucdr_name,
				'cucdr_date' => $row->cucdr_date
			);
			$subquery = $wpdb->get_results( $wpdb->prepare(
				"
				SELECT 
					*
				FROM 
					".$wpdb->prefix."wpnzcfcn_rank
				WHERE
					rank_id = %d;",
				$row->cucdr_rank_id
   	     ) );
			foreach( $subquery as $subrow ) {
				$application['part6']['cucdr_rank'] = $subrow->rank;
				$application['part6']['cucdr_rank_id'] = $subrow->rank_id;
			}
			
			$application['part7'] = array(
				'aso_recommendation' => $row->aso_recommendation,
				'aso_comment' => $row->aso_comment,
				'aso_name' => $row->aso_name,
				'aso_date' => $row->aso_date
			);
			$subquery = $wpdb->get_results( $wpdb->prepare(
				"
				SELECT 
					*
				FROM 
					".$wpdb->prefix."wpnzcfcn_rank
				WHERE
					rank_id = %d;",
				$row->aso_rank_id
   	     ) );
			foreach( $subquery as $subrow ) {
				$application['part7']['aso_rank'] = $subrow->rank;
				$application['part7']['aso_rank_id'] = $subrow->rank_id;
			}
			
			$application['part8'] = array(
				'ac_recommendation' => $row->ac_recommendation,
				'ac_comment' => $row->ac_comment,
				'ac_name' => $row->ac_name,
				'ac_date' => $row->ac_date
			);
			$subquery = $wpdb->get_results( $wpdb->prepare(
				"
				SELECT 
					*
				FROM 
					".$wpdb->prefix."wpnzcfcn_rank
				WHERE
					rank_id = %d;",
				$row->ac_rank_id
   	     ) );
			foreach( $subquery as $subrow ) {
				$application['part8']['ac_rank_id'] = $subrow->rank;
				$application['part8']['ac_rank_id'] = $subrow->rank_id;
			}
			
			$application['part9'] = array(
				'comdt_recommendation' => $row->comdt_recommendation,
				'comdt_comment' => $row->comdt_comment,
				'comdt_name' => $row->comdt_name,
				'comdt_date' => $row->comdt_date
			);
			$subquery = $wpdb->get_results( $wpdb->prepare(
				"
				SELECT 
					*
				FROM 
					".$wpdb->prefix."wpnzcfcn_rank
				WHERE
					rank_id = %d;",
				$row->comdt_rank_id
   	     ) );
			foreach( $subquery as $subrow ) {
				$application['part9']['comdt_rank'] = $subrow->rank;
				$application['part9']['comdt_rank_id'] = $subrow->rank_id;
			}
			
		}
		
 	   // Never forget to exit or die on the end of a WordPress AJAX action!
	    exit( json_encode( $application ) ); 
		
	}
	
	
	
	
	
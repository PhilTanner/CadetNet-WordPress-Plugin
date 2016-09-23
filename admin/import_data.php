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
	
	function cadetnet_admin_menu_import_data() {
		
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<h2>'.__('Import Data','nzcf-cadetnet').'</h2>';
		
		global $wpdb;
		
		// Request the file to upload
		if( !isset($_FILES['fileupload']) ) {
		?>
			<form method="post" enctype="multipart/form-data" style="width:30em;">
				<label for="fileupload"><?= __('File to upload', 'nzcf-cadetnet') ?></label>
				<input type="file" name="fileupload" id="fileupload" required="required" />
				<hr />
				<label for="datatype"><?= __('Data type', 'nzcf-cadetnet') ?></label>
				<select name="datatype" id="datatype" required="required">
					<option value="">
					<option value="ranks"><?=__('Ranks','nzcf-cadetnet')?></option>
				</select>
				<hr />
				<button type="submit"><?=__('Upload','nzcf-cadetnet')?></button>
			</form>
			<p style="clear:both;">
				<?= __('<strong>Note:</strong> Files should be in CSV (Comma Separated Value) format, with a header row containing the names of the fields', 'nzcdf-cadet-net') ?>
			</p>
		<?php
		// We've got a file uploaded, so let's process it.
		} else {
			try {
				$tmp_filename = $_FILES["fileupload"]["tmp_name"];
				// We only want to allow CSV file type uploads
				if( array_search($_FILES["fileupload"]["type"], array('text/csv','application/csv','application/vnd.ms-excel')) === false ) {
					throw new WPNZCFCNExceptionBadData(sprintf(__('Unknown file datatype: "%s".','nzcf-cadetnet'),$_FILES["fileupload"]["type"]));
				}
				// Read in our CSV into a named array (adjusted from from http://php.net/manual/en/function.str-getcsv.php#117692 )
				$csv = array_map('str_getcsv', file($tmp_filename));
				// Ensure our column names are lowercase
				foreach($csv[0] as &$val) {
					$val = strtolower($val);
				}
				// Create a named array for each row
				array_walk($csv, function(&$a) use ($csv) {
					$a = array_combine($csv[0], $a);
				});
				array_shift($csv); # remove column header
				
				$rowcounter = 1;  # "Friendly" row counter for error displays only
				// Loop through our CSV array to "do stuff"
				foreach( $csv as $row ){
					switch( strtolower($_POST['datatype']) ) {
						case 'ranks':
							// Make sure we have the data we're expecting to receive.
							$required_cols = array( 'rank_sort','rank_eqv','rank_short','rank_long','rank_scc','rank_nzcc','rank_atc','rank_rnzn','rank_army','rank_rnzaf','rank_off','rank_cdt','rank_civ','rank_status' );
							foreach($required_cols as $col ) {
								if( !isset($row[$col]) ) { 
									throw new WPNZCFCNExceptionBadData(sprintf(__('Missing required column: "%s" (line %d)','nzcf-cadetnet'), $col, $rowcounter));
								}
							}
							// Make sure we're not getting text where we expect to be receiving numbers
							$number_cols = array( 'rank_sort','rank_eqv','rank_scc','rank_nzcc','rank_atc','rank_rnzn','rank_army','rank_rnzaf','rank_off','rank_cdt','rank_civ' );
							foreach($required_cols as $col ) {
								if( (int)$row[$col] != $row[$col] ) { 
									throw new WPNZCFCNExceptionBadData(sprintf(__('Wrong data type for column: "%s" expecting number, got "%s" (line %d)','nzcf-cadetnet'), $col, $row[$col], $rowcounter));
								}
							}
							break;
						default:
							throw new WPNZCFCNExceptionBadData(sprintf(__('Unknown file datatype: "%s"','nzcf-cadetnet'),$_POST['datatype']));
					}
					$rowcounter++;
				}
				// Data looks OK (i.e. we've not thrown an error & aborted yet - lets do an import.
				if( strtolower($_POST['datatype']) == 'ranks' ) {
					// Clear our DB table first
					$wpdb->query('TRUNCATE '.$wpdb->prefix."wpnzcfcn_rank");
				}
				foreach( $csv as $row ){
					switch( strtolower($_POST['datatype']) ) {
						case 'ranks':
							// First off, calculate our rank bitmask
							$rank_corps_bitmask = 0;
							if( (bool)$row['rank_scc'] ) {
								$rank_corps_bitmask = $rank_corps_bitmask | WPNZCFCN_CADETS_SCC;
							}
							if( (bool)$row['rank_nzcc'] ) {
								$rank_corps_bitmask = $rank_corps_bitmask | WPNZCFCN_CADETS_NZCC;
							}
							if( (bool)$row['rank_atc'] ) {
								$rank_corps_bitmask = $rank_corps_bitmask | WPNZCFCN_CADETS_ATC;
							}
							if( (bool)$row['rank_rnzn'] ) {
								$rank_corps_bitmask = $rank_corps_bitmask | WPNZCFCN_REGULAR_FORCE_NAVY;
							}
							if( (bool)$row['rank_army'] ) {
								$rank_corps_bitmask = $rank_corps_bitmask | WPNZCFCN_REGULAR_FORCE_ARMY;
							}
							if( (bool)$row['rank_rnzaf'] ) {
								$rank_corps_bitmask = $rank_corps_bitmask | WPNZCFCN_REGULAR_FORCE_RNZAF;
							}
							if( (bool)$row['rank_off'] ) {
								$rank_corps_bitmask = $rank_corps_bitmask | WPNZCFCN_RANK_OFFICER;
							}
							if( (bool)$row['rank_cdt'] ) {
								$rank_corps_bitmask = $rank_corps_bitmask | WPNZCFCN_RANK_CADET;
							}
							if( (bool)$row['rank_civ'] ) {
								$rank_corps_bitmask = $rank_corps_bitmask | WPNZCFCN_RANK_CIVILIAN;
							}
							
							$wpdb->insert( 
								$wpdb->prefix."wpnzcfcn_rank", 
								array( 
									'rank_sort' => (int)$row['rank_sort'], 
									'rank_eqv' => (int)$row['rank_eqv'], 
									'rank_short' => $row['rank_short'], 
									'rank_long' => $row['rank_long'],
									'rank_applies_to' => $rank_corps_bitmask,
									'rank_status' => (int)$row['rank_status']
								) 
							);
							
							
							break;
						default:
							throw new WPNZCFCNExceptionBadData(sprintf(__('Unknown file datatype: "%s"','nzcf-cadetnet'),$_POST['datatype']));
					}
				}
				echo '<p>'.__('Import complete','nzcf-cadetnet').'</p>';
				
			} catch( Exception $Ex ) {
				echo '<h2>'.__('Bad upload', 'nzcf-cadetnet').'</h2>';
				echo '<p>'.__('Server reported:','nzcf-cadetnet').'</pre>';
				echo '<pre>'.$Ex->toString().'</pre>';
			} 
		}
	}
	
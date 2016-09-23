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
	
	function cadetnet_admin_menu_course() {
		
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<h2>'.__('Courses','nzcf-cadetnet').'</h2>';
		
		global $wpdb;
		
		// Before we extract our data from the DB for display, let's update our records, 
		// so the DB pull will reflect what's actually in there
		if( isset($_POST['course_ids']) && is_array($_POST['course_ids']) ) {
			foreach( $_POST['course_ids'] as $course_id ) {
				// The nzcf_corps is a bitmask, so calculate it.
				$nzcf_corps = 0;
				if( isset($_POST['nzcf_corps_'.$course_id.'_atc']) && $_POST['nzcf_corps_'.$course_id.'_atc'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_ATC;
				} 
				if( isset($_POST['nzcf_corps_'.$course_id.'_corps']) && $_POST['nzcf_corps_'.$course_id.'_corps'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_NZCC;
				} 
				if( isset($_POST['nzcf_corps_'.$course_id.'_sea']) && $_POST['nzcf_corps_'.$course_id.'_sea'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_SCC;
				}  
				if( isset($_POST['nzcf_corps_'.$course_id.'_civ']) && $_POST['nzcf_corps_'.$course_id.'_civ'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_RANK_CIVILIAN;
				} 
				if( isset($_POST['nzcf_corps_'.$course_id.'_rf']) && $_POST['nzcf_corps_'.$course_id.'_rf'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_REGULAR_FORCES;
				} 
				
				$personnel = 0;
				if( isset($_POST['personnel_'.$course_id.'_officer']) && $_POST['personnel_'.$course_id.'_officer'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_OFFICER;
				} 
				if( isset($_POST['personnel_'.$course_id.'_under_officer']) && $_POST['personnel_'.$course_id.'_under_officer'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_UNDER_OFFICER;
				} 
				if( isset($_POST['personnel_'.$course_id.'_nco']) && $_POST['personnel_'.$course_id.'_nco'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_NCO;
				} 
				if( isset($_POST['personnel_'.$course_id.'_cadet']) && $_POST['personnel_'.$course_id.'_cadet'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_CADET;
				} 
				if( isset($_POST['personnel_'.$course_id.'_civilian']) && $_POST['personnel_'.$course_id.'_civilian'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_CIVILIAN;
				} 
				if( isset($_POST['personnel_'.$course_id.'_junior']) && $_POST['personnel_'.$course_id.'_junior'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_LEVEL_JUNIOR;
				} 
				if( isset($_POST['personnel_'.$course_id.'_senior']) && $_POST['personnel_'.$course_id.'_senior'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_LEVEL_SENIOR;
				} 
				
				// User data handling done by the update function, not our problem.
				$wpdb->update( 
					$wpdb->prefix."wpnzcfcn_course", 
					array( 
						'course_name' => $_POST['course_name_'.$course_id],
						'nzcf_corps' => $nzcf_corps,
						'personnel' => $personnel
					), 
					array( 'course_id' => $course_id ), 
					array( 
						'%s',
						'%d',
						'%d'
					), 
					array( '%d' ) 
				);
			}
			if( strlen(trim($_POST['course_name_0'])) ) {
				// The nzcf_corps is a bitmask, so calculate it.
				$nzcf_corps = 0;
				if( isset($_POST['nzcf_corps_0_atc']) && $_POST['nzcf_corps_0_atc'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_ATC;
				} 
				if( isset($_POST['nzcf_corps_0_corps']) && $_POST['nzcf_corps_0_corps'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_NZCC;
				} 
				if( isset($_POST['nzcf_corps_0_sea']) && $_POST['nzcf_corps_0_sea'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_SCC;
				}  
				if( isset($_POST['nzcf_corps_0_civ']) && $_POST['nzcf_corps_0_civ'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_RANK_CIVILIAN;
				} 
				if( isset($_POST['nzcf_corps_0_rf']) && $_POST['nzcf_corps_0_rf'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_REGULAR_FORCES;
				} 
				
				$personnel = 0;
				if( isset($_POST['personnel_'.$course_id.'_officer']) && $_POST['personnel_'.$course_id.'_officer'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_OFFICER;
				} 
				if( isset($_POST['personnel_'.$course_id.'_under_officer']) && $_POST['personnel_'.$course_id.'_under_officer'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_UNDER_OFFICER;
				} 
				if( isset($_POST['personnel_'.$course_id.'_nco']) && $_POST['personnel_'.$course_id.'_nco'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_NCO;
				} 
				if( isset($_POST['personnel_'.$course_id.'_cadet']) && $_POST['personnel_'.$course_id.'_cadet'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_CADET;
				} 
				if( isset($_POST['personnel_'.$course_id.'_civilian']) && $_POST['personnel_'.$course_id.'_civilian'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_CIVILIAN;
				} 
				if( isset($_POST['personnel_'.$course_id.'_junior']) && $_POST['personnel_'.$course_id.'_junior'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_LEVEL_JUNIOR;
				} 
				if( isset($_POST['personnel_'.$course_id.'_senior']) && $_POST['personnel_'.$course_id.'_senior'] ) {
					$personnel = $personnel | WPNZCFCN_PERSONNEL_GROUP_LEVEL_SENIOR;
				} 
				
				
				// User data handling done by the update function, not our problem.
				$wpdb->insert( 
					$wpdb->prefix."wpnzcfcn_course", 
					array( 
						'course_name' => $_POST['course_name_0'],
						'nzcf_corps' => $nzcf_corps,
						'personnel' => $personnel
					), 
					array( 
						'%s',
						'%d',
						'%d'
					)
				);
			}
			echo '<h3>'.__('Saved','nzcf-cadetnet').'</h3>';
		}
		
		
		$response = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT 
				* 
			FROM 
				".$wpdb->prefix."wpnzcfcn_course
			ORDER BY 
				LOWER(course_name) ASC;",
			''
        ) );
 	   
		?>
			<form method="post">
				<table>
					<thead>	
						<tr>
							<th rowspan="2"> <?= __('Course Name','nzcf-cadetnet') ?> </th>
							<th colspan="5"> <?= __('Corps','nzcf-cadetnet') ?> </th>
							<th colspan="7"> <?= __('Available for','nzcf-cadetnet') ?> </th>
						</tr>
						<tr>
							<th> <?= __('Cadet','nzcf-cadetnet') ?> </th>
							<th> <?= __('ATC','nzcf-cadetnet') ?> </th>
							<th> <?= __('Sea','nzcf-cadetnet') ?> </th>
							<th> <?= __('Civ','nzcf-cadetnet') ?> </th>
							<th> <?= __('Reg. F','nzcf-cadetnet') ?> </th>
							
							<th> <?= __('OFF','nzcf-cadetnet') ?> </th>
							<th> <?= __('U/O','nzcf-cadetnet') ?> </th>
							<th> <?= __('NCO','nzcf-cadetnet') ?> </th>
							<th> <?= __('CDT','nzcf-cadetnet') ?> </th>
							<th> <?= __('CIV','nzcf-cadetnet') ?> </th>
							<th> <?= __('JNR','nzcf-cadetnet') ?> </th>
							<th> <?= __('SNR','nzcf-cadetnet') ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach( $response as $row ) {
								echo '<tr>';
								echo '	<td>';
								echo '		<input type="hidden" name="course_ids[]" value="'.$row->course_id.'" />';
								echo '		<input type="text" name="course_name_'.$row->course_id.'" value="'.htmlentities($row->course_name).'" class="course_name" maxlength="70" />';
								echo '	</td>';
								
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->course_id.'_corps" id="nzcf_corps_'.$row->course_id.'_corps" value="1" '.($row->nzcf_corps&WPNZCFCN_CADETS_NZCC?' checked="checked"':'').' class="corps" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->course_id.'_atc" id="nzcf_corps_'.$row->course_id.'_atc" value="1" '.($row->nzcf_corps&WPNZCFCN_CADETS_ATC?' checked="checked"':'').' class="atc" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->course_id.'_sea" id="nzcf_corps_'.$row->course_id.'_sea" value="1" '.($row->nzcf_corps&WPNZCFCN_CADETS_SCC?' checked="checked"':'').' class="sea" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->course_id.'_civ" id="nzcf_corps_'.$row->course_id.'_civ" value="1" '.($row->nzcf_corps&WPNZCFCN_RANK_CIVILIAN?' checked="checked"':'').' class="civilian" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->course_id.'_rf" id="nzcf_corps_'.$row->course_id.'_rf" value="1" '.($row->nzcf_corps&WPNZCFCN_REGULAR_FORCES?' checked="checked"':'').' class="regularforces" /> </td>';


								echo '	<td align="center"> <input type="checkbox" name="personnel_'.$row->course_id.'_officer" id="personnel_'.$row->course_id.'_officer" value="1" '.($row->personnel&WPNZCFCN_PERSONNEL_GROUP_OFFICER?' checked="checked"':'').' class="officer" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="personnel_'.$row->course_id.'_under_officer" id="personnel_'.$row->course_id.'_under_officer" value="1" '.($row->personnel&WPNZCFCN_PERSONNEL_GROUP_UNDER_OFFICER?' checked="checked"':'').' class="under_officer" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="personnel_'.$row->course_id.'_nco" id="personnel_'.$row->course_id.'_nco" value="1" '.($row->personnel&WPNZCFCN_PERSONNEL_GROUP_NCO?' checked="checked"':'').' class="nco" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="personnel_'.$row->course_id.'_cadet" id="personnel_'.$row->course_id.'_cadet" value="1" '.($row->personnel&WPNZCFCN_PERSONNEL_GROUP_CADET?' checked="checked"':'').' class="cadet" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="personnel_'.$row->course_id.'_civilian" id="personnel_'.$row->course_id.'_civilian" value="1" '.($row->personnel&WPNZCFCN_PERSONNEL_GROUP_CIVILIAN?' checked="checked"':'').' class="civilian" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="personnel_'.$row->course_id.'_junior" id="personnel_'.$row->course_id.'_junior" value="1" '.($row->personnel&WPNZCFCN_PERSONNEL_GROUP_LEVEL_JUNIOR?' checked="checked"':'').' class="junior" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="personnel_'.$row->course_id.'_senior" id="personnel_'.$row->course_id.'_senior" value="1" '.($row->personnel&WPNZCFCN_PERSONNEL_GROUP_LEVEL_SENIOR?' checked="checked"':'').' class="senior" /> </td>';
						
								echo '</tr>';
							}
							echo '<tr>';
							echo '	<td> <input type="text" name="course_name_0" value="" class="course_name" maxlength="255" /> </td>';
							
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_corps" id="nzcf_corps_0_corps" value="1" class="corps" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_atc" id="nzcf_corps_0_atc" value="1" class="atc" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_sea" id="nzcf_corps_0_sea" value="1" class="sea" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_civ" id="nzcf_corps_0_civ" value="1" class="civilian" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_rf" id="nzcf_corps_0_rf" value="1" class="regularforces" /> </td>';

							echo '	<td align="center"> <input type="checkbox" name="personnel_0_officer" id="personnel_0_officer" value="1" class="officer" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="personnel_0_under_officer" id="personnel_0_under_officer" value="1" class="under_officer" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="personnel_0_nco" id="personnel_0_nco" value="1" class="nco" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="personnel_0_cadet" id="personnel_0_cadet" value="1" class="cadet" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="personnel_0_civilian" id="personnel_0_civilian" value="1" class="civlian" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="personnel_0_junior" id="personnel_0_junior" value="1" class="junior" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="personnel_0_senior" id="personnel_0_senior" value="1" class="senior" /> </td>';
								
							echo '</tr>';
						?>
					</tbody>
				</table>
				<button type="submit" class="save"><?= __('Save Changes','nzcf-cadetnet') ?></button>
				<button type="cancel" class="cancel"><?= __('Cancel','nzcf-cadetnet') ?></button>
			</form>
		<?php
	}
	
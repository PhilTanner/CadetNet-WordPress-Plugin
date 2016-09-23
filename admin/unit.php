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
	
	function cadetnet_admin_menu_units() {
		
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<h2>'.__('Units','nzcf-cadet-net').'</h2>';
		
		global $wpdb;
		
		// Before we extract our data from the DB for display, let's update our records, 
		// so the DB pull will reflect what's actually in there
		if( isset($_POST['unit_ids']) && is_array($_POST['unit_ids']) ) {
			foreach( $_POST['unit_ids'] as $unit_id ) {
				// The nzcf_corps is a bitmask, so calculate it.
				$nzcf_corps = 0;
				if( isset($_POST['nzcf_corps_'.$unit_id.'_atc']) && $_POST['nzcf_corps_'.$unit_id.'_atc'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_ATC;
				} 
				if( isset($_POST['nzcf_corps_'.$unit_id.'_corps']) && $_POST['nzcf_corps_'.$unit_id.'_corps'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_NZCC;
				} 
				if( isset($_POST['nzcf_corps_'.$unit_id.'_sea']) && $_POST['nzcf_corps_'.$unit_id.'_sea'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_SCC;
				}  
				if( isset($_POST['nzcf_corps_'.$unit_id.'_civ']) && $_POST['nzcf_corps_'.$unit_id.'_civ'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_CIVILIAN;
				} 
				if( isset($_POST['nzcf_corps_'.$unit_id.'_rf']) && $_POST['nzcf_corps_'.$unit_id.'_rf'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_REGULAR_SERVICE;
				} 
				
				$parade_night = 0;
				if( isset($_POST['parade_night_'.$unit_id.'_sun']) && $_POST['parade_night_'.$unit_id.'_sun'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_SUNDAY;
				} 
				if( isset($_POST['parade_night_'.$unit_id.'_mon']) && $_POST['parade_night_'.$unit_id.'_mon'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_MONDAY;
				} 
				if( isset($_POST['parade_night_'.$unit_id.'_tue']) && $_POST['parade_night_'.$unit_id.'_tue'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_TUESDAY;
				} 
				if( isset($_POST['parade_night_'.$unit_id.'_wed']) && $_POST['parade_night_'.$unit_id.'_wed'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_WEDNESDAY;
				} 
				if( isset($_POST['parade_night_'.$unit_id.'_thu']) && $_POST['parade_night_'.$unit_id.'_thu'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_THURSDAY;
				} 
				if( isset($_POST['parade_night_'.$unit_id.'_fri']) && $_POST['parade_night_'.$unit_id.'_fri'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_FRIDAY;
				} 
				if( isset($_POST['parade_night_'.$unit_id.'_sat']) && $_POST['parade_night_'.$unit_id.'_sat'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_SATURDAY;
				} 
				
				// User data handling done by the update function, not our problem.
				$wpdb->update( 
					$wpdb->prefix."wpnzcfcn_unit", 
					array( 
						'unit_name' => $_POST['unit_name_'.$unit_id],
						'address' => $_POST['address_'.$unit_id],
						'phone' => $_POST['phone_'.$unit_id],
						'email' => $_POST['email_'.$unit_id],
						'latitude' => $_POST['latitude_'.$unit_id],
						'longitude' => $_POST['longitude_'.$unit_id],
						'website' => $_POST['website_'.$unit_id],
						'nzcf_corps' => $nzcf_corps,
						'parade_night' => $parade_night
					), 
					array( 'unit_id' => $unit_id ), 
					array( 
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d'
					), 
					array( '%d' ) 
				);
			}
			if( strlen(trim($_POST['unit_name_0'])) ) {
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
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_CIVILIAN;
				} 
				if( isset($_POST['nzcf_corps_0_rf']) && $_POST['nzcf_corps_0_rf'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_REGULAR_SERVICE;
				} 
				
				$parade_night = 0;
				if( isset($_POST['parade_night_0_sun']) && $_POST['parade_night_'.$unit_id.'_sun'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_SUNDAY;
				} 
				if( isset($_POST['parade_night_0_mon']) && $_POST['parade_night_0_mon'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_MONDAY;
				} 
				if( isset($_POST['parade_night_0_tue']) && $_POST['parade_night_0_tue'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_TUESDAY;
				} 
				if( isset($_POST['parade_night_0_wed']) && $_POST['parade_night_0_wed'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_WEDNESDAY;
				} 
				if( isset($_POST['parade_night_0_thu']) && $_POST['parade_night_0_thu'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_THURSDAY;
				} 
				if( isset($_POST['parade_night_0_fri']) && $_POST['parade_night_0_fri'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_FRIDAY;
				} 
				if( isset($_POST['parade_night_0_sat']) && $_POST['parade_night_0_sat'] ) {
					$parade_night = $parade_night | WPNZCFCN_DAY_SATURDAY;
				} 
				
				
				// User data handling done by the update function, not our problem.
				$wpdb->insert( 
					$wpdb->prefix."wpnzcfcn_unit", 
					array( 
						'unit_name' => $_POST['unit_name_0'],
						'phone' => $_POST['phone_0'],
						'email' => $_POST['email_0'],
						'latitude' => $_POST['latitude_0'],
						'longitude' => $_POST['longitude_0'],
						'website' => $_POST['website_0'],
						'nzcf_corps' => $nzcf_corps,
						'parade_night' => $parade_night
					), 
					array( 
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d'
					)
				);
			}
			echo '<h3>'.__('Saved','nzcf-cadet-net').'</h3>';
		}
		
		
		$response = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT 
				* 
			FROM 
				".$wpdb->prefix."wpnzcfcn_unit 
			ORDER BY 
				LOWER(unit_name) ASC;",
			''
        ) );
 	   
		?>
			<form method="post">
				<table>
					<thead>	
						<tr>
							<th rowspan="2"> <?= __('Unit Name','nzcf-cadet-net') ?> </th>
							<th rowspan="2"> <?= __('Address','nzcf-cadet-net') ?> </th>
							<th rowspan="2"> <?= __('Phone','nzcf-cadet-net') ?> </th>
							<th rowspan="2"> <?= __('Email','nzcf-cadet-net') ?> </th>
							<th colspan="2"> <?= __('Location','nzcf-cadet-net') ?> </th>
							<th rowspan="2"> <?= __('Website','nzcf-cadet-net') ?> </th>
							<th colspan="5"> <?= __('Corps','nzcf-cadet-net') ?> </th>
							<th colspan="7"> <?= __('Parade Nights','nzcf-cadet-net') ?> </th>
						</tr>
						<tr>
							<th> <?= __('Lat','nzcf-cadet-net') ?> </th>
							<th> <?= __('Lng','nzcf-cadet-net') ?> </th>
							
							<th> <?= __('Cadet','nzcf-cadet-net') ?> </th>
							<th> <?= __('ATC','nzcf-cadet-net') ?> </th>
							<th> <?= __('Sea','nzcf-cadet-net') ?> </th>
							<th> <?= __('Civ','nzcf-cadet-net') ?> </th>
							<th> <?= __('Reg. F','nzcf-cadet-net') ?> </th>
							
							<th> <?= __('S','nzcf-cadet-net') ?> </th>
							<th> <?= __('M','nzcf-cadet-net') ?> </th>
							<th> <?= __('T','nzcf-cadet-net') ?> </th>
							<th> <?= __('W','nzcf-cadet-net') ?> </th>
							<th> <?= __('T','nzcf-cadet-net') ?> </th>
							<th> <?= __('F','nzcf-cadet-net') ?> </th>
							<th> <?= __('S','nzcf-cadet-net') ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach( $response as $row ) {
								echo '<tr>';
								echo '	<td>';
								echo '		<input type="hidden" name="unit_ids[]" value="'.$row->unit_id.'" />';
								echo '		<input type="text" name="unit_name_'.$row->unit_id.'" value="'.htmlentities($row->unit_name).'" class="unit_name" maxlength="255" />';
								echo '	</td>';
								echo '	<td>';
								echo '		<input type="text" name="address_'.$row->unit_id.'" id="address_'.$row->unit_id.'" value="'.htmlentities($row->address).'" maxlength="255" />';
								echo '	</td>';
								echo '	<td> <input type="tel" name="phone_'.$row->unit_id.'" id="phone_'.$row->unit_id.'" value="'.htmlentities($row->phone).'" maxlength="255" /> </td>';
								echo '	<td> <input type="email" name="email_'.$row->unit_id.'" id="email_'.$row->unit_id.'" value="'.htmlentities($row->email).'" maxlength="255" /> </td>';
								echo '	<td> <input type="number" name="latitude_'.$row->unit_id.'" id="latitude_'.$row->unit_id.'" value="'.$row->latitude.'" max="180" min="-180" /> </td>';
								echo '	<td> <input type="number" name="longitude_'.$row->unit_id.'" id="longitude_'.$row->unit_id.'" value="'.$row->longitude.'" max="180" min="-180" /> </td>';
								echo '	<td> <input type="text" name="website_'.$row->unit_id.'" id="website_'.$row->unit_id.'" value="'.htmlentities($row->website).'" maxlength="150" /> </td>';
								
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->unit_id.'_corps" id="nzcf_corps_'.$row->unit_id.'_corps" value="1" '.($row->nzcf_corps&WPNZCFCN_CADETS_NZCC?' checked="checked"':'').' class="corps" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->unit_id.'_atc" id="nzcf_corps_'.$row->unit_id.'_atc" value="1" '.($row->nzcf_corps&WPNZCFCN_CADETS_ATC?' checked="checked"':'').' class="atc" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->unit_id.'_sea" id="nzcf_corps_'.$row->unit_id.'_sea" value="1" '.($row->nzcf_corps&WPNZCFCN_CADETS_SCC?' checked="checked"':'').' class="sea" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->unit_id.'_civ" id="nzcf_corps_'.$row->unit_id.'_civ" value="1" '.($row->nzcf_corps&WPNZCFCN_CADETS_CIVILIAN?' checked="checked"':'').' class="civilian" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$row->unit_id.'_rf" id="nzcf_corps_'.$row->unit_id.'_rf" value="1" '.($row->nzcf_corps&WPNZCFCN_CADETS_REGULAR_SERVICE?' checked="checked"':'').' class="regularforces" /> </td>';


								echo '	<td align="center"> <input type="checkbox" name="parade_night_'.$row->unit_id.'_sun" id="parade_night_'.$row->unit_id.'_sun" value="1" '.($row->parade_night&WPNZCFCN_DAY_SUNDAY?' checked="checked"':'').' class="sunday" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="parade_night_'.$row->unit_id.'_mon" id="parade_night_'.$row->unit_id.'_mon" value="1" '.($row->parade_night&WPNZCFCN_DAY_MONDAY?' checked="checked"':'').' class="monday" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="parade_night_'.$row->unit_id.'_tue" id="parade_night_'.$row->unit_id.'_tue" value="1" '.($row->parade_night&WPNZCFCN_DAY_TUESDAY?' checked="checked"':'').' class="tuesday" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="parade_night_'.$row->unit_id.'_wed" id="parade_night_'.$row->unit_id.'_wed" value="1" '.($row->parade_night&WPNZCFCN_DAY_WEDNESDAY?' checked="checked"':'').' class="wednesday" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="parade_night_'.$row->unit_id.'_thu" id="parade_night_'.$row->unit_id.'_thu" value="1" '.($row->parade_night&WPNZCFCN_DAY_THURSDAY?' checked="checked"':'').' class="thursday" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="parade_night_'.$row->unit_id.'_fri" id="parade_night_'.$row->unit_id.'_fri" value="1" '.($row->parade_night&WPNZCFCN_DAY_FRIDAY?' checked="checked"':'').' class="friday" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="parade_night_'.$row->unit_id.'_sat" id="parade_night_'.$row->unit_id.'_sat" value="1" '.($row->parade_night&WPNZCFCN_DAY_SATURDAY?' checked="checked"':'').' class="saturday" /> </td>';
								
								echo '</tr>';
							}
							echo '<tr>';
							echo '	<td> <input type="text" name="unit_name_0" value="" class="unit_name" maxlength="255" /> </td>';
							echo '	<td> <input type="text" name="address_0" id="address_0" value="" maxlength="255" /> </td>';
							echo '	<td> <input type="tel" name="phone_0" id="phone_0" value="" maxlength="255" /> </td>';
							echo '	<td> <input type="email" name="email_0" id="email_0" value="" maxlength="255" /> </td>';
							echo '	<td> <input type="number" name="latitude_0" id="latitude_0" value="" max="180" min="-180" /> </td>';
							echo '	<td> <input type="number" name="longitude_0" id="longitude_0" value="" max="180" min="-180" /> </td>';
							echo '	<td> <input type="text" name="website_0" id="website_0" maxlength="150" /> </td>';
							
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_corps" id="nzcf_corps_0_corps" value="1" class="corps" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_atc" id="nzcf_corps_0_atc" value="1" class="atc" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_sea" id="nzcf_corps_0_sea" value="1" class="sea" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_civ" id="nzcf_corps_0_civ" value="1" class="civilian" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_rf" id="nzcf_corps_0_rf" value="1" class="regularforces" /> </td>';

							echo '	<td align="center"> <input type="checkbox" name="parade_night_0_sun" id="parade_night_0_sun" value="1" class="sunday" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="parade_night_0_mon" id="parade_night_0_mon" value="1" class="monday" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="parade_night_0_tue" id="parade_night_0_tue" value="1" class="tuesday" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="parade_night_0_wed" id="parade_night_0_wed" value="1" class="wednesday" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="parade_night_0_thu" id="parade_night_0_thu" value="1" class="thursday" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="parade_night_0_fri" id="parade_night_0_fri" value="1" class="friday" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="parade_night_0_sat" id="parade_night_0_sat" value="1" class="saturday" /> </td>';
								
							echo '</tr>';
						?>
					</tbody>
				</table>
				<button type="submit" class="save"><?= __('Save Changes','nzcf-cadet-net') ?></button>
				<button type="cancel" class="cancel"><?= __('Cancel','nzcf-cadet-net') ?></button>
			</form>
		<?php
	}
	
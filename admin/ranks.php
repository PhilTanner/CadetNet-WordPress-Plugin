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
	
	function cadetnet_admin_menu_ranks() {
		
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<h2>'.__('Ranks','nzcf-cadetnet').'</h2>';
		
		global $wpdb;
		
		// Before we extract our data from the DB for display, let's update our records, 
		// so the DB pull will reflect what's actually in there
		if( isset($_POST['rank_ids']) && is_array($_POST['rank_ids']) )
		{
			foreach( $_POST['rank_ids'] as $rank_id ) {
				// The rank_applies_to is a bitmask, so calculate it.
				$rank_applies_to = 0;
				if( isset($_POST['rank_applies_to_'.$rank_id.'_scc']) && $_POST['rank_applies_to_'.$rank_id.'_scc'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_CADETS_SCC;
				} 
				if( isset($_POST['rank_applies_to_'.$rank_id.'_nzcc']) && $_POST['rank_applies_to_'.$rank_id.'_nzcc'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_CADETS_NZCC;
				} 
				if( isset($_POST['rank_applies_to_'.$rank_id.'_atc']) && $_POST['rank_applies_to_'.$rank_id.'_atc'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_CADETS_ATC;
				}
				
				if( isset($_POST['rank_applies_to_'.$rank_id.'_rnzn']) && $_POST['rank_applies_to_'.$rank_id.'_rnzn'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_REGULAR_FORCE_NAVY;
				} 
				if( isset($_POST['rank_applies_to_'.$rank_id.'_army']) && $_POST['rank_applies_to_'.$rank_id.'_army'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_REGULAR_FORCE_ARMY;
				} 
				if( isset($_POST['rank_applies_to_'.$rank_id.'_rnzaf']) && $_POST['rank_applies_to_'.$rank_id.'_rnzaf'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_REGULAR_FORCE_RNZAF;
				}
				
				if( isset($_POST['rank_applies_to_'.$rank_id.'_off']) && $_POST['rank_applies_to_'.$rank_id.'_off'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_RANK_OFFICER;
				} 
				if( isset($_POST['rank_applies_to_'.$rank_id.'_cdt']) && $_POST['rank_applies_to_'.$rank_id.'_cdt'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_RANK_CADET;
				} 
				if( isset($_POST['rank_applies_to_'.$rank_id.'_civ']) && $_POST['rank_applies_to_'.$rank_id.'_civ'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_RANK_CIVILIAN;
				} 
				
				// User data handling done by the update function, not our problem.
				$wpdb->update( 
					$wpdb->prefix."wpnzcfcn_rank", 
					array( 
						'rank_sort' => $_POST['rank_sort_'.$rank_id],
						'rank_eqv' => $_POST['rank_eqv_'.$rank_id],
						'rank_short' => $_POST['rank_short_'.$rank_id],
						'rank_long' => $_POST['rank_long_'.$rank_id],
						'rank_applies_to' => $rank_applies_to,
						'rank_status' => $_POST['rank_status_'.$rank_id],
					), 
					array( 'rank_id' => $rank_id ), 
					array( 
						'%d',
						'%d',
						'%s',
						'%s',
						'%d',
						'%d'
					), 
					array( '%d' ) 
				);
			}
			if( strlen(trim($_POST['rank_long_0'])) && strlen(trim($_POST['rank_long_0'])) )
			{
				// The rank_applies_to is a bitmask, so calculate it.
				$rank_applies_to = 0;
				if( isset($_POST['rank_applies_to_0_scc']) && $_POST['rank_applies_to_0_scc'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_CADETS_SCC;
				} 
				if( isset($_POST['rank_applies_to_0_nzcc']) && $_POST['rank_applies_to_0_nzcc'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_CADETS_NZCC;
				} 
				if( isset($_POST['rank_applies_to_0_atc']) && $_POST['rank_applies_to_0_atc'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_CADETS_ATC;
				}
				
				if( isset($_POST['rank_applies_to_0_rnzn']) && $_POST['rank_applies_to_0_rnzn'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_REGULAR_FORCE_NAVY;
				} 
				if( isset($_POST['rank_applies_to_0_army']) && $_POST['rank_applies_to_0_army'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_REGULAR_FORCE_ARMY;
				} 
				if( isset($_POST['rank_applies_to_0_rnzaf']) && $_POST['rank_applies_to_0_rnzaf'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_REGULAR_FORCE_RNZAF;
				}
				
				if( isset($_POST['rank_applies_to_0_off']) && $_POST['rank_applies_to_0_off'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_RANK_OFFICER;
				} 
				if( isset($_POST['rank_applies_to_0_cdt']) && $_POST['rank_applies_to_0_cdt'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_RANK_CADET;
				} 
				if( isset($_POST['rank_applies_to_0_civ']) && $_POST['rank_applies_to_0_civ'] ) {
					$rank_applies_to = $rank_applies_to | WPNZCFCN_RANK_CIVILIAN;
				} 
				
				// User data handling done by the update function, not our problem.
				$wpdb->insert( 
					$wpdb->prefix."wpnzcfcn_rank", 
					array( 
						'rank_sort'	=> $_POST['rank_sort_'.$rank_id],
						'rank_eqv'	=> $_POST['rank_eqv_'.$rank_id],
						'rank_short'	=> $_POST['rank_short_'.$rank_id],
						'rank_long'	=> $_POST['rank_long_'.$rank_id],
						'rank_applies_to' => $rank_applies_to,
						'rank_status'	=> $_POST['rank_status_'.$rank_id],
					), 
					array( 
						'%d',
						'%d',
						'%s',
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
				".$wpdb->prefix."wpnzcfcn_rank 
			ORDER BY 
				rank_sort ASC;",
			''
		) );
 	   
		?>
			<form method="post">
				<table>
					<thead>	
						<tr>
							<th rowspan="2"> <?= __('Sort','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Equivalency','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Shortname','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Rank','nzcf-cadetnet') ?> </th>
							<th colspan="9"> <?= __('Applies To','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Status','nzcf-cadetnet') ?> </th>
						</tr>
						<tr>
							<th> <?= __('SCC','nzcf-cadetnet') ?> </th>
							<th> <?= __('NZCC','nzcf-cadetnet') ?> </th>
							<th> <?= __('ATC','nzcf-cadetnet') ?> </th>
							<th> <?= __('RNZN','nzcf-cadetnet') ?> </th>
							<th> <?= __('Army','nzcf-cadetnet') ?> </th>
							<th> <?= __('RNZAF','nzcf-cadetnet') ?> </th>
							<th> <?= __('Off.','nzcf-cadetnet') ?> </th>
							<th> <?= __('CDT','nzcf-cadetnet') ?> </th>
							<th> <?= __('Civ.','nzcf-cadetnet') ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
						
							foreach( $response as $rank ) {
								echo '<tr>';
								echo '	<td>';
								echo '		<input type="hidden" name="rank_ids[]" value="'.$rank->rank_id.'" />';
								echo '		<input type="number" name="rank_sort_'.$rank->rank_id.'" value="'.$rank->rank_sort.'" class="order" required="required" />';
								echo '	</td>';
								echo '	<td> <input type="number" name="rank_eqv_'.$rank->rank_id.'" id="rank_eqv_'.$rank->rank_id.'" value="'.$rank->rank_eqv.'" required="required" /> </td>';
								echo '	<td> <input type="text" name="rank_short_'.$rank->rank_id.'" id="rank_short_'.$rank->rank_id.'" value="'.htmlentities($rank->rank_short).'" maxlength="10" required="required" /> </td>';
								echo '	<td> <input type="text" name="rank_long_'.$rank->rank_id.'" id="rank_long_'.$rank->rank_id.'" value="'.htmlentities($rank->rank_long).'" maxlength="64" required="required" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_'.$rank->rank_id.'_scc" id="rank_applies_to_'.$rank->rank_id.'_scc" value="1" '.($rank->rank_applies_to&WPNZCFCN_CADETS_SCC?' checked="checked"':'').' class="scc" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_'.$rank->rank_id.'_nzcc" id="rank_applies_to_'.$rank->rank_id.'_nzcc" value="1" '.($rank->rank_applies_to&WPNZCFCN_CADETS_NZCC?' checked="checked"':'').' class="nzcc" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_'.$rank->rank_id.'_atc" id="rank_applies_to_'.$rank->rank_id.'_atc" value="1" '.($rank->rank_applies_to&WPNZCFCN_CADETS_ATC?' checked="checked"':'').' class="atc" /> </td>';

								echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_'.$rank->rank_id.'_rnzn" id="rank_applies_to_'.$rank->rank_id.'_rnzn" value="1" '.($rank->rank_applies_to&WPNZCFCN_REGULAR_FORCE_NAVY?' checked="checked"':'').' class="rnzn" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_'.$rank->rank_id.'_army" id="rank_applies_to_'.$rank->rank_id.'_army" value="1" '.($rank->rank_applies_to&WPNZCFCN_REGULAR_FORCE_ARMY?' checked="checked"':'').' class="army" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_'.$rank->rank_id.'_rnzaf" id="rank_applies_to_'.$rank->rank_id.'_rnzaf" value="1" '.($rank->rank_applies_to&WPNZCFCN_REGULAR_FORCE_RNZAF?' checked="checked"':'').' class="rnzaf" /> </td>';
								
								echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_'.$rank->rank_id.'_off" id="rank_applies_to_'.$rank->rank_id.'_off" value="1" '.($rank->rank_applies_to&(WPNZCFCN_RANK_OFFICER|WPNZCFCN_REGULAR_FORCES)?' checked="checked"':'').' class="civilian" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_'.$rank->rank_id.'_cdt" id="rank_applies_to_'.$rank->rank_id.'_cdt" value="1" '.($rank->rank_applies_to&WPNZCFCN_RANK_CADET?' checked="checked"':'').' class="regularforces" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_'.$rank->rank_id.'_civ" id="rank_applies_to_'.$rank->rank_id.'_civ" value="1" '.($rank->rank_applies_to&WPNZCFCN_RANK_CIVILIAN?' checked="checked"':'').' class="regularforces" /> </td>';
								
								echo '	<td> ';
								echo '		<select id="rank_status_'.$rank->rank_id.'" name="rank_status_'.$rank->rank_id.'" required="required">';
								echo '			<optgroup label="'.htmlentities(__('Enabled','nzcf-cadetnet')).'">';
								echo '				<option value="'.WPNZCFCN_STATUS_ACTIVE.'"'.($rank->status==WPNZCFCN_STATUS_ACTIVE?' selected="selected"':'').'>'.__('Active','nzcf-cadetnet').'</option>';
								echo '				<option value="'.WPNZCFCN_STATUS_PENDING.'"'.($rank->status==WPNZCFCN_STATUS_PENDING?' selected="selected"':'').'>'.__('Pending','nzcf-cadetnet').'</option>';
								echo '			</optgroup>';
								echo '			<optgroup label="'.htmlentities(__('Disabled','nzcf-cadetnet')).'">';
								echo '				<option value="'.WPNZCFCN_STATUS_INACTIVE.'"'.($rank->status==WPNZCFCN_STATUS_INACTIVE?' selected="selected"':'').'>'.__('Inactive','nzcf-cadetnet').'</option>';
								echo '				<option value="'.WPNZCFCN_STATUS_RECESS.'"'.($rank->status==WPNZCFCN_STATUS_RECESS?' selected="selected"':'').'>'.__('Recess','nzcf-cadetnet').'</option>';
								echo '				<option value="'.WPNZCFCN_STATUS_RETIRED.'"'.($rank->status==WPNZCFCN_STATUS_RETIRED?' selected="selected"':'').'>'.__('Retired','nzcf-cadetnet').'</option>';
								echo '			</optgroup>';
								echo '		</select>';
								echo '	</td>';	
								echo '</tr>';
							}
							echo '	<td> <input type="number" name="rank_sort_0" value="99999" class="order" /> </td>';
							echo '	<td> <input type="number" name="rank_eqv_0" id="rank_eqv_0" value="99999" /> </td>';
							echo '	<td> <input type="text" name="rank_short_0" id="rank_short_0" value="" maxlength="10" /> </td>';
							echo '	<td> <input type="text" name="rank_long_0" id="rank_long_0" value="" maxlength="64" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_0_scc" id="rank_applies_to_0_scc" value="1" class="scc" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_0_nzcc" id="rank_applies_to_0_nzcc" value="1" class="nzcc" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_0_atc" id="rank_applies_to_0_atc" value="1" class="atc" /> </td>';

							echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_0_rnzn" id="rank_applies_to_0_rnzn" value="1" class="rnzn" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_0_army" id="rank_applies_to_0_army" value="1" class="army" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_0_rnzaf" id="rank_applies_to_0_rnzaf" value="1" class="rnzaf" /> </td>';
							
							echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_0_off" id="rank_applies_to_0_off" value="1" class="civilian" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_0_cdt" id="rank_applies_to_0_cdt" value="1" class="regularforces" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="rank_applies_to_0_civ" id="rank_applies_to_0_civ" value="1" class="regularforces" /> </td>';
							
							echo '	<td> ';
							echo '		<select id="rank_status_0" name="rank_status_0">';
							echo '			<optgroup label="'.htmlentities(__('Enabled','nzcf-cadetnet')).'">';
							echo '				<option value="'.WPNZCFCN_STATUS_ACTIVE.'">'.__('Active','nzcf-cadetnet').'</option>';
							echo '				<option value="'.WPNZCFCN_STATUS_PENDING.'">'.__('Pending','nzcf-cadetnet').'</option>';
							echo '			</optgroup>';
							echo '			<optgroup label="'.htmlentities(__('Disabled','nzcf-cadetnet')).'">';
							echo '				<option value="'.WPNZCFCN_STATUS_INACTIVE.'" selected="selected">'.__('Inactive','nzcf-cadetnet').'</option>';
							echo '				<option value="'.WPNZCFCN_STATUS_RECESS.'">'.__('Recess','nzcf-cadetnet').'</option>';
							echo '				<option value="'.WPNZCFCN_STATUS_RETIRED.'">'.__('Retired','nzcf-cadetnet').'</option>';
							echo '			</optgroup>';
							echo '		</select>';
							echo '	</td>';	
							echo '</tr>';
						?>
					</tbody>
				</table>
				<button type="submit" class="save"><?= __('Save Changes','nzcf-cadetnet') ?></button>
				<button type="cancel" class="cancel"><?= __('Cancel','nzcf-cadetnet') ?></button>
			</form>
		<?php
	}
	
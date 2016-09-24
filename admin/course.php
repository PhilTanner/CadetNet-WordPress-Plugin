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
		echo '<h2>'.__('Course Types','nzcf-cadetnet').'</h2>';
		
		global $wpdb;
		
		// Before we extract our data from the DB for display, let's update our records, 
		// so the DB pull will reflect what's actually in there
		if( isset($_POST['course_ids']) && is_array($_POST['course_ids']) )
		{
			foreach( $_POST['course_ids'] as $course_id ) {
				// User data handling done by the update function, not our problem.
				$wpdb->update( 
					$wpdb->prefix."wpnzcfcn_course", 
					array( 
						'course_sort' => $_POST['course_sort_'.$course_id],
						'course_eqv' => $_POST['course_eqv_'.$course_id],
						'course_short' => $_POST['course_short_'.$course_id],
						'course_long' => $_POST['course_long_'.$course_id],
						'course_applies_to' => $course_applies_to,
						'course_status' => $_POST['course_status_'.$course_id],
					), 
					array( 'course_id' => $course_id ), 
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
			if( strlen(trim($_POST['course_long_0'])) && strlen(trim($_POST['course_long_0'])) )
			{
				// User data handling done by the update function, not our problem.
				$wpdb->insert( 
					$wpdb->prefix."wpnzcfcn_course", 
					array( 
						'course_sort'		=> (int)$row['course_sort_0'],
						'course_short'		=> $row['course_short_0'],
						'course_long'		=> $row['course_long_0'],
						'lead_self'		=> (int)$row['lead_self_0'],
						'lead_team'		=> (int)$row['lead_team_0'],
						'lead_leaders'		=> (int)$row['lead_leaders_0'],
						'lead_capability'	=> (int)$row['lead_capability_0'],
						'lead_systems'		=> (int)$row['lead_systems_0'],
						'course_jnco'		=> (int)$row['course_jnco_0'],
						'course_snco'		=> (int)$row['course_snco_0'],
						'course_rank_eqv'	=> (int)$row['course_rank_eqv_0'],
						'course_duration'	=> (int)$row['course_duration_0'],
						'course_sail1'		=> (int)$row['course_sail1_0'],
						'course_sail2'		=> (int)$row['course_sail2_0'],
						'course_dayskip'	=> (int)$row['course_dayskip_0'],
						'course_fore'		=> (int)$row['course_fore_0'],
						'course_main'		=> (int)$row['relationship_sort_0'],
						'course_tiller'		=> (int)$row['course_tiller_0'],
						'course_bow'		=> (int)$row['course_bow_0'],
						'course_age_min'	=> (int)$row['course_age_min_0'],
						'course_age_max'	=> (int)$row['course_age_max_0'],
						'course_attendance'	=> (int)$row['course_attendance_0'],
						'course_status'		=> (int)$row['course_status_0']
					) 
				);
				
				$wpdb->insert( 
					$wpdb->prefix."wpnzcfcn_rank", 
					array( 
						'course_sort'	=> $_POST['course_sort_'.$course_id],
						'course_eqv'	=> $_POST['course_eqv_'.$course_id],
						'course_short'	=> $_POST['course_short_'.$course_id],
						'course_long'	=> $_POST['course_long_'.$course_id],
						'course_applies_to' => $course_applies_to,
						'course_status'	=> $_POST['course_status_'.$course_id],
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
				".$wpdb->prefix."wpnzcfcn_course 
			ORDER BY 
				course_sort ASC;",
			''
		) );
 	   
		?>
			<form method="post">
				<table>
					<thead>	
						<tr>
							<th rowspan="2"> <?= __('Sort','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Shortname','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Course name','nzcf-cadetnet') ?> </th>
							<th colspan="19"> <?= __('Requirements to attend','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Status','nzcf-cadetnet') ?> </th>
						</tr>
						<tr>
							<th> <?= __('Lead Self','nzcf-cadetnet') ?> </th>
							<th> <?= __('Lead Team','nzcf-cadetnet') ?> </th>
							<th> <?= __('Lead Leaders','nzcf-cadetnet') ?> </th>
							<th> <?= __('Lead Capability','nzcf-cadetnet') ?> </th>
							<th> <?= __('Lead System','nzcf-cadetnet') ?> </th>
							<th> <?= __('JNCO','nzcf-cadetnet') ?> </th>
							<th> <?= __('SNCO','nzcf-cadetnet') ?> </th>
							<th> <?= __('Min Rank Equiv','nzcf-cadetnet') ?> </th>
							<th> <?= __('Time in Rank (days)','nzcf-cadetnet') ?> </th>
							<th> <?= __('Sail 1','nzcf-cadetnet') ?> </th>
							<th> <?= __('Sail 2','nzcf-cadetnet') ?> </th>
							<th> <?= __('Day Skippers Course','nzcf-cadetnet') ?> </th>
							<th> <?= __('Fore Sheet (6hrs)','nzcf-cadetnet') ?> </th>
							<th> <?= __('Main Sheet (6hrs)','nzcf-cadetnet') ?> </th>
							<th> <?= __('Tiller (6hrs)','nzcf-cadetnet') ?> </th>
							<th> <?= __('Bow (6hrs)','nzcf-cadetnet') ?> </th>
							<th> <?= __('Min Age @ start (days)','nzcf-cadetnet') ?> </th>
							<th> <?= __('Max Age @ end (days)','nzcf-cadetnet') ?> </th>
							<th> <?= __('Parade nights counted','nzcf-cadetnet') ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach( $response as $course ) {
								echo '<tr'.($course->course_status<1?' class="inactive"':'').'>';
								
					
											
								echo '	<td data-col="course_sort" class="number"> '.$course->course_sort.' </td>';
								echo '	<td data-col="course_short"> '.htmlentities($course->course_short).' </td>';
								echo '	<td data-col="course_long"> '.htmlentities($course->course_long).' </td>';
								
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="scc"> '.($course->lead_self?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="nzcc"> '.($course->lead_team?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->lead_leaders?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->lead_capability?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->lead_system?'✔':'').' </td>';

								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="scc"> '.($course->course_jnco?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="nzcc"> '.($course->course_snco?'✔':'').' </td>';
								
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->course_rank_eq?'✔':'').' </td>';
								echo '	<td align="center" class="number" data-col="course_applies_to" data-col2="atc"> '.($course->course_duration?'✔':'').' </td>';
								
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->course_sail1?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="scc"> '.($course->course_sail2?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="nzcc"> '.($course->course_dayskip?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->course_fore?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->course_main?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->course_tiller?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->course_bow?'✔':'').' </td>';
								
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->course_age_min?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->course_age_max?'✔':'').' </td>';

								echo '	<td align="center" class="checkbox" data-col="course_applies_to" data-col2="atc"> '.($course->course_attendance?'✔':'').' </td>';

								echo '	<td class="active_status" data-col="course_status"> ';
								switch ($course->course_status) {
									case WPNZCFCN_STATUS_ACTIVE:
										echo __('Active','nzcf-cadetnet');
										break;
									case WPNZCFCN_STATUS_INACTIVE:
										echo __('Inactive','nzcf-cadetnet');
										break;
									default:
										echo __('Unknown','nzcf-cadetnet');
								}
								echo '	</td>';
								echo '	<td class="options"> <button type="button" class="edit" data-rownum="'.$course->course_id.'">'.__('Edit','nzcf-cadnet').'</button> </td>';
								echo '</tr>';
							}
							echo '</tbody>';
					/*
							echo '<tbody class="avoid-sort">';
							echo '	<td> <input type="number" name="course_sort_0" value="99999" class="order" /> </td>';
							echo '	<td> <input type="number" name="course_eqv_0" id="course_eqv_0" value="99999" /> </td>';
							echo '	<td> <input type="text" name="course_short_0" id="course_short_0" value="" maxlength="10" /> </td>';
							echo '	<td> <input type="text" name="course_long_0" id="course_long_0" value="" maxlength="64" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="course_applies_to_0_scc" id="course_applies_to_0_scc" value="1" class="scc" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="course_applies_to_0_nzcc" id="course_applies_to_0_nzcc" value="1" class="nzcc" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="course_applies_to_0_atc" id="course_applies_to_0_atc" value="1" class="atc" /> </td>';

							echo '	<td align="center"> <input type="checkbox" name="course_applies_to_0_rnzn" id="course_applies_to_0_rnzn" value="1" class="rnzn" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="course_applies_to_0_army" id="course_applies_to_0_army" value="1" class="army" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="course_applies_to_0_rnzaf" id="course_applies_to_0_rnzaf" value="1" class="rnzaf" /> </td>';
							
							echo '	<td align="center"> <input type="checkbox" name="course_applies_to_0_off" id="course_applies_to_0_off" value="1" class="civilian" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="course_applies_to_0_cdt" id="course_applies_to_0_cdt" value="1" class="regularforces" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="course_applies_to_0_civ" id="course_applies_to_0_civ" value="1" class="regularforces" /> </td>';
							
							echo '	<td> ';
							echo '		<select id="course_status_0" name="course_status_0">';
							echo '			<option value="'.WPNZCFCN_STATUS_ACTIVE.'">'.__('Active','nzcf-cadetnet').'</option>';
							echo '			<option value="'.WPNZCFCN_STATUS_INACTIVE.'" selected="selected">'.__('Inactive','nzcf-cadetnet').'</option>';
							echo '		</select>';
							echo '	</td>';	
							echo '</tr>';
					*/
						?>
					</tbody>
				</table>
				
				<script>
					// Allow us to edit any line as we want to.
					// This is needed as there's a PHP limit for 1000 data inputs on a form, and we have more than that!
					jQuery('button.edit').click(function(){
						var id = jQuery(this).data('rownum');
						jQuery.each(jQuery(this).parents('tr').children('td').not('.options'), function(){
							var currvalue = jQuery(this).html().trim();
							var col = jQuery(this).data('col');
							var col2 = jQuery(this).data('col2');
							
							if( jQuery(this).hasClass('checkbox') ) {
								jQuery(this).html('<input type="checkbox" name="'+col+'_'+id+'_'+col2+'" id="'+col+'_'+id+'_'+col2+'" value="1"'+(currvalue?' checked="checked"':'')+' />');
							} else if( jQuery(this).hasClass('active_status') ) {
								jQuery(this).html(''+
									'<select name="'+col+'_'+id+'" id="'+col+'_'+id+'">'+
									'	<option value="<?= WPNZCFCN_STATUS_ACTIVE ?>"'+(currvalue=='<?= htmlentities(__('Active','nzcf-cadetnet')) ?>'?' selected="selected"':'')+'><?= htmlentities(__('Active','nzcf-cadetnet')) ?></option>'+
									'	<option value="<?= WPNZCFCN_STATUS_INACTIVE ?>"'+(currvalue=='<?= htmlentities(__('Inactive','nzcf-cadetnet')) ?>'?' selected="selected"':'')+'><?= htmlentities(__('Inactive','nzcf-cadetnet')) ?></option>'+
									'</select>'
								);
							} else  if( jQuery(this).hasClass('number') ) {
								jQuery(this).html('<input type="number" name="'+col+'_'+id+'" id="'+col+'_'+id+'" value="'+currvalue+'" />');
							} else {
								jQuery(this).html('<input type="text" name="'+col+'_'+id+'" id="'+col+'_'+id+'" value="'+currvalue+'" />');
							}
						});
						jQuery(this).parent().siblings('td:first-child').append('<input type="hidden" name="course_ids[]" value="'+id+'" />');
						jQuery(this).hide();
					});
				</script>
				
				<button type="submit" class="save"><?= __('Save Changes','nzcf-cadetnet') ?></button>
				<button type="cancel" class="cancel"><?= __('Cancel','nzcf-cadetnet') ?></button>
			</form>
		<?php
	}
	
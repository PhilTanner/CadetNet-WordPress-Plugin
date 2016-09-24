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
	
	function cadetnet_admin_menu_units() {
		
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<h2>'.__('Units','nzcf-cadetnet').'</h2>';
		
		global $wpdb;
		
		// Before we extract our data from the DB for display, let's update our records, 
		// so the DB pull will reflect what's actually in there
		if( isset($_POST['unit_ids']) && is_array($_POST['unit_ids']) )
		{
			foreach( $_POST['unit_ids'] as $unit_id ) {
				// The unit_corps is a bitmask, so calculate it.
				$unit_corps = 0;
				if( isset($_POST['unit_corps_'.$unit_id.'_scc']) && $_POST['unit_corps_'.$unit_id.'_scc'] ) {
					$unit_corps = $unit_corps_ | WPNZCFCN_CADETS_SCC;
				} 
				if( isset($_POST['unit_corps_'.$unit_id.'_nzcc']) && $_POST['unit_corps_'.$unit_id.'_nzcc'] ) {
					$unit_corps_ = $unit_corps_ | WPNZCFCN_CADETS_NZCC;
				} 
				if( isset($_POST['unit_corps_'.$unit_id.'_atc']) && $_POST['unit_corps_'.$unit_id.'_atc'] ) {
					$unit_corps_ = $unit_corps_ | WPNZCFCN_CADETS_ATC;
				}
				// So's the area
				$unit_area = 0;
				if( isset($_POST['unit_area_'.$unit_id.'_n']) && $_POST['unit_area_'.$unit_id.'_n'] ) {
					$unit_area = $unit_area | WPNZCFCN_AREA_NORTHERN;
				} 
				if( isset($_POST['unit_area_'.$unit_id.'_c']) && $_POST['unit_area_'.$unit_id.'_c'] ) {
					$unit_area = $unit_area | WPNZCFCN_AREA_CENTRAL;
				} 
				if( isset($_POST['unit_area_'.$unit_id.'_s']) && $_POST['unit_area_'.$unit_id.'_s'] ) {
					$unit_area = $unit_area | WPNZCFCN_AREA_SOUTHERN;
				}
								
				// User data handling done by the update function, not our problem.
				$wpdb->update( 
					$wpdb->prefix."wpnzcfcn_unit", 
					array( 
						'unit_sort' => $_POST['unit_sort_'.$unit_id],
						'unit_short' => $_POST['unit_short_'.$unit_id],
						'unit_medium' => $_POST['unit_medium_'.$unit_id],
						'unit_long' => $_POST['unit_long_'.$unit_id],
						'unit_area' => $unit_area,
						'unit_corps' => $unit_corps,
						'unit_status' => $_POST['unit_status_'.$unit_id],
					), 
					array( 'unit_id' => $unit_id ), 
					array( 
						'%d',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d'
					), 
					array( '%d' ) 
				);
			}
			if( strlen(trim($_POST['unit_long_0'])) && strlen(trim($_POST['unit_long_0'])) )
			{
				$unit_id=0;
				// The unit_corps is a bitmask, so calculate it.
				$unit_corps = 0;
				if( isset($_POST['unit_corps_'.$unit_id.'_scc']) && $_POST['unit_corps_'.$unit_id.'_scc'] ) {
					$unit_corps = $unit_corps_ | WPNZCFCN_CADETS_SCC;
				} 
				if( isset($_POST['unit_corps_'.$unit_id.'_nzcc']) && $_POST['unit_corps_'.$unit_id.'_nzcc'] ) {
					$unit_corps_ = $unit_corps_ | WPNZCFCN_CADETS_NZCC;
				} 
				if( isset($_POST['unit_corps_'.$unit_id.'_atc']) && $_POST['unit_corps_'.$unit_id.'_atc'] ) {
					$unit_corps_ = $unit_corps_ | WPNZCFCN_CADETS_ATC;
				}
				// So's the area
				$unit_area = 0;
				if( isset($_POST['unit_area_'.$unit_id.'_n']) && $_POST['unit_area_'.$unit_id.'_n'] ) {
					$unit_area = $unit_area | WPNZCFCN_AREA_NORTHERN;
				} 
				if( isset($_POST['unit_area_'.$unit_id.'_c']) && $_POST['unit_area_'.$unit_id.'_c'] ) {
					$unit_area = $unit_area | WPNZCFCN_AREA_CENTRAL;
				} 
				if( isset($_POST['unit_area_'.$unit_id.'_s']) && $_POST['unit_area_'.$unit_id.'_s'] ) {
					$unit_area = $unit_area | WPNZCFCN_AREA_SOUTHERN;
				}
								
				// User data handling done by the update function, not our problem.
				$wpdb->update( 
					$wpdb->prefix."wpnzcfcn_unit", 
					array( 
						'unit_sort' => $_POST['unit_sort_'.$unit_id],
						'unit_short' => $_POST['unit_short_'.$unit_id],
						'unit_medium' => $_POST['unit_medium_'.$unit_id],
						'unit_long' => $_POST['unit_long_'.$unit_id],
						'unit_area' => $unit_area,
						'unit_corps' => $unit_corps,
						'unit_status' => $_POST['unit_status_'.$unit_id],
					), 
					array( 'unit_id' => $unit_id ), 
					array( 
						'%d',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d'
					), 
					array( '%d' ) 
				); 
				
				// User data handling done by the update function, not our problem.
				$wpdb->insert( 
					$wpdb->prefix."wpnzcfcn_unit", 
					array( 
						'unit_sort' => $_POST['unit_sort_'.$unit_id],
						'unit_short' => $_POST['unit_short_'.$unit_id],
						'unit_medium' => $_POST['unit_medium_'.$unit_id],
						'unit_long' => $_POST['unit_long_'.$unit_id],
						'unit_area' => $unit_area,
						'unit_corps' => $unit_corps,
						'unit_status' => $_POST['unit_status_'.$unit_id],
					),  
					array( 
						'%d',
						'%s',
						'%s',
						'%s',
						'%d',
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
				".$wpdb->prefix."wpnzcfcn_unit
			ORDER BY 
				unit_sort ASC;",
			''
		) );
 	   
		?>
			<form method="post">
				<table>
					<thead>	
						<tr>
							<th rowspan="2"> <?= __('Sort','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Shortname','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Medium name','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Long name','nzcf-cadetnet') ?> </th>
							<th colspan="3"> <?= __('Area','nzcf-cadetnet') ?> </th>
							<th colspan="3"> <?= __('Corps','nzcf-cadetnet') ?> </th>
							<th rowspan="2"> <?= __('Status','nzcf-cadetnet') ?> </th>
						</tr>
						<tr>
							<th> <?= __('N','nzcf-cadetnet') ?> </th>
							<th> <?= __('C','nzcf-cadetnet') ?> </th>
							<th> <?= __('S','nzcf-cadetnet') ?> </th>
							<th> <?= __('SCC','nzcf-cadetnet') ?> </th>
							<th> <?= __('NZCC','nzcf-cadetnet') ?> </th>
							<th> <?= __('ATC','nzcf-cadetnet') ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach( $response as $unit ) {
								echo '<tr'.($unit->unit_status<1?' class="inactive"':'').'>';
								echo '	<td data-col="unit_sort" class="number"> '.$unit->unit_sort.' </td>';
								echo '	<td data-col="unit_short"> '.htmlentities($unit->unit_short).' </td>';
								echo '	<td data-col="unit_medium"> '.htmlentities($unit->unit_medium).' </td>';
								echo '	<td data-col="unit_long"> '.htmlentities($unit->unit_long).' </td>';
								
								echo '	<td align="center" class="checkbox" data-col="unit_area" data-col2="n"> '.($unit->unit_area&WPNZCFCN_AREA_NORTHERN?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="unit_area" data-col2="c"> '.($unit->unit_area&WPNZCFCN_AREA_CENTRAL?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="unit_area" data-col2="s"> '.($unit->unit_area&WPNZCFCN_AREA_SOUTHERN?'✔':'').' </td>';
								
								echo '	<td align="center" class="checkbox" data-col="unit_corps" data-col2="scc"> '.($unit->unit_corps&WPNZCFCN_CADETS_SCC?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="unit_corps" data-col2="nzcc"> '.($unit->unit_corps&WPNZCFCN_CADETS_NZCC?'✔':'').' </td>';
								echo '	<td align="center" class="checkbox" data-col="unit_corps" data-col2="atc"> '.($unit->unit_corps&WPNZCFCN_CADETS_ATC?'✔':'').' </td>';
								
								echo '	<td class="active_status" data-col="unit_status"> ';
								switch ($unit->unit_status) {
									case WPNZCFCN_STATUS_ACTIVE:
										echo __('Active','nzcf-cadetnet');
										break;
									case WPNZCFCN_STATUS_INACTIVE:
										echo __('Inactive','nzcf-cadetnet');
										break;
									case WPNZCFCN_STATUS_DISBANDED:
										echo __('Disbanded','nzcf-cadetnet');
										break;
									case WPNZCFCN_STATUS_RECESS:
										echo __('Recess','nzcf-cadetnet');
										break;
									case WPNZCFCN_STATUS_UNRECOGNISED:
										echo __('Unrecognised','nzcf-cadetnet');
										break;
									default:
										echo __('Unknown','nzcf-cadetnet');
								}
								
								echo '	</td>';
								echo '	<td class="options"> <button type="button" class="edit" data-rownum="'.$unit->unit_id.'">'.__('Edit','nzcf-cadnet').'</button> </td>';
								echo '</tr>';
							}
							echo '</tbody>';
							echo '<tbody class="avoid-sort">';
							echo '<tr>';
							echo '	<td data-col="unit_sort" class="number"> <input type="number" name="unit_sort_0" id="unit_sort_0" value="99999" /> </td>';
							echo '	<td data-col="unit_short"> <input type="text" name="unit_short_0" id="unit_short_0" maxlength="12" /> </td>';
							echo '	<td data-col="unit_medium"> <input type="text" name="unit_medium_0" id="unit_medium_0" maxlength="60" /> </td>';
							echo '	<td data-col="unit_long"> <input type="text" name="unit_long_0" id="unit_long_0" maxlength="100" /> </td>';
							
							echo '	<td align="center" class="checkbox" data-col="unit_area" data-col2="n"> <input type="checkbox" name="unit_area_n" id="unit_area_n" value="1" /> </td>';
							echo '	<td align="center" class="checkbox" data-col="unit_area" data-col2="c"> <input type="checkbox" name="unit_area_c" id="unit_area_c" value="1" /> </td>';
							echo '	<td align="center" class="checkbox" data-col="unit_area" data-col2="s"> <input type="checkbox" name="unit_area_s" id="unit_area_s" value="1" /> </td>';
							
							echo '	<td align="center" class="checkbox" data-col="unit_corps" data-col2="scc"> <input type="checkbox" name="unit_corps_scc" id="unit_corps_scc" value="1" /> </td>';
							echo '	<td align="center" class="checkbox" data-col="unit_corps" data-col2="nzcc"> <input type="checkbox" name="unit_corps_nzcc" id="unit_corps_nzcc" value="1" /> </td>';
							echo '	<td align="center" class="checkbox" data-col="unit_corps" data-col2="atc"> <input type="checkbox" name="unit_corps_atc" id="unit_corps_atc" value="1" /> </td>';
							
							echo '	<td class="active_status" data-col="unit_status"> ';
							echo '		<select name="unit_status_0" id="unit_status_0">';
							echo '			<optgroup label="'. htmlentities(__('Active','nzcf-cadetnet')).'">';
							echo '				<option value="'.WPNZCFCN_STATUS_ACTIVE.'">'.htmlentities(__('Active','nzcf-cadetnet')).'</option>';
							echo '				<option value="'.WPNZCFCN_STATUS_UNRECOGNISED.'">'.htmlentities(__('Unrecognised','nzcf-cadetnet')).'</option>';
							echo '			</optgroup>';
							echo '			<optgroup label="'.htmlentities(__('Inactive','nzcf-cadetnet')).'">';
							echo '				<option value="'.WPNZCFCN_STATUS_INACTIVE.'">'.htmlentities(__('Inactive','nzcf-cadetnet')).'</option>';
							echo '				<option value="'.WPNZCFCN_STATUS_DISBANDED.'">'.htmlentities(__('Disbanded','nzcf-cadetnet')).'</option>';
							echo '				<option value="'.WPNZCFCN_STATUS_RECESS.'">'.htmlentities(__('Recess','nzcf-cadetnet')).'</option>';
							echo '			</optgroup>';
							echo '		</select>';
							echo '	</td>';
							echo '	<td class="options"> <button type="button" class="edit" data-rownum="'.$unit->unit_id.'">'.__('Edit','nzcf-cadnet').'</button> </td>';
							echo '</tr>';
						
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
									'	<optgroup label="<?= htmlentities(__('Active','nzcf-cadetnet'))?>">'+
									'		<option value="<?= WPNZCFCN_STATUS_ACTIVE ?>"'+(currvalue=='<?= htmlentities(__('Active','nzcf-cadetnet')) ?>'?' selected="selected"':'')+'><?= htmlentities(__('Active','nzcf-cadetnet')) ?></option>'+
									'		<option value="<?= WPNZCFCN_STATUS_UNRECOGNISED ?>"'+(currvalue=='<?= htmlentities(__('Unrecognised','nzcf-cadetnet')) ?>'?' selected="selected"':'')+'><?= htmlentities(__('Unrecognised','nzcf-cadetnet')) ?></option>'+
									'	</optgroup>'+
									'	<optgroup label="<?= htmlentities(__('Inactive','nzcf-cadetnet'))?>">'+
									'		<option value="<?= WPNZCFCN_STATUS_INACTIVE ?>"'+(currvalue=='<?= htmlentities(__('Inactive','nzcf-cadetnet')) ?>'?' selected="selected"':'')+'><?= htmlentities(__('Inactive','nzcf-cadetnet')) ?></option>'+
									'		<option value="<?= WPNZCFCN_STATUS_DISBANDED ?>"'+(currvalue=='<?= htmlentities(__('Disbanded','nzcf-cadetnet')) ?>'?' selected="selected"':'')+'><?= htmlentities(__('Disbanded','nzcf-cadetnet')) ?></option>'+
									'		<option value="<?= WPNZCFCN_STATUS_RECESS ?>"'+(currvalue=='<?= htmlentities(__('Recess','nzcf-cadetnet')) ?>'?' selected="selected"':'')+'><?= htmlentities(__('Recess','nzcf-cadetnet')) ?></option>'+
									'	</optgroup>'+
									'</select>'
								);
							} else  if( jQuery(this).hasClass('number') ) {
								jQuery(this).html('<input type="number" name="'+col+'_'+id+'" id="'+col+'_'+id+'" value="'+currvalue+'" />');
							} else {
								jQuery(this).html('<input type="text" name="'+col+'_'+id+'" id="'+col+'_'+id+'" value="'+currvalue+'" />');
							}
						});
						jQuery(this).parent().siblings('td:first-child').append('<input type="hidden" name="unit_ids[]" value="'+id+'" />');
						jQuery(this).hide();
					});
				</script>
				
				
				<button type="submit" class="save"><?= __('Save Changes','nzcf-cadetnet') ?></button>
				<button type="cancel" class="cancel"><?= __('Cancel','nzcf-cadetnet') ?></button>
			</form>
		<?php
	}
	
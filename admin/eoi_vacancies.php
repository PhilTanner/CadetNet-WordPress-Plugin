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
	
	function cadetnet_admin_menu_eoi_vacancies() {
		
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		echo '<h2>'.__('Expression of Interest Vacancies','nzcf-cadet-net').'</h2>';
		
		global $wpdb;
		
		// Before we extract our data from the DB for display, let's update our records, 
		// so the DB pull will reflect what's actually in there
		if( isset($_POST['vacancy_ids']) && is_array($_POST['vacancy_ids']) )
		{
			
			foreach( $_POST['vacancy_ids'] as $vacancy_id ) {
				// The nzcf_corps is a bitmask, so calculate it.
				$nzcf_corps = 0;
				if( isset($_POST['nzcf_corps_'.$vacancy_id.'_atc']) && $_POST['nzcf_corps_'.$vacancy_id.'_atc'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_ATC;
				} 
				if( isset($_POST['nzcf_corps_'.$vacancy_id.'_corps']) && $_POST['nzcf_corps_'.$vacancy_id.'_corps'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_CORPS;
				} 
				if( isset($_POST['nzcf_corps_'.$vacancy_id.'_sea']) && $_POST['nzcf_corps_'.$vacancy_id.'_sea'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_SEA;
				} 
				// So is the nzcf_area...
				$nzcf_area = 0;
				if( isset($_POST['nzcf_area_'.$vacancy_id.'_n']) && $_POST['nzcf_area_'.$vacancy_id.'_n'] ) {
					$nzcf_area = $nzcf_area | WPNZCFCN_AREA_NORTHERN;
				} 
				if( isset($_POST['nzcf_area_'.$vacancy_id.'_c']) && $_POST['nzcf_area_'.$vacancy_id.'_c'] ) {
					$nzcf_area = $nzcf_area | WPNZCFCN_AREA_CENTRAL;
				} 
				if( isset($_POST['nzcf_area_'.$vacancy_id.'_s']) && $_POST['nzcf_area_'.$vacancy_id.'_s'] ) {
					$nzcf_area = $nzcf_area | WPNZCFCN_AREA_SOUTHERN;
				} 
				
				// User data handling done by the update function, not our problem.
				$wpdb->update( 
					$wpdb->prefix."wpnzcfcn_vacancy", 
					array( 
						'short_desc' => $_POST['short_desc_'.$vacancy_id],
						'closing_date' => date('Y-m-d H:i:s', strtotime($_POST['closing_date_'.$vacancy_id])),
						'min_rank_id' => $_POST['min_rank_id_'.$vacancy_id],
						'nzcf_corps' => $nzcf_corps,
						'nzcf_area' => $nzcf_area
					), 
					array( 'vacancy_id' => $vacancy_id ), 
					array( 
						'%s',
						'%s',
						'%d',
						'%d',
						'%d'
					), 
					array( '%d' ) 
				);
			}
			echo '<h3>'.__('Saved','nzcf-cadet-net').'</h3>';
		}
		
		if( isset($_POST['short_desc_0']) && strlen(trim($_POST['short_desc_0'])) && strtotime($_POST['closing_date_0']) )
		{
			// The nzcf_corps is a bitmask, so calculate it.
			$nzcf_corps = 0;
			if( isset($_POST['nzcf_corps_0_atc']) && $_POST['nzcf_corps_0_atc'] ) {
				$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_ATC;
			} 
			if( isset($_POST['nzcf_corps_0_corps']) && $_POST['nzcf_corps_0_corps'] ) {
				$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_CORPS;
			} 
			if( isset($_POST['nzcf_corps_0_sea']) && $_POST['nzcf_corps_0_sea'] ) {
				$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_SEA;
			} 
			// So is the nzcf_area...
			$nzcf_area = 0;
			if( isset($_POST['nzcf_area_0_n']) && $_POST['nzcf_area_0_n'] ) {
				$nzcf_area = $nzcf_area | WPNZCFCN_AREA_NORTHERN;
			} 
			if( isset($_POST['nzcf_area_0_c']) && $_POST['nzcf_area_0_c'] ) {
				$nzcf_area = $nzcf_area | WPNZCFCN_AREA_CENTRAL;
			} 
			if( isset($_POST['nzcf_area_0_s']) && $_POST['nzcf_area_0_s'] ) {
				$nzcf_area = $nzcf_area | WPNZCFCN_AREA_SOUTHERN;
			} 
			
			// User data handling done by the update function, not our problem.
			$wpdb->insert( 
				$wpdb->prefix."wpnzcfcn_vacancy", 
				array( 
					'short_desc' => $_POST['short_desc_0'],
					'closing_date' => date('Y-m-d H:i:s', strtotime($_POST['closing_date_0'])),
					'min_rank_id' => $_POST['min_rank_id_0'],
					'nzcf_corps' => $nzcf_corps,
					'nzcf_area' => $nzcf_area,
					'posted_by_user_id' => get_current_user_id()
				), 
				array( 
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d'
				)
			);
			
			echo '<h3>'.__('Inserted','nzcf-cadet-net').'</h3>';
		}
		
		
		$response = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT 
				".$wpdb->prefix."users.display_name,
				".$wpdb->prefix."wpnzcfcn_vacancy.*,
				COUNT(".$wpdb->prefix."wpnzcfcn_vacancy_application.application_id) AS applications
			FROM 
				".$wpdb->prefix."wpnzcfcn_vacancy
				INNER JOIN ".$wpdb->prefix."users
					ON ".$wpdb->prefix."users.ID = ".$wpdb->prefix."wpnzcfcn_vacancy.posted_by_user_id
				LEFT JOIN ".$wpdb->prefix."wpnzcfcn_vacancy_application
					ON ".$wpdb->prefix."wpnzcfcn_vacancy.vacancy_id = ".$wpdb->prefix."wpnzcfcn_vacancy_application.vacancy_id
			GROUP BY
				".$wpdb->prefix."wpnzcfcn_vacancy.vacancy_id
			ORDER BY
				closing_date DESC;",
			''
        ) );
        
        $ranks = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT 
				* 
			FROM 
				".$wpdb->prefix."wpnzcfcn_rank 
			ORDER BY 
				ordering ASC;",
			''
        ) );

		?>
			<form method="post">
				<table>
					<thead>
						<tr>
							
							<th rowspan="2"> <?= __('Short Desc','nzcf-cadet-net') ?> </th>
							<th rowspan="2"> <?= __('Min Rank', 'nzcf-cadet-net') ?> </th>
							<th rowspan="2"> <?= __('Closing','nzcf-cadet-net') ?> </th>
							<th colspan="3"> <?= __('Corps','nzcf-cadet-net') ?> </th>
							<th colspan="3"> <?= __('Area','nzcf-cadet-net') ?> </th>
							<th rowspan="2"> <?= __('Apps','nzcf-cadet-net') ?> </th>
							<!-- <th rowspan="2"> <?= __('Created','nzcf-cadet-net') ?> </th> -->
						</tr>
						<tr>
							<th> <?= __('Cadet','nzcf-cadet-net') ?> </th>
							<th> <?= __('ATC','nzcf-cadet-net') ?> </th>
							<th> <?= __('Sea','nzcf-cadet-net') ?> </th>
							<th> <?= __('N','nzcf-cadet-net') ?> </th>
							<th> <?= __('C','nzcf-cadet-net') ?> </th>
							<th> <?= __('S','nzcf-cadet-net') ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach( $response as $vacancy ) {
								echo '<tr>';
								echo '	<td>';
								echo '		<input type="hidden" name="vacancy_ids[]" value="'.$vacancy->vacancy_id.'" />';
								echo '		<input type="text" name="short_desc_'.$vacancy->vacancy_id.'" id="short_desc_'.$vacancy->vacancy_id.'" value="'.htmlentities($vacancy->short_desc).'" class="vacancy short_desc" maxlength="255" />';
								echo '	</td>';
								echo '	<td>';
								echo '		<select name="min_rank_id_'.$vacancy->vacancy_id.'" id="min_rank_id_'.$vacancy->vacancy_id.'">';
								foreach( $ranks as $rank ){
									echo '			<option value="'.$rank->rank_id.'"'.($vacancy->min_rank_id==$rank->rank_id?' selected="selected"':'').'>'.htmlentities($rank->rank_shortname).'</option>';
								}
								echo '		</select>';
								echo '	</td>';
								echo '	<td>';
								echo '		<input type="datetime-local" name="closing_date_'.$vacancy->vacancy_id.'" id="closing_date_'.$vacancy->vacancy_id.'" value="'.date('Y-m-d\TH:i:s',strtotime($vacancy->closing_date)).'" />';
								echo '	</td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$vacancy->vacancy_id.'_corps" id="nzcf_corps_'.$vacancy->vacancy_id.'_corps" value="1" '.($vacancy->nzcf_corps&WPNZCFCN_CADETS_CORPS?' checked="checked"':'').' class="corps" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$vacancy->vacancy_id.'_atc" id="nzcf_corps_'.$vacancy->vacancy_id.'_atc" value="1" '.($vacancy->nzcf_corps&WPNZCFCN_CADETS_ATC?' checked="checked"':'').' class="atc" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$vacancy->vacancy_id.'_sea" id="nzcf_corps_'.$vacancy->vacancy_id.'_sea" value="1" '.($vacancy->nzcf_corps&WPNZCFCN_CADETS_SEA?' checked="checked"':'').' class="sea" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_area_'.$vacancy->vacancy_id.'_n" id="nzcf_area_'.$vacancy->vacancy_id.'_n" value="1" '.($vacancy->nzcf_area&WPNZCFCN_AREA_NORTHERN?' checked="checked"':'').' class="northern" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_area_'.$vacancy->vacancy_id.'_c" id="nzcf_area_'.$vacancy->vacancy_id.'_c" value="1" '.($vacancy->nzcf_area&WPNZCFCN_AREA_CENTRAL?' checked="checked"':'').' class="central" /> </td>';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_area_'.$vacancy->vacancy_id.'_s" id="nzcf_area_'.$vacancy->vacancy_id.'_s" value="1" '.($vacancy->nzcf_area&WPNZCFCN_AREA_SOUTHERN?' checked="checked"':'').' class="southern" /> </td>';
								echo '	<td style="text-align:center"> <button type="button" class="eoi_applications" data-vacancy="'.$vacancy->vacancy_id.'" >'.$vacancy->applications.'</button> </td>';
								echo '</tr>';
							}
							echo '<tr>';
							echo '	<td>';
							echo '		<input type="text" name="short_desc_0" value	="" class="vacancy short_desc" maxlength="255" />';
							echo '	</td>';
							echo '	<td>';
							echo '		<select name="min_rank_id_0" id="min_rank_id_0">';
							foreach( $ranks as $rank ){
								echo '			<option value="'.$rank->rank_id.'">'.htmlentities($rank->rank_shortname).'</option>';
							}
							echo '		</select>';
							echo '	</td>';
							echo '	<td>';
							echo '		<input type="datetime-local" name="closing_date_0" id="closing_date_0" />';
							echo '	</td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_corps" id="nzcf_corps_0_corps" value="1" class="corps" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_atc" id="nzcf_corps_0_atc" value="1" class="atc" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_sea" id="nzcf_corps_0_sea" value="1" class="sea" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_area_0_n" id="nzcf_area_0_n" value="1" class="northern" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_area_0_c" id="nzcf_area_0_c" value="1" class="central" /> </td>';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_area_0_s" id="nzcf_area_0_s" value="1" class="southern" /> </td>';
							//echo '	<td> '.$vacancy->created.' by '.$vacancy->display_name.' </td>';
							echo '</tr>';
						?>
					</tbody>
				</table>
				<button type="submit" class="save"><?= __('Save Changes','nzcf-cadet-net') ?></button>
				<button type="cancel" class="cancel"><?= __('Cancel','nzcf-cadet-net') ?></button>
			</form>
			
		<?php
	}
	
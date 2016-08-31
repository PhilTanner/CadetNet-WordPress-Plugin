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
	
	// First of all, we need to create a new top level menu
	// Taken from:https://codex.wordpress.org/Adding_Administration_Menus
	
	add_action( 'admin_menu', 'nzcf_cadet_net_plugin_menu' );
	
	function nzcf_cadet_net_plugin_menu(){
		// Add a new main menu level for CadetNet Admin
		add_menu_page( 
			__("NZCF CadetNet", 'nzcf-cadet-net'), // Page title
			__("CadetNet", 'nzcf-cadet-net'),       // Menu text
			"manage_options", // Capability required (Needed to save option changes to system)
			"cadet_net_menu", // Menu slug (unique name)
			"cadetnet_admin_menu" //, // Function to be called when displaying content
			//$icon_url, // The url to the icon to be used for this menu. This parameter is optional.
		);
		
		// Add sub menu items (Order will be the display order in the menu):
		add_submenu_page( 
			"cadet_net_menu", 
			__("NZCF CadetNet - Ranks", 'nzcf-cadet-net'), // Page title
			__("Ranks", 'nzcf-cadet-net'),       // Menu text
			"manage_options", // Req capability 
			"cadet_net_menu_ranks",  // Menu slug. 
			"cadetnet_admin_menu_ranks"
		);
		
		add_submenu_page( 
			"cadet_net_menu", 
			__("NZCF CadetNet - EOI Vacancies", 'nzcf-cadet-net'), // Page title
			__("EOI Vacancies", 'nzcf-cadet-net'),       // Menu text
			"manage_options", // Req capability 
			"cadet_net_menu_eoi_vacancies",  // Menu slug. 
			"cadetnet_admin_menu_eoi_vacancies"
		);
	}

	function cadetnet_admin_menu() {
		if ( !current_user_can( "manage_options" ) )  {
			wp_die( __( "You do not have sufficient permissions to access this page." ) );
		}
		echo "<h2>" . __("New Zealand Cadet Forces - CadetNet", "nzcf-cadet-net") . "</h2>";
		echo "<p>Welcome to the CadetNet WordPress plugin options. </p>";
		echo "<p>Congratulations on being trusted enough to be an admin ;) </p>";
	}
	
	function cadetnet_admin_menu_ranks() {
		
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<h2>'.__('Ranks','nzcf-cadet-net').'</h2>';
		
		global $wpdb;
		
		// Before we extract our data from the DB for display, let's update our records, 
		// so the DB pull will reflect what's actually in there
		if( isset($_POST['rank_ids']) && is_array($_POST['rank_ids']) )
		{
			foreach( $_POST['rank_ids'] as $rank_id ) {
				// The nzcf_corps is a bitmask, so calculate it.
				$nzcf_corps = 0;
				if( isset($_POST['nzcf_corps_'.$rank_id.'_atc']) && $_POST['nzcf_corps_'.$rank_id.'_atc'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_ATC;
				} 
				if( isset($_POST['nzcf_corps_'.$rank_id.'_corps']) && $_POST['nzcf_corps_'.$rank_id.'_corps'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_CORPS;
				} 
				if( isset($_POST['nzcf_corps_'.$rank_id.'_sea']) && $_POST['nzcf_corps_'.$rank_id.'_sea'] ) {
					$nzcf_corps = $nzcf_corps | WPNZCFCN_CADETS_SEA;
				} 
				// User data handling done by the update function, not our problem.
				$wpdb->update( 
					$wpdb->prefix."wpnzcfcn_rank", 
					array( 
						'rank' => $_POST['rank_'.$rank_id],
						'rank_shortname' => $_POST['rank_shortname_'.$rank_id],
						'ordering' => $_POST['rank_ordering_'.$rank_id],
						'nzcf20_order' => $_POST['nzcf20_order_'.$rank_id],
						'nzcf_corps' => $nzcf_corps
					), 
					array( 'rank_id' => $rank_id ), 
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
			if( strlen(trim($_POST['rank_0'])) && strlen(trim($_POST['rank_shortname_0'])) )
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
				// User data handling done by the update function, not our problem.
				$wpdb->insert( 
					$wpdb->prefix."wpnzcfcn_rank", 
					array( 
						'rank' => $_POST['rank_0'],
						'rank_shortname' => $_POST['rank_shortname_0'],
						'ordering' => $_POST['rank_ordering_0'],
						'nzcf20_order' => $_POST['nzcf20_order_0'],
						'nzcf_corps' => $nzcf_corps
					), 
					array( 
						'%s',
						'%s',
						'%d',
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
				".$wpdb->prefix."wpnzcfcn_rank 
			WHERE 
				LOWER(rank_shortname) LIKE %s 
				OR LOWER(rank) LIKE %s
			ORDER BY 
				ordering ASC;",
			'%'.$wpdb->esc_like($keywords).'%',
			'%'.$wpdb->esc_like($keywords).'%'
        ) );
 	   
		?>
			<form method="post">
				<table>
					<thead>	
						<tr>
							<th rowspan="2"> <?= __('Rank Name','nzcf-cadet-net') ?> </th>
							<th rowspan="2"> <?= __('Shortname','nzcf-cadet-net') ?> </th>
							<th colspan="3"> <?= __('Corps','nzcf-cadet-net') ?> </th>
							<th rowspan="2"> <?= __('NZCF20 order','nzcf-cadet-net') ?> </th>
						</tr>
						<tr>
							<th> <?= __('Cadet','nzcf-cadet-net') ?> </th>
							<th> <?= __('ATC','nzcf-cadet-net') ?> </th>
							<th> <?= __('Sea','nzcf-cadet-net') ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach( $response as $rank ) {
								echo '<tr>';
								echo '	<td>';
								echo '		<input type="hidden" name="rank_ids[]" value="'.$rank->rank_id.'" />';
								echo '		<input type="hidden" name="rank_ordering_'.$rank->rank_id.'" value="'.$rank->ordering.'" />';
								echo '		<input type="text" name="rank_'.$rank->rank_id.'" id="rank_'.$rank->rank_id.'" value="'.htmlentities($rank->rank).'" maxlength="70" />';
								echo '	</td>';
								echo '	<td> <input type="text" name="rank_shortname_'.$rank->rank_id.'" id="rank_shortname_'.$rank->rank_id.'" value="'.htmlentities($rank->rank_shortname).'" maxlength="10" /> ';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$rank->rank_id.'_corps" id="nzcf_corps_'.$rank->rank_id.'_corps" value="1" '.($rank->nzcf_corps&WPNZCFCN_CADETS_CORPS?' checked="checked"':'').' /> ';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$rank->rank_id.'_atc" id="nzcf_corps_'.$rank->rank_id.'_atc" value="1" '.($rank->nzcf_corps&WPNZCFCN_CADETS_ATC?' checked="checked"':'').' /> ';
								echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_'.$rank->rank_id.'_sea" id="nzcf_corps_'.$rank->rank_id.'_sea" value="1" '.($rank->nzcf_corps&WPNZCFCN_CADETS_SEA?' checked="checked"':'').' /> ';
								echo '	<td> <input type="number" name="nzcf20_order_'.$rank->rank_id.'" id="nzcf20_order_'.$rank->rank_id.'" value="'.$rank->nzcf20_order.'" /> ';	
								echo '</tr>';
							}
							echo '<tr>';
							echo '	<td>';
							echo '		<input type="hidden" name="rank_ordering_0" value="1000" />';
							echo '		<input type="text" name="rank_0" id="rank_0" value="" maxlength="70" />';
							echo '	</td>';
							echo '	<td> <input type="text" name="rank_shortname_0" id="rank_shortname_0" value="" maxlength="10" /> ';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_corps" id="nzcf_corps_0_corps" value="1" /> ';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_atc" id="nzcf_corps_0_atc" value="1" /> ';
							echo '	<td align="center"> <input type="checkbox" name="nzcf_corps_0_sea" id="nzcf_corps_0_sea" value="1" /> ';
							echo '	<td> <input type="number" name="nzcf20_order_0" id="nzcf20_order_0" value="100" /> ';
							echo '</tr>';
						?>
					</tbody>
				</table>
				<button type="submit"><?= __('Save Changes','nzcf-cadet-net') ?></button>
			</form>
		<?php
	}
	
	
	function cadetnet_admin_menu_eoi_vacancies() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<h2>'.__('<acronym title="Expression of Interest">EOI</acronym> Vacancies','nzcf-cadet-net').'</h2>';
	}
	
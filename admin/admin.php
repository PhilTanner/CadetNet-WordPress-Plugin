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
		
		// Add sub menu items:
		add_submenu_page( 
			"cadet_net_menu", 
			__("NZCF CadetNet - Ranks", 'nzcf-cadet-net'), // Page title
			__("Ranks", 'nzcf-cadet-net'),       // Menu text
			"manage_options", // Req capability 
			"cadet_net_menu",  // Menu slug. First one should be identical
			"cadetnet_admin_menu_ranks"
		);
	}

	function cadetnet_admin_menu() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo "<h2>".__('Test Settings','nzcf-cadet-net')."</h2>";
	}
	
	
	function cadetnet_admin_menu_ranks() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo "<h3>".__('Ranks','nzcf-cadet-net')."</h3>";
	}
	
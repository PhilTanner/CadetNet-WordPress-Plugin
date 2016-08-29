<?php
	/*			
		Plugin Name: NZCF Unit Administration
		Plugin URI:  https://github.com/PhilTanner/nzcf-unit-administration.git
		Description: This describes my plugin in a short sentence
		Version:     0.01
		Author:      Phil Tanner
		Author URI:  https://github.com/PhilTanner
		License:     GPL3
		License URI: http://www.gnu.org/licenses/gpl.html
		Domain Path: /languages
		Text Domain: nzcf-unit-administration
        
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

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );	
	
	
	if ( is_admin() ) {
    	// We are in admin mode
     	require_once( dirname(__FILE__).'/admin/nzcfadmin_admin.php' );
	}	
	
	// New Zealand Cadet Forces, as a bitmask - used for lesson training
	define( 'WPNZCFCN_CADETS_ATC',			1 );
	define( 'WPNZCFCN_CADETS_CORPS',			1 << 1 );
	define( 'WPNZCFCN_CADETS_SEA',			1 << 2 );
	
	// New Zealand Cadet Forces Areas, as a bitmask
	define( 'WPNZCFCN_AREA_NORTHERN',			1 );
	define( 'WPNZCFCN_AREA_CENTRAL',			1 << 1 );
	define( 'WPNZCFCN_AREA_SOUTHERN',			1 << 2 );
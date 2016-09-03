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
	
	jQuery(document).ready( function($){
	
		// Make any buttons ... buttons
		$('button').button().filter('[type=submit]').button({ icons: {primary: 'ui-icon-disk' } }).css({ float:'right', marginRight:'1em' });
		$('button[type=button].edit').button({ icons: {primary: 'ui-icon-pencil' }, text:false });
		
		
	});
	
	
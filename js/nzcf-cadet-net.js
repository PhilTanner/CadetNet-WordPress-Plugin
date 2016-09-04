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

		// Make our text input entry rank fields a auto-complete drop down
		// Each rank input needs to have a parent hidden input field with a matching ID, with "_id" appended, which will hold our ID field
		$('input[type=text].rank').autocomplete({
			source: "wp-admin/admin-ajax.php?action=rank",
			minLength: 0,
			focus: function( event, ui ) {
				$(this).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				// When we select an item from the list, put our friendly name in the box
				$(this).val( ui.item.label );
				// And store the value (db id) in a connected hidden form element
				$( "#"+$(this).attr('id')+"_id" ).val( ui.item.value );
				return false;
			},
			// Restrict options to what's on the list
			change: function(event,ui) {
				if (ui.item==null) {
					$(this).val('').addClass('ui-state-error');
					$( "#"+$(this).attr('id')+"_id" ).val( '' );
					$(this).focus();
				}
			}
			
		});
				
	});
	
	
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
	
	var site_url = WPURLs.site_url;

	jQuery(document).ready( function($){
	
		// Make any buttons ... buttons
		$('button').button().filter('[type=submit]').button({ icons: {primary: 'ui-icon-disk' } }).css({ float:'right', marginRight:'1em' });
		$('button[type=button].edit').button({ icons: {primary: 'ui-icon-pencil' }, text:false });
		$('button[type=button].new').button({ icons: {primary: 'ui-icon-plus' }, text:false });

		autocomplete_inputs();
		
		// Handle any AJAX errors with a friendly message popping up.
		$( document ).ajaxError( function(jqXHR, text, error) {
			// Interrupted AJAX calls return a status of 0, and we shouldn't bother users with that
			if( text.status ) {
				$('<div></div>').empty().html(text.responseText+"<pre>"+JSON.stringify(text)+"</pre>").dialog({
					title: "Error: "+text.statusText,
					modal: true,
					width: "80%",
					height:"70%",
					close: function(){ $(this).dialog("destroy"); },
					buttons: [{
						text: 'OK',
						click: function(){ $(this).dialog("destroy"); }
					}] 
				}).parent().css({ zIndex:10000 }).children().filter('.ui-dialog-titlebar').addClass('ui-state-error');
			}
		});
	});
	
	function autocomplete_inputs() {
		
		// Use jQueryUI to create autocomplete form fields.  
		// This first one is fully commented, the rest duplicate it, so aren't
		// Each autocomplete input needs to have a parent hidden input field with a matching ID, with "_id" appended, which will hold our ID field
		
		// Cadet units
		jQuery('input[type=text].cadet_unit').autocomplete({
			source: site_url+"/wp-admin/admin-ajax.php?action=unit",  // Pull our options from our JSON API
			minLength: 0, // allow users to see the full list by pressing down arrow, don't force them to type 3 matching chars (default)
			focus: function( event, ui ) {
				jQuery(this).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				// When we select an item from the list, put our friendly name in the box
				jQuery(this).val( ui.item.label );
				// And store the value (db id) in a connected hidden form element
				// If we're in a auto-increasing data-row, populate the correct one
				if( jQuery(this).data('name') && jQuery(this).parent().data('rownum') ) { 
					jQuery( "#"+jQuery(this).data('name')+"id_"+jQuery(this).parent().data('rownum') ).val( ui.item.value );
				} else {
					jQuery( "#"+jQuery(this).attr('id')+"_id" ).val( ui.item.value );
				}
				return false;
			},
			// Restrict options to what's on the list
			change: function(event,ui) {
				// If they've selected something not on this list
				if (ui.item==null) {
					// Mark it as an issue
					jQuery(this).val('').addClass('ui-state-error');
					// Clear any previously selected IDs
					if( jQuery(this).data('name') && jQuery(this).parent().data('rownum') ) { 
						jQuery( "#"+jQuery(this).data('name')+"id_"+jQuery(this).parent().data('rownum') ).val( '' );
					} else {
						jQuery( "#"+jQuery(this).attr('id')+"_id" ).val( '' );
					}
					// Bring the mouse back here
					jQuery(this).focus();
				} else {
					// If they've selected from the list, clear the error state
					jQuery(this).removeClass('ui-state-error');
				}
			}
		});
		
		// Make our courses an autocomplete drop down
		jQuery('input.course_type').autocomplete({
			source: site_url+"/wp-admin/admin-ajax.php?action=course_type",
			minLength:0,
			focus: function( event, ui ) {
				jQuery(this).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				jQuery(this).val( ui.item.label );
				if( jQuery(this).data('name') && jQuery(this).parent().data('rownum') ) { 
					jQuery( "#"+jQuery(this).data('name')+"id_"+jQuery(this).parent().data('rownum') ).val( ui.item.value );
				} else {
					jQuery( "#"+jQuery(this).attr('id')+"_id" ).val( ui.item.value );
				}
				return false;
			},
			// Restrict options to what's on the list
			change: function(event,ui) {
				if (ui.item==null) {
					jQuery(this).val('').addClass('ui-state-error');
					if( jQuery(this).data('name') && jQuery(this).parent().data('rownum') ) { 
						jQuery( "#"+jQuery(this).data('name')+"id_"+jQuery(this).parent().data('rownum') ).val( '' );
					} else {
						jQuery( "#"+jQuery(this).attr('id')+"_id" ).val( '' );
					}
					jQuery(this).focus();
				} else {
					jQuery(this).removeClass('ui-state-error');
				}
			}
		});
		
		// Make our text input entry rank fields a auto-complete drop down
		jQuery('input[type=text].rank').autocomplete({
			source: site_url+"/wp-admin/admin-ajax.php?action=rank",
			minLength: 0,
			focus: function( event, ui ) {
				jQuery(this).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				jQuery(this).val( ui.item.label );
				if( jQuery(this).data('name') && jQuery(this).parent().data('rownum') ) { 
					jQuery( "#"+jQuery(this).data('name')+"id_"+jQuery(this).parent().data('rownum') ).val( ui.item.value );
				} else {
					jQuery( "#"+jQuery(this).attr('id')+"_id" ).val( ui.item.value );
				}
				return false;
			},
			// Restrict options to what's on the list
			change: function(event,ui) {
				if (ui.item==null) {
					jQuery(this).val('').addClass('ui-state-error');
					if( jQuery(this).data('name') && jQuery(this).parent().data('rownum') ) { 
						jQuery( "#"+jQuery(this).data('name')+"id_"+jQuery(this).parent().data('rownum') ).val( '' );
					} else {
						jQuery( "#"+jQuery(this).attr('id')+"_id" ).val( '' );
					}
					jQuery(this).focus();
				} else {
					jQuery(this).removeClass('ui-state-error');
				}
			}
		});
		// And our select versions
		if( jQuery('select.rank').length ) {
			jQuery.ajax({
				url: site_url+'/wp-admin/admin-ajax.php?action=rank',
				dataType: 'json'
			}).done( function(json, text) { 
				jQuery('select.rank').each(function(){
					var select = jQuery(this);
					// If we've already selected something in this list, don't clear & reset it
					if( select.val() === null ) {
						select.empty();
						select.append('<option value=""></option>');
						jQuery.each( json, function(i, item){
							select.append('<option value="' + json[i].value + '">'+ json[i].label + '</option>');
						});
						// If we've already populated our form data before this point, update this input to match
						if( select.data('prepopulated_value') ) {
							select.val(select.data('prepopulated_value'));
						}
					}
				});
			});
		}
		
		// Make our expanding containers "expand" when we enter data to provide new empty rows
		jQuery('div.container .datarow input, div.container .datarow textarea, div.container .datarow select').change(function(){
			// Only create a new row if the last row already there isn't blank
			if( jQuery(this).parent().parent().children('.datarow:last-child').children('input:first-child').val() != "" ) {
				duplicateparentaldatarow( jQuery(this) );
			}
		});
		// Give the add button the same abilities (and re-show the last row's add new row button)
		jQuery('.datarow button[type=button].new').click(function(){ duplicateparentaldatarow( jQuery(this) ); }).show();
		
	}
	
	function duplicateparentaldatarow( object ) {
		var newrownum = object.parent().parent().children('.datarow:last-child').data('rownum')+1;
		// Do our duplication, updating our IDs and names, and resetting what we've entered into previous rows
		object.parent().clone().attr('data-rownum', newrownum).appendTo( object.parent().parent() ).children('input, select, textarea').each(function(){
			jQuery(this).attr({ 
				id: ""+jQuery(this).data('name')+newrownum,
				name: ""+jQuery(this).data('name')+newrownum 
			}).val('');
		});
		// Remove the Add New row functionality button
		object.parent().children('button.new').hide();
				
		// Create our new autocompletes
		autocomplete_inputs();
	}
	
	// If we're in debug mode....
	if( parseInt(WPURLs.debug) ) {
		// General JavaScript error alert
		window.onerror = function (errorMsg, url, lineNumber, column, errorObj) {
 		   alert('Error: ' + errorMsg + '\nScript: ' + url + '\nLine: ' + lineNumber
  		  + '\nColumn: ' + column + '\nStackTrace: ' +  errorObj);
		}
	}
	
	
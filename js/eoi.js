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
	
		// Prepend our Part numbers to the form.
		var i=0;
		$('legend').each(function(){
			i++;
			$(this).html("<strong>Part "+i+":</strong> "+$(this).html());
		});
		
		// Make our appointments an autocomplete drop down
		$('#vacancy_description').autocomplete({
			source:site_url+"/wp-admin/admin-ajax.php?action=eoi_positions",
			minLength:0,
			focus: function( event, ui ) {
				$( "#vacancy_description" ).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				$( "#vacancy_description" ).val( ui.item.label );
				$( "#vacancy_id" ).val( ui.item.value );
				$( "#rank" ).val( ui.item.ranks );
				$( "#application_closes").val( ui.item.closing_date );
				return false;
			},
			// Restrict options to what's on the list
			change: function(event,ui) {
				if (ui.item==null) {
					jQuery(this).val('').addClass('ui-state-error');
					jQuery( "#"+jQuery(this).attr('id')+"_id" ).val( '' );
					jQuery(this).focus();
				} else {
					jQuery(this).removeClass('ui-state-error');
				}
			}
		});
		
		
		// Take our data and put it into our form to allow the next stage editing
		function populateFormValues( arr )
		{
			$.each(arr, function(key, value){
				// Null values are empty placeholders, so nothing to do.
				if( value == null ) return;
				// If we have an object, it's complex, so recursively call ourself
				if( (typeof value === "object") && !Array.isArray(value) ) {
					populateFormValues(value);
				// We have an array object
				} else if(Array.isArray(value) ) {
					
					// Make sure we create enough rows to hold all our data
					for( var i=$('#'+key+' .datarow').length; i<value.length; i++ )
					{
						$('#'+key+' .datarow:first-child').clone({ withDataAndEvents: true }).attr('data-rownum', (i+1)).appendTo( $('#'+key) ).children('input').each(function(){
							$(this).attr('id', $(this).data('name')+(i+1) ).attr('name', $(this).data('name')+(i+1) ).val("");
						});
					}
					// Then run through each step populating the data.
					// Because we're calling our selves, this allows for infinite nesting...
					for( var i=0; i<value.length; i++ ) {
						populateFormValues(value[i]);
					}
					
				} else {
					// Copy in our value
					$('#'+key).val(value);
					// If we have something in there, make the field display only, don't allow edits, and don't pass thru the data
					if(value!="null" && ( value.length || value )) {
						$('#'+key).attr('readonly','readonly').attr('disabled','disabled');
					}
				}
			});
		}
		
		
		$.ajax({
			url: site_url+'/wp-admin/admin-ajax.php?action=eoi_application&eoi_id='+eoi_id,
			dataType: 'json'
		}).done( function(json, text) { 
			
			populateFormValues( json );
			
			// And now we've populated our data, hide any parts we don't need access to just yet 
			var parts = $('form.eoi fieldset');
			
			// We always want to show the first 5 parts - 0 indexed
			var showuntilsection = 4;
			for( var i=showuntilsection; i<parts.length; i++ ) {
				// Check if any of the form field elements have any data in them
				var showsection=false;
			
				parts.eq(i).find('input,textarea,select').each(function(){
					if( $(this).val().length ) {
						showsection=true;
						return false;
					}
				});
				// If so, we want to keep this section
				if( showsection ) {
					showuntilsection++;
				}
			}
			// And now, hide every section after the NEXT one - so we can progress thru the application
			for( var i=(showuntilsection+1); i<parts.length; i++ ) {
				parts.eq(i).hide().find('input,textarea,select').each(function(){
					$(this).attr('disabled','disabled').removeAttr('readonly');
				});
			}
		});
		
		$('form[name=eoi]').on( "submit", function( event ) {
			// stop the form submitting
			event.preventDefault();
			$('form[name=eoi] button[type="submit"]').attr('disabled','disabled').addClass('ui-state-disabled');
			
			// temporarily re-enable the fields to pass the data thru (so we can update the entire row)
			var disabled = $('[disabled=disabled]').removeAttr('disabled').removeAttr('readonly');
			$('form[name=eoi] input:hidden, form[name=eoi] textarea:hidden, form[name=eoi] select:hidden').not('[type=hidden]').attr('disabled','disabled');
			var data = $(this).serialize();
			disabled.attr('disabled','disabled').attr('readonly','readonly');
			
			$.ajax({
				url: site_url+'wp-admin/admin-ajax.php?action=eoi_application&eoi_id='+eoi_id,
				method: 'POST',
				data: data
			}).done( function(data, textStatus, jqXHR) {
				
				$('<div></div>').empty().html(data).dialog({
					title: "Saved",
					modal: false,
					close: function(){ $(this).dialog("destroy"); },
					buttons: [{
						text: 'OK',
						click: function(){ $(this).dialog("destroy"); }
					}] 
				});
			}).always( function(){ 
				$('form[name=eoi] button[type="submit"]').removeAttr('disabled').removeClass('ui-state-disabled');
			});
		});
		
	});
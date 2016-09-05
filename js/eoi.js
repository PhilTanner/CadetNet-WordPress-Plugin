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
			source:"wp-admin/admin-ajax.php?action=eoi_positions",
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
			}
		});
		
		// Make our courses an autocomplete drop down
		$('input.course').autocomplete({
			source:[
				{ value: 1, label: 'Commissioning Course' },
				{ value: 2, label: 'ITTM Course' },
				{ value: 3, label: 'Command Course' },
				{ value: 4, label: 'Range Conducting Officer Course' },
				{ value: 5, label: 'Officer Bushcraft Course' },
				{ value: 6, label: 'Maring Safety Officer Course' }
			],
			minLength:0,
			focus: function( event, ui ) {
				$(this).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				// When we select an item from the list, put our friendly name in the box
				$(this).val( ui.item.label );
				// And store the value (db id) in a connected hidden form element
				var rownum = $(this).parent().data('rownum');
				$( "#"+$(this).data('name')+"id_"+rownum ).val( ui.item.value );
				
				return false;
			}
		});
		
		// Make our expanding containers "expand" when we enter data to provide new empty rows
		$('div.container .datarow input[type=text]').change(function(){
			// Only create a new row if the last row already there isn't blank
			if( $(this).parent().parent().children('.datarow:last-child').children('input:first-child').val() != "" )
			{
				var newrownum = $(this).parent().parent().children('.datarow:last-child').data('rownum')+1;
				// Do our duplication, updating our IDs and names, and resetting what we've entered into previous rows
				$(this).parent().clone({ withDataAndEvents: true }).attr('data-rownum', newrownum).appendTo( $(this).parent().parent() ).children('input').each(function(){
					$(this).attr('id', $(this).data('name')+newrownum ).attr('name', $(this).data('name')+newrownum ).val("");
				});
			}
		});
		
		// Prepopulate the form with the progress made so far.
		var currentdata = {
			
			// Part 1
			part1: [{
				vacancy_id: 0,
				vacancy_description: "",
				rank: "",
				application_closes: ""
			}],
			
			part2: [{
				applicant_name: "",
				applicant_rank: "",
				service_number: ""
			}],
			
			part3: [{
				service: [{
					service_cadet_unit_1: "aa",
					service_start_date_1: "1970-01-01",
					service_end_date_1: "1970-01-01",
					service_appointments_held_1: "aa" 
				},{
					service_cadet_unit_2: "bb",
					service_start_date_2: "1971-02-02",
					service_end_date_2: "1971-02-02",
					service_appointments_held_2: "bb" 
				},{
					service_cadet_unit_3: "cc",
					service_start_date_3: "1972-03-03",
					service_end_date_3: "1972-03-03",
					service_appointments_held_3: "cc" 
				},{
					service_cadet_unit_4: "dd",
					service_start_date_4: "1973-04-04",
					service_end_date_4: "",
					service_appointments_held_4: "dd" 
				}],
				course: [{
					course_qual_id_1: "1",
					course_qual_1: "Commissioning Course",
					course_date_1: "2016-05-19"
				},{
					course_qual_id_2: "2",
					course_qual_2: "Range Conducting Officer",
					course_date_2: "2016-08-19",
				}],
				course_staffed: [{
					course_staffed_id_1: "",
					course_staffed_1: "",
					course_staffed_qty_1: ""
				}]
			}],
			
			part4: [{
				best_candidate_response: "I'm the best candidate because I'm Oh-4"
			}],

			part5: [{
				cv: "This is my Curriculum Vitae"
			}],
			
			part6: [{
				cucdr_recommendation: "",
				cucdr_comment: "",
				cucdr_rank: "",
				cucdr_name: "",
				cucdr_date: ""
			}],

			part7: [{
				aso_recommendation: "",
				aso_comment: "",
				aso_rank: "",
				aso_name: "",
				aso_date: ""
			}],

			part8: [{
				ac_recommendation: "",
				ac_comment: "",
				ac_rank: "",
				ac_name: "",
				ac_date: ""
			}],

			part9: [{
				comdt_approval: "",
				comdt_comment: "",
				comdt_rank: "",
				comdt_name: "",
				comdt_date: ""
			}]
			
		};
		
		
		// Take our data and put it into our form to allow the next stage editing
		function populateFormValues( arr )
		{
			$.each(arr, function(key, value){
				// We have an array object
				if(Array.isArray(value))
				{
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
					// If we have something in there, make the field display only, don't allow edits.
					if( value.length || value ) {
						$('#'+key).attr('readonly','readonly').attr('disabled','disabled');
					}
				}
			});
		}
		
		populateFormValues( currentdata );
		
		// And now we've populated our data, hide any parts we don't need access to just yet 
		var parts = $('form.eoi fieldset');
		
		// We always want to show the first 5 parts - 0 indexed
		var showuntilsection = 5;
		for( var i=showuntilsection; i<parts.length; i++ )
		{
			// Check if any of the form field elements have any data in them
			var showsection=false;
			
			parts.eq(i).find('input').each(function(){
				if( $(this).val().length )
				{
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
		for( var i=(showuntilsection+1); i<parts.length; i++ )
		{
			//parts.eq(i).hide();
		}
		
		
	});
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
	
	get_header();
	?>
	
	<style>
		
	</style>
	
	<h1>Expression of Interest </h1>
	
	<form name="eoi" class="eoi" data-id="4a">
		
		<p class="instruction"> Completed forms are to be emailed through to the Area Support Officer no later and Area Coordiator to reach NZCF HQ no later than 1200 hours on the Application close off date. </p>
		
		<fieldset>
			<legend> Position applying for </legend>
			<div>
				<label for="vacancy_description">Vacancy Description</label>
				<label for="rank">Rank</label>
				<label for="application_closes">Application closes</label>
				<br />
				<input type="hidden" name="vacancy_id" id="vacancy_id" value="" />
				<input type="text" name="vacancy_description" id="vacancy_description" required="required" />
				<input type="text" name="rank" id="rank" readonly="readonly" class="rank" />
				<input type="date" name="application_closes" id="application_closes" readonly="readonly" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend> Applicant details </legend>
			<div>
				<label for="applicant_rank"> Rank </label>
				<label for="applicant_name"> Initials and Surname </label>
				<label for="service_number"> Service number </label>
				<br />	
				<input type="text" name="applicant_rank" id="applicant_rank" class="rank" />
				<input type="text" name="applicant_name" id="applicant_name" />
				<input type="number" name="service_number" id="service_number" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend> NZ Cadet Forces Service </legend>
			<div>
				<label for="service_cadet_unit_1">Cadet Unit</label>
				<label for="service_start_date_1">Start Date</label>
				<label for="service_end_date_1">End Date</label>
				<label for="service_appts_held_1">Appointments Held</label>
			</div>
			<div class="container" id="service">
				<div class="datarow" data-rownum="1">
					<input type="text" name="service_cadet_unit_1" id="service_cadet_unit_1" class="cadet_unit" data-name="service_cadet_unit_" />
					<input type="date" name="service_start_date_1" id="service_start_date_1" max="<?= date("%Y-%m-%d") ?>" data-name="service_start_date_" />
					<input type="date" name="service_end_date_1" id="service_end_date_1" max="<?= date("%Y-%m-%d") ?>" data-name="service_end_date_" />
					<input type="text" name="service_appointments_held_1" id="service_appointments_held_1" data-name="service_appointments_held_" />
				</div>
			</div>
			<div>
				<label for="course_qual_1"> NZCF Officer Course Qualifications</label>
				<label for="course_date_1"> Date completed course </label>
			</div>
			<div class="container" id="course">
				<div class="datarow" data-rownum="1">
					<input type="hidden" name="course_qual_id_1" id="course_qual_id_1" />
					<input type="text" name="course_qual_1" id="course_qual_1" class="course" data-name="course_qual_" />
					<input type="date" name="course_date_1" id="course_date_1" max="<?= date('%Y-%m-%d') ?>" data-name="course_date_" />
				</div>
			</div>
			<div>
				<p class="instruction">List NZCF courses you have staffed</p>
				<label for="course_staffed_1"> Course Name</label>
				<label for="course_staffed_qty_1"> Number of times Staffed </label>
			</div>
			<div class="container" id="course_staffed">
				<div class="datarow" data-rownum="1">
					<input type="hidden" name="course_staffed_id_1" id="course_staffed_id_1" />
					<input type="text" name="course_staffed_1" id="course_staffed_1" class="course" data-name="course_staffed_" />
					<input type="number" name="course_staffed_qty_1" id="course_staffed_qty_1" data-name="course_staffed_date_" />
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend> Reasons for applying </legend>
			<p class="instruction">(In your own words, describe why you would be the best candidate for this role </p>
			<textarea name="best_candidate_response" id="best_candidate_response"></textarea>
		</fieldset>
		
		<fieldset>
			<legend> Curriculum Vitae </legend>
			<p class="instruction"> Provide a short summary of your NZCF and professional work history including any civilian qualifications that will assist you in this appointment.</p>
			<textarea name="cv" id="cv"></textarea>
			<p class="notice">As necessary, the AC, the Asst COMDT or COMDT may conduct further inquiries as to your suitability for the appointment. </p>
		</fieldset>

		<fieldset>
			<legend> Cadet Unit Commander Recommendation </legend>
			<div>
				<label for="cucdr_recommendation"> Recommendation </label>
				<select name="cucdr_recommendation" id="cucdr_recommendation" required="required">
					<option value="">Select</option>
					<option value="1">Recommended</option>
					<option value="0">Not Recommended</option>
				</select>
			</div>
			<div>
				<label for="cucdr_comment">Comment</label>
				<p class="instruction">Provide specific comment regarding the suitability of the candidate for the vacancy </p>
				<textarea name="cucdr_comment" id="cucdr_comment" required="required"></textarea>
			</div>
			<div>
				<label for="cucdr_rank"> Rank </label>
				<label for="cucdr_name"> Name </label>
				<label for="cucdr_date"> Date </label>
			</div>
			<div>
				<input type="text" class="rank" name="cucdr_rank" id="cucdr_rank" required="required" />
				<input type="text" name="cucdr_name" id="cucdr_name" required="required" />
				<input type="date" name="cucdr_date" id="cucdr_date" required="required" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend> Area Support Officer Recommendation </legend>
			<label for="aso_recommendation"> Recommendation </label>
			<select name="aso_recommendation" id="aso_recommendation" required="required">
				<option value="">Select</option>
				<option value="1">Recommended</option>
				<option value="0">Not Recommended</option>
			</select>
			<br />
			<label for="aso_comment">Comment</label>
			<p class="instruction">Provide specific comment regarding the suitability of the candidate for the vacancy </p>
			<textarea name="aso_comment" id="aso_comment" required="required"></textarea>
			<br />
			<label for="aso_rank"> Rank </label>
			<label for="aso_name"> Name </label>
			<label for="aso_date"> Date </label>
			<br />
			<div>
				<input type="text" class="rank" name="aso_rank" id="aso_rank" required="required" />
				<input type="text" name="aso_name" id="aso_name" required="required" />
				<input type="date" name="aso_date" id="aso_date" required="required" />
			</div>
		</fieldset>

		<fieldset>
			<legend> Area Coordinator Recommendation </legend>
			<label for="ac_recommendation"> Recommendation </label>
			<select name="ac_recommendation" id="ac_recommendation" required="required">
				<option value="">Select</option>
				<option value="1">Recommended</option>
				<option value="0">Not Recommended</option>
			</select>
			<br />
			<label for="ac_comment">Comment</label>
			<p class="instruction">Provide specific comment regarding the suitability of the candidate for the vacancy </p>
			<textarea name="ac_comment" id="ac_comment" required="required"></textarea>
			<br />
			<label for="ac_rank"> Rank </label>
			<label for="ac_name"> Name </label>
			<label for="ac_date"> Date </label>
			<br />
			<div>
				<input type="text" class="rank" name="ac_rank" id="ac_rank" required="required" />
				<input type="text" name="ac_name" id="ac_name" required="required" />
				<input type="date" name="ac_date" id="ac_date" required="required" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend> Headquarters NZCF </legend>
			<label for="comdt_approval"> COMDT Approval </label>
			<select name="comdt_approval" id="comdt_approval" required="required">
				<option value="">Select</option>
				<option value="1">Approved</option>
				<option value="0">Not Approved</option>
			</select>
			<br />
			<label for="comdt_comment">Comment</label>
			<br />
			<textarea name="comdt_comment" id="comdt_comment" required="required"></textarea>
			<br />
			<label for="comdt_rank"> Rank </label>
			<label for="comdt_name"> Name </label>
			<label for="comdt_date"> Date </label>
			<br />
			<div>
				<input type="text" class="rank" name="comdt_rank" id="comdt_rank" required="required" />
				<input type="text" name="ac_name" id="comdt_name" required="required" />
				<input type="date" name="comdt_date" id="comdt_date" required="required" />
			</div>
		</fieldset>
		<button type="submit">Save</button>
		<button type="cancel">Cancel</button>
	</form>
	
	<script>
		// Prepend our Part numbers to the form.
		var i=0;
		$('legend').each(function(){
			i++;
			$(this).html("<strong>Part "+i+":</strong> "+$(this).html());
		});
		
		// Make our rank entries a auto-complete drop down
		$('input.rank').autocomplete({
			source: ["GRPCPT","WNGCDR","SQNLDR","FLTLT","FLYOFF","PLTOFF"],
			minLength:0
		});
		
		// Make our appointments an autocomplete drop down
		$('#vacancy_description').autocomplete({
			source:[
				{ value: 1, label: 'Vacancy #1', rank: 'FLTLT', closes: '2016-08-30' },
				{ value: 2, label: 'Vacancy #2', rank: 'PLTOFF', closes: '2016-09-02' }
			],
			minLength:0,
			focus: function( event, ui ) {
				$( "#vacancy_description" ).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				$( "#vacancy_description" ).val( ui.item.label );
				$( "#vacancy_id" ).val( ui.item.value );
				$( "#rank" ).val( ui.item.rank );
				$( "#application_closes").val( ui.item.closes );
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
				vacancy_description: "Prepopulated vacancy desc",
				rank: "A/PLTOFF",
				application_closes: "2016-06-30"
			}],
			
			part2: [{
				applicant_name: "Phil Tanner",
				applicant_rank: "A/PLTOFF",
				service_number: "12345"
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
				cucdr_recommendation: 1,
				cucdr_comment: "CUCDR Comments - Approved",
				cucdr_rank: "CUCDRRANK",
				cucdr_name: "Cadet Unit Commander",
				cucdr_date: "1975-01-01"
			}],

			part7: [{
				aso_recommendation: 0,
				aso_comment: "ASO Comments - No approved",
				aso_rank: "ASO RANK",
				aso_name: "ASO name",
				aso_date: "1976-01-01"
			}],

			part8: [{
				ac_recommendation: "",
				ac_comment: "",
				ac_rank: "ACRANK",
				ac_name: "AC Name",
				ac_date: "1977-01-01"
			}],

			part9: [{
				comdt_approval: 1,
				comdt_comment: "COMDT Comments - Approved. Yay",
				comdt_rank: "COMDT rank",
				comdt_name: "Comandntantan name",
				comdt_date: "1978-01-01"
			}]
			
		};
		/* Prepopulate the form with the progress made so far.
		var currentdata = {
			
			// Part 1
			part1: [{
				vacancy_id: 0,
				vacancy_description: "Prepopulated vacancy desc",
				rank: "A/PLTOFF",
				application_closes: "2016-06-30"
			}],
			
			part2: [{
				applicant_name: "Phil Tanner",
				applicant_rank: "A/PLTOFF",
				service_number: "12345"
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
		*/
		
		// Take our data and put it into our form to allow the next stage editing
		function populateFormValues( arr )
		{
			$.each(arr, function(key, value){
				// We have an array object
				if(Array.isArray(value))
				{
					// So, first things first, let's make sure we create enough rows to hold all our data
					for( var i=$('#'+key+' .datarow').length; i<value.length; i++ )
					{
						$('#'+key+' .datarow:first-child').clone({ withDataAndEvents: true }).attr('data-rownum', (i+1)).appendTo( $('#'+key) ).children('input').each(function(){
							$(this).attr('id', $(this).data('name')+(i+1) ).attr('name', $(this).data('name')+(i+1) ).val("");
						});
					}
					// Then we can run through each step populating the data.
					// Because we're calling our selves, this allows for infinite nesting...
					for( var i=0; i<value.length; i++ )
						populateFormValues(value[i]);
				} else {
					// Copy in our value
					$('#'+key).val(value);
					// If we have something in there, make the field display only, don't allow edits.
					if( value.length || value )
						$('#'+key).attr('readonly','readonly').attr('disabled','disabled');
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
			if( showsection )
				showuntilsection++;
		}
		// And now, hide every section after the NEXT one - so we can progress thru the application
		for( var i=(showuntilsection+1); i<parts.length; i++ )
		{
			parts.eq(i).hide();
		}
		
		
		
	</script>
	
	<?= get_footer() ?>
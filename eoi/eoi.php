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
	
	<h1>Expression of Interest </h1>
	
	<form name="eoi" class="eoi" data-id="4a">
		
		<p class="instruction"> Completed forms are to be emailed through to the Area Support Officer no later and Area Coordiator to reach NZCF HQ no later than 1200 hours on the Application close off date. </p>
		
		<fieldset>
			<legend> Position applying for </legend>
			<div>
				<label for="vacancy_description">Vacancy Description</label>
				<label for="rank" class="rank">Rank</label>
				<label for="application_closes" type="date">Application closes</label>
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
				<label for="applicant_rank" class="rank"> Rank </label>
				<label for="applicant_name"> Initials and Surname </label>
				<label for="service_number"> Service number </label>
				<br />
				<input type="hidden" name="applicant_rank_id" id="applicant_rank_id" />
				<input type="text" name="applicant_rank" id="applicant_rank" class="rank" />
				<input type="text" name="applicant_name" id="applicant_name" />
				<input type="number" name="service_number" id="service_number" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend> NZ Cadet Forces Service </legend>
			<div>
				<label for="service_cadet_unit_1">Cadet Unit</label>
				<label for="service_start_date_1" type="date">Start Date</label>
				<label for="service_end_date_1" type="date">End Date</label>
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
				<label for="course_date_1" type="date"> Date completed course </label>
			</div>
			<div class="container" id="course">
				<div class="datarow" data-rownum="1">
					<input type="hidden" name="course_qual_id_1" id="course_qual_id_1" />
					<input type="text" name="course_qual_1" id="course_qual_1" class="course" data-name="course_qual_" />
					<input type="date" name="course_date_1" id="course_date_1" max="<?= date('%Y-%m-%d') ?>" data-name="course_date_"  type="date" />
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
				<p class="instruction">Provide specific comment regarding the suitability of the candidate for the vacancy </p>
				<label for="cucdr_comment">Comment</label>
				<textarea name="cucdr_comment" id="cucdr_comment" required="required"></textarea>
			</div>
			<div>
				<label for="cucdr_rank" class="rank"> Rank </label>
				<label for="cucdr_name"> Name </label>
				<label for="cucdr_date" type="date"> Date </label>
			</div>
			<div>
				<input type="hidden" name="cucdr_rank_id" id="cucdr_rank_id" />
				<input type="text" class="rank" name="cucdr_rank" id="cucdr_rank" required="required" />
				<input type="text" name="cucdr_name" id="cucdr_name" required="required" />
				<input type="date" name="cucdr_date" id="cucdr_date" required="required"  />
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
			<p class="instruction">Provide specific comment regarding the suitability of the candidate for the vacancy </p>
			
			<label for="aso_comment">Comment</label>
			<textarea name="aso_comment" id="aso_comment" required="required"></textarea>
			<br />
			<label for="aso_rank" class="rank"> Rank </label>
			<label for="aso_name"> Name </label>
			<label for="aso_date" type="date"> Date </label>
			<br />
			<div>
				<input type="hidden" name="aso_rank_id" id="aso_rank_id" />
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
			<p class="instruction">Provide specific comment regarding the suitability of the candidate for the vacancy </p>
			<label for="ac_comment">Comment</label>
			<textarea name="ac_comment" id="ac_comment" required="required"></textarea>
			<br />
			<label for="ac_rank" class="rank"> Rank </label>
			<label for="ac_name"> Name </label>
			<label for="ac_date" type="date"> Date </label>
			<br />
			<div>
				<input type="hidden" name="ac_rank_id" id="ac_rank_id" />
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
			<textarea name="comdt_comment" id="comdt_comment" required="required"></textarea>
			<br />
			<label for="comdt_rank" class="rank"> Rank </label>
			<label for="comdt_name"> Name </label>
			<label for="comdt_date" type="date"> Date </label>
			<br />
			<div>
				<input type="hidden" name="comdt_rank_id" id="comdt_rank_id" />
				<input type="text" class="rank" name="comdt_rank" id="comdt_rank" required="required" />
				<input type="text" name="ac_name" id="comdt_name" required="required" />
				<input type="date" name="comdt_date" id="comdt_date" required="required" />
			</div>
		</fieldset>
		<hr />
		<button type="submit">Save</button>
		<button type="cancel">Cancel</button>
	</form>
	
	<script>
		
	</script>
	
	<?= get_footer() ?>
<?php
	/*			
		Plugin Name: NZCF CadetNet 
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
	
	<h2> <?= __('Expression of Interest','nzcf-cadetnet') ?> </h2>
	
	<form name="eoi" class="eoi" data-id="4a">
		
		<p class="instruction"> <?= __('Completed forms are to be emailed through to the Area Support Officer no later and Area Coordiator to reach NZCF HQ no later than 1200 hours on the Application close off date.','nzcf-cadetnet') ?> </p>
		
		<fieldset>
			<legend> <?= __('Position applying for','nzcf-cadetnet') ?> </legend>
			<div>
				<label for="vacancy_description"><?= __('Vacancy Description','nzcf-cadetnet') ?></label>
				<label for="rank" class="rank"><?= __('Rank','nzcf-cadetnet') ?></label>
				<label for="application_closes" type="date"><?= __('Closing date','nzcf-cadetnet') ?></label>
				<br />
				<!--
				<input type="hidden" name="vacancy_id" id="vacancy_id" value="" />
				<input type="text" name="vacancy_description" id="vacancy_description" required="required" />
				-->
				<select name="vacancy_id" id="vacancy_id" required="required">
				</select>
				<input type="text" name="rank" id="rank" readonly="readonly" class="rank" />
				<input type="date" name="application_closes" id="application_closes" readonly="readonly" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend> <?= __('Applicant details','nzcf-cadetnet') ?> </legend>
			<div>
				<label for="applicant_rank" class="rank"> <?= __('Rank','nzcf-cadetnet') ?> </label>
				<label for="applicant_name"> <?= __('Initials and Surname','nzcf-cadetnet') ?> </label>
				<label for="service_number"> <?= __('Service number','nzcf-cadetnet') ?> </label>
				<br />
				<!--
				<input type="hidden" name="applicant_rank_id" id="applicant_rank_id" />
				<input type="text" name="applicant_rank" id="applicant_rank" class="rank" />
				-->
				<select name="applicant_rank_id" id="applicant_rank_id" required="required" class="rank">
				</select>
				<input type="text" name="applicant_name" id="applicant_name" required="required" />
				<input type="text" name="service_number" id="service_number" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend> <?= __('NZ Cadet Forces Service','nzcf-cadetnet') ?> </legend>
			<div>
				<label for="service_cadet_unit_1"><?= __('Cadet Unit','nzcf-cadetnet') ?></label>
				<label for="service_start_date_1" type="date"><?= __('Start Date','nzcf-cadetnet') ?></label>
				<label for="service_end_date_1" type="date"><?= __('End Date','nzcf-cadetnet') ?></label>
				<label for="service_appts_held_1"><?= __('Appointments Held','nzcf-cadetnet') ?></label>
			</div>
			<div class="container" id="service">
				<div class="datarow" data-rownum="1">
					<input type="hidden" name="service_cadet_unit_id_1" id="service_cadet_unit_id_1" data-name="service_cadet_unit_id_" />
					<input type="text" name="service_cadet_unit_1" id="service_cadet_unit_1" class="cadet_unit" data-name="service_cadet_unit_" />
					<input type="date" name="service_start_date_1" id="service_start_date_1" max="<?= date("%Y-%m-%d") ?>" data-name="service_start_date_" />
					<input type="date" name="service_end_date_1" id="service_end_date_1" max="<?= date("%Y-%m-%d") ?>" data-name="service_end_date_" />
					<input type="text" name="service_appointments_held_1" id="service_appointments_held_1" data-name="service_appointments_held_" />
					<button type="button" class="new">Add row</button>
				</div>
			</div>
			&nbsp;
			<div>
				<label for="course_qual_1"> <?= __('NZCF Officer Course Qualifications','nzcf-cadetnet') ?></label>
				<label for="course_date_1" type="date"> <?= __('Date completed','nzcf-cadetnet') ?> </label>
			</div>
			<div class="container" id="course">
				<div class="datarow" data-rownum="1">
					<input type="hidden" name="course_qual_id_1" id="course_qual_id_1"  data-name="course_qual_id_" />
					<input type="text" name="course_qual_1" id="course_qual_1" class="course_type" data-name="course_qual_" />
					<input type="date" name="course_date_1" id="course_date_1" max="<?= date('%Y-%m-%d') ?>" data-name="course_date_"  type="date" />
					<button type="button" class="new">Add row</button>
				</div>
			</div>
			<div>
				<p class="instruction"><?= __('List NZCF courses you have staffed','nzcf-cadetnet') ?></p>
				<label for="course_staffed_1"> <?= __('Course Name','nzcf-cadetnet') ?></label>
				<label for="course_staffed_qty_1"> <?= __('Number of times Staffed','nzcf-cadetnet') ?> </label>
			</div>
			<div class="container" id="course_staffed">
				<div class="datarow" data-rownum="1">
					<input type="hidden" name="course_staffed_id_1" id="course_staffed_id_1"  data-name="course_staffed_id_" />
					<input type="text" name="course_staffed_1" id="course_staffed_1" class="course_type" data-name="course_staffed_" />
					<input type="number" name="course_staffed_qty_1" id="course_staffed_qty_1" data-name="course_staffed_date_" />
					<button type="button" class="new">Add row</button>
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend> <?= __('Reasons for applying','nzcf-cadetnet') ?> </legend>
			<p class="instruction"><?= __('In your own words, describe why you would be the best candidate for this role','nzcf-cadetnet') ?> </p>
			<textarea name="best_candidate_response" id="best_candidate_response" required="required"></textarea>
		</fieldset>
		
		<fieldset>
			<legend> <?= __('Curriculum Vitae','nzcf-cadetnet') ?> </legend>
			<p class="instruction"> <?= __('Provide a short summary of your NZCF and professional work history including any civilian qualifications that will assist you in this appointment.','nzcf-cadetnet') ?></p>
			<textarea name="cv" id="cv" requried="required"></textarea>
			<p class="notice"><?= __('As necessary, the AC, the Asst COMDT or COMDT may conduct further inquiries as to your suitability for the appointment.','nzcf-cadetnet') ?> </p>
		</fieldset>

		<fieldset>
			<legend> <?= __('Cadet Unit Commander Recommendation','nzcf-cadetnet') ?> </legend>
			<div>
				<label for="cucdr_recommendation"> <?= __('Recommendation','nzcf-cadetnet') ?> </label>
				<select name="cucdr_recommendation" id="cucdr_recommendation" required="required">
					<option value=""><?= __('Select','nzcf-cadetnet') ?></option>
					<option value="1"><?= __('Recommended','nzcf-cadetnet') ?></option>
					<option value="0"><?= __('Not Recommended','nzcf-cadetnet') ?></option>
				</select>
			</div>
			<div>
				<p class="instruction"><?= __('Provide specific comment regarding the suitability of the candidate for the vacancy','nzcf-cadetnet') ?> </p>
				<label for="cucdr_comment"><?= __('Comment','nzcf-cadetnet') ?></label>
				<textarea name="cucdr_comment" id="cucdr_comment" required="required"></textarea>
			</div>
			<div>
				<label for="cucdr_rank" class="rank"> <?= __('Rank','nzcf-cadetnet') ?> </label>
				<label for="cucdr_name"> <?= __('Name','nzcf-cadetnet') ?> </label>
				<label for="cucdr_date" type="date"> <?= __('Date','nzcf-cadetnet') ?> </label>
			</div>
			<div>
				<!--
				<input type="hidden" name="cucdr_rank_id" id="cucdr_rank_id" />
				<input type="text" class="rank" name="cucdr_rank" id="cucdr_rank" required="required" />
				-->
				<select name="cucdr_rank_id" id="cucdr_rank_id" required="required" class="rank">
				</select>
				<input type="text" name="cucdr_name" id="cucdr_name" required="required" />
				<input type="date" name="cucdr_date" id="cucdr_date" required="required"  />
			</div>
		</fieldset>
		
		<fieldset>
			<legend> <?= __('Area Support Officer Recommendation','nzcf-cadetnet') ?> </legend>
			<label for="aso_recommendation"> <?= __('Recommendation','nzcf-cadetnet') ?> </label>
			<select name="aso_recommendation" id="aso_recommendation" required="required">
				<option value=""><?= __('Select','nzcf-cadetnet') ?></option>
				<option value="1"><?= __('Recommended','nzcf-cadetnet') ?></option>
				<option value="0"><?= __('Not Recommended<','nzcf-cadetnet') ?>/option>
			</select>
			<br />
			<p class="instruction"><?= __('Provide specific comment regarding the suitability of the candidate for the vacancy','nzcf-cadetnet') ?> </p>
			
			<label for="aso_comment"><?= __('Comment','nzcf-cadetnet') ?></label>
			<textarea name="aso_comment" id="aso_comment" required="required"></textarea>
			<br />
			<label for="aso_rank" class="rank"> <?= __('Rank','nzcf-cadetnet') ?> </label>
			<label for="aso_name"> <?= __('Name','nzcf-cadetnet') ?> </label>
			<label for="aso_date" type="date"> <?= __('Date','nzcf-cadetnet') ?> </label>
			<br />
			<div>
				<!--
				<input type="hidden" name="aso_rank_id" id="aso_rank_id" />
				<input type="text" class="rank" name="aso_rank" id="aso_rank" required="required" />
				-->
				<select name="aso_rank_id" id="aso_rank_id" class="rank" required="required">
				</select>
				<input type="text" name="aso_name" id="aso_name" required="required" />
				<input type="date" name="aso_date" id="aso_date" required="required" />
			</div>
		</fieldset>

		<fieldset>
			<legend> <?= __('Area Coordinator Recommendation','nzcf-cadetnet') ?> </legend>
			<label for="ac_recommendation"> <?= __('Recommendation','nzcf-cadetnet') ?> </label>
			<select name="ac_recommendation" id="ac_recommendation" required="required">
				<option value=""><?= __('Select','nzcf-cadetnet') ?></option>
				<option value="1"><?= __('Recommended','nzcf-cadetnet') ?></option>
				<option value="0"><?= __('Not Recommended','nzcf-cadetnet') ?></option>
			</select>
			<br />
			<p class="instruction"><?= __('Provide specific comment regarding the suitability of the candidate for the vacancy','nzcf-cadetnet') ?> </p>
			<label for="ac_comment"><?= __('Comment','nzcf-cadetnet') ?></label>
			<textarea name="ac_comment" id="ac_comment" required="required"></textarea>
			<br />
			<label for="ac_rank" class="rank"> <?= __('Rank','nzcf-cadetnet') ?> </label>
			<label for="ac_name"> <?= __('Name','nzcf-cadetnet') ?> </label>
			<label for="ac_date" type="date"> <?= __('Date','nzcf-cadetnet') ?> </label>
			<br />
			<div>
				<!--
				<input type="hidden" name="ac_rank_id" id="ac_rank_id" />
				<input type="text" class="rank" name="ac_rank" id="ac_rank" required="required" />
				-->
				<select name="ac_rank_id" id="ac_rank_id" class="rank" required="requried">
				</select>
				<input type="text" name="ac_name" id="ac_name" required="required" />
				<input type="date" name="ac_date" id="ac_date" required="required" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend> <?= __('Headquarters NZCF','nzcf-cadetnet') ?> </legend>
			<label for="comdt_approval"> <?= __('COMDT Approval','nzcf-cadetnet') ?> </label>
			<select name="comdt_approval" id="comdt_approval" required="required">
				<option value=""><?= __('Select','nzcf-cadetnet') ?></option>
				<option value="1"><?= __('Approved','nzcf-cadetnet') ?></option>
				<option value="0"><?= __('Not Approved','nzcf-cadetnet') ?></option>
			</select>
			<br />
			<label for="comdt_comment"><?= __('Comment','nzcf-cadetnet') ?></label>
			<textarea name="comdt_comment" id="comdt_comment" required="required"></textarea>
			<br />
			<label for="comdt_rank" class="rank"> <?= __('Rank','nzcf-cadetnet') ?> </label>
			<label for="comdt_name"> <?= __('Name','nzcf-cadetnet') ?> </label>
			<label for="comdt_date" type="date"> <?= __('Date','nzcf-cadetnet') ?> </label>
			<br />
			<div>
				<!--
				<input type="hidden" name="comdt_rank_id" id="comdt_rank_id" />
				<input type="text" class="rank" name="comdt_rank" id="comdt_rank" required="required" />
				-->
				<select name="comdt_rank_id" id="comdt_rank_id" class="rank" required="required">
				</select>
				<input type="text" name="comdt_name" id="comdt_name" required="required" />
				<input type="date" name="comdt_date" id="comdt_date" required="required" />
			</div>
		</fieldset>
		<hr />
		<button type="submit"><?= __('Save','nzcf-cadetnet') ?></button>
		<button type="cancel"><?= __('Cancel','nzcf-cadetnet') ?></button>
	</form>
	
	<script>
		var eoi_id = <?= (isset($_GET['eoi_id'])?(int)$_GET['eoi_id']:0)?>;
		//alert(eoi_id);
	</script>
	
	<?= get_footer() ?>
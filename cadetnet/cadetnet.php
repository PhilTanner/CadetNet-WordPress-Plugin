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
	<p>Welcome to the CadetNet portal.</p>
<p>From here you will be able to access your own NZCF records, apply for Camps and Courses that you are eligible for.</p>
<p>More features will be added over time to further enhance what you are able to do.</p>
<p>If you have any issues then please send an email to the <a href="mailto:website.admin@cadetforces.org.nz">Website Admin Team</a> and they will endeavour to assist you as soon as they can.</p>
<p style="color: red;">Do not send website requests or issues to the Area Offices or HQ NZCF as they are not in a position to be able to help you</p>
<p>Please remember that the Website Admin Team are all serving NZCF personnel with lives in the real world who will do their best to resolve your issue as soon as they are physically able to.</p>
<button class="btn btn-default" onclick="mailto:website.admin@cadetforces.org.nz">Email Website Admin Team</button>
	<!--h2> <?= __('','nzcf-cadetnet') ?> </h2>
    <div id="main_tabs" class="clear">
        <ul>
            <li><a href="<?= plugins_url( 'partials/welcome.php' , __FILE__ ) ?>"><?= __('Welcome','nzcf-cadetnet') ?></a></li>
            <li><a href="<?= plugins_url( 'partials/profile.php' , __FILE__ ) ?>"><?= __('Profile','nzcf-cadetnet') ?></a></li>
            <li><a href="<?= plugins_url( 'partials/camps.php' , __FILE__ ) ?>"><?= __('Camps','nzcf-cadetnet') ?></a></li>
            <li><a href="<?= plugins_url( 'partials/admin.php' , __FILE__ ) ?>"><?= __('Admin','nzcf-cadetnet') ?></a></li>
        </ul>
    </div>
   <script>
  jQuery( function() {
    jQuery( "#main_tabs" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.fail(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
            "If this wouldn't be a demo." );
        });
      }
    });
  } );
  </script-->
	
	<?= get_footer() ?>
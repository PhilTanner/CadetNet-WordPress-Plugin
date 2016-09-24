<h3>My Profile</h3>
<form class="form-inline">
    <fieldset class="form-group">
        <label for="profile_firstname">First name<span style="color: red;">*</span></label>
        <input type="text" name="firstname" id="profile_firstname" class="form-control"/><br/>
        <label for="profile_middlename">Middle Name(s)</label>
        <input type="text" name="middlename" id="profile_middlename" class="form-control"/><br/>
        <label for="profile_familyname">Family Name<span style="color: red;">*</span></label>
        <input type="text" name="familyname" id="profile_familyname" class="form-control"/><br/>
        <label for="profile_initials">Initials<span style="color: red;">*</span></label>
        <input type="text" name="initials" id="profile_initials" class="form-control"/>
    </fieldset>
    <fieldset class="form-group">
        <label for="profile_corp">Corp <span style="color: red;">*</span></label>
        <select name="corp" id="profile_corp" class="corp">
        </select>
        <label for="profile_corp">Rank <span style="color: red;">*</span></label>
        <select name="corp" id="profile_rank" class="rank">
        </select>
    </fieldset>
</form>
<!-- Nav tabs -->
<div>
    <ul>
        <li><a href="<?= plugins_url( 'partials/profile/contact.php' , __FILE__ ) ?>"><?= __('Contact','nzcf-cadetnet') ?></a></li>
        <li><a href="<?= plugins_url( 'partials/profile/next_of_kin.php' , __FILE__ ) ?>"><?= __('Next of kin','nzcf-cadetnet') ?></a></li>
        <li><a href="<?= plugins_url( 'partials/profile/school.php' , __FILE__ ) ?>"><?= __('School','nzcf-cadetnet') ?></a></li>
        <li><a href="<?= plugins_url( 'partials/profile/medical.php' , __FILE__ ) ?>"><?= __('Medical','nzcf-cadetnet') ?></a></li>
        <li><a href="<?= plugins_url( 'partials/profile/awards.php' , __FILE__ ) ?>"><?= __('Awards','nzcf-cadetnet') ?></a></li>
        <li><a href="<?= plugins_url( 'partials/profile/promotions.php' , __FILE__ ) ?>"><?= __('Promotions','nzcf-cadetnet') ?></a></li>
        <li><a href="<?= plugins_url( 'partials/profile/courses.php' , __FILE__ ) ?>"><?= __('Camps and Courses','nzcf-cadetnet') ?></a></li>
    </ul>
</div>

   <!--script>
  jQuery( function() {
    jQuery( "#profile_tabs" ).tabs({
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
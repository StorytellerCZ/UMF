<div class="page-header">
	<h2><?php echo lang('settings_page_name'); ?></h2>
</div>
<div class="well"><?php echo lang('profile_instructions'); ?></div>
<?php if (isset($profile_info)): ?>
<div class="alert alert-success fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo $profile_info; ?>
</div>
<?php endif;
echo form_open_multipart(uri_string(), 'class="form-horizontal" role="form"');
echo form_hidden('form_type', 'profile');
echo form_fieldset(); ?>

<div class="form-group <?php echo (form_error('profile_username')) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" for="profile_username"><?php echo lang('profile_username'); ?></label>

    <div class="col-lg-10">
	<?php echo form_input(array('name' => 'profile_username', 'id' => 'profile_username', 'value' => set_value('profile_username') ? set_value('profile_username') : (isset($account->username) ? $account->username : ''), 'maxlength' => '24', 'class' => 'form-control'));
	if (form_error('profile_username') || isset($profile_username_error))
	{
	    echo '<span class="alert alert-danger">';
	    echo form_error('profile_username');
	    echo isset($profile_username_error) ? $profile_username_error : '';
	    echo '</span>';
	} ?>
    </div>
</div>

<div class="form-group <?php echo (form_error('profile_username')) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" <?php if (!(isset($account_details->picture) && strlen(trim($account_details->picture)) > 0)){ ?> for="pic_selection"<?php } ?>><?php echo lang('profile_picture'); ?></label>

    <div class="col-lg-10">
	<?php if (isset($account_details->picture) && strlen(trim($account_details->picture)) > 0) : ?>
	<?php echo showPhoto($account_details->picture); ?> &nbsp;
	<?php echo anchor('account/profile/index/delete', '<i class="glyphicon glyphicon-trash"></i> '.lang('profile_delete_picture'), 'class="btn btn-default"'); ?>
	<?php else : ?>
		
	<div class="accountPicSelect clearfix">
	    <div class="pull-left">
		<input type="radio" name="pic_selection" value="custom" checked="true" />
		<?php echo showPhoto(); ?>
	    </div>
	    <div class="pull-left">
		<p><?php echo lang('profile_custom_upload_picture'); ?><br>
		    <?php echo form_upload(array('name' => 'account_picture_upload', 'id' => 'account_picture_upload')); ?><br>
		    <small>(<?php echo lang('profile_picture_guidelines'); ?>)</small>
		</p>
	    </div>
	</div>

	<div class="accountPicSelect clearfix">
		<div class="pull-left">
		    <input type="radio" name="pic_selection" value="gravatar" />
		    <?php echo showPhoto( $gravatar ); ?>
		</div>
		<div class="pull-left">
		    <p>
			<small><a href="http://gravatar.com/" target="_blank"><?php echo lang('gravatar'); ?></a></small>
		    </p>
		</div>
	</div>
	
	<?php endif; ?>
	<?php if (isset($profile_picture_error))
	{
	    echo '<span class="alert alert-danger">' . $profile_picture_error . '</span>';
	} ?>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
	<button type="submit" class="btn btn-primary"><?php echo lang('profile_save'); ?></button>
    </div>
</div>

<?php echo form_fieldset_close();
echo form_close(); ?>

<div class="well"><?php echo sprintf(lang('settings_privacy_statement'), anchor('privacy', lang('settings_privacy_policy'))); ?></div>
<?php if (isset($settings_info)): ?>
<div class="alert alert-success fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo $settings_info; ?>
</div>
<?php endif;
echo form_open(uri_string(), 'class="form-horizontal"');
echo form_hidden('form_type', 'settings');
echo form_fieldset(); ?>

<div class="form-group <?php echo (form_error('settings_email') || isset($settings_email_error)) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" for="settings_email"><?php echo lang('settings_email'); ?></label>

    <div class="col-lg-10">
	<?php echo form_input(array('name' => 'settings_email', 'id' => 'settings_email', 'value' => set_value('settings_email') ? set_value('settings_email') : (isset($account->email) ? $account->email : ''), 'maxlength' => 160, 'class' => 'form-control')); ?>
	<?php if (form_error('settings_email') || isset($settings_email_error)) : ?>
        <span class="alert alert-danger">
		<?php
			echo form_error('settings_email');
			echo isset($settings_email_error) ? $settings_email_error : '';
		?>
	</span>
	<?php endif; ?>
    </div>
</div>

<div class="form-group <?php echo (form_error('settings_firstname')) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" for="settings_firstname"><?php echo lang('settings_firstname'); ?></label>

    <div class="col-lg-10">
		<?php echo form_input(array('name' => 'settings_firstname', 'id' => 'settings_firstname', 'value' => set_value('settings_firstname') ? set_value('settings_firstname') : (isset($account_details->firstname) ? $account_details->firstname : ''), 'maxlength' => 80, 'class' => 'form-control')); ?>
	<?php if(form_error('settings_firstname')): ?>
		<span class="alert alert-danger">
			<?php echo form_error('settings_firstname'); ?>
		</span>
	<?php endif; ?>
    </div>
</div>

<div class="form-group <?php echo (form_error('settings_lastname')) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" for="settings_lastname"><?php echo lang('settings_lastname'); ?></label>

    <div class="col-lg-10">
		<?php echo form_input(array('name' => 'settings_lastname', 'id' => 'settings_lastname', 'value' => set_value('settings_lastname') ? set_value('settings_lastname') : (isset($account_details->lastname) ? $account_details->lastname : ''), 'maxlength' => 80, 'class' => 'form-control')); ?>
	<?php if(form_error('settings_lastname')):?>
		<span class="alert alert-danger">
			<?php echo form_error('settings_lastname'); ?>
		</span>
	<?php endif; ?>
    </div>
</div>

<div class="form-group <?php echo isset($settings_dob_error) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" for="settings_dob_year"><?php echo lang('settings_dateofbirth'); ?></label>

    <div class="col-lg-10">
		<?php $m = $this->input->post('settings_dob_month') ? $this->input->post('settings_dob_month') : (isset($account_details->dob_month) ? $account_details->dob_month : ''); ?>
        <select name="settings_dob_month">
            <option value=""><?php echo lang('dateofbirth_month'); ?></option>
            <option value="1"<?php if ($m == 1) echo ' selected="selected"'; ?>><?php echo lang('month_jan'); ?></option>
            <option value="2"<?php if ($m == 2) echo ' selected="selected"'; ?>><?php echo lang('month_feb'); ?></option>
            <option value="3"<?php if ($m == 3) echo ' selected="selected"'; ?>><?php echo lang('month_mar'); ?></option>
            <option value="4"<?php if ($m == 4) echo ' selected="selected"'; ?>><?php echo lang('month_apr'); ?></option>
            <option value="5"<?php if ($m == 5) echo ' selected="selected"'; ?>><?php echo lang('month_may'); ?></option>
            <option value="6"<?php if ($m == 6) echo ' selected="selected"'; ?>><?php echo lang('month_jun'); ?></option>
            <option value="7"<?php if ($m == 7) echo ' selected="selected"'; ?>><?php echo lang('month_jul'); ?></option>
            <option value="8"<?php if ($m == 8) echo ' selected="selected"'; ?>><?php echo lang('month_aug'); ?></option>
            <option value="9"<?php if ($m == 9) echo ' selected="selected"'; ?>><?php echo lang('month_sep'); ?></option>
            <option value="10"<?php if ($m == 10) echo ' selected="selected"'; ?>><?php echo lang('month_oct'); ?></option>
            <option value="11"<?php if ($m == 11) echo ' selected="selected"'; ?>><?php echo lang('month_nov'); ?></option>
            <option value="12"<?php if ($m == 12) echo ' selected="selected"'; ?>><?php echo lang('month_dec'); ?></option>
        </select>
		<?php $d = $this->input->post('settings_dob_day') ? $this->input->post('settings_dob_day') : (isset($account_details->dob_day) ? $account_details->dob_day : ''); ?>
        <select name="settings_dob_day">
            <option value="" selected="selected"><?php echo lang('dateofbirth_day'); ?></option>
		<?php for ($i = 1; $i < 32; $i ++) : ?>
			<option value="<?php echo $i; ?>"<?php if ($d == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
		<?php endfor; ?>
        </select>
		<?php $y = $this->input->post('settings_dob_year') ? $this->input->post('settings_dob_year') : (isset($account_details->dob_year) ? $account_details->dob_year : ''); ?>
        <select id="settings_dob_year" name="settings_dob_year">
            <option value=""><?php echo lang('dateofbirth_year'); ?></option>
		<?php $year = mdate('%Y', now()); for ($i = $year; $i > 1900; $i --) : ?>
			<option value="<?php echo $i; ?>"<?php if ($y == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
		<?php endfor; ?>
        </select>
	<?php if (isset($settings_dob_error)): ?>
		<span class="alert alert-danger">
			<?php echo $settings_dob_error; ?>
		</span>
	<?php endif; ?>
    </div>
</div>

<div class="form-group <?php echo (form_error('settings_gender')) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" for="settings_gender"><?php echo lang('settings_gender'); ?></label>

    <div class="col-lg-10">
		<?php $s = ($this->input->post('settings_gender') ? $this->input->post('settings_gender') : (isset($account_details->gender) ? $account_details->gender : '')); ?>
        <select id="settings_gender" name="settings_gender" class="form-control">
            <option value=""><?php echo lang('settings_select'); ?></option>
            <option value="m"<?php if ($s == 'm') echo ' selected="selected"'; ?>><?php echo lang('gender_male'); ?></option>
            <option value="f"<?php if ($s == 'f') echo ' selected="selected"'; ?>><?php echo lang('gender_female'); ?></option>
        </select>
    </div>
</div>

<div class="form-group <?php echo (form_error('settings_country')) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" for="settings_country"><?php echo lang('settings_country'); ?></label>

    <div class="col-lg-10">
		<?php $account_country = ($this->input->post('settings_country') ? $this->input->post('settings_country') : (isset($account_details->country) ? $account_details->country : '')); ?>
        <select id="settings_country" name="settings_country" class="form-control">
            <option value=""><?php echo lang('settings_select'); ?></option>
		<?php foreach ($countries as $country) : ?>
			<option value="<?php echo $country->alpha2; ?>"<?php if ($account_country == $country->alpha2) echo ' selected="selected"'; ?>>
				<?php echo $country->country; ?>
			</option>
		<?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group <?php echo (form_error('settings_language')) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" for="settings_language"><?php echo lang('settings_language'); ?></label>

    <div class="col-lg-10">
		<?php $account_language = ($this->input->post('settings_language') ? $this->input->post('settings_language') : (isset($account_details->language) ? $account_details->language : '')); ?>
        <select id="settings_language" name="settings_language" class="form-control">
            <option value=""><?php echo lang('settings_select'); ?></option>
		<?php foreach ($languages as $language) : ?>
			<option value="<?php echo $language->one; ?>"<?php if ($account_language == $language->one) echo ' selected="selected"'; ?>>
				<?php echo $language->language; ?><?php if ($language->native && $language->native != $language->language) echo ' ('.$language->native.')'; ?>
			</option>
		<?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group <?php echo (form_error('settings_timezone')) ? 'error' : ''; ?>">
    <label class="control-label col-lg-2" for="settings_timezone"><?php echo lang('settings_timezone'); ?></label>

    <div class="col-lg-10">
		<?php $account_timezone = ($this->input->post('settings_timezone') ? $this->input->post('settings_timezone') : (isset($account_details->timezone) ? $account_details->timezone : '')); ?>
        <select id="settings_timezone" name="settings_timezone" class="form-control">
            <option value=""><?php echo lang('settings_select'); ?></option>
		<?php foreach ($zoneinfos as $zoneinfo) : ?>
			<option value="<?php echo $zoneinfo->zoneinfo; ?>"<?php if ($account_timezone == $zoneinfo->zoneinfo) echo ' selected="selected"'; ?>>
				<?php echo $zoneinfo->zoneinfo; ?><?php if ($zoneinfo->offset) echo ' ('.$zoneinfo->offset.')'; ?>
			</option>
		<?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group">
	<div class="col-lg-offset-2 col-lg-10">
		<button type="submit" class="btn btn-primary"><?php echo lang('settings_save'); ?></button>
		<button type="reset" class="btn btn-default btn-small"><?php echo lang('settings_cancel');?></button>
	</div>
    
</div>

<?php echo form_fieldset_close();
echo form_close(); ?>
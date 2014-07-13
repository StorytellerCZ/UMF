<?php if (! ($this->config->item("sign_up_enabled"))): ?>
	<div class="col-sm-12">
		<div class="page-header">
			<h1><?php echo sprintf(lang('sign_up_heading'), lang('website_title')); ?></h1>
		</div>
		<div class="alert alert-danger">
			<strong><?php echo lang('sign_up_notice');?> </strong> <?php echo lang('sign_up_registration_disabled'); ?>
		</div>
	</div>
<?php else: ?>
	<div class="col-sm-6">
		
		<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'sign_up_form'));
		echo form_fieldset(); ?>
		<h1><?php echo sprintf(lang('sign_up_heading'), lang('website_title')); ?></h1>
		
		<div class="well">
			
			<div id="username" class="form-group <?php echo (form_error('sign_up_username') || isset($sign_up_username_error)) ? 'alert alert-warning' : ''; ?>">
				<label class="control-label col-sm-2" for="sign_up_username"><?php echo lang('sign_up_username'); ?></label>
				
				<div class="col-sm-10">
					<?php echo form_input(array('name' => 'sign_up_username', 'id' => 'sign_up_username', 'value' => set_value('sign_up_username'), 'maxlength' => $this->config->item('sign_up_username_max_length'), 'class' => 'form-control'));
					if (form_error('sign_up_username') || isset($sign_up_username_error)):
						echo form_error('sign_up_username');
						if (isset($sign_up_username_error)):
							echo $sign_up_username_error;
						endif;
					endif; ?>
				</div>
			</div>
		
			<div id="email" class="form-group <?php echo (form_error('sign_up_email') || isset($sign_up_email_error)) ? 'alert alert-warning' : ''; ?>">
				<label class="control-label col-sm-2" for="sign_up_email"><?php echo lang('sign_up_email'); ?></label>
				
				<div class="col-sm-10">
					<?php echo form_input(array('name' => 'sign_up_email', 'id' => 'sign_up_email', 'value' => set_value('sign_up_email'), 'maxlength' => '160', 'class' => 'form-control'));
					if (form_error('sign_up_email') || isset($sign_up_email_error)) :
						echo form_error('sign_up_email');
						if (isset($sign_up_email_error)) :
							echo $sign_up_email_error;
						endif;
					endif; ?>
				</div>
			</div>
			
			<div id="password" class="form-group <?php echo (form_error('sign_up_password')) ? 'alert alert-warning' : ''; ?>">
				<label class="control-label col-sm-2" for="sign_up_password"><?php echo lang('sign_up_password'); ?></label>
				
				<div class="col-sm-10">
					<?php echo form_password(array('name' => 'sign_up_password', 'id' => 'sign_up_password', 'value' => set_value('sign_up_password'), 'class' => 'form-control'));
					if (form_error('sign_up_password')) :
						echo form_error('sign_up_password');
					endif; ?>
				</div>
			</div>
			
			<div id="confirm_password" class="form-group <?php echo (form_error('sign_up_confirm_password')) ? 'alert alert-warning' : ''; ?>">
				<label class="control-label col-sm-2" for="sign_up_confirm_password"><?php echo lang('sign_up_confirm_password'); ?></label>
				
				<div class="col-sm-10">
					<?php echo form_password(array('name' => 'sign_up_confirm_password', 'id' => 'sign_up_confirm_password', 'value' => set_value('sign_up_confirm_password'), 'class' => 'form-control'));
					if (form_error('sign_up_confirm_password')) :
						echo form_error('sign_up_confirm_password');
					endif; ?>
				</div>
			</div>
			
                        <div class="form-group">
                            <?php echo form_label(lang('sign_up_terms'), 'sign_up_terms', array('class'=>'control-label col-sm-10')); ?>
                            <div class="checkbox col-sm-2">
                                <input type="checkbox" id="sign_up_terms" name="sign_up_terms" value="agree" />
                                    <?php if (form_error('sign_up_terms') || isset($sign_up_terms_error)) : ?>
                                            <span class="alert alert-warning">
                                            <?php echo form_error('sign_up_terms');
                                            if (isset($sign_up_terms_error)) : ?>
                                                    <?php echo $sign_up_terms_error;
                                            endif; ?>
                                            </span>
                                    <?php endif; ?>
                            </div>
                        </div>
			
			<?php if (isset($recaptcha)) :
				echo $recaptcha;
				if (isset($sign_up_recaptcha_error)) : ?>
					<span class="alert alert-warning"><?php echo $sign_up_recaptcha_error; ?></span>
				<?php endif; ?>
			<?php endif; ?>
			
			<div>
				<?php echo form_button(array('type'=> 'submit','class' => 'btn btn-primary btn-large pull-right'), '<i class="glyphicon glyphicon-pencil"></i> '.lang('sign_up_create_my_account')); ?>
			</div>
			<br/>
			
			<p><?php echo lang('sign_up_already_have_account'); ?> <?php echo anchor('account/sign_in', lang('sign_up_sign_in_now')); ?></p>
		</div>
		
		<?php echo form_fieldset_close();
		echo form_close(); ?>

	</div>

	<div class="col-sm-6">
		<?php if ($third_party_auth = $this->config->item('third_party_auth')) : ?>
			<h3><?php echo lang('sign_up_third_party_heading'); ?></h3>
			<ul>
				<?php foreach($third_party_auth['providers'] as $provider_name => $provider_values) :
					if($provider_values['enabled']) : ?>
					<li class="third_party"><?php echo anchor('account/connect/'.$provider_name, '<img src="'.base_url(RES_DIR.'/img/auth_icons/'.strtolower($provider_name).'.png').'" alt="'.sprintf(lang('sign_up_with'), lang('connect_'.strtolower($provider_name))).'" height="64" width="64">' ); ?></li>
					<?php endif;
				endforeach; ?>
			</ul>
		<?php endif; ?>
	</div><!-- /span6 -->
        <script>
            $(document).ready(function() {
                $('#sign_up_form').bootstrapValidator({
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        sign_up_username:{
                            threshold: 3,
                            validators:{
                                notEmpty: {
                                    message: '<?php echo lang('sign_up_js_validation_no_username'); ?>'
                                },
                                stringLength:{
                                    min: <?php echo $this->config->item('sign_up_username_min_length'); ?>,
                                    max: <?php echo $this->config->item('sign_up_username_max_length'); ?>,
                                    message: '<?php echo sprintf(lang('sign_up_js_validation_short'), $this->config->item('sign_up_username_min_length'), $this->config->item('sign_up_username_max_length')); ?>'
                                },
                                remote:{
                                    url: "./account/settings/username_exists/",
                                    message: '<?php echo lang('sign_up_username_taken'); ?>'
                                    
                                }
                            }
                        },
                        sign_up_email:{
                            threshold: 5,
                            validators:{
                                notEmpty: {
                                    message: '<?php echo lang('sign_up_js_validation_email_invaild'); ?>'
                                },
                                emailAddress:{
                                    message: '<?php echo lang('sign_up_js_validation_email_invaild'); ?>'
                                }
                            }
                        },
                        sign_up_password:{
                            threshold: 3,
                            validators:{
                                notEmpty: {
                                    message: '<?php echo lang('sign_up_js_validation_password_no'); ?>'
                                },
                                stringLength: {
                                    min: <?php echo $this->config->item('sign_up_password_min_length'); ?>,
                                    message: '<?php echo sprintf(lang('sign_up_js_validation_password_short'), $this->config->item('sign_up_password_min_length')); ?>'
                                }
                            }
                        },
                        sign_up_confirm_password:{
                            threshold: 3,
                            validators:{
                                notEmpty: {
                                    message: '<?php echo lang('sign_up_js_validation_password_no'); ?>'
                                },
                                identical:{
                                    message: '<?php echo lang('sign_up_js_validation_password_confirm_nomatch'); ?>',
                                    field: 'sign_up_password'
                                }
                            }
                        },
                        sign_up_terms:{
                            validators:{
                                choice:{
                                    min: 1,
                                    message:"<?php //echo lang('sign_up_js_validation_terms'); ?> "
                                }
                            }
                        }
                    }
                });
            });
        </script>
<?php endif; ?>
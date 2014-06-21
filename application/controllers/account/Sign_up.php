<?php
/**
 * A3M (Account Authentication & Authorization) is a CodeIgniter 3.x package.
 * It gives you the CRUD to get working right away without too much fuss and tinkering!
 * Designed for building webapps from scratch without all that tiresome login / logout / admin stuff thats always required.
 *
 * @link https://github.com/donjakobo/A3M GitHub repository
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Sign up
 *
 * @package A3M
 * @subpackage Controllers
 */
class Sign_up extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'account/ssl', 'url'));
		$this->load->library(array('account/authentication', 'account/authorization', 'account/recaptcha', 'form_validation'));
		$this->load->model(array('account/Account_details_model', 'account/Account_model'));
		$this->load->language(array('general', 'account/sign_up', 'account/connect_third_party'));
	}

	/**
	 * Account sign up
	 *
	 * @access public
	 * @return void
	 */
	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));

		// Redirect signed in users to homepage
		if ($this->authentication->is_signed_in()) redirect('');

		// Check recaptcha
		$recaptcha_result = $this->recaptcha->check();

		// Store recaptcha pass in session so that users only needs to complete captcha once
		if ($recaptcha_result === TRUE) $this->session->set_userdata('sign_up_recaptcha_pass', TRUE);

		// Setup form validation
		$this->form_validation->set_error_delimiters('<span class="alert alert-danger">', '</span>');
		$this->form_validation->set_rules(array(
			array('field' => 'sign_up_username',
			      'label' => 'lang:sign_up_username',
			      'rules' => 'trim|required|alpha_dash|min_length[2]|max_length[24]|callback_username_check'),
			array('field' => 'sign_up_password',
			      'label' => 'lang:sign_up_password',
			      'rules' => 'trim|required|min_length[6]'),
			array('field' => 'sign_up_email',
			      'label' => 'lang:sign_up_email',
			      'rules' => 'trim|required|valid_email|max_length[160]|callback_email_check'),
			array('field' => 'sign_up_confirm_password',
			      'label' => 'lang:sign_up_password_confirm',
			      'rules' => 'trim|required|min_length[6]|matches[sign_up_password]'),
			array('field' => 'sign_up_terms',
			      'label' => 'lang:sign_up_terms_confirm',
			      'rules' => 'trim|required')));

		// Run form validation
		if (($this->form_validation->run() === TRUE) && ($this->config->item("sign_up_enabled")))
		{
			// Either already pass recaptcha or just passed recaptcha
			if ( ($this->session->userdata('sign_up_recaptcha_pass') == FALSE || $recaptcha_result === FALSE) && $this->config->item("sign_up_recaptcha_enabled") === TRUE)
			{
				$data['sign_up_recaptcha_error'] = $this->input->post('recaptcha_response_field') ? lang('sign_up_recaptcha_incorrect') : lang('sign_up_recaptcha_required');
			}
			else
			{
				// Remove recaptcha pass
				$this->session->unset_userdata('sign_up_recaptcha_pass');

				// Create user
				$user_id = $this->Account_model->create($this->input->post('sign_up_username', TRUE), $this->input->post('sign_up_email', TRUE), $this->input->post('sign_up_password', TRUE));

				// Add user details (auto detected country, language, timezone)
				$this->Account_details_model->update($user_id);
				
				// Assigns user role
				$this->load->model('account/Rel_account_role_model');
				$this->Rel_account_role_model->update($user_id, $this->config->item("sign_up_default_user_group"));

				// Auto sign in?
				if($this->config->item('account_email_validate'))
				{
					//send authentication email
					$account = $this->Account_model->get_by_id($user_id);
					$authentication_url = site_url('account/validate?user_id=' . $user_id . '&token='. sha1($user_id . $account->createdon . $this->config->item('password_reset_secret')));
					
					// Load email library
					$this->load->library('email');
					
					// Set up email preferences
					$config['mailtype'] = 'html';
					
					// Initialise email lib
					$this->email->initialize($config);
					
					// Send the authentication email
					$this->email->from($this->config->item('account_email_confirm_sender'), lang('website_title'));
					$this->email->to($account->email);
					$this->email->subject(sprintf(lang('sign_up_email_subject'), lang('website_title')));
					$this->email->message($this->load->view('account/account_validation_email', array(
						'username' => $account->username,
						'authentication_url' => anchor($authentication_url, $authentication_url)
					), TRUE));
					if($this->email->send())
					{
						if($this->config->item("sign_up_auto_sign_in"))
						{
							// Run sign in routine
							$this->authentication->sign_in($this->input->post('sign_in_username_email', TRUE), $this->input->post('sign_in_password', TRUE), $this->input->post('sign_in_remember', TRUE));
						}
						
						// Load confirmation view
						$data['content'] = $this->load->view('account/account_validation_send', isset($data) ? $data : NULL, TRUE);
						$this->load->view('template', $data);
					}
					else
					{
						if(ENVIRONMENT == 'development')
						{
							$data['content'] = $this->email->print_debugger();
						}
						else
						{
							show_error('There was an error sending the e-mail. Please contact the webmaster.');
						}
					}
					
					return;
				}
				else
				{
					if ($this->config->item("sign_up_auto_sign_in"))
					{
						// Run sign in routine
						$this->authentication->sign_in($this->input->post('sign_in_username_email', TRUE), $this->input->post('sign_in_password', TRUE), $this->input->post('sign_in_remember', TRUE));
					}
					redirect('account/sign_in');
				}
				
			}
		}
		else
		{
			// Load recaptcha code
			if ($this->config->item("sign_up_recaptcha_enabled") === TRUE) if ($this->session->userdata('sign_up_recaptcha_pass') != TRUE) $data['recaptcha'] = $this->recaptcha->load($recaptcha_result, $this->config->item("ssl_enabled"));
			
			// Load sign up view
			$data['content'] = $this->load->view('sign_up', isset($data) ? $data : NULL, TRUE);
			$this->load->view('template', $data);
		}
	}

	/**
	 * Check if a username exist
	 *
	 * @access public
	 * @param string $username
	 * @return bool
	 */
	function username_check($username)
	{
		//once we update to PHP 5.5, we will be able to put this into the if statement
		$result = $this->Account_model->get_by_username($username);
		if( empty($result) )
		{
			return TRUE;
		}
		else
		{
			$error = lang('sign_up_username_taken');
			$this->form_validation->set_message('username_check', $error);
			return FALSE;
		}
	}

	/**
	 * Check if an email exist
	 *
	 * @access public
	 * @param string $email
	 * @return bool
	 */
	function email_check($email)
	{
		$result = $this->Account_model->get_by_email($email);
		if( empty($result) )
		{
			return TRUE;
		}
		else
		{
			$error = lang('sign_up_email_exist');
			$this->form_validation->set_message('email_check', $error);
			return FALSE;
		}
	}

}


/* End of file Sign_up.php */
/* Location: ./application/controllers/account/Sign_up.php */

<?php
/**
 * A3M (Account Authentication & Authorization) is a CodeIgniter 3.x package.
 * It gives you the CRUD to get working right away without too much fuss and tinkering!
 * Designed for building webapps from scratch without all that tiresome login / logout / admin stuff thats always required.
 *
 * @link https://github.com/donjakobo/A3M GitHub repository
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Forgot password page
 *
 * @package A3M
 * @subpackage Controllers
 */
class Forgot_password extends CI_Controller
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'account/ssl', 'url'));
		$this->load->library(array('account/authentication', 'account/authorization', 'account/recaptcha', 'form_validation'));
		$this->load->model('account/Account_model');
		$this->load->language(array('general', 'account/forgot_password'));
	}

	/**
	 * Forgot password
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
		if ($recaptcha_result === TRUE) $this->session->set_userdata('forget_password_recaptcha_pass', TRUE);

		// Setup form validation
		// max length as per IETF (http://www.rfc-editor.org/errata_search.php?rfc=3696&eid=1690)
		$this->form_validation->set_error_delimiters('<span class="alert alert-danger">', '</span>');
		$this->form_validation->set_rules(array(
			array(
				'field' => 'forgot_password_username_email',
				'label' => 'lang:forgot_password_username_email',
				'rules' => 'trim|required|min_length[2]|max_length[254]|callback_check_username_or_email'
			)
		));

		// Run form validation
		if ($this->form_validation->run())
		{
			// User has neither already passed recaptcha nor just passed recaptcha
			if ($this->session->userdata('forget_password_recaptcha_pass') != TRUE && $recaptcha_result !== TRUE && ($this->config->item("forgot_password_recaptcha_enabled") === TRUE))
			{
				$data['forgot_password_recaptcha_error'] = $this->input->post('recaptcha_response_field') ? lang('forgot_password_recaptcha_incorrect') : lang('forgot_password_recaptcha_required');
			}
			else
			{
				// Remove recaptcha pass
				$this->session->unset_userdata('forget_password_recaptcha_pass');

				// Username does not exist
				if ( ! $account = $this->Account_model->get_by_username_email($this->input->post('forgot_password_username_email', TRUE)))
				{
					$data['forgot_password_username_email_error'] = lang('forgot_password_username_email_does_not_exist');
				}
				// Does not manage password
				elseif ( ! $account->password)
				{
					$data['forgot_password_username_email_error'] = lang('forgot_password_does_not_manage_password');
				}
				else
				{
					// Set reset datetime
					$time = $this->Account_model->update_reset_sent_datetime($account->id);

					// Load email library
					$this->load->library('email');

					// Set up email preferences
					$config['mailtype'] = 'html';

					// Initialise email lib
					$this->email->initialize($config);

					// Generate reset password url
					$password_reset_url = site_url('account/reset_password?id='.$account->id.'&token='.sha1($account->id.$time.$this->config->item('password_reset_secret')));

					// Send reset password email
					$this->email->from($this->config->item('password_reset_email'), lang('reset_password_email_sender'));
					$this->email->to($account->email);
					$this->email->subject(sprintf(lang('reset_password_email_subject'), lang('website_title')));
					$this->email->message($this->load->view('account/reset_password_email', array(
						'username' => $account->username,
						'password_reset_url' => anchor($password_reset_url, $password_reset_url)
					), TRUE));
					if($this->email->send())
					{
						// Load reset password sent view
						$data['content'] = $this->load->view('account/reset_password_sent', isset($data) ? $data : NULL, TRUE);
					}
					else
					{
						if(ENVIRONMENT == 'development')
						{
							$data['content'] = $this->email->print_debugger();
						}
						else
						{
							show_error(lang('website_email_send_error'));
                                                        log_message('error', $this->email->print_debugger());
						}
					}

					$this->load->view('template', $data);
					return;
				}
			}
		}

		// Load recaptcha code if recaptcha is enabled
		if ($this->config->item("forgot_password_recaptcha_enabled") === TRUE)
			if ($this->session->userdata('forget_password_recaptcha_pass') != TRUE)
				$data['recaptcha'] = $this->recaptcha->load($recaptcha_result, $this->config->item("ssl_enabled"));

		// Load forgot password view
		$data['content'] = $this->load->view('account/forgot_password', isset($data) ? $data : NULL, TRUE);
		$this->load->view('template', $data);
	}

	/**
	 * Will check for username or e-mail
	 *
	 * Will check if the username or e-mail is available and return boolean value.
	 *
	 * @access public
	 * @param object $str Possible username or e-mail to be checked
	 * @return boolean
	 */
	public function check_username_or_email($str)
	{
		//are we checking an email address?
		if (strpos($str,'@') !== false)
		{
			//Its an email, so lets check if its valid
			if ($this->form_validation->valid_email($str))
				return TRUE;
			else
			{
				$this->form_validation->set_message('_check_username_or_email', lang('form_validation_forgot_password_email_invalid'));
				return FALSE;
			}
		}
		else
		{
			//check if username is alpha_dash
			if ($this->form_validation->alpha_dash($str))
				return TRUE;
			else
			{
				$this->form_validation->set_message('_check_username_or_email', lang('form_validation_forgot_password_username_invalid'));
				return FALSE;
			}
		}
	}
}
/* End of file Forgot_password.php */
/* Location: ./application/controllers/account/Forgot_password.php */

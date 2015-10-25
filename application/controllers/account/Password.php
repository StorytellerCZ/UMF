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
 * Password manager
 *
 * @package A3M
 * @subpackage Controllers
 */
class Password extends CI_Controller
{

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('date', 'language', 'account/ssl', 'url'));
		$this->load->library(array('account/authentication', 'account/authorization', 'form_validation'));
		$this->load->model('account/Account_model');
		$this->load->language(array('general', 'account/account_password'));
	}

	/**
	 * Account password manager
	 *
	 * Allows users to change their password
	 */
	function index()
	{
		$data = $this->authentication->initialize(TRUE, 'account/password');

		// No access to users without a password
		if ( ! $data['account']->password) redirect('');

		### Setup form validation
		$this->form_validation->set_error_delimiters('<span class="alert alert-danger">', '</span>');
		$this->form_validation->set_rules(array(
                    array('field' => 'password_new_password', 'label' => 'lang:password_new_password', 'rules' => 'trim|required|min_length['.$this->config->item('sign_up_password_min_length').']'),
                    array('field' => 'password_retype_new_password', 'label' => 'lang:password_retype_new_password', 'rules' => 'trim|required|matches[password_new_password]')
                    ));

		$this->session->set_flashdata('reseting_password', TRUE);

		### Run form validation
		if ($this->form_validation->run())
		{
			// Change user's password
			$this->Account_model->update_password($data['account']->id, $this->input->post('password_new_password', TRUE));
			$this->session->set_flashdata('password_info', lang('password_password_has_been_changed'));
			$this->session->set_flashdata('reseting_password', TRUE);
			redirect('account/password');
		}

		$data['content'] = $this->load->view('account/account_password', $data, TRUE);
		$this->load->view('template', $data);
	}
}
/* End of file Ppassword.php */
/* Location: ./application/controllers/account/Password.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Account_linked Controller
 */
class Linked_accounts extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account');
		$this->load->helper(array('language', 'account/ssl', 'url'));
		$this->load->library(array('account/authentication', 'account/authorization', 'form_validation'));
		$this->load->model(array('account/Account_model', 'account/Account_providers_model', 'account/Account_facebook_model', 'account/Account_twitter_model', 'account/Account_openid_model', 'account/Account_linkedin_model'));
		$this->load->language(array('general', 'account/account_linked', 'account/connect_third_party'));
	}

	/**
	 * Linked accounts
	 */
	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));

		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url('account/linked_accounts')));
		}

		// Retrieve sign in user
		$data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
		
		//delete a linked account
		if ($this->input->post('provider') && $this->input->post('uid'))
		{
			$this->Account_providers_model->delete($this->session->userdata('account_id'), $this->input->post('provider', TRUE), $this->input->post('uid', TRUE));
			$this->session->set_flashdata('linked_info', lang('linked_linked_account_deleted'));
		}
		
		$data['linked_accounts'] = $this->Account_providers_model->get_by_user_id($this->session->userdata('account_id'));
		
		$data['content'] = $this->load->view('account/account_linked', $data, TRUE);
		$this->load->view('template', $data);
	}

}


/* End of file Connect_accounts.php */
/* Location: ./application/controllers/account/Connect_accounts.php */

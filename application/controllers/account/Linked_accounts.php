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
 * Linked Accounts
 *
 * @package A3M
 * @subpackage Controllers
 */
class Linked_accounts extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'account/ssl', 'url'));
		$this->load->library(array('account/authentication', 'account/authorization', 'form_validation'));
		$this->load->model(array('account/Account_model', 'account/Account_providers_model'));
		$this->load->language(array('general', 'account/account_linked', 'account/connect_third_party'));
	}

	/**
	 * Linked accounts page
	 *
	 * Displays all the social media accounts that have been linked by the user to this page and offers an option to add or remove connections.
	 */
	function index()
	{
		$data = $this->authentication->initialize(TRUE, 'account/linked_accounts');
		
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
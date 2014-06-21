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
 * E-mail validation
 *
 * @package A3M
 * @subpackage Controllers
 */
class Validate extends CI_Controller
{
    function __construct()
    {
	parent::__construct();
        
        // Load the necessary stuff...
        $this->load->helper(array('language', 'account/ssl', 'url'));
        $this->load->library(array('account/authentication', 'account/authorization'));
        $this->load->model('account/Account_model');
        $this->load->language(array('general', 'account/sign_up'));
    }
    
    /**
     * Validate account e-mail
     */
    function Index()
    {
        // Enable SSL?
	maintain_ssl($this->config->item("ssl_enabled"));
        
        // Redirect signed in users to homepage
        if($this->config->item('account_email_validation_required'))
	{
	    if ($this->authentication->is_signed_in()) redirect('');
	}
	
	if($this->authentication->is_signed_in())
	{
	    $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
	}
        
        //redirect invalid entries to homepage
        if($this->input->get('user_id', TRUE) == NULL && $this->input->get('token', TRUE) == NULL) redirect('');
        
        $account = $this->Account_model->get_by_id($this->input->get('user_id', TRUE));
	
        //check for valid token
        if($this->input->get('token', TRUE) == sha1($account->id . $account->createdon . $this->config->item('password_reset_secret')))
        {
            //activate
            $this->Account_model->verify($account->id);
            
            //load the confirmation page
            $data['content'] = $this->load->view('account/account_validation', isset($data) ? $data : NULL, TRUE);
	    $this->load->view('template', $data);
        }
        else
        {
            redirect('');
        }
    }
    
    /**
     * Send validation e-mail again
     * @param string $user username or e-mail
     */
    public function resend($username_email)
    {
	if($this->config->item('account_email_validate'))
	{
	    //first find user id
	    $account = $this->Account_model->get_by_username_email($username_email);
	    $authentication_url = site_url('account/validate?user_id=' . $account->id . '&token='. sha1($account->id . $account->createdon . $this->config->item('password_reset_secret')));
	    
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
		// Load reset password sent view
		$data['content'] = $this->load->view('account/account_validation_resend', isset($data) ? $data : NULL, TRUE);
		$this->load->view('template', $data);
	    }
	    else
	    {
		if(ENVIRONMENT == 'development')
		{
		    echo($this->email->print_debugger());
		}
		else
		{
		    show_error('There was an error sending the e-mail. Please contact the webmaster.');
		}
	    }
	}
	else
	{
	    //e-mail validation is not active so return back to sing in
	    redirect(base_url("account/sign_in"));
	}
    }
}
/* End of file Validate.php */
/* Location: ./application/controllers/account/Validate.php */
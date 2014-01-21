<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forums extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->config('umf/forums');
        $this->load->model(array('forums/thread_model', 'forums/category_model'));
        $this->load->helper('pagination');
	$this->load->language('forums/forums');
	$this->load->library('forums/cibb');
    }
    
    public function index()
    {
        maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in() && ! $this->config->item('forums_view_anonym'))
        {
            redirect('account/sign_in/?continue='.urlencode(base_url('/forums')));
        }
	else
	{
	    $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
	}
	
	// Redirect unauthorized users to account profile page
	if( ! $this->config->item('forums_view_anonym'))
	{
	    if ( ! $this->authorization->is_permitted(array('forums_view ', 'forums_posts_create')))
	    {
		redirect('account/profile');
	    }
	}
        
        $data['categories'] = $this->category_model->get_all();
        
        $data['content'] = $this->load->view('forums/home', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
}
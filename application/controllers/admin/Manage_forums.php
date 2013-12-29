<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_forums extends CI_Controller {    
    public $data         = array();
    public $page_config  = array();
    
    public function __construct() 
    {
        parent::__construct();
	$this->load->library(array('forums/cibb', 'form_validation'));
        $this->load->model(array('forums/category_model', 'forums/thread_model'));
	$this->load->helper(array('pagination', 'form'));
	$this->load->language('forums/forums');
        
    }
    
    // start category function
    public function category_create()
    {
	maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url().'admin/forums/category_create'));
        }
	
	// Redirect unauthorized users to account profile page
	if ( ! $this->authorization->is_permitted('forums_categories_create'))
	{
	    redirect('account/profile');
	}
	
	$data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
	
	//validate data
        $this->form_validation->set_rules('category_name', 'lang:forums_name', 'required|trim|xss_clean|is_unique[forums_categories.name]');
	$this->form_validation->set_rules('category_slug', 'lang:forums_slug', 'required|trim|xss_clean|alpha_numeric|is_unique[forums_categories.slug]');
	$this->form_validation->set_rules('category_parent', 'lang:forums_parent', 'required|trim|xss_clean|integer');
	
	if($this->form_validation->run())
	{
	    //get the data and insert them into DB
	    $name = $this->input->post('category_name', TRUE);
	    $slug = $this->input->post('category_slug', TRUE);
	    $parent = $this->input->post('category_parent', TRUE);
	    
	    //@todo check that the input was correct
	    $this->category_model->create($name, $slug, $parent);
	    redirect('admin/manage_forums');
	}
        
        $data['categories'] = $this->category_model->get_all();
        
	$data['content'] = $this->load->view('admin/forums_category_create', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    
    public function category_view()
    {
	maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url().'admin/forums/category_view'));
        }
	
	// Redirect unauthorized users to account profile page
	if ( ! $this->authorization->is_permitted(array('forums_categories_create', 'forums_categories_edit', 'forums_categories_delete')))
	{
	    redirect('account/profile');
	}
        
        $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
	
        $data['categories'] = $this->category_model->get_all();
        
	$data['content'] = $this->load->view('admin/forums_category_view', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    
    public function category_edit($category_id)
    {
	maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url().'admin/forums/category_edit/'.$category_id));
        }
	
	// Redirect unauthorized users to account profile page
	if ( ! $this->authorization->is_permitted('forums_categories_edit') || $category_id == NULL)
	{
	    redirect('account/profile');
	}
        
        $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
	
        //validate data
        $this->form_validation->set_rules('category_name', 'lang:forums_name', 'required|trim|xss_clean|is_unique[forums_categories.name]');
	$this->form_validation->set_rules('category_slug', 'lang:forums_slug', 'required|trim|xss_clean|alpha_numeric|is_unique[forums_categories.slug]');
	$this->form_validation->set_rules('category_parent', 'lang:forums_parent', 'required|trim|xss_clean|integer');
	
	if($this->form_validation->run())
	{
	    //get the data and insert them into DB
	    $name = $this->input->post('category_name', TRUE);
	    $slug = $this->input->post('category_slug', TRUE);
	    $parent = $this->input->post('category_parent', TRUE);
	    
	    //@todo check that the input was correct
	    $this->category_model->edit($category_id, $name, $slug, $parent);
	    redirect('admin/forums');
	}
	
        $data['category'] =  $this->category_model->get($category_id);
	
	$data['categories'] = $this->category_model->get_all();
        
	$data['content'] = $this->load->view('admin/forums_category_edit', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    
    public function category_delete($category_id)
    {
	maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url().'admin/forums/category_delete'));
        }
	
	// Redirect unauthorized users to account profile page
	if ( ! $this->authorization->is_permitted('forums_categories_delete'))
	{
	    redirect('account/profile');
	}
        
        $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
	
        $this->db->delete(TBL_CATEGORIES, array('id' => $category_id));
        $this->session->set_userdata('tmp_success_del', 1);
        redirect('admin/category_view');
    }
    // end category function
    
    // start thread function
    public function thread_view($category_id, $page = 0)
    {
	maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url('admin/forums/thread_view')));
        }
	
	// Redirect unauthorized users to account profile page
	if ( ! $this->authorization->is_permitted(array('forums_threads_edit', 'forums_threads_create', 'forum_threads_delete')))
	{
	    redirect('account/profile');
	}
        
        $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
	
        // set pagination
        $this->load->library('pagination');
        $this->page_config['base_url']    = site_url('admin/thread_view/');
        $this->page_config['uri_segment'] = 5;
        $this->page_config['total_rows']  = $this->thread_model->get_total_by_category($category_id);
        $this->page_config['per_page']    = 10;
        
        set_pagination();
        
        $this->pagination->initialize($this->page_config);
        
        $data['start']   = $page;
        $data['page']    = $this->pagination->create_links();
        
	$data['category'] = $this->category_model->get($category_id);
	$data['threads'] = $this->thread_model->get_by_category($page, $this->page_config['per_page'], $category_id);
        
	$data['content'] = $this->load->view('admin/forums_thread_view', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    
    public function thread_edit($thread_id)
    {
	maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url().'admin/forums/thread_edit'));
        }
	
	// Redirect unauthorized users to account profile page
	if ( ! $this->authorization->is_permitted('forums_threads_edit'))
	{
	    redirect('account/profile');
	}
        
        $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
	
	//validate data
	
        
        $data['thread']  = $this->thread_model->get($thread_id);
        $data['categories'] = $this->category_model->get_all();
        
	$data['content'] = $this->load->view('admin/forums_thread_edit', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    
    public function thread_delete($thread_id)
    {
	maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url().'admin/forums/thread_delete'));
        }
	
	// Redirect unauthorized users to account profile page
	if ( ! $this->authorization->is_permitted('forums_threads_delete'))
	{
	    redirect('account/profile');
	}
        
        $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
	
	$this->Thread_model->delete($thread_id);
	
        $this->thread_view();
    }
    // end thread function
}
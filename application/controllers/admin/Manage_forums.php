<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Administration for the forums
 *
 * @package CIBB
 * @subpackage Controllers
 */
class Manage_forums extends CI_Controller
{
    /**
     * @var array
     */
    public $data         = array();
    /**
     * @var array
     */
    public $page_config  = array();
    
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct();
	$this->load->library(array('forums/cibb', 'form_validation'));
        $this->load->model(array('forums/category_model', 'forums/thread_model'));
	$this->load->helper(array('pagination', 'form'));
	$this->load->language(array('general', 'forums/forums'));
        
    }
    
    /**
     * Index will show the category view and management
     */
    public function index()
    {
	$this->category_view();
    }
    
    /**
     * Forum categoris management
     */
    public function category_create()
    {
	$data = $this->authentication->initialize(TRUE, 'admin/manage_forums/category_create', NULL, 'forums_categories_create');
	
	//validate data
        $this->form_validation->set_rules('category_name', 'lang:forums_name', 'required|trim|xss_clean|is_unique[forums_categories.name]');
	$this->form_validation->set_rules('category_slug', 'lang:forums_slug', 'required|trim|xss_clean|alpha_dash|is_unique[forums_categories.slug]');
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
    
    /**
     * Details of a specific category
     */
    public function category_view()
    {
	$data = $this->authentication->initialize(TRUE, 'admin/manage_forums/category_view', NULL, array('forums_categories_create', 'forums_categories_edit', 'forums_categories_delete'));
	
        $data['categories'] = $this->category_model->get_all();
        
	$data['content'] = $this->load->view('admin/forums_category_view', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    
    /**
     * Editing a category
     * @param int $category_id ID of a forum category to edit
     */
    public function category_edit($category_id)
    {
	$data = $this->authentication->initialize(TRUE, 'admin/manage_forums/category_edit/'.$category_id, NULL, 'forums_categories_edit');
	
	// Redirect unauthorized users to account profile page
	if ($category_id == NULL)
	{
	    redirect('account/profile');
	}
	
        //validate data
        $this->form_validation->set_rules('category_name', 'lang:forums_name', 'required|trim|xss_clean|is_unique[forums_categories.name]');
	$this->form_validation->set_rules('category_slug', 'lang:forums_slug', 'required|trim|xss_clean|alpha_dash|is_unique[forums_categories.slug]');
	$this->form_validation->set_rules('category_parent', 'lang:forums_parent', 'required|trim|xss_clean|integer');
	
	if($this->form_validation->run())
	{
	    //get the data and insert them into DB
	    $name = $this->input->post('category_name', TRUE);
	    $slug = $this->input->post('category_slug', TRUE);
	    $parent = $this->input->post('category_parent', TRUE);
	    
	    //@todo check that the input was correct
	    $this->category_model->edit($category_id, $name, $slug, $parent);
	    redirect('admin/manage_forums');
	}
	
        $data['category'] =  $this->category_model->get($category_id);
	
	$data['categories'] = $this->category_model->get_all();
        
	$data['content'] = $this->load->view('admin/forums_category_edit', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    
    /**
     * Delete a category
     * @param int $category_id
     */
    public function category_delete($category_id)
    {
	$data = $this->authentication->initialize(TRUE, 'admin/manage_forums/category_delete/'.$category_id, NULL, 'forums_categories_delete');
	
        $this->category_model->delete($category_id);
        $this->session->set_userdata('tmp_success_del', 1);
        redirect('admin/manage_forums');
    }
    // end category function
    
    /**
     * Display threads for a specific category
     * @param Number $category_id Category for which to display threads
     * @param Number $page Page number for pagination
     */
    public function thread_view($category_id, $page = 0)
    {
	$data = $this->authentication->initialize(TRUE, 'admin/manage_forums/thread_view/'.$category_id.'/'.$page, NULL, array('forums_threads_edit', 'forums_threads_create', 'forum_threads_delete'));
	
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
    
    /**
     * Edit a thread
     * @param Number $thread_id
     */
    public function thread_edit($thread_id)
    {
	$data = $this->authentication->initialize(TRUE, 'admin/manage_forums/thread_edit/'.$thread_id, NULL, 'forums_threads_edit');
	
	//validate data
        $this->form_validation->set_rules('thread-title', 'lang:forums_name', 'required|trim|xss_clean|is_unique[forums_categories.name]');
	$this->form_validation->set_rules('thread-slug', 'lang:forums_slug', 'required|trim|xss_clean|alpha_dash|is_unique[forums_categories.slug]');
	$this->form_validation->set_rules('thread-category', 'lang:forums_parent', 'required|trim|xss_clean|integer');
	
	if($this->form_validation->run())
	{
	    //get the data and insert them into DB
	    $name = $this->input->post('thread-title', TRUE);
	    $slug = $this->input->post('thread-slug', TRUE);
	    $parent = $this->input->post('thread-category', TRUE);
	    
	    //@todo check that the input was correct
	    $this->thread_model->edit($thread_id, $name, $slug, $parent);
	    redirect('admin/manage_forums/thread_view/'.$parent);
	}
        
        $data['thread']  = $this->thread_model->get($thread_id);
        $data['categories'] = $this->category_model->get_all();
        
	$data['content'] = $this->load->view('admin/forums_thread_edit', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    
    /**
     * Delete a thread
     * @param Number $thread_id
     */
    public function thread_delete($thread_id)
    {
	$data = $this->authentication->initialize(TRUE, 'admin/manage_forums/category_delete/'.$thread_id, NULL, 'forums_threads_delete');
	
	$this->thread_model->delete($thread_id);
	
        redirect('admin/manage_forums');
    }
    // end thread function
}
/* End of file Manage_forums.php */
/* Location: ./application/controllers/admin/Manage_forums.php */
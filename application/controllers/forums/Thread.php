<?php
/**
* CodeIgniter Bulletin Board
*
* Original Author of CIBB:
* @author Aditia Rahman
* @link http://superdit.com/2012/08/15/cibb-an-experimental-basic-forum-built-with-codeigniter-and-twitter-bootstrap/
*
* Rewrite Author:
* @author Jan Dvorak IV.
* @link https://github.com/AdwinTrave
*
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Threads
 * @package CIBB
 * @subpackage Controllers
 */
class Thread extends CI_Controller {
    
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct();
        $this->load->config('umf/forums');
        $this->load->model(array('forums/thread_model', 'forums/category_model', 'forums/posts_model'));
        $this->load->helper('pagination');
        $this->load->language(array('general', 'forums/forums'));
        $this->load->library(array('form_validation', 'forums/cibb'));
    }
    
    /*
    public function index($start = 0)
    {
        maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in() && ! $this->config->item('forums_view_anonym'))
        {
            redirect('account/sign_in/?continue='.urlencode(base_url('/forums/thread/'.$start)));
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
        
        // set pagination
        $this->load->library('pagination');
        $this->page_config['base_url']    = site_url('forums/thread/');
        $this->page_config['uri_segment'] = 3;
        $this->page_config['total_rows']  = $this->threads_model->count_all_threads();
        $this->page_config['per_page']    = 10;
        
        set_pagination();
        
        $this->pagination->initialize($this->page_config);
        
        $this->data['type']    = 'index';
        $this->data['page']    = $this->pagination->create_links();
        $this->data['threads'] = $this->thread_model->get_all($start, $this->page_config['per_page']);
        $data['content'] = $this->load->view('forums/index', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    */
    
    /**
     * Create a forum thread
     * @param int $category_id Category id in which the thread should be created
     */
    public function create($category_id)
    {
        maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url('/forums/thread/create')));
        }
	
	// Redirect unauthorized users to account profile page
        if ( ! $this->authorization->is_permitted(array('forums_view ', 'forums_posts_create')))
        {
            redirect('account/profile');
        }
        
        $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
        
        //validate data
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('thread-title', 'lang:forums_name', 'required|trim|xss_clean');
	$this->form_validation->set_rules('thread-slug', 'lang:forums_slug', 'required|trim|xss_clean|alpha_dash|is_unique[forums_threads.slug]');
        $this->form_validation->set_rules('thread-post', 'lang:forums_initial_post', 'required|trim|xss_clean');
        
        if($this->form_validation->run())
        {
            //get the data
            $title = $this->input->post('thread-title', TRUE);
            $slug = $this->input->post('thread-slug', TRUE);
            $post = $this->input->post('thread-post', TRUE);
            
            //insert into db
            $this->thread_model->create($title, $slug, $category_id, $post, $data['account']->id);
            
            //redirect to the new thread
            redirect('forums/thread/'.$slug);
        }
        
        $data['categories'] = $this->category_model->get_all();
        $data['content'] = $this->load->view('forums/create', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
    
    /**
     * Individual thread
     *
     * Originally talk
     *
     * @param string $slug
     * @param Number $page
     */
    public function Index($slug, $page = 0)
    {
        maintain_ssl($this->config->item("ssl_enabled"));
	
	// Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in() && ! $this->config->item('forums_view_anonym'))
        {
            redirect('account/sign_in/?continue='.urlencode(base_url('/forums/thread/'.$slug)));
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
        
        $data['thread'] = $this->thread_model->get_by_slug($slug);
        
        //check for reply
        $this->form_validation->set_rules('reply-post', 'lang:forums_post', 'required|trim|xss_clean');
        
        if($this->form_validation->run())
        {
            //prep the data
            $post = $this->input->post('reply-post', TRUE);
            
            $this->posts_model->reply($data['thread']->id, $data['account']->id, $post);
        }
        
        // set pagination
        $this->load->library('pagination');
        $this->page_config['base_url']    = site_url('forums/thread/'.$slug);
        $this->page_config['uri_segment'] = 3;
        $this->page_config['total_rows']  = $this->posts_model->get_post_num_in_thread($data['thread']->id);
        $this->page_config['per_page']    = 10;
        
        set_pagination();
        
        $this->pagination->initialize($this->page_config);    
        
        $data['categories']    = $this->category_model->get_all();
        $data['page']   = $this->pagination->create_links();
        $data['posts']  = $this->posts_model->get($data['thread']->id, $page, $this->page_config['per_page']);
        //$this->thread_model->get_posts_threaded($thread->id, $start, $this->page_config['per_page']);
        $data['cat']    = $this->category_model->get_all_parent($data['thread']->category_id, 0);
        
        $data['content'] = $this->load->view('forums/talk', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
}
/* End of file Thread.php */
/* Location: ./application/controllers/forums/Thread.php */
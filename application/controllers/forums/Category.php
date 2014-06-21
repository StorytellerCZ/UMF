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
 * Category
 * @package CIBB
 * @subpackage Controllers
 */
class Category extends CI_Controller {
    
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct();
        $this->load->config('umf/forums');
        $this->load->model(array('forums/thread_model', 'forums/category_model'));
        $this->load->library(array('pagination', 'forums/cibb'));
        $this->load->helper('pagination');
        $this->load->language(array('general', 'forums/forums'));
    }
    
    /**
     * Category view
     * @param string $slug Slug
     * @param Number $page page number
     */
    public function Index($slug, $page = 0)
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
        
        $category = $this->category_model->get_by_slug($slug);
        $data['cat']    = $this->category_model->get_all_parent($category->id, 0);
        $data['category'] = $this->category_model->get_by_slug($slug);
        
        // set pagination
        $this->page_config['base_url']    = site_url('forums/category/'.$slug);
        $this->page_config['uri_segment'] = 4;
        $this->page_config['total_rows']  = $this->thread_model->get_total_by_category($category->id);
        $this->page_config['per_page']    = 10;
        
        set_pagination();
        
        $this->pagination->initialize($this->page_config);
        
        $data['page']    = $this->pagination->create_links();
        
        $data['threads'] = $this->thread_model->get_by_category($page, $this->page_config['per_page'], $category->id);
        
        $data['type']    = 'category';
        
        $data['content'] = $this->load->view('forums/index', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
}
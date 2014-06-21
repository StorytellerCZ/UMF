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
 * Forum main page
 * @package CIBB
 * @subpackage Controllers
 */
class Forums extends CI_Controller {
    
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct();
        $this->load->config('umf/forums');
        $this->load->model(array('forums/thread_model', 'forums/category_model'));
        $this->load->helper('pagination');
	$this->load->language(array('general', 'forums/forums'));
	$this->load->library('forums/cibb');
    }
    
    /**
     * Forum index
     */
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
/* End of file Forums.php */
/* Location: ./application/controllers/forums/Forums.php */
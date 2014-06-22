<?php
/**
 * Mahana Messaging Library for CodeIgniter
 *
 * CI library for linking to application's existing user table and
 * creating basis of an internal messaging system. No views or controllers
 * included.
 *
 * @author      Jeff Madsen
 *              jrmadsen67@gmail.com
 *              http://www.codebyjeff.com
 * 
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mahana Messaging Messages Overview
 *
 * @author Jan Dvorak IV. <webmaster@freedombase.net>
 * @package Mahana Messaging
 * @subpackage Controller
 */
class Overview extends CI_Controller {
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('pm/mahana_messaging', 'form_validation'));
        $this->load->language(array('general', 'pm/pm', 'pm/mahana'));
    }
    
    /**
     * Overview of user's messages with an option to create a new one
     * 
     * @access public
     */
    function index()
    {
        $data = $this->authentication->initialize(TRUE, 'pm/overview', NULL, 'msg_use');
        
        $threads_grouped = $this->mahana_messaging->get_all_threads_grouped($this->session->userdata('account_id'));
        $data['threads'] = $threads_grouped['retval'];
        
        $data['participants'] = array();
        
        //get participants for each thread
        foreach($data['threads'] as $thread)
        {
            $list = $this->mahana_messaging->get_participant_list($thread['thread_id'], $this->session->userdata('account_id'));
            $data['participants'][$thread['thread_id']] = $list['retval'];
        }
        
        //listen if new message is being created
        if($this->input->post('msg-new', TRUE))
        {
            //create a new message and redirect to it
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            $this->form_validation->set_rules('msg-recipients', 'lang:pm_recipients', 'required|trim|xss_clean');
            $this->form_validation->set_rules('msg-subject', 'lang:pm_subject', 'required|trim|min_length[2]|xss_clean');
            $this->form_validation->set_rules('msg-text', 'lang:pm_text', 'required|trim|min_length[2]|xss_clean');
            
            if($this->form_validation->run())
            {
                //get the data
                $recipients = $this->input->post('msg-recipients', TRUE);
                $subject = $this->input->post('msg-subject', TRUE);
                $text = $this->input->post('msg-text', TRUE);
                
                //convert usernames to ids
                $recipients = explode(',', $recipients);
                $recipients = $this->mahana_messaging->usernames_to_ids($recipients);
                
                //submit
                $data['response'] = $this->mahana_messaging->send_new_message($this->session->userdata('account_id'), $recipients, $subject, $text);
            }
        }
        
        $data['content'] = $this->load->view('pm/overview', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
}
/* End of file Overview.php */
/* Location: ./application/controllers/pm/Overview.php */
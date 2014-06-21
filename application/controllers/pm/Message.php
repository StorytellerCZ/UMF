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
 * Mahana Messaging Mesage Control
 * 
 * @author Jan Dvorak IV. <webmaster@freedombase.net>
 * @package Mahana Messaging
 * @subpackage Controller
 */
class Message extends CI_Controller
{
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('account/authorization', 'pm/mahana_messaging', 'form_validation'));
        $this->load->language(array('general', 'pm/pm', 'pm/mahana'));
    }
    
    /**
     * Message thread
     * 
     * @param int $id Description
     * @access public
     */
    function index($id = NULL)
    {
        $data = $this->authentication->initialize(TRUE, 'pm/message/'.$id, NULL, 'msg_use');
        
        //if no message is defined, then redirect back to overview
        if($id == NULL || !is_numeric($id))
        {
            redirect('pm');
        }
        else
        {
            $id = (int)$id;
        }
        
        //listen for a reply
        if($this->input->post('msg-reply', TRUE))
        {
            //set rules
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            $this->form_validation->set_rules('msg-reply-id', 'lang:pm_reply', 'required|trim|xss_clean|numeric');
            $this->form_validation->set_rules('msg-subject', 'lang:pm_subject', 'required|trim|min_length[2]|xss_clean');
            $this->form_validation->set_rules('msg-text', 'lang:pm_text', 'required|trim|min_length[2]|xss_clean');
            
            //perform check
            if($this->form_validation->run())
            {
                //get the data
                $message_id = $this->input->post('msg-reply-id', TRUE);
                $subject = $this->input->post('msg-subject', TRUE);
                $text = $this->input->post('msg-text', TRUE);
                
                //add to DB
                $this->mahana_messaging->reply_to_message($message_id, $this->session->userdata('account_id'), $subject, $text);
            }
        }
        
        //listen for adding a participant
        if($this->input->post('participant-add', TRUE))
        {
            //check if they have correct permissions
        }
        
        //listen for removing a participant
        if($this->input->post('participant-remove', TRUE))
        {
            //check if they have correct permissions or if they are removing themselves
            
            //if removing themselves redirect to pm overview
        }
        
        //load the thread
        $full_thread = $this->mahana_messaging->get_full_thread($id, $this->session->userdata('account_id'));
        $data['thread'] = $full_thread['retval'];
        
        $participants_list = $this->mahana_messaging->get_participant_list($id);
        $data['participants'] = $participants_list['retval'];
        
        $data['content'] = $this->load->view('pm/message', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
}
/* End of file Message.php */
/* Location: ./application/controllers/pm/Message.php */
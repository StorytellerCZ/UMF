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
    
    /**
     * Add participant to a thread
     * @param int $id Thread ID
     */
    public function add_participant($id)
    {
        $data = $this->authentication->initialize(TRUE, 'pm/message/add_participant/'.$id, NULL, 'msg_use');
        
        //check that the user can do this
        $participants = $this->mahana_messaging->get_participant_list($id)['retval'];
        $allowed = FALSE;
        foreach($participants AS $participant)
        {
            if($participant['user_id'] == $data['account']->id)
            {
                $allowed = TRUE;
            }
        }
        
        if($allowed == FALSE)
        {
            $this->session->set_flashdata(array('message' => lang('pm_action_not_allowed'), 'message_type' => 'danger'));
            redirect(base_url('pm/message/'.$id));
        }
        
        $this->form_validation->set_rules('msg-add-participants', 'lang:pm_participant_wrong', 'required|trim|alpha_dash|xss_clean');
        if($this->form_validation->run())
        {
            $users = $this->input->post('msg-add-participants', TRUE);
            
            //now separate users by ',' into array
            $user_array = explode(',', trim($users));
            $user_ids = $this->mahana_messaging->usernames_to_ids($user_array);
            if($user_ids)
            {
                foreach($user_ids AS $user)
                {
                    $this->mahana_messaging->add_participant($id, $user);
                }
            }
            else
            {
                $this->session->set_flashdata(array('message' => lang('pm_participant_wrong'), 'message_type' => 'danger'));
            }
        }
        else
        {
            $this->session->set_flashdata(array('message' => lang('pm_participant_wrong'), 'message_type' => 'danger'));
        }
        redirect(base_url('pm/message/'.$id));
    }
    
    /**
     * Remove a participant from a thread
     * @param int $id Thread ID
     * @param int $user User ID
     */
    public function remove_participant($id, $user)
    {
        $data = $this->authentication->initialize(TRUE, 'pm/message/remove_participant/'.$id.'/'.$user, NULL, 'msg_use');
        
        //check that the user can do this
        if($data['account']->id != $user)
        {
            $this->session->set_flashdata(array('message' => lang('pm_action_not_allowed'), 'message_type' => 'danger'));
            redirect(base_url('pm/message/'.$id));
        }
        
        $error = $this->mahana_messaging->remove_participant($id, $user);
        if($error['err'] == 1)
        {
            $this->session->set_flashdata(array('message' => $error['msg'], 'message_type' => 'danger'));
        }
        elseif($error['err'] == 0)
        {
            $this->session->set_flashdata(array('message' => $error['msg'], 'message_type' => 'success'));
        }
        
        if($data['account']->id == $user)
        {
            redirect(base_url('pm'));
        }
        else
        {
            redirect(base_url('pm/message/'.$id));
        }
        
    }
}
/* End of file Message.php */
/* Location: ./application/controllers/pm/Message.php */
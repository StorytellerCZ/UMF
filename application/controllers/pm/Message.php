<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('account/authorization', 'pm/mahana_messaging', 'form_validation'));
        $this->load->language(array('pm/pm', 'pm/mahana'));
    }

    function index($id = NULL)
    {
        maintain_ssl($this->config->item("ssl_enabled"));
        
        // Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url().'pm/Message'.$id));
        }
        
        $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
        
        //check that they are authorized to use private messaging
        if(! $this->authorization->is_permitted('msg_use'))
        {
            redirect(base_url());
        }
        
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
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Overview extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        
        // Load the necessary stuff...
        $this->load->helper(array('language', 'url', 'form', 'account/ssl'));
        $this->load->library(array('pm/mahana_messaging', 'form_validation'));
        $this->load->language(array('pm/pm', 'pm/mahana'));
    }
    
    /*
     * Overview of user's messages with an option to create a new one
     * @access public
     */
    function index()
    {
        maintain_ssl($this->config->item("ssl_enabled"));
        
        // Redirect unauthenticated users to signin page
        if ( ! $this->authentication->is_signed_in())
        {
            redirect('account/sign_in/?continue='.urlencode(base_url().'pm/Message'));
        }
        
        $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));
        
        //check that they are authorized to use private messaging
        if(! $this->authorization->is_permitted('msg_use'))
        {
            redirect('');
        }
        
        $data['threads'] = $this->mahana_messaging->get_all_threads_grouped($this->session->userdata('account_id'))['retval'];
        
        //listen if new message is being created
        if($this->input->post('msg-new', TRUE))
        {
            //create a new message and redirect to it
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
                //@todo check for multiple recipients and convert to array if needed
                $recipients = $this->mahana_messaging->usernames_to_ids($recipients);
                
                //submit
                $data['response'] = $this->mahana_messaging->send_new_message($this->session->userdata('account_id'), $recipients, $subject, $text);
            }
            else
            {
                //send the data back to the view and show the form with errors
            }
        }
        
        $data['content'] = $this->load->view('pm/overview', isset($data) ? $data : NULL, TRUE);
        $this->load->view('template', $data);
    }
}
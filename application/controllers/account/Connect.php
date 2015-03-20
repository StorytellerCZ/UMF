<?php
/**
 * A3M (Account Authentication & Authorization) is a CodeIgniter 3.x package.
 * It gives you the CRUD to get working right away without too much fuss and tinkering!
 * Designed for building webapps from scratch without all that tiresome login / logout / admin stuff thats always required.
 *
 * @link https://github.com/donjakobo/A3M GitHub repository
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Connect to social providers
 * @package A3M
 * @subpackage Controllers
 */
class Connect extends CI_Controller
{
  /**
  * Constructor
  */
  function __construct()
  {
    parent::__construct();

    // Load the necessary stuff...
    $this->load->helper(array('language', 'account/ssl', 'url'));
    $this->load->library(array('account/Authentication', 'account/Authorization', 'account/Hybrid_auth_lib'));
    $this->load->model(array('account/Account_model', 'account/Account_details_model', 'account/Account_providers_model'));
    $this->load->language(array('general', 'account/connect_third_party'));
  }

  /**
   * The initiation of connecting to a third party provider
   * @param string $provider Name of the provider that we should connect to
   * @param string $identifier For OpenID, the link to the account
   */
  function Index($provider = NULL, $identifier = NULL)
  {
    // Enable SSL?
    maintain_ssl($this->config->item("ssl_enabled"));

    if(empty($provider))
    {
        if ($this->authentication->is_signed_in())
        {
            redirect('account/linked_accounts');
        }
        else
        {
            redirect('');
        }
    }

  	//check if open id
  	if($provider == "OpenID" && $identifier == NULL)
  	{
      //redirect to user to provide identifier
      redirect('account/connect_openid');
  	}

    try
    {
      if($this->hybrid_auth_lib->provider_enabled($provider))
      {
        log_message('debug', "controllers.HAuth.login: service $provider enabled, trying to authenticate.");

    		if($identifier == NULL)
    		{
    		  $service = $this->hybrid_auth_lib->authenticate($provider);
    		}
    		else
    		{
    		  $service = $this->hybrid_auth_lib->authenticate($provider, array('openid_identifier' => $identifier));
    		}

        if ($service->isUserConnected())
        {
          log_message('debug', 'controller.HAuth.login: user authenticated.');

          $user_profile = $service->getUserProfile();

          log_message('debug', 'controllers.HAuth.login: user profile:'.PHP_EOL.print_r($user_profile, TRUE));

      		//User has connected provider to A3M
          $user = $this->Account_providers_model->get_by_provider_uid($provider, $user_profile->identifier);
          if(isset($user))
          {
      			if(! $this->authentication->is_signed_in())
      			{
    			    //user isn't signed in, so login
    			    $this->authentication->sign_in_by_id($user->user_id);
      			}
      			else //otherwise this is an error and they are trying to connect already connected account
      			{
              if($user->user_id === $this->session->userdata('account_id'))
              {
                $this->session->set_userdata('linked_error', sprintf(lang('linked_linked_with_this_account'), lang('connect_'.strtolower($provider))));
              }
              else
              {
                $this->session->set_userdata('linked_error', sprintf(lang('linked_linked_with_another_account'), lang('connect_'.strtolower($provider))));
              }
              //mark the linked error session data as flash data
              $this->session->mark_as_flash('linked_error');

      			  redirect('account/linked_accounts');
      			}
          }
          else
          {
            //if user is signed in then they are adding provider to their connected accounts
      			if($this->authentication->is_signed_in())
      			{
    			    $this->Account_providers_model->insert($this->session->userdata('account_id'), $provider, $user_profile->identifier, $user_profile->email, $user_profile->displayName, $user_profile->firstName, $user_profile->lastName, $user_profile->profileURL, $user_profile->webSiteURL, $user_profile->photoURL);

              $this->session->set_userdata('linked_info', sprintf(lang('linked_linked_with_your_account'), $provider));
              $this->session->mark_as_flash('linked_info');

    			    redirect('account/linked_accounts');
      			}
    			  // Discussion: should we compare the e-mails that we get with what we have on record and then connect with that?
      			else //start creating a new account
      			{
    			    // Store user's data in session
    			    $this->session->set_userdata('connect_create', array($provider => (array)$user_profile));

    			    // Create a3m account
    			    redirect('account/connect_create');
      			}
          }
        }
        else // Cannot authenticate user
        {
          show_error(lang('connect_cant_authenticate'));
        }
      }
	    else
	    {
		      show_404();
	    }
    }
    catch(Exception $e)
    {
      $error = lang('connect_hybrid_error_unexpected');
      switch($e->getCode())
      {
        //@todo put errors in a lang file
        case 0 : $error = lang('connect_hybrid_error_unspecified'); break;
        case 1 : $error = lang('connect_hybrid_error_config'); break;
        case 2 : $error = lang('connect_hybrid_error_providers_config'); break;
        case 3 : $error = lang('connect_hybrid_error_provider_unknown'); break;
        case 4 : $error = lang('connect_hybrid_error_provider_credentials'); break;
        case 5 : log_message('debug', lang('connect_hybrid_error_fail_cancelled_refused'));
        //redirect();
        if (isset($service))
        {
           log_message('debug', lang('connect_hybrid_error_logout'));
           $service->logout();
        }
        show_error(lang('connect_hybrid_error_cancelled')); break;
        case 6 : $error = lang('connect_hybrid_error_provider_profile'); break;
        case 7 : $error = lang('connect_hybrid_error_provider_user_not_connected'); break;
        default : $error = lang('connect_hybrid_error_unspecified'); break;
      }

      if (isset($service))
      {
          $service->logout();
      }

      log_message('error', lang('connect_hybrid_error_debug').$error);
      show_error(lang('connect_hybrid_error_show'));
    }
  }
}
/* End of file Connect.php */
/* Location: ./application/controllers/account/Connect.php */

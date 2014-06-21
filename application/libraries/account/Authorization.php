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
 * A3M Authorization library
 *
 * @package A3M
 * @subpackage Libraries
 */
class Authorization
{
  /**
   * The CI object
   * @var object
   */
  var $CI;

  /**
   * Constructor
   */
  function __construct()
  {
    // Obtain a reference to the ci super object
    $this->CI =& get_instance();

    //Load the session, if CI2 load it as library, if it is CI3 load as a driver
    if (substr(CI_VERSION, 0, 1) == '2')
    {
      $this->CI->load->library('session');
    }
    else
    {
      $this->CI->load->driver('session');
    }
    
    log_message('debug', 'Authorization Class Initalized');
  }

  /**
   * Check if user has permission
   *
   * @access public
   * @param array/string $permission_keys
   * @param boolean $require_all
   * @return boolean
   */
  function is_permitted($permission_keys, $require_all = FALSE)
  {
    $account_id = $this->CI->session->userdata('account_id');

    $this->CI->load->model('account/Acl_permission_model');

    $account_permissions = $this->CI->Acl_permission_model->get_by_account_id($account_id);

    // Loop through and check if the account 
    // has any of the permission keys supplied
    if (isset($permission_keys))
    {
      foreach ($account_permissions as $perm) 
      {
        // Array of permission keys
        if (is_array($permission_keys))
        {
          foreach($permission_keys as $key) 
          {
            // Return if only a single one is required.
            if( $perm->key == $key && ! $require_all ) 
            {
              return TRUE;
            } 
            // Only takes one bad apple
            elseif($perm->key != $key && $require_all)
            {
              return FALSE;
            }
          }
        }
        // Single permission key
        else
        {
          // Return if only a single one is required.
          if ($perm->key == $permission_keys && ! $require_all ) 
          {
            return TRUE;
          }
          // Only takes one bad apple
          elseif ($perm->key != $permission_keys && $require_all) 
          {
            return FALSE;
          }
        }
      }
    }

    // If nothing above matched for single 
    // permission, then this is false.
    if (! $require_all)
    {
      return FALSE;
    }
    // If it made this this far and all are 
    // required, then all is fine in the world
    else
    {
      return TRUE;
    }
  }
  
  // --------------------------------------------------------------------
  
  /**
   * Check if user is admin
   *
   * @access public
   * @return boolean
   */
  function is_admin()
  {
    $account_id = $this->CI->session->userdata('account_id');

    $this->CI->load->model('account/Acl_role_model');

    return $this->CI->Acl_role_model->has_role('Admin', $account_id);
  }
  
  // --------------------------------------------------------------------
  
  /**
   * Check if user is a specific role
   *
   * @access public
   * @param string/array $roles
   * @param boolean $require_all
   * @return boolean
   */
  function is_role($roles, $require_all = FALSE)
  {
    $account_id = $this->CI->session->userdata('account_id');
    
    $this->CI->load->model('account/Acl_role_model');
    
    $account_roles = $this->CI->Acl_role_model->get_by_account_id($account_id);
    
    // Loop through and check if the account 
    // has any of the permission keys supplied
    if (isset($roles))
    {
      foreach ($account_roles as $perm) 
      {
        // Array of permission keys
        if (is_array($roles))
        {
          foreach($roles as $role) 
          {
            // Return if only a single one is required.
            if(strtolower($perm->name) == strtolower($role) && ! $require_all ) 
            {
              return TRUE;
            } 
            // Only takes one bad apple
            elseif(strtolower($perm->name) != strtolower($role) && $require_all)
            {
              return FALSE;
            }
          }
        }
        // Single permission key
        else
        {
          // Return if only a single one is required.
          if (strtolower($perm->name) == strtolower($roles) && ! $require_all ) 
          {
            return TRUE;
          }
          // Only takes one bad apple
          elseif (strtolower($perm->name) != strtolower($roles) && $require_all) 
          {
            return FALSE;
          }
        }
      }
    }
  }
}
/* End of file Authorization.php */
/* Location: ./application/account/libraries/Authorization.php */
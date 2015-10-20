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
   * Array of all the permissions so that they don't have to be constantly loaded from the database
   * @var array
   */
  private $_account_permissions_cache = array();

  /**
   * Constructor
   */
  function __construct()
  {
    // Obtain a reference to the ci super object
    $this->CI =& get_instance();

    $this->CI->load->driver('session');

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

    if (isset($this->_account_permissions_cache[$account_id]))
    {
        $account_permissions = $this->_account_permissions_cache[$account_id];
    }
    else
    {
        $account_permissions = array();
        $permissions = $this->CI->Acl_permission_model->get_by_account_id($account_id);
        foreach ($permissions as $perm)
        {
            $account_permissions[] = $perm->key;
        }
        $this->_account_permissions_cache[$account_id] = $account_permissions;
    }

    // Loop through and check if the account
    // has any of the permission keys supplied
    if (isset($permission_keys))
    {
        if ( ! is_array($permission_keys))
        {
            $permission_keys = array($permission_keys);
        }

        $permitted = array_intersect($permission_keys, $account_permissions);
        if ($require_all)
        {
            return count($permitted) == count($permission_keys);
        }
        else
        {
            return count($permitted) > 0;
        }

    }

    return FALSE;
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

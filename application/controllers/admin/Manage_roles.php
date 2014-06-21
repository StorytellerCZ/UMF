<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Manage roles
 * @package A3M
 * @subpackage Controllers
 */
class Manage_roles extends CI_Controller {

  /**
   * Constructor
   */
  function __construct()
  {
    parent::__construct();

    // Load the necessary stuff...
    $this->load->helper(array('date', 'language', 'account/ssl', 'url'));
    $this->load->library(array('account/authentication', 'account/authorization', 'form_validation'));
    $this->load->model(array('account/Account_model', 'account/Account_details_model', 'account/Acl_permission_model', 'account/Acl_role_model', 'account/Rel_account_permission_model', 'account/Rel_account_role_model', 'account/Rel_role_permission_model'));
    $this->load->language(array('general', 'admin/manage_roles', 'account/account_settings', 'account/account_profile', 'account/sign_up', 'account/account_password'));
  }

  /**
   * Roles overview
   */
  function index()
  {
    $data = $this->authentication->initialize(TRUE, 'admin/manage_roles', NULL, 'retrieve_roles');

    // Get all permossions, roles, and role_permissions
    $roles = $this->Acl_role_model->get();
    $permissions = $this->Acl_permission_model->get();
    $role_permissions = $this->Rel_role_permission_model->get();

    // Combine all these elements for display
    $data['roles'] = array();
    foreach( $roles as $role )
    {
      $current_role = array();
      $current_role['id'] = $role->id;
      $current_role['name'] = $role->name;
      $current_role['description'] = $role->description;
      $current_role['perm_list'] = array();
      $current_role['user_count'] = $this->Acl_role_model->get_user_count($role->id);
      $current_role['is_disabled'] = isset( $role->suspendedon );

      foreach( $role_permissions as $rperm )
      {
        if( $rperm->role_id == $role->id )
        {
          foreach( $permissions as $perm )
          {
            if( $rperm->permission_id == $perm->id )
            {
              $current_role['perm_list'][] = array(
                'id' => $perm->id, 
                'key' => $perm->key,
                'title' => $perm->description );
            }
          }
        }
      }

      $data['roles'][] = $current_role;
    }


    // Load manage roles view
    $data['content'] = $this->load->view('admin/manage_roles', $data, true);
    $this->load->view('template', $data);
  }


  /**
   * Create/Edit role
   *
   * If role if is passed in, it will allow to edit the given role.
   * If NULL, then it will show form to create a new role.
   *
   * @param int $id Role ID
   * 
   */
  function save($id = NULL)
  {
     // Keep track if this is a new role
    $is_new = empty($id);

    $data = $this->authentication->initialize(TRUE, 'admin/manage_roles');

    // Check if they are allowed to Update Roles
    if ( ! $this->authorization->is_permitted('update_roles') && ! empty($id) )
    {
      redirect('admin/manage_permissions');
    }

    // Check if they are allowed to Create Roles
    if ( ! $this->authorization->is_permitted('create_roles') && empty($id) )
    {
      redirect('admin/manage_permissions');
    }

    // Set action type (create or update role)
    $data['action'] = 'create';

    // Get all the permissions
    $data['permissions'] = $this->Acl_permission_model->get();

    // Is this a System Role?
    $data['is_system'] = FALSE;

    //Get the role
    if( ! $is_new )
    {
      $data['role'] = $this->Acl_role_model->get_by_id($id);
      $data['role_permissions'] = $this->Rel_role_permission_model->get_by_role_id($id);
      $data['action'] = 'update';
      $data['is_system'] = ($data['role']->is_system == 1);
    }

    // Retrieve sign in user
    $data['account'] = $this->Account_model->get_by_id($this->session->userdata('account_id'));

    // Setup form validation
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
    $this->form_validation->set_rules(
      array(
        array(
          'field' => 'role_name',
          'label' => 'lang:roles_name',
          'rules' => 'trim|required|max_length[80]'),
        array(
          'field' => 'role_description',
          'label' => 'lang:roles_description',
          'rules' => 'trim|max_length[160]')
      ));

    // Run form validation
    if ($this->form_validation->run())
    {
      $name_taken = $this->name_check($this->input->post('role_name', TRUE));

      if ( (! empty($id) && strtolower($this->input->post('role_name', TRUE)) != strtolower($data['role']->name) && $name_taken) || (empty($id) && $name_taken) )
      {
        $data['role_name_error'] = lang('roles_name_taken');
      }
      else
      {
        // Create/Update role
        $attributes = array();

        // Now allowed to update the Admin role name
        if( ! $data['is_system'] )
        {
          $attributes['name'] = $this->input->post('role_name', TRUE) ? $this->input->post('role_name', TRUE) : NULL;
        }

        $attributes['description'] = $this->input->post('role_description', TRUE) ? $this->input->post('role_description', TRUE) : NULL;
        $id = $this->Acl_role_model->update($id, $attributes);

        // Check if the user should be suspended
        if( $this->authorization->is_permitted('delete_roles') ) 
        {
          $permission_ban = $this->input->post('manage_role_ban', TRUE);
          if( $this->input->post('manage_role_ban', TRUE) ) 
          {
            $this->Acl_role_model->update_suspended_datetime($id);
          }
          elseif( $this->input->post('manage_role_unban', TRUE))
          {
            $this->Acl_role_model->remove_suspended_datetime($id);
          }
        }

        // Apply the checked permissions
        $perms = array();
        foreach( $data['permissions'] as $perm )
        {
          if( $this->input->post("role_permission_{$perm->id}", TRUE) )
          {
            $perms[] = $perm->id;
          }
        }
        $this->Rel_role_permission_model->delete_update_batch($id, $perms);

        redirect('admin/manage_roles'); 
      }
    }

    // Load manage roles view
    $data['content'] = $this->load->view('admin/manage_roles_save', $data, TRUE);
    $this->load->view('template', $data);
  }

  /**
   * Check if the role name exist
   *
   * @access public
   * @param string $role_name
   * @return bool
   */
  function name_check($role_name)
  {
    return $this->Acl_role_model->get_by_name($role_name) ? TRUE : FALSE;
  }
}
/* End of file Manage_roles.php */
/* Location: ./application/controllers/admin/Manage_roles.php */
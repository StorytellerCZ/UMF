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
 * Account Settings
 * @package A3M
 * @subpackage Controllers
 */
class Settings extends CI_Controller
{

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('date', 'language', 'account/ssl', 'url', 'account/photo'));
		$this->load->library(array('account/authentication', 'account/authorization', 'form_validation', 'account/gravatar'));
		$this->load->model(array('account/Account_model', 'account/Account_details_model', 'account/Ref_country_model', 'account/Ref_language_model', 'account/Ref_zoneinfo_model'));
		$this->load->language(array('general', 'account/account_settings'));
	}

	/**
	 * Page to change account settings and profile information
	 * @param string $action String from profile form to delete avatar
	 */
	function index($action = NULL)
	{
		$data = $this->authentication->initialize(TRUE, 'account/settings');
		$data['account_details'] = $this->Account_details_model->get_by_account_id($this->session->userdata('account_id'));

		// Retrieve countries, languages and timezones
		$data['countries'] = $this->Ref_country_model->get_all();
		$data['languages'] = $this->Ref_language_model->get_all();
		$data['zoneinfos'] = $this->Ref_zoneinfo_model->get_all();
		
		// Retrieve user's gravatar if available
		$data['gravatar'] = $this->gravatar->get_gravatar( $data['account']->email );
		
		// Delete profile picture
		if ($action == 'delete')
		{
			//check that the picture isn't from gravatar
			if(!isset($data['gravatar']))
			{
				// delete previous picture
				unlink(FCPATH.RES_DIR.'/user/settings/'.$data['account_details']->picture);
			}
			
			$this->Account_details_model->update($data['account']->id, array('picture' => NULL));
			redirect('account/profile');
		}
		
		// Split date of birth into month, day and year
		if ($data['account_details'] && $data['account_details']->dateofbirth)
		{
			$dateofbirth = strtotime($data['account_details']->dateofbirth);
			$data['account_details']->dob_month = mdate('%m', $dateofbirth);
			$data['account_details']->dob_day = mdate('%d', $dateofbirth);
			$data['account_details']->dob_year = mdate('%Y', $dateofbirth);
		}
		
		// Setup form validation
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
		
		if($this->input->post('form_type', TRUE) == 'settings')
		{
			$this->form_validation->set_rules(array(array('field' => 'settings_email', 'label' => 'lang:settings_email', 'rules' => 'trim|required|valid_email|max_length[160]'), array('field' => 'settings_firstname', 'label' => 'lang:settings_firstname', 'rules' => 'trim|max_length[80]'), array('field' => 'settings_lastname', 'label' => 'lang:settings_lastname', 'rules' => 'trim|max_length[80]')));
			
			// Run form validation
			if ($this->form_validation->run())
			{
				// If user is changing email and new email is already taken
				if (strtolower($this->input->post('settings_email', TRUE)) != strtolower($data['account']->email) && $this->email_check($this->input->post('settings_email', TRUE)) === TRUE)
				{
					$data['settings_email_error'] = lang('settings_email_exist');
				}
				// Detect incomplete birthday dropdowns
				elseif ( ! (($this->input->post('settings_dob_month') && $this->input->post('settings_dob_day') && $this->input->post('settings_dob_year')) || ( ! $this->input->post('settings_dob_month') && ! $this->input->post('settings_dob_day') && ! $this->input->post('settings_dob_year'))))
				{
					$data['settings_dob_error'] = lang('settings_dateofbirth_incomplete');
				}
				else
				{
					// Update account email
					$this->Account_model->update_email($data['account']->id, $this->input->post('settings_email', TRUE) ? $this->input->post('settings_email', TRUE) : NULL);
					
					// Update account details
					if ($this->input->post('settings_dob_month', TRUE) && 
						$this->input->post('settings_dob_day', TRUE) && 
						$this->input->post('settings_dob_year', TRUE)) $attributes['dateofbirth'] = mdate('%Y-%m-%d', strtotime($this->input->post('settings_dob_day', TRUE).'-'.$this->input->post('settings_dob_month', TRUE).'-'.$this->input->post('settings_dob_year', TRUE)));
						
					$attributes['firstname'] = $this->input->post('settings_firstname', TRUE) ? $this->input->post('settings_firstname', TRUE) : NULL;
					$attributes['lastname'] = $this->input->post('settings_lastname', TRUE) ? $this->input->post('settings_lastname', TRUE) : NULL;
					$attributes['gender'] = $this->input->post('settings_gender', TRUE) ? $this->input->post('settings_gender', TRUE) : NULL;
					$attributes['country'] = $this->input->post('settings_country', TRUE) ? $this->input->post('settings_country', TRUE) : NULL;
					$attributes['language'] = $this->input->post('settings_language', TRUE) ? $this->input->post('settings_language', TRUE) : NULL;
					$attributes['timezone'] = $this->input->post('settings_timezone', TRUE) ? $this->input->post('settings_timezone', TRUE) : NULL;
					$this->Account_details_model->update($data['account']->id, $attributes);
					
					$data['settings_info'] = lang('settings_details_updated');
				}
			}
		}
		elseif($this->input->post('form_type', TRUE) == 'profile')
		{
			$this->form_validation->set_rules(array(array('field' => 'profile_username', 'label' => 'lang:profile_username', 'rules' => 'trim|required|alpha_dash|min_length[2]|max_length[24]')));
			
			// Run form validation
			if ($this->form_validation->run())
			{
				// If user is changing username and new username is already taken
				if (strtolower($this->input->post('profile_username', TRUE)) != strtolower($data['account']->username) && $this->username_check($this->input->post('profile_username', TRUE)) === TRUE)
				{
					$data['profile_username_error'] = lang('profile_username_taken');
					$error = TRUE;
				}
				else
				{
					$data['account']->username = $this->input->post('profile_username', TRUE);
					$this->Account_model->update_username($data['account']->id, $this->input->post('profile_username', TRUE));
				}
				
				switch( $this->input->post('pic_selection') )
				{
					case "gravatar":
						$this->Account_details_model->update($data['account']->id, array('picture' => $data['gravatar']));
						redirect( current_url() );
					break;
					
					default:
						// If user has uploaded a file
						if (isset($_FILES['account_picture_upload']) && $_FILES['account_picture_upload']['error'] != 4)
						{
							// Load file uploading library - http://codeigniter.com/user_guide/libraries/file_uploading.html
							$this->load->library('upload', array('overwrite' => TRUE, 'upload_path' => FCPATH.RES_DIR.'/user/profile', 'allowed_types' => 'jpg|png|gif', 'max_size' => '800' // kilobytes
							));
	
							/// Try to upload the file
							if ( ! $this->upload->do_upload('account_picture_upload'))
							{
								$data['profile_picture_error'] = $this->upload->display_errors('', '');
								$error = TRUE;
							}
							else
							{
								// Get uploaded picture data
								$picture = $this->upload->data();
								
								// Create picture thumbnail - http://codeigniter.com/user_guide/libraries/image_lib.html
								$this->load->library('image_lib');
								$this->image_lib->clear();
								$this->image_lib->initialize(array('image_library' => 'gd2', 'source_image' => FCPATH.RES_DIR.'/user/profile/'.$picture['file_name'], 'new_image' => FCPATH.RES_DIR.'/user/profile/pic_'.md5($data['account']->id).$picture['file_ext'], 'maintain_ratio' => FALSE, 'quality' => '100%', 'width' => 100, 'height' => 100));
								
								// Try resizing the picture
								if ( ! $this->image_lib->resize())
								{
									$data['profile_picture_error'] = $this->image_lib->display_errors();
									$error = TRUE;
								}
								else
								{
									$data['account_details']->picture = 'pic_'.md5($data['account']->id).$picture['file_ext'];
									$this->Account_details_model->update($data['account']->id, array('picture' => $data['account_details']->picture));
								}
								
								// Delete original uploaded file
								unlink(FCPATH.RES_DIR.'/user/profile/'.$picture['file_name']);
								redirect( current_url() );
								
							}
						}
					break;
					
				} // end switch
				if ( ! isset($error)) $data['profile_info'] = lang('profile_updated');
			}
		}
		
		$data['content'] = $this->load->view('account/account_settings', $data, TRUE);
		$this->load->view('template', $data);
	}

	/**
	 * Check if an email exist
	 *
	 * @access public
	 * @param string $email
	 * @return bool
	 */
	function email_check($email)
	{
		return $this->Account_model->get_by_email($email) ? TRUE : FALSE;
	}
	
	/**
	 * Check if a username exist
	 *
	 * @access private
	 * @param string $username
	 * @return bool
	 */
	private function username_check($username)
	{
		return $this->Account_model->get_by_username($username) ? TRUE : FALSE;
	}
	
	/**
	* Public function for ajax calls for username checks
	*
	* @access public
	* @param string $username
	* @return boolean
	*/
	public function username_exists($username)
	{
		echo $this->username_check($username);
	}
}
/* End of file Settings.php */
/* Location: ./application/controllers/account/Settings.php */